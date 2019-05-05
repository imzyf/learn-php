<?php

$a = 'aa';

$str = 'a b \n c $a';
echo $str;

echo "\n\n";

$str = "a b \n c $a";
echo $str;

$str = "a b c '$a'";
var_dump($str);

$str = "a b c {$a}";
var_dump($str);

$str = "a b c '{$a}'";
var_dump($str);

echo "\n\n";

$str = <<<EOD
Example of string
spanning multiple lines
using heredoc syntax.
EOD;

echo $str;

var_dump(array(<<<EOD
foobar!
EOD
));

$name = 'Yifan';

echo <<<EOT
My name is "$name". 
EOT;

echo "\n\n";

echo <<<'EOT'
My name is "$name". 
EOT;


class foo {
    public $bar = <<<'EOT'
bar
EOT;
}

var_dump(new foo());

class foo2 {
    public $bar = <<<EOT
bar
EOT;
}

var_dump(new foo2());