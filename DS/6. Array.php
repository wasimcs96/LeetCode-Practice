<?php
//Max And Min in Array
$nums = [10,12,5,3,7,8];

$max = PHP_INT_MIN;
$min = PHP_INT_MAX; 

for($i = 0; $i < count($nums); $i++){
    if($nums[$i] > $max){
        $max = $nums[$i];
    }

    if($nums[$i] < $min){
        $min = $nums[$i];
    }
}

echo $max. "-" . $min;


//Second MIn and Second Largest NUmber in Array

$nums = [10,12,5,3,7,8,10];

$max = PHP_INT_MIN;
$second_max = PHP_INT_MIN;
$min = PHP_INT_MAX; 
$second_min = PHP_INT_MAX;

for($i = 0; $i < count($nums); $i++){
    if($nums[$i] > $max){
        $second_max =  $max;
        $max = $nums[$i];
    }elseif($nums[$i] > $second_max && $nums[$i] != $max){
        $second_max =  $nums[$i];
    }

    if($nums[$i] < $min){
        $second_min = $min;
        $min = $nums[$i];
    }elseif($nums[$i] < $second_min && $nums[$i] != $min){
        $second_min = $nums[$i];
    }
}

echo $second_max. "-" . $second_min;

//check array is sorted by
$nums = [2,3,4,1];
for($i=0;$i<count($nums)-1;$i++){
    if($nums[$i] > $nums[$i+1]){
        return false;
    }
}

//1752. Check if Array Is Sorted and Rotated
function check($nums) {
    $count = count($nums);
    $rotationCount = 0;
    for($i=0;$i<$count;$i++){
      //nextIndex give 1,2,1
      $nextIndex = (int) ($i+1) % $count;
      if($nums[$i] > $nums[$nextIndex]){
         $rotationCount++;
      }
    }
    if($rotationCount > 1){
      return false;
    }
    return true;
}

check([1,2,3]);

//removeDuplicates
function removeDuplicates(&$nums) {
    $i = 0;
    for ($j = 1; $j < count($nums); $j++) {
      if ($arr[$i] !== $arr[$j]) { //logic
        $i++;
        $arr[$i] = $arr[$j];
      }
    }
    return $i + 1;
    
}

$nums = [-4,-4,0,0,1,1,1,2,2,3,3,4];
echo removeDuplicates($nums);
print_r( $nums );

//189. Rotate Array
function rotate(&$nums, $k) {
  $length = count($nums);  //1,2,3,4,5,6,7,8,9,10
  if($k > 0){
    if($k > $length) $k=$k%$length;  //14=>4 if length is 10
    $n = count($nums)-1;
    $this->reverse($nums, $n-$k+1, $n); // 7 to 10 reverse => 10,9,8,7
    $this->reverse($nums, 0, $n-$k); // 1to 6 reverse => 6,5,4,3,2,1
    $this->reverse($nums, 0, $n); //complete reverse => 6,5,4,3,2,1,10,9,8,7 => 7,8,9,10,1,2,3,4,5,6
  }
}

function reverse(&$nums, $st, $ed){
  while($st < $ed){
    list($nums[$ed], $nums[$st]) = [$nums[$st], $nums[$ed]];
    $st++;
    $ed--;
  }
}


//***
//Longest sub-array having sum k

$k=15;
$nums = [10, 5, 2, 7, 1, 9 ];
$hash = [];
$arrLength = count($nums);
$maxLen=0;
$sum = 0;
print_r($nums); echo "<BR>";
for($i=0;$i<$arrLength;$i++){
    $sum += $nums[$i];

    if($sum == $k){
      $maxLen = max($maxLen, $i+1);
    }

    if(!isset($hash[$sum])){
      $hash[$sum] = $i;
    }
    echo "/Hash";
    print_r($hash);
    $rem = $sum-$k;
    echo  "rem--->".$rem;
    echo "/<BR>";

    //rem = sum-k => sum = rem + k

    //<-------------------------->Sum (sum till i)
    //<------->rem (sum till i)
    //         <----------------->k  
   
    if(isset($hash[$rem])) {  
      $maxLen = max($maxLen, $i-$hash[$rem]);
    }
    echo $maxLen;

}
echo $maxLen;


//optimize
$i=0;$j=0;

while($j<$arrLength){
  $sum += $nums[$j];

  if($sum == $k){
    $maxLen = max($maxLen, $j-$i+1);
    $j++;
  }else if($sum < $k){
    $j++;
  }else{
    while ($sum > $k) {
      $sum -= $nums[$i];
      $i++;
    }
    if($sum == $k){
      $maxLen = max($maxLen, $j-$i+1);
    }
    $j++;
  }

}

//O(2*N)

//1. Two Sum
//if return Yes and No then we can use Two pointer approch
function twoSum($nums, $target) {
    $arrLength = count($nums);
    $haspMap = [];
    for($i=0;$i<$arrLength;$i++){
      //2,7,11,15  k=9
      //i=0//9-2 =7 => set 7 in hasmap with i which is 0
      //i=1//7 is exist in hashmap = 7 => isset 7 with 0 value in hasmap then return map i and for loop i
      if(!isset($haspMap[$nums[$i]])){
        $haspMap[$target-$nums[$i]] = $i;
      }else{
        return [$haspMap[$nums[$i]], $i];
      }
    }
  }

  //Just return Yes if sum exits
    $target=10;
    $nums = [2,5,5,11];
    $arrLength = count($nums);
    sort($nums);
    $i=0; $j=$arrLength-1;
    $sum=$nums[$i];
    while($i<$j){
         $sum = $nums[$i] + $nums[$j];
        if($sum == $target){
            echo "YES"; die;
        }else if($sum < $target){
            $i++;
        }else{
            $j--;
        }
    }

//sort 0 and 1 2
//1->haspmap count 
//2-> left 0 and rightside 2 and mid 1 approch

$nums = [0,0,2,1,0,1,2,0,1];
$arrLength = count($nums);
$low = $mid = 0;
$high = count($nums)-1;
print_r($nums);
echo "<BR>";
if($arrLength == 1){ return; }
while($mid <= $high){
  if($nums[$mid] == 0){
    list($nums[$low], $nums[$mid]) = [$nums[$mid], $nums[$low]];
    $low++;
    $mid++;
  }else if($nums[$mid] == 1){
    $mid++;
  }else{
    list($nums[$high], $nums[$mid]) = [$nums[$mid], $nums[$high]];
    $high--;
  }
  print_r($nums); echo "-------------low: " . ($low). "mid: " . ($mid). "high: " . ($high);
  echo "<BR>";
}
print_r($nums);


//169. Majority Element
//O1 space 
function majorityElement($nums) {
  $length = count($nums)-1;
  if($length == 1) return $nums[0];
  //[2,2,1,1,1,2,2]

  $majorityElement = 0;
  $counter = 0;
  for($i=0;$i<=$length;$i++){
    if($counter == 0){ //if 2's couter beacome 0 then 1's counter start
      $majorityElement = $nums[$i];
      $counter++;
    }elseif($majorityElement == $nums[$i]){
      $counter++; //in case of 2 -> ++
    }else{
      $counter--; //in case of 1 -> --
    }
    //at last we get 0 for 2->3 times, 1-> 3 times and last 2 have 1 counter
    //counter like 1,2,1,0,1,0,1
  }


  //find counter for 2 in array will be 4 
  $majorityElementCount = 0;
  for($i=0;$i<=$length;$i++){
    if($majorityElement == $nums[$i]){
      $majorityElementCount++;
    }
  }

  if($majorityElementCount > $length / 2){
    return $majorityElement;
  }
}


//Kadane’s Algorithm : Maximum Subarray Sum in an Array
$nums = [-2,1,-3,4,-1,2,1,-5,4];
$arrLength = count($nums);
$sum = 0;  $max = PHP_INT_MIN; $tempStr = $str = $end = -1;
for($i=0;$i<$arrLength;$i++){
  if($sum == 0) $tempStr = $i; //when sum ==0 then actual masSubArray start

  $sum +=$nums[$i];

  if($sum > $max){
    $max = $sum;
    $str = $tempStr;
    $end = $i; //max value have end of array
  }

  if($sum < 0) $sum = 0;
  //str and end are index of max subarray
}
echo $str."\n";
echo $end."\n";
echo $max;


//Stock Buy And Sell
function maxProfit($prices) { 
  $max_profit = 0;
  $buy = PHP_INT_MAX;
  $n=count($prices);
  
  for($i=0;$i<$n;$i++){
      // if($buy > $prices[$i]){
      //     $buy = $prices[$i];
      // }elseif($prices[$i]-$buy > $max_profit){
      //     $max_profit = $prices[$i]-$buy;
      // }
      $buy = min($buy, $prices[$i]);
      $max_profit = max($max_profit, $prices[$i]-$buy);
  }
  return $max_profit;
}

//Rearrange Array Elements by Sign if not equal of negative and positive
function rearrangeArray($nums) {
  $length = count($nums);
  $ans = array_fill(0, $length, null);
  $negArr = $posArr = [];
  
  if($length == 0){
      return $ans;
  }

  for($i = 0; $i < $length; $i++){
    if($nums[$i] > 0){
      array_push($posArr, $nums[$i]);
    }else{
      array_push($negArr, $nums[$i]);
    }
  }

  $posArrLength = count($posArr);
  $negArrLength = count($negArr);

  if($posArrLength < $negArrLength){
    for($i = 0; $i < $posArrLength; $i++){
      $ans[$i*2] = $posArr[$i];
      $ans[($i*2) + 1] = $negArr[$i];
    }
    $index = $posArrLength*2;
    for($i=$posArrLength; $i < $negArrLength;$i++){
      $ans[$index] = $negArr[$i];
      $index++;
    }
  }else{
    for($i = 0; $i < $negArrLength; $i++){
      $ans[$i*2] = $posArr[$i];
      $ans[($i*2) + 1] = $negArr[$i];
    }
    $index = $negArrLength*2;
    for($i=$negArrLength; $i < $posArrLength;$i++){
      $ans[$index] = $posArr[$i];
      $index++;
    }
  }
  return $ans;
}

//Optimize
//Rearrange Array Elements by Sign if if equal number
function rearrangeArray1($nums) {
  $length = count($nums);
  $ans = array_fill(0, $length, null);
  
  if($length == 0){
      return $ans;
  }
  
  $counter = 0;
  $negCounter = 1;
  for($i=0;$i<$length;$i++){
      if($nums[$i] > 0){
          $ans[$counter]= $nums[$i];
          $counter += 2;
      }else{
          $ans[$negCounter]= $nums[$i];
          $negCounter += 2;
      }  
  }
  return $ans;
}


//31. Next Permutation
//      1,   //swap 1 and 3 like just greate number and after aswap this make sort after 3 values 23 00145
//   2    5,  
//          4,
//            3,
//              0,
//                0

function nextPermutation(&$nums) {
  //2,1,5,4,3,0,0
  $swapfirstIndex = "";
  $swapSecondIndex = "";
  
  for($i=count($nums)-2;$i>=0;$i--){
      if($nums[$i]<$nums[$i+1]){
          $swapfirstIndex = $i; //1
          break;
      }
  }

  if($i==-1) return sort($nums); //all in descending order so next permutation will be original array get after sort given array

  if($swapfirstIndex != ""){
      for($i=count($nums)-1;$i>$swapfirstIndex;$i--){
        if($nums[$i]>$nums[$swapfirstIndex]){ //give greate then 1 is 3 => 2,"1",5,4,"3"
            $swapSecondIndex = $i;
            list($nums[$swapSecondIndex], $nums[$swapfirstIndex]) = array($nums[$swapfirstIndex], $nums[$swapSecondIndex]); //after swap 2,3,5,4,1
            break;
        }
      }
  }
  $arr1 = [];
  $arr1 = array_slice($nums, 0, $swapfirstIndex+1);
  $nums = array_slice($nums, $swapfirstIndex+1); //make min number after 3
  sort($nums);
  $nums = array_merge($arr1, $nums);

  return;
}


//128. Longest Consecutive Sequence
//1. approch => sort and check current element -1 is equal last element and cnt++;
function longestConsecutive($nums) {
  $maxCount = 0;
  $hash = [];
  $count = count($nums);

  for($i = 0; $i < $count; $i++){
    $hash[$nums[$i]] = 0;
  }

  foreach($hash as $key => $val){
    //if key = 5 then while check 6,7,8
    $counter=0;
    $counterStart = $key;
    while(isset($hash[$key])){ //check 5,6,7,8
      if($hash[$key] != 0){ //if already calculated for 6 will be 3 =>6,7,8
        $counter += $hash[$key];
        unset($hash[$key]);
      }else{
        unset($hash[$key]);
        $counter++;
        $key++;
      }
     
    }
    $hash[$counterStart] = $counter;
    $maxCount = max($maxCount, $counter);
  }
  return $maxCount;
}

//48. Rotate Image
function rotateq(&$matrix) {
  $row = count($matrix);
  $col = count($matrix[0]);

  //convert row to column and coulm to row [j=0 => 1,4,7] -> [row=0=>1,4,7]
  //reverse each row in metrix [row=0=>1,4,7] => [4,7,1]

  //convert row to column and coulm to row 
  for ($i = 0; $i < $row; $i++) {
    for ($j = 0; $j < $i; $j++) {
      list($matrix[$j][$i], $matrix[$i][$j]) = [$matrix[$i][$j], $matrix[$j][$i]];
    }
  }

  //reverse each row in metrix
  for($rowStart = 0; $rowStart < $row; $rowStart++){
    $i=0;$j=$col-1;
    while($i < $j){
      list($matrix[$rowStart][$j], $matrix[$rowStart][$i]) = [$matrix[$rowStart][$i], $matrix[$rowStart][$j]];
      $i++; $j--;
    }
  }
}


//setMatrixZeroes
    $matrix = [[1, 1, 1], [1, 0, 1], [1, 1, 1]];

    $row = count($matrix);
    $col = count($matrix[0]);
    
    $col0 = 1;
    for($i = 0; $i < $row; $i++){
      for($j = 0; $j < $col; $j++){
        if($matrix[$i][$j] == 0){
          $matrix[$i][0] = 0;
          if($j != 0){
            $matrix[0][$j] = 0;
          }else{
            $col0 = 0;
          }
        }
      }
    }

    for($i = 1; $i < $row; $i++){
      for($j = 1; $j < $col; $j++){
        if($matrix[$i][$j] != 0){
          if($matrix[$i][0] == 0 || $matrix[0][$j] == 0){
            $matrix[$i][$j] = 0;
          }
        }
      }
    }
    if($matrix[0][0] == 0){
      for($j = 0; $j < $col; $j++){
        $matrix[0][$j] = 0;
      }
    }

    if ($col0 === 0) {
      for($i = 1; $i < $row; $i++){
        $matrix[$i][0] = 0;
      }
    }
  print_r($matrix);


//Spiral Traversal of Matrix
$matrix = [ [1, 2, 3, 4 ],
            [5, 6, 7, 8 ],
            [9, 10, 11, 12 ],
          ];

$col = count($matrix[0]);
$row = count($matrix);

$upRow = 0;
$rightCol = $col-1;
$botRow = $row-1;
$leftCol = 0;
$ans = [];


while($upRow <= $botRow && $leftCol <= $rightCol){
  $j=$leftCol;
  
  while($j <= $rightCol){
    $ans[] = $matrix[$upRow][$j];
    $j++;
  }
  $upRow++;

  $i=$upRow;
  while($i <= $botRow){
    $ans[] = $matrix[$i][$rightCol];
    $i++;
  }
  $rightCol--;

  if ($upRow <= $botRow) { //importent
    $j=$rightCol;
    while($j >= $leftCol){
      $ans[] = $matrix[$botRow][$j];
      $j--;
    }
    $botRow--;
  }

  if ($leftCol <= $rightCol) { // //importent
    $i = $botRow;
    while($i >= $upRow){
      $ans[] = $matrix[$i][$leftCol];
      $i--; 
    }
    $leftCol++;
  }
  
}

print_r($ans);


//560. Subarray Sum Equals K

function subarraySum($nums, $k) {
  $length = count($nums);
  $ansCounter = 0;
  $hash [0]= 1;
  $sum = 0;

  //rem = sum-k => sum = rem + k
  //<-------------------------->Sum (sum till i) ->set sum in hashmap
  //<------->rem (sum till i) ->check rem in haspmap 
  //         <----------------->k  
  for($i = 0; $i < $length; $i++){
    $sum += $nums[$i];
    $removal = $sum-$k;
    $ansCounter += $hash[$removal] ?? 0;
    $hash[$sum] = ($hash[$sum] ?? 0) + 1;
  }
  return $ansCounter;
}

//118. Pascal's Triangle
function generate($numRows) {
  // $ans = [];
  // $i=1;
  // $ans[0][0] = 1;
  // while($i < $numRows){
  //   $j=0;
  //   while($j <= $i){
  //     $ans[$i][$j] = ($ans[$i-1][$j-1] ?? 0) + ($ans[$i-1][$j] ?? 0);
  //     $j++;
  //   }
  //   $i++;
  // }

    $ans = [];
    $i=1;
    while($i <= $numRows){
      $j=0;
      $ans[$i-1][$j] = $answer = 1; $j++;
      while($j < $i){
        $answer = $answer * ($i - $j);
        $answer = $answer / $j;
        $ans[$i-1][$j] = $answer;
        $j++;
      }
      $i++;
    }

  return $ans;
}


//229. Majority Element II
function majorityElement3($nums) {
  $ans = [];
  $el_1="";  $el_2="";
  $c1=0; $c2=0;
  
  for($i=0;$i<count($nums);$i++){
    if($c1==0 && $el_2 != $nums[$i]){
      $el_1=$nums[$i];
      $c1=1;
    }elseif($c2==0 && $el_1 != $nums[$i]){
      $el_2=$nums[$i];
      $c2=1;
    }
    elseif($nums[$i] == $el_1){
      $c1++;
    }elseif($nums[$i] == $el_2){
      $c2++;
    }else{
      $c1--; $c2--;
    }
  }

  $c1=0;  $c2=0;
  for($i=0;$i<count($nums);$i++){
    if($el_1 == $nums[$i]) $c1++;
    if($el_2 == $nums[$i]) $c2++;
  }

  if($c1 > (int) (count($nums) / 3)){
    $ans[] = $el_1;
  }

  if($c2 > (int) (count($nums) / 3)){
    $ans[] = $el_2;
  }
  return $ans; 
}


//15. 3Sum
$ans = [];
sort($nums);
$i=0; $k=count($nums)-1;

for($i=0;$i<count($nums);$i++){
  if($i>0 && $nums[$i] == $nums[$i-1]) continue;

  $j=$i+1; 
  $k=count($nums)-1;
  while($j<$k){
      $sum = $nums[$i] + $nums[$j] + $nums[$k];
      if($sum > 0){
          $k--;
      }else if($sum < 0){
          $j++;
      }else{
          $temp_ans = [$nums[$i], $nums[$j], $nums[$k]];
          sort($temp_ans);
          $ans[$temp_ans[0]."_".$temp_ans[1]."_".$temp_ans[2]] = $temp_ans;
          $j++;
          $k--;
          while($j<$k && $nums[$j] == $nums[$j-1])  $j++; //elimate next duplicates j in one group
          while($j<$k && $nums[$k] == $nums[$k+1])  $k--;  //elimate next duplicates k in one group
      }
  }
}
return !empty($ans) ? array_values($ans) : [];
?>