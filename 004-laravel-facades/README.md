# Laravel Facades

> 环境：Laravel 5.8

[Facades](https://learnku.com/docs/laravel/5.8/facades/3888) 为应用的 服务容器 提供了一个「静态」 接口。Laravel 自带了很多 `Facades`，可以访问绝大部分功能。Laravel `Facades` 实际是服务容器中底层类的 「静态代理」，相对于传统静态方法，在使用时能够提供更加灵活、更加易于测试、更加优雅的语法。

`Facade` 的主要作用，是 **简化类调用的快捷语法**。因为在结构复杂，功能完善的框架中，往往类的结构，层次也比较复杂，Laravel 也是如此复杂的框架，因此为了简化使用，我们就定义了类的快捷访问方式，在 `Laravel` 中，就是 `Facade`！

常规设计模式中的 **外观模式**（Facade Pattern），就是解决快捷访问问题的，因此 `Laravel` 的 `Facade` 就是外观模式的实现。

Laravel 框架的核心就是个 `IoC` 容器即服务容器，功能类似于一个工厂模式，是个高级版的工厂。

Laravel 的其他功能例如路由、缓存、日志、数据库其实都是类似于插件或者零件一样，叫做服务（providers）。`IoC` 容器主要的作用就是生产各种零件，就是提供各个服务。

在 Laravel 中，如果我们想要用某个服务，该怎么办呢？最简单的办法就是调用服务容器的 `make` 函数，或者利用依赖注入，或者就是 `Facade`。`Facade` 相对于其他方法来说，最大的特点就是简洁。

例如我们经常使用的 `Router`，如果利用服务容器的 `make`：

```php
App::make('router')->get('/hello', function () {
    return 'hello';
});
```

利用 `facade`：

```php
Route::get('/hello-facade', function () {
    return 'hello-facade';
});
```

## 原理

以 `Route` 为例：

```php
namespace Illuminate\Support\Facades;

class Route extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    // 用来确定该 Facade 对应那个服务的方法，其返回值就是在服务容器中注册的服务标识。
    protected static function getFacadeAccessor()
    {
        return 'router';
    }
}
```

其实每个 `facade` 也就是重定义一下 `getFacadeAccessor` 函数就行了，这个函数返回服务的唯一名称：`router`。需要注意的是要确保这个名称可以用服务容器的 `make` 函数创建成功 `App::make('router')`。

那么当写出 `Route::get()` 这样的语句时，到底发生了什么呢？奥秘就在基类 `Facade` 中：

```php
// 静态方法重载
public static function __callStatic($method, $args)
{
    // 确定对应的服务对象
    $instance = static::getFacadeRoot();

    if (! $instance) {
        throw new RuntimeException('A facade root has not been set.');
    }

    // 调用服务对象的方法
    return $instance->$method(...$args);
}
```

`Route` 没有静态 `get()`，PHP 就会调用这个魔术函数 `__callStatic`，这个魔术函数做了两件事：

1. 获得对象实例
2. 利用对象调用 `get()` 函数

```php
// Get the root object behind the facade.
// 从服务容器中解析对象
public static function getFacadeRoot()
{
    // 利用某个 Facade 的 getFacadeAccess() 方法获取服务名称，解析服务对象
    return static::resolveFacadeInstance(static::getFacadeAccessor());
}

/**
 * Resolve the facade root instance from the container.
 *
 * @param  object|string  $name
 * @return mixed
 */
protected static function resolveFacadeInstance($name)
{
    if (is_object($name)) {
        return $name;
    }

    if (isset(static::$resolvedInstance[$name])) {
        return static::$resolvedInstance[$name];
    }

    // 在这里利用了 $app 也就是服务容器创建了 router，创建成功后放入 $resolvedInstance 作为缓存，以便以后快速加载
    return static::$resolvedInstance[$name] = static::$app[$name];
}
```

这里有几个问题：

- 为什么项目中直接写 `Route::` 就是 `Illuminate\Support\Facades\Route`？
- `$app` 是从哪里来的？

## 别名 Aliases

为什么项目中直接写 `Route::` 就是 `Illuminate\Support\Facades\Route`？

这个在于 PHP [class_alias](https://www.php.net/manual/zh/function.class-alias.php) 它可以为任何类创建别名。Laravel 在启动的时候为各个门面类调用了 `class_alias` 函数，因此不必直接用类名，直接用别名即可。

`config/app.php` 中存在这这些 `facade` 与 `类名` 的映射：

```php
'aliases' => [
    'App' => Illuminate\Support\Facades\App::class,
    'Artisan' => Illuminate\Support\Facades\Artisan::class,
    'Auth' => Illuminate\Support\Facades\Auth::class,
    ...
]
```

### 启动别名 Aliases 服务

`public/index.php` 中：

```php
// Composer provides 自动加载
require __DIR__.'/../vendor/autoload.php';

// 获取 Laravel 核心的 Ioc 容器
$app = require_once __DIR__.'/../bootstrap/app.php';
```

`bootstrap/app.php` 中：

```php
/*
我们要做的第一件事是创建一个新的Laravel应用程序实例
这是 Laravel 所有组件的 “胶水”
用于系统绑定所有不同部分的 IoC 容器
*/
$app = new Illuminate\Foundation\Application(
    $_ENV['APP_BASE_PATH'] ?? dirname(__DIR__)
);

/*
接下来，我们需要将一些重要的接口绑定到容器中我们将能够在需要时解决这些问题
kernels serve 的作用是来自 web 和 CLI 的对该应用程序的传入请求
*/
$app->singleton(
    Illuminate\Contracts\Http\Kernel::class,
    App\Http\Kernel::class
);
...

return $app;
```

`app/Http/Kernel.php`

```php
...
use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    ...
    // 1 The application's global HTTP middleware stack.
    // 2 The application's route middleware groups.
    // 3 The application's route middleware.
    // 4 The priority-sorted list of middleware. 按优先级排序的中间件列表。
}
```

`Illuminate\Foundation\Http\Kernel.php`：

```php
class Kernel implements KernelContract
{
    /**
     * Create a new HTTP kernel instance.
     *
     * @param  \Illuminate\Contracts\Foundation\Application  $app
     * @param  \Illuminate\Routing\Router  $router
     * @return void
     */
    public function __construct(Application $app, Router $router)
    {
        // 依赖注入？
        $this->app = $app;
        $this->router = $router;

        $router->middlewarePriority = $this->middlewarePriority;

        foreach ($this->middlewareGroups as $key => $middleware) {
            $router->middlewareGroup($key, $middleware);
        }

        foreach ($this->routeMiddleware as $key => $middleware) {
            $router->aliasMiddleware($key, $middleware);
        }
    }
}
```

呃，不知道如何继续了，重新继续看 `public/index.php`：

```php
// 运行 Application
// 处理通过内核传入的请求，并将关联的响应发回
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

// Laravel 里面所有功能服务的注册加载，乃至 Http 请求的构造与传递都是这一句的功劳
$response = $kernel->handle(
    // Laravel 通过全局 $_SERVER 数组构造一个 Http 请求
    $request = Illuminate\Http\Request::capture()
);
```

通过 `IoC` 容器制造一个 `Http kernel`，这个是一个接口，但是 `App\Http\Kernel::class` 进行了实现。查找 `handle` 方法，具体实现在 `Illuminate\Foundation\Http\Kernel` 中：

```php
/**
* Handle an incoming HTTP request.
*
* @param  \Illuminate\Http\Request  $request
* @return \Illuminate\Http\Response
*/
public function handle($request)
{
    try {
        // 允许在表单中使用 delete、put 等类型的请求
        $request->enableHttpMethodParameterOverride();

        $response = $this->sendRequestThroughRouter($request);
    } catch (Exception $e) {
        $this->reportException($e);

        $response = $this->renderException($request, $e);
    } catch (Throwable $e) {
        $this->reportException($e = new FatalThrowableError($e));

        $response = $this->renderException($request, $e);
    }

    $this->app['events']->dispatch(
        new Events\RequestHandled($request, $response)
    );

    return $response;
}
```

查看这句 `$this->sendRequestThroughRouter($request);`：

```php
/**
    * Send the given request through the middleware / router.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
protected function sendRequestThroughRouter($request)
{
    // IoC 容器设置 request 请求的对象实例
    $this->app->instance('request', $request);
    // Facade 中清除 request 的缓存实例
    Facade::clearResolvedInstance('request');

    $this->bootstrap();

    return (new Pipeline($this->app))
                ->send($request)
                ->through($this->app->shouldSkipMiddleware() ? [] : $this->middleware)
                ->then($this->dispatchToRouter());
}
```

查看这句 `$this->bootstrap();`：

```php
/**
 * The bootstrap classes for the application.
 *
 * @var array
    */
protected $bootstrappers = [
    \Illuminate\Foundation\Bootstrap\LoadEnvironmentVariables::class,
    \Illuminate\Foundation\Bootstrap\LoadConfiguration::class,
    \Illuminate\Foundation\Bootstrap\HandleExceptions::class,
    \Illuminate\Foundation\Bootstrap\RegisterFacades::class,
    \Illuminate\Foundation\Bootstrap\RegisterProviders::class,
    \Illuminate\Foundation\Bootstrap\BootProviders::class,
];

/**
 * Bootstrap the application for HTTP requests.
 *
 * @return void
    */
public function bootstrap()
{
    if (! $this->app->hasBeenBootstrapped()) {
        $this->app->bootstrapWith($this->bootstrappers());
    }
}
```

`$bootstrappers` 是 `Http Kernel` 里专门用于启动的组件。`bootstrap()` 中调用 `IoC` 容器的 `bootstrapWith()` 来创建这些组件并利用组件进行启动服务：

```php
/**
* Run the given array of bootstrap classes.
*
* @param  string[]  $bootstrappers
* @return void
*/
public function bootstrapWith(array $bootstrappers)
{
    $this->hasBeenBootstrapped = true;

    foreach ($bootstrappers as $bootstrapper) {
        $this['events']->dispatch('bootstrapping: '.$bootstrapper, [$this]);

        $this->make($bootstrapper)->bootstrap($this);

        $this['events']->dispatch('bootstrapped: '.$bootstrapper, [$this]);
    }
}
```

`dispatch` 消息队列，发送消息（还不太清楚）。

可以看到 `bootstrapWith()` 也就是利用 `IoC` 容器创建各个启动服务的实例后，回调启动自己的函数 `bootstrap()`，在这里我们只看 `RegisterFacades::class`：

```php
class RegisterFacades
{
    /**
     * Bootstrap the given application.
     *
     * @param  \Illuminate\Contracts\Foundation\Application  $app
     * @return void
     */
    public function bootstrap(Application $app)
    {
        // 清除 Facade 中的缓存
        Facade::clearResolvedInstances();

        // 设置 Facade 的 IoC 容器
        Facade::setFacadeApplication($app);

        // Get or create the singleton alias loader instance.
        AliasLoader::getInstance(array_merge(
            // 获得 config 文件夹里面 app 文件 aliases 别名映射数组
            $app->make('config')->get('app.aliases', []),
            // 三方库中的？
            $app->make(PackageManifest::class)->aliases()
        ))->register();
    }
}
```

```php
// Register the loader on the auto-loader stack.
public function register()
{
    if (! $this->registered) {
        $this->prependToLoaderStack();

        $this->registered = true;
    }
}

// Prepend the load method to the auto-loader stack.
protected function prependToLoaderStack()
{
    spl_autoload_register([$this, 'load'], true, true);
}
```

别名服务 的启动关键就是这个 `spl_autoload_register`，在自动加载中这个函数用于解析命名空间，在这里用于解析别名的真正类名。

### 别名 aliases 服务

```php
// Load a class alias if it is registered.
public function load($alias)
{
    // 实时门面服务
    if (static::$facadeNamespace && strpos($alias, static::$facadeNamespace) === 0) {
        $this->loadFacade($alias);
        return true;
    }

    // class_alias 利用别名映射数组将别名映射到真正的 facades 类中
    if (isset($this->aliases[$alias])) {
        return class_alias($this->aliases[$alias], $alias);
    }
}
```

### 实时门面服务

使用实时 `Facades`，你可以将应用程序中的任何类视为 `Facade`。

`App\Services\PaymentGateway.php`

```php
namespace App\Services;

class PaymentGateway
{
    public function pay($amount)
    {
        dump($amount);
    }
}
```

使用方法：

```php
...
use Facades\App\Services\PaymentGateway; // 命名空间前加 Facades\

class PostController extends Controller
{
    public function root()
    {
        PaymentGateway::pay(168);
        return view('pages.root');
    }
}
```

从上面的代码可以看到命名空间以 `Facades\` 开头的，那么就会调用实时门面的功能，调用`$this->loadFacade($alias);`：

```php
// Load a real-time facade for the given alias.
protected function loadFacade($alias)
{
    require $this->ensureFacadeExists($alias);
}

// Ensure that the given alias has an existing real-time facade class.
protected function ensureFacadeExists($alias)
{
    // 生成 facades 放在了 stroge/framework/cache
    if (file_exists($path = storage_path('framework/cache/facade-'.sha1($alias).'.php'))) {
        return $path;
    }

    // 根据 stub 模板生成文件
    file_put_contents($path, $this->formatFacadeStub(
        $alias, file_get_contents(__DIR__.'/stubs/facade.stub')
    ));

    return $path;
}
```

最终生成的文件 `storage/framework/cache/facade-1cd070dc4193c8fcbfa45aaf4927d550e83a94e3.php`

```php
namespace Facades\App\Services;

use Illuminate\Support\Facades\Facade;

/**
 * @see \App\Services\PaymentGateway
 */
class PaymentGateway extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'App\Services\PaymentGateway';
    }
}
```

## References

> - [Laravel Facade - Facade 门面源码分析](https://leoyang90.gitbooks.io/laravel-source-analysis/content/Laravel%20Facade%E2%80%94%E2%80%94Facade%20%E9%97%A8%E9%9D%A2%E6%BA%90%E7%A0%81%E5%88%86%E6%9E%90.html)

-- EOF --
