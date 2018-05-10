<?php

namespace App\Providers;

use App\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Dusterio\LumenPassport\LumenPassport;
use Laravel\Passport\Passport;
use Carbon\Carbon;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Boot the authentication services for the application.
     *
     * @return void
     */
    public function boot()
    {
        // Second parameter is the client Id(给客户端2,,的token设置一个时间)
        // LumenPassport::tokensExpireIn(Carbon::now()->addMinutes(1));
        Passport::tokensExpireIn(Carbon::now()->addDays(7));
        Passport::refreshTokensExpireIn(Carbon::now()->addDays(15));
        // 清理已失效的令牌。默认情况下，Passport 不会从数据库中删除已失效的令牌。随着时间增长，数据库中会积累大量已失效的令牌。如果你希望 Passport 自动删除它们，你可以在 AuthServiceProvider 的 boot 方法中调用 pruneRevokedTokens 方法
        // 这个函数的效果是在用户请求到新的访问令牌或刷新已存在令牌时会删除老的已失效令牌，而不是每次调用时立即删除所有的失效令牌。
        Passport::pruneRevokedTokens();

        // Here you may define how you wish users to be authenticated for your Lumen
        // application. The callback which receives the incoming request instance
        // should return either a User instance or null. You're free to obtain
        // the User instance via an API token or any other method necessary.

        $this->app['auth']->viaRequest('api', function ($request) {
            if ($request->input('api_token')) {
                return User::where('api_token', $request->input('api_token'))->first();
            }
        });
    }
}
