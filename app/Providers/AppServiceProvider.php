<?php namespace App\Providers;

use App\Oauth2\Client\Provider\Asana as AsanaProvider;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('App\Oauth2\Client\Provider\Asana', function () {
            return new AsanaProvider([
                'clientId'      => env('ASANA_CLIENTID'),
                'clientSecret'  => env('ASANA_CLIENTSECRET'),
                'redirectUri'   => url() . '/authenticate',
            ]);
        });
    }

    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
