<?php

/*
|--------------------------------------------------------------------------
| Problem: Longest Subarray With Sum Equals K
| Source: DS/3. Array/Array_enhancement.php
| Pattern: Prefix Sum
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
A running prefix sum stored in a hashmap (first-occurrence index per sum) turns 'does some earlier prefix make this subarray sum to k' into an O(1) lookup instead of an O(n^2) scan.
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
| Problem: Subarray Sum Equals K - Count (LC 560)
| Source: DS/3. Array/Array_enhancement.php
| Pattern: Prefix Sum
|--------------------------------------------------------------------------
*/

/*
Problem Description:
 What: count how many contiguous subarrays sum to exactly k (can include
       negative numbers).
 Why it exists: the natural extension of Problem 12's "longest subarray"
   into "how MANY subarrays" -- reinforces that Prefix Sum + HashMap
   generalizes cleanly to counting questions by switching from
   "first-index storage" to "frequency storage."

Example:
Input: nums = [1,1,1], k = 2
Output: 2

Why This Pattern:
A running prefix sum combined with a frequency map of prefix-sum occurrences turns 'how many subarrays sum to k' into an O(n) single pass instead of enumerating all O(n^2) subarrays.
*/

function subarraySumBrute(array $nums, int $k): int {
    $n = count($nums);
    $count = 0;
    for ($start = 0; $start < $n; $start++) {
        $sum = 0;
        for ($end = $start; $end < $n; $end++) {
            $sum += $nums[$end];
            if ($sum === $k) $count++;
        }
    }
    return $count;
}

function subarraySum(array $nums, int $k): int {
    $prefixSumFreq = [0 => 1];   // CRITICAL: accounts for subarrays starting at index 0
    $sum = 0;
    $count = 0;

    for ($i = 0; $i < count($nums); $i++) {
        $sum += $nums[$i];

        $needed = $sum - $k;   // We need this many EARLIER prefix sums to have existed
        $count += $prefixSumFreq[$needed] ?? 0;   // Each prior occurrence = one valid subarray ending here

        $prefixSumFreq[$sum] = ($prefixSumFreq[$sum] ?? 0) + 1;   // Register current prefix sum for FUTURE indices to match against
    }

    return $count;
}


/*
|--------------------------------------------------------------------------
| Problem: Binary Subarrays With Sum — Prefix-Sum + HashMap (LC 930)
| Source: DS/11.Two Pointer & Sliding Window/Combined_Problems.php
| Pattern: Prefix Sum
|--------------------------------------------------------------------------
*/

/*
Problem Description:
LeetCode 930 - Binary Subarrays With Sum

Approach:
Prefix Sum + HashMap

Time Complexity  : O(n)
Space Complexity : O(n)

Example:
Input: nums = [1,0,1,0,1], goal = 2
Output: 4

Why This Pattern:
A running prefix sum combined with a frequency map of prefix-sum occurrences turns 'how many subarrays sum to goal' into an O(n) single pass - the same Prefix-Sum + Hashing combination used across the Subarray-Sum family (Subarray Sum Equals K, Longest Subarray With Sum K).
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


/*
|--------------------------------------------------------------------------
| Problem: Subarray With Sum = 0 - Existence Check and Longest Length
| Source: DS/1. Basic/3. Hashing.php
| Pattern: Prefix Sum
|--------------------------------------------------------------------------
*/

/*
Problem Description:
  PROBLEM:
    Given an integer array, return true if any contiguous subarray has sum = 0.

  EXAMPLE:
    Input  : [4, 2, -3, 1, 6]
    Output : true    (subarray [2, -3, 1] has sum = 0)

  KEY INSIGHT — Prefix Sum + HashSet:
    Define prefixSum[i] = arr[0] + arr[1] + ... + arr[i].

    If prefixSum[i] == prefixSum[j] for some i < j:
      → subarray(i+1, j) = prefixSum[j] - prefixSum[i] = 0   ← zero-sum subarray!

    Special case: if prefixSum[i] == 0 itself
      → subarray(0, i) has sum = 0.

    So: store all prefix sums in a set.  If any prefix sum repeats → return true.
    Pre-insert 0 to catch the "prefix itself = 0" case.

  DRY RUN:
    arr        = [4, 2, -3, 1, 6]
    prefixSum  = 0
    seenPrefix = {0}   ← pre-insert 0 before the loop

    i=0 | num=4  → prefixSum=4  | 4 not in seenPrefix  → seenPrefix = {0,4}
    i=1 | num=2  → prefixSum=6  | 6 not in seenPrefix  → seenPrefix = {0,4,6}
    i=2 | num=-3 → prefixSum=3  | 3 not in seenPrefix  → seenPrefix = {0,4,6,3}
    i=3 | num=1  → prefixSum=4  | 4 IS in seenPrefix!  → return true  ✓
         (prefixSum[3]=4 = prefixSum[0]=4 → subarray[1..3]=[2,-3,1] = 0)

  TIME  : O(n)
  SPACE : O(n)

---

  PROBLEM:
    Find the LENGTH of the longest contiguous subarray with sum = 0.

  EXAMPLE:
    Input  : [15, -2, 2, -8, 1, 7, 10, 23]
    Output : 5    (subarray [-2, 2, -8, 1, 7] at indices 1..5)

  KEY INSIGHT — Prefix Sum + HashMap:
    If prefixSum[j] - prefixSum[i] = 0  →  prefixSum[i] == prefixSum[j]
    Length of subarray from (i+1) to j = j - i.

    Store {prefixSum → FIRST index where this sum appeared}.
    When the same prefix sum appears again at index j:
      length = j - firstIndex[prefixSum]
    We keep only the FIRST occurrence so the length is maximised.

  DRY RUN:
    arr        = [15, -2, 2, -8, 1, 7, 10, 23]
    firstIndex = {0:-1}   ← pSum 0 "seen" at virtual index -1 (before the array)
    prefixSum  = 0,  maxLen = 0

    i=0 | 15  → pS=15  | 15 not in map  → firstIndex[15] = 0
    i=1 | -2  → pS=13  | 13 not in map  → firstIndex[13] = 1
    i=2 | 2   → pS=15  | 15 IS in map at 0  → len = 2-0 = 2  →  maxLen=2
                          (pS=15 already stored, do NOT overwrite first index)
    i=3 | -8  → pS=7   | 7 not in map   → firstIndex[7]  = 3
    i=4 | 1   → pS=8   | 8 not in map   → firstIndex[8]  = 4
    i=5 | 7   → pS=15  | 15 IS in map at 0  → len = 5-0 = 5  →  maxLen=5  ✓
    i=6 | 10  → pS=25  | 25 not in map  → firstIndex[25] = 6
    i=7 | 23  → pS=48  | 48 not in map  → firstIndex[48] = 7

    → maxLen = 5   subarray [-2,2,-8,1,7] = 0  ✓

  TIME  : O(n)
  SPACE : O(n)

Example:
Input: [4, 2, -3, 1, 6]
hasZeroSumSubarray -> true (subarray [2,-3,1] sums to 0)

Input: [15, -2, 2, -8, 1, 7, 10, 23]
longestSubarrayWithZeroSum -> 5 (subarray [-2,2,-8,1,7])

Why This Pattern:
prefixSum[j] == prefixSum[i] for i<j means the subarray between them sums to zero - the foundational 'equal prefix sums => zero-sum subarray between them' insight that the general Prefix-Sum + HashMap technique (used for arbitrary target sums elsewhere in this pattern) specializes from.
*/

function hasZeroSumSubarray(array $arr): bool
{
    $prefixSum    = 0;
    $seenPrefix   = [];
    $seenPrefix[0] = true;      // Pre-insert 0 to handle subarrays starting at index 0

    foreach ($arr as $num) {
        $prefixSum += $num;

        if (isset($seenPrefix[$prefixSum])) {
            return true;        // Same prefix sum seen before → zero-sum subarray exists
        }

        $seenPrefix[$prefixSum] = true;
    }

    return false;
}

function longestSubarrayWithZeroSum(array $arr): int
{
    $firstIndex    = [];
    $firstIndex[0] = -1;       // Prefix sum 0 is "seen" before the array starts (index -1)
    $prefixSum     = 0;
    $maxLen        = 0;

    foreach ($arr as $i => $num) {
        $prefixSum += $num;

        if (isset($firstIndex[$prefixSum])) {
            // Same prefix sum was seen at firstIndex[$prefixSum]
            // → subarray from (firstIndex[$prefixSum]+1) to i has sum = 0
            $len    = $i - $firstIndex[$prefixSum];
            $maxLen = max($maxLen, $len);
        } else {
            // Store only the FIRST occurrence to maximise future lengths
            $firstIndex[$prefixSum] = $i;
        }
    }

    return $maxLen;
}

