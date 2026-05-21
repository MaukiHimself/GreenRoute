<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use App\Models\Location;
use App\Services\LocationService;
use App\Http\Resources\LocationResource;

class LocationController extends Controller
{
    /**
     * Location service instance
     */
    protected $locationService;

    /**
     * Create a new controller instance.
     */
    public function __construct(LocationService $locationService)
    {
        $this->locationService = $locationService;
    }

    /**
     * Reverse geocode coordinates to get address
     */
    public function reverseGeocode(Request $request)
    {
        $request->validate([
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180'
        ]);

        $lat = $request->latitude;
        $lng = $request->longitude;

        $cacheKey = 'reverse_geocode_' . round((float) $lat, 5) . '_' . round((float) $lng, 5);

        try {
            $result = Cache::remember($cacheKey, 3600, function () use ($lat, $lng) {
                $response = Http::timeout(10)
                    ->withHeaders([
                        'User-Agent' => config('services.openstreetmap.nominatim_user_agent'),
                        'Accept' => 'application/json',
                    ])
                    ->get('https://nominatim.openstreetmap.org/reverse', [
                        'format' => 'json',
                        'lat' => $lat,
                        'lon' => $lng,
                        'accept-language' => 'en',
                    ]);

                if (!$response->successful()) {
                    return null;
                }

                $data = $response->json();

                if (empty($data['display_name'])) {
                    return null;
                }

                return [
                    'address' => $data['display_name'],
                    'components' => $data['address'] ?? [],
                ];
            });

            if ($result) {
                return response()->json([
                    'success' => true,
                    'address' => $result['address'],
                    'components' => $result['components'],
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Reverse geocoding failed', [
                'latitude' => $lat,
                'longitude' => $lng,
                'error' => $e->getMessage(),
            ]);
        }

        return $this->generateFallbackAddress($lat, $lng);
    }

    /**
     * Generate a fallback address when reverse geocoding is not available
     */
    private function generateFallbackAddress($lat, $lng)
    {
        // Basic location-based address generation for Tanzania
        $address = "GPS Location: {$lat}, {$lng}";
        
        // Check if coordinates are in known areas
        if ($lat >= -3.5 && $lat <= -3.2 && $lng >= 37.2 && $lng <= 37.4) {
            $address = "Moshi, Kilimanjaro Region, Tanzania (GPS: {$lat}, {$lng})";
        } elseif ($lat >= -6.9 && $lat <= -6.7 && $lng >= 39.1 && $lng <= 39.3) {
            $address = "Dar es Salaam, Tanzania (GPS: {$lat}, {$lng})";
        } elseif ($lat >= -11.7 && $lat <= -0.95 && $lng >= 29.3 && $lng <= 40.5) {
            $address = "Tanzania (GPS: {$lat}, {$lng})";
        }

        return response()->json([
            'success' => true,
            'address' => $address,
            'fallback' => true
        ]);
    }

    /**
     * Validate location accuracy for registration
     */
    public function validateLocationAccuracy(Request $request)
    {
        $request->validate([
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'accuracy' => 'nullable|numeric'
        ]);

        $lat = (float) $request->latitude;
        $lng = (float) $request->longitude;

        // Tanzania bounds
        $inTanzania = ($lat >= -11.7 && $lat <= -0.95 && $lng >= 29.3 && $lng <= 40.5);
        
        // Moshi area bounds (more specific)
        $inMoshi = ($lat >= -3.5 && $lat <= -3.2 && $lng >= 37.2 && $lng <= 37.4);

        return response()->json([
            'in_tanzania' => $inTanzania,
            'in_moshi' => $inMoshi,
            'accuracy' => $request->accuracy
        ]);
    }

    /**
     * Get all regions from tbl_locations (with caching)
     */
    public function getRegions()
    {
        $regions = $this->locationService->getRegions();
            
        return response()->json([
            'success' => true,
            'data' => $regions,
            'cached' => true
        ]);
    }
    
    /**
     * Get districts for a specific region (with caching)
     */
    public function getDistricts(Request $request)
    {
        $request->validate([
            'region' => 'required|string'
        ]);

        $districts = $this->locationService->getDistricts($request->region);
            
        return response()->json([
            'success' => true,
            'data' => $districts,
            'cached' => true
        ]);
    }
    
    /**
     * Get wards for a specific district (with caching)
     */
    public function getWards(Request $request)
    {
        $request->validate([
            'region' => 'required|string',
            'district' => 'required|string'
        ]);

        $wards = $this->locationService->getWards($request->region, $request->district);
            
        return response()->json([
            'success' => true,
            'data' => $wards,
            'cached' => true
        ]);
    }
    
    /**
     * Get streets for a specific ward (with caching)
     */
    public function getStreets(Request $request)
    {
        $request->validate([
            'region' => 'required|string',
            'district' => 'required|string',
            'ward' => 'required|string'
        ]);

        $streets = $this->locationService->getStreets(
            $request->region,
            $request->district,
            $request->ward
        );
            
        return response()->json([
            'success' => true,
            'data' => $streets,
            'cached' => true
        ]);
    }

    /**
     * Search locations by keyword with pagination
     */
    public function searchLocations(Request $request)
    {
        $keyword = $request->query('q', '');
        $limit = min((int)$request->query('limit', 20), 100); // Max 100 results
        
        if (empty($keyword)) {
            return response()->json([
                'success' => false,
                'message' => 'Search keyword is required'
            ], 400);
        }
        
        if (strlen($keyword) < 2) {
            return response()->json([
                'success' => false,
                'message' => 'Search keyword must be at least 2 characters'
            ], 400);
        }
        
        $results = $this->locationService->searchLocations($keyword, $limit);
        
        return response()->json([
            'success' => true,
            'data' => $results,
            'count' => count($results),
            'keyword' => $keyword
        ]);
    }
    
    /**
     * Quick diagnostic check for locations
     */
    public function diagnostics()
    {
        try {
            $count = Location::count();
            $sample = Location::limit(3)->get();
            
            return response()->json([
                'success' => true,
                'total_locations' => $count,
                'database_table' => 'tbl_locations',
                'sample_data' => $sample,
                'message' => $count > 0 ? 'Locations table is populated' : 'WARNING: Locations table is empty!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
                'message' => 'Database connection or table issue'
            ], 500);
        }
    }
    
    /**
     * Autocomplete location search (fast, cached)
     */
    public function autocomplete(Request $request)
    {
        try {
            $query = $request->query('q', '');
            $type = $request->query('type', 'all'); // all, region, district, ward, street
            $limit = min((int)$request->query('limit', 10), 50);
            
            if (strlen($query) < 2) {
                return response()->json([
                    'success' => true,
                    'data' => [],
                    'count' => 0
                ]);
            }
            
            // Query database directly (no cache to avoid SQLite issues on Render)
            $queryBuilder = Location::query();
            
            switch ($type) {
                case 'region':
                    $results = $queryBuilder->select('region as value')
                        ->where('region', 'LIKE', "{$query}%")
                        ->distinct()
                        ->limit($limit)
                        ->pluck('value');
                    break;
                        
                case 'district':
                    $results = $queryBuilder->select('district as value')
                        ->where('district', 'LIKE', "{$query}%")
                        ->distinct()
                        ->limit($limit)
                        ->pluck('value');
                    break;
                        
                case 'ward':
                    $results = $queryBuilder->select('ward as value')
                        ->where('ward', 'LIKE', "{$query}%")
                        ->distinct()
                        ->limit($limit)
                        ->pluck('value');
                    break;
                        
                case 'street':
                    $results = $queryBuilder->select('street as value')
                        ->whereNotNull('street')
                        ->where('street', '!=', '')
                        ->where('street', 'LIKE', "{$query}%")
                        ->distinct()
                        ->limit($limit)
                        ->pluck('value');
                    break;
                        
                default: // all
                    $results = $queryBuilder->where(function($q) use ($query) {
                        $q->where('region', 'LIKE', "{$query}%")
                          ->orWhere('district', 'LIKE', "{$query}%")
                          ->orWhere('ward', 'LIKE', "{$query}%")
                          ->orWhere('street', 'LIKE', "{$query}%");
                    })
                    ->limit($limit)
                    ->get()
                    ->map(function($location) {
                        return [
                            'value' => implode(' → ', array_filter([
                                $location->region,
                                $location->district,
                                $location->ward,
                                $location->street
                            ])),
                            'region' => $location->region,
                            'district' => $location->district,
                            'ward' => $location->ward,
                            'street' => $location->street,
                        ];
                    });
                    break;
            }
            
            return response()->json([
                'success' => true,
                'data' => $results,
                'count' => count($results)
            ]);
            
        } catch (\Exception $e) {
            Log::error('Location autocomplete error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to load locations',
                'error' => config('app.debug') ? $e->getMessage() : 'An error occurred'
            ], 500);
        }
    }

    /**
     * Get location statistics
     */
    public function getStatistics()
    {
        $stats = $this->locationService->getStatistics();
            
        return response()->json([
            'success' => true,
            'data' => $stats
        ]);
    }

    /**
     * Validate a location exists
     */
    public function validateLocation(Request $request)
    {
        $request->validate([
            'region' => 'required|string',
            'district' => 'required|string',
            'ward' => 'required|string',
            'street' => 'nullable|string'
        ]);

        $exists = $this->locationService->validateLocation(
            $request->region,
            $request->district,
            $request->ward,
            $request->street
        );
            
        return response()->json([
            'success' => true,
            'exists' => $exists
        ]);
    }
}