<?php

class RedPackage
{
    // remainSize 剩余的红包数量
    // remainMoney 剩余的钱
    public $remainSize;
    public $remainMoney;

    public function __construct($remainSize, $remainMoney)
    {
        $this->remainSize = $remainSize;
        $this->remainMoney = $remainMoney;
    }
}

function getRandomMoney(RedPackage $redPackage)
{
    if (0 == $redPackage->remainSize || 0 == $redPackage->remainMoney) {
        return 0;
    }

    if (1 == $redPackage->remainSize) {
        --$redPackage->remainSize;

        return $redPackage->remainMoney;
    }

    $min = 0.01;
    $max = bcdiv(bcmul($redPackage->remainMoney, 2, 2), $redPackage->remainSize, 2);

    // 左开右闭
    $result = mt_rand(bcmul($min, 100), bcmul($max, 100) - 1);
    $result = bcdiv($result, 100, 2);
    $result = $result < $min ? $min : $result;

    $redPackage->remainMoney = bcsub($redPackage->remainMoney, $result, 2);
    --$redPackage->remainSize;

    return $result;
}

$redPackage = new RedPackage(3, 1);
while ($redPackage->remainSize > 0) {
    var_dump(getRandomMoney($redPackage));
}
