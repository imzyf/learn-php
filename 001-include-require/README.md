# include、require、\*\_once

## 实验

启动 PHP 内置 Server `php -S localhost:8877`，游览器访问 `localhost:8877` 查看示例。

## 相同点

- `require` 语句的性能与 `include` 相类似，都是包括并运行指定文件

1. 被包含文件先按参数给出的路径寻找，如果没有给出目录（只有文件名）时则按照 `include_path（include_path=".:/php/includes"`） 指定的目录寻找
1. 如果在 `include_path` 下没找到该文件则 `include` 最后才在调用脚本文件所在的目录和当前工作目录下寻找

## 不同点

- 如果最后仍未找到文件则 `include` 结构会发出一条 `警告`，并继续运行下边的代码
- 如果最后仍未找到文件则 `include` 结构会发出一条 `致命错误`，并停止运行下边的代码
- `*_once` 如果该文件中已经被包含过，则不会再次包含，以避免函数重定义以及变量重新赋值等问题

## 其他

`require` 和 `include` 语句是语言结构，不是真正的函数，因此其参数没有必要用括号将其括起来。

`return` 也是语言结构。当用引用返回值时 **永远** 不要使用括号。只能通过引用返回变量，而不是语句的结果。如果使用 `return ($a);` 时其实不是返回一个变量，而是表达式 `($a)` 的值（当然，此时该值也正是 `$a` 的值）。

> 这里有个疑惑，没能举出一个例子证明 `return ($a);` 与 `return $a;` 不同。

## References

> - [include - php.net](https://www.php.net/manual/zh/function.include.php)

-- EOF --
