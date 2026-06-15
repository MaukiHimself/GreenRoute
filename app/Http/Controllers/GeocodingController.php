<?php

namespace App\Http\Controllers;

use App\Jobs\GeocodeClientJob;
use App\Models\Client;
use App\Models\GeocodeCache;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GeocodingController extends Controller
{
    private const NOMINATIM_URL = 'https://nominatim.openstreetmap.org/search';

    public function geocodeClient(Request $request, Client $client)
    {
        $this->ensureClientBelongsToAuthenticatedContractor($client);

        $result = $this->geocodeClientAddress($client);

        if ($result['success']) {
            $client->forceFill([
                'latitude' => $result['data']['latitude'],
                'longitude' => $result['data']['longitude'],
            ])->save();

            session()->flash('success', 'Client geocoded successfully.');
        } else {
            session()->flash('error', $result['message']);
        }

        return response()->json($result, $result['status']);
    }

    public function geocodeBulk(Request $request)
    {
        $query = Client::query()->where(function ($query) {
            $query->whereNull('latitude')->orWhereNull('longitude');
        });

        if (! Auth::user()?->isAdmin()) {
            $query->where('contractor_id', Auth::id());
        }

        $clientIds = $query->pluck('id');

        if ($clientIds->isEmpty()) {
            return response()->json([
                'success' => true,
                'message' => 'No clients need geocoding.',
                'queued_jobs' => 0,
            ]);
        }

        $jobs = $clientIds->map(fn ($id) => new GeocodeClientJob((int) $id))->all();

        $batch = Bus::batch($jobs)
            ->name('Geocode clients without coordinates')
            ->dispatch();

        return response()->json([
            'success' => true,
            'message' => 'Geocoding jobs queued successfully.',
            'batch_id' => $batch->id,
            'queued_jobs' => $clientIds->count(),
        ]);
    }

    private function geocodeClientAddress(Client $client): array
    {
        $address = $this->normalizeAddress($client);

        if ($address === '') {
            return [
                'success' => false,
                'message' => 'Client address is empty.',
                'data' => $this->coordinatesPayload(null, null),
                'status' => 422,
            ];
        }

        $addressHash = $this->addressHash($address);
        $cached = GeocodeCache::where('address_hash', $addressHash)->first();

        if ($cached) {
            return [
                'success' => true,
                'message' => 'Client geocoded from cache.',
                'data' => $this->coordinatesPayload($cached->latitude, $cached->longitude),
                'cached' => true,
                'status' => 200,
            ];
        }

        try {
            $response = Http::timeout(20)
                ->withHeaders([
                    'User-Agent' => config('services.openstreetmap.nominatim_user_agent', 'GreenRoute/1.0'),
                    'Accept' => 'application/json',
                ])
                ->get(self::NOMINATIM_URL, [
                    'q' => $address,
                    'format' => 'json',
                    'limit' => 1,
                ]);
        } catch (\Throwable $e) {
            Log::error('Nominatim geocoding request failed', [
                'client_id' => $client->id,
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'message' => 'Geocoding service unavailable.',
                'data' => $this->coordinatesPayload(null, null),
                'status' => 502,
            ];
        }

        if (! $response->successful()) {
            Log::warning('Nominatim geocoding returned an unsuccessful response', [
                'client_id' => $client->id,
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            return [
                'success' => false,
                'message' => 'Geocoding service returned an error.',
                'data' => $this->coordinatesPayload(null, null),
                'status' => 502,
            ];
        }

        $results = $response->json();

        if (! is_array($results) || empty($results[0])) {
            return [
                'success' => false,
                'message' => 'No geocoding result found for this address.',
                'data' => $this->coordinatesPayload(null, null),
                'status' => 404,
            ];
        }

        $latitude = filter_var($results[0]['lat'] ?? null, FILTER_VALIDATE_FLOAT);
        $longitude = filter_var($results[0]['lon'] ?? null, FILTER_VALIDATE_FLOAT);

        if ($latitude === false || $longitude === false) {
            Log::warning('Nominatim returned invalid coordinates', [
                'client_id' => $client->id,
                'result' => $results[0],
            ]);

            return [
                'success' => false,
                'message' => 'Geocoding result contained invalid coordinates.',
                'data' => $this->coordinatesPayload(null, null),
                'status' => 422,
            ];
        }

        $latitude = (float) $latitude;
        $longitude = (float) $longitude;

        GeocodeCache::updateOrCreate(
            ['address_hash' => $addressHash],
            [
                'address' => $address,
                'latitude' => $latitude,
                'longitude' => $longitude,
                'geocoded_at' => now(),
            ]
        );

        return [
            'success' => true,
            'message' => 'Client geocoded successfully.',
            'data' => $this->coordinatesPayload($latitude, $longitude),
            'cached' => false,
            'status' => 200,
        ];
    }

    private function normalizeAddress(Client $client): string
    {
        $address = trim(implode(', ', array_filter([
            $client->address,
            $client->city,
            $client->state,
            $client->zip_code,
        ], fn ($value) => $value !== null && trim((string) $value) !== '')));

        return preg_replace('/\s+/', ' ', $address) ?? $address;
    }

    private function addressHash(string $address): string
    {
        return hash('sha256', mb_strtolower($address));
    }

    private function coordinatesPayload(float|null $latitude, float|null $longitude): array
    {
        return [
            'latitude' => $latitude,
            'longitude' => $longitude,
        ];
    }

    private function ensureClientBelongsToAuthenticatedContractor(Client $client): void
    {
        if (Auth::user()?->isAdmin()) {
            return;
        }

        if ($client->contractor_id !== Auth::id()) {
            abort(403);
        }
    }
}
