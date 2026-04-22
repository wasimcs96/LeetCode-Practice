<?php 
$arr = [1,10, 22, 13, 14, 5, 0];
$start = 0;
$end = count($arr)-1;
devideAndMerge($arr, $start, $end);
print_r($arr);
function devideAndMerge(&$arr, $start, $end){
    if($start >= $end) return;
    $mid = (int) (($start + $end) / 2);
    $mid = (int) $mid;
    devideAndMerge($arr, $start, $mid);
    devideAndMerge($arr, $mid+1, $end);
    merge($arr, $start, $mid, $end);
    
}

function merge(&$arr, $start, $mid, $end){
    echo "start: $start, mid: $mid, end: $end\n";
    $leftStart = $start;
    $leftEnd = $mid;
    $rightStart = $mid + 1;
    $rightEnd = $end; 
    $temp = [];

    while($leftStart <= $leftEnd && $rightStart <= $rightEnd){
        if($arr[$leftStart] <= $arr[$rightStart]){
            $temp[] = $arr[$leftStart];
            $leftStart++;
        } else {
            $temp[] = $arr[$rightStart];
            $rightStart++;
        }
    }
    while($leftStart <= $leftEnd){
        $temp[] = $arr[$leftStart];
        $leftStart++;
    }
    while($rightStart <= $rightEnd){
        $temp[] = $arr[$rightStart];
        $rightStart++;
    }
    for($i = $start; $i <= $end; $i++){
        $arr[$i] = $temp[$i - $start];
    }
}








