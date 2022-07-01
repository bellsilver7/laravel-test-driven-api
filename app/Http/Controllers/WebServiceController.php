<?php

namespace App\Http\Controllers;

use App\Models\WebService;
use Google\Client;
use Illuminate\Http\Request;

class WebServiceController extends Controller
{
    public const DRIVE_SCOPES = [
        'https://www.googleapis.com/auth/drive',
        'https://www.googleapis.com/auth/drive.file',
    ];

    public function connect($name)
    {
        if ($name === 'google-drive') {

            $config = config('services.google');

            $client = new Client();
            $client->setClientId($config['id']);
            $client->setClientSecret($config['secret']);
            $client->setRedirectUri($config['redirect_uri']);
            $client->setScopes(self::DRIVE_SCOPES);
            $url = $client->createAuthUrl();

            return response(['url' => $url]);
        }
    }

    public function callback(Request $request)
    {
        $config = config('services.google');

        $client = new Client();
        $client->setClientId($config['id']);
        $client->setClientSecret($config['secret']);
        $client->setRedirectUri($config['redirect_uri']);
        $access_token = $client->fetchAccessTokenWithAuthCode($request->code);

        $service = WebService::create([
            'user_id' => auth()->id(),
            'token' => json_encode(['access_token' => $access_token]),
            'name' => 'google-drive'
        ]);
        return $service;
    }
}
