<?php 

//<!-- Question List -->


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