# Laravel IoC Container

> 环境：Laravel 5.8

[控制反转](https://zh.wikipedia.org/wiki/%E6%8E%A7%E5%88%B6%E5%8F%8D%E8%BD%AC)（Inversion of Control，缩写为 IoC），是面向对象编程中的一种设计原则，可以用来减低计算机代码之间的耦合度。其中最常见的方式叫做依赖注入（Dependency Injection，简称 DI），还有一种方式叫 `依赖查找`（Dependency Lookup）。通过控制反转，对象在被创建的时候，由一个调控系统内所有对象的外界实体，将其所依赖的对象的引用传递给它。也可以说，依赖被注入到对象中。

[Laravel 学习笔记 - 神奇的服务容器](https://www.insp.top/learn-laravel-container) 这篇文章告诉我们，服务容器就是工厂模式的升级版，对于传统的工厂模式来说，虽然解耦了对象和外部资源之间的关系，但是工厂和外部资源之间却存在了耦和。而服务容器在为对象创建了外部资源的同时，又与外部资源没有任何关系，这个就是 `IoC 容器`。所谓的依赖注入和控制反转，就是：

只要不是由内部生产（比如初始化、构造函数 `__construct` 中通过工厂方法、自行手动 `new` 的），而是由外部以参数或其他形式注入的，都属于依赖注入（DI）。

依赖注入是从应用程序的角度在描述：应用程序依赖容器创建并注入它所需要的外部资源。控制反转是从容器的角度在描述：容器控制应用程序，由容器反向的向应用程序注入应用程序所需要的外部资源。

Laravel 服务容器主要承担两个作用：`绑定` 与 `解析`。

## 绑定

所谓的绑定就是将 _接口_ 与 _实现_ 建立对应关系。几乎所有的服务容器绑定都是在服务提供者中完成，也就是在服务提供者中绑定。

如果一个类没有基于任何接口那么就没有必要将其绑定到容器。容器并不需要被告知如何构建对象，因为它会使用 PHP 的反射服务自动解析出具体的对象。也就是说，如果需要依赖注入的外部资源如果没有接口，那么就不需要绑定，直接利用服务容器进行解析就可以了，服务容器会根据类名利用反射对其进行自动构造。

### bind 绑定

绑定自身，一般用于绑定单例：

```php
$this->app->bind('App\Services\RedisEventPusher', null);
```

绑定闭包：

```php
// 闭包返回变量
$this->app->bind('name', function () {
    return 'Taylor';
});

// 闭包直接提供类实现方式
$this->app->bind('HelpSpot\API', function () {
    return HelpSpot\API::class;
});
  
// 闭包返回类变量
public function testSharedClosureResolution()
{
    $class = new \stdClass();
    $class->age = 18;
    app()->bind('class', function () use ($class) {
        return $class;
    });

    echo app()->make('class')->age; // 18
}

// 闭包直接提供类实现方式
$this->app->bind('HelpSpot\API', function () {
    return new HelpSpot\API();
});

// 闭包返回需要依赖注入的类
$this->app->bind('HelpSpot\API', function ($app) {
    return new HelpSpot\API($app->make('HttpClient'));
});
```

绑定接口，在 `tests` 中进行测试：

```php
class ExampleTest extends TestCase
{
    // A basic test example.
    public function testBasicTest()
    {
        $container = app();
        $container->bind('Tests\Feature\IContainerContractStub',
            'Tests\Feature\ContainerImplementationStub');

        $this->assertInstanceOf(ContainerDependentStub::class,
            $container->build(ContainerDependentStub::class));
    }
}

interface IContainerContractStub {}

class ContainerImplementationStub implements IContainerContractStub {}

class ContainerDependentStub
{
    public $impl;

    public function __construct(IContainerContractStub $impl)
    {
        $this->impl = $impl;
    }
}
```

执行 `vendor/bin/phpunit` 得到结果。

### bindIf 绑定

```php
public function testBindIf()
{
    $container = app();
    $container->bind('name', function () {
        return 'Taylor';
    });
    $container->bindIf('name', function () {
        return 'Swift';
    });

    $this->assertEquals('Taylor', $container->make('name'));
}
```

### singleton 绑定

singleton 方法绑定一个只需要解析一次的类或接口到容器，然后接下来对容器的调用将会返回同一个实例：

```php
$this->app->singleton('HelpSpot\API', function ($app) {
    return new HelpSpot\API($app->make('HttpClient'));
});
```

### instance 绑定

instance 方法绑定一个已存在的对象实例到容器，随后调用容器将总是返回给定的实例：

```php
$api = new HelpSpot\API(new HttpClient);
$this->app->instance('HelpSpot\Api', $api);
```

### Context 绑定

有时侯我们可能有两个类使用同一个接口，但我们希望在每个类中注入不同实现，例如，两个控制器依赖 `Illuminate\Contracts\Filesystem\Filesystem` 契约的不同实现。Laravel 为此定义了简单、平滑的接口：

```php
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\VideoController;
use App\Http\Controllers\PhotoControllers;
use Illuminate\Contracts\Filesystem\Filesystem;

// 提供类名
$this->app->when(StorageController::class)
          ->needs(Filesystem::class)
          ->give(function () {
            Storage::class
          });

// 提供实现方式
$this->app->when(PhotoController::class)
          ->needs(Filesystem::class)
          ->give(function () {
             return new Storage();
          });

// 需要依赖注入
$this->app->when(VideoController::class)
          ->needs(Filesystem::class)
          ->give(function () {
            return new Storage($app->make(Disk::class));
          });
```

### 原始值绑定

我们可能有一个接收注入类的类，同时需要注入一个原生的数值比如整型，可以结合上下文轻松注入这个类需要的任何值：

```php
$this->app->when('App\Http\Controllers\UserController')
          ->needs('$variableName')
          ->give($value);
```

### 数组绑定

数组绑定一般用于绑定闭包和变量，但是不能绑定接口，否则只能返回接口的实现类名字符串，并不能返回实现类的对象。

```php
public function testBindArray() {
    $container = app();
    $container[IContainerContractStub::class] = ContainerImplementationStub::class;
    $this->assertTrue(isset($container[IContainerContractStub::class]));
    $this->assertEquals(ContainerImplementationStub::class,
        $container[IContainerContractStub::class]);

    unset($container['something']);
    $this->assertFalse(isset($container['something']));
}
```

### 标签绑定

少数情况下，我们需要解析特定分类下的所有绑定。例如，你正在构建一个接收多个不同 `Report` 接口实现的报告聚合器，在注册完 `Report` 实现之后，可以通过 `tag` 方法给它们分配一个标签：

```php
$this->app->bind('SpeedReport', function () { 
    //
});
$this->app->bind('MemoryReport', function () {
    //
});
$this->app->tag(['SpeedReport', 'MemoryReport'], 'reports'); 
```

这些服务被打上标签后，可以通过 `tagged` 方法来轻松解析它们：

```php
$this->app->bind('ReportAggregator', function ($app) {
    return new ReportAggregator($app->tagged('reports'));
});
```

## entend 扩展

extend 是在当原来的类被注册或者实例化出来后，可以对其进行扩展，而且可以支持多重扩展：

```php
$container->bind('foo', function () {
    $obj = new StdClass;
    $obj->foo = 'bar';

    return $obj;
});

$container->extend('foo', function ($obj, $container) {
    $obj->bar = 'baz';
    return $obj;
}); 
```

## rebinding

绑定是针对接口的，是为接口提供实现方式的方法。我们可以对接口在不同的时间段里提供不同的实现方法，一般来说，对同一个接口提供新的实现方法后，不会对已经实例化的对象产生任何影响。但是在一些场景下，在提供新的接口实现后，我们希望对已经实例化的对象重新做一些改变，这个就是 rebinding 函数的用途。 

---

## 服务器别名

不同于 Facades 在 config/app 定义的别名，这里的别名服务绑定名称的别名。通过服务绑定的别名，在解析服务的时候，跟不使用别名的效果一致。别名的作用也是为了同时支持全类型的服务绑定名称以及简短的服务绑定名称考虑的。

```php
$this->app->make('auth')

$this->app->make('\Illuminate\Auth\AuthManager::class')

$this->app->make('\Illuminate\Contracts\Auth\Factory::class')
```

后面两个服务的名字都是 auth 的别名，使用别名和使用 auth 的效果是相同的。

### 服务别名的实现

在 `\Illuminate\Foundation\Application.php` 的 `__construct` 中：

```
$this->registerCoreContainerAliases();
```

```php
   public function registerCoreContainerAliases()
    {
        foreach ([
            'app'                  => [\Illuminate\Foundation\Application::class, \Illuminate\Contracts\Container\Container::class, \Illuminate\Contracts\Foundation\Application::class,  \Psr\Container\ContainerInterface::class],
            'auth'                 => [\Illuminate\Auth\AuthManager::class, \Illuminate\Contracts\Auth\Factory::class],
            ....
        ] as $key => $aliases) {
            foreach ($aliases as $alias) {
                $this->alias($key, $alias);
            }
        }
    }
```

```php
    public function alias($abstract, $alias)
    {
        $this->aliases[$alias] = $abstract; 
        $this->abstractAliases[$abstract][] = $alias;
    }
```

加载后，服务容器的 aliases 和 abstractAliases 数组：

```php
$aliases = [
  'Illuminate\Foundation\Application' = "app"
  'Illuminate\Contracts\Container\Container' = "app"
  'Illuminate\Contracts\Foundation\Application' = "app"
  'Illuminate\Auth\AuthManager' = "auth"
  ...
］

$abstractAliases = [
  app = {array} [3]
  0 = "Illuminate\Foundation\Application"
  1 = "Illuminate\Contracts\Container\Container"
  2 = "Illuminate\Contracts\Foundation\Application"
  auth = {array} [2]
  0 = "Illuminate\Auth\AuthManager"
  1 = "Illuminate\Contracts\Auth\Factory"
  ...
]
```

---

## 服务解析

### make 解析
 
```php  
// @param  string  $abstract 类名或接口名作为参数
// @param  array  $parameters
public function testResolvingWithArrayOfParameters()
{
    $container = app();
    $instance = $container->make(ContainerDefaultValueStub::class, ['default' => 'adam']);
    $this->assertEquals('adam', $instance->default);
}
```

### 自动注入

```
    public function testAutoDI()
    {
        $container = app();
        $userCtrl = $container->make(UserCtrl::class);
        $this->assertNotNull($userCtrl->show());
    }

class UserCtrl
{
    protected $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function show()
    {
        return $this->user;
    }
}
```