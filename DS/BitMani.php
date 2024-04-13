<?php 


Decimal	Hexadecimal	Binary
0	0	0000
1	1	0001
2	2	0010
3	3	0011
4	4	0100
5	5	0101    25=11001
6	6	0110
7	7	0111
8	8	1000
9	9	1001
10	A	1010
11	B	1011
12	C	1100
13	D	1101
14	E	1110
15	F	1111

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
        $subsets = 1 << $length;
        for($i=0;$i<$subsets;$i++){
            for($j=0;$j<$length;$j++){
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

?>