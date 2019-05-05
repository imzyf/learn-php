<?php

$data = ['a', 'b', 'c'];

foreach ($data as $key => $val) {
    $val = &$data[$key];
}

var_dump($data);

// 1. 每次循环结束时 $data 的值是什么？
/*
    $key   $val
0   0      &$data[0]   ['a', 'b', 'c']
1   1      &$data[1]   ['b', 'b', 'c']
2   2      &$data[2]   ['b', 'c', 'c']
*/
