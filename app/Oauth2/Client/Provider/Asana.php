<?php

namespace App\Oauth2\Client\Provider;

use League\OAuth2\Client\Entity\User;
use League\OAuth2\Client\Token\AccessToken;
use League\OAuth2\Client\Provider\AbstractProvider;

class Asana extends AbstractProvider
{
    public $authorizationHeader = 'Bearer';

    public function urlAuthorize()
    {
        return 'https://app.asana.com/-/oauth_authorize';
    }

    public function urlAccessToken()
    {
        return 'https://app.asana.com/-/oauth_token';
    }

    public function urlUserDetails(AccessToken $token)
    {
        return 'https://app.asana.com/api/1.0/users/me';
    }

    public function userDetails($response, AccessToken $token)
    {
        $user = new User();

        $user->exchangeArray([
            'uid' => $response->data->id,
            'name' => $response->data->name,
            'email' => $response->data->email,
            'imageurl' => $response->data->photo->image_128x128,
        ]);

        return $user;
    }
}
