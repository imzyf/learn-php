<?php

class Person1
{
    public $name;
    public $age;

    public function __construct($name, $age)
    {
        $this->name = $name.'MODEL!!!';
        $this->age = $age;
    }
}
