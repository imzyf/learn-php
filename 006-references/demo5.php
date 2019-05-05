<?php

$a = 1;

// 指向同一个地方
$b = &$a;

// unset 只会取消引用，不会销毁内存空间
unset($b);

echo $a; // 1
