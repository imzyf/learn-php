# 致命错误

## 异常和错误

在其他语言中，异常和错误是有区别的，但是 PHP，遇见自身错误时，会触发一个错误，而不是抛出异常。并且，PHP 大部分情况，都会触发错误，终止程序执行，在 PHP5 中，try catch 是没有办法处理错误的，PHP7 是可以捕获错误。

## catch Exception

```php
function test()
{
    try {
        throw new Exception('Error Processing Request', 1);
    } catch (Exception $e) {
        echo $e->getCode()."\n";
        echo $e->getMessage()."\n";
        echo $e->getLine()."\n";
        echo $e->getFile()."\n";
    }
}

test();

```

```bash
./php56/bin/php f01_php_exception.php

1
Error Processing Request
6
.../reboot-php/024-fatal-error/f01_php_exception.php
```

## error handler

```php
function MyErrorHandler($error, $errstr, $errfile, $errline)
{
    echo 'Custom error: '.$error.' : '.$errstr."\n";
    echo "Error on line $errline in ".$errfile."\n";
}

set_error_handler('MyErrorHandler', E_ALL | E_STRICT);

function test()
{
    trigger_error('some error');
    1 / 0;
}

test();
```

```bash
./php56/bin/php f02_php_error_handler.php

Custom error: 1024 : some error
Error on line 13 in ./reboot-php/024-fatal-error/f02_php_error_handler.php
Custom error: 2 : Division by zero
Error on line 14 in ./reboot-php/024-fatal-error/f02_php_error_handler.php
```

## register shudown

```php
register_shutdown_function(function () {
    $e = error_get_last();
    var_dump($e);
});

function test()
{
    not_exist();
}

test();
```

```bash
./php56/bin/php f01_php_exception.php

Fatal error: Call to undefined function not_exist() in ./reboot-php/024-fatal-error/f03_php_register_shutdown.php on line 10
array(4) {
  ["type"]=>
  int(1)
  ["message"]=>
  string(38) "Call to undefined function not_exist()"
  ["file"]=>
  string(87) "./reboot-php/024-fatal-error/f03_php_register_shutdown.php"
  ["line"]=>
  int(10)
}
```
