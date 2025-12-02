<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class AzamPayService
{
    protected $baseUrl;
    protected $authUrl;
    protected $clientId;
    protected $clientSecret;
    protected $appName;
    protected $apiKey;
    protected $isSandbox;

    public function __construct()
    {
        // Handle sandbox as string or boolean
        $sandbox = config('services.azampay.sandbox', true);
        $this->isSandbox = filter_var($sandbox, FILTER_VALIDATE_BOOLEAN);
        
        $this->baseUrl = $this->isSandbox 
            ? 'https://sandbox.azampay.co.tz' 
            : 'https://checkout.azampay.co.tz';
            
        $this->authUrl = $this->isSandbox 
            ? 'https://authenticator-sandbox.azampay.co.tz' 
            : 'https://authenticator.azampay.co.tz';

        $this->clientId = config('services.azampay.client_id');
        $this->clientSecret = config('services.azampay.client_secret');
        $this->appName = config('services.azampay.app_name');
        $this->apiKey = config('services.azampay.api_key');
    }

    /**
     * Generate or retrieve Bearer Token
     */
    public function getToken()
    {
        // Clear any cached failed attempts first
        if (!Cache::has('azampay_token_valid')) {
            Cache::forget('azampay_token');
        }

        return Cache::remember('azampay_token', 3000, function () {
            // Log what we're sending for debugging
            Log::info('AzamPay Auth Attempt', [
                'authUrl' => $this->authUrl,
                'appName' => $this->appName,
                'clientId' => $this->clientId ? 'SET' : 'MISSING',
                'clientSecret' => $this->clientSecret ? 'SET (length: ' . strlen($this->clientSecret) . ')' : 'MISSING',
            ]);

            $response = Http::post("{$this->authUrl}/AppRegistration/GenerateToken", [
                'appName' => $this->appName,
                'clientId' => $this->clientId,
                'clientSecret' => $this->clientSecret,
            ]);

            if ($response->successful()) {
                $data = $response->json();
                if (isset($data['data']['accessToken'])) {
                    Cache::put('azampay_token_valid', true, 3000);
                    return $data['data']['accessToken'];
                }
            }

            Log::error('AzamPay Token Error', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);
            throw new \Exception('Failed to authenticate with AzamPay: ' . $response->body());
        });
    }

    /**
     * Mobile Money Checkout (MNO)
     */
    public function mobileCheckout($accountNumber, $amount, $transactionId, $provider)
    {
        $token = $this->getToken();
        
        $payload = [
            'accountNumber' => $accountNumber,
            'amount' => (string) $amount,
            'currency' => 'TZS',
            'externalId' => $transactionId,
            'provider' => $provider, // Airtel, Tigo, Halopesa, Azampesa
        ];

        Log::info('AzamPay MNO Checkout Request', $payload);

        $response = Http::withToken($token)
            ->withHeaders([
                'X-API-KEY' => $this->apiKey,
                'Content-Type' => 'application/json'
            ])
            ->post("{$this->baseUrl}/azampay/mno/checkout", $payload);

        $result = $response->json();
        
        Log::info('AzamPay MNO Checkout Response', [
            'status' => $response->status(),
            'body' => $result
        ]);

        // Normalize response
        if ($response->successful() && isset($result['success']) && $result['success'] === true) {
            return $result;
        }

        // Return error with message
        return [
            'success' => false,
            'message' => $result['message'] ?? $result['error'] ?? $response->body() ?? 'Payment request failed'
        ];
    }

    /**
     * Bank Checkout (Redirect)
     */
    public function bankCheckout($amount, $transactionId, $redirectUrl)
    {
        $token = $this->getToken();
        
        // For bank card checkout, use the checkout endpoint with cart items
        $payload = [
            'appName' => $this->appName,
            'clientId' => $this->clientId,
            'vendorId' => $this->clientId, // Use client ID as vendor ID
            'language' => 'en',
            'currency' => 'TZS',
            'externalId' => $transactionId,
            'requestOrigin' => $redirectUrl,
            'redirectFailURL' => $redirectUrl,
            'redirectSuccessURL' => $redirectUrl,
            'vendorName' => $this->appName,
            'amount' => (string) $amount,
            'cart' => [
                'items' => [
                    [
                        'name' => 'Invoice Payment'
                    ]
                ]
            ]
        ];

        Log::info('AzamPay Bank Checkout Request', $payload);

        $response = Http::withToken($token)
            ->withHeaders([
                'X-API-KEY' => $this->apiKey,
                'Content-Type' => 'application/json'
            ])
            ->post("{$this->baseUrl}/AppRegistration/RegisterCheckout", $payload);

        $result = $response->json();
        
        Log::info('AzamPay Bank Checkout Response', [
            'status' => $response->status(),
            'body' => $result
        ]);

        // Normalize response - check various response structures
        if ($response->successful()) {
            // Check if response has data wrapper
            if (isset($result['data']) && is_array($result['data'])) {
                $data = $result['data'];
                // Look for redirect URL in various possible fields
                $redirectUrl = $data['redirectUrl'] ?? $data['redirect_url'] ?? $data['url'] ?? $data['checkoutUrl'] ?? null;
                
                if ($redirectUrl) {
                    return [
                        'success' => true,
                        'redirectUrl' => $redirectUrl,
                        'transactionId' => $data['transactionId'] ?? $data['reference'] ?? null
                    ];
                }
            }
            
            // Check if redirect URL is at root level
            $redirectUrl = $result['redirectUrl'] ?? $result['redirect_url'] ?? $result['url'] ?? $result['checkoutUrl'] ?? null;
            if ($redirectUrl) {
                return [
                    'success' => true,
                    'redirectUrl' => $redirectUrl,
                    'transactionId' => $result['transactionId'] ?? $result['reference'] ?? null
                ];
            }
        }

        // Return error with detailed message
        $errorMessage = $result['message'] ?? $result['error'] ?? $result['statusMessage'] ?? null;
        
        // If no message, try to extract from nested data
        if (!$errorMessage && isset($result['data']['message'])) {
            $errorMessage = $result['data']['message'];
        }
        
        // Log detailed error for debugging
        Log::error('AzamPay Bank Checkout Failed', [
            'status' => $response->status(),
            'result' => $result
        ]);
        
        return [
            'success' => false,
            'message' => $errorMessage ?? 'Bank checkout initialization failed. Please contact support.'
        ];
    }
}
