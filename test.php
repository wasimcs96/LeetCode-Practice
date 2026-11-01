<?php

function longestSubarrayWithSumKK(array $arr, int $k): int
{
    
    $maxLen = 0; $sum = $arr[0];
    $left = $right = 0;

    while($right < count($arr)){
        
        while($left <= $right && $sum > $k){
            $sum -= $arr[$left];
            $left++;
        }

        if($sum == $k){
            $maxLen = max($maxLen, $right-$left + 1);
        }

        $right++;

        if($right < count($arr)){
            $sum += $arr[$right];
        }


    }

    return $maxLen;
    
}

function longestSubarrayWithSumZero(array $arr, $k){
    $sum = 0;
    $length = count($arr); $preSumHashMap = []; $max = 0;

    for($i=0; $i<$length; $i++){
        $sum +=  $arr[$i];

        if($sum == 0){
            $max = $i + 1;
        }
        elseif(isset($preSumHashMap[$sum])){
            $max = max($max, $i - $preSumHashMap[$sum]);
        }else{
            $preSumHashMap[$sum] = $preSumHashMap[$sum] ?? $i;
        }


    }
    return $max;
    
}
echo "     Output: Length = " . longestSubarrayWithSumK([10, 5, 2, 7, 1, 9], 15) . "\n\n";
echo "     Output: Length = " . longestSubarrayWithSumZero([1, 0, -4, 3, 1, 0], 0) . "\n\n";

////Solution for  positive values
function longestSubarrayWithSumKForPositive(array $arr, int $k): int
{
    $hashMap = [];
    $maxLen = 0; $sum = 0;


    foreach($arr as $i => $num){
        $sum += $num;

        if($sum == $k){
            $maxLen = max($maxLen, $i+1);
        }
        $remaining = $sum - $k;

        if(isset($hashMap[$remaining])){
            $maxLen = max($maxLen, $i-$hashMap[$remaining]);
        }

        if(!isset($hashMap[$sum])){
            $hashMap[$sum] = $i;
        }

    }

    return $maxLen;
    
}

echo "     Output: Length = " . longestSubarrayWithSumKForPositive([10, 5, 2, 7, 1, 9], 15) . "\n\n";

function longestSubarrayWithSumK(array $arr, int $k): int
{
    $firstIndex    = [];
    $firstIndex = [];       // Prefix sum 0 before the array = index -1
    $prefixSum     = 0;
    $maxLen        = 0;

    foreach ($arr as $i => $num) {
        $prefixSum += $num;
        
        if($prefixSum == $k){
            $maxLen = max($maxLen, $i + 1);
        }

        $need = $prefixSum - $k;

        if (isset($firstIndex[$need])) {
            $len    = $i - $firstIndex[$need];
            $maxLen = max($maxLen, $len);
        }

        // Store only the FIRST occurrence of this prefix sum
        if (!isset($firstIndex[$prefixSum])) {
            $firstIndex[$prefixSum] = $i;
        }
    }

    return $maxLen;
}

echo "     Output: Length = " . longestSubarrayWithSumK([1, -1, 5, -2, 3], 3) . "\n\n";


function longestSubarrayWithZeroSum(array $arr): int
{
    $firstIndex    = [];
    $firstIndex = [];       // Prefix sum 0 is "seen" before the array starts (index -1)
    $prefixSum     = 0;
    $maxLen        = 0;

    foreach ($arr as $i => $num) {
        $prefixSum += $num;

        if($prefixSum == 0){
            $maxLen = max($maxLen, $i + 1);
        }else if (isset($firstIndex[$prefixSum])) {
            // Same prefix sum was seen at firstIndex[$prefixSum]
            // → subarray from (firstIndex[$prefixSum]+1) to i has sum = 0
            $len    = $i - $firstIndex[$prefixSum];
            $maxLen = max($maxLen, $len);
        } else {
            // Store only the FIRST occurrence to maximise future lengths
            $firstIndex[$prefixSum] = $i;
        }
    }

    return $maxLen;
}

echo "5.4  Longest Subarray With Sum = 0:\n";
echo "     Input: [15, -2, 2, -8, 1, 7, 10, 23]\n";
echo "     Output: Length = " . longestSubarrayWithZeroSum([1, 0, -4, 3, 1, 0]) . "\n\n";