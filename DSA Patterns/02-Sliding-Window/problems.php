<?php

/*
|--------------------------------------------------------------------------
| Problem: Max Consecutive Ones (LC 485)
| Source: DS/3. Array/Array_enhancement.php
| Pattern: Sliding Window
|--------------------------------------------------------------------------
*/

/*
Problem Description:
 What: find the length of the longest run of consecutive 1s in a binary array.
 Why it exists: the simplest possible "reset-on-violation" counter
   pattern -- the mental stepping stone to LC1004 (Max Consecutive Ones
   III, which allows flipping up to K zeroes -- a true sliding window).

Example:
Input: nums = [1,1,0,1,1,1]
Output: 3

Why This Pattern:
A running counter that resets on a zero and is compared against the best-seen value on every step is an implicit, counter-based sliding window over each run of consecutive 1s.
*/

function findMaxConsecutiveOnes(array $nums): int {
    $maxRun = 0;
    $currentRun = 0;

    foreach ($nums as $num) {
        if ($num === 1) {
            $currentRun++;              // Extend the current run
        } else {
            $currentRun = 0;            // A zero breaks the run entirely -- hard reset
        }
        $maxRun = max($maxRun, $currentRun);   // Always compare, even mid-run (not just on reset)
    }

    return $maxRun;
}


/*
|--------------------------------------------------------------------------
| Problem: Longest Subarray With Sum Equals K
| Source: DS/3. Array/Array_enhancement.php
| Pattern: Sliding Window
|--------------------------------------------------------------------------
*/

/*
Problem Description:
 What: find the length of the longest contiguous subarray whose elements
       sum to exactly k.
 Why it exists: prefix sums convert "subarray sum" questions into "index
   difference" questions, turning an O(n^2) all-pairs check into O(n).

Example:
Input: nums = [10,5,2,7,1,9], k = 15
Output: 4 (both the Prefix-Sum+HashMap version and the all-positive Sliding-Window version return 4)

Why This Pattern:
When all numbers are guaranteed positive, the prefix sum is monotonic, so a growing/shrinking window (expand right, shrink left while the running sum exceeds k) finds the same answer without a hashmap.
*/

function longestSubarraySumK_Brute(array $nums, int $k): int {
    $n = count($nums);
    $maxLen = 0;
    for ($start = 0; $start < $n; $start++) {
        $sum = 0;
        for ($end = $start; $end < $n; $end++) {
            $sum += $nums[$end];              // Extend running sum instead of re-summing (still O(n^2) overall)
            if ($sum === $k) {
                $maxLen = max($maxLen, $end - $start + 1);
            }
        }
    }
    return $maxLen;
}

function longestSubarraySumK(array $nums, int $k): int {
    $firstIndexOfSum = [];   // prefix sum value -> first index it was seen at
    $sum = 0;
    $maxLen = 0;

    for ($i = 0; $i < count($nums); $i++) {
        $sum += $nums[$i];

        if ($sum === $k) {
            $maxLen = max($maxLen, $i + 1);   // Whole prefix up to i sums to k
        }

        $remainder = $sum - $k;    // We need a PAST prefix sum equal to this
        if (isset($firstIndexOfSum[$remainder])) {
            $maxLen = max($maxLen, $i - $firstIndexOfSum[$remainder]);
        }

        // Store only the FIRST occurrence -- an earlier index maximizes
        // the length of any future subarray that needs this prefix sum.
        if (!isset($firstIndexOfSum[$sum])) {
            $firstIndexOfSum[$sum] = $i;
        }
    }

    return $maxLen;
}

function longestSubarrayPositive(array $nums, int $k): int {
    $left = 0;
    $sum = 0;
    $maxLen = 0;

    for ($right = 0; $right < count($nums); $right++) {
        $sum += $nums[$right];

        while ($sum > $k && $left <= $right) {   // Shrink -- only valid because nums are non-negative
            $sum -= $nums[$left];
            $left++;
        }

        if ($sum === $k) {
            $maxLen = max($maxLen, $right - $left + 1);
        }
    }

    return $maxLen;
}


/*
|--------------------------------------------------------------------------
| Problem: Count Substrings with Exactly K Distinct Characters
| Source: DS/5. String/String_enhancement.php
| Pattern: Sliding Window
|--------------------------------------------------------------------------
*/

/*
Problem Description:
 What: count the number of substrings of s that contain EXACTLY k
       distinct characters.
 Why it exists: the atMost(K)-minus-atMost(K-1) decomposition is one of
   the most reusable tricks across sliding-window counting problems
   (also used for "exactly K odd numbers," "binary subarrays with sum," etc.).


 --- 🎯 Interview-Ready Add-Ons (constraints, timing, pitch) ---
 Asked at      : Amazon, Google -- a strong signal question for whether you know the atMost(K)-minus-atMost(K-1) decomposition, which appears repeatedly across sliding-window counting problems.
 Constraints   : 1 <= s.length <= 10^4, 1 <= k <= 26 -> O(n) expected via two calls to a monotonic sliding-window helper, not a single 'track exactly K directly' window (which is structurally awkward).
 Time-boxing   : Total ~10 min: 2 min restate + explain why 'exactly K' resists a direct window, 3 min build atMostKDistinct as a clean sliding window, 3 min compose the subtraction, 2 min dry run.
 60-Sec Pitch  : "I count substrings with AT MOST k distinct characters using a standard expand/shrink sliding window, then subtract the AT MOST (k-1) count from it -- the difference isolates exactly the substrings with EXACTLY k distinct characters."

Example:
Input: s="pqpqs", k=2
Output: 7

Why This Pattern:
The atMost(K) - atMost(K-1) decomposition reduces 'exactly K distinct characters' to two calls of a standard expand/shrink sliding window - a widely reusable sliding-window counting trick.
*/

function atMostKDistinct(string $s, int $k): int
{
    if ($k < 0) return 0;   // No valid substring can have a negative distinct-character budget

    $left = 0;
    $result = 0;
    $freqMap = [];
    $n = strlen($s);

    for ($right = 0; $right < $n; $right++) {
        $char = $s[$right];
        $freqMap[$char] = ($freqMap[$char] ?? 0) + 1;   // Expand: include s[right] in the window

        while (count($freqMap) > $k) {                    // Too many distinct characters -- shrink from the left
            $leftChar = $s[$left];
            $freqMap[$leftChar]--;
            if ($freqMap[$leftChar] === 0) {
                unset($freqMap[$leftChar]);                // Character fully removed from the window
            }
            $left++;
        }

        $result += ($right - $left + 1);   // Every substring ending at `right`, starting in [left..right], is valid
    }

    return $result;
}

function countSubstringsExactlyKDistinct(string $s, int $k): int
{
    return atMostKDistinct($s, $k) - atMostKDistinct($s, $k - 1);
}


/*
|--------------------------------------------------------------------------
| Problem: Longest Substring Without Repeating Characters (LC 3)
| Source: DS/11.Two Pointer & Sliding Window/Combined_Problems.php
| Pattern: Sliding Window
|--------------------------------------------------------------------------
*/

/*
Problem Description:
Length of Longest Substring without any Repeating Character

Example:
Input: s = "abcabcbb"
Output: 3 (the answer is "abc")

Why This Pattern:
The window's right edge always expands, and the left edge only ever jumps forward to just past a repeated character's last occurrence - the flagship variable-size Sliding Window problem. Two solutions are included: a frequency-hashmap shrink-loop version and the more optimal 'Striver Approach' last-seen-index version.

Note: renamed from the source's `lengthOfLongestSubstring()` to `lengthOfLongestSubstringStriver()` solely to avoid a duplicate-function fatal error, since the source file declares `lengthOfLongestSubstring()` twice (a frequency-hashmap version and this 'Striver Approach' last-seen-index version) for the same problem. No logic changed.
*/

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

function lengthOfLongestSubstringStriver($s)
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


/*
|--------------------------------------------------------------------------
| Problem: Max Consecutive Ones III (LC 1004)
| Source: DS/11.Two Pointer & Sliding Window/Combined_Problems.php
| Pattern: Sliding Window
|--------------------------------------------------------------------------
*/

/*
Problem Description:
1004. Max Consecutive Ones III

Example:
Input: nums = [1,1,1,0,0,0,1,1,1,1,0], k = 2
Output: 6

Why This Pattern:
The window expands with every element and only shrinks from the left when it holds more than k zeros - a textbook variable-size Sliding Window. Two solutions are included: an inner-while-loop shrink version and the 'Striver Optimal Solution' which shrinks by at most one element per step (no inner loop needed) since the window only ever grows invalid by one zero at a time.
*/

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


/*
|--------------------------------------------------------------------------
| Problem: Fruit Into Baskets (LC 904)
| Source: DS/11.Two Pointer & Sliding Window/Combined_Problems.php
| Pattern: Sliding Window
|--------------------------------------------------------------------------
*/

/*
Problem Description:
   904. Fruit Into Baskets

Example:
Input: fruits = [1,2,1]
Output: 3

Input: fruits = [0,1,2,2]
Output: 3

Why This Pattern:
A window that must contain at most 2 distinct fruit types - expand right always, shrink left whenever a third type appears - the same 'at most K distinct' Sliding Window family as Longest Substring with At Most K Distinct Characters. Two solutions are included: a frequency-hashmap shrink-loop version and an 'Optimal Solution' that jumps left directly via a last-seen-index map instead of shrinking one element at a time.
*/

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


/*
|--------------------------------------------------------------------------
| Problem: Longest Repeating Character Replacement (LC 424)
| Source: DS/11.Two Pointer & Sliding Window/Combined_Problems.php
| Pattern: Sliding Window
|--------------------------------------------------------------------------
*/

/*
Problem Description:
   424. Longest Repeating Character Replacement

Example:
Input: s = "ABAB", k = 2
Output: 4

Why This Pattern:
The window stays valid as long as (window length - count of its most frequent character) <= k; expand right always, shrink left by one whenever that invariant breaks - the same maxFreq-tracking Sliding Window technique used for Longest Substring Without Repeating Characters, generalized to allow k replacements. The source declares this function twice with near-identical logic (an untyped version and a typed version) - only the typed version is copied here as the clean single definition, since duplicating both would be a redundant fatal-error-causing redeclaration for no added value.
*/

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


/*
|--------------------------------------------------------------------------
| Problem: Binary Subarrays With Sum — atMost(k) Sliding Window (LC 930)
| Source: DS/11.Two Pointer & Sliding Window/Combined_Problems.php
| Pattern: Sliding Window
|--------------------------------------------------------------------------
*/

/*
Problem Description:
Function to calculate number of subarrays with sum exactly equal to goal

Helper function to count number of subarrays with sum at most k

Example:
Input: nums = [1,0,1,0,1], goal = 2
Output: 4

Why This Pattern:
'Exactly goal' is decomposed into atMost(goal) - atMost(goal-1), where atMost(k) is a standard expand/shrink Sliding Window counting subarrays with sum <= k - the same atMost-difference trick used for Count Number of Nice Subarrays and Count Substrings with Exactly K Distinct Characters elsewhere in this codebase. A second, genuinely different technique for the same LC 930 problem as the Prefix-Sum version above, so both are kept, each filed under the pattern it actually demonstrates.

Note: copied verbatim from the source, including its `public function` / `private function` modifiers - these are only valid inside a class, but no enclosing class exists anywhere in this source file, so this snippet has a pre-existing syntax error in the original DS file. Not modified or fixed here, per the rule that DS stays untouched.
*/

    public function numSubarraysWithSum($nums, $goal)
    {
        // Return difference between atMost(goal) and atMost(goal - 1)
        return $this->atMost($nums, $goal) - $this->atMost($nums, $goal - 1);
    }

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


/*
|--------------------------------------------------------------------------
| Problem: Count Number of Nice Subarrays (LC 1248)
| Source: DS/11.Two Pointer & Sliding Window/Combined_Problems.php
| Pattern: Sliding Window
|--------------------------------------------------------------------------
*/

/*
Problem Description:
1248. Count Number of Nice Subarrays

Exactly K odd numbers

Example:
Input: nums = [1,1,2,1,1], k = 3
Output: 2

Why This Pattern:
'Exactly k odd numbers' is decomposed into atMost(k) - atMost(k-1), where atMost(k) is a standard expand/shrink Sliding Window counting subarrays with at most k odd numbers - the same atMost-difference trick used for Binary Subarrays With Sum and Count Substrings with Exactly K Distinct Characters elsewhere in this codebase.
*/

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

function niceSubarrays($nums, $k)
{
    return numberOfSubarrays($nums, $k)
         - numberOfSubarrays($nums, $k - 1);
}


/*
|--------------------------------------------------------------------------
| Problem: Longest Substring with At Most K Distinct Characters
| Source: DS/11.Two Pointer & Sliding Window/Combined_Problems.php
| Pattern: Sliding Window
|--------------------------------------------------------------------------
*/

/*
Problem Description:
Longest Substring with At Most K Distinct Characters

Example:
Input: s = "eceba", k = 2
Output: 3 (the answer is "ece")

Why This Pattern:
Expand the window with every character, shrink it from the left whenever it holds more than k distinct characters - the same 'at most K distinct' variable-size Sliding Window family as Fruit Into Baskets (a specialization of this exact problem to K=2 fruit types).
*/

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


/*
|--------------------------------------------------------------------------
| Problem: Number of Substrings Containing All Three Characters (LC 1358)
| Source: DS/11.Two Pointer & Sliding Window/Combined_Problems.php
| Pattern: Sliding Window
|--------------------------------------------------------------------------
*/

/*
Problem Description:
Optimized solution for strings containing only:

     a, b and c

Instead of storing frequencies,
store the latest index of each character.

Whenever all three characters have been seen,
the earliest last occurrence determines how many
valid substrings end at the current index.

Time Complexity  : O(n)
Space Complexity : O(1)

---

Count substrings containing exactly/at least K distinct characters
(This implementation works for problems like LeetCode 1358 when k = 3).

Time Complexity  : O(n)
Space Complexity : O(k)

Idea:
-----
Expand the window using the right pointer.

As soon as the window contains exactly K distinct characters,
every substring starting from 'left' and ending at
'right', 'right+1', ..., 'n-1' will also satisfy the condition.

Therefore, instead of checking each substring individually,
we add:

     n - right

Then shrink the window from the left to search for more valid windows.

Example:
Input: s = "abcabc"
Output: 10

Why This Pattern:
Two Sliding Window solutions to the same problem are included: `numberOfSubstrings` tracks only the latest index of each of a/b/c and adds the earliest of those indices + 1 once all three have been seen; `numberOfSubstringsV1` is the more general expand/shrink window (works for any K distinct-required-characters count, not just fixed a/b/c) that adds (n - right) once the window holds exactly K distinct characters, then shrinks - the same generalized technique as Count Substrings with Exactly K Distinct Characters.
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


/*
|--------------------------------------------------------------------------
| Problem: Frequency of the Most Frequent Element - Sliding Window (LC 1838)
| Source: DS/1. Basic/3. Hashing.php
| Pattern: Sliding Window
|--------------------------------------------------------------------------
*/

/*
Problem Description:
Given nums[] and integer k (max allowed increment operations, each +1), find the maximum achievable frequency of any element.

Approach: sort the array. For a window [l..r], the cheapest target to raise every element to is nums[r] (the largest, rightmost element in the window), since elements can only be incremented. cost = nums[r]*windowSize - windowSum. Expand r each step; while cost > k, shrink from the left. The window size at each step is a candidate answer.

Example:
Input: nums=[1, 2, 4], k=5
Output: 3 (raise all three to 4: cost = 3+2+0 = 5 <= k)

Why This Pattern:
After sorting, the window's right edge always expands and the left edge only ever shrinks when the cost to raise the whole window up to nums[r] exceeds the budget k - the handbook's own 'sorted array + variable window with budget' listing for this exact problem (#21).
*/

function maxFrequencySlidingWindow(array $nums, int $k): int
{
    sort($nums);                    // Sort ascending so window target = nums[r] (rightmost)

    $n         = count($nums);
    $maxFreq   = 0;
    $windowSum = 0;
    $l         = 0;                 // Left pointer of sliding window

    for ($r = 0; $r < $n; $r++) {
        $windowSum += $nums[$r];    // Expand window rightward by including nums[r]

        // Cost = how many total increments needed to make all window elements = nums[r]
        $cost = $nums[$r] * ($r - $l + 1) - $windowSum;

        // If cost exceeds budget k → shrink from left until cost is within budget
        while ($cost > $k) {
            $windowSum -= $nums[$l];
            $l++;
            $cost = $nums[$r] * ($r - $l + 1) - $windowSum;
        }

        $maxFreq = max($maxFreq, $r - $l + 1);     // Current valid window size = max freq
    }

    return $maxFreq;
}

