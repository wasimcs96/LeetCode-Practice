<?php
$arr = [-3, 2, 1]; $k = 15;
$arrLength = count($arr);
$targetLength = 0; $sum = 0; $i=0;$j=0;

while($i<=$j && $j<$arrLength){
    echo "i: $i, j: $j, temp: $sum\n";
    if($sum < $k){ 
        $sum += $arr[$j]; $j++;
    }
    else if($sum > $k){ 
        $sum -= $arr[$i]; $i++;
    }
    else { 
        echo "Target Found at index: $i and " . ($j-1) . "\n";
        $sumLength = $j-$i; 
        $targetLength = max($targetLength, $sumLength); 
        $sum += $arr[$j]; $j++;
    }
}

?>