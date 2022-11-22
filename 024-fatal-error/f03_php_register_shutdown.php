<?php

// set_error_handler 捕获致命错误
register_shutdown_function(function () {
    $e = error_get_last();
    var_dump($e);
});

function test()
{
    not_exist();
}

test();
