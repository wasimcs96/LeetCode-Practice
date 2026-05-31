<?php

$nums = [2, 1, 1, 2, 1, 1, 2]; 

$high = count($nums)-1; $low = 0; $maxElement = 0; $maxCount = 0;
while($low <= $high){
    if($maxCount == 0){
        $maxElement = $nums[$low];
        $maxCount = 1;
    }
    elseif($maxElement == $nums[$low]){
        $maxCount++;
    }else{
        $maxCount--;
    }
    

    $low++;
    
}

echo $maxElement;