<?php

/*
|--------------------------------------------------------------------------
| Problem: Maximum Subarray - Kadane's Algorithm (LC 53)
| Source: DS/3. Array/Array_enhancement.php
| Pattern: Dynamic Programming (1D)
|--------------------------------------------------------------------------
*/

/*
Problem Description:
 What: find the contiguous subarray with the largest sum; return the sum
       (this enhanced version also returns the subarray's boundaries).
 Why it exists: Kadane's is the prototypical "local decision, global
   optimum" 1D dynamic-programming pattern -- the decision at each index
   is simply "extend the previous subarray, or start fresh here?"

Example:
Input: nums = [-2,1,-3,4,-1,2,1,-5,4]
Output: maxSum = 6, subarray = [4,-1,2,1]

Why This Pattern:
Kadane's algorithm is the prototypical 1D DP recurrence: dp[i] = max(nums[i], dp[i-1] + nums[i]), the decision at each index being 'extend the running subarray or start fresh here'.
*/

function maxSubArrayBrute(array $nums): int {
    $n = count($nums);
    $maxSum = PHP_INT_MIN;
    for ($start = 0; $start < $n; $start++) {
        $sum = 0;
        for ($end = $start; $end < $n; $end++) {
            $sum += $nums[$end];
            $maxSum = max($maxSum, $sum);
        }
    }
    return $maxSum;
}

function maxSubArray(array $nums): array {
    $runningSum = 0;
    $maxSum = PHP_INT_MIN;      // Must be -infinity, NOT 0 -- handles all-negative arrays correctly
    $tentativeStart = -1;
    $bestStart = $bestEnd = -1;

    for ($i = 0; $i < count($nums); $i++) {
        if ($runningSum === 0) {
            $tentativeStart = $i;   // A fresh start begins here (either the very first index, or right after a reset)
        }

        $runningSum += $nums[$i];

        if ($runningSum > $maxSum) {
            $maxSum = $runningSum;
            $bestStart = $tentativeStart;
            $bestEnd = $i;
        }

        if ($runningSum < 0) {
            $runningSum = 0;   // Negative running sum can only hurt the future -- discard it entirely
        }
    }

    return [
        'maxSum' => $maxSum,
        'start' => $bestStart,
        'end' => $bestEnd,
        'subarray' => array_slice($nums, $bestStart, $bestEnd - $bestStart + 1),
    ];
}


/*
|--------------------------------------------------------------------------
| Problem: Best Time to Buy and Sell Stock (LC 121)
| Source: DS/3. Array/Array_enhancement.php
| Pattern: Dynamic Programming (1D)
|--------------------------------------------------------------------------
*/

/*
Problem Description:
 What: given daily stock prices, find the maximum profit from buying on
       one day and selling on a LATER day (or 0 if no profit is possible).
 Why it exists: teaches "track the best opportunity seen so far" as you
   scan forward -- directly reusable for "best time to buy/sell" variants
   (with cooldown, with fees, with K transactions -- all in the DP topic).

Example:
Input: prices = [7,1,5,3,6,4]
Output: 5 (buy at 1, sell at 6)

Why This Pattern:
This is the degenerate single-state case of 1D DP over the price sequence - dp[i] tracks the best profit achievable using only prices seen up to i (equivalently, a running min price + running max profit).
*/

function maxProfitBrute(array $prices): int {
    $maxProfit = 0;
    for ($i = 0; $i < count($prices); $i++) {
        for ($j = $i + 1; $j < count($prices); $j++) {
            $maxProfit = max($maxProfit, $prices[$j] - $prices[$i]);
        }
    }
    return $maxProfit;
}

function maxProfit(array $prices): int {
    $minPriceSoFar = PHP_INT_MAX;
    $maxProfit = 0;

    foreach ($prices as $price) {
        $minPriceSoFar = min($minPriceSoFar, $price);      // Best possible buy point up to today
        $maxProfit = max($maxProfit, $price - $minPriceSoFar);  // Best possible profit if selling today
    }

    return $maxProfit;
}


/*
|--------------------------------------------------------------------------
| Problem: Fibonacci Number (LC 509) - Series Build, Naive Recursion, Memoized DP
| Source: DS/1. Basic/4. Recursion.php
| Pattern: DP - 1D
|--------------------------------------------------------------------------
*/

/*
Problem Description:
============================================================
8. FIBONACCI SERIES
============================================================
The Fibonacci sequence: 0, 1, 1, 2, 3, 5, 8, 13, 21 …
F(0)=0, F(1)=1, F(N) = F(N-1) + F(N-2)

--- Approach A: Build series array iteratively ---
Useful when you need all N terms at once.

Time Complexity : O(N)
Space Complexity: O(N)
============================================================

---

============================================================
--- Approach B: Find the Nth Fibonacci number ---

Simple recursive definition of Fibonacci.

⚠ Warning: Very inefficient — overlapping subproblems.
  The same sub-results are computed multiple times.
  Use Memoization (DP) to optimise (see section 9).

Time Complexity : O(2^N) — exponential (two branches per call)
Space Complexity: O(N)   — max call stack depth at any time
============================================================

---

============================================================
9. FIBONACCI WITH MEMOIZATION (Top-Down DP)
============================================================
Problem: Same as above but optimised by caching results.

Key Idea:
  Store already-computed values in a lookup table ($memo).
  Before computing, check if the result is already cached.
  This eliminates redundant recursive calls.

Time Complexity : O(N) — each subproblem computed once
Space Complexity: O(N) — memo array + call stack
============================================================

Example:
fibonacci(7) = 13
fibMemo(10) = 55
buildFibSeries(0,1,8,...) -> 0,1,1,2,3,5,8,13

Why This Pattern:
The naive fibonacci() recursion re-solves identical overlapping subproblems exponentially many times (fib(3) gets recomputed many times inside fib(7)) - the handbook's own textbook 'classic overlapping subproblem introduction' (Fibonacci Number, LC 509). fibMemo() adds a cache and turns the same recurrence into O(N), the canonical top-down DP transition; buildFibSeries() is the non-memoized series-building variant included alongside it for the same topic.
*/

function buildFibSeries(int $a, int $b, int $n, array &$result): void
{
    // Base case: we have collected exactly n terms
    if (count($result) === $n) return;

    $next = $a + $b;                     // Next Fibonacci number
    $result[] = $next;                   // Add it to the result array

    buildFibSeries($b, $next, $n, $result);  // Slide the window forward
}

function fibonacci(int $n): int
{
    // Base cases: F(0) = 0, F(1) = 1
    if ($n === 0 || $n === 1) return $n;

    // F(N) = F(N-1) + F(N-2)
    return fibonacci($n - 1) + fibonacci($n - 2);
}

function fibMemo(int $n, array &$memo = []): int
{
    // Base cases
    if ($n === 0 || $n === 1) return $n;

    // Return cached result if available
    if (isset($memo[$n])) return $memo[$n];

    // Compute, cache, and return
    $memo[$n] = fibMemo($n - 1, $memo) + fibMemo($n - 2, $memo);
    return $memo[$n];
}

