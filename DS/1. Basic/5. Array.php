<?php

//Sum of Array Elements

function sumOfArr(array $arr){
    if(count($arr) == 0) return 0;
    $sum = 0;
    for($i=0; $i < count($arr); $i++){
        $sum += $arr[$i];
    }
    return $sum;
} 

//echo sumOfArr([1,2,3,4]);
//odd number sum
function countOfOddNum(array $arr){
    if(count($arr) == 0) return 0;
    $ount = 0;
    for($i=0; $i < count($arr); $i++){
        if($arr[$i] % 2 != 0) $ount++;
    }
    return $ount;
} 

//echo countOfOddNum([1,2,3,4,5]);

function chekArrIsSorted(array $arr){
    if(count($arr) == 0) return false;
    for($i=0; $i < count($arr)-1; $i++){
        if($arr[$i] > $arr[$i+1]) return false;
    }
    return true;
}

var_dump(chekArrIsSorted([1,2,13,14,5]));