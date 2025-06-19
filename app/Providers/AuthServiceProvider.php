<?php

namespace App\Providers;

use App\Models\Restaurant;
use App\Models\Category;
use App\Models\Review;
use App\Models\User;
use App\Policies\ReviewPolicy;
use App\Policies\RestaurantPolicy;
use App\Policies\CategoryPolicy;
use App\Policies\UserPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Restaurant::class => RestaurantPolicy::class,
        Category::class => CategoryPolicy::class,
        Review::class => ReviewPolicy::class,
        User::class => UserPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        //
    }
}