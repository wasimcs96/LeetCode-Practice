<?php

//Factorial a number
function factorial($num) {
    if($num == 0) return 1;
    return $num*factorial($num-1);
  }
  echo factorial(4);

//Reverse a array 
function reverseArray(&$input, $i, $j) {
    if($i > $j){
        return;
    }

    list($input[$j], $input[$i]) = [$input[$i], $input[$j]];
    $i++; $j--;
    return reverseArray($input, $i, $j);
    
}
$arr = [1,2,3,4];
$str = 0;
$end = count($arr)-1;
reverseArray($arr, $str, $end);
print_r($arr); 


function reverseArrayy(&$input, $i, $n) {
    if($i >= (int)$n/2){
        return;
    }
    list($input[$n-$i-1], $input[$i]) = [$input[$i], $input[$n-$i-1]]; //$j replace by $n-$i-1
    $i++;
    return reverseArrayy($input, $i, $n);
    
}
$arr = [1,2,3,4];
$i = 0;
$n = count($arr);
reverseArrayy($arr, $i, $n);
print_r($arr); 


// check palendromeString
function palendromeString($input, $i, $n) {
    if($i >= (int)$n/2){
        return true;
    }
    if($input[$i] != $input[$n-$i-1]){
        return false;
    }

    $i++;
    return palendromeString($input, $i, $n);
    
}


$str = "asw dfd wsa";
$str = str_split($str);
$i = 0;
$n = count($str);
var_dump(palendromeString($str, $i, $n));


//Print Fibonacci Series up to Nth term

$n = 8;
$first = 0; $second = 1;
echo $first.", ";
for($i=2; $i<=$n;$i++){
    echo $second.", ";
    $temp = $first + $second;
    $first = $second; 
    $second = $temp;
}

//Time Complexity: O(2^N)  Space Complexity: O(N) 
function fibonnic($n){

    if ($n == 0 || $n==1 ) {
        return $n;
    }

    $ans =   fibonnic($n-1) + fibonnic($n-2);
    return $ans;
}



echo fibonnic(7);
?>