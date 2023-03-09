<?php

$coba=array();
$coba[0]=0;
$coba[1]=1;
$coba[2]=2;
unset ($coba[0]);
for ($i = 0 ; $i < 3 ;$i++){
    echo $coba[$i]."</br>";
}

?>
