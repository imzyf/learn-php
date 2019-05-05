# 文件及目录处理

## 文件读写

### 读

- fopen [**打开模式**](https://www.php.net/manual/zh/function.fopen.php)
- file_get_contents

### 写

- fwrite
- fput
- file_put_contents

### 关闭

- fclose

### 远程文件

## 目录

- 名称：basename dirname pathinfo
- 读取：opendir readdir closedir rewinddir
- 删除：rmdir（内容为空）
- 创建：mkdir
- 文件大小：filesize
- 磁盘大小：disk_free_spase disk_total_space
- 复制文件：copy
- 删除文件：unlink
- 文件类型：filetype
- 文件重命名：rename
- 文件截取：ftruncate
- 文件属性：file_exists is_readable is_writable is_executable filectime(创建时间) fileatime(访问时间) filemtime
- 文件锁：flock
- 文件指针：ftell fseek rewind

## 练习

在文件头部循环添加内容

```
$ php test1.php 
```

变量目录中的文件

```
$ php test2.php 
```
