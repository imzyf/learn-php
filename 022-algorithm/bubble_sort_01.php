<?php

// [2,4,3,9,7,1]

function bubble_sort($arr)
{
    for ($i = 0, $c = count($arr); $i < $c; ++$i) {
        $flag = true;
        for ($j = 0; $j < $c - 1; ++$j) {
            if ($arr[$j] < $arr[$j + 1]) {
                $tmp = $arr[$j];
                $arr[$j] = $arr[$j + 1];
                $arr[$j + 1] = $tmp;
                $flag = false;
            }
        }
        if ($flag) {
            break;
        }
    }

    return $arr;
}

$arr = [2, 4, 3, 9, 7, 1];
var_dump(bubble_sort($arr));
