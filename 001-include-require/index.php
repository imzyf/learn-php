<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>

<body>
    <h2>Base</h2> 
    <ul>
        <li><?php include 'header.php'; ?></li>
        <li><?php require 'header.php'; ?></li>
        <li><?php require('header.php'); ?></li>
    </ul>

    <h2>vars</h2>
    <ul>
        <li>
        <?php 
            include 'vars.php';
            echo "I have a $color $car"; // I have a red BMW
            ?>  
        </li>
    </ul>

    <h2>error</h2>

    <?php include 'no-such-file.php'; 
        echo 'include errors'
    ?> 

    <p>errors include p</p>
 
    <?php //require 'no-such-file.php'; 
        echo 'require errors'
    ?> 
 
    <?php  
        echo 'errors'
    ?> 
    <p>errors p</p>

    <h2>function</h2>

    <?php  
        include 'func1.php';
        echo '<p>line 1</p>';
        // include 'func1.php';
        echo '<p>line 2</p>';
        include_once 'func1.php';
        echo '<p>line 3</p>'; 
    ?> 

    <?php 
    $i = 1;
    while ($i <= 2) {
        // include "var$i.php"; 
        // require_once "var$i.php"; 
        $i ++;
    } 
    var_dump($v1,$v2)
        
    ?> 

</body>

</html>