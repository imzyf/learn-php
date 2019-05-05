<?php

$str = '13912345678';

$pattern = '/^139\d{8}$/';

preg_match($pattern, $str, $out);

var_dump($out);