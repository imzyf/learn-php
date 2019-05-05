<?php

$a = 1; /* global scope */
$b = 2;
function Test()
{
    global $a;
    echo $a;
    echo $GLOBALS['b'];
}

Test();
