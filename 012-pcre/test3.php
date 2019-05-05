<?php

$str = 'chinese 中文';

$pattern = '/[\x{4e00}-\x{9fa6}]+/u';

preg_match($pattern, $str, $out);

var_dump($out);