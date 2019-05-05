# 会话控制

## cookie

> - [cookie - php.net](https://www.php.net/manual/zh/features.cookies.php)

cookie 优点：节约服务器资源，缺点：不安全、用户可能禁用

## session

session 是基于 cookie 的，cookie 存储 sessionid。

`SID` 是 `session_name() . '=' . session_id()`

session 存储到内存服务器 `session_set_save_handler()`

```
session.gc_divisor	1000 
session.gc_maxlifetime	1440 // 过期时间
session.gc_probability	1 
```

回收机制：每 1000 次 1 次清理过期的 session