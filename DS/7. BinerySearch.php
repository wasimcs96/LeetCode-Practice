<?php

//34. Find First and Last Position of Element in Sorted Array
$nums = [5,7,7,8,8,10];
$high = count($nums)-1; $low = 0; $key=8;
$ans = -1;
$fiestIndex = searchFirstInsert($nums, $low, $high, $key, $ans);
$secondIndex = searchSecondInsert($nums, $low, $high, $key, $ans);
return [$fiestIndex, $secondIndex];
function searchFirstInsert($nums, $low, $high, $key, $ans){
    if($low > $high) return $ans;
   
    $mid = (int) (($low+$high) / 2);

    if($nums[$mid] == $key ) {
        $ans = $mid;
        return searchFirstInsert($nums, $low, $mid-1, $key, $ans);
    }
    else if($nums[$mid] > $key){
        return searchFirstInsert($nums, $low, $mid-1, $key, $ans);
    }else{
        return searchFirstInsert($nums, $mid+1, $high, $key, $ans);
    }
}

function searchSecondInsert($nums, $low, $high, $key, $ans){
    if($low > $high) return $ans;
   
    $mid = (int) (($low+$high) / 2);

    if($nums[$mid] == $key ) {
        $ans = $mid;
        return searchSecondInsert($nums, $mid+1, $high, $key, $ans);
    }
    else if($nums[$mid] > $key){
        return searchSecondInsert($nums, $low, $mid-1, $key, $ans);
    }else{
        return searchSecondInsert($nums, $mid+1, $high, $key, $ans);
    }
}

//33. Search in Rotated Sorted Array

$nums = [3,1];
$high = count($nums)-1; $low = 0; $key=1; $ans = -1;

while($low <= $high){
    $mid = (int) (($low + $high) / 2);
   
    if ($nums[$mid] == $key ){
        echo $mid; die;
    }

    if($nums[$low] <= $nums[$mid]){
        if($nums[$low] <= $key && $key < $nums[$mid]){
            $high = $mid-1;
        }else{
            $low = $mid+1;
        }
    }else{
        if($nums[$mid] < $key && $key <= $nums[$high]){
            $low = $mid+1;
        }else{
            $high = $mid-1;
        }
    }
}
echo -1;

//153. Find Minimum in Rotated Sorted Array
function findMin($nums) {
    $length = count($nums);
    $low = 0; $high = $length-1;
    $min = PHP_INT_MAX;
    while($low <= $high){
      $mid = (int) (($low+$high)/2);
     
      if($nums[$low] <= $nums[$high]){
        $min = min($min, $nums[$low]);
        break;
      }
      
      if($nums[$low] <= $nums[$mid]){
          $min = min($min, $nums[$low]);
          $low = $mid+1;
      }else{
          $min = min($min, $nums[$mid]);
          $high = $mid-1;
      }
    }
    return $min;
}


//Find out how many times the array has been rotated
function findMin($nums) {
    $length = count($nums);
    $low = 0; $high = $length-1;
    $min = PHP_INT_MAX; $index = -1;
    while($low <= $high){
      $mid = (int) (($low+$high)/2);
      if($nums[$low] <= $nums[$high]){
       
        if($min > $nums[$low]) {$index = $low;}
        $min = min($min, $nums[$low]);
        break;
      }
      if($nums[$low] <= $nums[$mid]){
        
          if($min > $nums[$low]) $index = $low;
          $min = min($min, $nums[$low]);
          $low = $mid+1;
      }else{
        
          if($min > $nums[$mid]) $index = $mid;
          $min = min($min, $nums[$mid]);
          
          $high = $mid-1;
      }
    }
   
    return $length - $index -1;
}

echo findMin([5,6,2,3,4]);

//540. Single Element in a Sorted Array
function singleNonDuplicate($nums) {
    $length = count($nums);
    $low = 0; $high = $length-1; 

    if($length == 1 || $nums[0] != $nums[1]){
        return $nums[0];
    }
    if($nums[$high] != $nums[$high-1]){
        return $nums[$high];
    }

    while($low <= $high){
      $mid = (int) (($low+$high)/2);
      $midIsEven = ($mid+1) % 2 == 0 ? true : false;
      if($nums[$mid] != $nums[$mid-1] && $nums[$mid] != $nums[$mid+1]){
        return $nums[$mid];
    }
      if($midIsEven){
        if($nums[$mid] == $nums[$mid-1]){
            $low = $mid+1;
        }else{
            $high = $mid-1;
        }
      }else{
        if($mid != $high && $nums[$mid] == $nums[$mid+1]){
            $low = $mid+2;
        }else{
            $high = $mid-2;
        }
      }
    }

    return $nums[$mid];
}

//1011. Capacity To Ship Packages Within D Days
function getPossibleShipWeight($nums, $mid, $days){
    $cnt = 0; $cntDays = 0;
    foreach ($nums as $value) {
      if($mid >= $cnt+$value){
        $cnt = $cnt + $value;
      }else{
        $cntDays++;
        $cnt = $value;
      }
    }

    if($cnt >= $mid){
      $cntDays += (int)($cnt/$mid);
    }else{
      $cntDays++;
    }
    if($cntDays <= $days) return true;
    return false;
  }

  function shipWithinDays($weights, $days) {
    $sum = array_sum($weights);
    
    $low = max($weights); $high = $sum;
    while ($low <= $high){
      $mid = (int)(($low + $high)/2);
      $isPossible = $this->getPossibleShipWeight($weights, $mid, $days);
      if($isPossible){
        $high = $mid-1;
      }else{
        $low = $mid+1;
      }
    }
    return $low;
  }


//Aggressive Cows : Detailed Solution
//https://leetcode.com/problems/magnetic-force-between-two-balls/
$stalls = [0,3,4,7,10,9]; $m = 4; //3 answer
$stalls = [4,2,1,3,6]; $m = 2;  //5 answer

sort($stalls);
echo minDays($stalls, $m);

function minDays($arr, $cow) {
  $length = count($arr);
  $limit = max($arr)-min($arr); //valuue must be range  of stall number.   0->10 in this we can get min distanse
  // for($i = 1; $i <= $limit; $i++) {
  //   if(canputCows($arr, $cow, $i) == false){
  //     return $i-1;
  //   }
  // }
  // return $limit;

  //BS Aprroch
  $min = 1; $high = $limit;
  while($min<=$high){
    $mid = (int)(($min+$high)/2);
    if(canputCows($arr, $cow, $mid) == true){ //check for value mid we can put all 4 cows in arr => mid 1,2,3 give true but for 4 we can not put all cows in arr 
      $min = $mid+1;
    }else{
      $high = $mid-1;
    }
  }
  return $high;
  
}

function canputCows($arr, $cow, $minDistance){ //0,3,4,7,10,9
  $last = $arr[0]; $length = count($arr)-1;
  $cntCows = 1;// no. of cows placed
  for($j = 1; $j <= $length; $j++){
    if($arr[$j]-$last >= $minDistance){ //3-0=3 > 1 so true then check  next condition 4-3 = 1 >=1 and so on
      $cntCows++;
      $last = $arr[$j]; //0=>3 , first two index
    }
    if($cntCows >= $cow) return true;
  }
  return false;
}

//Allocate Minimum Number of Pages --similer to  https://leetcode.com/problems/capacity-to-ship-packages-within-d-days/
$books = [12, 34, 67, 90]; $student = 2;
$max = array_sum($books); $min = min($books);
// for($i = $min; $i <= $max; $i++){ min->max
//   if(canAllocateToStident($books, $student, $i) == true){
//     echo $i; break;
//   }
// }
while($min <= $max ){
  $mid = (int)(($min + $max) / 2);
  if(canAllocateToStident($books, $student, $mid) == true){
    $min = $mid+1;
  }else{
    $max = $mid-1;
  }
}
echo $min;
function canAllocateToStident($books, $student, $pages){ echo $pages."--";
  $length = count($books)-1; 
  $cntStudent = 1; $cntPages = 0;
  for ($j=0; $j<= $length ; $j++) {
    if($books[$j] + $cntPages <= $pages){ 
      $cntPages += $books[$j];
    }else{
      $cntPages = $books[$j];
      $cntStudent++;
    }
  }
  echo $cntStudent."\n";
  return $cntStudent >  $student ? true : false;
}

//Split Array https://leetcode.com/problems/split-array-largest-sum/
$a = [1,2,3,4,5]; $k = 3;
$max = array_sum($a); $min = max($a);

// for($i = $min; $i <= $max; $i++){
//   if(canSplitArray($a, $k, $i) == true){
//     echo $i; break;
//   }
// }

while($min <= $max ){
  $mid = (int)(($min + $max) / 2);
  if(canSplitArray($a, $k, $mid)){
    $min = $mid+1;
  }else{
    $max = $mid-1;
  }
}

echo "ANS-->".$min  ;

function canSplitArray($arr, $k, $sum){ //echo $sum."--";
  $length = count($arr)-1; 
  $cnt = 0; $cntArr = 1;
  for ($j=0; $j<= $length ; $j++) {
    if($arr[$j] + $cnt <= $sum){ 
      $cnt += $arr[$j];
    }else{
      $cnt = $arr[$j];
      $cntArr++;
    }
  }
  echo $cntArr."\n";
  return $cntArr >  $k ? true : false;
}



//Find the row with maximum number of 1’s
$mat = [[1, 1, 1],[0, 1, 1],[0, 0, 1]];
$n = count($mat); $m = count($mat[0]);
getMaxOnesIndex($mat, $n, $m);
function getMaxOnesIndex($mat, $n, $m){
  $maxOneIndex = -1; $maxOnes = -1;
  for($i=0; $i<$n; $i++){
    $firstIndex = getfirstIndexOfOne($mat, $i, $m);
    //echo $firstIndex;
    if($firstIndex == -1){
      continue;
    }else{
      $lastIndex = getlastIndexOfOne($mat, $i, $m);
      //echo $lastIndex;
      if($firstIndex != $lastIndex){
        $count = $lastIndex - $firstIndex + 1;
      }else{
        $count = 1;
      }
      if($count > $maxOnes){
        $maxOnes = $count;
        $maxOneIndex = $i;
      }
    }
  }
  echo $maxOneIndex."\n";
}

function getfirstIndexOfOne($mat, $i, $m){
  $low = 0; $high = $m; $first=-1;
  while ($low < $high) {
    $mid = (int)(($low+$high)/2);
    if($mat[$i][$mid] == 1){
      $first = $mid;
      $high = $mid-1;
    }else{
      $low = $mid+1;
    }
  }
  return $first;
}

function getlastIndexOfOne($mat, $i, $m){
  $low = 0; $high = $m; $last=-1;
  while ($low < $high) {
    $mid = (int)(($low+$high)/2);
    if($mat[$i][$mid] == 1){
      $last = $mid;
    }
    $low = $mid+1;
  }

  return $last;
}










?>