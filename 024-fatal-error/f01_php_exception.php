<?php

// 普通异常捕获
function test()
{
    try {
        throw new Exception('Error Processing Request', 1);
    } catch (Exception $e) {
        echo $e->getCode()."\n";
        echo $e->getMessage()."\n";
        echo $e->getLine()."\n";
        echo $e->getFile()."\n";
    }
}

test();
