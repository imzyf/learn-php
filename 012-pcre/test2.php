<?php

$str = '<b>abc</b><b>abc2</b>';

$pattern = '/<b>(.*)<\/b>/';
$patternU = '/<b>(.*)<\/b>/U';
$patternU2 = '/<b>.*?<\/b>/';

preg_match_all($pattern, $str, $out);
var_dump($out);

// 贪婪模式
preg_match_all($patternU, $str, $out);
var_dump($out);

preg_match_all($patternU2, $str, $out);
var_dump($out);
