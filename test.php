<?php
$nums = [0,3,7,2,8,4,6,0,1]; 
//Approach 1: Sort linearly and count consecutive sequences 
sort($nums); // O(n log n)
$maxCount = 0;
for($i = 0; $i < count($nums); $i++){
    $tempCount = 1;
    for($j = $i + 1; $j < count($nums); $j++){
        if($nums[$j] == $nums[$j - 1]) continue; // Skip duplicates
        else if($nums[$j] == $nums[$j - 1] + 1){
            $tempCount++;
        } else {
            break; // Break if the sequence is not consecutive
        }
    }
    $maxCount = max($maxCount, $tempCount);
}
echo "Longest Consecutive Sequence Length (Approach 1): " . $maxCount . "\n";

//Approach 2: HashSet for O(n) time complexity
$numsSet = array_flip($nums); // Convert to associative array for O(1) lookup
$maxCount = 0;
foreach($nums as $num){
    // Only start counting if this is the beginning of a sequence like 1, not 2 or 3 which would have been counted when we started at 1 
    if(!isset($numsSet[$num - 1])){ //0 for 1// Check if the previous number is not in the set, meaning this is the start of a sequence for example 1, not 2 or 3 which would have been counted when we started at 1 
        $tempCount = 1;
        //  Keep counting the next consecutive numbers like 2, 3, 4... until we find a gap 
        // 1+1=2, 1+2=3, 1+3=4...
        while(isset($numsSet[$num + $tempCount])){ // Check for the next consecutive number 
            $tempCount++;
        }
        $maxCount = max($maxCount, $tempCount);
    }
}
echo "Longest Consecutive Sequence Length (Approach 2): " . $maxCount . "\n";

//Approach 3: Union-Find (Disjoint Set Union) - More complex but efficient for large datasets
        $maxCount = 0;
        $hash = [];
        $count = count($nums);

        for($i = 0; $i < $count; $i++){
          $hash[$nums[$i]] = 0;
        }

        foreach($hash as $key => $val){
          //if key = 5 then while will check 6,7,8
          $counter=0;
          $counterStart = $key; //5
          while(isset($hash[$key])){ //check 5,6,7,8....  
            if($hash[$key] != 0){ //if already calculated for 6 will be 3 =>6,7,8
              $counter += $hash[$key]; // Add the count of the sequence starting from this key 
              unset($hash[$key]);
            }else{
              unset($hash[$key]); // Mark as visited 
              $counter++;
              $key++;
            }
           
          }
          $hash[$counterStart] = $counter; // Store the count of the sequence starting from the original key (e.g., 5 => 4 for 5,6,7,8) 
          $maxCount = max($maxCount, $counter);
          //print_r($hash); die;
        }
echo "Longest Consecutive Sequence Length (Approach 3): " . $maxCount . "\n";
        
?>