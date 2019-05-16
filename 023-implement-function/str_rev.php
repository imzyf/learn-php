<?php

function str_rev($str)
{
    $count = 0;
    while (true) {
        if (isset($str[$count])) {
            ++$count;
        } else {
            break;
        }
    }

    for ($i = 0; $i < $count / 2; ++$i) {
        $tmp = $str[$i];
        $str[$i] = $str[$count - $i - 1];
        $str[$count - $i - 1] = $tmp;
    }

    return $str;
}

var_dump(str_rev('yifan'));
var_dump(str_rev('oahz'));
var_dump(str_rev(''));
