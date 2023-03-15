<?php

namespace App\Services;

use App\Models\Token;
use Carbon\Carbon;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Log;

class MeliService
{
    protected $clientId;
    protected $clientSecret;
    protected $accessToken;
    protected $client;

    public function __construct()
    {
        $this->clientId = env('MELI_CLIENT_ID');
        $this->clientSecret = env('MELI_CLIENT_SECRET');
        $this->accessToken = env('MELI_ACCESS_TOKEN');
        $this->client = new Client([
            'base_uri' => 'https://api.mercadolibre.com/',
        ]);
    }

    public function saveToken($access_token, $refresh_token, $expires_in)
    {
        $token = new Token([
            'access_token' => $access_token,
            'refresh_token' => $refresh_token,
            'expires_in' => $expires_in,
            'expires_at' => Carbon::now()->addSeconds($expires_in),
        ]);

        $token->save();
    }

    public function getToken()
    {
        $token = Token::orderBy('created_at', 'desc')->first();

        if ($token->expires_at->isPast()) {
            $refresh_token = $token->refresh_token;
            $grant_type = 'refresh_token';

            $response = $this->client->request('POST', '/oauth/token', [
                'form_params' => [
                    'grant_type' => $grant_type,
                    'client_id' => $this->clientId,
                    'client_secret' => $this->clientSecret,
                    'refresh_token' => $refresh_token,
                ]
            ]);

            $body = json_decode((string) $response->getBody(), true);

            $this->saveToken($body['access_token'], $body['refresh_token'], $body['expires_in']);

            $token = Token::orderBy('created_at', 'desc')->first();
        }

        return $token->access_token;
    }

    public function getOrder($orderId)
    {
        try {
            $response = $this->client->request('GET', "orders/{$orderId}", [
                'headers' => [
                    'Authorization' => "Bearer {$this->getToken()}",
                ],
            ]);

            $order = json_decode($response->getBody()->getContents(), true);

            return $order;
        } catch (RequestException $e) {
            Log::error($e->getMessage());
            return null;
        }
    }

    public function getOrderItems($orderId)
    {
        try {
            $response = $this->client->request('GET', "orders/{$orderId}/items", [
                'headers' => [
                    'Authorization' => "Bearer {$this->getToken()}",
                ],
            ]);

            $items = json_decode($response->getBody()->getContents(), true);

            return $items;
        } catch (RequestException $e) {
            Log::error($e->getMessage());
            return null;
        }
    }
}
