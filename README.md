# 快速开始

- 安装包: `composer install`

- 复制配置文件, 并修改配置: `cp .env.example .env`【数据库配置, passport配置】

- 执行数据库迁移: `php artisan migrate`

- 安装passport: `php artisan passport:install`

- 启动项目: `php -S localhost:8088 -t public/`

- 生成一条假用户数据: `php artisan db:seed --class=UsersTableSeeder`

# 从零开始构造介绍

## 项目初始化

- 安装lumen安装器: `composer global require "laravel/lumen-installer"`

- 先 `lumen new user-center` 初始化一个项目 或者 `composer create-project --prefer-dist laravel/lumen user-center`

- 执行 `composer install` 安装依赖包，如果是用 `lumen new` 命令可以省略这一步。

- 复制配置文件 `cp .env.example .env`

- 设置 `APP_KEY` 等配置信息, 因为 `php artisan key:generate` 没用

- 启动项目 `php -S localhost:8000 -t public`

## 引入lumen-passport

- 安装 `lumen-passport` 包 [#](https://github.com/dusterio/lumen-passport)

```
composer require dusterio/lumen-passport
```

- 修改 `bootstrap/app.php` 文件

```
// 集成passport
//只是取消注释
// Enable Facades
$app->withFacades();
// Enable Eloquent
$app->withEloquent();
// Enable auth middleware (shipped with Lumen)
$app->routeMiddleware([
    'auth' => App\Http\Middleware\Authenticate::class,
]);

//新增
// Finally register two service providers - original one and Lumen adapter
$app->register(Laravel\Passport\PassportServiceProvider::class);
$app->register(Dusterio\LumenPassport\PassportServiceProvider::class);

// 自定义-下面有说到, 可以之后加
// 配置-新增
$app->configure('auth');

// 开启AppServiceProvider-取消注释
$app->register(App\Providers\AppServiceProvider::class);
$app->register(App\Providers\AuthServiceProvider::class);

// $app->alias('cache', 'Illuminate\Cache\CacheManager'); //新增，解决Lumen的Cache问题
```

- 执行 `migrate` 和安装 `passport`

```
# Create new tables for Passport
php artisan migrate
# Install encryption keys and other necessary stuff for Passport
php artisan passport:install
```

```
$ php artisan migrate
Migration table created successfully.
Migrating: 2016_06_01_000001_create_oauth_auth_codes_table
Migrated:  2016_06_01_000001_create_oauth_auth_codes_table
Migrating: 2016_06_01_000002_create_oauth_access_tokens_table
Migrated:  2016_06_01_000002_create_oauth_access_tokens_table
Migrating: 2016_06_01_000003_create_oauth_refresh_tokens_table
Migrated:  2016_06_01_000003_create_oauth_refresh_tokens_table
Migrating: 2016_06_01_000004_create_oauth_clients_table
Migrated:  2016_06_01_000004_create_oauth_clients_table
Migrating: 2016_06_01_000005_create_oauth_personal_access_clients_table
Migrated:  2016_06_01_000005_create_oauth_personal_access_clients_table
```

```
$ php artisan passport:install
Encryption keys generated successfully.
Personal access client created successfully.
Client ID: 1
Client Secret: oIWaBwhNt2KZD606lb0Il5dZl8D72fhMBUwkPvHW
Password grant client created successfully.
Client ID: 2
Client Secret: gvpDe6KIieDD1dvouk639fsxD6wLiNjbPuabT4wh
```

- 在根目录新建 `config/auth.php` 文件，加入以下内容

```
return [
    'defaults' => [
        'guard' => 'api',
        'passwords' => 'users',
    ],

    'guards' => [
        'api' => [
            'driver' => 'passport',
            'provider' => 'users',
        ],
    ],

    'providers' => [
        'users' => [
            'driver' => 'eloquent',
            'model' => \App\User::class
        ]
    ]
];
```

- 在 `bootstrap/app.php` 中引入配置文件

```
$app->configure('auth');
```

- 注册路由

Next, you should call the LumenPassport::routes method within the boot method of your application (one of your service providers). This method will register the routes necessary to issue access tokens and revoke access tokens, clients, and personal access tokens:

```
Dusterio\LumenPassport\LumenPassport::routes($this->app);
```

You can add that into an existing group, or add use this route registrar independently like so;

```
Dusterio\LumenPassport\LumenPassport::routes($this->app, ['prefix' => 'v1/oauth']);
```

- 并且在 `.env` 文件中加入几项配置项

```
# 环境
APP_ENV=local
# 调试
APP_DEBUG=true
# 秘钥
APP_KEY=base64:24uriVnENMM+x8u8ouLsNlE4EohGNY1mxTGWdxmPt2w=
# 时区
APP_TIMEZONE=UTC
# 语言
#APP_LOCALE
# 数据库配置
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=user_center
DB_USERNAME=root
DB_PASSWORD=
# 缓存
CACHE_DRIVER=file
# 队列
QUEUE_DRIVER=sync
# passport password grant_type client(passport进行密码授权的客户端)
APP_CLIENT_ID=2
APP_CLIENT_SECRET=bohwT7qPxj8ltPUn21nDvmMVg5DYiRgoFCZYvVh7
# passport进行密码授权时请求路径,向自己请求
APP_URL=http://www.b.com
```

## 其他环境和配置准备

- 在 `app` 目录下添加通用函数文件 `helper.php` 并且通过文件的形式自动载入， 在 `composer.json` 里的 `autoload` 添加如下代码：

```
    "autoload": {
        "psr-4": {
            "App\\": "app/"
        },
        "files": [
            "app/helpers.php"
        ]
    },
```

- 在 `routes` 文件下新建 `api` 路由文件夹， 并在 `bootstrap\app.php` 文件将它加载进来。

```
$app->group(['namespace' => 'App\Http\Controllers'], function ($app) {
    require __DIR__.'/../routes/web.php';
    require __DIR__.'/../routes/api/v1.php';
});
```

- 安装 `dingo` 包 [#](https://github.com/dingo/api)

```
composer require dingo/api:1.0.x@dev
```

`lumen 5.5` 使用下面命令

```
"require": {
    "dingo/api": "2.0.0-alpha1"
}
```

- 将 `dingo` 引入到 `lumen`

在 `bootstrap/app.php` 文件中引入：

```
$app->register(Dingo\Api\Provider\LumenServiceProvider::class);
```

- 在 `app` 目录下新增 `Models` 和 `Serializers` 和 `Transformers` 目录，`dingo` 返回数据的时候可以  `transform` 下

- 配置自定义配置文件

你还可以创建自定义的配置文件并使用 `$app->configure()` 方法来加载它们。例如，如果你的配置文件位于`config/options.php`，你可以像这样加载它：`$app->configure('options');`

- 为了刷新 `token` 还可以引入 `Listeners`

- 引入所有的 `config`

```
// config
$app->configure('app');
$app->configure('auth');
$app->configure('secrets');
$app->configure('filesystems');
```

**通过 `passport` 进行鉴权**

- 为了将 `Unauthorized.` 以状态码加提示信息的形式返回。在 `Exceptions\Handler.php` 目录的 `render`函数中加入

```
        switch (true) {
            case $e instanceof AuthorizationException:
                return response('This action is unauthorized.', 403);
            case $e instanceof ModelNotFoundException:
                return response('The model is not found.', 404);
        }
```

## 解决跨域问题[lumen-cors](https://github.com/palanik/lumen-cors)【现在换成了用laravel-cors[#](https://github.com/barryvdh/laravel-cors)】

```
// laravel-cors
composer require barryvdh/laravel-cors
```

在 `bootstrap/app.php` 文件中

```
// laravel-cors
// 注册 `cors` 的服务提供者
$app->register(Barryvdh\Cors\ServiceProvider::class);

$app->routeMiddleware([
    'cors' => \Barryvdh\Cors\HandleCors::class,
]);
```