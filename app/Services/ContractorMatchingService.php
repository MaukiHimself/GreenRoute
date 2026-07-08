<?php

namespace App\Services;

use App\Models\ContractorRoute;
use App\Models\User;

/**
 * Decides which contractor a self-registering client should be assigned to.
 *
 * Primary signal: coverage. A contractor "covers" an area if they have an
 * active route in that ward (falling back to district, then region). This is
 * inferred from the routes contractors already build - no extra setup needed.
 *
 * Tiebreak: when several contractors cover the same area, pick the one whose
 * base location (users.latitude/longitude) is nearest to the client's pin.
 */
class ContractorMatchingService
{
    /**
     * Resolve the best contractor for a client's location.
     *
     * @return array{contractor_id:int, route:ContractorRoute|null, level:string}|null
     *         null when no contractor covers the area (client -> admin queue).
     */
    public function match(?string $ward, ?string $district, ?string $region, $lat = null, $lng = null): ?array
    {
        // Try progressively broader coverage levels.
        foreach ([['ward', $ward], ['district', $district], ['region', $region]] as [$level, $value]) {
            if (empty($value)) {
                continue;
            }

            $routes = ContractorRoute::where('is_active', true)
                ->where($level, $value)
                ->get(['id', 'contractor_id', 'route_name', 'region', 'district', 'ward']);

            if ($routes->isEmpty()) {
                continue;
            }

            $chosen = $this->pickNearest($routes, $lat, $lng);

            return [
                'contractor_id' => (int) $chosen->contractor_id,
                'route'         => $chosen,
                'level'         => $level,
            ];
        }

        return null;
    }

    /**
     * From a set of candidate routes, choose the one whose contractor base is
     * nearest to the client's pin. Falls back to the first route (stable order)
     * when no client pin is provided or no candidate base has coordinates.
     */
    protected function pickNearest($routes, $lat, $lng): ContractorRoute
    {
        if ($lat === null || $lng === null || $lat === '' || $lng === '') {
            return $routes->first();
        }

        // Base coordinates for each candidate contractor (users table).
        $contractorIds = $routes->pluck('contractor_id')->unique()->all();
        $bases = User::whereIn('id', $contractorIds)
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->pluck('latitude', 'id'); // id => lat (we fetch lng separately)
        $lngs = User::whereIn('id', $contractorIds)
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->pluck('longitude', 'id');

        $best = null;
        $bestDistance = INF;

        foreach ($routes as $route) {
            $cid = $route->contractor_id;
            if (! isset($bases[$cid], $lngs[$cid])) {
                continue; // no base coords -> can't measure distance
            }
            $d = $this->haversine((float) $lat, (float) $lng, (float) $bases[$cid], (float) $lngs[$cid]);
            if ($d < $bestDistance) {
                $bestDistance = $d;
                $best = $route;
            }
        }

        return $best ?? $routes->first();
    }

    /**
     * Great-circle distance in kilometres.
     */
    protected function haversine(float $lat1, float $lng1, float $lat2, float $lng2): float
    {
        $earthRadius = 6371; // km
        $dLat = deg2rad($lat2 - $lat1);
        $dLng = deg2rad($lng2 - $lng1);
        $a = sin($dLat / 2) ** 2
            + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLng / 2) ** 2;
        return $earthRadius * 2 * atan2(sqrt($a), sqrt(1 - $a));
    }
}
