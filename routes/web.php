<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/drive', function () {
    $client = new Google\Client();
    $client->setClientId(env('GOOGLE_CLIENT_ID'));
    $client->setClientSecret(env('GOOGLE_CLIENT_SECRET'));
    $client->setRedirectUri('http://localhost:8000/google-drive/callback');
    $client->setScopes([
        'https://www.googleapis.com/auth/drive',
        'https://www.googleapis.com/auth/drive.file',
    ]);
    $url = $client->createAuthUrl();
    return redirect($url);
});

Route::get('/google-drive/callback', function () {
    $client = new Google\Client();
    $client->setClientId(env('GOOGLE_CLIENT_ID'));
    $client->setClientSecret(env('GOOGLE_CLIENT_SECRET'));
    $client->setRedirectUri('http://localhost:8000/google-drive/callback');
    $code = request('code');
    $access_token = $client->fetchAccessTokenWithAuthCode($code);
    return $access_token;
});

Route::get('/upload', function () {
    try {
        $client = new Google\Client();
        $access_token = '{access_token}';
        $client->setAccessToken($access_token);
        $service = new Google\Service\Drive($client);
        $file = new Google\Service\Drive\DriveFile();

        DEFINE('TESTFILE', 'testfile-small.txt');
        if (!file_exists(TESTFILE)) {
            $fh = fopen(TESTFILE, 'w');
            fseek($fh, 1024 * 1024);
            fwrite($fh, '!', 1);
            fclose($fh);
        }

        $file->setName('Hello World!');
        $service->files->create(
            $file,
            [
                'data' => file_get_contents(TESTFILE),
                'mimeType' => 'application/octet-stream',
                'uploadType' => 'multipart'
            ]
        );
    } catch (\Exception $e) {
        echo $e->getMessage();
    }
});
