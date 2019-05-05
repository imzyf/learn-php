<?php

function &mgFunc()
{
    static $b = 10;

    return $b;
}

$a = mgFunc();
$b = &mgFunc();
$b = 100; 

var_dump(mgFunc());
var_dump($a);
