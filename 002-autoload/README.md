# autoload

## no autoload

`require_once` 加载示例：

```
php no_autoload.php
```

## __autoload

如果发现这个类没有加载，就会自动运行 `__autoload()` 函数。

``` 
php autoload.php
```

1. 根据类名确定类文件名。
1. 确定类文件所在的磁盘路径。
1. 将类从磁盘文件中加载到系统中。

将类名与实际的磁盘文件对应起来，就可以实现 `lazy loading` 的效果。

### __autoload 存在的问题

实现中，许多类库由不同的开发人员编写的，其类名与实际的磁盘文件的映射规则不尽相同。这时如果要实现类库文件的自动加载，就必须在 `__autoload()` 函数中将所有的映射规则全部实现。浙江导致函数非常复杂甚至无法实现。

这问题出现在 `__autoload()` 是全局函数只能定义一次 ，不够灵活，所以所有的类名与文件名对应的逻辑规则都要在一个函数里面实现，造成这个函数的臃肿。

为解决这个问题就是使用 `__autoload 调用堆栈`，不同的映射关系写到不同的 `__autoload()` 函数 中去，然后统一注册统一管理，这个就是 `SPL Autoload`。

## spl_autoload_register

> [SPL 函数 - php.net](https://www.php.net/manual/zh/ref.spl.php)

SPL（Standard PHP Library）是用于解决典型问题（standard problems）的一组接口与类的集合。

```
php spl_autoload.php

php spl_static_autoload.php
```

## 命名空间

> [命名空间概述 - php.net](https://php.net/manual/zh/language.namespaces.rationale.php)

## PSR-0 PSR-4

对 `命名空间的命名`、类文件目录的 `位置` 和 `两者映射关系` 做出了限制，这个就是标准的核心了。

### PSR-0 PSR-4 的不同

- PSR-4 中，在类名中使用下划线没有任何特殊含义。而 PSR-0 则规定类名中的 `下划线 _` 会被转化成目录分隔符。
- PSR-4 带来更简洁的文件结构。

```
{
    "psr-4": {
       "Foo\\": "src/"
    }
}
```

当试图自动加载 `Foo\Bar\Baz` class 时，回去寻找 `src/Bar/Baz.php` 这个文件。

```
{
    "psr-0": {
       "Foo\\": "src/"
    }
}
```

- 当试图自动加载 `Foo\Bar\Baz` class 时，会去寻找 `src/Foo/Bar/Baz.php` 这个文件。
- PSR-0 当试图加载 `Foo\A_B` class 时，会去寻找 `src/Foo/A/B.php` 这个文件。

## Composer

[Composer](https://docs.phpcomposer.com/00-intro.html) 是 `PHP` 的一个依赖管理工具。它允许你申明项目所依赖的代码库，它会在你的项目中为你安装他们。

`Composer` 帮助我们下载好了符合 `PSR0/PSR4` 标准的第三方库，并把文件放在相应位置。帮我们写了 `__autoload()` 函数，注册到了 `spl_register()` 函数，当我们想用第三方库的时候直接使用命名空间即可。

> [自动加载 - phpcomposer.com](https://docs.phpcomposer.com/01-basic-usage.html)

你可以在 `composer.json` 的 `autoload` 字段中增加自己的 `autoloader`：
```
...
    "autoload": {
        "psr-4": {
            "Imzyf\\": "Lib/Imzyf"
        }
    },
...
```

`Composer` 将注册一个 `PSR-4` `autoloader` 到 `Imzyf` 命名空间。

此时 `Lib` 会在你项目的根目录，与 `vendor` 文件夹同级。例如 `Lib/Imzyf/Model/Person.php` 文件应该包含 `Imzyf\Model\Person` 类。

添加 `autoload` 字段后，你应该再次运行 `install` 命令来生成 `vendor/autoload.php` 文件。
 
```
$ cd composer-autoload
$ php test.php
```

### classmap

`classmap` 引用的所有组合，都会在 `install/update` 过程中生成，并存储到 `vendor/composer/autoload_classmap.php` 文件中。这个 `map` 是经过扫描指定目录（同样支持直接精确到文件）中所有的 `.php` 和 `.inc` 文件里内置的类而得到的。

你可以用 `classmap` 生成支持支持自定义加载的不遵循 `PSR-0/4 `规范的类库。要配置它指向需要的目录，以便能够准确搜索到类文件。
 
```
{
    "autoload": {
        "classmap": ["src/", "lib/", "Something.php"]
    }
}
```

## Reference

> [类的自动加载 - php.net](https://www.php.net/manual/zh/language.oop5.autoload.php)
> [PHP学习 之 autoload](https://www.jianshu.com/p/8c839edb79d7)
> [composer.json 架构](https://docs.phpcomposer.com/04-schema.html)