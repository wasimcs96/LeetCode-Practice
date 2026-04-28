<?php

// ============================================================
// ADVANCED RECURSION — Complete Revision Guide
// Topics : Fast Power / Binary Exponentiation  (LeetCode 50)
//          Print All Subsequences
//          Print All Subsequences with Sum = K
//          Print Only ONE Subsequence with Sum = K
//          Count Subsequences with Sum = K
//          Combination Sum                      (LeetCode 39)
//          Subset Sums (All Unique Sums)
//          Subsets II — With Duplicates         (LeetCode 90)
// ============================================================
// Core Intuition — Pick / Not-Pick (Binary Decision Tree):
//   Every subsequence / subset problem builds a BINARY TREE
//   where at each node you make ONE of two decisions:
//     LEFT  (EXCLUDE): skip arr[i], move to i+1, state unchanged.
//     RIGHT (INCLUDE): pick arr[i], move to i+1, update state.
//
//   Total paths from root to leaf = 2^N  → at least O(2^N) work.
//   Max stack depth                = N   → at least O(N) space.
//
//   Three-step template for every recursion:
//     Step 1 — BASE CASE  : what to do when i reaches end
//     Step 2 — EXCLUDE    : skip current element, recurse i+1
//     Step 3 — INCLUDE    : pick element, recurse i+1, UNDO (backtrack)
// ============================================================


// ============================================================
// 1. FAST POWER — ITERATIVE (Binary Exponentiation)  (LeetCode 50)
// ============================================================
// Intuition:
//   x^13 = x^(1101 in binary) = x^8 × x^4 × x^1
//   Idea: repeatedly SQUARE x and multiply into result only when
//   the corresponding bit of n is 1.
//
//   Algorithm:
//     result = 1
//     While n > 0:
//       If n is ODD  → result *= x   (current bit is 1)
//       x = x * x                    (square x for next bit)
//       n = n / 2                    (shift to next bit)
//     Handle negative n: answer = 1 / result
//
// Dry Run: x=2, n=10  (10 = 1010 in binary → 2^10 = 1024)
//   n=10  (even): result=1,    x=4,    n=5
//   n=5   (odd):  result=4,    x=16,   n=2
//   n=2   (even): result=4,    x=256,  n=1
//   n=1   (odd):  result=1024, x=...,  n=0
//   return 1024  ✓
//
// TC: O(log N)  SC: O(1)
// ----------------------------------------------------------
function myPow(float $x, int $n): float
{
    $result = 1.0;
    $absN   = abs($n); // Handle negative exponent at the end

    while ($absN > 0) {
        if ($absN % 2 === 1) {
            $result *= $x;      // Current bit is 1 → multiply into result
        }
        $x    *= $x;            // Square x (move to next bit position)
        $absN  = intdiv($absN, 2); // Right-shift n (process next bit)
    }

    // Negative exponent: x^(-n) = 1 / x^n
    return ($n < 0) ? 1.0 / $result : $result;
}


// ============================================================
// 2. FAST POWER — RECURSIVE (Single-Call, O(log N))  (LeetCode 50)
// ============================================================
// Intuition:
//   x^n = (x^2)^(n/2)   if n is even
//   x^n = x × (x^2)^(n/2)  if n is odd   (pull one x out)
//
//   Each recursive call HALVES n → depth = O(log N).
//   Only ONE recursive call per level (not two) → no exponential blowup.
//
// Dry Run: myPowRecursive(2, 10)
//   n=10 (even): recurse(4, 5)
//     n=5 (odd):  2 ← pulled out × recurse(16, 2)
//       n=2 (even): recurse(256, 1)
//         n=1 (odd): 256 ← pulled out × recurse(65536, 0)
//           n=0: return 1
//         return 256 × 1 = 256
//       return 256
//     return 4 × 256 = 1024
//   return 1024  ✓
//
// TC: O(log N)  SC: O(log N) — recursion stack depth
// ----------------------------------------------------------
function myPowRecursive(float $x, int $n): float
{
    if ($n === 0) return 1.0; // Base case: anything^0 = 1

    // Recurse with x squared and n halved (single call — no double work)
    $half = myPowRecursive($x * $x, intdiv($n, 2));

    if ($n % 2 === 0) {
        return $half;           // Even: x^n = (x^2)^(n/2)
    } else {
        return $x * $half;      // Odd:  x^n = x × (x^2)^(n/2)
    }
}

// Wrapper that handles negative exponents
function powFast(float $x, int $n): float
{
    $result = myPowRecursive($x, abs($n));
    return ($n < 0) ? 1.0 / $result : $result;
}


// ============================================================
// TEACHING EXAMPLE: Double Recursive Pow — WHY IT'S BAD
// ============================================================
// Calling myRecursivePow TWICE with the same half problem doubles
// the work at every level: T(n) = 2T(n/2) → O(N) by Master Theorem.
// Compare to single-call: T(n) = T(n/2) → O(log N).
// Fix: store the half result in a variable before using it twice.
//
// BAD (O(N)):    $f1 = pow(x, n/2);  $f2 = pow(x, n/2); return $f1*$f2;
// GOOD (O(logN)):$half = pow(x, n/2); return $half * $half;
// ----------------------------------------------------------


// ============================================================
// 3. PRINT ALL SUBSEQUENCES  (Pick / Not-Pick)
// ============================================================
// Intuition:
//   For every index i in [0..n-1], we make a binary choice:
//     EXCLUDE arr[i] — don't add to current path
//     INCLUDE arr[i] — add to current path (add, recurse, UNDO)
//   When i reaches n (leaf node), print the current path.
//   Total paths = 2^N (all subsets including empty set).
//
// Dry Run: arr=[1,2,3]
//   Recursion tree (X=exclude, I=include):
//   i=0       X:[]  I:[1]
//   i=1    X:[]  I:[2]     X:[1]  I:[1,2]
//   i=2  X:[] I:[3] X:[2] I:[2,3]  X:[1] I:[1,3] X:[1,2] I:[1,2,3]
//   Leaves (8 subsets): [], [3], [2], [2,3], [1], [1,3], [1,2], [1,2,3]
//
// TC: O(2^N × N)  — 2^N subsets, each takes O(N) to print
// SC: O(N)        — recursion stack depth
// ----------------------------------------------------------
function printAllSubsequences(int $i, int $n, array $arr, array $path): void
{
    if ($i >= $n) {
        // Leaf node: print whatever we've collected along this path
        echo "[" . implode(", ", $path) . "]\n";
        return;
    }

    // EXCLUDE arr[i]: skip it, move to next index, path unchanged
    printAllSubsequences($i + 1, $n, $arr, $path);

    // INCLUDE arr[i]: add to path, recurse, then UNDO (backtrack)
    $path[] = $arr[$i];
    printAllSubsequences($i + 1, $n, $arr, $path);
    // No explicit undo needed here: $path is passed by value in PHP
    // (PHP arrays are value types — each call gets its own copy)
}


// ============================================================
// 4. PRINT ALL SUBSEQUENCES WITH SUM = K
// ============================================================
// Intuition:
//   Same pick/not-pick tree as above, but at the leaf node
//   we FILTER: only print if the accumulated sum equals target.
//
//   Optimisation (positive-only arrays): if running sum already
//   exceeds target, prune the entire subtree (can't go back).
//
// Dry Run: arr=[1,2,3], target=3
//   All subsets and their sums:
//     []      → 0   ✗
//     [3]     → 3   ✓ print
//     [2]     → 2   ✗
//     [2,3]   → 5   ✗
//     [1]     → 1   ✗
//     [1,3]   → 4   ✗
//     [1,2]   → 3   ✓ print
//     [1,2,3] → 6   ✗
//   Output: [3], [1,2]
//
// TC: O(2^N × N)  SC: O(N)
// ----------------------------------------------------------
function printSubsetsWithSumK(int $i, int $n, array $arr, array $path,
                               int $target, int $sum): void
{
    // Pruning: if sum already exceeds target (works when all elements > 0)
    if ($sum > $target) return;

    if ($i >= $n) {
        if ($sum === $target) {
            echo "[" . implode(", ", $path) . "]\n";
        }
        return;
    }

    // EXCLUDE: don't add arr[i] to sum
    printSubsetsWithSumK($i + 1, $n, $arr, $path, $target, $sum);

    // INCLUDE: add arr[i] to sum, recurse, backtrack
    $path[] = $arr[$i];
    printSubsetsWithSumK($i + 1, $n, $arr, $path, $target, $sum + $arr[$i]);
    // No explicit undo: $path is passed by VALUE (PHP copies the array)
}


// ============================================================
// 5. PRINT ONLY ONE SUBSEQUENCE WITH SUM = K (Early Exit)
// ============================================================
// Intuition:
//   Same as above, but STOP the moment we find ONE valid subset.
//   Use a boolean RETURN VALUE to signal up the call stack:
//     true  → valid subset found; propagate upward → stop all recursion
//     false → not found yet; continue searching
//
//   Critical fix: the `if(found) return TRUE` must propagate upward!
//   If a child returns true, the parent must also return true immediately.
//
// Dry Run: arr=[1,2,3], target=3
//   EXCLUDE 1 → EXCLUDE 2 → EXCLUDE 3 → [] sum=0 ✗ return false
//   EXCLUDE 1 → EXCLUDE 2 → INCLUDE 3 → [3] sum=3 ✓ print → return true
//   true propagates all the way up → all other branches SKIPPED  ✓
//
// TC: O(2^N) worst  SC: O(N)
// ----------------------------------------------------------
function printOneSubsetWithSumK(int $i, int $n, array $arr, array $path,
                                 int $target, int $sum): bool
{
    if ($sum > $target) return false; // Pruning

    if ($i >= $n) {
        if ($sum === $target) {
            echo "[" . implode(", ", $path) . "]\n";
            return true;  // Signal: found! stop all further recursion
        }
        return false;     // Not found at this leaf
    }

    // EXCLUDE: try without arr[i]
    if (printOneSubsetWithSumK($i + 1, $n, $arr, $path, $target, $sum)) {
        return true;      // Found in exclude-branch → propagate up, stop here
    }

    // INCLUDE: try with arr[i]
    $path[] = $arr[$i];
    if (printOneSubsetWithSumK($i + 1, $n, $arr, $path, $target, $sum + $arr[$i])) {
        return true;      // Found in include-branch → propagate up, stop here
    }

    return false;         // Neither branch found anything
}


// ============================================================
// 6. COUNT SUBSEQUENCES WITH SUM = K
// ============================================================
// Intuition:
//   Instead of printing, RETURN the COUNT of valid subsets.
//   At each leaf: return 1 if sum==target, else 0.
//   Internal nodes: return left_count + right_count.
//
//   This is a pure functional recursion — no global variable needed.
//   The count bubbles UP the call stack through return values.
//
// Dry Run: arr=[1,2,3], target=3
//   Each leaf contributes 1 (if sum=3) or 0:
//     [] →0, [3]→1, [2]→0, [2,3]→0, [1]→0, [1,3]→0, [1,2]→1, [1,2,3]→0
//   Total = 0+1+0+0+0+0+1+0 = 2  ✓
//
// TC: O(2^N)  SC: O(N)
// ----------------------------------------------------------
function countSubsetsWithSumK(int $i, int $n, array $arr,
                               int $target, int $sum): int
{
    if ($sum > $target) return 0; // Pruning (positive arrays only)

    if ($i >= $n) {
        return ($sum === $target) ? 1 : 0; // Leaf: found=1, not found=0
    }

    // EXCLUDE: count valid subsets that don't include arr[i]
    $exclude = countSubsetsWithSumK($i + 1, $n, $arr, $target, $sum);

    // INCLUDE: count valid subsets that include arr[i]
    $include = countSubsetsWithSumK($i + 1, $n, $arr, $target, $sum + $arr[$i]);

    return $exclude + $include; // Total count = left subtree + right subtree
}


// ============================================================
// 7. COMBINATION SUM  (LeetCode 39)
// ============================================================
// Intuition:
//   Find all UNIQUE combinations of $candidates that sum to $target.
//   Each element may be used UNLIMITED times (no index increment on pick).
//
//   Key difference from subset problems:
//     - INCLUDE: stay at the SAME index $i (allow reuse of arr[i]).
//     - EXCLUDE: move to the NEXT index $i+1 (skip arr[i] forever).
//
//   Base cases:
//     target == 0 → found a valid combination → store it.
//     i >= n OR target < 0 → dead end → return.
//
// Dry Run: candidates=[2,3,6,7], target=7
//   Branch PICK 2: [2] target=5
//     PICK 2: [2,2] target=3
//       PICK 2: [2,2,2] target=1
//         PICK 2: [2,2,2,2] target=-1 → prune
//         SKIP 2: try 3: [2,2,2,3] target=-2 → prune ... no result
//       SKIP 2: try 3: [2,2,3] target=0 → ✓ store [2,2,3]
//     SKIP 2: try 3: [2,3] target=2
//       PICK 3: target=-1 → prune; SKIP 3: try 6 →prune; SKIP: try 7→prune
//   ... SKIP 2: try 3: [3] target=4
//     PICK 3: [3,3] target=1 → prune chain
//     SKIP 3: try 6 →prune; try 7→[7] target=0 → ✓ store [7]
//   ... SKIP 3: try 6 → prune; try 7 → ✓ store [7] (already found via other path)
//   Result: [[2,2,3], [7]]  ✓
//
// TC: O(2^(target/min) × target/min)  SC: O(target/min) recursion depth
// ----------------------------------------------------------
function combinationSum(array $candidates, int $target): array
{
    $result = [];
    combinationSumHelper($candidates, $target, 0, [], $result);
    return $result;
}

function combinationSumHelper(array $candidates, int $target, int $i,
                               array $temp, array &$result): void
{
    if ($target === 0) {
        $result[] = $temp; // Found a valid combination
        return;
    }
    if ($i >= count($candidates) || $target < 0) {
        return; // No more elements or overshot
    }

    // INCLUDE: pick candidates[$i], stay at $i (REUSE allowed)
    $temp[] = $candidates[$i];
    combinationSumHelper($candidates, $target - $candidates[$i], $i, $temp, $result);
    array_pop($temp); // Backtrack: undo the inclusion

    // EXCLUDE: skip candidates[$i], move to NEXT index
    combinationSumHelper($candidates, $target, $i + 1, $temp, $result);
}


// ============================================================
// 8. SUBSET SUMS — ALL UNIQUE SUMS  (Striver's Problem)
// ============================================================
// Intuition:
//   Compute the SUM of every possible subset and return all sums SORTED.
//   We don't collect the elements — just the running sum.
//   At each leaf, store $sum in the results array.
//
//   Why store the sum (not the subset)?
//     We only care WHAT the sums are, not WHICH elements form them.
//     This simplifies the state: just pass a single integer $sum.
//
// Dry Run: arr=[2,5,8], all 8 subsets and their sums:
//   []→0, [8]→8, [5]→5, [5,8]→13, [2]→2, [2,8]→10, [2,5]→7, [2,5,8]→15
//   Sorted: [0, 2, 5, 7, 8, 10, 13, 15]  ✓
//
// TC: O(2^N + 2^N × log(2^N)) = O(2^N × N)  SC: O(2^N) for result storage
// ----------------------------------------------------------
function subsetSums(array $arr): array
{
    $n      = count($arr);
    $result = [];
    subsetSumsHelper($arr, 0, $n, 0, $result);
    sort($result);
    return $result;
}

function subsetSumsHelper(array $arr, int $i, int $n, int $sum, array &$result): void
{
    if ($i >= $n) {
        $result[] = $sum; // Leaf: store the accumulated sum
        return;
    }

    // EXCLUDE: don't add arr[i] to sum
    subsetSumsHelper($arr, $i + 1, $n, $sum, $result);

    // INCLUDE: add arr[i] to sum (no array to undo — sum is a scalar passed by value)
    subsetSumsHelper($arr, $i + 1, $n, $sum + $arr[$i], $result);
}


// ============================================================
// 9. SUBSETS II — UNIQUE SUBSETS FROM ARRAY WITH DUPLICATES  (LeetCode 90)
// ============================================================
// Intuition:
//   Given a sorted array with possible duplicates, generate all
//   UNIQUE subsets (no two subsets should be identical).
//
//   Approach: SORT the array, then use a for-loop recursion
//   (instead of pick/not-pick). At each level, iterate over
//   CHOICES for the NEXT element in the subset.
//
//   Key deduplication rule (applied to SIBLINGS at same recursion level):
//     If arr[j] == arr[j-1] AND j > start → SKIP (it's a duplicate sibling).
//     Only skip when j > start (not when j == start — first occurrence is fine).
//
//   Why for-loop approach?
//     Pick/not-pick with sorting works too, but the for-loop approach
//     makes deduplication at the SAME LEVEL extremely clean and natural.
//
// Dry Run: nums=[1,2,2], start=0, temp=[]
//   j=0: pick 1 → recurse(start=1, temp=[1])
//     j=1: pick 2 → recurse(start=2, temp=[1,2])
//       j=2: 2==nums[1]=2 AND j(2) > start(2)? NO (j==start) → pick 2
//         recurse(start=3) → base → store [1,2,2]
//     j=2: nums[2]=2 == nums[1]=2 AND j(2) > start(1)? YES → SKIP ✓
//   j=1: pick 2 → recurse(start=2, temp=[2])
//     j=2: 2==nums[1]=2 AND j(2) > start(2)? NO → pick 2 → store [2,2]
//   j=2: nums[2]=2 == nums[1]=2 AND j(2) > start(0)? YES → SKIP ✓
//   Result: [], [1], [1,2], [1,2,2], [2], [2,2]  ✓  (no duplicate [1,2])
//
// TC: O(2^N × N)  SC: O(N) recursion depth + O(2^N × N) result storage
// ----------------------------------------------------------
function subsetsWithDup(array $nums): array
{
    sort($nums); // Sort first so duplicates are adjacent
    $result = [];
    subsetsWithDupHelper($nums, 0, [], $result);
    return array_values($result);
}

function subsetsWithDupHelper(array $nums, int $start, array $temp,
                               array &$result): void
{
    $result[] = $temp; // Every call state is a valid (possibly partial) subset

    for ($j = $start; $j < count($nums); $j++) {
        // Skip duplicate siblings: same value at the same recursion level
        // j > $start ensures we only skip DUPLICATES, not the first occurrence
        if ($j > $start && $nums[$j] === $nums[$j - 1]) {
            continue;
        }
        $temp[] = $nums[$j];                              // INCLUDE nums[j]
        subsetsWithDupHelper($nums, $j + 1, $temp, $result); // Recurse
        array_pop($temp);                                 // Backtrack: undo inclusion
    }
}


// ============================================================
// DEMO — Run All Operations
// ============================================================

echo "=== 1. Fast Power — Iterative (LC 50) ===\n";
echo myPow(2.0, 10)   . "\n";  // 1024
echo myPow(2.0, -2)   . "\n";  // 0.25
echo myPow(2.0, 0)    . "\n";  // 1
echo myPow(0.0, 0)    . "\n";  // 1

echo "\n=== 2. Fast Power — Recursive (LC 50) ===\n";
echo powFast(2.0, 10)  . "\n"; // 1024
echo powFast(2.0, -2)  . "\n"; // 0.25
echo powFast(3.0, 3)   . "\n"; // 27

echo "\n=== 3. Print All Subsequences ===\n";
printAllSubsequences(0, 3, [1, 2, 3], []);
// 8 subsets: [], [3], [2], [2,3], [1], [1,3], [1,2], [1,2,3]

echo "\n=== 4. Print All Subsequences with Sum = 3 ===\n";
printSubsetsWithSumK(0, 3, [1, 2, 3], [], 3, 0);
// [3], [1,2]

echo "\n=== 5. Print ONLY ONE Subsequence with Sum = 3 ===\n";
printOneSubsetWithSumK(0, 3, [1, 2, 3], [], 3, 0);
// [3]  (first found, then stops)

echo "\n=== 6. Count Subsequences with Sum = 3 ===\n";
echo countSubsetsWithSumK(0, 3, [1, 2, 3], 3, 0) . "\n"; // 2
echo countSubsetsWithSumK(0, 4, [1, 1, 1, 1], 2, 0) . "\n"; // 6

echo "\n=== 7. Combination Sum (LC 39) ===\n";
$r = combinationSum([2, 3, 6, 7], 7);
foreach ($r as $combo) echo "[" . implode(", ", $combo) . "]\n";
// [2, 2, 3], [7]

$r2 = combinationSum([2, 3, 5], 8);
foreach ($r2 as $combo) echo "[" . implode(", ", $combo) . "]\n";
// [2,2,2,2], [2,3,3], [3,5]

echo "\n=== 8. Subset Sums ===\n";
$sums = subsetSums([2, 5, 8]);
echo implode(", ", $sums) . "\n"; // 0, 2, 5, 7, 8, 10, 13, 15

$sums2 = subsetSums([3, 1]);
echo implode(", ", $sums2) . "\n"; // 0, 1, 3, 4

echo "\n=== 9. Subsets II — With Duplicates (LC 90) ===\n";
$sets = subsetsWithDup([1, 2, 2]);
foreach ($sets as $s) echo "[" . implode(", ", $s) . "]\n";
// [], [1], [1,2], [1,2,2], [2], [2,2]

$sets2 = subsetsWithDup([1, 1, 2]);
foreach ($sets2 as $s) echo "[" . implode(", ", $s) . "]\n";
// [], [1], [1,1], [1,1,2], [1,2], [2]


// ============================================================
// COMPARISON SUMMARY
// ============================================================
//
//  Problem                          | TC              | SC         | Key Idea
// ----------------------------------+-----------------+------------+---------------------
//  Fast Power Iterative             | O(log N)        | O(1)       | Bit-by-bit squaring
//  Fast Power Recursive (1-call)    | O(log N)        | O(log N)   | Half + square trick
//  Fast Power Recursive (2-calls)   | O(N)            | O(log N)   | AVOID — recomputes
//  Print All Subsequences           | O(2^N × N)      | O(N)       | Pick / Not-Pick
//  Subsets with Sum K (all)         | O(2^N × N)      | O(N)       | Filter at leaf
//  Subsets with Sum K (one)         | O(2^N) worst    | O(N)       | Boolean early exit
//  Count Subsets with Sum K         | O(2^N)          | O(N)       | Return 0/1 at leaf
//  Combination Sum (LC 39)          | O(2^(T/min)×T)  | O(T/min)   | Stay at i (reuse)
//  Subset Sums (all sums sorted)    | O(2^N × N)      | O(2^N)     | Pass sum as scalar
//  Subsets II (with duplicates)     | O(2^N × N)      | O(N)       | Sort + skip sibling


// ============================================================
// PRACTICE PROBLEMS & APPLICATIONS
// ============================================================
//
//  EASY
//  1. Pow(x, n) (LeetCode 50)
//     → Iterative or recursive binary exponentiation; handle n<0
//  2. Subsets I (LeetCode 78)
//     → Pick/not-pick; all 2^N subsets; no duplicates in input
//  3. Letter Case Permutation (LeetCode 784)
//     → At each letter, branch into uppercase and lowercase
//
//  MEDIUM
//  4. Subsets II (LeetCode 90)
//     → Sort + for-loop recursion; skip arr[j]==arr[j-1] when j > start
//  5. Combination Sum (LeetCode 39)
//     → Unlimited reuse; stay at same index on pick; prune when target<0
//  6. Combination Sum II (LeetCode 40)
//     → Each element used ONCE; sort + skip duplicate siblings (same as Subsets II)
//  7. Permutations (LeetCode 46)
//     → All orderings; swap-based or visited-array approach
//  8. Permutations II — with Duplicates (LeetCode 47)
//     → Sort; skip duplicates at the SAME recursion level using a set
//  9. Palindrome Partitioning (LeetCode 131)
//     → At each index, try all lengths for the next palindrome segment
// 10. Word Search (LeetCode 79)
//     → DFS + backtracking on a 2D grid; mark visited, unmark on return
//
//  HARD
// 11. N-Queens (LeetCode 51)
//     → Place one queen per row; check column + diagonal conflicts
// 12. Sudoku Solver (LeetCode 37)
//     → Try digits 1-9 at each empty cell; backtrack on conflict
// 13. Expression Add Operators (LeetCode 282)
//     → Insert +, -, × between digits; track current value and last operand
// 14. Unique Paths III (LeetCode 980)
//     → DFS on grid visiting ALL non-obstacle cells exactly once


// ============================================================
// KEY PATTERNS & VARIATIONS FOR REVISION
// ============================================================
//
//  PATTERN 1 — Pick / Not-Pick (Index-Based):
//    The fundamental template for subsequence/subset problems.
//    At each index i, make two recursive calls:
//      EXCLUDE: recurse(i+1, same_state)
//      INCLUDE: recurse(i+1, updated_state),  then UNDO state
//    When to use: generate all subsets, count/print with constraint.
//
//  PATTERN 2 — For-Loop Recursion (Choice-Based):
//    At each level, use a for-loop to pick the NEXT element.
//    for j from start to n:
//      skip if duplicate sibling (j > start && arr[j] == arr[j-1])
//      pick arr[j], recurse(j+1), backtrack
//    When to use: permutations, combinations, subsets with dedup (LC 90).
//    Naturally expresses "what comes NEXT in the sequence" problems.
//
//  PATTERN 3 — Boolean Return for Early Exit:
//    When you need ONLY ONE result, return bool from every call.
//    If a child returns true → return true immediately (stop exploring).
//    If a child returns false → try the next branch.
//    Pattern: "if (recurse(...)) return true;"
//    Covers: LC 37 (Sudoku), LC 79 (Word Search), "print one subset".
//
//  PATTERN 4 — Count by Summing Return Values:
//    Replace "print at leaf" with "return 1 at leaf, 0 otherwise".
//    Internal nodes return left + right.
//    No global variable needed — pure functional recursion.
//    Covers: count subsets with sum K, count paths in grid.
//
//  PATTERN 5 — Backtracking (Undo After Recursion):
//    After the INCLUDE recursive call, UNDO the change:
//      array_pop($temp)  — undo adding an element
//      flip cell back    — undo marking a grid cell
//    In PHP: array parameters passed by VALUE (auto-copied).
//    For efficiency pass arrays BY REFERENCE (&$arr) + backtrack manually.
//
//  PATTERN 6 — Deduplication in Subsets/Combinations:
//    Sort the array so duplicates are adjacent.
//    In a for-loop recursion: skip if arr[j] == arr[j-1] AND j > start.
//    "j > start" means: skip duplicates at the same LEVEL, but allow the
//    first occurrence at each level.
//    Covers: LC 40, LC 47, LC 90.
//
//  PATTERN 7 — Binary Exponentiation (Divide & Conquer):
//    Replace linear multiplication with squaring:
//      x^n = (x^2)^(n/2)           [even n]
//      x^n = x * (x^2)^(n/2)       [odd n]
//    Always use ONE recursive call (store half result in variable).
//    Never call recursively twice with same args — O(N) blowup!


// ============================================================
// IMPORTANT TIPS & EDGE CASES
// ============================================================
//
//  1. PHP arrays are PASSED BY VALUE:
//     When you pass $temp to a recursive function, PHP copies it.
//     This means array_pop AFTER the recursive call still works as
//     backtracking (the callee's copy is already gone).
//     For LARGE arrays, pass by reference (&$temp) + manual array_pop
//     to avoid copying overhead at every call.
//
//  2. Fast Power — negative exponent edge case:
//     Always take abs($n) for the loop; at the very end divide by result.
//     x^(-n) = 1 / x^n — never pass negative n into the loop/recursion.
//
//  3. Fast Power — x=0 edge case:
//     If x=0.0 and n<0 → division by zero (0^(-2) = 1/0).
//     LeetCode guarantees this won't happen, but guard in production code.
//
//  4. Boolean early-exit: propagate true ALL the way up:
//     A common mistake is to not return the boolean:
//       WRONG: printOneSubset(...);  // return value discarded!
//       RIGHT: if (printOneSubset(...)) return true;
//
//  5. Dedup "j > start" vs "j > i":
//     In Subsets II / Combination Sum II, the skip condition is
//     j > start (where start = the beginning of the current loop),
//     NOT j > 0 (which would incorrectly skip the first occurrence in
//     any loop, not just duplicates).
//
//  6. Combination Sum (LC 39) vs Combination Sum II (LC 40):
//     LC 39: unlimited reuse → on PICK, stay at index i (recurse with i).
//     LC 40: each element once → on PICK, advance to i+1 (recurse with i+1).
//             Also: sort + skip duplicates at same level.
//
//  7. int vs intdiv() for exponent halving:
//     In PHP, $n / 2 returns a float when $n is odd.
//     Always use intdiv($n, 2) to get integer division.
//     Passing a float as $n to a typed int parameter causes a TypeError.
//
//  8. Empty subset is always a valid subset:
//     Both Subsets (LC 78) and Subsets II (LC 90) include the empty set [].
//     In pick/not-pick: the leaf with all EXCLUDEs is [].
//     In for-loop recursion: $result[] = $temp at the top includes [] (first call).

?>
