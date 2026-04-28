<?php
// Decimal	    Hexa	Binary
// 0	        0	    0000
// 1	        1	    0001
// 2	        2	    0010
// 3	        3	    0011
// 4	        4	    0100
// 5	        5	    0101    
// 6	        6	    0110
// 7	        7	    0111
// 8	        8	    1000
// 9	        9	    1001
// 10	        A	    1010
// 11	        B	    1011
// 12	        C	    1100
// 13	        D	    1101
// 14	        E	    1110
// 15	        F	    1111




//Get ReverNumber
function reverse($num){
    $reversNum = 0;
    while((int) $num > 0){
        $lastDigit = $num % 10;
        $reversNum = $reversNum*10 + $lastDigit;
        $num /= 10;
    }
    return $reversNum;
}

//convert decimal number to binery
echo decToBin(45)."\n";
function decToBin($dec){
    $bin = '';
    while($dec){
        $bin = ($dec % 2) . $bin;
        $dec = floor($dec / 2);
    }
    return $bin;
}

//convert binery to decimal
echo binToDec("101101");
function binToDec($bin){
    $number = 0;
    $bitLength = strlen($bin); $pow = 1;
    while($bitLength){
        $bit = $bin[$bitLength-1];
        if($bit == 1) $number += $pow;
        $pow *= 2; // 2,4,8
        $bitLength--;
    }
    return $number;
}

//swap Two numbers
function swap(&$a, &$b){
    $a = $a ^ $b;
    $b = $a ^ $b; //($a ^ $b) ^ $b   => $a
    $a = $a ^ $b; //($a ^ $b) ^ $b($a) => $b 
}
$a = 1; $b=2;
swap($a, $b);
echo $a."\n".$b."\n";


//Some Basic Question like
    //get Bit
    //Set Bit
    //Clear Bit
    //Update Bit
    //Toggle Bit

//Remove Last Set Bit
function removeLastSetBit($num){
    $num = $num & ($num - 1);
    return $num;
}
//Check number is power of 2
function isPowerOf2($num){
    return ($num & ($num - 1)) == 0; //beacause it remove only rightmost set bit and number should have only 1 bit in bN
}
//countSetBits
function countSetBits($n){
    $count = 0;
    //first Approches
    while($n){
        $count += $n & 1; //1 shift to right->
        $n >>=1;   //devide by 2 $n/2
    }

    //Second Approches : removeLastSetBit 
    while($n){
        $count++;
        $n = $n & ($n-1); //remove last set bit everytime till become zero
    }
    return $count;
}

//oddEven(N){
//     return (N & 1) ? 'odd' : 'even'; //Always first set bit  is 1 for odd number

// }

function checkKthBit($n, $k) {
    return ($n & ($k<<1)) ? 'Yes' : 'No';
}
echo checkKthBit(4, 1);

//29. Divide Two Integers
function divide($dividend, $divisor) {
    $sign = 1;
    if(($dividend > 0 && $divisor < 0) || ($dividend <0 && $divisor > 0)) $sign = 0;

    $n = $dividend = abs($dividend);
    $d = $divisor = abs($divisor);
    if($dividend == $divisor) return ($sign) ? 1 : -1;
    //$dividend = 22;
    //$$divisor = 3;
    // 3 *  pow(2, 0) + 3 *  pow(2, 1) + 3 *  pow(2, 2)
    // 3 + 6 + 12 < 22 
    // ans = pow(2, 0) + pow(2, 1) + pow(2, 2) = 7;
    $sum = 0; $i=0; $ans=0;
    while($n >= $d){
        $cnt = 0;
        while($n >= ($d<<($cnt+1))) $cnt++; //chekck max value of 3 * pow(2, $cnt) : 2,1,0
        $ans += (1<<$cnt); //4,2,1
        $n -= ($d << $cnt); // 22-(3*4) = 10, 10-(3*2)=> 4, 4-(3*1)=>1
    }

    $ans = ($sign) ? $ans : -1*$ans;
    if($sign && $ans >= pow(2,31)) $ans = pow(2,31)-1;
    if(!$sign && $ans >= pow(2,31)) $ans = pow(2,31);
    return $ans;
}

//2220. Minimum Bit Flips to Convert Number
function minBitFlips($start, $goal) {
    $sbin = decToBin($start);
    $gbin = decToBin($goal);
    $i=0; $count=0;
    
    //second approch
    //1010 ^ 0111 = 1101
    $ans = $start ^ $goal; //XOR tells us result bits have unmatched bit 1 => 1 ($start's bit), 0 ( $goal's bit)
    
    while($i < 32){
        //if(($start & (1 << $i)) != ($goal & (1 << $i))) $count++;
        
        //second approch
        if(($ans & (1 << $i))) $count++;
        $i++;
    }
    return $count;
}

//136. Single Numbe
function singleNumber($nums) {
    $count=count($nums);
    $ans = 0;
    if($count == 1) return $nums[0];
    
    for($i=0;$i<$count;$i++){
       $ans ^= $nums[$i];
    }
    return $ans;
  }

  //78. Subsets
// 0 = (000) = {}
// 1 = (001) = {c}
// 2 = (010) = {b}
// 3 = (011) = {b, c}
// 4 = (100) = {a}
// 5 = (101) = {a, c}
// 6 = (110) = {a, b}
// 7 = (111) = {a, b, c}

$arr = [0];
$ans = [];
$length = count($arr);
if($length > 0){
    $subsets = 1 << $length; ////get total number of subsets pow(2, $length); => 8 : 0->7
    while($subsets){ //0->7
        $number = $subsets-1;
        $temp = [];
        for($i = 0; $i < $length; $i++){//0->2
            if($number & 1 << $i) $temp[] = $arr[$i]; ////001 & 001 = true / 001 & 010 = flase / check same set bit and get index of set bit as well arr index value
        }
        $subsets--;
        $ans[] = $temp;
    }
}
print_r($ans);

/// Function to find XOR of two numbers without using XOR operator
findXOR(l, r) {
    var XOR = 0;
    if(l < r){
        // while(l<=r){
        //     XOR ^= l;
        //     l++;
        // }
        XOR =this.OneToNXor(l-1) ^ this.OneToNXor(r);
       
    }
    return XOR;
}
OneToNXor(N){
    if(N%4 == 1) return 1;
    if(N%4 == 2) return N+1;
    if(N%4 == 3) return 0;
    return N;
}
//260. Single Number III
// Input: nums = [1,2,1,3,2,5]
// Output: [3,5]
function singleNumber($nums) {
    $length = count($nums);

    $XOR = 0;
    for($i = 0; $i < $length; $i++){
        $XOR = $XOR ^ $nums[$i]; //Give 1 ^ 2 two numbers of XOR like 100100 
        //NOTE: Always remember XOR of two values have minimum 1 bit differnce, Like in 1 and 2 has minimum one bit will differnet
    }
    $rightMostSetBit = ($XOR & $XOR-1) ^ $XOR; //it gives us rightmost set bit number like 4 (00100) of XOR (100100) have 2nd index is 1;
    $setNotBitBucket = $setBitBucket = 0;
    for($i = 0; $i < $length; $i++){
       if($rightMostSetBit & $nums[$i]) $setBitBucket ^= $nums[$i]; // if num[$i] have 2nd index is same then condition give 1 otherwise 0
       else $setNotBitBucket ^= $nums[$i];
    }

    return [$setBitBucket, $setNotBitBucket];
    
}
?>