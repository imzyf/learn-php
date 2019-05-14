# 高并发大流量

## 如何解决

### 架构概念

并发：操作系统中，是指一个时间段中有几个程序都处于已启动运行到运行完毕之间，而且这几个程序都是在同一个处理机上运行，但任一时刻上只有个程序在处理机上运行。

我们常说的并发：并发访问，某个时间点，有多少个访问同时到来。

关心什么：

- QPS：Query Per Second 每秒钟请求或者查询的数量，在互联网领域，指每秒的请求数。
- 吞吐量：单位时间内处理的请求数量。
- 响应时间：从发出请求到收到响应。
- PV Page View 访问的页面的数量。
- UV Unique Visitor。
- 带宽。
- 日网站带宽 PV / 统计时间（秒）\* 平均页面大小（KB） \* 8。
- QPS 不等于 并发连接数，并发连接数是 系统同时处理的请求数量。
- 峰值 QPS =（PV \* 80%）/ (6 小时秒 \* 20%) 这是 28 定律：80% 的访问集中在 20% 的时间。（6 小时估计值）
- 压力测试。测试能承受的最大并发数。测试最大承受的 QPS 值。

### 常用工具 ab

ab wrk http_load

- apacke benchmark 创建多个并发访问线程，模拟多个访问者同时对某个 URL 地址进行访问。测试 Web 服务器压力 
- ab -c 100 -n 5000 URL （并发 100 总是 5000）
- 被测试服务器 CPU 内存 网络 不超过 75%，top 命令观察 
- 指标：失败测试

QPS 100

- 数据库 0.01 秒完成，一个页面一个 SQL。数据库不能保证能完成 100 次
- 数据库缓存、数据库缓存

QPS 800

- 8MB 带宽，每个页面 10K，带宽已经吃完了
- CDN 加速、负载均衡

QPS 1000

- Memcache 的悲观锁并发在 2w 左右，但是内网带宽已经吃完了
- 静态 HTML 缓存

QPS 2000

- 文件系统访问锁成为灾难
- 业务分离，分布式存储

## 解决案例

### 流量优化

- 防盗链处理

### 前端优化

- 减少 HTTP 请求，合并文件
- 添加异步请求
- 游览器缓存 文件压缩 webp GZIP
- CDN 加载
- 独立图片服务器

### 服务端优化

- 针对功能优化服务器配置，IO CPU
- 页面静态化
- 并发处理 队列 异步

### 数据库优化

- 数据库缓存
- 分库分表、分区处理 mycat
- 读写分离
- 负载均衡

### Web 服务器优化

- NGINX 反向代理 负载均衡

## 防盗链

盗链，自己的页面上展示并不是直接服务器上的内容。

盗链可以减轻自己服务器的负担，因为真实的空间和流量均来自别人的服务器，illegal

### 工作原理

1、refer 判断来源

NGINX 模块 valid_referers none | blocked | server_names | string...;

```
location ~ .*\.(gif|jpg|png|flv|rar|zip)$
{
    valid_referers none blocked imooc.com *.imooc.com;
    if ($invalid_referer)
    {
        # return 403;
        rewrite ^/ http://.../403.jpg;
    }
}
```

- 可以伪造 referer 破解

2、加密签名

NGINX HttpAccessKeyModule 实现 Nginx 防盗链

- accesskey on | off
- accesskey_hashmethod md5 | sha-a
- accesskey_arg GET 参数名
- accesskey_signature 加密规则

```
location ~ .*\.(gif|jpg|png|flv|rar|zip)$
{
    accesskey on;
    accesskey_hashmethod md5;
    accesskey_arg "key";
    accesskey_signature "mypass$remote_addr";
}
```

`.jpg?key=md5(mypass$remote_addr)`

## 减少 HTTP 请求

性能黄金法则：只有 10% - 20% 的用户响应时间花在接收的 HTML 文档上，剩下的 80% 花在 HTML 所引用的组件上 （image script css）

HTTP 连接产生的开销：

域名解析 - TCP 连接 - 发送请求 - 等待 - 下载资源 - 解析时间

- DNS 缓存 - 但是 查找缓存需要时间
- HTTP 1.1 Keep-Alive - 但是只能串行发送

1、图片地图

一个图片关联多个 URL。`<map>` `<area>`

2、CSS Sprites

background-position

3、合并 JS CSS

4、图片使用 base64 编码

```
"data:image/gif;base64,.."
```

## 游览器缓存和数据压缩

- 200 from cache 本地缓存
- 304 Not Modified 协商缓存，不返回响应体

### 本地缓存

如果游览器认为缓存是有效的就不会请求服务器。

Header:

- Expires HTTP 1.0 客户端、服务端缓存时间可能不一致
- Cache-Control HTTP 1.1 解决上述问题，缓存时间间隔 no-store no-cache max-age=seconds
- Cache-Control 优先于 Expires

### 协商缓存

Header:

- Last-Modified 格林威治时间
- If-Modified-Since request 时携带
- ETag HTTP 1.1
- If-None-Match request 时携带

### 缓存策略

- 本地缓存：image js css
- 协商缓存：HTML 文件 加入文件签名拒绝缓存

NGINX 配置缓存策略

```
expires time;
```

```
add_header cache-control max-age=3600;
```

### 资源压缩

工具：tinypng JpegMini ImageOptim

gzip

## CDN

content delivery network 内容分发网络

在网络各处放置节点服务器所构成的在现有的互联网基础之上的一层智能虚拟网络

- 本地 Cache 加速
- 跨运营商的网络加速
- 负载均衡技术，智能选择 Cache 服务器
- 减轻原站点 Web 服务器负载等功能
- 冗余机制 预防黑客入侵

输入域名发起请求 -> 智能 DNS 解析 -> 获取缓存服务器 IP（地理 网络类型 路由最短） -> 把内容返回用户（如果缓存有） -> 向源站发起请求 -> 将结果返回给用户 -> 将结果存入缓存服务器

LVS 做 4 层负载均衡

squid 反向代理

## 独立图片服务器

分担 Web 服务器的 IO 负载，对图片服务器针对性优化 IO，降低 CPU 计算

采用独立域名：

- 同于域名下游览器并发连接数有限制
- cookie 不利于缓存

## 动态语言静态化

## 动态语言并发处理

### 进程

进程 运行活动 系统进行资源分配和调度的基本单位

- 进程 三态模型 运行、就绪、阻塞
- 进程 五态模型 新建态 活跃态 静止就绪 活跃阻塞 静止阻塞

### 线程

线程 共享进程所拥有资源 系统独立调度和分派 CPU 的基本单位

在单个程序中同时运行多个线程完成不同的工作 称为多线程

### 协程

### 同步阻塞

1. 创建一个 socket
1. 进入 while 循环 阻塞在进程 accept 操作上，等待客户端连接进入
1. 主进程在多进程模型下通过 fork 创建子进程
