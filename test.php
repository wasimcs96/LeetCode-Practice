<?php 

$arr =[3,1,-2,-5,2,-4]; 
$i = 0; $p=0;$n=1;
$k = count($arr)-1;
$ans = [];

while($i <= $k){
   if($arr[$i] > 0){
      $ans[$p] = $arr[$i];
      $p+=2;
   }else{
      $ans[$n] = $arr[$i];
      $n+=2;
   }
   $i++;

}
ksort($ans);
print_r($ans); 
