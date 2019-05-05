<?php

// zval 结构体 变量容器
$a = range(0, 2);
xdebug_debug_zval('a');

$b = $a;
xdebug_debug_zval('a');

// 开辟新空间
$a = range(0, 2);
xdebug_debug_zval('a');
xdebug_debug_zval('b');

/* 
a: (refcount=1, is_ref=0)=array(3) {
  [0] =>
  (refcount=0, is_ref=0)=int(0)
  [1] =>
  (refcount=0, is_ref=0)=int(1)
  [2] =>
  (refcount=0, is_ref=0)=int(2)
}

a: (refcount=2, is_ref=0)=array(3) {
  [0] =>
  (refcount=0, is_ref=0)=int(0)
  [1] =>
  (refcount=0, is_ref=0)=int(1)
  [2] =>
  (refcount=0, is_ref=0)=int(2)
}

a: (refcount=1, is_ref=0)=array(3) {
  [0] =>
  (refcount=0, is_ref=0)=int(0)
  [1] =>
  (refcount=0, is_ref=0)=int(1)
  [2] =>
  (refcount=0, is_ref=0)=int(2)
} 

b: (refcount=1, is_ref=0)=array(3) {
  [0] =>
  (refcount=0, is_ref=0)=int(0)
  [1] =>
  (refcount=0, is_ref=0)=int(1)
  [2] =>
  (refcount=0, is_ref=0)=int(2)
}
*/