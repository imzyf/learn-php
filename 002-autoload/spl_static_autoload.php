<?php

// 静态调用

class Loader
{
    public static function load($class_name)
    {
        $class_path = $class_name . '.class.php';
        if (file_exists($class_path)) {
            require_once $class_path;
        } else {
            echo 'class file' . $class_path . 'not found!';
        }
    }
}

spl_autoload_register("Loader::load");
// spl_autoload_register( ["Loader","load" ] );

$person = new Person("Yifan", 28);
var_dump($person);
