<?php 

$n=780; $counter = [];

for($i=2; $i<=sqrt($n); $i++){
  echo "n". "-". $n;
  echo " ::i". "-". $i. "\n";
  if($n % $i == 0){
      //echo $i. "-";
      $counter[] = $i;
      while($n%$i == 0){
          $n = $n/$i;
          echo "Inner loop : ";
          echo "n". "-". $n;
          echo " ::i". "-". $i. "\n";
          //echo $n/$i. "\n";
      }
  }
  echo "After a itiration : sqrt(n) is ". sqrt($n). " and n is ". $n. "\n";
  echo "for next itiration, condition will be like this : i ($i+1) <= ". sqrt($n). "\n";
}






