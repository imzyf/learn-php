<?php

echo 4 ** 3;

class Cat
{
}

$c1 = new Cat();
$c2 = new Cat();

var_dump($c1 === $c2);

$a = null;
++$a;
var_dump($a);

$a = false || true;
$b = false or true;
var_dump($a);
var_dump($b);
