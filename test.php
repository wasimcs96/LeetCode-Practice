<?php
$nums =[0, 0, 0, 0, 0, 0, 0, 1];

$counter = 0; $max = 0;

foreach($nums as $value){
    if($value == 1) {
        $counter++;
        $max = max($max, $counter);
    }
    else{
        
        $counter=0;
    }
}
echo $max;





$nums = [0, 1, 4, 0, 5, 2];

$length = count($nums);
$firstNonZeroIndex = -1; $k=0;
while($k < $length){
    if($nums[$k] != 0){
        break;
    }
    $k++;
}
$zeroIndex=0;
for($j=$k;$j<$length;$j++){
    if($nums[$j] != 0){
        [$nums[$j], $nums[$zeroIndex]] = [$nums[$zeroIndex], $nums[$j]];
        $zeroIndex++;
    }
}
print_r($nums);