<?php 
   
   function fibonnic($n, &$memo){
    if ($n == 0 || $n==1 ) {
        return $n;
    }
    if(isset($memo[$n])){
        return $memo[$n];
    }
    $memo[$n] =   fibonnic($n-1, $memo) + fibonnic($n-2, $memo);
    return $memo[$n];
}
$memo = [];
echo fibonnic(5, $memo) . "\n\n";








