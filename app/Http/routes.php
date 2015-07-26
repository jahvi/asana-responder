<?php

use App\Oauth2\Client\Provider\Asana as AsanaProvider;
use App\User;
use GuzzleHttp\Client;
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

$app->get('/', function() {
    if (Auth::check()) {
        return view('dashboard');
    }

    return view('home');
});

$app->post('/', ['middleware' => 'auth', function(Request $request) {
    $user = Auth::user();

    $user->message = $request->input('message');

    $user->save();

    return redirect('/')->withMessage('Message saved successfully');
}]);

$app->get('logout', ['middleware' => 'auth', function() {
    Auth::logout();
    return redirect('/');
}]);

$app->get('authenticate', function(Request $request, AsanaProvider $provider) {
    if (!$request->has('code')) {
        $authUrl = $provider->getAuthorizationUrl();
        return redirect()->to($authUrl);
    }

    $token = $provider->getAccessToken('authorization_code', [
        'code' => $request->input('code')
    ]);

    $userDetails = $provider->getUserDetails($token);

    $user = User::whereAsanaId($userDetails->uid)->first();

    if (!$user) {
        $user = new User;
    }

    $user->fill([
        'name'         => $userDetails->name,
        'email'        => $userDetails->email,
        'image_url'    => $userDetails->imageUrl,
        'asana_id'     => $userDetails->uid,
        'access_token' => $token,
    ]);

    $user->save();

    Auth::login($user);

    return redirect('/');
});
