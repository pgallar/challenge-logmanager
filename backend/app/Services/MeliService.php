<?php

namespace App\Services;

use App\Models\Account;
use App\Models\Token;
use Carbon\Carbon;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Log;

class MeliService
{
    protected $client;

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => 'https://api.mercadolibre.com/',
        ]);
    }

    public function saveToken($accountId, $access_token, $refresh_token, $expires_in)
    {
        $token = Token::where('account_id', $accountId)->firstOrNew();
        $token->account_id = $accountId;
        $token->access_token = $access_token;
        $token->refresh_token = $refresh_token;
        $token->expires_in = $expires_in;
        $token->expires_at = Carbon::now()->addSeconds($expires_in)->toDateTimeString();

        $token->save();

        return $token;
    }

    public function getToken($accountId)
    {
        $token = Token::where('account_id', $accountId)->first();
        $account = Account::findOrFail($accountId)->first();

        if (isset($token)) {
            if ($token->isExpired()) {
                $refresh_token = $token->refresh_token;
                $grant_type = 'refresh_token';

                $response = $this->client->request('POST', '/oauth/token', [
                    'form_params' => [
                        'grant_type' => $grant_type,
                        'client_id' => $account->client_id,
                        'client_secret' => $account->client_secret,
                        'refresh_token' => $refresh_token,
                    ]
                ]);
            }
        } else {
            $response = $this->client->request('POST', '/oauth/token', [
                'form_params' => [
                    'grant_type' => 'authorization_code',
                    'client_id' => $account->client_id,
                    'client_secret' => $account->client_secret,
                    'response_type' => 'code',
                    'code' => $account->code,
                    'redirect_uri' => "http://localhost:8000/accounts/callback/$account->short_name"
                ]
            ]);
        }

        if (isset($response)) {
            $body = json_decode((string) $response->getBody(), true);

            $token = $this->saveToken($accountId, $body['access_token'], $body['refresh_token'], $body['expires_in']);
        }

        return $token->access_token;
    }

    public function getOrder($accountId, $orderId)
    {
        try {
            $token = $this->getToken($accountId);
            $response = $this->client->request('GET', "orders/{$orderId}", [
                'headers' => [
                    'Authorization' => "Bearer {$token}",
                ],
            ]);

            return json_decode($response->getBody()->getContents(), true);
        } catch (RequestException $e) {
            Log::error($e->getMessage());
            return null;
        }
    }

    public function getUser($accountId)
    {
        try {
            $token = $this->getToken($accountId);
            $response = $this->client->request('GET', "users/me", [
                'headers' => [
                    'Authorization' => "Bearer {$token}",
                ],
            ]);

            return json_decode($response->getBody()->getContents(), true);
        } catch (RequestException $e) {
            Log::error($e->getMessage());
            return null;
        }
    }

    public function activate($shortName, $code)
    {
        $account = Account::where('short_name', $shortName)->firstOrFail();
        $account->code = $code;
        $account->save();

        $userData = $this->getUser($account->id);

        if (!isset($userData)) {
            throw new \Exception("Error on get token");
        }

        $account->activated = true;
        $account->user_id = $userData['id'];
        $account->save();
    }
}
