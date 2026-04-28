<?php
//151. Reverse Words in a String
$s =  "a good   example";
echo reverseWords($s) ;
function reverseWords($s) {
  $ans = ""; $temp = ""; $s = trim($s);
  $low = 0; $high = strlen($s)-1;

  while ($low <= $high){ 
      if($s[$low] != ' '){
        $temp .= $s[$low];
      } else {
          if($ans != ""){
            $ans = trim($temp)." ".trim($ans);
          }else{
            $ans = trim($temp);
          }
          $temp = "";
      }
      echo $ans."\n";
    $low++;
  }

  if($temp != ' ') {
    if($ans != ""){
      $ans = trim($temp)." ".trim($ans);
    }else{
      $ans = trim($temp);
    }
  }
  return $ans;
}

//Gretest odd number
$num = "2221444";
echo largestOddNumber($num) ;
function largestOddNumber($num) {
  $temp = "";
  $low = 0; $high = strlen($num)-1;

  while($high >= $low){
    if($num[$high] % 2 != 0){
        $num = substr($num, 0, strlen($num)-$high);
    }
    $high--;
  }
  return $num;
}

//myAtoi
$s = "2147 483647";
echo myAtoi($s);
function myAtoi($s) {
      if(empty($s)) return 0;
      $isNegative = false;
      $result = 0;

      $i=0;
      while($i < strlen($s) && $s[$i] == ' '){
        $i++;
      }
      while($i < strlen($s) && ($s[$i] == '-' || $s[$i] == '+')){
        $isNegative = $s[$i] == '-' ?  true : false;
        $i++;
        break;
      }
      $PHP_INT_MAX = 2147483647;
      $PHP_INT_MIN = -2147483648;

      while($i < strlen($s)){
        if(is_numeric($s[$i])){ 
            if($result > (int) ($PHP_INT_MAX / 10)){
              if($isNegative) return $PHP_INT_MIN;
              else return $PHP_INT_MAX;
            }elseif($result == (int) ($PHP_INT_MAX / 10)){
              $digit = intval($s[$i]);
              if($isNegative && $digit > $PHP_INT_MAX % 10){ 
                return $PHP_INT_MIN;
              }
              elseif(!$isNegative && $digit >= $PHP_INT_MAX % 10)
                return $PHP_INT_MAX;
              else
                $result = $result  * 10 + $s[$i];
            }else{
              $result = $result  * 10 + $s[$i];
            }
        }else{
          break;
        }
        $i++;
      }
      if($isNegative) return -$result;
      else return $result;
}


//Recursive approch
//Recursive Approch
$i=0; $result = 0; $isNegative = false; $str = $s;
if(empty($str)) return 0;  
while($i < strlen($str) && $str[$i] == ' ') $i++;
while($i < strlen($str) && ($str[$i] == '+' || $str[$i] == '-')){
    if($str[$i] == '-') $isNegative = true;
    $i++;
    break;
}
myAtoiRecursive($str, $result, $isNegative, $i);
return $isNegative == true ? -$result : $result;


function myAtoiRecursive($str, &$result, &$isNegative, $i)
{
if($i >= strlen($str)) return;        
$PHP_INT_MAX = 2147483647;
$PHP_INT_MIN = -2147483648;
if($i < strlen($str) && is_numeric($str[$i])){
    if($result > (int) ($PHP_INT_MAX/10)){
        if($isNegative) {
          $isNegative = false;
          return $result = $PHP_INT_MIN;
        }
        else return $result = $PHP_INT_MAX;
    }else if($result == (int) ($PHP_INT_MAX/10)){
        $lastDigit = intval($str[$i]);
        if($isNegative && $lastDigit > (int) ($PHP_INT_MAX%10)) {
$isNegative = false;
$result = $PHP_INT_MIN;
return;
}
if(!$isNegative && $lastDigit > (int) ($PHP_INT_MAX%10)) {
  $result = $PHP_INT_MAX;
  return;
}
$result = $result * 10 + $lastDigit;
    }else{
        $result = $result * 10 + $str[$i];
    }
    $i++;
    return myAtoiRecursive($str, $result, $isNegative, $i);
}else{
    return;
}
} 


?>










?>