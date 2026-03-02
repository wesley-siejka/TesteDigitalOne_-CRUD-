<?php

namespace App\Providers;

use App\Models\User;
use App\Policies\UserPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Mapeamento das policies.
     */
    protected $policies = [
        User::class => UserPolicy::class,
    ];

    /**
     * Registra as policies no Laravel.
     */
    public function boot(): void
    {
        $this->registerPolicies();
    }
}
