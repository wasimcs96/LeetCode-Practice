<?php
// ============================================================
//  ARRAYS — Complete DSA Revision Guide
//  Topics: Max/Min, Sorting, Two Pointers, Hashing, Matrix
//  Reference: Striver's A2Z DSA Sheet
// ============================================================


// ============================================================
// 1. MAX AND MIN IN AN ARRAY
// ============================================================
// Intuition: Track global max and min while traversing once.
// TC: O(n)  |  SC: O(1)
// ============================================================

$nums = [10, 12, 5, 3, 7, 8];

$max = PHP_INT_MIN;  // Initialise max to smallest possible integer
$min = PHP_INT_MAX;  // Initialise min to largest possible integer

for ($i = 0; $i < count($nums); $i++) {
    if ($nums[$i] > $max) $max = $nums[$i];
    if ($nums[$i] < $min) $min = $nums[$i];
}

echo "Max: $max | Min: $min\n";

// Dry Run  ->  $nums = [10, 12, 5, 3, 7, 8]
// i=0: max=10, min=10
// i=1: max=12, min=10
// i=2: max=12, min=5
// i=3: max=12, min=3
// Output: Max: 12 | Min: 3


// ============================================================
// 2. SECOND LARGEST AND SECOND SMALLEST
// ============================================================
// Intuition: Keep top-2 max and top-2 min while traversing.
//   - On finding new max, push current max down to second_max.
//   - Skip duplicates with the != check.
// TC: O(n)  |  SC: O(1)
// ============================================================

$nums = [10, 12, 5, 3, 7, 8, 10];

$max        = PHP_INT_MIN;
$second_max = PHP_INT_MIN;
$min        = PHP_INT_MAX;
$second_min = PHP_INT_MAX;

for ($i = 0; $i < count($nums); $i++) {
    // --- Max tracking ---
    if ($nums[$i] > $max) {
        $second_max = $max;           // Old max becomes second
        $max        = $nums[$i];
    // elseif ($arr[$i] < $maxElement && $arr[$i] > $secondMaxElement){ //Also a solution, but we need to check duplicates with $nums[$i] != $max to avoid wrong updates when max is repeated.
    } elseif ($nums[$i] > $second_max && $nums[$i] != $max) {
        $second_max = $nums[$i];      // Update second_max (skip duplicates)
    }

    // --- Min tracking ---
    if ($nums[$i] < $min) {
        $second_min = $min;           // Old min becomes second
        $min        = $nums[$i];
    // elseif ($arr[$i] > $minElement && $arr[$i] < $secondMinElement){ //Also a solution, but we need to check duplicates with $nums[$i] != $min to avoid wrong updates when min is repeated.
    } elseif ($nums[$i] < $second_min && $nums[$i] != $min) {
        $second_min = $nums[$i];      // Update second_min (skip duplicates)
    }
}

echo "Second Max: $second_max | Second Min: $second_min\n";

// Dry Run  ->  $nums = [10, 12, 5, 3, 7, 8, 10]
// After traversal: max=12, second_max=10, min=3, second_min=5
// Output: Second Max: 10 | Second Min: 5


// ============================================================
// 3. CHECK IF ARRAY IS SORTED (ASCENDING)
// ============================================================
// Intuition: A sorted array has no adjacent pair where a[i] > a[i+1].
// TC: O(n)  |  SC: O(1)
// ============================================================

function isSorted(array $nums): bool {
    for ($i = 0; $i < count($nums) - 1; $i++) {
        if ($nums[$i] > $nums[$i + 1]) return false;  // Violation found
    }
    return true;
}

echo "Is sorted: " . (isSorted([1, 2, 3, 4]) ? "true" : "false") . "\n"; // true
echo "Is sorted: " . (isSorted([2, 3, 4, 1]) ? "true" : "false") . "\n"; // false


// ============================================================
// 4. LC 1752 -- CHECK IF ARRAY IS SORTED AND ROTATED
// ============================================================
// Intuition: In a sorted+rotated array there is at most ONE "drop"
//   (a place where nums[i] > nums[next]).
//   If there are 0 or 1 drops -> valid.  More than 1 drop -> invalid.
//   Use modulo to wrap the last element around to index 0.
// TC: O(n)  |  SC: O(1)
// ============================================================

function check(array $nums): bool {
    $count         = count($nums);
    $rotationCount = 0;

    for ($i = 0; $i < $count; $i++) {
//Imp->  $nextIndex = (int) ($i+1) % $count; //lets assume count 5, nextIndex give 1,2,3,4,5,1
        $nextIndex = ($i + 1) % $count;  // Wraps last -> first
        if ($nums[$i] > $nums[$nextIndex]) {
            $rotationCount++;
        }
    }

    return $rotationCount <= 1;  // At most one "drop" is allowed
}

// Dry Run  ->  $nums = [3, 4, 5, 1, 2]
// Pairs: (3,4)ok (4,5)ok (5,1)drop=1 (1,2)ok (2,3)ok wrap
// rotationCount=1 -> true

var_dump(check([3, 4, 5, 1, 2])); // bool(true)
var_dump(check([2, 1, 3, 4]));    // bool(false)  -- two drops


// ============================================================
// 5. LC 26 -- REMOVE DUPLICATES FROM SORTED ARRAY (IN-PLACE)
// ============================================================


// Initial naive approach (not in-place, just for understanding):
$i=0; $count = count($arr); $rotatedCount = 0;
while($i < $count-1){
    if($arr[$i] == $arr[$i+1]){
        unset($arr[$i]);
    }
    $i++;
}

// Intuition: Two-pointer approach.
//   i = slow pointer (last unique position)
//   j = fast pointer (scanner)
//   When nums[j] != nums[i], move i forward and write nums[j] there.
// TC: O(n)  |  SC: O(1)
// ============================================================
function removeDuplicates(array &$nums): int {
    $i = 0;  // Points to the last written unique element

    for ($j = 1; $j < count($nums); $j++) {
        if ($nums[$j] !== $nums[$i]) {  // Found a new unique element
            $i++;
            $nums[$i] = $nums[$j];  // Write it right after last unique
        }
    }

    return $i + 1;  // Length of unique part
}

// Dry Run  ->  $nums = [-4, -4, 0, 0, 1, 1, 1, 2, 2, 3, 3, 4]
// i=0(-4), j scans:
//   j=1: -4==-4 skip
//   j=2:  0!=-4 -> i=1, nums[1]=0
//   j=3:  0==0  skip
//   j=4:  1!=0  -> i=2, nums[2]=1
//   ...continues until all uniques written
// Output array: [-4,0,1,2,3,4,...], returns 6

$nums = [-4, -4, 0, 0, 1, 1, 1, 2, 2, 3, 3, 4];
$len  = removeDuplicates($nums);
echo "Unique count: $len\n";
echo "Array: " . implode(", ", array_slice($nums, 0, $len)) . "\n";


// ============================================================
// 6. LC 189 -- ROTATE ARRAY BY K POSITIONS (RIGHT)
// ============================================================
//Bruit-force approach (not in-place, just for understanding):
$i=0; $j=0; $length = count($arr)-1; $rotated = 2;
while($rotated){
    $temp = $arr[$i];
    while($j < $length){
        $arr[$j] = $arr[$j+1];
        $j++;
    }
    $arr[$j] = $temp;
    $rotated--;
    $i=0; $j=0;
}
print_r($arr);
// Intuition: Reversal algorithm -- 3 reversals achieve rotation.
//   Step 1: Reverse last k elements  ->  [..., k_reversed]
//   Step 2: Reverse first n-k elements ->  [first_reversed, ...]
//   Step 3: Reverse entire array       ->  final rotated result
// TC: O(n)  |  SC: O(1)
// ============================================================
class Solution {
function rotate(&$nums, $k) {
        $arrLength = count($nums);
        if($k == 0 || $arrLength == 0) return;

        //imp ->    if ($k >= $n) return; // This check is not needed if we do k = k % n, as it will handle cases where k > n by effectively reducing it to a valid rotation within the array length.
        //Below logic for handling cases where k is greater than the length of the array is crucial for ensuring that the rotation works correctly regardless of the value of k. By using the modulus operator, we can effectively reduce k to a valid rotation within the bounds of the array length. For example, if we have an array of length 10 and we want to rotate it by 14 positions, we can calculate k as follows:
        $k = (int) $k % $arrLength; // k=14 on array of 10 -> effectively k=4
        
        //Example: For Right Rotate Just a simple logic. [-> 1, 2, 3, 4, | 5, 6, 7 ->] if k=3
        //Let's say we have an array [1, 2, 3, 4, 5, 6, 7] and we want to rotate it to the right by 3 positions. The steps would be as follows:
        //1. Reverse the last k elements: Reverse the last 3 elements [5, 6, 7] to get [7, 6, 5]. The array now
        //   looks like this: [1, 2, 3, 4, 7, 6, 5].
        //2. Reverse the first n-k elements: Reverse the first 4 elements [1, 2, 3, 4] to get [4, 3, 2
        //   , 1]. The array now looks like this: [4, 3, 2, 1, 7, 6, 5].
        //3. Reverse the entire array: Reverse the entire array [4, 3, 2, 1, 7, 6, 5] to get [5, 6, 7, 1, 2, 3, 4]. The final array is the rotated version of the original array.
        // $this->reverse($nums, 0, $arrLength-$k-1);
        // $this->reverse($nums, $arrLength-$k, $arrLength-1);
        // $this->reverse($nums, 0, $arrLength-1);

        //for Left Roate Just a simple logic [<- 1, 2, 3,| 4, 5, 6, 7 <-] if k=3
        //Example: For Right Rotate Just a simple logic Let's say we have an array [1, 2, 3, 4, 5, 6, 7] and we want to rotate it to the left by 3 positions. The steps would be as follows:
        //1. Reverse the first k elements: Reverse the first 3 elements [1, 2, 3] to get [3, 2, 1]. The array now looks like this: [3, 2, 1, 4, 5, 6, 7].
        //2. Reverse the remaining n-k elements: Reverse the remaining 4 elements [4, 5, 6, 7] to get [7, 6, 5, 4]. The array now looks like this: [3, 2, 1, 7, 6, 5, 4].
        //3. Reverse the entire array: Reverse the entire array [3, 2, 1, 7, 6, 5, 4] to get [4, 5, 6, 7, 1, 2, 3]. The final array is the rotated version of the original array.   
        $this->reverse($nums, 0, $k-1); print_r($nums);
        $this->reverse($nums, $k, $arrLength-1);print_r($nums);
        $this->reverse($nums, 0, $arrLength-1);print_r($nums);
    }

    function reverse(&$arr, $st, $ed){
        echo "st: $st, ed: $ed\n";
        while($st < $ed){
            list($arr[$st], $arr[$ed]) = [$arr[$ed], $arr[$st]];
            $st++; $ed--;
        }
    } 
}
$arr = [1, 2, 3, 4, 5, 6, 7];
$i=0;  $length = count($arr); $j=$length-1; $rotated = 3;
$solution = new Solution();
$solution->rotate($arr, $rotated);
print_r($arr);

// ============================================================
// 7. LC 283 -- MOVE ZEROES TO END
// ============================================================
// Intuition: Two-pointer swap -- j scans for non-zero,
//   i tracks next empty slot. Swap places non-zero at front.
//   Relative order of non-zero elements is preserved.
// TC: O(n)  |  SC: O(1)
// ============================================================

function moveZeroes(array &$nums): void {
    $i = 0;  // Next position to place a non-zero element
    //Dry and Run the code with example to understand the logic. Let's say we have an array [0, 1, 0, 3, 12]. We want to move all the zeroes to the end while maintaining the relative order of the non-zero elements. The steps would be as follows:
        //1. Initialize two pointers: We start with two pointers, i and j. The pointer i will keep track of the position where the next non-zero element should be placed, while the pointer j will iterate through the array to find non-zero elements. Initially, i is set to 0 (indicating the starting index for the next non-zero element) and j is set to 0 (the starting index of the array).
        //2. Iterate through the array: We use a while loop to iterate through the array with the pointer j. For each element at index j:
        //   - If the element is zero, we do nothing and move to the next element.
        //   - If the element is non-zero, it means we have found a non-zero element and we need to move it to the position indicated by i. We swap the elements at indices i and j, and then increment i to point to the next position for the next non-zero element.
        //3. Continue iterating: We continue iterating through the array until j reaches the end. By the end of the loop, all non-zero elements will have been moved to the front of the array in their original order, and all zeroes will be moved to the end.
    for ($j = 0; $j < count($nums); $j++) {
        if ($nums[$j] != 0) {           // Found a non-zero element
            [$nums[$i], $nums[$j]] = [$nums[$j], $nums[$i]];
            $i++;
        }
    }
}

// Dry Run  ->  $nums = [0, 1, 0, 3, 12]
// j=0: 0 skip
// j=1: 1!=0 -> swap(nums[0],nums[1]) -> [1,0,0,3,12], i=1
// j=2: 0 skip
// j=3: 3!=0 -> swap(nums[1],nums[3]) -> [1,3,0,0,12], i=2
// j=4: 12!=0-> swap(nums[2],nums[4]) -> [1,3,12,0,0], i=3
// Output: [1,3,12,0,0]


//Another solution is 
// We can optimize the approach using 2 pointers i.e. i and j. The pointer j will point to the first 0 in the array and i will point to the next index.

// Assume, the given array is {1, 0, 2, 3, 2, 0, 0, 4, 5, 1}. Now, initially, we will place the 2-pointers like the following:
// First, we iterate through the array to locate the position of the first zero, using a pointer j. If no zero is found, no further steps are needed.
// Next, we set a second pointer i to j + 1 and start moving it forward through the array.
// While moving i, whenever we encounter a non-zero element a[i], we swap it with the element at index j. After the swap, since j now holds a non-zero value, we increment j to point to the next zero.

$nums = [0, 1, 0, 3, 12];
moveZeroes($nums);
echo "After moveZeroes: " . implode(", ", $nums) . "\n";  // 1,3,12,0,0


// ============================================================
// 8. UNION OF TWO SORTED ARRAYS
// ============================================================
// Intuition: Merge-like traversal with two pointers.
//   Compare heads; pick the smaller (or equal) one and advance.
//   Use a hash-keyed array to auto-deduplicate.
// TC: O(m + n)  |  SC: O(m + n)
// ============================================================

function findUnion(array $arr1, array $arr2): array {
    $uni    = [];
    $count1 = count($arr1);
    $count2 = count($arr2);
    $i = $j = 0;

    while ($i < $count1 && $j < $count2) {
        if ($arr1[$i] < $arr2[$j]) {
            $uni[$arr1[$i]] = $arr1[$i];  // Key = value -> auto dedup
            $i++;
        } elseif ($arr1[$i] > $arr2[$j]) {
            $uni[$arr2[$j]] = $arr2[$j];
            $j++;
        } else {
            $uni[$arr1[$i]] = $arr1[$i];  // Equal -- add once
            $i++;
            $j++;
        }
    }

    // Drain remaining elements
    while ($i < $count1) { $uni[$arr1[$i]] = $arr1[$i]; $i++; }
    while ($j < $count2) { $uni[$arr2[$j]] = $arr2[$j]; $j++; }

    return array_values($uni);
}

// Dry Run  ->  arr1=[1,2,3,4,5], arr2=[2,3,4,4,5,11,12]
// Merge picks 1, then 2(both advance), 3, 4, 5, then drains 11,12
// Output: [1,2,3,4,5,11,12]

$result = findUnion([1, 2, 3, 4, 5], [2, 3, 4, 4, 5, 11, 12]);
echo "Union: " . implode(", ", $result) . "\n";


// ============================================================
// 9. LC 268 -- MISSING NUMBER (0 to N)
// ============================================================
//Bruit-force approach (not optimal):
$arr = [1,2,3,5];
$naturalNumber = count($arr) + 1; // Since one number is missing, the total count should be n+1
$i=1;
while($i<=$naturalNumber){
    $j = 0; $isFound = false;
    while($j<count($arr)){
        if($arr[$j] == $i){
            $isFound = true;
            break;
        }
        $j++;
    }
    if(!$isFound){
        echo "Missing number: $i\n";
        break;
    }
    $i++;
}


//Second Approch using hasmap
$arr = [1,2,3,5];
$naturalNumber = count($arr) + 1; // Since one number is missing, the total count should be n+1
$i=1; $hashMap = [];
foreach($arr as $num){
    $hashMap[$num] = true; // Mark the presence of each number in the array
}
while($i<=$naturalNumber){
    if(!isset($hashMap[$i])) echo "Missing number is: $i\n"; // If a number from 1 to n is not found in the hash map, it is the missing number
    $i++;
}


// Intuition: Sum of first N natural numbers = N*(N+1)/2.
//   Subtract actual array sum -> remainder is the missing number.
// TC: O(n)  |  SC: O(1)
// ============================================================

function missingNumber(array $nums): int {
    $n        = count($nums);
    $expected = $n * ($n + 1) / 2;  // Gauss formula
    $actual   = array_sum($nums);

    return $expected - $actual;
}

// Dry Run  ->  $nums = [3, 0, 1]
// n=3, expected = 3*4/2 = 6
// actual = 3+0+1 = 4
// missing = 6-4 = 2  ok

echo "Missing: " . missingNumber([3, 0, 1]) . "\n";  // 2


// ============================================================
// 10. LC 485 -- MAX CONSECUTIVE ONES
// ============================================================
// Intuition: Reset a running counter on every 0; track global max.
// TC: O(n)  |  SC: O(1)
// ============================================================

function findMaxConsecutiveOnes(array $nums): int {
    $max       = 0;
    $tempCount = 0;

    for ($i = 0; $i < count($nums); $i++) {
        if ($nums[$i] === 1) {
            $tempCount++;
        } else {
            $tempCount = 0;  // Reset on zero
        }
        $max = max($max, $tempCount);
    }

    return $max;
}

// Dry Run  ->  $nums = [1,1,0,1,1,1]
// i=0:cnt=1  i=1:cnt=2  i=2:reset=0  i=3:cnt=1  i=4:cnt=2  i=5:cnt=3
// max=3

echo "Max consecutive ones: " . findMaxConsecutiveOnes([1, 1, 0, 1, 1, 1]) . "\n";  // 3


// ============================================================
// 11. LC 136 -- SINGLE NUMBER (XOR TRICK)
// ============================================================
//Hash Map Approch
$arr = [4,1,2,1,2];
$arrLength = count($arr);

$hashMap = [];
foreach($arr as $value){
    if(isset($hashMap[$value])) $hashMap[$value]++;
    else $hashMap[$value] = 1;
}
foreach($hashMap as $key => $value){
    if($value == 1) { 
        echo($key); break;
    }
}


// Intuition: XOR of a number with itself = 0; XOR with 0 = itself.
//   All duplicates cancel out -> only the unique element remains.
// TC: O(n)  |  SC: O(1)
// ============================================================

function singleNumber(array $nums): int {
    $ans = 0;

    foreach ($nums as $num) {
        $ans ^= $num;  // a^a=0, a^0=a -> duplicates cancel
    }

    return $ans;
}

// Dry Run  ->  $nums = [4, 1, 2, 1, 2]
// 0^4=4 -> 4^1=5 -> 5^2=7 -> 7^1=6 -> 6^2=4
// Output: 4  ok

echo "Single number: " . singleNumber([4, 1, 2, 1, 2]) . "\n";  // 4


// ============================================================
// 12. LONGEST SUBARRAY WITH SUM EQUALS K  (Prefix Sum + HashMap)
// ============================================================
// Intuition:
//   prefix_sum[i] = sum of nums[0..i]
//   If sum == k -> subarray from 0..i is valid, length = i+1
//   If (sum - k) exists in hash -> subarray from hash[sum-k]+1..i
//     has sum == k. Store FIRST occurrence of each prefix sum.
//
//   Diagram:
//   <-----------sum (0 to i)----------->
//   <---rem (0 to hash[rem])---> <--k-->
//
// TC: O(n)  |  SC: O(n)
// Note: Works for +ve and -ve numbers (unlike sliding window)
// ============================================================

function longestSubarraySumK(array $nums, int $k): int {
    $hash   = [];   // prefix_sum -> first index seen
    $sum    = 0;
    $maxLen = 0;

    for ($i = 0; $i < count($nums); $i++) {
        $sum += $nums[$i];

        if ($sum === $k) {
            $maxLen = max($maxLen, $i + 1);  // Entire prefix
        }

        $rem = $sum - $k;  // We need rem to have appeared before

        if (isset($hash[$rem])) {
            $maxLen = max($maxLen, $i - $hash[$rem]);
        }

        // Store only FIRST occurrence so subarray length is maximised
        if (!isset($hash[$sum])) {
            $hash[$sum] = $i;
        }
    }

    return $maxLen;
}

// Dry Run  ->  $nums = [10,5,2,7,1,9], k=15
// i=0: sum=10, rem=-5 (not in hash), hash={10:0}
// i=1: sum=15, sum==k -> maxLen=2, rem=0 (not in hash)
// i=2: sum=17, rem=2 (not in hash)
// i=3: sum=24, rem=9 (not in hash)
// i=4: sum=25, rem=10 -> in hash at idx 0 -> len=4-0=4, maxLen=4
// i=5: sum=34, rem=19 (not in hash), maxLen stays 4
// Output: 4  (subarray [5,2,7,1])

echo "Longest subarray (sum=15): " . longestSubarraySumK([10, 5, 2, 7, 1, 9], 15) . "\n";  // 4


// ============================================================
// 12b. LONGEST SUBARRAY SUM K -- SLIDING WINDOW (only +ve numbers)
// ============================================================
// Intuition: Expand right pointer; shrink left when sum > k.
//   Only valid for non-negative arrays.
// TC: O(n)  |  SC: O(1)
// ============================================================

function longestSubarrayPositive(array $nums, int $k): int {
    $i = $j = $sum = $maxLen = 0;
    $n = count($nums);

    while ($j < $n) {
        $sum += $nums[$j];

        while ($sum > $k && $i <= $j) {
            $sum -= $nums[$i++];  // Shrink window from left
        }

        if ($sum === $k) {
            $maxLen = max($maxLen, $j - $i + 1);
        }

        $j++;
    }

    return $maxLen;
}


// ============================================================
// 13. LC 1 -- TWO SUM
// ============================================================
// Intuition: For each element x, check if (target - x) was seen.
//   Store target-nums[i] -> i in hashmap; if nums[j] exists in map,
//   we found the pair.
// TC: O(n)  |  SC: O(n)
// ============================================================

function twoSum(array $nums, int $target): array {
    $map = [];  // value -> index

    for ($i = 0; $i < count($nums); $i++) {
        if (isset($map[$nums[$i]])) {
            return [$map[$nums[$i]], $i];     // Complement was stored earlier
        }
        $map[$target - $nums[$i]] = $i;       // Store complement -> index
    }

    return [];
}

// Dry Run  ->  $nums = [2,7,11,15], target=9
// i=0: map[9-2]=map[7]=0
// i=1: nums[1]=7 -> isset(map[7])=true -> return [0,1]  ok

print_r(twoSum([2, 7, 11, 15], 9));  // [0, 1]

// Two-pointer variant (when only YES/NO needed, array must be sorted)
function twoSumExists(array $nums, int $target): bool {
    sort($nums);
    $i = 0;
    $j = count($nums) - 1;

    while ($i < $j) {
        $sum = $nums[$i] + $nums[$j];
        if ($sum === $target)     return true;
        elseif ($sum < $target)   $i++;
        else                      $j--;
    }

    return false;
}


// ============================================================
// 14. LC 75 -- SORT COLORS (DUTCH NATIONAL FLAG ALGORITHM)
// ============================================================
// Intuition: Three-pointer approach -- low, mid, high.
//   - 0s go to [0..low-1]
//   - 1s go to [low..mid-1]
//   - 2s go to [high+1..n-1]
//   mid scans; swap with low for 0, swap with high for 2.
// TC: O(n)  |  SC: O(1)
// ============================================================

function sortColors(array &$nums): void {
    $low  = 0;
    $mid  = 0;
    $high = count($nums) - 1;

    while ($mid <= $high) {
        if ($nums[$mid] === 0) {
            [$nums[$low], $nums[$mid]] = [$nums[$mid], $nums[$low]];
            $low++;
            $mid++;
        } elseif ($nums[$mid] === 1) {
            $mid++;            // 1 is already in correct zone
        } else {               // nums[$mid] === 2
            [$nums[$high], $nums[$mid]] = [$nums[$mid], $nums[$high]];
            $high--;           // Do NOT increment mid; re-examine swapped value
        }
    }
}

// Dry Run  ->  $nums = [2,0,2,1,1,0]
// low=0,mid=0,high=5
// mid=2: swap(high,mid)->[0,0,2,1,1,2], high=4
// mid=0: swap(low,mid)->[0,0,2,1,1,2], low=1,mid=1
// mid=0: swap(low,mid)->[0,0,2,1,1,2], low=2,mid=2
// mid=2: swap(high,mid)->[0,0,1,1,2,2], high=3
// mid=1: mid++->mid=3
// mid=1: mid++->mid=4 (4>high=3 stop)
// Output: [0,0,1,1,2,2]  ok

$nums = [2, 0, 2, 1, 1, 0];
sortColors($nums);
echo "Sorted colors: " . implode(", ", $nums) . "\n";  // 0,0,1,1,2,2


// ============================================================
// 15. LC 169 -- MAJORITY ELEMENT (BOYER-MOORE VOTING)
// ============================================================
// Intuition: The majority element appears > n/2 times.
//   Cancel each non-majority element with one majority element.
//   The survivor after all cancellations is the majority element.
//   Final count verification ensures correctness.
// TC: O(n)  |  SC: O(1)
// ============================================================

function majorityElement(array $nums): int {
    $candidate = 0;
    $count     = 0;

    // Phase 1: Find candidate
    foreach ($nums as $num) {
        if ($count === 0) {
            $candidate = $num;  // Pick new candidate when count exhausted
        }
        $count += ($num === $candidate) ? 1 : -1;
    }

    // Phase 2: Verify candidate (required if majority not guaranteed)
    $verify = array_count_values($nums)[$candidate] ?? 0;
    if ($verify > count($nums) / 2) {
        return $candidate;
    }

    return -1;  // No majority element
}

// Dry Run  ->  $nums = [2,2,1,1,1,2,2]
// 2(c=1) 2(c=2) 1(c=1) 1(c=0) 1(cand=1,c=1) 2(c=0) 2(cand=2,c=1)
// candidate=2, count(2)=4 > 7/2=3.5 -> return 2  ok

echo "Majority element: " . majorityElement([2, 2, 1, 1, 1, 2, 2]) . "\n";  // 2


// ============================================================
// 16. LC 53 -- MAXIMUM SUBARRAY (KADANE'S ALGORITHM)
// ============================================================
// Intuition: Extend current subarray if it's beneficial.
//   If running sum goes negative, discard it (start fresh).
//   Track start/end indices for the actual subarray.
// TC: O(n)  |  SC: O(1)
// ============================================================

function maxSubArray(array $nums): array {
    $sum     = 0;
    $max     = PHP_INT_MIN;
    $start   = 0;
    $end     = 0;
    $tempStr = 0;

    for ($i = 0; $i < count($nums); $i++) {
        if ($sum === 0) $tempStr = $i;  // Potential new start

        $sum += $nums[$i];

        if ($sum > $max) {
            $max   = $sum;
            $start = $tempStr;
            $end   = $i;
        }

        if ($sum < 0) $sum = 0;  // Discard negative prefix
    }

    return ['maxSum' => $max, 'start' => $start, 'end' => $end,
            'subarray' => array_slice($nums, $start, $end - $start + 1)];
}

// Dry Run  ->  $nums = [-2,1,-3,4,-1,2,1,-5,4]
// i=0: sum=-2, max=-2  -> sum<0 reset to 0
// i=1: sum=1,  max=1,  start=1,end=1
// i=2: sum=-2, max=1   -> sum<0 reset to 0
// i=3: sum=4,  max=4,  start=3,end=3
// i=4: sum=3,  max=4
// i=5: sum=5,  max=5,  start=3,end=5
// i=6: sum=6,  max=6,  start=3,end=6
// i=7: sum=1,  max=6
// i=8: sum=5,  max=6
// Output: maxSum=6, subarray=[4,-1,2,1]

$result = maxSubArray([-2, 1, -3, 4, -1, 2, 1, -5, 4]);
echo "Max subarray sum: " . $result['maxSum'] . "\n";           // 6
echo "Subarray: " . implode(", ", $result['subarray']) . "\n";  // 4,-1,2,1


// ============================================================
// 17. LC 121 -- BEST TIME TO BUY AND SELL STOCK
// ============================================================
// Intuition: Track the minimum price seen so far (buy point).
//   For each day, profit = price - min_buy.
//   Track global maximum profit.
// TC: O(n)  |  SC: O(1)
// ============================================================

function maxProfit(array $prices): int {
    $maxProfit = 0;
    $minBuy    = PHP_INT_MAX;

    foreach ($prices as $price) {
        $minBuy    = min($minBuy, $price);              // Update cheapest buy
        $maxProfit = max($maxProfit, $price - $minBuy); // Update best profit
    }

    return $maxProfit;
}

// Dry Run  ->  $prices = [7,1,5,3,6,4]
// i=0: buy=7, profit=0
// i=1: buy=1, profit=0
// i=2: buy=1, profit=4
// i=3: buy=1, profit=4
// i=4: buy=1, profit=5
// i=5: buy=1, profit=5
// Output: 5  (buy on day 2 at 1, sell day 5 at 6)

echo "Max profit: " . maxProfit([7, 1, 5, 3, 6, 4]) . "\n";  // 5


// ============================================================
// 18. LC 2149 -- REARRANGE ARRAY ELEMENTS BY SIGN
// ============================================================

// --- 18a. Equal positives and negatives (optimal O(n)) ---
// Intuition: Positives at even indices (0,2,4...), negatives at odd (1,3,5...).
//   Single pass with two independent index pointers.
// TC: O(n)  |  SC: O(n)

function rearrangeEqual(array $nums): array {
    $n      = count($nums);
    $ans    = array_fill(0, $n, 0);
    $posIdx = 0;  // Next even index for positive
    $negIdx = 1;  // Next odd index for negative

    foreach ($nums as $num) {
        if ($num > 0) {
            $ans[$posIdx] = $num;
            $posIdx += 2;
        } else {
            $ans[$negIdx] = $num;
            $negIdx += 2;
        }
    }

    return $ans;
}

// --- 18b. Unequal positives and negatives ---
// Intuition: Separate into pos[] and neg[], interleave up to min count,
//   then append the excess of the longer array.
// TC: O(n)  |  SC: O(n)

function rearrangeUnequal(array $nums): array {
    $posArr = array_values(array_filter($nums, fn($x) => $x > 0));
    $negArr = array_values(array_filter($nums, fn($x) => $x < 0));
    $ans    = [];

    $minLen = min(count($posArr), count($negArr));

    // Interleave equal portion
    for ($i = 0; $i < $minLen; $i++) {
        $ans[] = $posArr[$i];
        $ans[] = $negArr[$i];
    }

    // Append remaining elements
    for ($i = $minLen; $i < count($posArr); $i++) $ans[] = $posArr[$i];
    for ($i = $minLen; $i < count($negArr); $i++) $ans[] = $negArr[$i];

    return $ans;
}

print_r(rearrangeEqual([3, 1, -2, -5, 2, -4]));   // [3,-2,1,-5,2,-4]
print_r(rearrangeUnequal([-1, 1, -2, -3, 2, 3])); // interleaved + extra negatives


// ============================================================
// 19. LC 31 -- NEXT PERMUTATION
// ============================================================
// Intuition: Three-step algorithm:
//   1. Find the RIGHTMOST index i where nums[i] < nums[i+1] (dip point)
//   2. Find the RIGHTMOST index j > i where nums[j] > nums[i], swap them
//   3. Reverse the suffix after index i (makes it the smallest arrangement)
//   If no dip found -> array is fully descending -> reverse all (wraps to first).
// TC: O(n)  |  SC: O(1)
// ============================================================

function nextPermutation(array &$nums): void {
    $n   = count($nums);
    $dip = -1;

    // Step 1: Find rightmost dip
    for ($i = $n - 2; $i >= 0; $i--) {
        if ($nums[$i] < $nums[$i + 1]) {
            $dip = $i;
            break;
        }
    }

    // If entire array is descending -> reverse to get first permutation
    if ($dip === -1) {
        reverseArr($nums, 0, $n - 1);
        return;
    }

    // Step 2: Find rightmost element greater than nums[dip]
    for ($j = $n - 1; $j > $dip; $j--) {
        if ($nums[$j] > $nums[$dip]) {
            [$nums[$dip], $nums[$j]] = [$nums[$j], $nums[$dip]];
            break;
        }
    }

    // Step 3: Reverse suffix after dip
    reverseArr($nums, $dip + 1, $n - 1);
}

// Dry Run  ->  $nums = [2,1,5,4,3,0,0]
// Step 1: scan right->left: 3>0 skip, 4>3 skip, 5>4 skip, 1<5 -> dip=1
// Step 2: scan right for > nums[1]=1: found 3 at idx 4 -> swap -> [2,3,5,4,1,0,0]
// Step 3: reverse suffix after idx 1 -> [2,3,0,0,1,4,5]
// Output: [2,3,0,0,1,4,5]  ok

$nums = [2, 1, 5, 4, 3, 0, 0];
nextPermutation($nums);
echo "Next permutation: " . implode(", ", $nums) . "\n";


// ============================================================
// 20. LC 128 -- LONGEST CONSECUTIVE SEQUENCE
// ============================================================
// Intuition: Only start counting from the BEGINNING of a sequence
//   (i.e., when num-1 is NOT in the set). This avoids redundant work.
// TC: O(n)  |  SC: O(n)
// ============================================================

function longestConsecutive(array $nums): int {
    $numSet   = array_flip($nums);  // O(1) lookup
    $maxCount = 0;

    foreach ($numSet as $num => $_) {
        // Only start from sequence beginning (no num-1 in set)
        if (!isset($numSet[$num - 1])) {
            $current = $num;
            $count   = 1;

            while (isset($numSet[$current + 1])) {
                $current++;
                $count++;
            }

            $maxCount = max($maxCount, $count);
        }
    }

    return $maxCount;
}

// Dry Run  ->  $nums = [100,4,200,1,3,2]
// Set: {100,4,200,1,3,2}
// num=100: no 99 in set -> count 100 only -> len=1
// num=4:   3 in set -> skip (not a start)
// num=200: no 199 -> len=1
// num=1:   no 0 -> count 1,2,3,4 -> len=4
// Output: 4  (sequence 1,2,3,4)

echo "Longest consecutive: " . longestConsecutive([100, 4, 200, 1, 3, 2]) . "\n";  // 4


// ============================================================
// 21. LC 48 -- ROTATE IMAGE (90 degrees CLOCKWISE, IN-PLACE)
// ============================================================
// Intuition: Two-step process:
//   1. Transpose  -> matrix[i][j] swaps with matrix[j][i]
//   2. Reverse each row  -> completes the 90 degree clockwise rotation
// TC: O(n^2)  |  SC: O(1)
// ============================================================

function rotateMatrix(array &$matrix): void {
    $n = count($matrix);

    // Step 1: Transpose (mirror along main diagonal)
    for ($i = 0; $i < $n; $i++) {
        for ($j = 0; $j < $i; $j++) {   // Only below diagonal to avoid double-swap
            [$matrix[$i][$j], $matrix[$j][$i]] = [$matrix[$j][$i], $matrix[$i][$j]];
        }
    }

    // Step 2: Reverse each row
    foreach ($matrix as &$row) {
        $row = array_reverse($row);
    }
}

// Dry Run  ->  [[1,2,3],[4,5,6],[7,8,9]]
// After transpose: [[1,4,7],[2,5,8],[3,6,9]]
// After row reverse: [[7,4,1],[8,5,2],[9,6,3]]  ok

$matrix = [[1, 2, 3], [4, 5, 6], [7, 8, 9]];
rotateMatrix($matrix);
echo "Rotated matrix:\n";
foreach ($matrix as $row) echo implode(" ", $row) . "\n";
// 7 4 1
// 8 5 2
// 9 6 3


// ============================================================
// 22. LC 73 -- SET MATRIX ZEROES (IN-PLACE, O(1) SPACE)
// ============================================================
// Intuition: Use first row and first column as marker arrays.
//   - matrix[i][0]=0 marks row i should be zeroed.
//   - matrix[0][j]=0 marks col j should be zeroed.
//   - col0 variable tracks whether column 0 itself needs zeroing
//     (to avoid overwriting the row-0 marker).
// TC: O(m*n)  |  SC: O(1)
// ============================================================

function setZeroes(array &$matrix): void {
    $rows = count($matrix);
    $cols = count($matrix[0]);
    $col0 = 1;  // Tracks if column 0 should be zeroed

    // Pass 1: Mark first row/col as flags
    for ($i = 0; $i < $rows; $i++) {
        for ($j = 0; $j < $cols; $j++) {
            if ($matrix[$i][$j] === 0) {
                $matrix[$i][0] = 0;    // Mark row

                if ($j !== 0) {
                    $matrix[0][$j] = 0;  // Mark column
                } else {
                    $col0 = 0;           // Column 0 itself has a zero
                }
            }
        }
    }

    // Pass 2: Zero inner cells (skip row 0 and col 0 -- they're markers)
    for ($i = 1; $i < $rows; $i++) {
        for ($j = 1; $j < $cols; $j++) {
            if ($matrix[$i][0] === 0 || $matrix[0][$j] === 0) {
                $matrix[$i][$j] = 0;
            }
        }
    }

    // Pass 3: Handle first row using matrix[0][0]
    if ($matrix[0][0] === 0) {
        for ($j = 0; $j < $cols; $j++) $matrix[0][$j] = 0;
    }

    // Pass 4: Handle first column using col0 flag
    if ($col0 === 0) {
        for ($i = 0; $i < $rows; $i++) $matrix[$i][0] = 0;
    }
}

// Dry Run  ->  [[1,1,1],[1,0,1],[1,1,1]]
// Pass 1: matrix[1][1]=0 -> mark matrix[1][0]=0, matrix[0][1]=0
// Pass 2: row 1 -> all 0; col 1 -> all 0
// Result: [[1,0,1],[0,0,0],[1,0,1]]  ok

$matrix = [[1, 1, 1], [1, 0, 1], [1, 1, 1]];
setZeroes($matrix);
echo "Matrix with zeroes:\n";
foreach ($matrix as $row) echo implode(" ", $row) . "\n";


// ============================================================
// 23. LC 54 -- SPIRAL MATRIX TRAVERSAL
// ============================================================
// Intuition: Maintain four boundaries: upRow, botRow, leftCol, rightCol.
//   Traverse in order: -> down <- up and shrink the boundary after each pass.
//   Guard inner two passes to handle single-row/column remainders.
// TC: O(m*n)  |  SC: O(1) [output array aside]
// ============================================================

function spiralOrder(array $matrix): array {
    $upRow    = 0;
    $botRow   = count($matrix) - 1;
    $leftCol  = 0;
    $rightCol = count($matrix[0]) - 1;
    $ans      = [];

    while ($upRow <= $botRow && $leftCol <= $rightCol) {
        // -> Traverse top row left to right
        for ($j = $leftCol; $j <= $rightCol; $j++)
            $ans[] = $matrix[$upRow][$j];
        $upRow++;

        // down Traverse right column top to bottom
        for ($i = $upRow; $i <= $botRow; $i++)
            $ans[] = $matrix[$i][$rightCol];
        $rightCol--;

        // <- Traverse bottom row right to left (guard: rows remain)
        if ($upRow <= $botRow) {
            for ($j = $rightCol; $j >= $leftCol; $j--)
                $ans[] = $matrix[$botRow][$j];
            $botRow--;
        }

        // up Traverse left column bottom to top (guard: cols remain)
        if ($leftCol <= $rightCol) {
            for ($i = $botRow; $i >= $upRow; $i--)
                $ans[] = $matrix[$i][$leftCol];
            $leftCol++;
        }
    }

    return $ans;
}

// Dry Run  ->  [[1,2,3,4],[5,6,7,8],[9,10,11,12]]
// -> top row:   1,2,3,4
// down right col: 8,12
// <- bot row:   11,10,9
// up left col:  5
// -> next top:  6,7
// Output: [1,2,3,4,8,12,11,10,9,5,6,7]

$matrix = [[1,2,3,4],[5,6,7,8],[9,10,11,12]];
echo "Spiral: " . implode(", ", spiralOrder($matrix)) . "\n";


// ============================================================
// 24. LC 560 -- SUBARRAY SUM EQUALS K  (count of subarrays)
// ============================================================
// Intuition: Same prefix-sum trick as problem 12.
//   hash[0]=1 accounts for subarrays starting from index 0.
//   For each prefix sum, add hash[sum-k] to answer count.
// TC: O(n)  |  SC: O(n)
// ============================================================

function subarraySum(array $nums, int $k): int {
    $hash       = [0 => 1];  // Prefix sum -> frequency; base case
    $sum        = 0;
    $ansCounter = 0;

    foreach ($nums as $num) {
        $sum += $num;

        // If (sum-k) appeared before, those subarrays sum to k
        $ansCounter += $hash[$sum - $k] ?? 0;

        // Record current prefix sum
        $hash[$sum] = ($hash[$sum] ?? 0) + 1;
    }

    return $ansCounter;
}

// Dry Run  ->  $nums = [1,1,1], k=2
// init: hash={0:1}, sum=0
// i=0: sum=1, rem=-1 -> 0, hash={0:1,1:1}
// i=1: sum=2, rem=0  -> hash[0]=1 -> ans=1, hash={0:1,1:1,2:1}
// i=2: sum=3, rem=1  -> hash[1]=1 -> ans=2
// Output: 2  ([1,1] starting at index 0 and index 1)

echo "Subarray sum count: " . subarraySum([1, 1, 1], 2) . "\n";  // 2


// ============================================================
// 25. LC 118 -- PASCAL'S TRIANGLE
// ============================================================
// Intuition: Each element = C(row, col) = C(row, col-1) * (row-col) / col.
//   Compute row values using the multiplicative formula to avoid
//   recomputing factorials.  Each row starts and ends with 1.
// TC: O(n^2)  |  SC: O(n^2)
// ============================================================

function generate(int $numRows): array {
    $ans = [];

    for ($i = 1; $i <= $numRows; $i++) {
        $row     = [];
        $val     = 1;
        $row[0]  = $val;

        for ($j = 1; $j < $i; $j++) {
            $val     = (int) ($val * ($i - $j) / $j);  // C(i-1, j) formula
            $row[$j] = $val;
        }

        $ans[] = $row;
    }

    return $ans;
}

// Dry Run  ->  numRows=4
// Row 1: [1]
// Row 2: [1,1]
// Row 3: [1,2,1]   -> val=1*(3-1)/1=2
// Row 4: [1,3,3,1] -> val=1*(4-1)/1=3, val=3*(4-2)/2=3
// Output: [[1],[1,1],[1,2,1],[1,3,3,1]]

echo "Pascal's Triangle:\n";
foreach (generate(5) as $row) echo implode(" ", $row) . "\n";


// ============================================================
// 26. LC 229 -- MAJORITY ELEMENT II (> n/3 times)
// ============================================================
// Intuition: At most 2 elements can appear > n/3 times.
//   Extended Boyer-Moore with 2 candidates.
//   Phase 1: Identify candidates.
//   Phase 2: Verify they actually appear > n/3 times.
// TC: O(n)  |  SC: O(1)
// ============================================================

function majorityElementII(array $nums): array {
    $el1 = $el2 = null;
    $c1  = $c2  = 0;
    $n   = count($nums);

    // Phase 1: Find up to two candidates
    foreach ($nums as $num) {
        if ($c1 === 0 && $num !== $el2) {
            $el1 = $num; $c1 = 1;
        } elseif ($c2 === 0 && $num !== $el1) {
            $el2 = $num; $c2 = 1;
        } elseif ($num === $el1) {
            $c1++;
        } elseif ($num === $el2) {
            $c2++;
        } else {
            $c1--; $c2--;  // Cancel out with both
        }
    }

    // Phase 2: Count actual occurrences
    $count1 = $count2 = 0;
    foreach ($nums as $num) {
        if ($num === $el1) $count1++;
        if ($num === $el2) $count2++;
    }

    $ans = [];
    if ($count1 > (int)($n / 3)) $ans[] = $el1;
    if ($count2 > (int)($n / 3)) $ans[] = $el2;

    return $ans;
}

// Dry Run  ->  $nums = [1,1,1,3,3,2,2,2]
// el1=1(c1=3), el2=3 cancelled, el2=2(c2=2)
// Verify: count(1)=3 > 8/3=2.6 ok, count(2)=3 > 2.6 ok
// Output: [1,2]

print_r(majorityElementII([1, 1, 1, 3, 3, 2, 2, 2]));  // [1, 2]


// ============================================================
// 27. LC 15 -- 3SUM (THREE SUM TO ZERO)
// ============================================================
// Intuition: Sort the array, fix the first element with outer loop,
//   then use two-pointer on the remaining subarray.
//   Skip duplicates to avoid repeated triplets.
// TC: O(n^2)  |  SC: O(1) [output aside]
// ============================================================

function threeSum(array $nums): array {
    sort($nums);
    $ans = [];
    $n   = count($nums);

    for ($i = 0; $i < $n - 2; $i++) {
        // Skip duplicate for outer pointer
        if ($i > 0 && $nums[$i] === $nums[$i - 1]) continue;

        $j = $i + 1;
        $k = $n - 1;

        while ($j < $k) {
            $sum = $nums[$i] + $nums[$j] + $nums[$k];

            if ($sum > 0) {
                $k--;
            } elseif ($sum < 0) {
                $j++;
            } else {
                $ans[] = [$nums[$i], $nums[$j], $nums[$k]];
                $j++;
                $k--;

                // Skip duplicates for inner pointers
                while ($j < $k && $nums[$j] === $nums[$j - 1]) $j++;
                while ($j < $k && $nums[$k] === $nums[$k + 1]) $k--;
            }
        }
    }

    return $ans;
}

// Dry Run  ->  $nums = [-1,0,1,2,-1,-4]  ->  sorted: [-4,-1,-1,0,1,2]
// i=0(-4): j=1,k=5  sum=-4-1+2=-3<0 j++; eventually no match
// i=1(-1): j=2,k=5  sum=-1-1+2=0 ok add [-1,-1,2]; j++,k--; j=3,k=4
//          sum=-1+0+1=0 ok add [-1,0,1]; j++,k--; j=4>=k=3 stop
// i=2(-1): duplicate of i=1 -> skip
// i=3(0):  j=4,k=5  sum=0+1+2=3>0 k--; j>=k stop
// Output: [[-1,-1,2],[-1,0,1]]  ok

print_r(threeSum([-1, 0, 1, 2, -1, -4]));


// ============================================================
// ============================================================
//  REVISION SECTION
// ============================================================
// ============================================================

// ============================================================
// A. ADDITIONAL PRACTICE PROBLEMS
// ============================================================
//
//  Easy / Warm-up
//  -------------
//  LC  1  -- Two Sum
//  LC 26  -- Remove Duplicates from Sorted Array
//  LC 283 -- Move Zeroes
//  LC 485 -- Max Consecutive Ones
//  LC 268 -- Missing Number
//  LC 136 -- Single Number
//
//  Medium (Core Patterns)
//  ----------------------
//  LC  75 -- Sort Colors (Dutch National Flag)
//  LC 169 -- Majority Element (Boyer-Moore)
//  LC  53 -- Maximum Subarray (Kadane)
//  LC 121 -- Best Time to Buy and Sell Stock
//  LC 560 -- Subarray Sum Equals K
//  LC  31 -- Next Permutation
//  LC 128 -- Longest Consecutive Sequence
//  LC 189 -- Rotate Array
//  LC  48 -- Rotate Image
//  LC  73 -- Set Matrix Zeroes
//  LC  54 -- Spiral Matrix
//  LC 118 -- Pascal's Triangle
//  LC  15 -- 3Sum
//  LC 229 -- Majority Element II
//  LC 2149-- Rearrange Array Elements by Sign
//  LC 1752-- Check if Array Is Sorted and Rotated
//
//  Hard (Extensions)
//  -----------------
//  LC  42 -- Trapping Rain Water
//  LC  84 -- Largest Rectangle in Histogram
//  LC  18 -- 4Sum
//  LC  16 -- 3Sum Closest
//  LC  11 -- Container With Most Water
//  LC 152 -- Maximum Product Subarray
//  LC 239 -- Sliding Window Maximum


// ============================================================
// B. KEY PATTERNS AND VARIATIONS
// ============================================================
//
//  1. TWO POINTER
//     - Works on SORTED arrays for pair/triplet sum problems
//     - Left and right converge: O(n) instead of O(n^2)
//     - Problems: 2Sum (sorted), 3Sum, Move Zeroes, Remove Duplicates
//
//  2. SLIDING WINDOW
//     - Fixed window: track sum/count in a window of size k
//     - Variable window: expand right, shrink left on violation
//     - Only valid for non-negative numbers when tracking sum
//     - Problems: Max Consecutive Ones, Longest Subarray Sum K (positive)
//
//  3. PREFIX SUM + HASHMAP
//     - Store prefix sum index/frequency; answer uses sum - k
//     - Works for +ve and -ve numbers
//     - Problems: Subarray Sum Equals K, Longest Subarray Sum K
//
//  4. KADANE'S ALGORITHM
//     - Reset running sum to 0 when it goes negative
//     - O(n) maximum subarray; track indices for the actual subarray
//     - Extension: Maximum Product Subarray (track both max and min)
//
//  5. DUTCH NATIONAL FLAG (3-WAY PARTITION)
//     - low/mid/high pointers; DO NOT advance mid after swapping with high
//     - Used in Sort Colors, Quick Sort partition
//
//  6. BOYER-MOORE VOTING
//     - Majority > n/2: one candidate
//     - Majority > n/3: two candidates
//     - Always verify candidate count after the voting phase
//
//  7. REVERSAL TRICK
//     - Rotate array by k: reverse last k, reverse first n-k, reverse all
//     - Also used in Next Permutation (reverse suffix)
//
//  8. MATRIX TRICKS
//     - Rotate 90 degrees: Transpose + Reverse rows
//     - Set Zeroes: Use row0/col0 as markers; handle col0 separately
//     - Spiral: Four boundary pointers, shrink after each side pass
//
//  9. HASHING FOR O(1) LOOKUP
//     - Consecutive Sequence: only start from sequence head (no num-1)
//     - Union of sorted arrays: merge + key-dedup


// ============================================================
// C. IMPORTANT TIPS AND EDGE CASES
// ============================================================
//
//  1. ROTATION / MODULO
//     - Always reduce k: k = k % n  (k might be > n)
//     - k=0 or k=n -> no rotation needed
//
//  2. DUPLICATES IN 3SUM / NEXT PERMUTATION
//     - Skip duplicate outer element AFTER first iteration (i > 0 check)
//     - Skip duplicate inner pointers AFTER adding a valid result
//
//  3. SECOND LARGEST / MIN
//     - Must check num != max/min to handle duplicate maximums
//     - All-same array -> second max remains PHP_INT_MIN
//
//  4. MAJORITY ELEMENT
//     - Problem says majority guaranteed -> no verify step needed
//     - When NOT guaranteed -> always verify with a second pass
//
//  5. PREFIX SUM HASH
//     - Initialise hash[0] = 1 before the loop (handles subarrays from index 0)
//     - Store FIRST occurrence of each prefix sum for maximum length problems
//     - Store FREQUENCY of each prefix sum for count problems
//
//  6. KADANE'S EDGE CASES
//     - All-negative array: initialise max = PHP_INT_MIN (not 0!)
//     - Single-element array: handled naturally
//
//  7. SET MATRIX ZEROES
//     - Process first row and first column LAST (they are the markers)
//     - Use a separate col0 flag to avoid contaminating row markers
//
//  8. SPIRAL MATRIX
//     - Guard the bottom-row and left-column passes to avoid double-counting
//       when only one row or column remains
//
//  9. PASCAL'S TRIANGLE
//     - Use multiplicative formula C(n,k) = C(n,k-1) * (n-k+1) / k
//       to avoid large intermediate factorials
//
//  10. MISSING NUMBER
//      - Gauss sum: n*(n+1)/2 -- simple, O(1) space
//      - XOR alternative: XOR all indices 0..n with all array values
//
?>
