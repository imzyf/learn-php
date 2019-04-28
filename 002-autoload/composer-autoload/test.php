<?php

require 'vendor/autoload.php';

use Monolog\Logger;
use Imzyf\Model\Person;

$p = new Person('Yifan', 10);
var_dump($p);

$log = new Logger('name');
// add records to the log
$log->warning('Foo');

$dog = new Dog('doo1');
var_dump($dog);