<?php
//printName(5);
function printName($n){
    if($n == 0) return;

    echo "Print Name \n";
    printName($n-1);
}

//printNtoOne(5);
// function printNtoOne($n){
//     if($n==0) return;
//     echo $n;
//     printNtoOne($n-1);
// }
//OR
//printNtoOne(1,5);
function printNtoOne($x, $n){
    if($x>$n) return;
    printNtoOne($x+1, $n);
    echo $x."\n";
}

// printOnetoN(1, 5);
// function printOnetoN($x, $n){
//     if($n<$x) return;
//     echo $x;
//     printOnetoN(++$x, $n);
// }
//OR
//printOnetoN(5, 5);
function printOnetoN($x, $n){
    if($x<1) return;
    printOnetoN($x-1, $n);
    echo $x;
}

//Sum of N numbers
//parmetrised
//echo sumOfNNumbers(5, 0);
function sumOfNNumbers($n, $sum){
    if($n<1) return $sum;
    return sumOfNNumbers($n-1, $sum+$n);
}
//functional
echo sumOfNNumberss(5 );
function sumOfNNumberss($n){
    if($n<1) return 0;
    return $n+sumOfNNumberss($n-1);
}

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

//pow()
$x = 2.00000; $n = -2;
echo pow($x, $n);
function myPow($x, $n) {
    if ($n == 0) return 1;

    $result = (float) (1.0);
    $absN = abs($n);

    while ($absN > 0) {
        if ($absN % 2 == 1) {
            $result *= $x;
        }
        $x *= $x;
        $absN = (int) ($absN / 2);
    }
    return  ($n < 0) ? (float) (1 / $result) :  (float) ($result);

    //Recursive
    if($n == 0) return 1;

    if($n > 0){
        $x = (float) (1 * myRecursivePow($x, $n));
    }else{
        $x = (float) (1 / myRecursivePow($x, $n));
    }
    return $x;
}
function myRecursivePow($x, $n) {
    if($n == 0) return 1;
    $isOdd = (int) ($n % 2);

    if($isOdd){
        $x = (float) ($x * myRecursivePow($x, $n-1));
    }else{
        $x = (float) (myRecursivePow($x*$x, $n/2));
    }
    
    return $x;
}
//using n/2 through double calling like fibonnice
$x = 2.00000; $n = -2;
if($n > 0){
    $x = (float) (1 * myDoubleRecursivePow($x, $n));
}else{
    $x = (float) (1 / myDoubleRecursivePow($x, -$n));
}

echo $x;
function myDoubleRecursivePow($x, int $n){
    if($n <= 0){
        return 1;
    }
    $isOdd = (int) ($n % 2);

    if($isOdd){
        $f1 = (float) ($x * myDoubleRecursivePow($x, $n/2));
        $f2 = (float) (myDoubleRecursivePow($x, $n/2));
    }else{
        $f1 = (float) (myDoubleRecursivePow($x, $n/2));
        $f2 = (float) (myDoubleRecursivePow($x, $n/2));
    }
    return $f1 * $f2;
}

//print all subsequnce of arrays and
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