<?php

/*
|--------------------------------------------------------------------------
| Problem: Remove Duplicates from Sorted Array (LC 26)
| Source: DS/3. Array/Array_enhancement.php
| Pattern: Two Pointers
|--------------------------------------------------------------------------
*/

/*
Problem Description:
 What: given a sorted array, remove duplicates in-place so each unique
       element appears once, and return the count of unique elements.
       The first `k` slots of the array must hold the unique values.
 Why it exists: teaches the "slow/fast pointer" in-place compaction
       technique that reappears constantly (Move Zeroes, Remove Element,
       Duplicate Zeros, etc.).

Example:
Input: nums = [-4,-4,0,0,1,1,1,2,2,3,3,4] (sorted)
Output: 6 unique values written to the front: [-4,0,1,2,3,4]

Why This Pattern:
A slow pointer marks the last confirmed-unique slot while a fast pointer scans ahead; this is the textbook same-direction Two Pointers technique for in-place compaction on a sorted array.
*/

function removeDuplicates(array &$nums): int {
    if (count($nums) === 0) return 0;   // Guard: an empty array has 0 unique elements

    $i = 0;   // Points to the last written unique element

    for ($j = 1; $j < count($nums); $j++) {
        if ($nums[$j] !== $nums[$i]) {   // Strict !== avoids PHP type-juggling surprises
            $i++;
            $nums[$i] = $nums[$j];       // Compact the unique value into the next free slot
        }
        // else: nums[j] is a duplicate of nums[i] -- simply skip it, $i does not move
    }

    return $i + 1;   // $i is a 0-based index of the last unique slot -> length = i+1
}


/*
|--------------------------------------------------------------------------
| Problem: Rotate Array by K Positions (LC 189)
| Source: DS/3. Array/Array_enhancement.php
| Pattern: Two Pointers
|--------------------------------------------------------------------------
*/

/*
Problem Description:
 What: shift every element k positions to the right, wrapping around.
 Why it exists: the reversal trick is a widely reusable O(1)-space,
   O(n)-time technique for cyclic rearrangement -- same core idea powers
   Next Permutation's suffix-reverse step (Problem 19).

Example:
Input: nums = [1,2,3,4,5,6,7], k = 3
Output: [5,6,7,1,2,3,4]

Why This Pattern:
The O(1)-space rotation is built entirely from reversals, each performed by two pointers converging from the ends toward the middle - the same converging-pointer mechanic used in basic array/string reversal.
*/

function rotateWithExtraArray(array $nums, int $k): array {
    $n = count($nums);
    if ($n === 0) return $nums;
    $k = $k % $n;                       // Reduce k in case k > n
    $result = array_fill(0, $n, 0);

    for ($i = 0; $i < $n; $i++) {
        $result[($i + $k) % $n] = $nums[$i];   // Each element's new home, wrapping via modulo
    }

    return $result;
}

function reverseSegment(array &$arr, int $start, int $end): void {
    while ($start < $end) {
        [$arr[$start], $arr[$end]] = [$arr[$end], $arr[$start]];
        $start++;
        $end--;
    }
}

function rotateRight(array &$nums, int $k): void {
    $n = count($nums);
    if ($n === 0) return;
    $k = $k % $n;                        // CRITICAL: k can exceed n; k=n means "no rotation"
    if ($k === 0) return;

    // Step 1: reverse the first (n-k) elements -- these are the elements
    //         that will END UP at the back after rotation.
    reverseSegment($nums, 0, $n - $k - 1);

    // Step 2: reverse the last k elements -- these will END UP at the front.
    reverseSegment($nums, $n - $k, $n - 1);

    // Step 3: reverse the entire array -- this "flips" both already-reversed
    //         segments back into correct internal order while keeping them
    //         in their new (swapped) positions.
    reverseSegment($nums, 0, $n - 1);
}


/*
|--------------------------------------------------------------------------
| Problem: Move Zeroes to End (LC 283)
| Source: DS/3. Array/Array_enhancement.php
| Pattern: Two Pointers
|--------------------------------------------------------------------------
*/

/*
Problem Description:
 What: push all zeroes to the end while keeping non-zero elements in their
       original relative order.
 Why it exists: a very common "compact while preserving order" building
   block, e.g., filtering nulls from a data pipeline in-place.

Example:
Input: nums = [0,1,0,3,12]
Output: [1,3,12,0,0]

Why This Pattern:
A slow pointer tracks the next free non-zero slot while a fast pointer scans forward, swapping in place - the classic same-direction Two Pointers compaction pattern (same family as Remove Duplicates).
*/

function moveZeroes(array &$nums): void {
    $i = 0;   // Next slot that should hold a non-zero value

    for ($j = 0; $j < count($nums); $j++) {
        if ($nums[$j] !== 0) {                          // Found a non-zero element
            [$nums[$i], $nums[$j]] = [$nums[$j], $nums[$i]];   // Swap it into place
            $i++;
        }
    }
    // Micro-optimization note: when i === j the swap is a harmless no-op
    // (swapping an element with itself); some implementations add an
    // `if ($i !== $j)` guard to skip that redundant swap, but it does not
    // change correctness or asymptotic complexity.
}


/*
|--------------------------------------------------------------------------
| Problem: Union of Two Sorted Arrays
| Source: DS/3. Array/Array_enhancement.php
| Pattern: Two Pointers
|--------------------------------------------------------------------------
*/

/*
Problem Description:
 What: given two SORTED arrays (possibly with internal duplicates),
       return their sorted union (each value appearing once).
 Why it exists: the merge-step of Merge Sort is one of the most reused
   primitives in DSA (external sorting, k-way merge, interval merging).

Example:
Input: arr1 = [1,2,3,4,5], arr2 = [2,3,4,4,5,11,12]
Output: [1,2,3,4,5,11,12]

Why This Pattern:
Both sorted arrays are walked simultaneously with one pointer each (a tandem merge, like the merge step of Merge Sort), always advancing whichever pointer currently holds the smaller value.
*/

function findUnion(array $arr1, array $arr2): array {
    $union = [];
    $n1 = count($arr1);
    $n2 = count($arr2);
    $i = $j = 0;

    while ($i < $n1 && $j < $n2) {
        if ($arr1[$i] < $arr2[$j]) {
            $union[$arr1[$i]] = $arr1[$i];    // Value as key -> automatic O(1) dedup
            $i++;
        } elseif ($arr1[$i] > $arr2[$j]) {
            $union[$arr2[$j]] = $arr2[$j];
            $j++;
        } else {                              // Equal -- add once, advance both
            $union[$arr1[$i]] = $arr1[$i];
            $i++;
            $j++;
        }
    }

    while ($i < $n1) { $union[$arr1[$i]] = $arr1[$i]; $i++; }   // Drain leftover arr1
    while ($j < $n2) { $union[$arr2[$j]] = $arr2[$j]; $j++; }   // Drain leftover arr2

    return array_values($union);   // Re-index from 0
}


/*
|--------------------------------------------------------------------------
| Problem: Two Sum (LC 1)
| Source: DS/3. Array/Array_enhancement.php
| Pattern: Two Pointers
|--------------------------------------------------------------------------
*/

/*
Problem Description:
 What: given an array and a target, return the indices of the two
       numbers that add up to target (exactly one solution guaranteed).
 Why it exists: the single most-repeated interview warm-up question --
   tests whether you instinctively reach for a HashMap to turn an O(n^2)
   all-pairs check into O(n).

Example:
Input: nums = [2,7,11,15], target = 9
Output: [0,1]  (nums[0] + nums[1] = 2 + 7 = 9)

Why This Pattern:
When the array is already sorted, two pointers converging from both ends toward the middle find the pair in O(1) extra space instead of the O(n) space a hashmap needs.
*/

function twoSumBrute(array $nums, int $target): array {
    for ($i = 0; $i < count($nums); $i++) {
        for ($j = $i + 1; $j < count($nums); $j++) {
            if ($nums[$i] + $nums[$j] === $target) return [$i, $j];
        }
    }
    return [];
}

function twoSum(array $nums, int $target): array {
    $seenValueToIndex = [];   // value -> index of a previously seen element

    for ($i = 0; $i < count($nums); $i++) {
        $complement = $target - $nums[$i];
        if (isset($seenValueToIndex[$complement])) {
            return [$seenValueToIndex[$complement], $i];   // Found: earlier index first, current index second
        }
        $seenValueToIndex[$nums[$i]] = $i;   // Record this value so a LATER element can find it as a complement
    }

    return [];   // No solution (won't happen given LC1's guarantee)
}

function twoSumExists(array $nums, int $target): bool {
    sort($nums);   // NOTE: destroys original index order -- only use when indices are not needed
    $left = 0;
    $right = count($nums) - 1;

    while ($left < $right) {
        $sum = $nums[$left] + $nums[$right];
        if ($sum === $target)      return true;
        elseif ($sum < $target)    $left++;    // Sum too small -> need a bigger left value
        else                       $right--;    // Sum too big -> need a smaller right value
    }

    return false;
}


/*
|--------------------------------------------------------------------------
| Problem: Sort Colors - Dutch National Flag (LC 75)
| Source: DS/3. Array/Array_enhancement.php
| Pattern: Two Pointers
|--------------------------------------------------------------------------
*/

/*
Problem Description:
 What: sort an array containing only 0s, 1s, and 2s in a single pass,
       in-place, without using a library sort.
 Why it exists: the "3-way partition" is the same core idea used inside
   Quicksort's partition step when handling duplicate pivot values --
   mastering it here pays off broadly.

Example:
Input: nums = [2,0,2,1,1,0]
Output: [0,0,1,1,2,2]

Why This Pattern:
Three pointers (low/mid/high) partition the array into <, ==, > regions in a single pass - the classic Dutch National Flag three-pointer specialization of the Two Pointers pattern.
*/

function sortColorsCounting(array &$nums): void {
    $counts = [0, 0, 0];
    foreach ($nums as $num) $counts[$num]++;    // Pass 1: tally

    $idx = 0;
    for ($color = 0; $color <= 2; $color++) {
        for ($c = 0; $c < $counts[$color]; $c++) {
            $nums[$idx++] = $color;             // Pass 2: overwrite in sorted order
        }
    }
}

function sortColors(array &$nums): void {
    $low = 0;
    $mid = 0;
    $high = count($nums) - 1;

    while ($mid <= $high) {
        if ($nums[$mid] === 0) {
            [$nums[$low], $nums[$mid]] = [$nums[$mid], $nums[$low]];
            $low++;
            $mid++;              // Safe to advance: the value from `low` was already classified (0 or 1)
        } elseif ($nums[$mid] === 1) {
            $mid++;               // 1 is already in its correct zone -- just move on
        } else {                  // nums[mid] === 2
            [$nums[$high], $nums[$mid]] = [$nums[$mid], $nums[$high]];
            $high--;               // Do NOT advance mid -- the swapped-in value is unclassified, re-examine it next iteration
        }
    }
}


/*
|--------------------------------------------------------------------------
| Problem: 3Sum (LC 15) and 4Sum (LC 18)
| Source: DS/3. Array/Array_enhancement.php
| Pattern: Two Pointers
|--------------------------------------------------------------------------
*/

/*
Problem Description:
 What (3Sum): find all UNIQUE triplets [a,b,c] in the array such that
       a+b+c = 0.
 What (4Sum): find all UNIQUE quadruplets [a,b,c,d] such that a+b+c+d = target.
 Why it exists: the definitive generalization of Two Sum (Problem 13) to
   N elements -- demonstrates that "fix N-2 elements, two-pointer the
   rest" scales cleanly, and that duplicate-skipping discipline is a
   reusable skill, not a one-off trick.

Example:
3Sum: nums = [-1,0,1,2,-1,-4] -> [[-1,-1,2],[-1,0,1]]
4Sum: nums = [1,0,-1,0,-2,2], target = 0 -> at least one valid quadruplet

Why This Pattern:
After sorting, each outer loop fixes one (3Sum) or two (4Sum) elements and two pointers converge on the sorted remainder to find the rest of the sum - the standard 'fix N-2, two-pointer the rest' generalization of Two Sum.
*/

function threeSum(array $nums): array {
    sort($nums);
    $result = [];
    $n = count($nums);

    for ($i = 0; $i < $n - 2; $i++) {
        if ($i > 0 && $nums[$i] === $nums[$i - 1]) continue;   // Skip duplicate outer values (but only after the first occurrence)

        $left = $i + 1;
        $right = $n - 1;

        while ($left < $right) {
            $sum = $nums[$i] + $nums[$left] + $nums[$right];

            if ($sum > 0) {
                $right--;                 // Sum too big -> need a smaller value -> shrink from the right
            } elseif ($sum < 0) {
                $left++;                  // Sum too small -> need a bigger value -> grow from the left
            } else {
                $result[] = [$nums[$i], $nums[$left], $nums[$right]];
                $left++;
                $right--;

                // Skip duplicates for the inner pointers -- but ONLY after
                // recording a valid match, not before (otherwise valid
                // triplets sharing a repeated boundary value get skipped).
                while ($left < $right && $nums[$left] === $nums[$left - 1]) $left++;
                while ($left < $right && $nums[$right] === $nums[$right + 1]) $right--;
            }
        }
    }

    return $result;
}

function fourSum(array $nums, int $target): array {
    sort($nums);
    $result = [];
    $n = count($nums);

    for ($i = 0; $i < $n - 3; $i++) {
        if ($i > 0 && $nums[$i] === $nums[$i - 1]) continue;   // Skip duplicate 1st element

        for ($j = $i + 1; $j < $n - 2; $j++) {
            if ($j > $i + 1 && $nums[$j] === $nums[$j - 1]) continue;   // Skip duplicate 2nd element

            $left = $j + 1;
            $right = $n - 1;

            while ($left < $right) {
                // Use a wider integer type mentally here -- four large
                // values summed could overflow 32-bit systems (not a
                // concern in PHP's native 64-bit ints, but worth
                // mentioning explicitly in an interview for languages
                // where it matters).
                $sum = $nums[$i] + $nums[$j] + $nums[$left] + $nums[$right];

                if ($sum > $target) {
                    $right--;
                } elseif ($sum < $target) {
                    $left++;
                } else {
                    $result[] = [$nums[$i], $nums[$j], $nums[$left], $nums[$right]];
                    $left++;
                    $right--;

                    while ($left < $right && $nums[$left] === $nums[$left - 1]) $left++;
                    while ($left < $right && $nums[$right] === $nums[$right + 1]) $right--;
                }
            }
        }
    }

    return $result;
}


/*
|--------------------------------------------------------------------------
| Problem: Merge Two Sorted Lists (LC 21)
| Source: DS/6. LinkedList/9. Single-LL.php
| Pattern: Two Pointers
|--------------------------------------------------------------------------
*/

/*
Problem Description:
MERGE TWO SORTED LINKED LISTS (LeetCode 21)
Intuition:
  Use a dummy head to simplify edge cases.
  Compare heads of both lists; attach the smaller one.
  Append remaining nodes after one list is exhausted.

Dry Run: l1=1→3→5, l2=2→4→6
  1≤2: curr→1; 2<3: curr→2; 3≤4: curr→3
  4<5: curr→4; 5≤6: curr→5; l1 done: curr→6
  Result: 1 → 2 → 3 → 4 → 5 → 6 → NULL  ✓

TC: O(N+M)  SC: O(1) — only re-linking, no new nodes

Example:
Input: l1 = 1→2→4, l2 = 1→3→4
Output: 1→1→2→3→4→4

Why This Pattern:
Two pointers, one per list, advance in tandem and always attach whichever node currently holds the smaller value - a direct two-pointer tandem merge (contrast with Linked List Reversal: this problem restructures links without reversing anything).
*/

    public function mergeTwoSortedLists(?Node $l1, ?Node $l2): ?Node
    {
        $dummy   = new Node(-1); // Sentinel node
        $current = $dummy;

        while ($l1 !== null && $l2 !== null) {
            if ($l1->data <= $l2->data) {
                $current->next = $l1;
                $l1            = $l1->next;
            } else {
                $current->next = $l2;
                $l2            = $l2->next;
            }
            $current = $current->next;
        }

        $current->next = ($l1 !== null) ? $l1 : $l2; // Attach remaining nodes

        return $dummy->next;
    }


/*
|--------------------------------------------------------------------------
| Problem: Sort a Linked List of 0s, 1s and 2s (Dutch National Flag on a List)
| Source: DS/6. LinkedList/9. Single-LL.php
| Pattern: Two Pointers
|--------------------------------------------------------------------------
*/

/*
Problem Description:
(No written description in the source beyond the code/title itself.)

Example:
Input: list = 1→2→0→1→0→2
Output: 0→0→1→1→2→2

Why This Pattern:
Three synchronized dummy-head pointer chains (0s, 1s, 2s) partition the list into three buckets in a single pass - the linked-list analogue of the Dutch National Flag three-pointer technique used for Sort Colors on an array.
*/

    public function sortZeroOneTwoListV2(?Node $head): ?Node
    {
        
        if ($head === null || $head->next === null) return $head;

        $d0 = new Node(-1); 
        $c0 = $d0; // Dummy head for 0s
        $d1 = new Node(-1); 
        $c1 = $d1; // Dummy head for 1s
        $d2 = new Node(-1); 
        $c2 = $d2; // Dummy head for 2s

        $current = $head;
        while ($current !== null) {
            if ($current->data === 0) {
                $c0->next = $current; $c0 = $c0->next;
            } elseif ($current->data === 1) {
                $c1->next = $current; $c1 = $c1->next;
            } else {
                $c2->next = $current; $c2 = $c2->next;
            }
            $current = $current->next;
        }

        $c2->next = null;                                         // CRITICAL: terminate 2s group
        $c1->next = ($d2->next !== null) ? $d2->next : null;     // 1s → 2s (or null)
        $c0->next = ($d1->next !== null) ? $d1->next : $d2->next; // 0s → 1s (or 2s)

        $this->head = $d0->next;
        return $this->head;
    }


/*
|--------------------------------------------------------------------------
| Problem: Remove Duplicates from a Sorted Doubly Linked List
| Source: DS/6. LinkedList/10. DLL.php
| Pattern: Two Pointers
|--------------------------------------------------------------------------
*/

/*
Problem Description:
13. REMOVE DUPLICATES FROM SORTED DLL
Intuition:
  In a SORTED list, all duplicates are adjacent.
  At each node: if current->next has the same data,
    use a runner to skip ALL consecutive duplicates.
    Link current directly to the first non-duplicate node.
  Don't advance current after removing — the new current->next
  might also be a duplicate of current.

Dry Run: list=1 ↔ 1 ↔ 2 ↔ 3 ↔ 3 ↔ 3 ↔ 4
  curr=1: next->data==1 (dup). runner: 1→1→2 (stop). 1->next=2, 2->prev=1.
  curr=1: next->data==2 (ok). advance → curr=Node(2).
  curr=2: next->data==3 (ok). advance → curr=Node(3).
  curr=3: next->data==3 (dup). runner: 3→3→4 (stop). 3->next=4, 4->prev=3.
  curr=3: next->data==4 (ok). advance → curr=Node(4).
  curr=4: next==null. STOP.
  Result: NULL ↔ 1 ↔ 2 ↔ 3 ↔ 4 ↔ NULL  ✓

TC: O(N)  SC: O(1)

Example:
Input: list = 1↔1↔2↔3↔3↔3↔4
Output: 1↔2↔3↔4

Why This Pattern:
A primary current pointer holds position while a secondary runner pointer scans ahead past every consecutive duplicate before relinking - the same cooperating-two-pointers technique used for Remove Duplicates from Sorted Array, adapted to a doubly linked list.
*/

    public function removeDuplicatesSorted(): void
    {
        if ($this->head === null) return;

        $current = $this->head;

        while ($current !== null && $current->next !== null) {
            if ($current->data === $current->next->data) {
                // Find the first node AFTER all consecutive duplicates
                $runner = $current->next;
                while ($runner !== null && $runner->data === $current->data) {
                    $runner = $runner->next;
                }
                // Bypass all duplicates in one shot
                $current->next = $runner;
                if ($runner !== null) $runner->prev = $current;
                // Do NOT advance current — new next might still be a dup
            } else {
                $current = $current->next;  // No duplicate, safe to advance
            }
        }
    }


/*
|--------------------------------------------------------------------------
| Problem: Find All Pairs With Given Sum in a Sorted Doubly Linked List (Two-Pointer)
| Source: DS/6. LinkedList/10. DLL.php
| Pattern: Two Pointers
|--------------------------------------------------------------------------
*/

/*
Problem Description:
14. FIND ALL PAIRS WITH GIVEN SUM IN SORTED DLL (Two-Pointer)
Intuition:
  SORTED list → two-pointer (same as two-sum on sorted array):
    $low  starts at HEAD (smallest element).
    $high starts at TAIL (largest element).
  Each iteration:
    total == sum → found pair; shrink window from both sides.
    total <  sum → need a larger total → move low  right (+).
    total >  sum → need a smaller total → move high left  (−).
  Stop when $low === $high (pointers meet) or they cross
  ($low->prev === $high means low passed high by one step).

Dry Run: list=1 ↔ 2 ↔ 3 ↔ 4 ↔ 5 ↔ 6 ↔ 7, sum=7
  low=1, high=7: 1+7=8 > 7 → high=6
  low=1, high=6: 1+6=7     → pair(1,6)! low=2, high=5
  low=2, high=5: 2+5=7     → pair(2,5)! low=3, high=4
  low=3, high=4: 3+4=7     → pair(3,4)! low=4, high=3 → crossed STOP
  Pairs: [[1,6],[2,5],[3,4]]  ✓

TC: O(N)  SC: O(1) — not counting the result array output

Example:
Input: list = 1↔2↔3↔4↔5↔6↔7, sum = 7
Output: [[1,6],[2,5],[3,4]]

Why This Pattern:
The source's own comment already labels this 'Two-Pointer': low starts at head, high starts at tail, and they converge inward based on the running sum - structurally identical to Two Sum II on a sorted array, just walked via prev/next instead of array indices.
*/

    public function findPairsWithSum(int $sum): array
    {
        $result = [];
        if ($this->head === null) return $result;

        // Walk to tail → $high pointer
        $high = $this->head;
        while ($high->next !== null) {
            $high = $high->next;
        }

        $low = $this->head;

        // Continue while pointers haven't met ($low !== $high) and
        // low hasn't passed high ($low->prev !== $high)
        while ($low !== $high && $low->prev !== $high) {
            $total = $low->data + $high->data;

            if ($total === $sum) {
                $result[] = [$low->data, $high->data];  // Found a valid pair
                $low      = $low->next;                 // Shrink window from left
                $high     = $high->prev;                // Shrink window from right
            } elseif ($total < $sum) {
                $low  = $low->next;     // Total too small → move low right
            } else {
                $high = $high->prev;    // Total too large → move high left
            }
        }

        return $result;
    }


/*
|--------------------------------------------------------------------------
| Problem: Reverse an Array - Two-Pointer Recursion
| Source: DS/1. Basic/4. Recursion.php
| Pattern: Two Pointers
|--------------------------------------------------------------------------
*/

/*
Problem Description:
============================================================
6. REVERSE AN ARRAY  (Two-pointer recursion)
============================================================
Problem: Reverse an array in-place using recursion.

Approach:
  - Use two pointers: $i (start) and $j (end).
  - Swap elements at $i and $j, then move pointers inward.
  - Base case: $i >= $j → pointers have crossed, done.

Note: Array is passed by reference (&$arr) so swaps persist.

Time Complexity : O(N)
Space Complexity: O(N) — call stack depth N/2
============================================================

Example:
Input: [1,2,3,4,5]
Output: [5,4,3,2,1]

Why This Pattern:
Two indices start at opposite ends, swap, and move inward until they cross - the canonical converging two-pointer swap, here expressed as recursion instead of a while-loop (the recursive call replaces the loop's iteration, but the pointer mechanics are identical to Reverse String / iterative array reversal).
*/

function reverseArray(array &$arr, int $i, int $j): void
{
    if ($i >= $j) return;           // Base case: pointers crossed

    // Swap elements at positions i and j
    [$arr[$i], $arr[$j]] = [$arr[$j], $arr[$i]];

    // Move both pointers inward and recurse
    reverseArray($arr, $i + 1, $j - 1);
}


/*
|--------------------------------------------------------------------------
| Problem: Check Palindrome String - Two-Pointer Recursion
| Source: DS/1. Basic/4. Recursion.php
| Pattern: Two Pointers
|--------------------------------------------------------------------------
*/

/*
Problem Description:
============================================================
7. CHECK PALINDROME STRING
============================================================
Problem: Check if a string is a palindrome using recursion.

Approach:
  - Use two-pointer technique on character array.
  - Compare characters at $i and $n-$i-1 (mirror positions).
  - Base case: $i >= n/2 → all characters matched → true.
  - Mismatch at any step → false immediately.

Time Complexity : O(N)
Space Complexity: O(N)
============================================================

Example:
"racecar" -> true, "hello" -> false

Why This Pattern:
Two indices compare mirrored positions (i and n-i-1) and converge toward the middle, failing fast on any mismatch - the same converging/mirror-comparison technique the handbook lists for Valid Palindrome, here as the plain (non-alphanumeric-filtered) practice version.
*/

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
|--------------------------------------------------------------------------
| Problem: Valid Palindrome (LC 125)
| Source: DS/1. Basic/4. Recursion.php
| Pattern: Two Pointers
|--------------------------------------------------------------------------
*/

/*
Problem Description:
============================================================
10. LEETCODE 125 — VALID PALINDROME
============================================================
Problem: Given a string s, return true if it is a palindrome
after keeping only alphanumeric characters and lowercasing.

Example: "A man, a plan, a canal: Panama" → true
         "race a car"                     → false

Approach:
  Step 1: Filter out non-alphanumeric characters & lowercase.
  Step 2: Use recursive two-pointer palindrome check.

Time Complexity : O(N)
Space Complexity: O(N)
============================================================

Example:
Input: "A man, a plan, a canal: Panama" -> true
Input: "race a car" -> false

Why This Pattern:
After filtering to alphanumeric-only lowercase characters, two pointers converge from both ends comparing mirrored characters - the handbook's own core Beginner example for this pattern, listed explicitly as Valid Palindrome (LC 125), 'Converging pointer on strings'.
*/

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

