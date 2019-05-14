# SQL 语句编写

## 关联更新

```
update A,B set a.c1 = b.c1 where A.id = B.id and B.age > 5
update A inner join B on A.id = B.id set A.c1 = B.c1 where B.age > 5
```

## 关联查询

- cross join
- inner、left、right
- union

### cross

```
select * from a,b,c
select * from a cross join b cross join c
```

### 内连接

- 等值连接 a.id = b.id
- 不等值连接 a.id > b.id
- 自连接 t1 = t2

### 联合

select \* from a union select \* from b union ...

- 列数要相等，相同记录行会合并
- union all 不会合并重复的记录行

## 优化

### 分析 SQL 查询

1、记录慢查询日志

2、show profiles

```
set profiling = 1;
show profiles;
select * from a;
show profiles;
show profile for query 2(id);
```

3、show status

- show global status

4、show processlist

5、explain

- 别名 desc

### 优化查询过程中的数据访问

- 访问数据太多
- 检查是否使用不需要的的行、列，指定列
- 是否扫描额外的数据
- 修改数据表范式，添加冗余

### 优化长难

一条大的 SQL 好，还是多条小的好

- 切分查询
- 分解关联查询，让缓存的效率更高，减少锁竞争

### 优化特定

count(\*)

- 使用 explain 查询近似值，用近似值代替 count(\*)
- 增加汇总表

关联查询

- 确定 on 或者 using 子句的列上有索引
- 确保 group by, order by 只用一个表中的列，这样 MySQL 才有可能使用索引

子查询

- 使用关联查询代替

group by、distinct

- order by null 不再进行排序

优化 limit 分页

- 记录上一次查询的最大 id，下次根据 id 查询

UNION

- 使用 UNION ALL 代替，程序中处理
