<?php

class Person
{
    public $name = 'zhao';
}

$p1 = new Person();
xdebug_debug_zval('p1');

// 对象本身就是引用传递
// 没有 copy on write
$p2 = $p1;
xdebug_debug_zval('p1');

$p2->name = 'Yi';
xdebug_debug_zval('p1');

/*
p1: (refcount=1, is_ref=0)=class Person#1 (1) {
  public $name =>
  (refcount=2, is_ref=0)=string(4) "zhao"
}

p1: (refcount=2, is_ref=0)=class Person#1 (1) {
  public $name =>
  (refcount=2, is_ref=0)=string(4) "zhao"
}

p1: (refcount=2, is_ref=0)=class Person#1 (1) {
  public $name =>
  (refcount=0, is_ref=0)=string(2) "Yi"
}
*/
