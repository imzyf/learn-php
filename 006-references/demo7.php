<?php 

class Person
{
    public $name = 'zhao';
}

$p1 = 1;

$GLOBALS['baz'] = 2;

function test(&$p) {  
  var_dump($GLOBALS['baz']);
  xdebug_debug_zval('p');  
  $p = &$GLOBALS["baz"]; 
  var_dump($p);
  xdebug_debug_zval('p');
}
xdebug_debug_zval('p1');
// var_dump(memory_get_usage());

test($p1);
xdebug_debug_zval('p1');
xdebug_debug_zval('baz');
var_dump($p1); 
