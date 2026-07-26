<?php

function characterReplacement($s, $k) {
    $length = strlen($s);
    if($length == 0 || $length == 1) return $length;

    $left = $right = 0; $maxLength = 0;  $chrHashCounter = []; $maxFreq = 0;

    for($right; $right < $length; $right++){
        if(isset($chrHashCounter[$s[$right]])) $chrHashCounter[$s[$right]]++;
        else $chrHashCounter[$s[$right]] = 1;

        $maxFreq = max($chrHashCounter[$s[$right]], $maxFreq);

        while(($right - $left + 1) - $maxFreq > $k){
            $leftChr = $s[$left];
            $chrHashCounter[$leftChr]--;
            if($chrHashCounter[$leftChr] == 0) unset($chrHashCounter[$leftChr]);
            $left++;
        }

        $windowLength = $right - $left + 1;
        $maxLength = max($maxLength, $windowLength);
    }
    return $maxLength;
}


$fruits = "AABABBA"; $k = 1;
echo characterReplacement($fruits, $k);

?>

