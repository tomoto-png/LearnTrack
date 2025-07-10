<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\Answer;
use App\Policies\AnswerPolicy;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    protected $policies = [
        Answer::class => AnswerPolicy::class,
    ];
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->registerPolicies(); // ← これを忘れずに！！
    }
}
