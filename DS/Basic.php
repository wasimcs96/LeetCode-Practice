<?php 


//check prime
function isPrime($num){
    if($num <= 2){
        return true;
    }

    for($i=2;$i<$num;$i++){
        if($num%$i == 0){
            return false;
        }
    }

    return true;
}
$ans = isPrime(10) ? "Prime" : "Not Prime";
//echo "Number is $ans";


//Prime between 1 to 100
$start = 1;
$end = 100;

for($i = 2; $i <= $end; $i++){
    if(isPrime($i)){
        echo $i.", ";
    }
}

?>