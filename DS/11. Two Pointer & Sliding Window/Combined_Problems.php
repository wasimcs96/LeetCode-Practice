<?php
//Length of Longest Substring without any Repeating Character

    function lengthOfLongestSubstring($s) {
            $length = strlen($s);
            if($length == 0 || $length == 1){
                return $length;
            }
            $left = $right = 0; $maxLength = 0; $chrHashMap = [];

            while($right < $length){
                $chr = $s[$right];
                if(isset($chrHashMap[$chr])) {
                    $chrHashMap[$chr]++;
                    $maxLength = max($maxLength, count($chrHashMap));
                    while($left < $right){
                        $leftChr = $s[$left];
                        $left++;
                        
                        $chrHashMap[$leftChr]--;
                        if($chrHashMap[$leftChr] == 0) unset($chrHashMap[$leftChr]);

                        if($leftChr == $s[$right]){
                            break;
                        }
                        
                    }
                }
                else $chrHashMap[$chr] = 1;
                $right++;
            }

            $maxLength = max($maxLength, count($chrHashMap));
            return $maxLength; 
        }

//Striver  Approch
function lengthOfLongestSubstring($s)
    {
        $length = strlen($s);

        // Edge cases
        if ($length <= 1) {
            return $length;
        }

        // Stores:
        // ASCII Value -> Last Index
        $hash = array_fill(0, 256, -1);

        // Left pointer of current window
        $left = 0;

        // Maximum window length found
        $maxLength = 0;

        // Expand the window
        for ($right = 0; $right < $length; $right++) {

            // Current character
            $ascii = ord($s[$right]);

            // Duplicate found?
            if ($hash[$ascii] != -1) {

                /*
                 * Move left pointer
                 *
                 * Previous occurrence + 1
                 *
                 * But never move left backwards.
                 */
                $left = max($hash[$ascii] + 1, $left);
            }

            // Current window length
            $currentLength = $right - $left + 1;

            // Update answer
            $maxLength = max($maxLength, $currentLength);

            // Store latest index
            $hash[$ascii] = $right;
        }

        return $maxLength;
    }

//1004. Max Consecutive Ones III

function longestOnes($nums, $k) {
        $length = count($nums);
        if($length == 0) return 0;

        $left = $right = 0; $maxWindowLength = 0; $bineryHashArray = [0=>0,1=>0];

        while($right < $length){
            $bineryHashArray[$nums[$right]]++;
            $counterOfZero = $bineryHashArray[0];
            while($counterOfZero > $k){
                if($nums[$left] == 0) {
                    $counterOfZero--;
                }
                $left++;
            }
            $bineryHashArray[0] = $counterOfZero;

            $windowLength = $right - $left + 1;
            $maxWindowLength = max($maxWindowLength, $windowLength);
            $right++;
        }

        return $maxWindowLength;
    }

    //Striver Optimal Solution
    function longestOnesV2($nums, $k)
    {
        // Total number of elements
        $length = count($nums);

        // Edge case: Empty array
        if ($length == 0) {
            return 0;
        }

        // Sliding Window pointers
        $left = 0;
        $right = 0;

        // Stores maximum valid window length found so far
        $maxWindowLength = 0;

        /*
        * Frequency array
        *
        * Index 0 -> Count of zeros inside current window
        * Index 1 -> Count of ones inside current window
        */
        $binaryHashArray = [
            0 => 0,
            1 => 0
        ];
        //Instead of you can use only ZeroCounter

        // Expand the window using right pointer
        while ($right < $length) {

            // Include current element into window
            $binaryHashArray[$nums[$right]]++; //        //Instead of you can use only ZeroCounter


            /*
            * If number of zeros becomes greater than k,
            * our window becomes invalid.
            *
            * Shrink the window from the left until
            * it becomes valid again.
            */
            if ($binaryHashArray[0] > $k) {

                // Remove left element from window
                if ($nums[$left] == 0) {
                    $binaryHashArray[0]--;          //Instead of you can use only ZeroCounter

                }

                // Move left pointer
                $left++;
            }

            // Current valid window length
            $windowLength = $right - $left + 1;

            // Store maximum window length
            $maxWindowLength = max($maxWindowLength, $windowLength);

            // Expand window
            $right++;
        }

        return $maxWindowLength;
    }

//    904. Fruit Into Baskets
function totalFruitV1($fruits) {
        $length = count($fruits);

        if($length == 0) return 0;

        $maxCollection = 0; $fruitHashMap = [];
        $left = 0;
        for($right=0;$right<$length;$right++){
            if(isset($fruitHashMap[$fruits[$right]])){
                $fruitHashMap[$fruits[$right]]++;
            }else{
                $fruitHashMap[$fruits[$right]] = 1;
            }

            while($left <= $right && count($fruitHashMap) > 2){
                
                $fruitHashMap[$fruits[$left]]--;

                if($fruitHashMap[$fruits[$left]] == 0) {
                    unset($fruitHashMap[$fruits[$left]]);
                }
                
                $left++;
            }
            
            $maxCollection = max($maxCollection, $right - $left + 1);
        }
        return $maxCollection;
    }

    //Optimal Soution
    function totalFruit($fruits) {
        $length = count($fruits);

        if($length == 0) return 0;

        $maxCollection = 0; $fruitHashMap = [];
        $left = 0;
        for($right=0;$right<$length;$right++){

            $fruitHashMap[$fruits[$right]] = $right;
            $minValue = PHP_INT_MAX;

            if(count($fruitHashMap) > 2){
                $unsetKey = -1;

                foreach($fruitHashMap as $key => $value){
                    if($minValue > $value){
                        $minValue = $value;
                        $left = $value;
                        $unsetKey = $key;
                    }
                }

                unset($fruitHashMap[$unsetKey]);
                $left++;
            }
            
            $maxCollection = max($maxCollection, $right - $left + 1);
        }
        return $maxCollection;
    }
//    424. Longest Repeating Character Replacement

function characterReplacement($s, $k) {
        $length = strlen($s);
        if($length == 0 || $length == 1) return $length;

        $left = $right = 0; $maxLength = 0;  $chrHashCounter = []; $maxFreq = 0;

        for($right; $right < $length; $right++){
            if(isset($chrHashCounter[$s[$right]])) $chrHashCounter[$s[$right]]++;
            else $chrHashCounter[$s[$right]] = 1;

            $maxFreq = max($chrHashCounter[$s[$right]], $maxFreq);

            while(($right - $left + 1) - $maxFreq > $k){
                $leftChr = $s[$left];
                $chrHashCounter[$leftChr]--;
                if($chrHashCounter[$leftChr] == 0) unset($chrHashCounter[$leftChr]);
                $left++;
            }

            $windowLength = $right - $left + 1;
            $maxLength = max($maxLength, $windowLength);
        }
        return $maxLength;
    }

    function characterReplacement(string $s, int $k): int
    {
        $chars = [];
        $maxRepeat = 0;
        $res = 0;

        for ($l = 0, $r = 0; $r < strlen($s); $r++) {
            $chars[$s[$r]]++;
            $maxRepeat = max($maxRepeat, $chars[$s[$r]]);
            if ($r - $l + 1 - $maxRepeat > $k) {
                $chars[$s[$l]]--;
                $l++;
            }
            $res = max($res, $r - $l + 1);
        }

        return $res;
    }


    /**
     * LeetCode 930 - Binary Subarrays With Sum
     *
     * Approach:
     * Prefix Sum + HashMap
     *
     * Time Complexity  : O(n)
     * Space Complexity : O(n)
     */
    function numSubarraysWithSum($nums, $goal)
    {
        /*
         * HashMap
         *
         * Stores:
         * Prefix Sum => Frequency
         *
         * Example:
         * 0 => 1
         * 1 => 2
         * 2 => 1
         */
        $prefixSumCount = [];

        /*
         * Base Case
         *
         * A prefix sum of 0 exists once before
         * we start traversing the array.
         */
        $prefixSumCount[0] = 1;

        // Running prefix sum
        $sum = 0;

        // Total number of valid subarrays
        $count = 0;

        // Traverse the array
        foreach ($nums as $num) {

            // Update running prefix sum
            $sum += $num;

            /*
             * We need:
             *
             * Current Prefix Sum - Previous Prefix Sum = Goal
             *
             * Therefore,
             *
             * Previous Prefix Sum = Current Prefix Sum - Goal
             *
             * If this prefix sum has appeared before,
             * then every occurrence forms one valid subarray.
             */
            $requiredPrefixSum = $sum - $goal;

            if (isset($prefixSumCount[$requiredPrefixSum])) {
                $count += $prefixSumCount[$requiredPrefixSum];
            }

            /*
             * Store current prefix sum
             *
             * Increase its frequency because
             * the same prefix sum may occur again.
             */
            if (isset($prefixSumCount[$sum])) {
                $prefixSumCount[$sum]++;
            } else {
                $prefixSumCount[$sum] = 1;
            }
        }

        return $count;
    }


$nums = [1, 0, 1, 0, 1];
$goal = 2;

echo numSubarraysWithSum($nums, $goal);


// Function to calculate number of subarrays with sum exactly equal to goal
    public function numSubarraysWithSum($nums, $goal)
    {
        // Return difference between atMost(goal) and atMost(goal - 1)
        return $this->atMost($nums, $goal) - $this->atMost($nums, $goal - 1);
    }

    // Helper function to count number of subarrays with sum at most k
    private function atMost($nums, $k)
    {
        // No valid subarrays if k is negative
        if ($k < 0) {
            return 0;
        }

        $left = 0;
        $sum = 0;
        $count = 0;

        // Traverse array using right pointer
        for ($right = 0; $right < count($nums); $right++) {

            // Add current element into window sum
            $sum += $nums[$right];

            // Shrink window until sum becomes <= k
            while ($sum > $k) {
                $sum -= $nums[$left];
                $left++;
            }

            // All subarrays ending at 'right' and starting
            // from left to right are valid.
            $count += ($right - $left + 1);
        }

        return $count;
    }


//1248. Count Number of Nice Subarrays
    function numberOfSubarrays($nums, $k) {

    // Total number of elements
    $arrLength = count($nums);

    // Edge case
    if ($arrLength == 0) return 0;

    // Sliding window pointers
    $left = 0;

    // Stores total valid subarrays having at most k odd numbers
    $subArrCounter = 0;

    // Current number of odd elements inside window
    $oddNumberCounter = 0;

    // Expand the window
    for ($right = 0; $right < $arrLength; $right++) {

        // If current element is odd, increase odd counter
        if ($nums[$right] % 2 != 0) {
            $oddNumberCounter++;
        }

        // Window has more than k odd numbers
        // Shrink it from the left
        while ($oddNumberCounter > $k) {

            // Remove left element contribution
            if ($nums[$left] % 2 != 0) {
                $oddNumberCounter--;
            }

            $left++;
        }

        /*
         Window [left...right] now contains
         at most k odd numbers.

         Every subarray ending at "right"
         and starting anywhere from
         left to right is valid.

         Number of such subarrays

             = right - left + 1
        */
        $subArrCounter += ($right - $left + 1);
    }

    return $subArrCounter;
}

// Exactly K odd numbers
function niceSubarrays($nums, $k)
{
    return numberOfSubarrays($nums, $k)
         - numberOfSubarrays($nums, $k - 1);
}

$nums = [2,2,2,1,2,2,1,2,2,2];
$k = 2;

echo niceSubarrays($nums, $k);


//Longest Substring with At Most K Distinct Characters

function lengthOfLongestSubstringKDistinct($s, $k){
    $strlength = strlen($s);
    if($strlength == 0) return 0;

    $maxLength = 0; $left = 0; $distintNumCount = [];

    for($right = 0; $right < $strlength; $right++){
        $distintNumCount[$s[$right]] = isset($distintNumCount[$s[$right]]) ? $distintNumCount[$s[$right]]+1 : 1;

        while(count($distintNumCount) > $k){
            $leftChr = $s[$left];
            $distintNumCount[$leftChr]--;

            if($distintNumCount[$leftChr] == 0) unset($distintNumCount[$leftChr]);

            $left++;
        }

        $windowLength = $right - $left + 1;
        $maxLength = max($maxLength, $windowLength);
    }

    return $maxLength ;
}

$s = "abcddefg";
$k = 3;

echo lengthOfLongestSubstringKDistinct($s, $k);


/**
    * Optimized solution for strings containing only:
    *
    *      a, b and c
    *
    * Instead of storing frequencies,
    * store the latest index of each character.
    *
    * Whenever all three characters have been seen,
    * the earliest last occurrence determines how many
    * valid substrings end at the current index.
    *
    * Time Complexity  : O(n)
    * Space Complexity : O(1)
    */

    function numberOfSubstrings($s)
    {
        $k = 3;
        $strLength = strlen($s);

        if ($strLength == 0) {
            return 0;
        }

        // Stores the latest index of each character
        $lastSeen = [];

        $subArrCounter = 0;

        for ($right = 0; $right < $strLength; $right++) {

            // Update latest occurrence
            $lastSeen[$s[$right]] = $right;

            // Once all K distinct characters are present
            if (count($lastSeen) == $k) {

                /**
                * Earliest occurrence among
                * a,b,c determines how many
                * substrings end at current position.
                */
                $subArrCounter += min(
                    $lastSeen['a'],
                    $lastSeen['b'],
                    $lastSeen['c']
                ) + 1;
            }
        }

        return $subArrCounter;
    }

    /**
    * Count substrings containing exactly/at least K distinct characters
    * (This implementation works for problems like LeetCode 1358 when k = 3).
    *
    * Time Complexity  : O(n)
    * Space Complexity : O(k)
    *
    * Idea:
    * -----
    * Expand the window using the right pointer.
    *
    * As soon as the window contains exactly K distinct characters,
    * every substring starting from 'left' and ending at
    * 'right', 'right+1', ..., 'n-1' will also satisfy the condition.
    *
    * Therefore, instead of checking each substring individually,
    * we add:
    *
    *      n - right
    *
    * Then shrink the window from the left to search for more valid windows.
    */
 
    function numberOfSubstringsV1($s)
    {
        $k = 3;
        $strLength = strlen($s);

        // Edge case
        if ($strLength == 0) {
            return 0;
        }

        // Left pointer of sliding window
        $left = 0;

        // Character frequency inside current window
        $distinctCharCount = [];

        // Final answer
        $subArrCounter = 0;

        // Expand window
        for ($right = 0; $right < $strLength; $right++) {

            // Include current character
            $distinctCharCount[$s[$right]] =
                isset($distinctCharCount[$s[$right]])
                    ? $distinctCharCount[$s[$right]] + 1
                    : 1;

            /**
            * If window contains exactly K distinct characters,
            * then every extension of this window is also valid.
            */
            while (count($distinctCharCount) == $k) {

                /**
                * Count all valid substrings.
                *
                * Example:
                *
                * String : abcabc
                * Right  : 2 ('c')
                *
                * Valid endings:
                *
                * abc
                * abca
                * abcab
                * abcabc
                *
                * Total = n - right
                */
                $subArrCounter += $strLength - $right;

                // Remove leftmost character
                $leftChar = $s[$left];
                $distinctCharCount[$leftChar]--;

                // Remove key if frequency becomes zero
                if ($distinctCharCount[$leftChar] == 0) {
                    unset($distinctCharCount[$leftChar]);
                }

                // Shrink window
                $left++;
            }
        }

        return $subArrCounter;
    }
?>