<?php 

$n=$m=4;

for($i=0;$i<$n;$i++){
    for($j=0;$j<$n;$j++){
        echo " * ";
    }
    echo "<BR>";
}

// * * * *
// * * * *
// * * * *
// * * * *



//with while
$i=1;
while($i<=$n){
    $j=1;
    while($j<=$n){
        echo $i;
        $j++;
    }
    echo "<BR>";
    $i++;
}

// 1111
// 2222
// 3333
// 4444


$i=1;
while($i<=$n){
    $j=1;
    while($j<=$n){
        echo $j;
        $j++;
    }
    echo "<BR>";
    $i++;
}

// 1234
// 1234
// 1234
// 1234


$i=1; $counter = 1;
while($i<=$n){
    $j=1;
    while($j<=$n){
        echo $counter;
        $j++;
        $counter++;
    }
    echo "<BR>";
    $i++;
}


// 1234
// 5678
// 9101112
// 13141516



$i=1;
while($i<=$n){
    $j=1;
    while($j<=$i){
        echo " * ";
        $j++;
    }
    echo "<BR>";
    $i++;
}

// *
// * *
// * * *
// * * * *



$i=1; $counter = 1;
while($i<=$n){
    $j=1;
    while($j<=$i){
        echo $counter;
        $j++;
        $counter++;
    }
    echo "<BR>";
    $i++;
}

// 1
// 23
// 456
// 78910


$i=1; $counter = 1;
while($i<=$n){
    // $j=$i;
    // while($j>=1){
    //     echo $j;
    //     $j--;
    // }

    $j=1;
    while($j<=$i){
        echo $i-$j+1;
        $j++;
    }
    echo "<BR>";
    $i++;
}

// 1
// 21
// 321
// 4321


$i=1;
$ch = 65; //A
while($i<=$n){
    $j=1;
    while($j<=$n){
        echo chr($ch+$i-1);
        $j++;
    }
    echo "<BR>";
    $i++;
}

// AAAA
// BBBB
// CCCC
// DDDD


$i=1;
while($i<=$n){
    $j=1;
    while($j<=$n){
        echo chr(65+$i-2+$j);
        $j++;
    }
    echo "<BR>";
    $i++;
}

// ABCD
// BCDE
// CDEF
// DEFG

$i=1;
while($i<=$n){
    $j=1;
    while($j<=$i){
        echo chr(65+$i+$j-2);
        $j++;
    }
    echo "<BR>";
    $i++;
}
// A
// BB
// CCC
// DDDD

$i=0;
while($i<$n){
    $j=0;
    while($j<=$i){
        echo chr(65+$n-$i+$j-1);
        $j++;
    }
    echo "<BR>";
    $i++;
}

// D
// CD
// BCD
// ABCD

$n=3;
$i=0;
while($i<$n){
    $j=0;
    while($j<$n){
        echo chr(65+$i+$j);
        $j++;
    }

    echo '<BR>';
    $i++;
}

// ABC
// BCD
// CDE

$n=4;

for($i=1;$i<=$n;$i++){
    for($k=0;$k<$n-$i;$k++){
        echo " _ ";
    }
    
    for($j=$k;$j<$n;$j++){
        echo " * ";
    }
    echo '<BR>';
}

// _ _ _ *
// _ _ * *
// _ * * *
// * * * *



for($i=0;$i<$n;$i++){
    for($k=0;$k<$n-$i;$k++){
        echo " * ";
    }
    
    for($j=$k;$j<$n;$j++){
        echo " _ ";
    }
    echo '<BR>';
}

// * * * *
// * * * _
// * * _ _
// * _ _ _

$n=4;
$i=1;

while($i<=$n){
    $j = 1;
    while($j<$i){
        echo '_';
        $j++;
    }
    while($j<=$n){
        echo $i;
        $j++;
    }
   
    echo '<BR>';
    $i++;
}

// 1111
// _222
// __33
// ___4

$n=4;
$i=1;

while($i<=$n){
    $j = 1;
    while($j<=$n-$i){
        echo '_';
        $j++;
    }
    while($j<=$n){
        echo $i;
        $j++;
    }
   
    echo '<BR>';
    $i++;
}

// ___1
// __22
// _333
// 4444

$n=4;
$i=1;

while($i<=$n){
    $j = 1;
    while($j<=$n-$i){
        echo '_';
        $j++;
    }
    $k=0;
    while($k<$i){
        $k++;
        echo $k;
        
    }
    $k--;
    while($k>0){
        echo $k;
        $k--;
    }
   
    echo '<BR>';
    $i++;
}



?> 