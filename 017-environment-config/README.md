# 开发环境与配置相关

## 版本控制

集中式和分布式

## PHP 运行原理

- [CGI(Common Gateway Interface)](https://zh.wikipedia.org/wiki/%E9%80%9A%E7%94%A8%E7%BD%91%E5%85%B3%E6%8E%A5%E5%8F%A3) 语言解析器与 web server 的通信协议。
- FastCGI：CGI 的改良版本。Fastcgi 是 CGI 的更高级的一种方式，是用来提高 CGI 程序性能的。
- PHP-FPM：FastCGI 进程管理器

CGI 针对每个 http 请求都是 fork 一个新进程来进行处理，处理过程包括解析 php.ini 文件，初始化执行环境等，然后这个进程会把处理完的数据返回给 web 服务器，最后 web 服务器把内容发送给用户，刚才 fork 的进程也随之退出。 如果下次用户还请求动态资源，那么 web 服务器又再次 fork 一个新进程，周而复始的进行。

而 Fastcgi 则会先 fork 一个 master，解析配置文件，初始化执行环境，然后再 fork 多个 worker。当请求过来时，master 会传递给一个 worker，然后立即可以接受下一个请求。这样就避免了重复的劳动，效率自然是高。

> - [区分 CGI FastCGI PHP-CGI PHP-FPM](https://zyf.im/2017/04/21/what-is-cgi-fastcgi-phpcgi-phpfpm/)

## PHP 常见配置项

- register_globals 打开以后，各种变量都被注入代码
- allow_url_fopen 是否允许打开远程文件
- allow_url_include 是否允许包含文件
- date.timezone 设置时区
- display_errors 是否显示错误
- error_reporting 显示错误的区别设置
- safe_mode 是否开启安全模式
- upload_max_filesize 上传的最大文件大小
- max_file_uploads 上传的最大文件数量
- post_max_size post 数据最大大小
