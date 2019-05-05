<?php

$a = 1; /* global scope */

function Test()
{ 
    echo $a; /* reference to local scope variable */
}

Test();