<?php

// 创建 打开文件
$file = './testfile.txt';
$handle = fopen($file, 'x');
fwrite($handle, 'first line');
fclose($handle);

$handle = fopen($file, 'r');
// r+ 写会覆盖原来的内容
$content = fread($handle, filesize($file));
fclose($handle);

$content = "hello world\n".$content;

$handle = fopen($file, 'w');
fwrite($handle, $content);
fclose($handle);
