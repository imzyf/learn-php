<?php

function MyErrorHandler($error, $errstr, $errfile, $errline)
{
    echo 'Custom error: '.$error.' : '.$errstr."\n";
    echo "Error on line $errline in ".$errfile."\n";
}

set_error_handler('MyErrorHandler', E_ALL | E_STRICT);

function test()
{
    trigger_error('some error');
    1 / 0;
}

test();
