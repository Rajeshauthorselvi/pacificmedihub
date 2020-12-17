<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Contracts\Auth\Access\Gate as GateContract;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot(GateContract $gate)
    {
        $this->registerPolicies($gate);

        // Check for Super Admin
        $gate->define('isSuperAdmin', function($user){
            return $user->role_id == 1;
        });

        // Check for Admin
        $gate->define('isAdmin', function($user){
            return $user->role_id == 2;
        });

        // Check for Logistics
        $gate->define('isLogistics', function($user){
            return $user->role_id == 3;
        });

        // Check for Sales Rep
        $gate->define('isSalesRep', function($user){
            return $user->role_id == 4;
        });

        // Check for Employee
        $gate->define('isEmployee', function($user){
            return $user->role_id == 5;
        });

        // Check for Delivery Person
        $gate->define('isDeliveryPerson', function($user){
            return $user->role_id == 6;
        });

        // Check for Customer
        $gate->define('isCustomer', function($user){
            return $user->role_id == 7;
        });
    }
}
