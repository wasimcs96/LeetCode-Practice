<?php

function findContentChildren(array $g, array $s) {
    $gSize = count($g);
    $sSize = count($s);
    if($gSize == 0 || $sSize == 0) return 0;

    sort($g);
    sort($s);

    $i = $j = 0;
    while($i < $sSize && $j < $gSize){
        //echo  $s[$i] .">=". $g[$j]."\n";
        if($s[$i] >= $g[$j]){
            $j++;
        }
        $i++;
    }
    return $j;
} 

$g = [10,9,8,7]; $s = [5,6,7,8];
//echo findContentChildren($g, $s);


function lemonadeChange($bills) {
        $arrLength = count($bills);
        if($arrLength == 0) return false;

        $five = $ten = 0;
        for($i = 0; $i < $arrLength ; $i++){
            if($bills[$i] == 5) {
                $five++;
            }elseif($bills[$i] == 10){
                if($five > 0) {
                    $five--;
                    $ten++;
                }else{
                    return false;
                }
            }else{
                if($five > 0 && $ten > 0){
                    $five--;
                    $ten--;
                }elseif($five >= 3){
                    $five -= 3;
                }else{
                    return false;
                }
            }
        }

        return true;
    }


    $val = [10,20,30]; $wt = [5,10,15]; $capacity = 100;
   // echo fractionalKnapsack($val, $wt, $capacity);

    function fractionalKnapsack($val, $wt, $cap){
        if($cap <= 0) return 0;

        $valAndWtRationMap = [];
        for($i=0; $i < count($val); $i++){
            $ratio = (int) ($val[$i]/$wt[$i]);
            $valAndWtRationMap[] = [$ratio, $i];
        }

        usort($valAndWtRationMap, function($a, $b) {
            return $b[0] <=> $a[0];
        });

        $finalValue = 0.000000;

        foreach($valAndWtRationMap as $set){
            $index = $set[1];
            if($cap <= 0) {
                break;
            }
            elseif($cap >= $wt[$index]){
                $finalValue += $val[$index];
                $cap -= $wt[$index];
            }else{
                $remainingWt = ($cap / $wt[$index]) * 100;
               
                $estimatedVal = ($remainingWt * $val[$index]) / 100;
                
                $finalValue += $estimatedVal;
                $cap = 0;
            }
        }

        return number_format($finalValue, 6, '.', '');

    }

    $boxTypes = [[1,3],[2,2],[3,1]]; $truckSize = 4;
    echo maximumUnits($boxTypes, $truckSize);

    function maximumUnits($boxTypes, $truckSize) {
        if($truckSize <= 0) return 0;

        usort($boxTypes, function($a, $b) {
            return $b[1] <=> $a[1];
        });

        $finalUnits = 0;

        foreach($boxTypes as $set){
            $numberOfBox= $set[0];
            $numberOfUnits= $set[1];
            if($truckSize <= 0) {
                break;
            }
            elseif($truckSize >= $numberOfBox){
                $finalUnits += $numberOfUnits*$numberOfBox;
                $truckSize   -= $numberOfBox;
            }else{
                $finalUnits += $numberOfUnits * $truckSize;
                $truckSize = 0;
            }
        }
        return $finalUnits;
    }   

