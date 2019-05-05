# 运算符

> - [运算符 - php.net](https://www.php.net/manual/zh/language.operators.php)

## 运算符优先级

> - [运算符优先级 - php.net](https://www.php.net/manual/zh/language.operators.precedence.php)

```
递增／递减
!
算术运算符
比较大小
（不）相等比较
逻辑与 &&
逻辑或 ||
三目
赋值
and 
xor	 
or
```

## ===

=== 比较值与类型（地址？）

## 递增／递减

- **递增／递减运算符不影响布尔值**
- 递减 NULL 值没效果
- 递增 NULL 为 1
- 递增／递减运算符在前先运算后返回，反之反之

## 逻辑运算符

`or` 优先级最低

## 错误控制运算符

`@` 当将其放置在一个 PHP 表达式之前，该表达式可能产生的任何错误信息都被忽略掉。

```
$ php test3.php 
```