<?php

function mergeSort($arr)
{
    $len = count($arr);
    if ($len <= 1) {
        return $arr;
    } // 递归结束条件, 到达这步的时候, 数组就只剩下一个元素了, 也就是分离了数组

    $mid = $len / 2;
    $left = array_slice($arr, 0, $mid); // 拆分数组0-mid这部分给左边left
    $right = array_slice($arr, $mid); // 拆分数组mid-末尾这部分给右边right
    $left = mergeSort($left); // 左边拆分完后开始递归合并往上走
    $right = mergeSort($right); // 右边拆分完毕开始递归往上走

    $arr = merge($left, $right); // 合并两个数组,继续递归
    return $arr;
}

// merge函数将指定的两个有序数组(arrA, arr)合并并且排序
function merge($arrA, $arrB)
{
    $arrC = array();
    while (count($arrA) && count($arrB)) {
        // 这里不断的判断哪个值小, 就将小的值给到arrC, 但是到最后肯定要剩下几个值,
        // 不是剩下arrA里面的就是剩下arrB里面的而且这几个有序的值, 肯定比arrC里面所有的值都大所以使用
        $arrC[] = $arrA[0] < $arrB[0] ? array_shift($arrA) : array_shift($arrB);
    }

    return array_merge($arrC, $arrA, $arrB);
}

$startTime = microtime(1);

$arr = range(1, 1000);
shuffle($arr);
echo 'before sort: ', implode(', ', $arr), "\n";
$sortArr = mergeSort($arr);
echo 'after sort: ', implode(', ', $sortArr), "\n";

echo 'use time: ', microtime(1) - $startTime, "s\n";
