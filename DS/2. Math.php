<?php
//<!-- Count digits in a number -->

//O(n)
function countDigits($n) {
    $x=$n;
    $digits = 0;
    while((int) $x != 0){
      $x = (int) ($x/10);
      echo $x."<BR>";
      $digits++;
    }
    return $digits;
  }

 //O(1)
 function countDigits1($n) {
    $n = (string) $n;
    return strlen($n);
}

//O(1)
function countDigits2($n) {
    return ((int)log10($n))+1;
}


//<!-- Reverse a number -->
function reversNum($n) {
    $reverseNumber = 0;
    while((int)$n != 0){
        $lastDigit = $n%10;
        $reverseNumber = $reverseNumber * 10 + $lastDigit;
        $n = $n / 10;
    }
    return $reverseNumber;
}



//<!-- Check if a number is Armstrong Number or not -->

    //count digit and pow with countdigit of last digit and sum of them if is equal to original then is armstrong number
    //153 = pow(1,3) + pow(5,3) + pow(3,3);  //3 is count digit in number
    //1535 = pow(1,4) + pow(5,4) + pow(3,4) + pow(5,4);  //4 is count digit in number


//<!-- Print all Divisors of a given Number -->
function printDivisorsOptimal($num) {
    for($i=1;$i<=sqrt($num);$i++){  //sqrt($num) == (i*i <= num)  == 1.2.3....6 <= 36/6
        if($num%$i == 0){  //1 2 3 4 6 
            echo $i."<BR>";  //push in array and sort and show array values
            if( $i !== (int) $num/$i){ //36 18 12 9   //condition false on 6 == 6
                echo (int) ($num/$i)."<BR>";//push in array and sort and show array values
            }
        }
        
    }
}
echo printDivisorsOptimal(36); //1 2 3 4 6 9 12 18 36

//<!-- Prime Number -->
function isPrime($num) {
    $count=0;
    for($i=1;$i<=$num;$i++){
      if($num%$i == 0){
          $count++;
      }
    }

    if($count == 2){ //Prime number division by only  2 number one one is 1 and itself so count should be 2
      return "PRIME";
    }else{
      return "NOT Prime";
    }
}

function isPrime($num) {
  $count=0;
  for($i=1;$i<=sqrt($num);$i++){  //sqrt($num) == (i*i <= num)
    if($num%$i == 0){
      $count++;
    }
  }

  if($count == 2){ //Prime number division by only  2 number one one is 1 and itself so count should be 2
    return "PRIME";
  }else{
    return "NOT Prime";
  }
}

function isPrime($num) {
  $count=0;
  for($i=1;$i<=sqrt($num);$i++){  //sqrt($num) == (i*i <= num)
    if($num%$i == 0){
      $count++;
      if($num/$i != $i){
        $count++;
      }
    }
  }

  if($count == 2){ //Prime number division by only  2 number first one is 1 and second one is itself so count should be 2
    return "PRIME";
  }else{
    return "NOT Prime";
  }
}
echo isPrime(227);

//<!-- Find GCD of two numbers -->
    //GCD = greatest common division , HCF => Highest commmon factor
    //lcm(a,b) * gcd(a,b) = a*b


    function findGCD($a, $b){
        if ($a == 0 || $b==0 ) {
            return $a==0?$b:$a;
        }
        $gcd = "";
        for($i=1;$i<=min($a, $b);$i++){ //GCD will never greater then min value among params
            if(($a%$i == 0) && ($b%$i == 0)){
                $gcd = $i;
            }
        }
        return $gcd;
    }


    
    function findGcd($a, $b){
        if ($a == 0 ) 
            return $b;
        
        if($a > $b) return findGCD($a-$b, $b);  //$a%$b is direct approch of a-b and reduce some number of recursion (52-10, 10) ..... reach (2,10) after 4 recusrion but (52%10, 10) give (2,10) in 1 step
            else return findGCD($b-$a, $a); 
        }

    //formula = gcd(a,b) => gcd(a%b, b) where a>b

    function findGCD($a, $b){
        if($a == 0) return $b;
    
        if($a > $b) return findGCD($a%$b, $b); 
        if($b > $a) return findGCD($b%$a, $a);
    }
    echo findGCD(7,9);


//Given a number N. Find its unique prime factors in increasing order.
//Input
$n = 780;
//First Approch
$prime_factors = array();
for ($i = 2; $i <= sqrt($n); $i++) { //O(N) =>squrt(N)*2*squrt(N)
    if ($n % $i == 0) {

        if(isPrime($i)) $prime_factors[] = $i;

        if($n/$i != $i && isPrime($n/$i)){ //squrt(N)
            $prime_factors[] = $n/$i;
        }
    }
}
print_r($prime_factors);

function isPrime(int $n){//O(N) => squrt(N)
    for($i = 2; $i <= sqrt($n); $i++){
        if($n % $i == 0){
            return false;
        }
    }
    return true;
}

//SecondApproch
$n = 780;
for ($i = 2; $i <= $n; $i++) { //O(N)
    if ($n % $i == 0) {
        $prime_factorss[] = $i;
        while($n % $i == 0){ //2,2,3,4,4,4,5
            $n = $n/$i;
        }
    }
}
print_r($prime_factorss);

//ThirdApproch
$n = 780;
for ($i = 2; $i <= sqrt($n); $i++) { //O(squrt(N))
    if ($n % $i == 0) {
        $prime_factorsss[] = $i;
        while($n % $i == 0){
            $n = $n/$i;
        }
    }
}
if($n != 1) $prime_factorsss[] = $n;
print_r($prime_factorsss);


//Prime Factorization using Sieve
function printPrimeFactorsWithSieve($n) {
  $ans = [];
  $primeFactores = array_fill(2, $n-1, 1);
  $keys = array_keys($primeFactores);
  $primeFactores = array_combine($keys, $keys);
  //make prime
  for ($i = 2; $i <= $n; $i++) {
      if ($primeFactores[$i] == $i) {
          //update all multiples of i
          for ($j = $i * $i; $j <= $n; $j +=$i){
              $primeFactores[$j] = $i;
          }
      }
  }

  while($n > 1){
      if($primeFactores[$n]){
          $ans[$primeFactores[$n]] = $primeFactores[$n]; //for uniqe 2,5
          //echo $primeFactores[$n] . " ";  //for alll factorws 2,2,5
          $n = $n/$primeFactores[$n];
      }
  }  
  print_r($ans);  
}
printPrimeFactorsWithSieve(20);



?>

