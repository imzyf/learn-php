<?php

function __autoload($class)
{
    require_once($class . ".class.php");
}

$person = new Person("Yi", 27);
var_dump($person);
