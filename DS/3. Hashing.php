<?php 

//<!-- Count frequency of each element in the array -->

// Example 1:
// Input: arr[] = {10,5,10,15,10,5};
// Output: 10  3
// 	    5  2
//         15  1


//BFA
$arr =[10,5,10,15,10,5];
$count = count($arr);

$visited = array_fill(0, $count, 'F');

for($i=0;$i<$count;$i++){
    if(isset($visited[$arr[$i]]) && ($visited[$arr[$i]] == 'T')) continue;

    $counter = 1;
    for($j=$i+1;$j < $count;$j++){  
        $visited[$arr[$i]] = 'T';
        if($arr[$i] == $arr[$j]){
          $counter++;
        }
    }
    echo $arr[$i]." ".$counter."<BR>";
}
//OA

$arr =[10,5,10,15,10,5];
$count = count($arr);
$visited = [];

for($i=0;$i<$count;$i++){
    if(isset($visited[$arr[$i]])) $visited[$arr[$i]]++;
    else 
      $visited[$arr[$i]] = 1;
}
foreach($visited as $key=>$value){  
  //echo $key." ".$value."<BR>";
}



// abcdafedcb
//count frequency of each charetecter and out with fequency
//echo ord("b")-ord("a");

function charFrequency($str) {
  $freqArr=[];
  $str = str_split($str);
  $count = count($str);
  //Assicate Array Approch

  foreach($str as $chr){
      if(isset($freqArr[$chr])) 
          $freqArr[$chr]++;
      else 
          $freqArr[$chr] = 1;
  }

  arsort($freqArr);
  foreach($freqArr as $chr => $freq){
      echo $chr.$freq;
  }

   //AscII approch

  // for($i=0;$i<$count;$i++){
  //     $index = ord($str[$i])-ord("a"); //ord() to get ascii int value
  //     if(isset($freqArr[$index])) 
  //         $freqArr[$index]++;
  //     else 
  //         $freqArr[$index] = 1;
  // }
  
  // arsort($freqArr); //asort to get ascending iorder
  // foreach($freqArr as $chr => $freq){
  //     echo chr(ord("a") + $chr).$freq; //ch() use to ascci int value to chareterec
  // }
  
}

$str = 'abcbdabfg';
charFrequency($str); //b3a2c1d1f1g1



//1838. Frequency of the Most Frequent Element
function maxFrequency($nums, $key) {
  $count = count($nums);
  sort($nums);
  $l = 1; $h = $count;
  $ans = 1;
  while($l<=$h){
    $mid = (int) (($h + $l) / 2); //use binery search
    if(checkPossible($nums, $mid, $key)){
      $ans = $mid;
      $l = $mid+1;
    }else{
      $h = $mid-1;
    }
  }

  return $ans;

}

function checkPossible($nums, $mid, $key){
    
  $totalCount = $nums[$mid-1] * ($mid);  //count till $mid in which all element is equal to $nums[$mid] 1,2,3 => 3,3,3 = 9 ==> will check 9-5 <= $KEY
  $windowCount = 0;
  for($i=0;$i<$mid;$i++){//count sum till mid-1 
    $windowCount += $nums[$i];
  }
  

  if($totalCount - $windowCount <= $key) return true; //$nums[$mid] 1,2,3 => 3,3,3 = 9 ==> will check 9-5 <= $KEY

  $j=0;
  for($i=$mid;$i<count($nums);$i++){ //REMOVE 1 and add 4 from both counting
    $windowCount -=$nums[$j]; 
    $windowCount +=$nums[$i];
    $totalCount = $nums[$i] * ($mid); 

    if($totalCount - $windowCount <= $key) return true;

    $j++;

  }

}


$nums = [1,2,4];
$key=5;
echo maxFrequency($nums, $key); 


?>