<?php

$nums = [4,5,6,7,1,2,3];
$target = PHP_INT_MAX;
echo search($nums, $target); 
function search(array $nums, int $target): int {
    $length = count($nums);
    $left = 0; $right = $length - 1; 
    
    while ($left <= $right) {
        $mid = (int) (($right + $left) / 2);

        if($nums[$left] <= $nums[$right]) {
           $target = min($target, $nums[$left]);
           break;
        }

        if($nums[$left] <= $nums[$mid]) {
            $target = min($target, $nums[$left]);
            $left = $mid + 1;
        }else if($nums[$mid] <= $nums[$right]) {
            $target = min($target, $nums[$mid]);
            $right = $mid - 1;
        }
        echo "left: $left, right: $right, mid: $mid, target: $target \n";
    }
    return $target;
}


