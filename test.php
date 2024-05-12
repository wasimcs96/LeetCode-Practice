<?php
$arr = [1,2,3];
printSub(0, 3, $arr, []);

function printSub($i, $n, $arr, $result){

    if($i >= $n){
        print_r($result); 
        return;
    }
    printSub($i+1, $n, $arr, $result);

    array_push($result, $arr[$i]);
    printSub($i+1, $n, $arr, $result);
    
}

?>
              