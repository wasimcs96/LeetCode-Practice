<?php

function numSubarraysWithSum($nums, $goal) {
    $arrLength = count($nums);

    if($arrLength == 0) return 0;

    $prefixSumCount[0] = 1;

    $sum = 0; $subArrayCounter = 0;

    foreach($nums as $num){
        $sum += $num;

        $requiredPrefixSum = $sum - $goal;

        if(isset($prefixSumCount[$requiredPrefixSum])){
            $subArrayCounter += $prefixSumCount[$requiredPrefixSum];
        }

        $prefixSumCount[$sum] = isset($prefixSumCount[$sum]) ? $prefixSumCount[$sum]+1 : 1;

        print_r($prefixSumCount);

    }

    
    return $subArrayCounter;
}


$nums = [1,0,1,0,1]; $goal = 2;
echo numSubarraysWithSum($nums, $goal);

?>

