<?php

namespace App\Providers;


use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use App\Models\{
    User,
    Review,
    Support,
    Resource,
    ResourceChange,
    ResourceVote,
    ResourceComplaint,
    ReviewComplaint
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
        Review::class => DefaultPolicy::class,
        Support::class => DefaultPolicy::class,
        Resource::class => DefaultPolicy::class,
        ResourceVote::class => DefaultPolicy::class,
        ResourceChange::class => DefaultPolicy::class,
        ResourceComplaint::class => DefaultPolicy::class,
        ReviewComplaint::class => DefaultPolicy::class,
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
