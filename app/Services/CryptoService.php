<?php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Cache;

class CryptoService
{
    protected $client;

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => 'https://api.coingecko.com/api/v3/',
        ]);
    }

    public function getPrices($perPage = 100)
    {
        return Cache::remember('crypto_prices', 60, function () use ($perPage) {
            try {
                // Make a request to CoinGecko API with the API key
                $response = $this->client->get('coins/markets', [
                    'headers' => [
                        'Authorization' => 'Bearer ' . env('COINGECKO_API_KEY', 'CG-PPGDkVdBctELev3X8oKn5iBm')  // Use the API key here
                    ],
                    'query' => [
                        'vs_currency' => 'usd',
                        'order' => 'market_cap_desc',
                        'per_page' => $perPage,
                        'page' => 1,
                        'sparkline' => false,
                    ],
                ]);

                return json_decode($response->getBody(), true);
            } catch (\Exception $e) {
                return []; // Return an empty array on failure
            }
        });
    }
}
