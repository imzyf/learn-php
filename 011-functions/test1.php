<?php

function &mgFunc()
{
    static $b = 10;

    return $b;
}

$a = mgFunc();
// 返回引用
$b = &mgFunc();
$b = 100; 

var_dump(mgFunc());
var_dump($a);
