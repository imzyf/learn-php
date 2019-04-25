# include、require、*_once

启动 PHP 内置 Server 查看示例 `php -S localhost:8877`。

- `include` 引入文件的时候，如果碰到错误，会给出提示，并继续运行下边的代码。
- `require` 引入文件的时候，如果碰到错误，会给出提示，并停止运行下边的代码。
- `*_once` 已加载的不再加载，以避免函数重定义以及变量重新赋值等问题。
