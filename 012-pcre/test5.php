<?php

$str = '<img class="id" src="baidu.com/xx/img.jpg" alt="title">';

$pattern = '/<img.*src="(.*)".*\/?>/U';

preg_match($pattern, $str, $out);

var_dump($out);