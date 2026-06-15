<?php

namespace App\Jobs;

use App\Models\Client;
use App\Models\GeocodeCache;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GeocodeClientJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $clientId;

    public function __construct(int $clientId)
    {
        $this->clientId = $clientId;
    }

    public function handle(): void
    {
        $client = Client::find($this->clientId);

        if (! $client) {
            return;
        }

        if (! is_null($client->latitude) && ! is_null($client->longitude)) {
            return;
        }

        $result = $this->geocodeClientAddress($client);

        if (! $result['success']) {
            return;
        }

        $client->forceFill([
            'latitude' => $result['data']['latitude'],
            'longitude' => $result['data']['longitude'],
        ])->save();
    }

    private function geocodeClientAddress(Client $client): array
    {
        $address = $this->normalizeAddress($client);

        if ($address === '') {
            return [
                'success' => false,
                'message' => 'Client address is empty.',
                'data' => [
                    'latitude' => null,
                    'longitude' => null,
                ],
            ];
        }

        $addressHash = $this->addressHash($address);
        $cached = GeocodeCache::where('address_hash', $addressHash)->first();

        if ($cached) {
            return [
                'success' => true,
                'message' => 'Client geocoded from cache.',
                'data' => [
                    'latitude' => $cached->latitude,
                    'longitude' => $cached->longitude,
                ],
            ];
        }

        try {
            $response = Http::timeout(20)
                ->withHeaders([
                    'User-Agent' => config('services.openstreetmap.nominatim_user_agent', 'GreenRoute/1.0'),
                    'Accept' => 'application/json',
                ])
                ->get('https://nominatim.openstreetmap.org/search', [
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
                'data' => [
                    'latitude' => null,
                    'longitude' => null,
                ],
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
                'data' => [
                    'latitude' => null,
                    'longitude' => null,
                ],
            ];
        }

        $results = $response->json();

        if (! is_array($results) || empty($results[0])) {
            return [
                'success' => false,
                'message' => 'No geocoding result found for this address.',
                'data' => [
                    'latitude' => null,
                    'longitude' => null,
                ],
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
                'data' => [
                    'latitude' => null,
                    'longitude' => null,
                ],
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
            'data' => [
                'latitude' => $latitude,
                'longitude' => $longitude,
            ],
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
}
