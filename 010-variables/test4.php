<?php

$count = 5;
function get_count()
{
    static $count;

    return $count++; // NULL 1
}

echo $count; // 5
++$count; // 6

echo get_count(); 
echo get_count();

/* 
NULL 不被输出

结果为：
51
*/