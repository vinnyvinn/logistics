<?php

namespace App\Providers;

use App\Http\Controllers\EslPolicy;
use Carbon\Carbon;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Laravel\Passport\Passport;
use Esl\helpers\Constants;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        $this->registerEslPolicies();

        Passport::routes();
        Passport::tokensExpireIn(Carbon::now()->addYears(15));
        Passport::refreshTokensExpireIn(now()->addYears(15));

        //
    }

    public function registerEslPolicies()
    {
        $permissions = Constants::PERMISSIONS;
        foreach ($permissions as $key => $permission) {
            Gate::define($key, function ($user) use ($key) {
                return EslPolicy::auth()->checkPermission($user, [$key]);
            });
        }

        // Gate::define('manage-user', function ($user){
        //     return EslPolicy::auth()->checkPermission($user, ['manage-user']);
        // });
    }
}
