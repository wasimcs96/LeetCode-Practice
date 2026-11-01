<?php
function countPairsWithSum(array $nums, $k): int
{   
       $maxLength = -1; $i = $j = 0; $sum = $nums[0];
       while($j < count($nums)){

            while($i <= $j && $sum > $k){
                $sum -= $nums[$i];
                $i++;
            }

            if($sum == $k){ 
                $length = $j - $i + 1;
                $maxLength = max($maxLength, $length);
                
            }
            $j++;
            if($j < count($nums)){
                $sum += $nums[$j];
            }

       }

       if($maxLength == -1) return 0;
       else return $maxLength;

    
}
echo "5.1  Two Sum [LC #1]:\n";
echo "     Input: [2,7,11,15], target=9\n";
$result = countPairsWithSum([10, 5, 2, 7, 1, 9], 15);
echo "     Output: ".$result ;

