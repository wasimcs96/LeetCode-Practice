<?php

//Selection Sort
//Push the minimum element at the first position
$arr = [1,4,6,2,0,4,8,9];
selectionSort($arr); 
function selectionSort(&$array){
    $n=count($array);
    for($i=0;$i<$n-1;$i++){
        $tempMin = $i;
        for($j=$i+1; $j<$n;$j++){
            if($array[$tempMin] > $array[$j]) 
                $tempMin = $j;
        }
        list($array[$tempMin], $array[$i]) = [$array[$i], $array[$tempMin]];
    }
}

print_r($arr);

//TC = n-1+n-2+n-3+.......3+2+1 = n^2
//SC = Constant
//Use case in small array will work fine


//Bubble Sort
$arr = [1,4,6,2,0,4,8,9];
bubbleSort($arr);
function bubbleSort(&$array){
    $n=count($array);
    for($i=1;$i<$n;$i++){ //loop i to n
        $swapped = false;
        for($j=0; $j<$n-$i;$j++){ //loop i to $n-i => move<---Arr
            if($array[$j] > $array[$j+1]){
                list($array[$j+1], $array[$j]) = [$array[$j], $array[$j+1]];
                $swapped = true;
            }
        }

        if($swapped == false) break; //already sorted not need to check other values // O(N) for the best case. 
    }
}

print_r($arr);

//TC = n-1+n-2+n-3+.......3+2+1 = n^2  // O(n) in already sorted case
//SC = Constant S(1)
//Use case in a itiration place largest element in last position in array

//Recursive Bubble Sort
function recursiveBubbleSort(&$arr, $l, $swap){
    if($l <= 1 | !$swap) return; //replace first for loop
    $swapped = false;
    for($i=0;$i<=$l-1;$i++){
        if($arr[$i] > $arr[$i+1]){
            list($arr[$i+1], $arr[$i]) = [$arr[$i], $arr[$i+1]];
            $swapped = true;
        }
    }
    recursiveBubbleSort($arr, $l-1, $swapped);
}
$nums = [1,2,3];
$length = count($nums)-1;
recursiveBubbleSort($nums, $length, true);



//Insertion Sort

$arr = [1,0,6,2,6,8,9];
insertionSort($arr);
function insertionSort(&$array){
    $n=count($array);
    for($i=1;$i<$n;$i++){
        $j=$i;
        while($j>0 && $array[$j-1] > $array[$j]){ //for best case it will not run
            list($arr[$j], $arr[$j-1]) = [$arr[$j-1], $arr[$j]];
            $j--;
        }
    }
}


print_r($arr);

//TC = 1+2+3...n-3+n-2+n-1 = n^2  => O(n^2)
//SC = Constant S(1)
//time complexity in the best case will boil down to O(N)



//Insertion Sort By Recursion
function recursiveInsertionSort(&$arr, $j){
    if($j<=1 | $arr[$j-1] < $arr[$j]){
        return;
    }
    list($arr[$j], $arr[$j-1]) = [$arr[$j-1], $arr[$j]];
    recursiveInsertionSort($arr, $j-1);
}
$nums = [1,5,3, 6,2,1];
$length = count($nums);
for($i=1;$i<$length;$i++){
    recursiveInsertionSort($nums, $i);
    
}
print_r($nums);

//Insertion Sort By Recursion
function insertionnSort(&$array, $i , $n){
    if($i == $n) return;
     $j=$i;
     while($j>0 && $array[$j-1] > $array[$j]){ //for best case it will not run
        list($arr[$j], $arr[$j-1]) = [$arr[$j-1], $arr[$j]];
         $j--;
     }

     insertionnSort($array, $i+1, $n);

}
$nums = [1,5,3, 6,2,1];
$length = count($nums);
insertionnSort($nums, 1, $length);
print_r($nums);



//Merge Sort  => Devide and merge concept
function mergSort(&$array, $l, $r){
    if($l>=$r) return;
 
    $mid = (int) ($l+$r) / 2;
 
    $mid = (int) $mid;
 
    mergSort($array, $l, $mid);
    mergSort($array, $mid+1, $r);
    merge($array,$l,$mid,$r);
 }
 
 function merge(&$arr,$l,$mid,$r){//exp => merge 2 to 6
 
     $left = $l;//left->mid   => 2 -> 4
     $right = $mid+1;   //right->r    => 5 -> 6 
     $temp = []; //will store 5 items
     while($left <= $mid && $right <= $r){
         if($arr[$left] <= $arr[$right]){ //check 2 and 5 index
             $temp[] = $arr[$left];
             $left++;
         }else{
             $temp[] = $arr[$right];
             $right++;
         }
     }
     while($left <= $mid ){//remianging left to mid
         $temp[] = $arr[$left];
         $left++;
     }
 
     while($right <= $r){//remianging right to r
         $temp[] = $arr[$right];
         $right++;
     }
 
     
     for($i=$l; $i<=$r; $i++){
         $arr[$i] = $temp[$i-$l];
     }
     print_r($temp); echo "<BR>";
 
   
 }
 
 $arr = [1,0,6,2,6,8,9];
 print_r($arr);
 $l=0;$r=count($arr)-1;
 mergSort($arr, $l, $r);
 print_r($arr);
 
 //TC = n/2+n/4+n/6.....= logn  => O(N * log2N). //All case
 //SC = Constant S(N)

 //Recuirsive Approch
 $arr = [6,2,4,9,2,1,6,44,22,77,99,112,666,43];
 mergeSort($arr, 0, count($arr)-1);
 print_r($arr);
 function mergeSort(&$arr, $left, $right ){
     if($left >= $right) return;
 
     //devide
     $mid = (int)($left+$right)/2;
     $mid = (int) $mid;
 
     mergeSort($arr, $left, $mid);
     mergeSort($arr,$mid+1, $right);
     
     //merge
     
     mergeSortedArr($arr, $left, $mid, $right);
 }
 
 function mergeSortedArr(&$arr,$l,$mid,$r){
     echo "$l , $mid , $r \n";
     $left = $l;//left->mid   => 2 -> 4
      $right = $mid+1;   //right->r    => 5 -> 6 
      $temp = []; //will store 5 items
      while($left <= $mid && $right <= $r){
          if($arr[$left] <= $arr[$right]){ //check 2 and 5 index
              $temp[] = $arr[$left];
              $left++;
          }else{
              $temp[] = $arr[$right];
              $right++;
          }
      }
      while($left <= $mid ){//remianging left to mid
          $temp[] = $arr[$left];
          $left++;
      }
  
      while($right <= $r){//remianging right to r
          $temp[] = $arr[$right];
          $right++;
      }
  
      print_r($temp);
      for($i=$l; $i<=$r; $i++){
          $arr[$i] = $temp[$i-$l];
      }
}


?>