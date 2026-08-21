<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

        /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // 本番環境では、常にHTTPSのURLを生成するように強制する
        // Renderなどのリバースプロキシ環境では、実際の通信はHTTPSでも、
        // コンテナ内部への転送がHTTPで行われるため、Laravelが誤ってHTTPだと判断してしまう
        // そのため、CSSやJSのURLがhttp://になってしまい、ブラウザにブロックされる問題が起きていた
        if ($this->app->environment('production')) {
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }
    }
}
