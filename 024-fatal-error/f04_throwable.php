<?php

error_reporting(E_ALL); //报告所有错误

try {
    not_exist();
} catch (Throwable $e) {
    echo $e->getCode()."\n";
    echo $e->getMessage()."\n";
    echo $e->getLine()."\n";
    echo $e->getFile()."\n";
}
