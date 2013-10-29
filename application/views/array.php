<?php

$myarray = array('one','two','three','four');


?>
<html>
    <head>
        <title><?php echo($title); ?></title>
    </head>
    <h1><?php echo($heading); ?></h1>
    <?php 
    foreach ($myarray as $item) {
        if($item == "four"){
            echo $item;
        }
        else{
      echo $item, ",<br/>";  
        }
    }
    ?>
    
    <h1><?php echo($heading2); ?></h1>
    
    <?php
    $counter=0;
    while ($counter < count($myarray)) {
        if ($myarray[$counter] == "four") {
            echo $myarray[$counter], "<br/>";
        }
        else {
            echo $myarray[$counter], ",<br/>";
        }
        $counter++;
    }
    
    
    
    
    
    ?>
    
</html>