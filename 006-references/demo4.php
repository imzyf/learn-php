<?php

$a = range(0, 2);
xdebug_debug_zval('a');

// 指向同一个地方
$b = &$a;
xdebug_debug_zval('a');

// 内存不会有大的变化
$a = range(0, 2);
// a b 指向的是同一空间
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

a: (refcount=2, is_ref=1)=array(3) {
  [0] =>
  (refcount=0, is_ref=0)=int(0)
  [1] =>
  (refcount=0, is_ref=0)=int(1)
  [2] =>
  (refcount=0, is_ref=0)=int(2)
}

a: (refcount=2, is_ref=1)=array(3) {
  [0] =>
  (refcount=0, is_ref=0)=int(0)
  [1] =>
  (refcount=0, is_ref=0)=int(1)
  [2] =>
  (refcount=0, is_ref=0)=int(2)
}

b: (refcount=2, is_ref=1)=array(3) {
  [0] =>
  (refcount=0, is_ref=0)=int(0)
  [1] =>
  (refcount=0, is_ref=0)=int(1)
  [2] =>
  (refcount=0, is_ref=0)=int(2)
}
 */
