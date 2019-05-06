# 数据类型与常量

## 试题

字符串的定义方式以及各自区别？

[String 字符串 - php.net](https://www.php.net/language.types.string)

1. 单引号 - 单引号定义的字符串中的变量和特殊字符的转义序列将不会被替换。效率高
1. 双引号 - 双引号定义的字符串最重要的特征是变量会被解析
1. heredoc - 结构类似于双引号字符串。要提供一个标识符，然后换行。结束时所引用的标识符必须在该行的第一列
1. nowdoc - 类似于单引号字符串的。不进行解析操作。标识符要用单引号括起来，即 `<<<'EOT'`

## 数据类型

8 大数据类型，分三类：标量、复合、特殊

- fload 不用用于比较
- false 的 7 中情况：`0` `0.0` `""` `'0'` `false` `[]` `NULL`
- [超全局变量](https://www.php.net/manual/zh/language.variables.superglobals.php)
- NULL 3 种情况：直接赋值 null、未定义、unset

> - [预定义变量 - php.net](https://www.php.net/manual/zh/reserved.variables.php)
> - [$_SERVER - php.net](https://www.php.net/manual/zh/reserved.variables.server.php)

### 整型溢出

PHP 的整型数的字长和平台有关，对与 32 位操作系统，最大的整型是 2 的 31 次方，最小是负 2 的 31 次方。PHP 不支持无符号整数。运算结果超出后会返回 float。
 
## 常量

- const 语言结构 可以定义类的常量 
- define 函数

### 预定义常量

> - [几个 PHP 的“魔术常量” - php.net](https://www.php.net/manual/zh/language.constants.predefined.php)
 