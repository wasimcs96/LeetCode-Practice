<?php 
$arr = [0, 222,10, 22, 1300, 14, 5, 2222 , -1];

$maxElement = $arr[0];
$minElement = $arr[0];
$secondMaxElement = $arr[0];
$secondMinElement = $arr[0]; 
$i = 0;
while($i < count($arr)){
    if($arr[$i] >= $maxElement && $maxElement >= $secondMaxElement)
        $secondMaxElement = $maxElement; 
        $maxElement = $arr[$i];
    if($arr[$i] <= $minElement && $minElement <= $secondMinElement)  
        $secondMinElement = $minElement; 
        $minElement = $arr[$i];
        
    $i++;
}
echo "Second Max element is: " . $secondMaxElement;
echo "Second Min element is: " . $secondMinElement;







