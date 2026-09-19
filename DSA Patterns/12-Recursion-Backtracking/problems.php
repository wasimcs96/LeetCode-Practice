<?php

/*
|--------------------------------------------------------------------------
| Problem: String to Integer / atoi - Recursive (LC 8)
| Source: DS/5. String/String_enhancement.php
| Pattern: Recursion / Backtracking
|--------------------------------------------------------------------------
*/

/*
Problem Description:
 What: identical to Problem 10, but the digit-accumulation loop is
       replaced with recursion; the whitespace/sign preprocessing stays iterative.
 Why it exists: a good exercise in recognizing which PART of an
   algorithm genuinely benefits from being reframed recursively (here,
   just the "keep consuming digits" loop) versus which part is more
   naturally left iterative (the one-time preprocessing).


 --- 🎯 Interview-Ready Add-Ons (constraints, timing, pitch) ---
 Asked at      : Amazon, Google -- almost always asked as a direct follow-up immediately after the iterative atoi, specifically to test recursion-conversion instincts.
 Constraints   : Same constraints as the iterative version (0 <= s.length <= 200) -> O(n) time, O(n) space (call stack depth) expected -- explicitly naming the space trade-off versus the iterative O(1) version is part of a strong answer.
 Time-boxing   : Total ~8 min (assuming the iterative version is already done): 2 min identify which PART becomes recursive (just digit consumption, not the whitespace/sign preprocessing), 6 min code the by-reference recursive helper + dry run.
 60-Sec Pitch  : "I keep the whitespace and sign preprocessing iterative exactly as before, and convert only the digit-accumulation loop into a recursive helper that takes the running result BY REFERENCE so every recursive call accumulates into the same variable."

Example:
Input: s="4193 with words" -> Output: 4193
Input: s="   -42" -> Output: -42

Why This Pattern:
The digit-accumulation loop is deliberately reframed as recursion (passing the running result by reference through each call) instead of a while-loop - a direct, explicit application of the Recursion pattern to a problem that's normally solved iteratively.
*/

function myAtoiHelper(string $s, int $i, int &$result, bool $isNegative): void
{
    $n = strlen($s);
    $INT_MAX = 2147483647;

    if ($i >= $n || $s[$i] < '0' || $s[$i] > '9') {   // Base case: end of string or non-digit
        return;
    }

    $digit = (int) $s[$i];
    $limitLastDigit = $isNegative ? 8 : 7;              // |INT_MIN| ends in 8, INT_MAX ends in 7

    if ($result > intdiv($INT_MAX, 10)) {
        $result = $isNegative ? 2147483648 : $INT_MAX;   // Store the RAW absolute bound -- sign applied by the caller
        return;
    }
    if ($result === intdiv($INT_MAX, 10) && $digit > $limitLastDigit) {
        $result = $isNegative ? 2147483648 : $INT_MAX;
        return;
    }

    $result = $result * 10 + $digit;                     // Safe to accumulate this digit
    myAtoiHelper($s, $i + 1, $result, $isNegative);       // Recurse on the next character
}

function myAtoiRecursive(string $s): int
{
    if ($s === '') return 0;

    $i = 0;
    $n = strlen($s);
    $isNegative = false;
    $result = 0;

    while ($i < $n && $s[$i] === ' ') $i++;   // Preprocessing stays iterative -- only digit consumption recurses

    if ($i < $n && ($s[$i] === '+' || $s[$i] === '-')) {
        $isNegative = ($s[$i] === '-');
        $i++;
    }

    myAtoiHelper($s, $i, $result, $isNegative);   // Recursively accumulate digits into $result

    return $isNegative ? -$result : $result;
}


/*
|--------------------------------------------------------------------------
| Problem: Add 1 to a Number Represented as a Linked List
| Source: DS/6. LinkedList/9. Single-LL.php
| Pattern: Recursion / Backtracking
|--------------------------------------------------------------------------
*/

/*
Problem Description:
ADD 1 TO NUMBER REPRESENTED AS LINKED LIST
Intuition — Recursive carry from tail:
  Recurse to the last node first (rightmost digit).
  Add carry=1; propagate carry back through the list.
  If carry remains after head, prepend Node(1).

Dry Run: list=9→9→9 (represents 999)
  Recurse to tail Node(9): 9+1=10 → data=0, carry=1
  Back at mid  Node(9): 9+1=10 → data=0, carry=1
  Back at head Node(9): 9+1=10 → data=0, carry=1
  carry=1 → prepend Node(1)
  Result: 1 → 0 → 0 → 0 → NULL (represents 1000)  ✓

TC: O(N)  SC: O(N) — recursion stack

Example:
Input: list = 9→9→9 (represents 999)
Output: 1→0→0→0 (represents 1000)

Why This Pattern:
The carry is propagated by recursing all the way to the tail first and only applying +1 as each call returns back up the stack - a direct, explicit application of the Recursion pattern to a problem that would otherwise need a reversed list or an explicit stack.
*/

    private function addOneHelper(?Node $node): int
    {
        if ($node === null) return 1; // Base: initial carry of 1

        $carry       = $this->addOneHelper($node->next); // Recurse to tail first
        $node->data += $carry;

        if ($node->data < 10) {
            return 0; // No overflow → carry stops here
        } else {
            $node->data = $node->data % 10; // Keep single digit
            return 1;                        // Propagate carry upward
        }
    }

    public function addOneToList(): void
    {
        $carry = $this->addOneHelper($this->head);

        if ($carry !== 0) {
            // Entire number rolled over (e.g. 999 → 1000)
            $newNode       = new Node(1);
            $newNode->next = $this->head;
            $this->head    = $newNode;
        }
    }


/*
|--------------------------------------------------------------------------
| Problem: Print All Subsequences (Pick / Not-Pick)
| Source: DS/10. Recursion/Advance_Recursion.php
| Pattern: Recursion / Backtracking
|--------------------------------------------------------------------------
*/

/*
Problem Description:
============================================================
TEACHING EXAMPLE: Double Recursive Pow — WHY IT'S BAD
============================================================
Calling myRecursivePow TWICE with the same half problem doubles
the work at every level: T(n) = 2T(n/2) → O(N) by Master Theorem.
Compare to single-call: T(n) = T(n/2) → O(log N).
Fix: store the half result in a variable before using it twice.

BAD (O(N)):    $f1 = pow(x, n/2);  $f2 = pow(x, n/2); return $f1*$f2;
GOOD (O(logN)):$half = pow(x, n/2); return $half * $half;
----------------------------------------------------------


============================================================
3. PRINT ALL SUBSEQUENCES  (Pick / Not-Pick)
============================================================
Intuition:
  For every index i in [0..n-1], we make a binary choice:
    EXCLUDE arr[i] — don't add to current path
    INCLUDE arr[i] — add to current path (add, recurse, UNDO)
  When i reaches n (leaf node), print the current path.
  Total paths = 2^N (all subsets including empty set).

Dry Run: arr=[1,2,3]
  Recursion tree (X=exclude, I=include):
  i=0       X:[]  I:[1]
  i=1    X:[]  I:[2]     X:[1]  I:[1,2]
  i=2  X:[] I:[3] X:[2] I:[2,3]  X:[1] I:[1,3] X:[1,2] I:[1,2,3]
  Leaves (8 subsets): [], [3], [2], [2,3], [1], [1,3], [1,2], [1,2,3]

TC: O(2^N × N)  — 2^N subsets, each takes O(N) to print
SC: O(N)        — recursion stack depth
----------------------------------------------------------

Example:
Input: arr=[3,1,2]
Output: [3,1,2] [3,1] [3,2] [3] [1,2] [1] [2] []  (8 = 2^3 subsequences)

Why This Pattern:
At each index, explicitly branch into 'include this element' and 'exclude this element' before recursing on the next index - the handbook's own named Subsets Template (Section 5.3, Include/Exclude), the foundational building block every other subset/subsequence problem in this pattern is built from.
*/

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


/*
|--------------------------------------------------------------------------
| Problem: Print All Subsequences With Sum = K
| Source: DS/10. Recursion/Advance_Recursion.php
| Pattern: Recursion / Backtracking
|--------------------------------------------------------------------------
*/

/*
Problem Description:
============================================================
4. PRINT ALL SUBSEQUENCES WITH SUM = K
============================================================
Intuition:
  Same pick/not-pick tree as above, but at the leaf node
  we FILTER: only print if the accumulated sum equals target.

  Optimisation (positive-only arrays): if running sum already
  exceeds target, prune the entire subtree (can't go back).

Dry Run: arr=[1,2,3], target=3
  All subsets and their sums:
    []      → 0   ✗
    [3]     → 3   ✓ print
    [2]     → 2   ✗
    [2,3]   → 5   ✗
    [1]     → 1   ✗
    [1,3]   → 4   ✗
    [1,2]   → 3   ✓ print
    [1,2,3] → 6   ✗
  Output: [3], [1,2]

TC: O(2^N × N)  SC: O(N)
----------------------------------------------------------

Example:
Input: arr=[1,2,1], k=2
Output: [1,1] [2]

Why This Pattern:
The same pick/not-pick Subsets Template, extended with a running sum tracked through the recursion and checked against k only once a complete subsequence is formed - a direct constraint-checking variant of the include/exclude backtracking template.
*/

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


/*
|--------------------------------------------------------------------------
| Problem: Print Only One Subsequence With Sum = K (Early Exit)
| Source: DS/10. Recursion/Advance_Recursion.php
| Pattern: Recursion / Backtracking
|--------------------------------------------------------------------------
*/

/*
Problem Description:
============================================================
5. PRINT ONLY ONE SUBSEQUENCE WITH SUM = K (Early Exit)
============================================================
Intuition:
  Same as above, but STOP the moment we find ONE valid subset.
  Use a boolean RETURN VALUE to signal up the call stack:
    true  → valid subset found; propagate upward → stop all recursion
    false → not found yet; continue searching

  Critical fix: the `if(found) return TRUE` must propagate upward!
  If a child returns true, the parent must also return true immediately.

Dry Run: arr=[1,2,3], target=3
  EXCLUDE 1 → EXCLUDE 2 → EXCLUDE 3 → [] sum=0 ✗ return false
  EXCLUDE 1 → EXCLUDE 2 → INCLUDE 3 → [3] sum=3 ✓ print → return true
  true propagates all the way up → all other branches SKIPPED  ✓

TC: O(2^N) worst  SC: O(N)
----------------------------------------------------------

Example:
Input: arr=[1,2,1], k=2
Output: [1,1]  (first match found, then stop exploring)

Why This Pattern:
The same include/exclude backtracking template as above, with an early-exit (return true up the call stack once one valid subsequence is found) - the classic pruning optimization the handbook calls out for 'print one solution' style backtracking variants.
*/

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


/*
|--------------------------------------------------------------------------
| Problem: Count Subsequences With Sum = K
| Source: DS/10. Recursion/Advance_Recursion.php
| Pattern: Recursion / Backtracking
|--------------------------------------------------------------------------
*/

/*
Problem Description:
============================================================
6. COUNT SUBSEQUENCES WITH SUM = K
============================================================
Intuition:
  Instead of printing, RETURN the COUNT of valid subsets.
  At each leaf: return 1 if sum==target, else 0.
  Internal nodes: return left_count + right_count.

  This is a pure functional recursion — no global variable needed.
  The count bubbles UP the call stack through return values.

Dry Run: arr=[1,2,3], target=3
  Each leaf contributes 1 (if sum=3) or 0:
    [] →0, [3]→1, [2]→0, [2,3]→0, [1]→0, [1,3]→0, [1,2]→1, [1,2,3]→0
  Total = 0+1+0+0+0+0+1+0 = 2  ✓

TC: O(2^N)  SC: O(N)
----------------------------------------------------------

Example:
Input: arr=[1,2,1], k=2
Output: 2  ([1,1] and [2])

Why This Pattern:
The same include/exclude backtracking template again, this time summing 1s from completed valid branches instead of printing or early-exiting - the 'count instead of enumerate' variant of the same exhaustive decision-tree exploration.
*/

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


/*
|--------------------------------------------------------------------------
| Problem: Combination Sum (LC 39)
| Source: DS/10. Recursion/Advance_Recursion.php
| Pattern: Recursion / Backtracking
|--------------------------------------------------------------------------
*/

/*
Problem Description:
============================================================
7. COMBINATION SUM  (LeetCode 39)
============================================================
Intuition:
  Find all UNIQUE combinations of $candidates that sum to $target.
  Each element may be used UNLIMITED times (no index increment on pick).

  Key difference from subset problems:
    - INCLUDE: stay at the SAME index $i (allow reuse of arr[i]).
    - EXCLUDE: move to the NEXT index $i+1 (skip arr[i] forever).

  Base cases:
    target == 0 → found a valid combination → store it.
    i >= n OR target < 0 → dead end → return.

Dry Run: candidates=[2,3,6,7], target=7
  Branch PICK 2: [2] target=5
    PICK 2: [2,2] target=3
      PICK 2: [2,2,2] target=1
        PICK 2: [2,2,2,2] target=-1 → prune
        SKIP 2: try 3: [2,2,2,3] target=-2 → prune ... no result
      SKIP 2: try 3: [2,2,3] target=0 → ✓ store [2,2,3]
    SKIP 2: try 3: [2,3] target=2
      PICK 3: target=-1 → prune; SKIP 3: try 6 →prune; SKIP: try 7→prune
  ... SKIP 2: try 3: [3] target=4
    PICK 3: [3,3] target=1 → prune chain
    SKIP 3: try 6 →prune; try 7→[7] target=0 → ✓ store [7]
  ... SKIP 3: try 6 → prune; try 7 → ✓ store [7] (already found via other path)
  Result: [[2,2,3], [7]]  ✓

TC: O(2^(target/min) × target/min)  SC: O(target/min) recursion depth
----------------------------------------------------------

Example:
Input: candidates=[2,3,6,7], target=7
Output: [[2,2,3],[7]]

Why This Pattern:
The handbook lists Combination Sum by name as a core Medium problem: 'Backtracking with reusable elements' - on PICK, the recursion stays at the same index i (instead of advancing to i+1) since an element may be reused unlimited times, the key variation that distinguishes it from Combination Sum II.
*/

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


/*
|--------------------------------------------------------------------------
| Problem: Subset Sums - All Unique Sums (Striver's Problem)
| Source: DS/10. Recursion/Advance_Recursion.php
| Pattern: Recursion / Backtracking
|--------------------------------------------------------------------------
*/

/*
Problem Description:
============================================================
8. SUBSET SUMS — ALL UNIQUE SUMS  (Striver's Problem)
============================================================
Intuition:
  Compute the SUM of every possible subset and return all sums SORTED.
  We don't collect the elements — just the running sum.
  At each leaf, store $sum in the results array.

  Why store the sum (not the subset)?
    We only care WHAT the sums are, not WHICH elements form them.
    This simplifies the state: just pass a single integer $sum.

Dry Run: arr=[2,5,8], all 8 subsets and their sums:
  []→0, [8]→8, [5]→5, [5,8]→13, [2]→2, [2,8]→10, [2,5]→7, [2,5,8]→15
  Sorted: [0, 2, 5, 7, 8, 10, 13, 15]  ✓

TC: O(2^N + 2^N × log(2^N)) = O(2^N × N)  SC: O(2^N) for result storage
----------------------------------------------------------

Example:
Input: arr=[3,1,2]
Output: [0,3,1,4,2,5,3,6] (sum of every one of the 2^3 subsets, unsorted)

Why This Pattern:
The same pick/not-pick Subsets Template, but instead of collecting each subset itself, only the running sum is recorded once a complete subsequence is formed - a minimal-output variant of the foundational Subsets Template.
*/

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


/*
|--------------------------------------------------------------------------
| Problem: Subsets II - Unique Subsets From Array With Duplicates (LC 90)
| Source: DS/10. Recursion/Advance_Recursion.php
| Pattern: Recursion / Backtracking
|--------------------------------------------------------------------------
*/

/*
Problem Description:
============================================================
9. SUBSETS II — UNIQUE SUBSETS FROM ARRAY WITH DUPLICATES  (LeetCode 90)
============================================================
Intuition:
  Given a sorted array with possible duplicates, generate all
  UNIQUE subsets (no two subsets should be identical).

  Approach: SORT the array, then use a for-loop recursion
  (instead of pick/not-pick). At each level, iterate over
  CHOICES for the NEXT element in the subset.

  Key deduplication rule (applied to SIBLINGS at same recursion level):
    If arr[j] == arr[j-1] AND j > start → SKIP (it's a duplicate sibling).
    Only skip when j > start (not when j == start — first occurrence is fine).

  Why for-loop approach?
    Pick/not-pick with sorting works too, but the for-loop approach
    makes deduplication at the SAME LEVEL extremely clean and natural.

Dry Run: nums=[1,2,2], start=0, temp=[]
  j=0: pick 1 → recurse(start=1, temp=[1])
    j=1: pick 2 → recurse(start=2, temp=[1,2])
      j=2: 2==nums[1]=2 AND j(2) > start(2)? NO (j==start) → pick 2
        recurse(start=3) → base → store [1,2,2]
    j=2: nums[2]=2 == nums[1]=2 AND j(2) > start(1)? YES → SKIP ✓
  j=1: pick 2 → recurse(start=2, temp=[2])
    j=2: 2==nums[1]=2 AND j(2) > start(2)? NO → pick 2 → store [2,2]
  j=2: nums[2]=2 == nums[1]=2 AND j(2) > start(0)? YES → SKIP ✓
  Result: [], [1], [1,2], [1,2,2], [2], [2,2]  ✓  (no duplicate [1,2])

TC: O(2^N × N)  SC: O(N) recursion depth + O(2^N × N) result storage
----------------------------------------------------------

Example:
Input: nums=[1,2,2]
Output: [[],[1],[1,2],[1,2,2],[2],[2,2]]

Why This Pattern:
The handbook lists Subsets II by name as a core Medium problem: 'Duplicate-skipping backtracking' - the array is sorted first, then at each recursion level a duplicate value is skipped unless it is the first occurrence at that level, the standard duplicate-handling variant of the Subsets Template.
*/

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

