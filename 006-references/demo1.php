<?php

$a = range(0, 1000);
var_dump(memory_get_usage());

// a 的值赋值给 b
// COW copy on write
$b = $a;
var_dump(memory_get_usage());

// 验证 COW - 内存大量增长，说明开辟了新空间
$a = range(0, 1000);
var_dump(memory_get_usage());
