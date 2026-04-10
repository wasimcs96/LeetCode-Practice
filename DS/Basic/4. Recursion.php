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

//SubSeqance problems

//print all subsequnce of arrays and
$arr = [1,2,3];
printSub(0, 3, $arr, []);
function printSub($i, $n, $arr, $result){

    if($i >= $n){
        print_r($result); 
        return;
    }
    //exclude
    printSub($i+1, $n, $arr, $result);

    //include
    array_push($result, $arr[$i]);
    printSub($i+1, $n, $arr, $result);
    
}
//print all subsequnce of arrays which have sum equal to k
$arr = [1,2,3];
printSubSumOfK(0, 3, $arr, [], 3);
function printSubSumOfK($i, $n, $arr, $result, $sum){
    if($i >= $n){
        if(array_sum($result) == $sum)
            print_r($result); 
        return;
    }
    //exclude
    printSub($i+1, $n, $arr, $result);

    //include
    array_push($result, $arr[$i]);
    printSub($i+1, $n, $arr, $result);
    
}
//OR With $sum passing
printSubSumofKWithSum(0, 3, $arr, [], 3, 0);
function printSubSumofKWithSum($i, $n, $arr, $result, $target, $sum){
    if($i >= $n){
        if($target == $sum)
            print_r($result); 
        return;
    }
    //exclude
    printSubSumofKWithSum($i+1, $n, $arr, $result, $target, $sum);

    //include
    array_push($result, $arr[$i]);
    $sum += $arr[$i];
    printSubSumofKWithSum($i+1, $n, $arr, $result, $target, $sum);
    
}
//print Only one subsequnce of arrays which have sum equal to k
$arr = [1,2,3];
printOnlyOneSubSumOfKWithSum(0, 3, $arr, [], 3, 0);
function printOnlyOneSubSumOfKWithSum($i, $n, $arr, $result, $target, $sum){
    if($i >= $n){
        if($target == $sum)
            print_r($result); return true;

        return false;
    }
    //exclude
    $calling1 = printOnlyOneSubSumOfKWithSum($i+1, $n, $arr, $result, $target, $sum);
    if($calling1) return;

    //include
    array_push($result, $arr[$i]);
    $sum += $arr[$i];
    $calling2 = printOnlyOneSubSumOfKWithSum($i+1, $n, $arr, $result, $target, $sum);
    if($calling2) return;
    
}

//return count of subsequnce of arrays which have sum equal to k
$arr = [1,2,3];
// $count = 0;
// echo printCountSSSumOfKWithSum(0, 3, $arr, 3, 0, $count);

function printCountSSSumOfKWithSum($i, $n, $arr, $target, $sum, &$count){
    // add this if all arr's element will be positive
    if($target < $sum) return 0;

    if($i >= $n){
        if($target == $sum)
            $count++;

        return;
    }
    //exclude
    printCountSSSumOfKWithSum($i+1, $n, $arr, $target, $sum, $count);
  

    //include
    $sum += $arr[$i];
    printCountSSSumOfKWithSum($i+1, $n, $arr, $target, $sum, $count);

    return $count;
    
}

//OR
echo printCountSSSumOfKWithSum(0, 3, $arr, 3, 0);

function printCountSSSumOfKWithSum($i, $n, $arr, $target, $sum, $map){
    //add this if all arr's element will be positive
    if($target < $sum) return 0;

    if($i >= $n){
        if($target == $sum)
            return 1;

        return 0;
    }

    // Check if result is already memoized
    $memoKey = $i."-".$sum;
    if ($map[$memoKey]) return $map[$memoKey];
    //exclude
    $left = printCountSSSumOfKWithSum($i+1, $n, $arr, $target, $sum);
  
    //include
    $sum += $arr[$i];
    $right = printCountSSSumOfKWithSum($i+1, $n, $arr, $target, $sum);

    // Update memoization table
    $map[$memoKey] = $left+$right;
    return $left+$right;
    
}

//39. Combination Sum
$candidates = [2]; $target = 1; 
combinationSum($candidates, $target);
function combinationSum($candidates, $target) {
    $result = []; $i=0; $tempArr = [];
    combinationSumRecursive($candidates, $target, $i, $tempArr, $result);
    print_r($result);
}
//use target-$$candidates[$i] also and change base condition also
function combinationSumRecursive($candidates, $target, $i, $tempArr, &$result){
    if($i >= count($candidates)){
        return;
    }

    if(array_sum($tempArr) == $target){
        $result[] = $tempArr;
        return;
    }elseif(array_sum($tempArr) > $target){
        return;
    }else{
        $tempArr[] = $candidates[$i];
    }

    combinationSumRecursive($candidates, $target, $i, $tempArr, $result);

    array_pop($tempArr);
    combinationSumRecursive($candidates, $target, $i+1, $tempArr, $result);

    return;
}

//Subset-1
class Solution {
    /**
     * @param Integer[] $candidates
     * @param Integer $target
     * @return Integer[][]
     */
    function subsetSums($candidates) {
        $result = []; $i=0; $n = count($candidates); $temp = 0;
        $this->subsetSumsHelper($candidates, $result, $i, $n, $temp);
        return $result;
    }



    function subsetSumsHelper($candidates, &$result, $i, $n, $temp){
        if($i >= $n){
            $result[] = $temp;
            return;
        }
        //exclude
        $this->subsetSumsHelper($candidates, $result, $i+1, $n, $temp);
        $this->subsetSumsHelper($candidates, $result, $i+1, $n, $temp+$candidates[$i]);
    }
}

$solu = new Solution();
$candidates = [2, 5, 8, 11, 13];

$combinations = $solu->subsetSums($candidates);
sort($combinations);
echo implode(", ", $combinations);

//Subset-2
function subsetsWithDup($nums) {
    $result = []; $i=0; $n = count($nums); $temp = [];
    sort($nums);
    $this->subsetsWithDupHelper($nums, $result, $i, $n, $temp);
    return $result;
}
function subsetsWithDupHelper($candidates, &$result, $i, $n, $temp){
    if($i >= $n){
        sort($temp);
        $key = implode(',', $temp);
        $result[$key] = $temp;
        echo $key."\n";
        return;
    }
    //exclude
    $this->subsetSumsHelper($candidates, $result, $i+1, $n, $temp);
    $temp[] = $candidates[$i];
    $this->subsetSumsHelper($candidates, $result, $i+1, $n, $temp);
}

?>