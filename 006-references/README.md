# 引用的解释

> [引用的解释 - php.net](https://www.php.net/manual/zh/language.references.whatare.php)

在 PHP 中引用意味着用不同的名字访问同一个变量内容。

## 工作原理

COW copy on write：

```
$ php demo1.php
```

使用引用：

```
$ php demo2.php
```

查看 zval 变量容器：

[引用计数基本知识 - php.net](https://php.net/features.gc.refcounting-basics)

- `is_ref` 标识这个变量是否是属于引用集合(reference set)。通过这个字节，PHP 引擎才能把普通变量和引用变量区分开来
- `refcount` 表示指向这个 `zval` 变量容器的变量(也称符号即 symbol)个数

```
$ php demo3.php
$ php demo4.php
```

## unset

unset 只会取消引用，不会销毁内存空间

```
$ php demo5.php
```

## 对象

对象本身就是引用传递

```
$ php demo6.php
```

## 练习

```
$ php test1.php
```
