<?php

namespace App\Providers;


use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use App\Models\{
    User,
    Support,
    Resource,
    ResourceChange,
    ResourceVote,
    ResourceComplaint
};
use App\Policies\{
    UserPolicy,
    DefaultPolicy,
};

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        User::class => UserPolicy::class,
        Support::class => DefaultPolicy::class,
        Resource::class => DefaultPolicy::class,
        ResourceVote::class => DefaultPolicy::class,
        ResourceChange::class => DefaultPolicy::class,
        ResourceComplaint::class => DefaultPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();


    }
}
