<?php

$a = range(0, 1000);
var_dump(memory_get_usage());
 
// 指向同一个地方
$b = &$a;
var_dump(memory_get_usage());
 
// 内存不会有大的变化
$a = range(0, 1000);
var_dump(memory_get_usage());
