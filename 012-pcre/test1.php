<?php

$str = '<b>abc</b>';

$pattern = '/<b>(.*)<\/b>/';

// 后向引用
// $str = preg_replace($pattern, '${1}', $str); 
// $str = preg_replace($pattern, '\\1', $str); 
$str = preg_replace($pattern, '$1', $str); 
var_dump($str);
