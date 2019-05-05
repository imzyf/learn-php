<?php

$arr = array ('a', 'b', 'c', 'd', 'e');
reset($arr);
while (list($k, $v) = each($arr)) {
    # 当前指针已经被指向了下一位
    $curr = current($arr);
    echo "{$k} => {$v} -- {$curr}\n";
}

while (list($k, $v) = each($arr)) { 
    var_dump('list');
}

 