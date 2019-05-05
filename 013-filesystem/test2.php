<?php

$dir = './../002-autoload';
// 打开目录
// 读取目录中的文件
// 如果文件类型是目录，继续打开目录
// 读取子目录中的文件，输出文件名
// 关闭目录

function loopDir($dir)
{
    $handle = opendir($dir);
    while (false !== ($file = readdir($handle))) {
        if ('.' == $file || '..' == $file) {
            continue;
        } 
        if ('dir' == filetype($dir.'/'.$file)) {
            echo $dir.'/'.$file."\n";
            loopDir($dir.'/'.$file);
        } else {
            echo $file."\n";
        }
    }
}

loopDir($dir);
