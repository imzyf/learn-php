<?php

function array_mer()
{
    $arrays = func_get_args();
    $result = [];
    foreach ($arrays as $array) {
        if (is_array($array)) {
            foreach ($array as  $value) {
                $result[] = $value;
            }
        }
    }

    return $result;
}

var_dump(array_mer([1], [1, 23], [2, 3, 4]));
