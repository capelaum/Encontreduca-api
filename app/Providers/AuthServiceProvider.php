<?php

namespace App\Providers;


use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use App\Policies\DefaultPolicy;
use Illuminate\Support\Facades\Gate;
use App\Models\{Category,
    Motive,
    User,
    Review,
    Support,
    Resource,
    ResourceChange,
    ResourceVote,
    ResourceComplaint,
    ReviewComplaint};

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        User::class => DefaultPolicy::class,
        Review::class => DefaultPolicy::class,
        Support::class => DefaultPolicy::class,
        Resource::class => DefaultPolicy::class,
        ResourceVote::class => DefaultPolicy::class,
        ResourceChange::class => DefaultPolicy::class,
        ResourceComplaint::class => DefaultPolicy::class,
        ReviewComplaint::class => DefaultPolicy::class,
        Category::class => DefaultPolicy::class,
        Motive::class => DefaultPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::define('is_admin', fn(User $user) => $user->is_admin);
    }
}
