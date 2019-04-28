<?php

function autoload_in_dir($class_name, $dir)
{
    echo "autoload in " . $dir . " classname: " . $class_name . "\n";

    $class_path = $dir . $class_name . '.class.php';
    if (file_exists($class_path)) {
        require_once $class_path;
    } else {
        echo 'class file' . $class_path . 'not found!';
    }
}

function autoload_in_current_dir($class_name)
{
    autoload_in_dir($class_name, "./");
}

function autoload_in_parent_dir($class_name)
{
    autoload_in_dir($class_name, "./model/");
}

spl_autoload_register('autoload_in_current_dir');

$person = new Person("Yi", 28);
var_dump($person);

// A nice way to unregister all functions.
// $functions = spl_autoload_functions();
// foreach ($functions as $function) {
//     spl_autoload_unregister($function);
// }

spl_autoload_unregister("autoload_in_current_dir");
// spl_autoload_register("autoload_in_parent_dir");

// $person = new Person1("Yifan1", 28);
// var_dump($person);
$person = new Person("Yifan", 28);
var_dump($person);
 