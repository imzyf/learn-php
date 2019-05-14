# 变量

## 变量范围

变量的范围即它定义的上下文背景（也就是它的生效范围）。大部分的 PHP 变量只有一个单独的范围。这个单独的范围跨度同样包含了 include 和 require 引入的文件。

```
$ php test1.php
```

## global 关键字

```
$ php test2.php

$ php -S localhost:8877
http://localhost:8877/test3.php?name=yi
```

## 静态变量

```
$ php test4.php
```

1. 仅初始化一次
2. 初始化需要赋值
3. 每次执行函数该值会保留
4. static 修改的变量是局部的，仅在函数内部有效
5. 可以记录函数的调用次数，从而可以在某些条件下终止递归
