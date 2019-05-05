<?php

$var1 = 5;
$var2 = 10;

function foo(&$my_var) {
    global $var1;
    $var1 += 2;
    $var2 = 4;
    $my_var += 3;

    return $var2;
}

$my_var = 5; 
echo foo($my_var)."\n"; // 4
echo $my_var."\n"; // 8
echo $var1; // 7
echo $var2; // 10

/* 
$var1 7
$var2 10
_$var2 4
$my_var 8
*/
 
$bar = 'foo';
$my_var = 10;
echo $bar($my_var)."\n"; // 4
 