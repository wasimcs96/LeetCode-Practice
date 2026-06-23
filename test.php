<?php
$weights = [1,2,3,1,1]; $days = 4;
$ans = shipWithinDays($weights, $days);
echo $ans;

function shipWithinDays(array $weights, $days) {
    $minWeights = max($weights);
    $maxWeights = array_sum($weights);

    while($minWeights <= $maxWeights){
        $midWeights = (int)(($minWeights + $maxWeights)/2);
        $shippingDays = getMinimalWeight($weights, $midWeights);
        if($shippingDays <= $days){
            $maxWeights = $midWeights - 1;
        }else{
            $minWeights = $midWeights + 1;
        }
    }
    return $minWeights;
}

function getMinimalWeight(array $weights, int $midWeights){
    $cnt = 0; $days = 1;
    for($i=0;$i<count($weights); $i++){
        if($cnt + $weights[$i] > $midWeights){
            $days++;
            $cnt = $weights[$i];
        }else{
            $cnt += $weights[$i];
        }
    }
    return $days;
}


