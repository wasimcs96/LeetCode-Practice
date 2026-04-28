<?php 




//Get ReverNumber

$num = 1234;
$reversNum = 0;
while((int) $num > 0){
    $lastDigit = $num % 10;
    $reversNum = $reversNum*10 + $lastDigit;
    $num /= 10;
}

//echo $reversNum;


//Decimal to Binery

$num = 1;
$bineryNum = 0;
while((int) $num > 0){
    $lastDigit = $num % 2;
    $bineryNum = $bineryNum*10 + $lastDigit;
    $num /= 2;
}
//show $bineryNum
// /echo $bineryNum;


//Binery To decimal
$num = 101;
$result = 0;
$x = 1;

while((int)$num > 0){
    $lastNum = $num%10;
    $result += $x*$lastNum;
    $x *=2;
    $num /= 10;
}

//echo $result;

//Some Basic Question like
    //get Bit
    //Set Bit
    //Clear Bit
    //Update Bit


/*Some PAtterns <Questions>*/
// https://www.hackerearth.com/practice/basic-programming/bit-manipulation/basics-of-bit-manipulation/tutorial/
//Check number is power of 2 or not?

function checkPowerOf2($num){
    if(is_numeric($num)){
        return ($num && !($num & ($num-1)));
    }
}

//var_dump(checkPowerOf2(8));

//2) Count the number of ones in the binary representation of the given number.

// n = 23 = {10111}2 .
// 1. Initially, count = 0.
// 2. Now, n will change to n&(n-1). As n-1 = 22 = {10110}2 , then n&(n-1) will be {101112 & {10110}2, which will be {10110}2 which is equal to 22. Therefore n will change to 22 and count to 1.
// 3. As n-1 = 21 = {10101}2 , then n&(n-1) will be {10110}2 & {10101}2, which will be {10100}2 which is equal to 20. Therefore n will change to 20 and count to 2.
// 4. As n-1 = 19 = {10011}2 , then n&(n-1) will be {10100}2 & {10011}2, which will be {10000}2 which is equal to 16. Therefore n will change to 16 and count to 3.
// 5. As n-1 = 15 = {01111}2 , then n&(n-1) will be {10000}2 & {01111}2, which will be {00000}2 which is equal to 0. Therefore n will change to 0 and count to 4.
// 6. As n = 0, the the loop will terminate and gives the result as 4.

// Complexity: O(K), where K is the number of ones present in the binary form of the given number. 
// we have to divide it by 2, until it gets 0, which will take log2N of time.

function countBit($num){
    $count = 0 ;
    while($num > 0){
        $num = $num & $num-1;
        $count++;
    }
    return $count;
}
$num = 23;
//echo countBit($num);


// 4) How to generate all the possible subsets of a set ?

// 0 = (000) = {}
// 1 = (001) = {c}
// 2 = (010) = {b}
// 3 = (011) = {b, c}
// 4 = (100) = {a}
// 5 = (101) = {a, c}
// 6 = (110) = {a, b}
// 7 = (111) = {a, b, c}

function getSubsets($arr){ 
    $length = count($arr);
    if($length > 0){
        $subsets = 1 << $length; //get total number of subsets pow(2, $length)
        for($i=0;$i<$subsets;$i++){
            for($j=0;$j<$length;$j++){ //0->2
                if($i & 1<<$j){ //001 & 001 = true / 001 & 010 = flase // if bit matched then print
                    echo $arr[$j].", ";
                }
            }
            echo '<BR>';
        }
    }
}
//getSubsets([1,2,3,4]);



//Non-repeating element form array where all element exist 2 times except one

function findUniqueNumber($arr){
    $result = 0;
    for($i=0;$i<count($arr);$i++){
        $result ^= $arr[$i];
    }
    return $result;
}

$array = [1,2,3,3,5,2,1];
//echo findUniqueNumber($array);

//Two Non-repeating element form array where all element exist 2 times except two

function findTwoUniqueNumber($arr){
    $Xor = 0;
    for($i=0;$i<count($arr);$i++){
        $Xor ^= $arr[$i];
    }
    $set_bit_no = $Xor & ~($Xor - 1); //2nd bit -> 0110 == Give 2 //method the number inwhich we get first right most bit of XOR like XOR=1100 then method will give 0100 = 4 bcz in xor we have 3rd set bit and method give 0100

    $x = $y = 0;
    for($i=0;$i<count($arr);$i++){
 
        /*Xor of first set */
        if ($arr[$i] & $set_bit_no){
            echo "X-".$arr[$i].'<BR>';
            $x = $x ^ $arr[$i];
        /*Xor of second set*/
        }else {
            echo "Y-".$arr[$i].'<BR>';
            $y = $y ^ $arr[$i];
        }
    }

    return $x."-".$y;
}

$array = [1,2,3,5,2,1];
echo findTwoUniqueNumber($array); 

//Non-rpeating values form array where others values comes k-Times


function countSetBits($n)
{
    $count = 0;
    while ($n)
    {
        $count += $n & 1;
        $n >>= 1;
        echo $n."<BR>";
    }
    return $count;
}

countSetBits(6);

// Count set bits in an integer
function countSetBits($n){
    
    $count = 0;

    //first Approches
    while($n){
        $count += $n & 1;
        $n >>=1;
    }

    //Second Approches
    while($n){
        $count++;
        $n = $n & ($n-1);
    }
    return $count;
}

echo countSetBits(13);

//Find the two non-repeating elements in an array of repeating elements
function findTwoUniqueNumber($arr){
    $Xor = 0;
    for($i=0;$i<count($arr);$i++){
        $Xor ^= $arr[$i];
    }
    $set_bit_no = $Xor & ~($Xor - 1); //2nd bit -> 0110 == Give 2

    $x = $y = 0;
    for($i=0;$i<count($arr);$i++){
 
        /*Xor of first set */
        if ($arr[$i] & $set_bit_no){  //Same set bit number come in this case 0010 & 0110
            $x = $x ^ $arr[$i];
        /*Xor of second set*/
        }else {
            $y = $y ^ $arr[$i];
        }
    }

    $ans = [];
    if($x > $y){
        $ans[0] = $y;
        $ans[1] = $x;
    }else{
        $ans[0] = $x;
        $ans[1] = $y;
    }
    return $ans;
}



//Program to find whether a no is power of two
function checkPowerOf2($num){
    if(is_numeric($num)){
        return ($num && !($num & ($num-1)));
    }
}


//Find position of the only set bit
 
// Returns position of the
// only set bit in 'n'
function findPosition($n)
{
    if (!isPowerOfTwo($n))
        return -1;
 
    $i = 1;
    $pos = 1;
 
    // Iterate through bits of n
    // till we find a set bit i&n
    // will be non-zero only when
    // 'i' and 'n' have a set bit
    // at same position
    while (!($i & $n))
    {
        // Unset current bit and
        // set the next bit in 'i'
        $i = $i << 1;
 
        // increment position
        ++$pos;
    }
    return $pos;
}


//Count number of bits to be flipped to convert A to B
function countBitsFlip($a, $b)
{
    $xor = $a ^ $b;
    $count = 0;
    while($xor){
        $count++;
        $xor = $xor & ($xor-1);
    }
    
    return $count;
}


//Count total set bits in first N Natural Numbers (all numbers from 1 to N)
function countSetBits($n)
{
    $bitCount = 0; // initialize the result
 
    for ($i = 1; $i <= $n; $i++)
        $bitCount += countSetBitsUtil($i);
 
    return $bitCount;
}
 
// A utility function to count
// set bits in a number x
function countSetBitsUtil($x)
{
    if ($x <= 0)
        return 0;
    return ($x % 2 == 0 ? 0 : 1) + countSetBitsUtil($x / 2);
   //return 1 + countSetBitsUtil(($x & ($x-1)));

}
 
// Driver Code
$n = 4;
echo "Total set bit count is " .countSetBits($n);

//Copy set bits in a range
function copySetBits($x, $y, $l, $r){
    if($l < 1 || $r > 32){
        return $x;
    }

    for($i = $l; $i <= $r; $i++){
        $mask = 1 << ($i-1); // set bit at $l postion  like 0010
        if(($y & $mask) != 0){ //check set bit at same postion of $y 1010 & 0010 ->($mask) if true then update $x setbit at same position
            $x = $x | $mask;
        }
    }

    return $x;
}

$x = 8; $y = 7; $l = 1; $r = 2;
echo copySetBits($x, $y, $l, $r);



//Calculate square of a number without using *, / and pow()
function square($n)
{
     
    // Base case
    if ($n==0) return 0;
 
    // Handle negative number 
    if ($n < 0) $n = -$n;
 
    // Get floor(n/2)
    // using right shift
    $x = $n >> 1;   
 
    // If n is odd
    if ($n & 1)
        return ((square($x) << 2) + ($x << 2) + 1);
    else // If n is even
        return (square($x) << 2);
}
echo "n = ", 4, ", n^2 = ", square(4),"\n";

?>