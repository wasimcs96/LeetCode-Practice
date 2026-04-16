<?php

// ============================================================
//  TOPIC: Recursion
//  ============================================================
//  Recursion is a technique where a function calls itself to
//  solve a smaller sub-problem of the original problem.
//
//  Every recursive function needs:
//    1. Base Case  → the condition to STOP recursion
//    2. Recursive Case → the call that reduces the problem
//
//  Mental model:
//    - Trust the recursion: assume the function works correctly
//      for smaller inputs and build the answer from that.
//    - Draw a recursion tree to visualise the call stack.
//
//  Stack memory note:
//    Each recursive call occupies a stack frame.
//    Too many calls → Stack Overflow.
// ============================================================


// ============================================================
// 1. PRINT NAME N TIMES
// ============================================================
// Problem: Print "Name" n times using recursion (no loop).
//
// Approach:
//   - Base case : n == 0 → stop
//   - Print, then recurse with n-1
//
// Time Complexity : O(N)
// Space Complexity: O(N) — call stack depth
// ============================================================

function printName(int $n): void
{
    if ($n === 0) return;          // Base case: nothing left to print

    echo "Wasim\n";
    printName($n - 1);             // Recurse with a smaller value
}

/*
 * Dry Run: printName(3)
 *   printName(3) → prints "Wasim", calls printName(2)
 *   printName(2) → prints "Wasim", calls printName(1)
 *   printName(1) → prints "Wasim", calls printName(0)
 *   printName(0) → base case, returns
 *
 * Output: Wasim  Wasim  Wasim
 */
printName(3);
echo "\n";


// ============================================================
// 2. PRINT N DOWN TO 1  (Back-tracking style)
// ============================================================
// Problem: Print N, N-1, N-2 … 1 using recursion.
//
// Approach:
//   - Base case : n == 0 → stop
//   - Print n BEFORE the recursive call → prints in forward order
//     as the call stack descends (N → 1).
//
// Time Complexity : O(N)
// Space Complexity: O(N)
// ============================================================

function printNtoOne(int $n): void
{
    if ($n === 0) return;           // Base case

    echo $n . " ";
    printNtoOne($n - 1);            // Recurse with smaller value
}

/*
 * Dry Run: printNtoOne(4)
 *   printNtoOne(4) → prints 4, calls printNtoOne(3)
 *   printNtoOne(3) → prints 3, calls printNtoOne(2)
 *   printNtoOne(2) → prints 2, calls printNtoOne(1)
 *   printNtoOne(1) → prints 1, calls printNtoOne(0)
 *   printNtoOne(0) → base case, returns
 *
 * Output: 4 3 2 1
 */
echo "N to 1: ";
printNtoOne(4);
echo "\n\n";


// ============================================================
// 3. PRINT 1 TO N  (using backtracking trick)
// ============================================================
// Problem: Print 1, 2, 3 … N using recursion.
//
// Key Insight:
//   Print AFTER the recursive call → the print happens on the
//   way BACK up the call stack, which reverses the order.
//
// Time Complexity : O(N)
// Space Complexity: O(N)
// ============================================================

function printOneToN(int $n): void
{
    if ($n === 0) return;           // Base case

    printOneToN($n - 1);            // Go all the way down first
    echo $n . " ";                  // Print on the way back up
}

/*
 * Dry Run: printOneToN(4)
 *   Stack builds: printOneToN(4) → (3) → (2) → (1) → (0) returns
 *   Unwind: prints 1, then 2, then 3, then 4
 *
 * Output: 1 2 3 4
 */
echo "1 to N: ";
printOneToN(4);
echo "\n\n";


// ============================================================
// 4. SUM OF FIRST N NATURAL NUMBERS
// ============================================================
// Problem: Find sum 1 + 2 + … + N.
//
// --- Approach A: Parametrised (pass sum as accumulator) ---
// Accumulator carries the running total downward.
//
// Time Complexity : O(N)
// Space Complexity: O(N)
// ============================================================

function sumParametrised(int $n, int $sum = 0): int
{
    if ($n < 1) return $sum;        // Base case: return accumulated sum

    return sumParametrised($n - 1, $sum + $n);  // Add current n to sum
}

// --- Approach B: Functional (return value bubbles back up) ---
// Trust that sumFunctional(n-1) gives the correct sum up to n-1,
// then add n on top of it.
function sumFunctional(int $n): int
{
    if ($n < 1) return 0;           // Base case: sum of nothing is 0

    return $n + sumFunctional($n - 1);  // n + sum(n-1)
}

/*
 * Dry Run (Functional): sumFunctional(4)
 *   sumFunctional(4) = 4 + sumFunctional(3)
 *   sumFunctional(3) = 3 + sumFunctional(2)
 *   sumFunctional(2) = 2 + sumFunctional(1)
 *   sumFunctional(1) = 1 + sumFunctional(0)
 *   sumFunctional(0) = 0    ← base case
 *   Unwind: 1+0=1, 2+1=3, 3+3=6, 4+6=10
 *
 * Output: 10
 */
echo "Sum (parametrised) of 1..5 = " . sumParametrised(5) . "\n";
echo "Sum (functional)   of 1..5 = " . sumFunctional(5) . "\n\n";


// ============================================================
// 5. FACTORIAL OF A NUMBER
// ============================================================
// Problem: Compute N! = N × (N-1) × … × 1.
//
// Approach:
//   - Base case : n == 0 → return 1  (0! = 1 by definition)
//   - Recursive : n × factorial(n-1)
//
// Time Complexity : O(N)
// Space Complexity: O(N)
// ============================================================

function factorial(int $n): int
{
    if ($n === 0) return 1;         // Base case: 0! = 1

    return $n * factorial($n - 1);  // N! = N × (N-1)!
}

/*
 * Dry Run: factorial(4)
 *   factorial(4) = 4 × factorial(3)
 *   factorial(3) = 3 × factorial(2)
 *   factorial(2) = 2 × factorial(1)
 *   factorial(1) = 1 × factorial(0)
 *   factorial(0) = 1    ← base case
 *   Unwind: 1×1=1, 2×1=2, 3×2=6, 4×6=24
 *
 * Output: 24
 */
echo "Factorial(5) = " . factorial(5) . "\n\n";


// ============================================================
// 6. REVERSE AN ARRAY  (Two-pointer recursion)
// ============================================================
// Problem: Reverse an array in-place using recursion.
//
// Approach:
//   - Use two pointers: $i (start) and $j (end).
//   - Swap elements at $i and $j, then move pointers inward.
//   - Base case: $i >= $j → pointers have crossed, done.
//
// Note: Array is passed by reference (&$arr) so swaps persist.
//
// Time Complexity : O(N)
// Space Complexity: O(N) — call stack depth N/2
// ============================================================

function reverseArray(array &$arr, int $i, int $j): void
{
    if ($i >= $j) return;           // Base case: pointers crossed

    // Swap elements at positions i and j
    [$arr[$i], $arr[$j]] = [$arr[$j], $arr[$i]];

    // Move both pointers inward and recurse
    reverseArray($arr, $i + 1, $j - 1);
}

/*
 * Dry Run: reverseArray([1,2,3,4,5], 0, 4)
 *   i=0, j=4 → swap(1,5) → [5,2,3,4,1], recurse(1,3)
 *   i=1, j=3 → swap(2,4) → [5,4,3,2,1], recurse(2,2)
 *   i=2, j=2 → base case (i >= j), return
 *
 * Output: [5, 4, 3, 2, 1]
 */
$arr = [1, 2, 3, 4, 5];
reverseArray($arr, 0, count($arr) - 1);
echo "Reversed array: ";
echo implode(", ", $arr) . "\n\n";


// ============================================================
// 7. CHECK PALINDROME STRING
// ============================================================
// Problem: Check if a string is a palindrome using recursion.
//
// Approach:
//   - Use two-pointer technique on character array.
//   - Compare characters at $i and $n-$i-1 (mirror positions).
//   - Base case: $i >= n/2 → all characters matched → true.
//   - Mismatch at any step → false immediately.
//
// Time Complexity : O(N)
// Space Complexity: O(N)
// ============================================================

function isPalindromeStr(array $chars, int $i, int $n): bool
{
    // Base case: checked all mirror pairs → it's a palindrome
    if ($i >= (int)($n / 2)) return true;

    // If characters at mirror positions don't match → not palindrome
    if ($chars[$i] !== $chars[$n - $i - 1]) return false;

    // Move inward and check the next pair
    return isPalindromeStr($chars, $i + 1, $n);
}

/*
 * Dry Run: "racecar"  → chars = ['r','a','c','e','c','a','r'], n=7
 *   i=0: chars[0]='r', chars[6]='r' → match, recurse(1)
 *   i=1: chars[1]='a', chars[5]='a' → match, recurse(2)
 *   i=2: chars[2]='c', chars[4]='c' → match, recurse(3)
 *   i=3: 3 >= 7/2=3 → base case → return true
 *
 * Dry Run: "hello"   → chars = ['h','e','l','l','o'], n=5
 *   i=0: chars[0]='h', chars[4]='o' → mismatch → return false
 */
$tests = ["racecar", "hello", "madam", "abcba", "abcd"];
foreach ($tests as $str) {
    $chars  = str_split($str);
    $result = isPalindromeStr($chars, 0, count($chars));
    echo "\"$str\" is" . ($result ? "" : " NOT") . " a palindrome\n";
}
echo "\n";


// ============================================================
// 8. FIBONACCI SERIES
// ============================================================
// The Fibonacci sequence: 0, 1, 1, 2, 3, 5, 8, 13, 21 …
// F(0)=0, F(1)=1, F(N) = F(N-1) + F(N-2)
//
// --- Approach A: Build series array iteratively ---
// Useful when you need all N terms at once.
//
// Time Complexity : O(N)
// Space Complexity: O(N)
// ============================================================

function buildFibSeries(int $a, int $b, int $n, array &$result): void
{
    // Base case: we have collected exactly n terms
    if (count($result) === $n) return;

    $next = $a + $b;                     // Next Fibonacci number
    $result[] = $next;                   // Add it to the result array

    buildFibSeries($b, $next, $n, $result);  // Slide the window forward
}

/*
 * Dry Run: buildFibSeries(0,1,6,[0,1])
 *   result=[0,1], next=0+1=1 → result=[0,1,1], call(1,1)
 *   result=[0,1,1], next=1+1=2 → result=[0,1,1,2], call(1,2)
 *   result=[0,1,1,2], next=1+2=3 → result=[0,1,1,2,3], call(2,3)
 *   result=[0,1,1,2,3], next=2+3=5 → result=[0,1,1,2,3,5], count=6 → stop
 *
 * Output: 0, 1, 1, 2, 3, 5
 */
$fibSeries = [0, 1];
buildFibSeries(0, 1, 8, $fibSeries);
echo "Fibonacci series (8 terms): " . implode(", ", $fibSeries) . "\n\n";


// ============================================================
// --- Approach B: Find the Nth Fibonacci number ---
//
// Simple recursive definition of Fibonacci.
//
// ⚠ Warning: Very inefficient — overlapping subproblems.
//   The same sub-results are computed multiple times.
//   Use Memoization (DP) to optimise (see section 9).
//
// Time Complexity : O(2^N) — exponential (two branches per call)
// Space Complexity: O(N)   — max call stack depth at any time
// ============================================================

function fibonacci(int $n): int
{
    // Base cases: F(0) = 0, F(1) = 1
    if ($n === 0 || $n === 1) return $n;

    // F(N) = F(N-1) + F(N-2)
    return fibonacci($n - 1) + fibonacci($n - 2);
}

/*
 * Dry Run: fibonacci(5)
 * Recursion tree (partial):
 *                  fib(5)
 *               /         \
 *           fib(4)       fib(3)
 *          /     \       /    \
 *       fib(3) fib(2) fib(2) fib(1)
 *       ...
 * fib(1)=1, fib(0)=0 are the leaves.
 * Computed value: 0,1,1,2,3,5 → fib(5) = 5
 *
 * Output: 5
 */
echo "fibonacci(7) = " . fibonacci(7) . "\n\n";


// ============================================================
// 9. FIBONACCI WITH MEMOIZATION (Top-Down DP)
// ============================================================
// Problem: Same as above but optimised by caching results.
//
// Key Idea:
//   Store already-computed values in a lookup table ($memo).
//   Before computing, check if the result is already cached.
//   This eliminates redundant recursive calls.
//
// Time Complexity : O(N) — each subproblem computed once
// Space Complexity: O(N) — memo array + call stack
// ============================================================

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

/*
 * Dry Run: fibMemo(5)
 *   fibMemo(5) = fibMemo(4) + fibMemo(3)
 *   fibMemo(4) = fibMemo(3) + fibMemo(2)  → memo[4] = 3
 *   fibMemo(3) = fibMemo(2) + fibMemo(1)  → memo[3] = 2
 *      (fibMemo(3) for fib(5) now returns from cache: 2)
 *   fibMemo(2) = fibMemo(1) + fibMemo(0)  → memo[2] = 1
 *   fibMemo(5) = 3 + 2 = 5
 *
 * Output: 5  (computed in O(N) instead of O(2^N))
 */
echo "fibMemo(10) = " . fibMemo(10) . "\n\n";


// ============================================================
// 10. LEETCODE 125 — VALID PALINDROME
// ============================================================
// Problem: Given a string s, return true if it is a palindrome
// after keeping only alphanumeric characters and lowercasing.
//
// Example: "A man, a plan, a canal: Panama" → true
//          "race a car"                     → false
//
// Approach:
//   Step 1: Filter out non-alphanumeric characters & lowercase.
//   Step 2: Use recursive two-pointer palindrome check.
//
// Time Complexity : O(N)
// Space Complexity: O(N)
// ============================================================

function checkPalindromeRecursive(array $arr, int $i, int $j): bool
{
    if ($i >= $j) return true;                         // Pointers crossed → palindrome

    if ($arr[$i] !== $arr[$j]) return false;           // Mismatch found

    return checkPalindromeRecursive($arr, $i + 1, $j - 1);  // Check inner part
}

function isValidPalindrome(string $s): bool
{
    if (empty($s)) return true;

    // Keep only alphanumeric characters, convert to lowercase
    $filtered = [];
    foreach (str_split($s) as $ch) {
        if (ctype_alnum($ch)) {
            $filtered[] = strtolower($ch);
        }
    }

    $len = count($filtered);
    if ($len <= 1) return true;                        // Empty or single char → palindrome

    return checkPalindromeRecursive($filtered, 0, $len - 1);
}

/*
 * Dry Run: isValidPalindrome("A man, a plan, a canal: Panama")
 *   After filter: ['a','m','a','n','a','p','l','a','n','a','c','a','n','a','l','p','a','n','a','m','a']
 *   Two-pointer check: 'a'=='a', 'm'=='m', 'a'=='a' … all match → true
 *
 * Dry Run: isValidPalindrome("race a car")
 *   After filter: ['r','a','c','e','a','c','a','r']
 *   i=0,j=7: 'r'=='r' ✓
 *   i=1,j=6: 'a'=='a' ✓
 *   i=2,j=5: 'c'=='c' ✓
 *   i=3,j=4: 'e'!='a' → false
 */
echo "Valid Palindrome 'A man, a plan, a canal: Panama': ";
var_dump(isValidPalindrome("A man, a plan, a canal: Panama"));

echo "Valid Palindrome 'race a car': ";
var_dump(isValidPalindrome("race a car"));
echo "\n";


// ============================================================
//  ADDITIONAL PRACTICE PROBLEMS
// ============================================================
//
//  Beginner:
//  1.  Power function: Compute x^n using recursion.
//  2.  Sum of digits: sum all digits of a number (e.g., 1234→10).
//  3.  Count zeros: count zeros in a number recursively.
//  4.  GCD (Euclidean algorithm): gcd(a,b) = gcd(b, a%b).
//  5.  Binary search using recursion.
//
//  Intermediate:
//  6.  Tower of Hanoi: classic recursion problem.
//  7.  Generate all subsets (power set) of an array.
//  8.  Generate all permutations of a string/array.
//  9.  Merge Sort & Quick Sort (divide and conquer).
//  10. Count ways to climb stairs (1 or 2 steps at a time).
//
//  Advanced:
//  11. N-Queens problem.
//  12. Sudoku Solver using backtracking.
//  13. Word Break Problem.
//  14. Generate balanced parentheses.
//  15. Rat in a Maze (backtracking).
//
// ============================================================
//  KEY PATTERNS & VARIATIONS
// ============================================================
//
//  Pattern 1 — Print before recurse   : top-down  (N → 1)
//  Pattern 2 — Print after recurse    : bottom-up (1 → N, backtracking)
//  Pattern 3 — Parametrised recursion : carry state in parameters
//  Pattern 4 — Functional recursion   : build answer from return value
//  Pattern 5 — Two-pointer recursion  : shrink problem from both ends
//  Pattern 6 — Multiple recursive calls: trees, Fibonacci, subsets
//  Pattern 7 — Backtracking           : try → recurse → undo (restore state)
//  Pattern 8 — Memoization            : cache results to avoid recomputation
//
// ============================================================
//  IMPORTANT TIPS & EDGE CASES
// ============================================================
//
//  Tips:
//  1. ALWAYS define the base case first — missing it causes infinite recursion.
//  2. Ensure each recursive call moves TOWARD the base case.
//  3. Use pass-by-reference (&$var) in PHP when the function must
//     modify the original array or collect results.
//  4. For problems with overlapping subproblems, add memoization immediately.
//  5. Draw the recursion tree for any problem you can't visualise mentally.
//
//  Edge Cases:
//  - n = 0  → must be handled by base case (empty input).
//  - n = 1  → single element; many two-pointer algorithms must handle this.
//  - Negative input → add guard at the call site or inside the function.
//  - Large n (e.g., n > 10,000) → recursive solution may cause stack overflow;
//    consider iterative or tail-call approaches.
//  - Even vs. Odd length arrays/strings → floor(n/2) handles both correctly.
//  - Palindrome edge cases: empty string, single char, all same characters.
//
// ============================================================