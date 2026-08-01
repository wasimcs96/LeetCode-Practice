<?php
// ================================================================================
//  ARRAYS — PRODUCTION-QUALITY INTERVIEW REVISION GUIDE  (Enhanced Edition v2)
// ================================================================================
//  Source file   : Array.php  (original, untouched — kept as-is)
//  This file     : Array_enhancement.php — a single source of truth for the
//                  Array topic, built ON TOP OF the original file's logic.
//  Target bar    : Senior/Staff SDE — Saudi Arabia, UAE (Dubai/Abu Dhabi),
//                  India Tier-1/Tier-2 (₹60LPA+), FAANG-level standards.
//  Reference     : Striver's A2Z DSA Sheet (problem numbering mirrors it)
//  Built against : DS/00-Interview-Enhancement-Master-Prompt.md (the
//                  reusable spec for every topic file in this repo)
//
//  v2 CHANGELOG (nothing below was removed from v1 — only added)
//  --------------------------------------------------------------
//  Every one of the 26 fully-detailed problems below now also carries, per
//  the master prompt's Section B additions:
//    - An "Asked at" companies tag (placeholder to personalize as you go
//      through your own mocks/interviews)
//    - A Constraints-to-Complexity mapping (what TC is SAFE given typical
//      LeetCode constraints for that exact problem)
//    - Interview Time-Boxing guidance (a realistic per-problem time budget)
//    - A 60-Second Verbal Pitch (a spoken-language script, no code, to
//      rehearse saying out loud before you start coding)
//    - Explicit runnable assert()-style Test Cases (not just prose edge cases)
//    - PHP-specific gotchas, where relevant
//    - Mistake-Recovery guidance (what to do/say if you realize mid-solve
//      your approach is wrong or suboptimal)
//    - Follow-Up / Scale-Up Extensions (system-design-adjacent twists an
//      interviewer might improvise on the spot)
//  Plus two new file-level sections: a Pre-Submission Checklist (Section Z1)
//  and a Spaced-Repetition Revision Schedule (Section Z3).
//
//  HOW TO USE THIS FILE
//  ---------------------
//  Each problem below follows the SAME structure so you can revise in a
//  predictable rhythm the night before an interview:
//
//    1. How to Identify This Pattern   (keywords, hidden hints, mistakes)
//    2. Problem Understanding          (what/why/constraints/analogy)
//    3. Interview-Ready Add-Ons        (companies, constraints->complexity,
//       time-boxing, 60-second verbal pitch)                          [v2]
//    4. Approaches (Brute -> Better -> Optimal): intuition, algorithm,
//       code, complexity, pros/cons
//    5. Complete Dry Run (table form)
//    6. Patterns Used (primary + secondary)
//    7. Pattern Recognition Tips (when to use / not use / similar problems)
//    8. Edge Cases
//    9. Additional Senior-Level Prep   (test assertions, PHP gotchas,
//       mistake-recovery tip, follow-up/scale-up extensions)           [v2]
//   10. Interview Discussion (Q&A you should be able to answer instantly)
//   11. Related Problems (Easy -> Medium -> Hard)
//   12. (implicit) Clean, commented, production-quality PHP code
//
//  A BUG LOG is kept in Section Z at the end of this file — every bug found
//  in the original Array.php is listed there with a one-line fix summary,
//  cross-referenced to the problem number where the fix lives. A
//  Pre-Submission Checklist (Z1) and Spaced-Repetition Schedule (Z3) close
//  out the file.
// ================================================================================


// ================================================================================
//  SECTION 0 — MASTER PATTERN RECOGNITION GUIDE (read this first, every time)
// ================================================================================
//
//  Array problems in interviews almost always reduce to ONE of these 9 engines.
//  Train yourself to name the engine within 30 seconds of reading the problem.
//
//  ┌────────────────────────────┬──────────────────────────────────────────────┐
//  │ ENGINE                     │ TRIGGER KEYWORDS / SIGNALS                    │
//  ├────────────────────────────┼──────────────────────────────────────────────┤
//  │ 1. Two Pointers            │ "pair/triplet that sums to X", "sorted array",│
//  │                             │ "remove in place", "palindrome check"         │
//  │ 2. Sliding Window          │ "longest/shortest subarray", "at most K",     │
//  │                             │ "contiguous", "consecutive", window of size K │
//  │ 3. Prefix Sum + HashMap    │ "subarray sum equals K", negative numbers     │
//  │                             │ allowed, "count of subarrays"                 │
//  │ 4. Kadane's / DP-on-array  │ "maximum subarray", "maximum product",        │
//  │                             │ contiguous + optimization                     │
//  │ 5. Dutch National Flag     │ "sort 0s 1s 2s", "3-way partition", colors    │
//  │ 6. Boyer-Moore Voting      │ "majority element", "> n/2" or "> n/3" times  │
//  │ 7. Reversal Trick          │ "rotate array by k", "next permutation"       │
//  │ 8. Matrix Boundary Tricks  │ "rotate image", "spiral", "set zeroes",       │
//  │                             │ anything 2D with in-place / O(1) space asks   │
//  │ 9. Hashing for O(1) lookup │ "consecutive sequence", "union/intersection", │
//  │                             │ "have we seen this before"                    │
//  └────────────────────────────┴──────────────────────────────────────────────┘
//
//  COMMON MISTAKES WHILE IDENTIFYING THE PATTERN (across this entire topic)
//  --------------------------------------------------------------------------
//  - Reaching for a HashMap when the array is already SORTED and Two Pointers
//    would give the same O(n) (or better) result with O(1) space instead of O(n).
//  - Using Sliding Window on an array that can contain NEGATIVE numbers.
//    Sliding Window's "shrink when sum too big" logic silently breaks the
//    moment negative numbers are allowed — Prefix Sum + HashMap is the
//    correct engine there, not Sliding Window.
//  - Forgetting that "majority guaranteed" (problem promises an answer exists)
//    removes the need for a verification pass — but the MOMENT that guarantee
//    is dropped (e.g., LC229's ">n/3", where an answer might not exist),
//    skipping verification silently returns a wrong candidate.
//  - Jumping to O(n^2) brute force for matrix problems instead of noticing
//    the "in-place / O(1) extra space" constraint, which is the strongest
//    possible hint that you're expected to use the array itself as storage
//    (row/col 0 as markers, boundary pointers, transpose+reverse, etc.).
//  - Treating "rotate array" as a simple loop-shift without noticing k can be
//    larger than n (always do k = k % n first) or without noticing LEFT vs
//    RIGHT rotation require reversing DIFFERENT segments first (see Problem 6
//    below, where the original file's implementation had exactly this bug).
//
//  GENERAL COMPLEXITY CHEAT SHEET
//  --------------------------------------------------------------------------
//  Two Pointers on sorted array        : O(n) time,        O(1) space
//  Sliding Window (fixed/variable)     : O(n) time,        O(1) space
//  Prefix Sum + HashMap                : O(n) time,        O(n) space
//  Kadane's                            : O(n) time,        O(1) space
//  Dutch National Flag                 : O(n) time,        O(1) space
//  Boyer-Moore Voting                  : O(n) time,        O(1) space
//  Reversal-based rotation             : O(n) time,        O(1) space
//  Matrix transpose + reverse          : O(n*m) time,      O(1) space
//  Hashing (set/map based)             : O(n) time,        O(n) space
//
// ================================================================================


// ================================================================================
//  PROBLEM 1 — MAX AND MIN IN AN ARRAY
// ================================================================================
//
//  --- 🎯 Interview-Ready Add-Ons (constraints, timing, pitch) ---
//  Asked at      : Amazon, Microsoft, Adobe, Zoho, and virtually every phone-screen (extremely common warm-up) -- update after your own mocks
//  Constraints   : 1 <= n <= 10^5, values fit in a 64-bit int -> O(n) single pass is expected; O(n log n) via sort() would be marked down as under-optimized for such a trivial ask.
//  Time-boxing   : Total ~5 min: 1 min restate, 1 min brute (call max()/min() and explain why that's not the 'real' answer), 3 min manual single-pass + dry run.
//  60-Sec Pitch  : "I'll track a running max and running min in one pass, seeded at PHP_INT_MIN and PHP_INT_MAX so it's correct even on all-negative or all-positive input, and update both on every element."
//
//
//  --- How to Identify This Pattern ---
//  Keywords     : "find the maximum and minimum", "largest and smallest element"
//  Signal       : Single unsorted array, one linear property to extract.
//  Hidden hint  : Asking for BOTH max and min in one pass (not two separate
//                 scans) is a subtle efficiency signal — interviewers want to
//                 see you track both in a SINGLE loop, not call max()/min()
//                 twice (which is technically still O(n) but does 2n
//                 comparisons instead of ~1.5n, and shows less array-scanning
//                 discipline).
//  Common mistake: Calling PHP's built-in max($nums) and min($nums)
//                 separately. It works, but in an interview you're expected
//                 to demonstrate the manual single-pass technique — built-ins
//                 hide the comparison logic interviewers want to see.
//
//  --- Problem Understanding ---
//  What: given an unsorted array, return its largest and smallest values.
//  Why it exists: it's the "hello world" of array scanning — establishes the
//    habit of initializing accumulators to the correct sentinel values
//    (PHP_INT_MIN / PHP_INT_MAX) rather than 0, which would silently break
//    on all-negative or all-positive arrays.
//  Real-world analogy: scanning a day's stock prices to report the day's
//    high and low in one glance.
//
//  --- Approach: Single Pass (the only approach worth showing) ---
//  Intuition : Track a running max and running min; update both on every
//              element in the same loop.
//  Algorithm : 1) max = -infinity, min = +infinity
//              2) for each num: if num > max, max = num; if num < min, min = num
//              3) return {max, min}
//  TC: O(n)  — single pass, one comparison-pair per element
//  SC: O(1)  — two scalar accumulators
//  Why optimal: you cannot do better than O(n) — every element must be
//    inspected at least once to guarantee correctness (an unexamined element
//    could always be the true max or min).
//
function findMaxAndMin(array $nums): array {
    $max = PHP_INT_MIN;  // Sentinel: anything in the array will be greater than this
    $min = PHP_INT_MAX;  // Sentinel: anything in the array will be smaller than this

    foreach ($nums as $num) {
        if ($num > $max) $max = $num;   // Widen the upper bound whenever a bigger value appears
        if ($num < $min) $min = $num;   // Widen the lower bound whenever a smaller value appears
    }

    return ['max' => $max, 'min' => $min];
}

//  --- Dry Run ---  nums = [10, 12, 5, 3, 7, 8]
//  ┌───┬──────┬──────────┬──────────┐
//  │ i │ num  │ max      │ min      │
//  ├───┼──────┼──────────┼──────────┤
//  │ 0 │ 10   │ 10       │ 10       │
//  │ 1 │ 12   │ 12       │ 10       │
//  │ 2 │ 5    │ 12       │ 5        │
//  │ 3 │ 3    │ 12       │ 3        │
//  │ 4 │ 7    │ 12       │ 3        │
//  │ 5 │ 8    │ 12       │ 3        │
//  └───┴──────┴──────────┴──────────┘
//  Output: max=12, min=3

$result = findMaxAndMin([10, 12, 5, 3, 7, 8]);
echo "Max: {$result['max']} | Min: {$result['min']}\n";  // Max: 12 | Min: 3

//  --- Patterns Used ---     Primary: Linear Scan.  Secondary: none.
//  --- Recognition Tips ---
//    Use when: you need one or two aggregate values from an unsorted array
//              in a single pass.
//    Don't use when: you need the SECOND largest/smallest too (see Problem 2
//              — that needs extra state, not just this pattern).
//    Similar problems: Second Largest/Smallest (below), Kth Largest Element
//              (needs a heap, not a linear scan).
//  --- Edge Cases ---
//    - Empty array           -> max/min remain sentinels; guard with a count()==0
//                                check and throw/return null before calling.
//    - Single element        -> max == min == that element. Works naturally.
//    - All elements identical-> max == min == that value. Works naturally.
//    - All negative numbers  -> PHP_INT_MIN/MAX sentinels are critical here;
//                                initializing to 0 would silently return 0 as
//                                a false "max" for an all-negative array.
//
//  --- 🧪 Additional Senior-Level Prep (tests, gotchas, follow-ups) ---
//  assert(findMaxAndMin([10,12,5,3,7,8]) === ['max' => 12, 'min' => 3]);
//  assert(findMaxAndMin([-5,-1,-9]) === ['max' => -1, 'min' => -9]);   // all-negative -- proves sentinel init is correct
//  assert(findMaxAndMin([7]) === ['max' => 7, 'min' => 7]);            // single element
//  PHP Gotcha       : None specific -- this problem is a good place to FIRST introduce the PHP_INT_MIN/MAX sentinel habit that every later problem in this file relies on.
//  Mistake Recovery : If you catch yourself defaulting max/min to 0, say it out loud immediately ("wait, that breaks on all-negative arrays") and fix it before the interviewer has to point it out -- self-correction reads far better than a silent bug.
//  Follow-Up / Scale-Up:
//    - What if the array is a live stream (values keep arriving)? -> same O(1)-space accumulators, just update on each new value as it arrives, no need to store the stream.
//    - What if you need this for billions of records across multiple machines? -> compute local (max,min) per shard/machine, then reduce (combine) the partial results -- classic MapReduce-style associative reduction.
//
//  --- Interview Discussion ---
//    Q: Can this be done with fewer than 2n comparisons?
//    A: Yes — pair up elements and compare within pairs first, then compare
//       winners against max and losers against min. This is the classic
//       "tournament method," bringing total comparisons down to ~3n/2
//       instead of 2n. Rarely asked to implement, but good to mention.
//    Q: Why not just use max($nums) and min($nums)?
//       Correct for production code, but shows less algorithmic reasoning
//       in an interview — always offer the manual version first.
//  --- Related Problems ---
//    Easy   : Second Largest/Smallest (below) — same engine, extra state.
//    Medium : Kth Largest Element in an Array (LC215) — needs Quickselect/Heap.
//    Hard   : Sliding Window Maximum (LC239) — max of every window, not whole array.


// ================================================================================
//  PROBLEM 2 — SECOND LARGEST AND SECOND SMALLEST
// ================================================================================
//
//  --- 🎯 Interview-Ready Add-Ons (constraints, timing, pitch) ---
//  Asked at      : Amazon, Flipkart, Paytm -- common as a 'do it without sorting' follow-up to Problem 1.
//  Constraints   : 1 <= n <= 10^5 -> O(n) single pass expected; sorting (O(n log n)) is a valid 'Better' answer but should be explicitly named as sub-optimal before the optimal is given.
//  Time-boxing   : Total ~7 min: 1 min restate + duplicate-handling clarification, 2 min sort-based Better approach, 4 min single-pass Optimal + dry run.
//  60-Sec Pitch  : "I'll extend the single-pass max/min tracking from the previous problem with one more tier of state -- when a new max appears, the OLD max demotes to second_max, and I explicitly guard against a duplicate of the max being mistaken for the second-largest distinct value."
//
//
//  --- How to Identify This Pattern ---
//  Keywords     : "second largest", "runner-up", "second smallest"
//  Signal       : Extension of Problem 1 — same single-pass discipline, but
//                 now you must track TWO tiers of state per side (max/second_max).
//  Hidden hint  : Duplicates are the entire difficulty here. If the array is
//                 [10, 10, 8], the second largest is 8, NOT 10 — you must
//                 explicitly guard against the max value "counting twice."
//  Common mistake: Sorting the array and picking index [1] / [n-2]. This
//                 works but costs O(n log n) when O(n) is achievable, AND
//                 it still needs a dedup step for the duplicate-max case
//                 (sorted [10,10,8] -> index 1 is still 10, not 8!). So the
//                 "just sort it" instinct is a trap on both efficiency and
//                 correctness fronts.
//
//  --- Problem Understanding ---
//  What: find the second-largest and second-smallest DISTINCT values.
//  Why it exists: forces you to reason about "state that depends on other
//    state" (second_max only updates correctly relative to the current max)
//    and to handle duplicates correctly — a very common interview trap.
//
//  --- Approach 1: Sort then pick (Brute Force) ---
//  Intuition : sort ascending; second smallest = first distinct value after
//              index 0; second largest = first distinct value before the
//              last index.
//  TC: O(n log n)  |  SC: O(1) extra (O(n) if you copy the array first)
//  Disadvantage: pays a sorting cost for information a single pass can give you.
//
//  --- Approach 2: Two-Tier Single Pass (Optimal) ---
//  Intuition : maintain (max, second_max) and (min, second_min) together.
//              On a new max, the OLD max demotes to second_max (not the
//              incoming value — a common off-by-one bug). Skip an update to
//              second_max/second_min when the value equals the current
//              max/min, otherwise duplicates of the max would incorrectly
//              become "second max."
//  TC: O(n)  |  SC: O(1)
//
function findSecondMaxAndMin(array $nums): array {
    $max = $secondMax = PHP_INT_MIN;
    $min = $secondMin = PHP_INT_MAX;

    foreach ($nums as $num) {
        // --- Max tracking ---
        if ($num > $max) {
            $secondMax = $max;   // Demote the OLD max before overwriting it
            $max = $num;
        } elseif ($num > $secondMax && $num !== $max) {   // != $max prevents duplicates of max "winning" second place
            $secondMax = $num;
        }

        // --- Min tracking (mirror logic) ---
        if ($num < $min) {
            $secondMin = $min;
            $min = $num;
        } elseif ($num < $secondMin && $num !== $min) {
            $secondMin = $num;
        }
    }

    return compact('max', 'secondMax', 'min', 'secondMin');
}

//  --- Dry Run ---  nums = [10, 12, 5, 3, 7, 8, 10]
//  ┌───┬─────┬─────┬───────────┬─────┬───────────┐
//  │ i │ num │ max │ secondMax │ min │ secondMin │
//  ├───┼─────┼─────┼───────────┼─────┼───────────┤
//  │ 0 │ 10  │ 10  │ MIN_INT   │ 10  │ MAX_INT   │
//  │ 1 │ 12  │ 12  │ 10        │ 10  │ MAX_INT   │
//  │ 2 │ 5   │ 12  │ 10        │ 5   │ 10        │
//  │ 3 │ 3   │ 12  │ 10        │ 3   │ 5         │
//  │ 4 │ 7   │ 12  │ 10        │ 3   │ 5         │
//  │ 5 │ 8   │ 12  │ 10        │ 3   │ 5         │
//  │ 6 │ 10  │ 12  │ 10 (skip: 10===max, not a new second) │ 3   │ 5   │
//  └───┴─────┴─────┴───────────┴─────┴───────────┘
//  Output: secondMax=10, secondMin=5

$r = findSecondMaxAndMin([10, 12, 5, 3, 7, 8, 10]);
echo "Second Max: {$r['secondMax']} | Second Min: {$r['secondMin']}\n";  // 10 | 5

//  --- Patterns Used ---   Primary: Linear Scan with layered state.
//  --- Recognition Tips ---
//    Use when: you need the Nth largest/smallest for SMALL, FIXED N (1st,
//              2nd, maybe 3rd) — beyond N=2 or 3, switch to a min/max-heap
//              of size N, which generalizes far better (see Kth Largest).
//    Don't use when: N is large or variable at runtime — heap-based
//              Kth-largest (LC215) is the right generalization.
//    Similar problems: Kth Largest Element (LC215), Third Maximum Number (LC414).
//  --- Edge Cases ---
//    - All elements identical -> secondMax/secondMin remain sentinel values
//      (PHP_INT_MIN/MAX). Caller must check for this and treat it as "no
//      second distinct value exists."
//    - Array with fewer than 2 distinct values -> same as above.
//    - Two elements only -> works naturally (each becomes max/min of the other).
//
//  --- 🧪 Additional Senior-Level Prep (tests, gotchas, follow-ups) ---
//  assert(findSecondMaxAndMin([10,12,5,3,7,8,10])['secondMax'] === 10);
//  assert(findSecondMaxAndMin([10,12,5,3,7,8,10])['secondMin'] === 5);
//  // All-identical input -> secondMax/secondMin remain sentinels; caller must check for this explicitly:
//  assert(findSecondMaxAndMin([4,4,4])['secondMax'] === PHP_INT_MIN);
//  Mistake Recovery : If asked to generalize to Kth-largest mid-interview and you're not sure, say so explicitly ("for K=2 or 3 a layered scan works, but for general K I'd switch to a heap") rather than trying to force this exact pattern to scale -- naming the pattern's limit is itself a signal of seniority.
//  Follow-Up / Scale-Up:
//    - Generalize to Kth largest/smallest for arbitrary K -> min-heap of size K (see LC215, already flagged as the follow-up in Related Problems).
//    - What if duplicates should count toward rank (i.e., second-largest INCLUDING duplicates of the max)? -> drop the `!= max` guard; state clearly which definition the interviewer wants before coding, since both are legitimate interpretations.
//
//  --- Interview Discussion ---
//    Q: Why demote the OLD max to secondMax instead of assigning the new
//       value to secondMax?
//    A: Because the old max is guaranteed >= every value seen so far except
//       the new max — it's the only value that's provably a valid "runner-up"
//       candidate at that exact moment.
//    Q: How would you generalize this to the Kth largest?
//    A: Maintain a min-heap of size K; if a new value is bigger than the
//       heap's root (the current Kth largest), pop the root and push the
//       new value. O(n log K) time, O(K) space.
//  --- Related Problems ---
//    Easy   : Third Maximum Number (LC414) — same layered-state idea, one more tier.
//    Medium : Kth Largest Element in an Array (LC215) — heap generalization.
//    Hard   : Find K Pairs with Smallest Sums (LC373) — heap + array combo.


// ================================================================================
//  PROBLEM 3 — CHECK IF ARRAY IS SORTED (ASCENDING)
// ================================================================================
//
//  --- 🎯 Interview-Ready Add-Ons (constraints, timing, pitch) ---
//  Asked at      : Nearly every company as a warm-up or as a precondition check embedded inside a larger problem (e.g., before applying Binary Search).
//  Constraints   : 1 <= n <= 10^5 -> O(n) is both necessary and sufficient; there is no faster correct answer since every element must be inspected at least once.
//  Time-boxing   : Total ~3 min: this should be near-instant -- if it takes longer than 3 minutes end to end, that's itself a signal to speed up on trivial warm-ups.
//  60-Sec Pitch  : "A sorted array has no adjacent inversion, so I just scan once checking nums[i] <= nums[i+1] and bail out the instant I find a violation."
//
//
//  --- How to Identify This Pattern ---
//  Keywords     : "is sorted", "monotonic", "non-decreasing"
//  Signal       : A pure verification / invariant-check problem — the
//                 simplest possible array scan, but it's the BUILDING BLOCK
//                 for Problem 4 (sorted+rotated) and many Binary-Search
//                 precondition checks. Never skip mastering this one.
//  Common mistake: Off-by-one in the loop bound — iterating to count($nums)
//                 instead of count($nums) - 1 causes an out-of-bounds read
//                 on nums[i+1] at the last index.
//
//  --- Problem Understanding ---
//  What: return true if every adjacent pair satisfies nums[i] <= nums[i+1].
//  Why it exists: sortedness is a PRECONDITION for dozens of other patterns
//    (Binary Search, Two Pointers on sorted arrays, etc.) — you must be able
//    to verify it in O(n) before relying on it.
//
//  --- Approach: Single Pass Adjacent-Pair Check (only one approach needed) ---
//  Intuition : a sorted array has NO adjacent inversion (nums[i] > nums[i+1]).
//              Finding even one inversion proves it is not sorted.
//  TC: O(n)  |  SC: O(1)
//
function isSorted(array $nums): bool {
    for ($i = 0; $i < count($nums) - 1; $i++) {   // Stop at n-2: we always look ahead to i+1
        if ($nums[$i] > $nums[$i + 1]) return false;  // Found an inversion -> not sorted, exit early
    }
    return true;   // No inversion found in the entire array
}

//  --- Dry Run ---  nums = [2, 3, 4, 1]
//  i=0: 2<=3 ok | i=1: 3<=4 ok | i=2: 4>1 -> return false immediately

echo "Is sorted [1,2,3,4]: " . (isSorted([1, 2, 3, 4]) ? "true" : "false") . "\n"; // true
echo "Is sorted [2,3,4,1]: " . (isSorted([2, 3, 4, 1]) ? "true" : "false") . "\n"; // false

//  --- Patterns Used ---   Primary: Linear Scan / Invariant Check.
//  --- Recognition Tips ---
//    Use when: you need a quick O(n) precondition check before applying
//              Binary Search or a sorted-array Two-Pointer technique.
//    Don't use when: you actually need to know HOW MANY inversions exist,
//              or WHERE the break is (that's Problem 4's job).
//  --- Edge Cases ---
//    - Empty array / single element -> loop never executes -> returns true
//      (vacuously sorted). This is correct and expected.
//    - Array with duplicates -> `>` (strict) allows equal adjacent values to
//      pass, correctly treating [1,1,2] as sorted (non-decreasing).
//
//  --- 🧪 Additional Senior-Level Prep (tests, gotchas, follow-ups) ---
//  assert(isSorted([1,2,3,4]) === true);
//  assert(isSorted([2,3,4,1]) === false);
//  assert(isSorted([]) === true);     // vacuously sorted -- empty array has no inversions
//  assert(isSorted([5]) === true);    // single element -- trivially sorted
//  Mistake Recovery : Not much can go wrong here, but if you DO fumble the loop bound (off-by-one reading nums[i+1] out of bounds), name the fix immediately rather than silently patching it -- interviewers notice unexplained edits more than the original slip.
//  Follow-Up / Scale-Up:
//    - What about descending order instead of ascending? -> flip the comparison operator; ask which direction is expected before assuming.
//    - What if the array is too large to fit in memory (streaming validation)? -> keep only the previous element in memory, compare each new element against it as it arrives -- same O(1) space, now O(1) per-element instead of a batch pass.
//
//  --- Interview Discussion ---
//    Q: How would you check for STRICTLY increasing instead of non-decreasing?
//    A: Change `>` to `>=` in the condition — any equal adjacent pair then
//       also counts as a violation.
//  --- Related Problems ---
//    Easy   : Check if Array Is Sorted and Rotated (LC1752) — Problem 4 below.
//    Medium : Monotonic Array (LC896) — check increasing OR decreasing.


// ================================================================================
//  PROBLEM 4 — LC 1752: CHECK IF ARRAY IS SORTED AND ROTATED
// ================================================================================
//
//  --- 🎯 Interview-Ready Add-Ons (constraints, timing, pitch) ---
//  Asked at      : Amazon, Google (as a lead-in to 'Search in Rotated Sorted Array' style questions).
//  Constraints   : 1 <= n <= 100 (LC1752's actual constraint is small) -> O(n) is trivially fast enough; this problem is really testing the INSIGHT (at-most-one-break), not raw efficiency.
//  Time-boxing   : Total ~6 min: 1 min restate, 2 min explain the 'at most one break, including wrap-around' insight verbally, 3 min code + dry run.
//  60-Sec Pitch  : "A validly rotated-then-sorted array has at most one place where a value is followed by something smaller -- including the wrap-around pair from the last element back to the first -- so I count those breaks with modulo indexing and check the count is <= 1."
//
//
//  --- How to Identify This Pattern ---
//  Keywords     : "sorted and rotated", "circularly sorted", "at most one place"
//  Signal       : Whenever a problem mentions an array that WAS sorted but
//                 has since been "rotated," think in terms of counting
//                 "breaks" (places where nums[i] > nums[i+1]) rather than
//                 trying to literally un-rotate it.
//  Hidden hint  : The word "rotated" almost always implies you should check
//                 the WRAP-AROUND pair (last element vs. first element) too
//                 — a very common miss.
//  Common mistake: Forgetting the wrap-around comparison (nums[n-1] vs
//                 nums[0]) — without it you'd wrongly reject valid rotations
//                 like [3,4,5,1,2] (which has exactly one "real" break plus
//                 one wrap-around non-break).
//
//  --- Problem Understanding ---
//  What: return true if the array could have been produced by taking a
//        sorted array and rotating it some number of times (0 rotations
//        included — an already-sorted array counts as valid).
//  Why it exists: this is the CONCEPTUAL PRECURSOR to "Search in Rotated
//    Sorted Array" (Binary Search pattern) — recognizing "at most one break
//    point" is the exact same insight Binary-Search-on-rotated-arrays needs.
//  Real-world analogy: a clock face — reading the numbers starting from any
//    point still gives you a "rotated but locally sorted" sequence.
//
//  --- Approach: Count Breaks with Circular Indexing (only approach needed) ---
//  Intuition : a validly sorted-then-rotated array has AT MOST ONE index i
//              where nums[i] > nums[(i+1) % n]. Zero breaks = already sorted.
//              One break = exactly one rotation point. Two+ breaks = impossible.
//  TC: O(n)  |  SC: O(1)
//
function isSortedAndRotated(array $nums): bool {
    $n = count($nums);
    $breaks = 0;

    for ($i = 0; $i < $n; $i++) {
        $next = ($i + 1) % $n;              // Wraps the last index back to 0 -- this is the key trick
        if ($nums[$i] > $nums[$next]) {
            $breaks++;                       // Found a "drop" -- a candidate rotation point
        }
    }

    return $breaks <= 1;   // 0 breaks: already sorted. 1 break: valid rotation. 2+: impossible.
}

//  --- Dry Run ---  nums = [3, 4, 5, 1, 2]
//  ┌───┬────────────┬────────┬──────────┐
//  │ i │ pair        │ result │ breaks   │
//  ├───┼────────────┼────────┼──────────┤
//  │ 0 │ (3,4)       │ ok     │ 0        │
//  │ 1 │ (4,5)       │ ok     │ 0        │
//  │ 2 │ (5,1)       │ DROP   │ 1        │
//  │ 3 │ (1,2)       │ ok     │ 1        │
//  │ 4 │ (2,3) wrap  │ ok     │ 1        │
//  └───┴────────────┴────────┴──────────┘
//  breaks=1 <= 1 -> true

var_dump(isSortedAndRotated([3, 4, 5, 1, 2])); // bool(true)
var_dump(isSortedAndRotated([2, 1, 3, 4]));    // bool(false) -- two drops: (2,1) and (4,2)wrap

//  --- Patterns Used ---   Primary: Circular Array Scan.  Secondary: Invariant Counting.
//  --- Recognition Tips ---
//    Use when: "rotated array" appears alongside a YES/NO or "find the
//              pivot" question.
//    Don't use when: you need the actual VALUE of the rotation count or the
//              minimum element — that requires Binary Search (see the
//              Binary Search topic file for "Find Minimum in Rotated Array").
//    Similar problems: Find Minimum in Rotated Sorted Array (LC153), Search
//              in Rotated Sorted Array (LC33) — both build on this exact insight.
//  --- Edge Cases ---
//    - Already sorted, no rotation -> 0 breaks -> true.
//    - Single element -> the wrap pair compares the element with itself
//      (equal, not a break) -> 0 breaks -> true.
//    - All elements identical -> 0 breaks (no strict `>`) -> true.
//    - Rotated by exactly n (full circle) -> identical to "already sorted" -> true.
//
//  --- 🧪 Additional Senior-Level Prep (tests, gotchas, follow-ups) ---
//  assert(isSortedAndRotated([3,4,5,1,2]) === true);
//  assert(isSortedAndRotated([2,1,3,4]) === false);   // two breaks: (2,1) and (4,2) wrap
//  assert(isSortedAndRotated([1,2,3]) === true);      // already sorted, zero rotations, zero breaks
//  assert(isSortedAndRotated([1]) === true);          // single element -- wrap pair compares itself to itself
//  PHP Gotcha       : PHP's `%` operator on the LAST index correctly wraps to 0 for `($i+1) % $n`; double-check this wrap logic explicitly out loud since it's the single most-forgotten line in this problem.
//  Mistake Recovery : If you initially forget the wrap-around comparison and a test case like [3,4,5,1,2] fails your mental trace, don't panic-rewrite -- isolate exactly which comparison is missing (the last-to-first pair) and add just that.
//  Follow-Up / Scale-Up:
//    - Follow-up almost always asked immediately after this: 'now find the actual rotation COUNT, not just yes/no' -> Binary Search for the index of the minimum element (LC153, already cross-referenced).
//    - What if there could be duplicate values? -> the break-counting logic still works with `>` (strict), since equal adjacent values never count as a break either way.
//
//  --- Interview Discussion ---
//    Q: Why does "at most one break" prove validity?
//    A: A sorted array rotated once has exactly one place where the
//       sequence "restarts" from a smaller value; a second such place
//       would mean the array wasn't monotonic before rotation, which
//       contradicts the premise.
//    Q: Follow-up — how would you find the actual rotation COUNT (not just
//       yes/no)?
//    A: Binary search for the index of the minimum element — that index IS
//       the rotation count. See "Find Minimum in Rotated Sorted Array" in
//       the Binary Search topic file (already implemented there).
//  --- Related Problems ---
//    Easy   : Check if Array Is Sorted (Problem 3 above).
//    Medium : Find Minimum in Rotated Sorted Array (LC153).
//    Hard    : Search in Rotated Sorted Array II with duplicates (LC81).


// ================================================================================
//  PROBLEM 5 — LC 26: REMOVE DUPLICATES FROM SORTED ARRAY (IN-PLACE)
// ================================================================================
//
//  --- 🎯 Interview-Ready Add-Ons (constraints, timing, pitch) ---
//  Asked at      : Amazon, Microsoft, Google -- extremely common warm-up, often the FIRST question in a phone screen.
//  Constraints   : 1 <= n <= 3*10^4, array is already SORTED -> O(n) two-pointer expected; anything using unset()-in-a-loop or array_unique() should be named explicitly as the wrong instinct before the real answer.
//  Time-boxing   : Total ~8 min: 1 min restate + clarify 'in-place' means, 2 min explain why unset() is fragile, 5 min two-pointer code + dry run.
//  60-Sec Pitch  : "Because the array is sorted, duplicates are always adjacent, so I use a slow pointer for 'last written unique slot' and a fast pointer that scans ahead, writing a value forward only when it differs from the last unique one."
//
//
//  --- How to Identify This Pattern ---
//  Keywords     : "sorted array", "remove duplicates", "in-place", "return
//                 the new length"
//  Signal       : "In-place" + "sorted" together is the single strongest
//                 combination signal for Two Pointers (slow/fast) in the
//                 entire Array topic.
//  Hidden hint  : Because the array is SORTED, all duplicates of a value are
//                 guaranteed to be ADJACENT — you never need a hash set to
//                 detect a duplicate, just compare with the previous
//                 element. This is what makes O(1) space achievable.
//  Common mistake: Using `unset()` inside a loop on a live array (as the
//                 brute-force scratch code below does) — this shifts PHP's
//                 internal array indices unpredictably and is O(n) per
//                 removal, degrading to O(n^2) overall, plus it's genuinely
//                 fragile because PHP arrays are ordered maps, not true
//                 contiguous arrays.
//
//  --- Problem Understanding ---
//  What: given a sorted array, remove duplicates in-place so each unique
//        element appears once, and return the count of unique elements.
//        The first `k` slots of the array must hold the unique values.
//  Why it exists: teaches the "slow/fast pointer" in-place compaction
//        technique that reappears constantly (Move Zeroes, Remove Element,
//        Duplicate Zeros, etc.).
//
//  --- Approach 1: Naive unset() in a loop (Brute Force, shown for contrast) ---
//  Intuition : walk the array, unset any element equal to its neighbor.
//  TC: O(n) amortized but with costly re-indexing  |  SC: O(1) extra, but
//      mutates array structure unpredictably.
//  Disadvantage: PHP re-keys arrays lazily on unset(), which is easy to get
//      subtly wrong (skipped comparisons, gaps in keys) — NOT how this
//      problem is solved in a real interview. Shown only so you recognize
//      it as the wrong instinct.
$demoArr = [1, 1, 2, 2, 3];
$i = 0;
$demoCount = count($demoArr);
while ($i < $demoCount - 1) {
    if ($demoArr[$i] == $demoArr[$i + 1]) {
        unset($demoArr[$i]);   // Fragile: re-keys the array, easy to introduce off-by-one bugs
    }
    $i++;
}
// (Intentionally not used further -- Approach 2 below is the real solution.)

//  --- Approach 2: Slow/Fast Two Pointers (Optimal) ---
//  Intuition : `i` marks the last position known to hold a unique value.
//              `j` scans ahead. Whenever nums[j] differs from nums[i], a
//              new unique value has been found — advance `i` and write
//              nums[j] into that new slot.
//  Algorithm : 1) i = 0
//              2) for j = 1 .. n-1: if nums[j] != nums[i]: i++; nums[i] = nums[j]
//              3) return i + 1
//  TC: O(n)  |  SC: O(1) — genuinely in-place, no extra array
//
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

//  --- Dry Run ---  nums = [-4, -4, 0, 0, 1, 1, 1, 2, 2, 3, 3, 4]
//  ┌───┬─────────┬───┬────────────────────────────────┐
//  │ j │ nums[j] │ i │ action                          │
//  ├───┼─────────┼───┼────────────────────────────────┤
//  │ 1 │ -4      │ 0 │ equal to nums[0] -> skip        │
//  │ 2 │ 0       │ 1 │ new -> i=1, nums[1]=0           │
//  │ 3 │ 0       │ 1 │ equal -> skip                   │
//  │ 4 │ 1       │ 2 │ new -> i=2, nums[2]=1           │
//  │ 5 │ 1       │ 2 │ equal -> skip                   │
//  │ 6 │ 1       │ 2 │ equal -> skip                   │
//  │ 7 │ 2       │ 3 │ new -> i=3, nums[3]=2           │
//  │ 8 │ 2       │ 3 │ equal -> skip                   │
//  │ 9 │ 3       │ 4 │ new -> i=4, nums[4]=3           │
//  │10 │ 3       │ 4 │ equal -> skip                   │
//  │11 │ 4       │ 5 │ new -> i=5, nums[5]=4           │
//  └───┴─────────┴───┴────────────────────────────────┘
//  Output: length=6, array prefix = [-4,0,1,2,3,4]

$nums = [-4, -4, 0, 0, 1, 1, 1, 2, 2, 3, 3, 4];
$len  = removeDuplicates($nums);
echo "Unique count: $len\n";
echo "Array: " . implode(", ", array_slice($nums, 0, $len)) . "\n";

//  --- Patterns Used ---   Primary: Two Pointers (slow/fast).  Secondary: In-place compaction.
//  --- Recognition Tips ---
//    Use when: sorted input + in-place removal/compaction is requested.
//    Don't use when: array is UNSORTED — then duplicates aren't adjacent
//              and you genuinely need a HashSet (O(n) space).
//    Similar problems: Remove Element (LC27), Remove Duplicates II -- allow
//              at most 2 copies (LC80), Move Zeroes (Problem 7 below).
//  --- Edge Cases ---
//    - Empty array -> return 0 immediately (guarded above).
//    - All elements identical -> i never advances -> returns 1.
//    - No duplicates at all -> i advances every step -> returns n (no-op).
//
//  --- 🧪 Additional Senior-Level Prep (tests, gotchas, follow-ups) ---
//  $demo = [-4,-4,0,0,1,1,1,2,2,3,3,4]; $len = removeDuplicates($demo);
//  assert($len === 6 && array_slice($demo, 0, $len) === [-4,0,1,2,3,4]);
//  assert((function(){ $a=[]; return removeDuplicates($a); })() === 0);   // empty array
//  assert((function(){ $a=[7]; return removeDuplicates($a); })() === 1);  // single element
//  PHP Gotcha       : PHP's unset() inside a foreach/while re-keys the array lazily and does NOT reindex automatically -- if you ever reach for it here, immediately name why it's unsafe (shown as the Brute Force contrast above) rather than debugging it live.
//  Mistake Recovery : If your two-pointer swap looks right but the returned LENGTH is off by one, trace whether you're returning `i` instead of `i + 1` -- this exact off-by-one is the single most common slip on this problem.
//  Follow-Up / Scale-Up:
//    - What if duplicates should be allowed up to 2 copies (LC80)? -> compare nums[j] against nums[i-1] instead of nums[i].
//    - What if the array is NOT sorted? -> the whole approach breaks (duplicates aren't adjacent anymore) -- you'd need a HashSet, trading O(1) space for O(n).
//
//  --- Interview Discussion ---
//    Q: Why does this NOT work on an unsorted array?
//    A: Duplicates could be anywhere, not adjacent -- the two-pointer
//       compaction relies entirely on "if it's a duplicate, it's my
//       immediate left neighbor," which sortedness guarantees.
//    Q: Follow-up -- allow each value to appear at most TWICE (LC80)?
//    A: Compare nums[j] with nums[i-1] instead of nums[i] -- this lets one
//       duplicate slip through before rejecting the next one.
//  --- Related Problems ---
//    Easy   : Remove Element (LC27) -- same two-pointer compaction, no sort needed.
//    Medium : Remove Duplicates from Sorted Array II (LC80) -- allow 2 copies.
//    Medium : Remove Duplicates from Sorted List (LC83) -- same idea on a linked list.


// ================================================================================
//  PROBLEM 6 — LC 189: ROTATE ARRAY BY K POSITIONS (RIGHT)          [BUG FIXED]
// ================================================================================
//
//  --- 🎯 Interview-Ready Add-Ons (constraints, timing, pitch) ---
//  Asked at      : Amazon, Microsoft, Bloomberg -- also a favorite 'gotcha' question specifically because of the left/right direction bug fixed in this file.
//  Constraints   : 1 <= n <= 10^5, 0 <= k <= 10^9 (k can be MUCH bigger than n) -> O(n) time, O(1) space expected; the k%n reduction is mandatory, not optional.
//  Time-boxing   : Total ~10 min: 2 min restate + confirm rotation direction, 3 min extra-array Better approach, 5 min reversal-trick Optimal + dry run (trace it fully, this is where the direction bug hides).
//  60-Sec Pitch  : "I'll reduce k modulo n first, then apply the three-reversal trick -- reverse the first n-k elements, reverse the last k elements, then reverse the whole array -- which swaps the two blocks in place without extra memory."
//
//
//  *** BUG FOUND IN ORIGINAL FILE ***
//  The original `Solution::rotate()` reversed segments [0, k-1] then
//  [k, n-1] then the whole array. Tracing it on nums=[1,2,3,4,5,6,7], k=3
//  produces [4,5,6,7,1,2,3] -- that is a LEFT rotation by 3, NOT the
//  required RIGHT rotation (LeetCode 189 expects [5,6,7,1,2,3,4]). The
//  correct segment order for a RIGHT rotation is the exact opposite: reverse
//  the FIRST (n-k) elements, then the LAST k elements, then the whole array.
//  This was actually written correctly in a comment inside the original file
//  (just above the buggy code) but never implemented. Fixed below.
//
//  --- How to Identify This Pattern ---
//  Keywords     : "rotate array by k", "shift right/left by k positions"
//  Signal       : Whenever "rotate" + "in-place" + "O(1) extra space" appear
//                 together, think Reversal Trick before anything else.
//  Hidden hint  : k can be LARGER than the array length -- always reduce
//                 with k = k % n first, or you'll index out of bounds / do
//                 wasted full rotations.
//  Common mistake: Confusing which segments to reverse for LEFT vs RIGHT
//                 rotation (this is exactly the bug fixed here). The rule:
//                   RIGHT rotate by k -> reverse(0, n-k-1), reverse(n-k, n-1), reverse(0, n-1)
//                   LEFT  rotate by k -> reverse(0, k-1),   reverse(k, n-1),   reverse(0, n-1)
//
//  --- Problem Understanding ---
//  What: shift every element k positions to the right, wrapping around.
//  Why it exists: the reversal trick is a widely reusable O(1)-space,
//    O(n)-time technique for cyclic rearrangement -- same core idea powers
//    Next Permutation's suffix-reverse step (Problem 19).
//
//  --- Approach 1: Extra Array (Better, not optimal on space) ---
//  Intuition : place nums[i] into result[(i+k) % n], then copy back.
//  TC: O(n)  |  SC: O(n)
//
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

//  --- Approach 2: Reversal Trick (Optimal, in-place) ---
//  Intuition : for a RIGHT rotation by k:
//              1) reverse the first (n-k) elements
//              2) reverse the last k elements
//              3) reverse the WHOLE array
//              Why this works: reversing twice with a re-reversal of the
//              whole array is equivalent to a block-swap of the two
//              segments while preserving each segment's internal order.
//  TC: O(n)  |  SC: O(1)
//
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

//  --- Dry Run ---  nums = [1,2,3,4,5,6,7], k=3, n=7, n-k=4
//  ┌──────┬───────────────────────┬────────────────────────────┐
//  │ Step │ Action                │ Array State                │
//  ├──────┼───────────────────────┼────────────────────────────┤
//  │ 0    │ initial               │ [1,2,3,4,5,6,7]             │
//  │ 1    │ reverse(0,3)          │ [4,3,2,1,5,6,7]             │
//  │ 2    │ reverse(4,6)          │ [4,3,2,1,7,6,5]             │
//  │ 3    │ reverse(0,6)          │ [5,6,7,1,2,3,4]  <- CORRECT │
//  └──────┴───────────────────────┴────────────────────────────┘
//  Expected LC189 output for k=3: [5,6,7,1,2,3,4]  -- MATCHES.
//  (The original buggy code produced [4,5,6,7,1,2,3] on this exact input --
//   compare the two and you can see the segment-order mistake directly.)

$arr = [1, 2, 3, 4, 5, 6, 7];
rotateRight($arr, 3);
echo "Rotated right by 3: " . implode(", ", $arr) . "\n";  // 5,6,7,1,2,3,4

//  --- Patterns Used ---   Primary: Reversal Trick.  Secondary: Two Pointers (inside reverseSegment).
//  --- Recognition Tips ---
//    Use when: "rotate in place" + O(1) space is required.
//    Don't use when: you need a NEW array anyway (then the extra-array
//              approach is simpler to write correctly under time pressure).
//    Similar problems: Rotate List (LC61, linked list version), Next
//              Permutation (Problem 19, reuses the reverse-a-segment helper).
//  --- Edge Cases ---
//    - k = 0        -> no-op, guarded explicitly.
//    - k = n        -> k % n = 0 -> no-op.
//    - k > n        -> reduced correctly via modulo.
//    - n = 1         -> any k reduces to 0 -> no-op, safe.
//    - k negative    -> NOT handled by this LeetCode variant (constraints
//                        guarantee k >= 0); a LEFT-rotation variant would
//                        need `$k = (($k % $n) + $n) % $n` to normalize negative k.
//
//  --- 🧪 Additional Senior-Level Prep (tests, gotchas, follow-ups) ---
//  $demo = [1,2,3,4,5,6,7]; rotateRight($demo, 3);
//  assert($demo === [5,6,7,1,2,3,4]);   // the exact case that exposed the original bug
//  $demo2 = [1,2]; rotateRight($demo2, 0); assert($demo2 === [1,2]);         // k=0 no-op
//  $demo3 = [1]; rotateRight($demo3, 5); assert($demo3 === [1]);             // n=1, any k reduces to 0
//  PHP Gotcha       : PHP arrays passed by reference (&$nums) mutate the caller's copy directly -- forgetting the `&` in the function signature silently turns this into a no-op on the caller's array with no error raised.
//  Mistake Recovery : This is THE problem where the original file's own bug lived (see Section Z Bug Log) -- if your rotation direction looks flipped during a dry run, don't guess-and-check swaps; re-derive from first principles which segment must move to the FRONT and reverse that segment SECOND, not first.
//  Follow-Up / Scale-Up:
//    - Do it for a LEFT rotation instead -> mirror the segment order (reverse first k, then last n-k, then whole).
//    - What if the array is a linked list instead (LC61)? -> same rotation concept, but implemented via pointer surgery, not reversal, since a linked list has no O(1) random-access reversal shortcut.
//
//  --- Interview Discussion ---
//    Q: Why does reversing three times work?
//    A: Reversing segment A then segment B then the whole [A|B] is
//       mathematically equivalent to swapping A and B while preserving each
//       segment's internal relative order -- a clean O(1)-space alternative
//       to a full extra-array copy.
//    Q: What's the classic bug here (this exact one)?
//    A: Reversing the WRONG segments first swaps the rotation direction --
//       always derive which segment goes first by asking "which chunk ends
//       up at the FRONT after rotation?" and reverse that chunk first... no
//       -- reverse the chunk that ends up at the BACK first (see algorithm
//       above), this is the exact mistake this file's original code made.
//  --- Related Problems ---
//    Easy   : Rotate String (LC796, string version of the same shift idea).
//    Medium : Rotate List (LC61) -- linked-list analogue.
//    Hard    : Rotate Image / Matrix (Problem 21 below) -- 2D rotation.


// ================================================================================
//  PROBLEM 7 — LC 283: MOVE ZEROES TO END
// ================================================================================
//
//  --- 🎯 Interview-Ready Add-Ons (constraints, timing, pitch) ---
//  Asked at      : Amazon, Facebook/Meta, Bloomberg -- common as a quick warm-up with an 'in-place, preserve order' twist.
//  Constraints   : 1 <= n <= 10^4 -> O(n) single pass expected; any solution allocating a new array should be flagged as not meeting the in-place requirement even if asymptotically fine on time.
//  Time-boxing   : Total ~7 min: 1 min restate 'preserve relative order' constraint, 1 min naive filter+append idea (and why it's not in-place), 5 min two-pointer swap + dry run.
//  60-Sec Pitch  : "I keep a pointer `i` for the next slot that should hold a non-zero value, scan the whole array with `j`, and swap any non-zero I find into position i -- since I only ever advance i on a non-zero find, relative order among non-zero elements is preserved automatically."
//
//
//  --- How to Identify This Pattern ---
//  Keywords     : "move zeroes to the end", "maintain relative order",
//                 "in-place"
//  Signal       : "In-place" + "preserve relative order of the rest" is the
//                 classic slow/fast two-pointer compaction signature (same
//                 family as Problem 5).
//  Common mistake: Removing zeroes and appending them at the end using
//                 array functions (array_filter + array_merge) -- this
//                 works but uses O(n) extra space and multiple passes;
//                 interviewers want the O(1)-space single-pass swap version.
//
//  --- Problem Understanding ---
//  What: push all zeroes to the end while keeping non-zero elements in their
//        original relative order.
//  Why it exists: a very common "compact while preserving order" building
//    block, e.g., filtering nulls from a data pipeline in-place.
//
//  --- Approach: Two Pointers with Swap (Optimal) ---
//  Intuition : `i` tracks the next position that should hold a non-zero
//              value. `j` scans the whole array. Every time nums[j] is
//              non-zero, swap it into position i and advance i.
//  TC: O(n)  |  SC: O(1)
//
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

//  --- Dry Run ---  nums = [0, 1, 0, 3, 12]
//  ┌───┬─────────┬───┬─────────────────────────────┐
//  │ j │ nums[j] │ i │ Array After Step             │
//  ├───┼─────────┼───┼─────────────────────────────┤
//  │ 0 │ 0       │ 0 │ [0,1,0,3,12]  (skip, is zero)│
//  │ 1 │ 1       │ 1 │ swap(0,1) -> [1,0,0,3,12]    │
//  │ 2 │ 0       │ 1 │ [1,0,0,3,12]  (skip)         │
//  │ 3 │ 3       │ 2 │ swap(1,3) -> [1,3,0,0,12]    │
//  │ 4 │ 12      │ 3 │ swap(2,4) -> [1,3,12,0,0]    │
//  └───┴─────────┴───┴─────────────────────────────┘
//  Output: [1,3,12,0,0]

$nums = [0, 1, 0, 3, 12];
moveZeroes($nums);
echo "After moveZeroes: " . implode(", ", $nums) . "\n";  // 1,3,12,0,0

//  --- Patterns Used ---   Primary: Two Pointers (slow/fast swap).
//  --- Recognition Tips ---
//    Use when: "move/filter X to the end/front while preserving order" +
//              in-place is asked.
//    Don't use when: order does NOT need to be preserved -- then a simpler
//              Dutch-Flag-style partition (swap without preserving order)
//              can reduce the number of writes.
//    Similar problems: Remove Element (LC27), Sort Colors (Problem 14 --
//              same swap-based partitioning family, 3-way instead of 2-way).
//  --- Edge Cases ---
//    - All zeroes            -> every swap is with itself; array unchanged, correct.
//    - No zeroes at all      -> i tracks j exactly; every swap is a no-op, correct.
//    - Single element        -> loop runs once, trivially correct.
//    - Zeroes already at end -> still correct, just does redundant self-swaps.
//
//  --- 🧪 Additional Senior-Level Prep (tests, gotchas, follow-ups) ---
//  $demo = [0,1,0,3,12]; moveZeroes($demo); assert($demo === [1,3,12,0,0]);
//  $demo2 = [0,0,0]; moveZeroes($demo2); assert($demo2 === [0,0,0]);        // all zeroes
//  $demo3 = [1,2,3]; moveZeroes($demo3); assert($demo3 === [1,2,3]);        // no zeroes -- pure no-op swaps
//  PHP Gotcha       : The swap `[$nums[$i], $nums[$j]] = [$nums[$j], $nums[$i]]` works correctly even when i === j (self-swap is harmless) -- don't add a defensive `if ($i !== $j)` guard unless you're explicitly asked to minimize write operations.
//  Mistake Recovery : If you initially write a version that OVERWRITES with 0 instead of SWAPPING, catch it yourself by asking 'does this destroy a non-zero value I haven't examined yet?' -- swapping preserves it, overwriting silently drops it.
//  Follow-Up / Scale-Up:
//    - Minimize the NUMBER of swaps (not just correctness)? -> track the position of the first zero explicitly and only swap once a zero has actually been seen, skipping redundant self-swaps before that point.
//    - What if you need to move a DIFFERENT sentinel value (not just 0) to the end? -> identical algorithm, just change the comparison target.
//
//  --- Interview Discussion ---
//    Q: How many swaps does this perform in the worst case?
//    A: At most n swaps, but often fewer -- exactly one swap per non-zero
//       element that needs to move past at least one zero.
//    Q: Could you do this with fewer writes?
//    A: Yes -- track the position of the first zero with `j`, then when a
//       non-zero is found ahead of it, swap and advance `j` by one instead
//       of doing a full swap for every non-zero (skips redundant self-swaps
//       when no zero has been seen yet). Same asymptotic complexity, fewer
//       constant-factor operations.
//  --- Related Problems ---
//    Easy   : Remove Element (LC27).
//    Medium : Sort Colors (Problem 14 below) -- 3-way partition generalization.


// ================================================================================
//  PROBLEM 8 — UNION OF TWO SORTED ARRAYS
// ================================================================================
//
//  --- 🎯 Interview-Ready Add-Ons (constraints, timing, pitch) ---
//  Asked at      : Amazon, Adobe -- common as a 'merge step of merge sort, but with dedup' variation.
//  Constraints   : 1 <= m,n <= 10^5 -> O(m+n) two-pointer merge expected; sorting the concatenation (O((m+n) log(m+n))) should be named explicitly as sub-optimal given both inputs are ALREADY sorted.
//  Time-boxing   : Total ~8 min: 1 min restate, 1 min note both inputs are sorted (the key exploitable fact), 6 min merge-step code + dry run including the drain-remainder phase.
//  60-Sec Pitch  : "Since both arrays are already sorted, I merge them like the merge step of Merge Sort -- always advancing whichever pointer has the smaller head value -- and use the value itself as a hash key so duplicates (within or across arrays) collapse automatically."
//
//
//  --- How to Identify This Pattern ---
//  Keywords     : "union of two sorted arrays", "merge without duplicates"
//  Signal       : TWO sorted inputs + "combine" is the classic Merge-Step
//                 (borrowed from Merge Sort) two-pointer signature.
//  Common mistake: Concatenating both arrays and then sorting + deduping --
//                 O((m+n) log(m+n)) when a linear merge achieves O(m+n).
//
//  --- Problem Understanding ---
//  What: given two SORTED arrays (possibly with internal duplicates),
//        return their sorted union (each value appearing once).
//  Why it exists: the merge-step of Merge Sort is one of the most reused
//    primitives in DSA (external sorting, k-way merge, interval merging).
//
//  --- Approach: Two-Pointer Merge with Dedup (Optimal) ---
//  Intuition : advance whichever pointer points to the smaller head value;
//              on a tie, advance both (adds the value once). A hash-keyed
//              array as the accumulator gives automatic dedup even within
//              a single array's own repeats.
//  TC: O(m + n)  |  SC: O(m + n) for the output
//
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

//  --- Dry Run ---  arr1=[1,2,3,4,5], arr2=[2,3,4,4,5,11,12]
//  i=0,j=0: 1<2 -> add 1, i=1
//  i=1,j=0: 2==2 -> add 2, i=2,j=1
//  i=2,j=1: 3==3 -> add 3, i=3,j=2
//  i=3,j=2: 4==4 -> add 4, i=4,j=3
//  i=4,j=3: 5>4  -> add 4 (already present, no-op via key) j=4
//  i=4,j=4: 5==5 -> add 5, i=5,j=5
//  arr1 exhausted -> drain arr2: 11, 12
//  Output: [1,2,3,4,5,11,12]

$result = findUnion([1, 2, 3, 4, 5], [2, 3, 4, 4, 5, 11, 12]);
echo "Union: " . implode(", ", $result) . "\n";

//  --- Patterns Used ---   Primary: Two Pointers (merge-step).  Secondary: Hashing for dedup.
//  --- Recognition Tips ---
//    Use when: BOTH inputs are already sorted.
//    Don't use when: inputs are unsorted -- then a plain HashSet union
//              (O(m+n) time, no sort needed) is simpler and just as fast.
//    Similar problems: Merge Sorted Array (LC88), Intersection of Two
//              Arrays (LC349), Merge Two Sorted Lists (linked-list analogue).
//  --- Edge Cases ---
//    - One array empty -> loop skips straight to draining the other -- correct.
//    - Both empty       -> returns [] immediately.
//    - Fully overlapping arrays -> every value taken once via the "equal" branch.
//    - Arrays with internal duplicates (e.g., arr2 has 4 twice) -> the hash
//      key naturally collapses them; demonstrated in the dry run above.
//
//  --- 🧪 Additional Senior-Level Prep (tests, gotchas, follow-ups) ---
//  assert(findUnion([1,2,3,4,5], [2,3,4,4,5,11,12]) === [1,2,3,4,5,11,12]);
//  assert(findUnion([], [1,2,3]) === [1,2,3]);      // one array empty
//  assert(findUnion([], []) === []);                 // both empty
//  PHP Gotcha       : Using the value as an array key (`$union[$value] = $value`) only works cleanly for INTEGER or STRING-safe values -- PHP silently casts float keys to int, which would corrupt a union of floating-point numbers; flag this explicitly if the input isn't guaranteed to be integers.
//  Mistake Recovery : If asked for the INTERSECTION instead mid-interview and you freeze, don't restart from scratch -- point out this is the SAME merge skeleton with only the 'equal' branch keeping its addition, the other two branches just advance pointers without adding.
//  Follow-Up / Scale-Up:
//    - Compute the INTERSECTION instead of the union -> only add on the equal-values branch.
//    - What if the arrays are NOT sorted? -> fall back to two HashSets (still O(m+n), but O(m+n) space instead of relying on sortedness for the pointer walk).
//
//  --- Interview Discussion ---
//    Q: How would you compute the INTERSECTION instead of the union?
//    A: Only add a value when arr1[i] === arr2[j] (the equal branch);
//       discard the two `elseif` branches' additions -- just advance
//       pointers without adding.
//    Q: What if the arrays were NOT sorted?
//    A: Union: dump both into an associative array (O(m+n)) with no need
//       for pointers. Intersection: build a frequency map of one array,
//       scan the other checking membership.
//  --- Related Problems ---
//    Easy   : Intersection of Two Arrays (LC349).
//    Medium : Merge Sorted Array (LC88) -- in-place merge variant.
//    Hard    : Merge k Sorted Lists (LC23) -- generalizes to k inputs via a heap.


// ================================================================================
//  PROBLEM 9 — LC 268: MISSING NUMBER (0 to N)
// ================================================================================
//
//  --- 🎯 Interview-Ready Add-Ons (constraints, timing, pitch) ---
//  Asked at      : Amazon, Microsoft, Apple -- classic warm-up, frequently used as a lead-in to 'now find it using ONLY bitwise ops.'
//  Constraints   : 0 <= n <= 10^4, nums contains n distinct values from [0,n] -> O(n) time, O(1) space expected; a HashSet approach (O(n) space) is a valid 'Better' step but should be explicitly improved upon.
//  Time-boxing   : Total ~8 min: 1 min restate the range guarantee, 2 min brute/hashmap, 5 min Gauss-sum AND XOR approaches + explain the overflow trade-off between them.
//  60-Sec Pitch  : "Since the array is guaranteed to hold n distinct values from a KNOWN contiguous range [0,n] with exactly one missing, I can either subtract the actual sum from the expected Gauss sum, or XOR every index 0..n together with every array value so all present numbers cancel out, leaving only the missing one."
//
//
//  --- How to Identify This Pattern ---
//  Keywords     : "array contains n distinct numbers in range [0,n]",
//                 "find the missing number"
//  Signal       : A KNOWN, CONTIGUOUS numeric range with exactly ONE value
//                 missing is the classic trigger for either the Gauss-sum
//                 trick or the XOR trick -- both O(n) time, O(1) space,
//                 no hashmap required.
//  Common mistake: Reaching for a HashSet/HashMap (O(n) space) when the
//                 range is fully known and contiguous -- that's a strictly
//                 worse solution here on space, even though it's O(n) time too.
//
//  --- Problem Understanding ---
//  What: given n distinct numbers taken from [0, n], exactly one number in
//        that range is missing from the array (which has n elements, not n+1).
//        Find it.
//  Why it exists: teaches that a "closed-form expected total" (Gauss's
//    formula) or a "self-cancelling operation" (XOR) can replace an
//    explicit membership-tracking structure entirely.
//
//  --- Approach 1: Brute Force -- linear search for each candidate (for contrast) ---
//  Intuition : for every candidate i in [0, n], scan the whole array to see
//              if i is present.
//  TC: O(n^2)  |  SC: O(1)
//  Disadvantage: quadratic; scanning is wasteful when arithmetic can solve
//              this in one pass.
//
//  --- Approach 2: HashMap membership (Better) ---
//  Intuition : mark every seen number in a hashmap, then scan [0, n] for
//              the one missing key.
//  TC: O(n)  |  SC: O(n)
//  Disadvantage: uses O(n) extra space when O(1) is achievable.
//
//  --- Approach 3a: Gauss Sum Formula (Optimal) ---
//  Intuition : the sum of 0..n is a known closed form: n*(n+1)/2. Subtract
//              the array's actual sum -- the difference is exactly the
//              missing number.
//  TC: O(n)  |  SC: O(1)
//
function missingNumberGaussSum(array $nums): int {
    $n = count($nums);                    // nums has n elements, representing range [0, n]
    $expectedSum = intdiv($n * ($n + 1), 2);   // Sum of 0..n via Gauss's formula
    $actualSum   = array_sum($nums);

    return $expectedSum - $actualSum;     // Whatever's missing accounts for the shortfall
}

//  --- Approach 3b: XOR Trick (Optimal, avoids any risk of integer overflow) ---
//  Intuition : XOR every index 0..n together with every array value
//              together. Every number that's PRESENT appears exactly twice
//              in the combined XOR stream (once as an index, once as a
//              value) and cancels to 0 (x^x=0). Only the missing number's
//              index has no matching array value, so it survives the XOR.
//  TC: O(n)  |  SC: O(1)
//  Advantage over Gauss sum: immune to integer overflow on very large n
//  (XOR never produces a value larger than the operands, unlike a sum).
//
function missingNumberXOR(array $nums): int {
    $n = count($nums);
    $xorAll = 0;

    for ($i = 0; $i <= $n; $i++) {
        $xorAll ^= $i;          // XOR in every index from the expected full range 0..n
    }
    foreach ($nums as $num) {
        $xorAll ^= $num;        // XOR in every actual array value -- present values cancel out
    }

    return $xorAll;             // Only the missing number's contribution survives
}

//  --- Dry Run (Gauss Sum) ---  nums = [3, 0, 1]
//  n=3, expected = 3*4/2 = 6
//  actual = 3+0+1 = 4
//  missing = 6-4 = 2

echo "Missing (Gauss): " . missingNumberGaussSum([3, 0, 1]) . "\n";  // 2

//  --- Dry Run (XOR) ---  nums = [1,2,3,4,5,7,8,9,10]  (n=9, missing 6)
//  xorAll starts 0, XOR in 0..9, then XOR in every array value.
//  Every value 1..5,7..10 appears both as an index and as a value -> cancels.
//  Only index 6 has no matching array value -> survives -> result = 6

echo "Missing (XOR): " . missingNumberXOR([1, 2, 3, 4, 5, 7, 8, 9, 10]) . "\n";  // 6

//  --- Patterns Used ---   Primary: Bit Manipulation (XOR) / Math (Gauss Sum).  Secondary: Cyclic-Sort family.
//  --- Recognition Tips ---
//    Use when: range is KNOWN, CONTIGUOUS, and exactly one value is missing/duplicated.
//    Don't use when: multiple values are missing, or the range is not
//              contiguous/known -- then a HashSet-based approach or Cyclic
//              Sort (placing each value at its index) generalizes better.
//    Similar problems: Find All Numbers Disappeared in an Array (LC448,
//              Cyclic Sort), Find the Duplicate Number (LC287, Floyd's
//              Cycle Detection on the value-as-next-index trick), First
//              Missing Positive (LC41, Cyclic Sort).
//  --- Edge Cases ---
//    - n = 0 (single-element input representing range [0,0] minus itself)
//      -> Gauss sum handles it: expected=0, actual=0 or the one value present.
//    - Missing number is 0       -> both approaches handle this correctly
//      (0 contributes nothing to a sum, and XOR-ing 0 is a no-op, both fine).
//    - Missing number is n (the largest) -> handled symmetrically, no special case needed.
//    - Very large n -> Gauss sum can theoretically overflow on 32-bit
//      systems (less of a concern in PHP's 64-bit int, but worth mentioning
//      the XOR approach as the overflow-safe alternative in an interview).
//
//  --- 🧪 Additional Senior-Level Prep (tests, gotchas, follow-ups) ---
//  assert(missingNumberGaussSum([3,0,1]) === 2);
//  assert(missingNumberXOR([1,2,3,4,5,7,8,9,10]) === 6);
//  assert(missingNumberGaussSum([0]) === 1);    // smallest possible input, n=1
//  assert(missingNumberGaussSum([1]) === 0);    // missing number is 0 itself
//  PHP Gotcha       : PHP's native int is 64-bit on 64-bit systems, so Gauss-sum overflow is a non-issue in PHP specifically -- but say this OUT LOUD as a deliberate observation, don't just silently ignore the overflow question, since the interviewer is testing whether you're AWARE of it even if this particular language sidesteps it.
//  Mistake Recovery : If you blank on the XOR trick under pressure, fall back to Gauss-sum first (easier to derive live) and mention XOR as 'the overflow-safe alternative I'd reach for in a fixed-width-integer language like Java or C++' -- showing you know both is often enough even without perfect XOR recall.
//  Follow-Up / Scale-Up:
//    - Find TWO missing numbers instead of one -> XOR everything to get (missing1 XOR missing2), pick any set bit to partition all numbers into two groups, XOR each group separately.
//    - What if the array could have a DUPLICATE instead of a missing number (LC287)? -> switch to Floyd's Cycle Detection treating values as 'next pointers' -- a completely different technique despite the surface similarity.
//
//  --- Interview Discussion ---
//    Q: Why prefer XOR over the sum formula?
//    A: XOR is immune to overflow entirely, since the result is always
//       bounded by the bit-width of the operands -- a summation can, in
//       principle, overflow for astronomically large n on fixed-width
//       integer systems.
//    Q: How would you find TWO missing numbers instead of one?
//    A: XOR everything together first to get (missing1 XOR missing2), find
//       any set bit in that XOR result to partition all numbers into two
//       groups by that bit, then XOR each group separately to isolate each
//       missing number individually.
//  --- Related Problems ---
//    Easy   : Single Number (Problem 11 below) -- same XOR-cancellation core idea.
//    Medium : Find All Numbers Disappeared in an Array (LC448) -- Cyclic Sort.
//    Hard    : Find the Duplicate Number (LC287) -- Floyd's Cycle Detection twist.


// ================================================================================
//  PROBLEM 10 — LC 485: MAX CONSECUTIVE ONES
// ================================================================================
//
//  --- 🎯 Interview-Ready Add-Ons (constraints, timing, pitch) ---
//  Asked at      : Amazon, Facebook/Meta -- ubiquitous easy warm-up, often the FIRST question asked.
//  Constraints   : 1 <= n <= 10^5, binary array -> O(n) single pass with a running counter; no window/pointers needed for the base version.
//  Time-boxing   : Total ~4 min: near-instant -- this should never take more than 5 minutes end to end including the dry run.
//  60-Sec Pitch  : "I extend a running counter on every 1 and hard-reset it to 0 on every 0, comparing against a global max at each step -- no need for explicit window pointers since there's nothing to shrink, only reset."
//
//
//  --- How to Identify This Pattern ---
//  Keywords     : "maximum consecutive", "longest run of 1s", binary array
//  Signal       : "Consecutive" / "run of" on a simple binary array is the
//                 leanest possible Sliding Window (in fact so simple it
//                 doesn't even need explicit left/right pointers -- a
//                 running counter suffices).
//  Common mistake: Overcomplicating this with an actual two-pointer window
//                 when a single running counter reset on violation is
//                 sufficient and clearer.
//
//  --- Problem Understanding ---
//  What: find the length of the longest run of consecutive 1s in a binary array.
//  Why it exists: the simplest possible "reset-on-violation" counter
//    pattern -- the mental stepping stone to LC1004 (Max Consecutive Ones
//    III, which allows flipping up to K zeroes -- a true sliding window).
//
//  --- Approach: Running Counter with Reset (Optimal, only approach needed) ---
//  Intuition : extend a counter while seeing 1s; reset to 0 the instant a 0
//              is seen; track the global max after every step.
//  TC: O(n)  |  SC: O(1)
//
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

//  --- Dry Run ---  nums = [1,1,0,1,1,1]
//  ┌───┬─────┬────────────┬────────┐
//  │ i │ num │ currentRun │ maxRun │
//  ├───┼─────┼────────────┼────────┤
//  │ 0 │ 1   │ 1          │ 1      │
//  │ 1 │ 1   │ 2          │ 2      │
//  │ 2 │ 0   │ 0 (reset)  │ 2      │
//  │ 3 │ 1   │ 1          │ 2      │
//  │ 4 │ 1   │ 2          │ 2      │
//  │ 5 │ 1   │ 3          │ 3      │
//  └───┴─────┴────────────┴────────┘
//  Output: 3

echo "Max consecutive ones: " . findMaxConsecutiveOnes([1, 1, 0, 1, 1, 1]) . "\n";  // 3

//  --- Patterns Used ---   Primary: Sliding Window (implicit, counter-based).
//  --- Recognition Tips ---
//    Use when: you need the longest run satisfying a simple per-element condition.
//    Don't use when: the condition involves a BUDGET (e.g., "at most K
//              zeroes allowed") -- that needs a true expand/shrink window
//              with two pointers (LC1004 -- already implemented in your
//              Two Pointer & Sliding Window file).
//    Similar problems: Max Consecutive Ones II/III (LC487, LC1004),
//              Longest Substring Without Repeating Characters (LC3).
//  --- Edge Cases ---
//    - All zeroes  -> maxRun stays 0. Correct.
//    - All ones    -> maxRun equals array length. Correct.
//    - Empty array -> loop never runs, returns 0. Correct.
//
//  --- 🧪 Additional Senior-Level Prep (tests, gotchas, follow-ups) ---
//  assert(findMaxConsecutiveOnes([1,1,0,1,1,1]) === 3);
//  assert(findMaxConsecutiveOnes([0,0,0]) === 0);   // all zeroes
//  assert(findMaxConsecutiveOnes([1,1,1]) === 3);   // all ones
//  assert(findMaxConsecutiveOnes([]) === 0);        // empty input
//  Mistake Recovery : If the interviewer immediately follows up with 'now allow flipping K zeroes' and you try to bolt a budget onto this counter-reset version, stop -- that's a structurally different algorithm (true sliding window with two pointers), not a small tweak to this one.
//  Follow-Up / Scale-Up:
//    - Allow flipping up to K zeroes (LC1004) -> switch to a true expand/shrink sliding window tracking a zero-count budget, not a reset-counter.
//    - What if this were a live sensor stream reporting max run so far at any moment? -> the same O(1)-space counter naturally supports this; just expose maxRun after each new reading instead of only at the end.
//
//  --- Interview Discussion ---
//    Q: How does this extend to "at most K zeroes can be flipped to 1"?
//    A: That's LC1004 -- switch to a true sliding window: expand right
//       always; shrink left whenever the zero-count inside the window
//       exceeds K. Track max window size throughout, not a reset-counter.
//  --- Related Problems ---
//    Easy   : (this is already the easy tier)
//    Medium : Max Consecutive Ones III (LC1004) -- sliding window with budget.
//    Medium : Longest Substring Without Repeating Characters (LC3).


// ================================================================================
//  PROBLEM 11 — LC 136: SINGLE NUMBER (XOR TRICK)
// ================================================================================
//
//  --- 🎯 Interview-Ready Add-Ons (constraints, timing, pitch) ---
//  Asked at      : Amazon, Microsoft, Google -- the canonical intro to XOR-based bit tricks, often paired with a 'can you do it in O(1) space' follow-up.
//  Constraints   : 1 <= n <= 3*10^4, exactly one element appears once, ALL others exactly twice, O(1) EXTRA SPACE explicitly required -> this space constraint is the whole point of the question.
//  Time-boxing   : Total ~6 min: 1 min restate, 2 min HashMap Better approach (and explicitly name it as O(n) space), 3 min XOR Optimal + dry run showing the cancellation.
//  60-Sec Pitch  : "Since every duplicate pair XORs to zero and XOR with zero is a no-op, I XOR every element together in one pass -- the duplicates silently cancel out and only the unique value survives, in O(1) space."
//
//
//  --- How to Identify This Pattern ---
//  Keywords     : "every element appears twice except one", "find that
//                 single one", O(1) EXTRA SPACE constraint explicitly stated
//  Signal       : "Everything appears twice except one" + an O(1)-space
//                 requirement is THE textbook XOR-cancellation signature.
//  Common mistake: Reaching for a HashMap frequency count -- correct but
//                 O(n) space, when the O(1)-space constraint is explicitly
//                 telling you XOR is expected.
//
//  --- Problem Understanding ---
//  What: every element in the array appears exactly twice except one, which
//        appears exactly once. Find that element.
//  Why it exists: the foundational XOR-cancellation problem -- everything
//    in Bit Manipulation's "Single Number" family (I/II/III) builds on this
//    core insight: x^x=0, x^0=x, XOR is commutative and associative.
//
//  --- Approach 1: HashMap Frequency Count (Better, not optimal on space) ---
//  Intuition : count occurrences of every value; return the one with count 1.
//  TC: O(n)  |  SC: O(n)
//
function singleNumberHashMap(array $nums): int {
    $freq = [];
    foreach ($nums as $num) {
        $freq[$num] = ($freq[$num] ?? 0) + 1;   // Tally occurrences
    }
    foreach ($freq as $value => $count) {
        if ($count === 1) return $value;         // The lone survivor
    }
    return -1;   // Should never happen if the problem's guarantee holds
}

//  --- Approach 2: XOR Cancellation (Optimal) ---
//  Intuition : XOR every element together. Since x^x=0 and x^0=x, and XOR
//              is commutative/associative, every duplicate pair cancels to
//              0 regardless of order, leaving only the unique element.
//  TC: O(n)  |  SC: O(1)
//
function singleNumber(array $nums): int {
    $result = 0;
    foreach ($nums as $num) {
        $result ^= $num;   // Duplicates cancel (a^a=0); result ends up holding the lone value
    }
    return $result;
}

//  --- Dry Run ---  nums = [4, 1, 2, 1, 2]
//  ┌───────┬─────────┬────────────────────┐
//  │ step  │ num     │ result (binary)    │
//  ├───────┼─────────┼────────────────────┤
//  │ start │ --      │ 000 (0)            │
//  │ 1     │ 4       │ 100 (4)            │
//  │ 2     │ 1       │ 101 (5)            │
//  │ 3     │ 2       │ 111 (7)            │
//  │ 4     │ 1       │ 110 (6)            │
//  │ 5     │ 2       │ 100 (4)            │
//  └───────┴─────────┴────────────────────┘
//  Output: 4

echo "Single number: " . singleNumber([4, 1, 2, 1, 2]) . "\n";  // 4

//  --- Patterns Used ---   Primary: Bit Manipulation (XOR).  Secondary: Hashing (brute alternative).
//  --- Recognition Tips ---
//    Use when: "appears twice except one" + O(1) space constraint.
//    Don't use when: elements appear THREE times except one (need the
//              bit-counting-mod-3 trick, LC137) or TWO unique elements
//              exist instead of one (LC260 -- partition by a differing bit).
//    Similar problems: Single Number II (LC137), Single Number III (LC260),
//              Missing Number (Problem 9 -- same XOR-cancellation family).
//  --- Edge Cases ---
//    - Single-element array -> XOR of one number with nothing is itself. Correct.
//    - Negative numbers      -> XOR operates on two's-complement bit
//      patterns; works correctly regardless of sign in PHP's 64-bit ints.
//
//  --- 🧪 Additional Senior-Level Prep (tests, gotchas, follow-ups) ---
//  assert(singleNumber([4,1,2,1,2]) === 4);
//  assert(singleNumber([1]) === 1);           // single-element array
//  assert(singleNumber([-3,-3,7]) === 7);      // negative numbers work identically via two's-complement bit patterns
//  PHP Gotcha       : PHP's `^` operator works correctly on negative ints via native two's-complement representation -- no special-casing needed, unlike some languages where bitwise ops on signed vs unsigned types behave differently.
//  Mistake Recovery : If the interviewer changes the problem to 'appears three times except one' mid-conversation and your instinct is to still reach for a single XOR pass, catch yourself -- x^x^x = x, not 0, so plain XOR cancellation breaks; that variant needs bit-counting mod 3 instead (LC137).
//  Follow-Up / Scale-Up:
//    - Every element appears THREE times except one (LC137) -> track bit counts mod 3 across all bit positions, not a single XOR accumulator.
//    - TWO unique elements instead of one (LC260) -> XOR everything first to get (unique1 XOR unique2), then use any set bit in that result to partition the array and XOR each partition separately.
//
//  --- Interview Discussion ---
//    Q: Why does order not matter for the XOR result?
//    A: XOR is both commutative (a^b = b^a) and associative
//       ((a^b)^c = a^(b^c)) -- so the duplicate pairs can be conceptually
//       regrouped adjacent to each other regardless of their actual array
//       positions, and each pair cancels to 0 independently.
//    Q: Follow-up -- every element appears THREE times except one?
//    A: Track bit counts mod 3 across all 32/64 bits (LC137) -- XOR alone
//       can't distinguish "appeared 3 times" from "appeared 0 times" since
//       x^x^x = x, not 0.
//  --- Related Problems ---
//    Medium : Single Number II (LC137) -- bit-count mod 3.
//    Medium : Single Number III (LC260) -- two unique elements.
//    Easy    : Missing Number (Problem 9 above).


// ================================================================================
//  PROBLEM 12 — LONGEST SUBARRAY WITH SUM EQUALS K  (two variants)
// ================================================================================
//
//  --- 🎯 Interview-Ready Add-Ons (constraints, timing, pitch) ---
//  Asked at      : Amazon, Google, Flipkart -- frequently asked with a deliberate 'does this array contain negatives?' trap baked into the constraints.
//  Constraints   : 1 <= n <= 10^5, values can be negative (check this explicitly!) -> O(n) Prefix Sum + HashMap if negatives are possible; O(n) Sliding Window ONLY if the problem guarantees non-negative values.
//  Time-boxing   : Total ~10 min: 2 min restate + explicitly confirm sign constraints with the interviewer, 3 min brute force, 5 min optimal (whichever engine the constraints justify) + dry run.
//  60-Sec Pitch  : "The very first thing I do is check whether negative numbers are allowed -- if yes, I use prefix sums with a hashmap storing the FIRST index each running sum was seen at; if the array is guaranteed non-negative, a true expand/shrink sliding window is simpler and uses O(1) space instead."
//
//
//  --- How to Identify This Pattern ---
//  Keywords     : "longest subarray", "sum equals k", "contiguous subarray"
//  Signal       : "Longest/shortest CONTIGUOUS subarray with sum = K" is the
//                 flagship Prefix-Sum + HashMap problem WHEN NEGATIVE
//                 NUMBERS ARE ALLOWED. If the array is guaranteed
//                 non-negative, a true Sliding Window becomes valid too
//                 (and is simpler) -- both are shown below.
//  Hidden hint  : Whether negatives are allowed is the single deciding
//                 factor between these two engines -- always ask/check the
//                 constraints before picking one.
//  Common mistake: Using Sliding Window on an array that CAN contain
//                 negative numbers -- shrinking the window when "sum > k"
//                 is not a valid strategy anymore, because adding a
//                 negative number later could bring the sum back down,
//                 meaning the window boundary logic silently gives wrong answers.
//
//  --- Problem Understanding ---
//  What: find the length of the longest contiguous subarray whose elements
//        sum to exactly k.
//  Why it exists: prefix sums convert "subarray sum" questions into "index
//    difference" questions, turning an O(n^2) all-pairs check into O(n).
//
//  --- Approach 1: Brute Force -- all subarrays (for contrast) ---
//  Intuition : for every (start, end) pair, sum the subarray and check == k.
//  TC: O(n^2) (or O(n^3) if you re-sum from scratch each time instead of
//      extending a running sum)  |  SC: O(1)
//
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

//  --- Approach 2: Prefix Sum + HashMap (Optimal -- works with negatives) ---
//  Intuition : let prefixSum[i] = sum(nums[0..i]). A subarray (j+1..i) sums
//              to k exactly when prefixSum[i] - prefixSum[j] = k, i.e.
//              prefixSum[j] = prefixSum[i] - k. Store the FIRST index each
//              prefix sum was seen at (to maximize subarray length when
//              multiple j's would work).
//  TC: O(n)  |  SC: O(n)
//
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

//  --- Approach 3: Sliding Window (Optimal, ONLY valid for non-negative arrays) ---
//  Intuition : expand the window by moving `right`; whenever the running
//              sum exceeds k, shrink from `left`. This works ONLY because
//              non-negative numbers guarantee the sum is monotonic as the
//              window grows -- shrinking always decreases it predictably.
//  TC: O(n) (each pointer moves forward at most n times)  |  SC: O(1)
//
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

//  --- Dry Run (Prefix Sum + HashMap) ---  nums = [10,5,2,7,1,9], k=15
//  ┌───┬─────┬─────┬───────────┬──────────────────────┬────────┐
//  │ i │ num │ sum │ remainder │ hash lookup           │ maxLen │
//  ├───┼─────┼─────┼───────────┼──────────────────────┼────────┤
//  │ 0 │ 10  │ 10  │ -5        │ miss                  │ 0      │
//  │ 1 │ 5   │ 15  │ 0         │ sum==k -> maxLen=2    │ 2      │
//  │ 2 │ 2   │ 17  │ 2         │ miss                  │ 2      │
//  │ 3 │ 7   │ 24  │ 9         │ miss                  │ 2      │
//  │ 4 │ 1   │ 25  │ 10        │ hit at idx 0 -> len=4 │ 4      │
//  │ 5 │ 9   │ 34  │ 19        │ miss                  │ 4      │
//  └───┴─────┴─────┴───────────┴──────────────────────┴────────┘
//  Output: 4  (subarray [5,2,7,1], indices 1..4)

echo "Longest subarray sum=15 (prefix+hash): " . longestSubarraySumK([10, 5, 2, 7, 1, 9], 15) . "\n";  // 4
echo "Longest subarray sum=15 (sliding window, all positive): " . longestSubarrayPositive([10, 5, 2, 7, 1, 9], 15) . "\n";  // 4

//  --- Patterns Used ---   Primary: Prefix Sum + HashMap.  Secondary: Sliding Window (positive-only variant).
//  --- Recognition Tips ---
//    Use Prefix Sum + HashMap when: negatives are possible, or you're unsure.
//    Use Sliding Window when: the problem GUARANTEES all non-negative values
//              (strictly faster in practice: O(1) space instead of O(n)).
//    Similar problems: Subarray Sum Equals K -- COUNT not length (Problem 24
//              below, same prefix-sum core), Maximum Size Subarray Sum
//              Equals k (LC325, same problem, different LC number).
//  --- Edge Cases ---
//    - k = 0 with negatives present -> still handled correctly by prefix-sum approach.
//    - No subarray sums to k at all -> maxLen stays 0. Correct.
//    - Entire array sums to k -> maxLen = n, caught by the `sum === k` check
//      directly (not just via the hashmap lookup).
//    - Sliding window on an array WITH a negative number -> gives WRONG
//      answers silently; always verify non-negativity before choosing this approach.
//
//  --- 🧪 Additional Senior-Level Prep (tests, gotchas, follow-ups) ---
//  assert(longestSubarraySumK([10,5,2,7,1,9], 15) === 4);
//  assert(longestSubarrayPositive([10,5,2,7,1,9], 15) === 4);   // same answer via the non-negative-only engine
//  assert(longestSubarraySumK([-2,-1,2,1], 1) === 2);            // negatives present -- sliding window would silently break here
//  Mistake Recovery : The single most dangerous silent bug in this problem: applying the sliding-window shrink logic to an array that turns out to contain a negative number. If you're mid-way through a sliding-window implementation and the interviewer casually mentions 'oh, values can be negative,' STOP and switch engines out loud rather than patching the window logic.
//  Follow-Up / Scale-Up:
//    - Count HOW MANY subarrays sum to k, not the longest one -> switch the hashmap from storing first-index to storing frequency (this is exactly Problem 24 below).
//    - What if k itself can change between queries on the same array (many queries, same array)? -> precompute the prefix sum array once, then each query is an O(n) scan of that precomputed array (or O(n) with a fresh hashmap per query if k varies), rather than recomputing prefix sums from scratch each time.
//
//  --- Interview Discussion ---
//    Q: Why store only the FIRST occurrence of each prefix sum?
//    A: For a "longest subarray" question, an earlier j always yields a
//       longer (or equal) subarray length (i - j) than a later j with the
//       same prefix sum value -- so overwriting with later indices would
//       only ever shrink your candidate answer.
//    Q: How would this change if the question asked for the SHORTEST subarray?
//    A: You'd want the LATEST occurrence of each prefix sum instead --
//       overwrite the hashmap entry every time, don't guard with isset().
//  --- Related Problems ---
//    Medium : Subarray Sum Equals K (Problem 24 below) -- counts, not length.
//    Medium : Maximum Size Subarray Sum Equals k (LC325).
//    Hard    : Minimum Size Subarray Sum (LC209) -- true sliding window, min length, positive-only.


// ================================================================================
//  PROBLEM 13 — LC 1: TWO SUM (+ sorted two-pointer variant)
// ================================================================================
//
//  --- 🎯 Interview-Ready Add-Ons (constraints, timing, pitch) ---
//  Asked at      : Nearly EVERY company, including Amazon/Google/Meta as an OA or phone-screen filter question -- expect this in some form at almost every interview loop you do.
//  Constraints   : 2 <= n <= 10^4, exactly one solution guaranteed, indices must be returned -> O(n) HashMap expected; O(n log n) sort+two-pointer loses the original indices unless you track them separately.
//  Time-boxing   : Total ~5 min: this is a calibration question -- if it takes longer than 5-6 minutes total, that's a signal to drill fundamentals before moving to harder patterns.
//  60-Sec Pitch  : "For each element I check whether its complement (target minus the current value) was already seen, storing value-to-index as I go -- this turns an O(n^2) all-pairs check into a single O(n) pass with O(n) space."
//
//
//  --- How to Identify This Pattern ---
//  Keywords     : "two numbers that add up to target", "return indices"
//  Signal       : THE canonical HashMap "have I seen the complement"
//                 problem. If indices matter -> HashMap. If only
//                 existence/values matter AND the array is sorted (or can
//                 be sorted) -> Two Pointers is simpler and uses O(1) space.
//  Common mistake: Sorting the array to use two pointers when the ORIGINAL
//                 INDICES must be returned -- sorting destroys index
//                 information unless you track it separately. Read the
//                 problem statement carefully: "return indices" -> HashMap
//                 is almost always the correct choice.
//
//  --- Problem Understanding ---
//  What: given an array and a target, return the indices of the two
//        numbers that add up to target (exactly one solution guaranteed).
//  Why it exists: the single most-repeated interview warm-up question --
//    tests whether you instinctively reach for a HashMap to turn an O(n^2)
//    all-pairs check into O(n).
//
//  --- Approach 1: Brute Force -- all pairs (for contrast) ---
//  Intuition : check every pair (i, j) with i < j.
//  TC: O(n^2)  |  SC: O(1)
//
function twoSumBrute(array $nums, int $target): array {
    for ($i = 0; $i < count($nums); $i++) {
        for ($j = $i + 1; $j < count($nums); $j++) {
            if ($nums[$i] + $nums[$j] === $target) return [$i, $j];
        }
    }
    return [];
}

//  --- Approach 2: HashMap, One Pass (Optimal when indices are required) ---
//  Intuition : for each element, check whether its COMPLEMENT (target - x)
//              was already seen. If yes, we've found our pair immediately
//              -- no need for a second pass. Store complement -> index
//              BEFORE checking, or store value -> index and check for the
//              complement of the CURRENT element (equivalent, shown here).
//  TC: O(n)  |  SC: O(n)
//
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

//  --- Approach 3: Two Pointers on Sorted Copy (Optimal for YES/NO or VALUES, not original indices) ---
//  Intuition : sort the array; if the two-element sum is too small, move
//              the left pointer right (increase sum); if too big, move the
//              right pointer left (decrease sum).
//  TC: O(n log n) for the sort, O(n) for the scan  |  SC: O(1) extra (O(n) if a copy is made to preserve original array)
//  Trade-off: loses the ORIGINAL indices unless you track (value, original
//             index) pairs explicitly before sorting.
//
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

//  --- Dry Run (HashMap) ---  nums = [2,7,11,15], target=9
//  ┌───┬─────┬────────────┬─────────────────────┬────────────┐
//  │ i │ num │ complement │ seenValueToIndex     │ action     │
//  ├───┼─────┼────────────┼─────────────────────┼────────────┤
//  │ 0 │ 2   │ 7          │ {} -> miss           │ store {2:0}│
//  │ 1 │ 7   │ 2          │ {2:0} -> HIT         │ return [0,1]│
//  └───┴─────┴────────────┴─────────────────────┴────────────┘

print_r(twoSum([2, 7, 11, 15], 9));  // [0, 1]

//  --- Patterns Used ---   Primary: Hashing.  Secondary: Two Pointers (sorted variant).
//  --- Recognition Tips ---
//    Use HashMap when: original indices matter, or the array is unsorted
//              and you want a single O(n) pass.
//    Use Two Pointers when: array is already sorted (or index doesn't
//              matter) -- gives O(1) space instead of O(n).
//    Similar problems: 3Sum (Problem 27, extends this with an outer loop +
//              two pointers), 4Sum (extends further with two outer loops),
//              Two Sum II - Input Array Is Sorted (LC167, literally
//              Approach 3 above as its own LeetCode problem).
//  --- Edge Cases ---
//    - Duplicate values that ARE the answer (e.g., nums=[3,3], target=6)
//      -> the HashMap approach handles this correctly because it checks
//      the complement BEFORE storing the current element, so nums[0]=3 is
//      stored first, then nums[1]=3 finds it as a valid complement.
//    - No valid pair exists -> HashMap returns [] ; two-pointer returns false.
//    - Negative numbers -> both approaches handle them natively, no special casing needed.
//
//  --- 🧪 Additional Senior-Level Prep (tests, gotchas, follow-ups) ---
//  assert(twoSum([2,7,11,15], 9) === [0,1]);
//  assert(twoSum([3,3], 6) === [0,1]);          // duplicate values that ARE the answer
//  assert(twoSumExists([2,7,11,15], 9) === true);   // sorted two-pointer variant, existence only
//  assert(twoSumExists([1,2,3], 100) === false);
//  PHP Gotcha       : Checking the complement BEFORE inserting the current value into the map is what prevents an element from matching itself when target = 2*nums[i] -- get the ORDER of check-then-insert backwards and this exact duplicate-value edge case breaks.
//  Mistake Recovery : If the interviewer asks for the two-pointer version and you instinctively sort the array without pausing, catch yourself immediately -- sorting destroys the ORIGINAL indices this problem explicitly asks for, so name that trade-off out loud before committing to an approach.
//  Follow-Up / Scale-Up:
//    - Return ALL pairs, not just one -> don't return early; the hashmap may need to store a LIST of indices per value if duplicates can pair with different partners.
//    - Extend to 3Sum/4Sum -> fix N-2 elements with outer loop(s), two-pointer the rest on a SORTED copy (Problem 27 below).
//
//  --- Interview Discussion ---
//    Q: Why check the complement BEFORE inserting the current element?
//    A: To avoid using the SAME element twice -- if you insert first, an
//       element could incorrectly "find itself" as its own complement when
//       target = 2*nums[i].
//    Q: What if there could be MULTIPLE valid pairs and you need them all?
//    A: Don't return early -- keep collecting matches into a results array,
//       and consider whether the HashMap should store a LIST of indices
//       per value (not just one) if duplicate values are allowed to pair
//       with different other elements.
//  --- Related Problems ---
//    Easy   : Two Sum II - Input Array Is Sorted (LC167).
//    Medium : 3Sum (Problem 27 below), 4Sum (Problem 27b below).
//    Medium : Two Sum III - Data Structure Design (LC170).


// ================================================================================
//  PROBLEM 14 — LC 75: SORT COLORS (DUTCH NATIONAL FLAG ALGORITHM)
// ================================================================================
//
//  --- 🎯 Interview-Ready Add-Ons (constraints, timing, pitch) ---
//  Asked at      : Amazon, Microsoft, Facebook/Meta -- a favorite because it tests whether you truly understand WHY mid doesn't advance after a high-swap, not just whether you memorized the pointer names.
//  Constraints   : n == nums.length, 0 <= nums[i] <= 2, ONE PASS explicitly required -> O(n) three-way partition expected; calling sort() technically works but fails the 'one pass, no library sort' constraint the problem explicitly states.
//  Time-boxing   : Total ~10 min: 2 min restate + counting-sort Better approach, 6 min Dutch-Flag Optimal + a FULL dry run (this problem lives or dies on the dry run -- do it carefully), 2 min edge cases.
//  60-Sec Pitch  : "I maintain three regions with low/mid/high pointers -- 0s get swapped to the front (low), 1s stay where mid finds them, and 2s get swapped to the back (high) -- with the critical detail that mid does NOT advance after a high-swap, since the newly swapped-in value from the high side hasn't been classified yet."
//
//
//  --- How to Identify This Pattern ---
//  Keywords     : "sort an array of 0s, 1s, and 2s", "in one pass", "in-place"
//  Signal       : Exactly THREE distinct known values + "one pass, in-place"
//                 is the unmistakable Dutch National Flag (3-way
//                 partitioning) signature.
//  Common mistake: Calling a generic sort() -- technically legal but
//                 O(n log n) when the known-3-values structure allows O(n).
//                 Also, forgetting NOT to advance `mid` after a swap with
//                 `high` (the swapped-in value from the high side hasn't
//                 been classified yet and must be re-examined).
//
//  --- Problem Understanding ---
//  What: sort an array containing only 0s, 1s, and 2s in a single pass,
//        in-place, without using a library sort.
//  Why it exists: the "3-way partition" is the same core idea used inside
//    Quicksort's partition step when handling duplicate pivot values --
//    mastering it here pays off broadly.
//
//  --- Approach 1: Counting Sort (Better, two passes) ---
//  Intuition : count occurrences of 0, 1, 2 in one pass; overwrite the
//              array in a second pass using those counts.
//  TC: O(n) (two passes)  |  SC: O(1) (fixed 3-element count array)
//
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

//  --- Approach 2: Dutch National Flag, One Pass (Optimal) ---
//  Intuition : maintain three regions using three pointers:
//              [0..low-1]    = all 0s (finalized)
//              [low..mid-1]  = all 1s (finalized)
//              [mid..high]   = unexplored
//              [high+1..n-1] = all 2s (finalized)
//              `mid` scans; a 0 swaps with `low` (both advance, since the
//              value coming from `low` is always a known 1 or the initial
//              region), a 1 just advances `mid`, a 2 swaps with `high` (only
//              `high` shrinks -- `mid` stays, because the swapped-in value
//              from the high region is UNCLASSIFIED and must be re-examined).
//  TC: O(n), single pass  |  SC: O(1)
//
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

//  --- Dry Run ---  nums = [2,0,2,1,1,0]
//  ┌────────┬─────┬─────┬──────┬──────────────────────────┐
//  │ step   │ low │ mid │ high │ array state               │
//  ├────────┼─────┼─────┼──────┼──────────────────────────┤
//  │ start  │ 0   │ 0   │ 5    │ [2,0,2,1,1,0]              │
//  │ mid=2  │ 0   │ 0   │ 4    │ swap(mid,high)[0,0,2,1,1,2]│
//  │ mid=0  │ 1   │ 1   │ 4    │ swap(low,mid)[0,0,2,1,1,2] │
//  │ mid=0  │ 2   │ 2   │ 4    │ swap(low,mid)[0,0,2,1,1,2] │
//  │ mid=2  │ 2   │ 2   │ 3    │ swap(mid,high)[0,0,1,1,2,2]│
//  │ mid=1  │ 2   │ 3   │ 3    │ mid++ (1 in place)         │
//  │ mid=1  │ 2   │ 4   │ 3    │ mid++, 4>high=3 -> STOP    │
//  └────────┴─────┴─────┴──────┴──────────────────────────┘
//  Output: [0,0,1,1,2,2]

$nums = [2, 0, 2, 1, 1, 0];
sortColors($nums);
echo "Sorted colors: " . implode(", ", $nums) . "\n";  // 0,0,1,1,2,2

//  --- Patterns Used ---   Primary: Dutch National Flag (3-way partition).  Secondary: Counting Sort (alternative).
//  --- Recognition Tips ---
//    Use when: exactly 3 (or a small fixed K) distinct known values need
//              in-place sorting in one pass.
//    Don't use when: values aren't from a small fixed set -- that needs a
//              general sort or Quickselect for a specific rank.
//    Similar problems: Quicksort's Lomuto/Hoare partition (this is a 3-way
//              generalization), Sort Array By Parity (LC905, a 2-way version).
//  --- Edge Cases ---
//    - All same color -> low/mid march together to the end, or high never
//      moves; both are correctly handled by the invariant.
//    - Already sorted -> zero swaps needed; the pointers still correctly
//      terminate at mid > high.
//    - Single element -> loop runs 0 or 1 times, trivially correct.
//
//  --- 🧪 Additional Senior-Level Prep (tests, gotchas, follow-ups) ---
//  $demo = [2,0,2,1,1,0]; sortColors($demo); assert($demo === [0,0,1,1,2,2]);
//  $demo2 = [1,1,1]; sortColors($demo2); assert($demo2 === [1,1,1]);   // all one color
//  $demo3 = [0]; sortColors($demo3); assert($demo3 === [0]);          // single element
//  Mistake Recovery : The #1 way candidates fail this problem live: advancing `mid` after EVERY swap, including high-swaps. If your dry run shows a 0 or 2 slipping through unclassified into the 'finalized' 1-region, that's almost certainly the bug -- fix the high branch specifically, don't rewrite the whole function.
//  Follow-Up / Scale-Up:
//    - Generalize to K distinct colors, not just 3 -> counting sort generalizes trivially (Approach 1); the pointer-based one-pass Dutch Flag technique does NOT generalize cleanly past 3 without real complexity, worth naming that limit explicitly.
//    - What if you can't mutate the input at all (need a copy)? -> counting sort naturally produces a new array; the Dutch Flag version would need an explicit copy first, adding O(n) space back.
//
//  --- Interview Discussion ---
//    Q: Why don't you advance `mid` after swapping with `high`?
//    A: The value swapped IN from the high side has never been examined --
//       it could be a 0, 1, or 2, so it must be re-classified on the very
//       next loop iteration. Advancing `mid` there would silently skip a
//       potentially misclassified element.
//    Q: Why is it safe to advance `mid` after swapping with `low`?
//    A: Because everything the `low` pointer holds is always either 0 or a
//       value we've already confirmed is safe to place there (loop
//       invariant), so the newly-swapped-in value at `mid` is guaranteed
//       to be a 1 (the only thing that could have been sitting between low
//       and mid) -- correctly classified, safe to move past.
//  --- Related Problems ---
//    Easy   : Sort Array By Parity (LC905) -- 2-way partition version.
//    Medium : Sort Colors II - K colors (premium) -- generalizes to K buckets.
//    Hard    : 3-way Quicksort partitioning for arrays with many duplicate keys.


// ================================================================================
//  PROBLEM 15 — LC 169 & LC 229: MAJORITY ELEMENT (BOYER-MOORE VOTING)     [BUG FIXED]
// ================================================================================
//
//  --- 🎯 Interview-Ready Add-Ons (constraints, timing, pitch) ---
//  Asked at      : Amazon, Google, Adobe -- LC169 is a common warm-up; LC229 (>n/3) is a frequent 'harder follow-up' asked immediately after.
//  Constraints   : 1 <= n <= 5*10^4 -> O(n) time, O(1) space Boyer-Moore expected; a HashMap count (O(n) space) is an acceptable 'Better' step but should be improved upon when asked for O(1) space.
//  Time-boxing   : Total ~10 min for LC169 (2 min restate, 3 min HashMap Better, 5 min voting + dry run) -- budget another 8-10 min if the >n/3 follow-up is asked immediately after.
//  60-Sec Pitch  : "I walk the array maintaining a candidate and a counter that increments on a match and decrements otherwise -- a true majority element (>n/2) mathematically can't be fully cancelled out by everything else combined, so it always survives as the final candidate; I still verify with a second pass whenever the problem doesn't GUARANTEE a majority exists."
//
//
//  *** BUG FOUND IN ORIGINAL FILE ***
//  `majorityElementV2()` used `$verify` inside the verification loop without
//  ever initializing it to 0 beforehand (only a stray, unused `$count = 0;`
//  existed nearby). PHP silently treats an undefined variable as null and
//  coerces null++ to 1 on first use, so the code "worked" in loose testing,
//  but it emits an "Undefined variable $verify" warning under PHP 8+ strict
//  reporting and is fragile if the function is ever refactored. Fixed below
//  by explicitly initializing `$verifyCount = 0;` and removing the dead
//  `$count = 0;` line.
//
//  --- How to Identify This Pattern ---
//  Keywords     : "majority element", "appears more than n/2 times",
//                 "appears more than n/3 times"
//  Signal       : "Majority" (a strict FRACTION-of-total threshold) is the
//                 unmistakable Boyer-Moore Voting signature. The exact
//                 fraction (n/2 vs n/3) tells you how many candidates to
//                 track simultaneously: n/2 -> 1 candidate, n/3 -> up to 2
//                 candidates (since at most 2 elements can each exceed 1/3
//                 of the array).
//  Common mistake: Skipping the VERIFICATION pass when the problem does NOT
//                 explicitly guarantee a majority element exists (true for
//                 LC229's >n/3 variant) -- the voting phase alone can
//                 produce a candidate that isn't actually a majority element.
//
//  --- Problem Understanding ---
//  What (LC169): find the element appearing more than n/2 times (guaranteed to exist).
//  What (LC229): find all elements appearing more than n/3 times (0, 1, or 2 such elements; NOT guaranteed).
//  Why it exists: Boyer-Moore Voting is a beautiful O(1)-space alternative
//    to a HashMap frequency count, built on the insight that a true
//    majority element can survive any number of "cancellations" against
//    non-majority elements.
//
//  --- Approach 1: HashMap Frequency Count (Better, not optimal on space) ---
//  Intuition : count every value; return the one exceeding the threshold.
//  TC: O(n)  |  SC: O(n)
//
function majorityElementHashMap(array $nums): int {
    $freq = [];
    $threshold = count($nums) / 2;
    foreach ($nums as $num) {
        $freq[$num] = ($freq[$num] ?? 0) + 1;
        if ($freq[$num] > $threshold) return $num;   // Early exit the moment threshold is crossed
    }
    return -1;
}

//  --- Approach 2a: Boyer-Moore Voting, single candidate (Optimal for LC169, >n/2) ---
//  Intuition : walk the array maintaining a candidate and a counter. A
//              matching element increments the counter; a non-matching
//              element decrements it. When the counter hits 0, a NEW
//              candidate is chosen. Because a true majority element (>n/2)
//              outnumbers everything else COMBINED, it is mathematically
//              guaranteed to survive as the final candidate.
//  TC: O(n)  |  SC: O(1)
//
function majorityElement(array $nums): int {
    $candidate = 0;
    $count = 0;

    // Phase 1: Voting -- find a candidate that MIGHT be the majority element
    foreach ($nums as $num) {
        if ($count === 0) {
            $candidate = $num;    // Counter exhausted -- gamble on a new candidate
        }
        $count += ($num === $candidate) ? 1 : -1;   // Vote for or against the current candidate
    }

    // Phase 2: Verification -- required whenever a majority isn't GUARANTEED to exist.
    // LC169 guarantees one exists, so this pass is technically optional there,
    // but keeping it makes the function safe to reuse for the non-guaranteed case too.
    $verifyCount = 0;
    foreach ($nums as $num) {
        if ($num === $candidate) $verifyCount++;
    }

    return $verifyCount > count($nums) / 2 ? $candidate : -1;
}

//  --- Approach 2b: Boyer-Moore Voting, two candidates (Optimal for LC229, >n/3) ---
//  Intuition : at most 2 elements can each appear more than n/3 times
//              (a 4th candidate would force total count > n, impossible).
//              Track two candidates and two counters simultaneously; the
//              cancellation rule now decrements BOTH counters when an
//              element matches neither candidate.
//  TC: O(n)  |  SC: O(1)
//
function majorityElementII(array $nums): array {
    $candidate1 = $candidate2 = null;
    $count1 = $count2 = 0;
    $n = count($nums);

    // Phase 1: Voting for up to two candidates
    foreach ($nums as $num) {
        if ($count1 === 0 && $num !== $candidate2) {
            $candidate1 = $num; $count1 = 1;
        } elseif ($count2 === 0 && $num !== $candidate1) {
            $candidate2 = $num; $count2 = 1;
        } elseif ($num === $candidate1) {
            $count1++;
        } elseif ($num === $candidate2) {
            $count2++;
        } else {
            $count1--; $count2--;    // Matches neither -- cancel one vote from BOTH
        }
    }

    // Phase 2: Verification -- REQUIRED here (no guarantee an answer exists)
    $verify1 = $verify2 = 0;
    foreach ($nums as $num) {
        if ($num === $candidate1) $verify1++;
        elseif ($num === $candidate2) $verify2++;
    }

    $result = [];
    if ($verify1 > intdiv($n, 3)) $result[] = $candidate1;
    if ($verify2 > intdiv($n, 3)) $result[] = $candidate2;
    return $result;
}

//  --- Dry Run (single candidate) ---  nums = [2,2,1,1,1,2,2]
//  ┌───┬─────┬───────────┬───────┐
//  │ i │ num │ candidate │ count │
//  ├───┼─────┼───────────┼───────┤
//  │ 0 │ 2   │ 2         │ 1     │
//  │ 1 │ 2   │ 2         │ 2     │
//  │ 2 │ 1   │ 2         │ 1     │
//  │ 3 │ 1   │ 2         │ 0     │
//  │ 4 │ 1   │ 1 (reset) │ 1     │
//  │ 5 │ 2   │ 1         │ 0     │
//  │ 6 │ 2   │ 2 (reset) │ 1     │
//  └───┴─────┴───────────┴───────┘
//  candidate=2, verify: count(2)=4 > 7/2=3.5 -> return 2

echo "Majority element (>n/2): " . majorityElement([2, 2, 1, 1, 1, 2, 2]) . "\n";  // 2
print_r(majorityElementII([1, 1, 1, 3, 3, 2, 2, 2]));  // [1, 2]

//  --- Patterns Used ---   Primary: Boyer-Moore Voting.  Secondary: Hashing (brute alternative).
//  --- Recognition Tips ---
//    Use when: "majority" / ">n/k times" threshold language appears.
//    Don't use when: you need ALL elements above a frequency threshold with
//              an arbitrary K (not 2 or 3) -- that needs a HashMap + a
//              min-heap or a straightforward frequency-count-and-filter.
//    Similar problems: Majority Element (LC169), Majority Element II (LC229),
//              Check If a Number Is Majority Element in a Sorted Array (LC1150).
//  --- Edge Cases ---
//    - No true majority exists (LC229 case) -> verification pass correctly
//      filters out false-positive candidates. NEVER skip this pass unless
//      the problem explicitly guarantees existence.
//    - All elements identical -> trivially the majority; both phases handle
//      this correctly.
//    - Exactly at the n/2 or n/3 boundary (not strictly greater) -> the
//      strict `>` comparison correctly excludes exact-boundary ties, since
//      the problems ask for STRICTLY more than the threshold.
//
//  --- 🧪 Additional Senior-Level Prep (tests, gotchas, follow-ups) ---
//  assert(majorityElement([2,2,1,1,1,2,2]) === 2);
//  assert(majorityElementII([1,1,1,3,3,2,2,2]) === [1,2]);
//  assert(majorityElementII([1,2,3]) === []);   // LC229: no element actually exceeds n/3 -- verification pass catches this
//  Mistake Recovery : Skipping the verification pass is the #1 way this problem goes wrong -- if you're solving LC229 (>n/3) and skip it to save time, say explicitly why you're keeping it anyway ("the problem doesn't guarantee an answer exists, so I need to confirm my candidates") rather than silently including it without justification.
//  Follow-Up / Scale-Up:
//    - Generalize to '> n/k times' for arbitrary k -> track up to (k-1) candidates simultaneously; verification becomes even more critical as k grows since false positives become more likely.
//    - What if the array is a live stream and you need the majority element AT ANY POINT so far? -> Boyer-Moore's single-candidate state (candidate + counter) is naturally streaming-friendly for the >n/2 case; just note that the ANSWER can change as more elements arrive, unlike a batch computation.
//
//  --- Interview Discussion ---
//    Q: Why does the "cancel one vote from BOTH counters" rule work in the
//       two-candidate version?
//    A: It preserves the invariant that any TRUE majority element (>n/3)
//       cannot be fully cancelled out, because there physically aren't
//       enough OTHER elements to cancel it against, even when spread across
//       two decrementing counters simultaneously.
//    Q: Why is verification mandatory for LC229 but "optional" (defensive
//       only) for LC169?
//    A: LC169 GUARANTEES a majority element exists in the input, so the
//       voting phase alone is provably correct. LC229 makes no such
//       guarantee -- the voting phase can produce candidates that turn out
//       to not actually satisfy the >n/3 threshold, so skipping
//       verification there would silently return wrong answers.
//  --- Related Problems ---
//    Easy   : Check If a Number Is Majority Element in a Sorted Array (LC1150).
//    Medium : Majority Element II (LC229, implemented above).
//    Hard    : Online Majority Element In Subarray (LC1157) -- needs random sampling + verification.


// ================================================================================
//  PROBLEM 16 — LC 53: MAXIMUM SUBARRAY (KADANE'S ALGORITHM)
// ================================================================================
//
//  --- 🎯 Interview-Ready Add-Ons (constraints, timing, pitch) ---
//  Asked at      : Asked at essentially every company -- Amazon, Google, Meta, Microsoft, Flipkart -- one of the highest-frequency medium problems across ALL of DSA interviewing.
//  Constraints   : 1 <= n <= 10^5, values can be negative -> O(n) Kadane's expected; O(n^2) brute force is an acceptable starting point ONLY if you immediately state you'll optimize it.
//  Time-boxing   : Total ~10 min: 2 min restate + the all-negative edge case explicitly, 3 min brute force + complexity, 5 min Kadane's + full dry run showing at least one reset.
//  60-Sec Pitch  : "At each index I ask 'does extending the running sum help, or should I start fresh here?' -- a running sum that's gone negative can only ever hurt any future subarray it's prepended to, so I discard it back to zero the instant it turns negative, tracking the global max throughout."
//
//
//  --- How to Identify This Pattern ---
//  Keywords     : "maximum subarray sum", "contiguous subarray", "maximum sum"
//  Signal       : "Contiguous subarray" + "maximize a running total" is the
//                 single strongest Kadane's Algorithm trigger in the entire
//                 topic -- it appears constantly in disguised forms
//                 (maximum product, circular array variants, etc.).
//  Common mistake: Initializing the running/max sum to 0 instead of
//                 PHP_INT_MIN (or the first element) -- this silently
//                 breaks on all-negative arrays, where the correct answer
//                 is the LEAST negative single element, not 0 (0 is not
//                 achievable if every subarray must be non-empty).
//
//  --- Problem Understanding ---
//  What: find the contiguous subarray with the largest sum; return the sum
//        (this enhanced version also returns the subarray's boundaries).
//  Why it exists: Kadane's is the prototypical "local decision, global
//    optimum" 1D dynamic-programming pattern -- the decision at each index
//    is simply "extend the previous subarray, or start fresh here?"
//
//  --- Approach 1: Brute Force -- all subarrays (for contrast) ---
//  Intuition : sum every possible (start, end) subarray, track the max.
//  TC: O(n^2)  |  SC: O(1)
//
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

//  --- Approach 2: Kadane's Algorithm (Optimal, with subarray boundaries) ---
//  Intuition : at each index, decide: does extending the PREVIOUS running
//              sum help, or is it better to START FRESH from here? A
//              running sum that has gone negative can only ever HURT a
//              future subarray's total, so discard it (reset to 0) the
//              moment it turns negative.
//  Algorithm : 1) runningSum = 0, maxSum = -infinity
//              2) for each i: if runningSum == 0, mark tentative start = i
//                 runningSum += nums[i]
//                 if runningSum > maxSum: update maxSum + record start/end
//                 if runningSum < 0: reset runningSum = 0 (discard, don't carry negative baggage forward)
//  TC: O(n)  |  SC: O(1)
//
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

//  --- Dry Run ---  nums = [-2,1,-3,4,-1,2,1,-5,4]
//  ┌───┬─────┬─────────────┬────────┬───────┬─────┐
//  │ i │ num │ runningSum  │ maxSum │ start │ end │
//  ├───┼─────┼─────────────┼────────┼───────┼─────┤
//  │ 0 │ -2  │ -2 -> reset 0│ -2     │ 0     │ 0   │
//  │ 1 │ 1   │ 1           │ 1      │ 1     │ 1   │
//  │ 2 │ -3  │ -2 -> reset 0│ 1      │ 1     │ 1   │
//  │ 3 │ 4   │ 4           │ 4      │ 3     │ 3   │
//  │ 4 │ -1  │ 3           │ 4      │ 3     │ 3   │
//  │ 5 │ 2   │ 5           │ 5      │ 3     │ 5   │
//  │ 6 │ 1   │ 6           │ 6      │ 3     │ 6   │
//  │ 7 │ -5  │ 1           │ 6      │ 3     │ 6   │
//  │ 8 │ 4   │ 5           │ 6      │ 3     │ 6   │
//  └───┴─────┴─────────────┴────────┴───────┴─────┘
//  Output: maxSum=6, subarray=[4,-1,2,1]

$result = maxSubArray([-2, 1, -3, 4, -1, 2, 1, -5, 4]);
echo "Max subarray sum: {$result['maxSum']}\n";           // 6
echo "Subarray: " . implode(", ", $result['subarray']) . "\n";  // 4,-1,2,1

//  --- Patterns Used ---   Primary: Kadane's Algorithm (1D DP).  Secondary: none.
//  --- Recognition Tips ---
//    Use when: "maximum/minimum contiguous subarray sum" is asked.
//    Don't use when: the subarray can be NON-contiguous (that's just
//              "sum of all positive numbers," a trivial filter) or when the
//              array is CIRCULAR (LC918 -- needs a twist: max(Kadane's
//              normal max, totalSum - Kadane's MINIMUM subarray sum)).
//    Similar problems: Maximum Product Subarray (LC152 -- track running MAX
//              AND MIN, since a negative * negative can flip a min into a
//              max), Maximum Sum Circular Subarray (LC918), Best Time to
//              Buy and Sell Stock (Problem 17 below -- a simplified,
//              one-transaction cousin of this same "running best" idea).
//  --- Edge Cases ---
//    - All negative numbers -> answer is the single LEAST negative element;
//      PHP_INT_MIN initialization (not 0) is what makes this correct.
//    - Single element -> loop runs once, trivially returns that element.
//    - All positive numbers -> the entire array is the answer; runningSum
//      never goes negative, never resets.
//    - Empty array -> undefined behavior for this problem (LC53 guarantees
//      at least 1 element); guard explicitly in production code.
//
//  --- 🧪 Additional Senior-Level Prep (tests, gotchas, follow-ups) ---
//  $r = maxSubArray([-2,1,-3,4,-1,2,1,-5,4]); assert($r['maxSum'] === 6 && $r['subarray'] === [4,-1,2,1]);
//  assert(maxSubArray([-5,-2,-8])['maxSum'] === -2);   // all-negative -- proves PHP_INT_MIN init matters, NOT 0
//  assert(maxSubArray([5])['maxSum'] === 5);            // single element
//  Mistake Recovery : If you initialize maxSum to 0 instead of PHP_INT_MIN and someone tests an all-negative array, you'll silently return 0 -- a sum that was never actually achievable. If this happens mid-interview, name the fix out loud ('this breaks when every element is negative, let me fix the initial value') rather than quietly patching it.
//  Follow-Up / Scale-Up:
//    - Maximum PRODUCT subarray instead of sum (LC152) -> track both a running max AND running min simultaneously, since multiplying by a negative can flip today's minimum into tomorrow's maximum.
//    - Array is CIRCULAR (LC918) -> answer is max(normal Kadane's max, totalSum - Kadane's MINIMUM subarray sum), with a special case for all-negative arrays where the wrap-around trick would incorrectly produce an empty subarray.
//
//  --- Interview Discussion ---
//    Q: Why is discarding a negative running sum always safe?
//    A: A negative prefix can only ever REDUCE any future sum it's
//       prepended to -- so a fresh start (sum=0) is provably at least as
//       good as carrying negative baggage forward, for any future extension.
//    Q: Follow-up -- Maximum Product Subarray?
//    A: Track BOTH a running max and running min product at each step,
//       because multiplying by a negative number can flip the sign --
//       today's minimum could become tomorrow's maximum.
//    Q: Follow-up -- what if the array were circular (can wrap around)?
//    A: The answer is either the normal Kadane's max, OR (total sum -
//       Kadane's MINIMUM subarray sum) if the optimal subarray wraps around
//       the array boundary -- take the max of both cases (with an edge
//       case for all-negative arrays, where the wrap-around trick would
//       incorrectly produce an empty subarray).
//  --- Related Problems ---
//    Medium : Maximum Product Subarray (LC152).
//    Medium : Best Time to Buy and Sell Stock (Problem 17 below).
//    Hard    : Maximum Sum Circular Subarray (LC918).


// ================================================================================
//  PROBLEM 17 — LC 121: BEST TIME TO BUY AND SELL STOCK
// ================================================================================
//
//  --- 🎯 Interview-Ready Add-Ons (constraints, timing, pitch) ---
//  Asked at      : Amazon, Meta, Bloomberg, Google -- the entry point to an entire FAMILY of 5-6 related DP problems (II through IV, with cooldown, with fees) that are all common Staff-level follow-ups.
//  Constraints   : 1 <= n <= 10^5, one buy + one sell only, sell strictly after buy -> O(n) single pass expected.
//  Time-boxing   : Total ~7 min: 1 min restate 'sell after buy' constraint, 2 min brute force O(n^2), 4 min single-pass + dry run + note the Kadane's connection.
//  60-Sec Pitch  : "I scan once tracking the lowest price seen so far as the best possible buy point, and at every day compute the profit if I sold TODAY against that running minimum, keeping the best profit seen across the whole scan."
//
//
//  --- How to Identify This Pattern ---
//  Keywords     : "buy on one day, sell on a LATER day", "maximize profit",
//                 "one transaction"
//  Signal       : "One buy, one sell, sell must be AFTER buy" is a
//                 single-pass "track the best-so-far" pattern -- a close
//                 cousin of Kadane's (in fact, Best-Time-to-Buy-and-Sell IS
//                 equivalent to running Kadane's on the array of
//                 day-to-day PRICE DIFFERENCES).
//  Common mistake: Using nested loops to check every (buy day, sell day)
//                 pair -- O(n^2) when a single pass achieves O(n) by
//                 tracking the minimum price seen SO FAR.
//
//  --- Problem Understanding ---
//  What: given daily stock prices, find the maximum profit from buying on
//        one day and selling on a LATER day (or 0 if no profit is possible).
//  Why it exists: teaches "track the best opportunity seen so far" as you
//    scan forward -- directly reusable for "best time to buy/sell" variants
//    (with cooldown, with fees, with K transactions -- all in the DP topic).
//
//  --- Approach 1: Brute Force -- all (buy, sell) pairs (for contrast) ---
//  Intuition : check every pair of days i < j, compute prices[j]-prices[i].
//  TC: O(n^2)  |  SC: O(1)
//
function maxProfitBrute(array $prices): int {
    $maxProfit = 0;
    for ($i = 0; $i < count($prices); $i++) {
        for ($j = $i + 1; $j < count($prices); $j++) {
            $maxProfit = max($maxProfit, $prices[$j] - $prices[$i]);
        }
    }
    return $maxProfit;
}

//  --- Approach 2: Single Pass, Track Minimum-So-Far (Optimal) ---
//  Intuition : as you scan left to right, keep the LOWEST price seen so
//              far (the best possible buy point up to now). At every day,
//              the best possible profit if selling TODAY is
//              price[today] - minPriceSoFar. Track the maximum of that
//              across all days.
//  TC: O(n)  |  SC: O(1)
//
function maxProfit(array $prices): int {
    $minPriceSoFar = PHP_INT_MAX;
    $maxProfit = 0;

    foreach ($prices as $price) {
        $minPriceSoFar = min($minPriceSoFar, $price);      // Best possible buy point up to today
        $maxProfit = max($maxProfit, $price - $minPriceSoFar);  // Best possible profit if selling today
    }

    return $maxProfit;
}

//  --- Dry Run ---  prices = [7,1,5,3,6,4]
//  ┌───┬───────┬───────────────┬───────────┐
//  │ i │ price │ minPriceSoFar │ maxProfit │
//  ├───┼───────┼───────────────┼───────────┤
//  │ 0 │ 7     │ 7             │ 0         │
//  │ 1 │ 1     │ 1             │ 0         │
//  │ 2 │ 5     │ 1             │ 4         │
//  │ 3 │ 3     │ 1             │ 4         │
//  │ 4 │ 6     │ 1             │ 5         │
//  │ 5 │ 4     │ 1             │ 5         │
//  └───┴───────┴───────────────┴───────────┘
//  Output: 5  (buy at 1 on day 2, sell at 6 on day 5)

echo "Max profit: " . maxProfit([7, 1, 5, 3, 6, 4]) . "\n";  // 5

//  --- Patterns Used ---   Primary: Single-Pass "Best So Far" tracking.  Secondary: Kadane's (equivalent formulation).
//  --- Recognition Tips ---
//    Use when: exactly ONE buy + ONE sell transaction, sell strictly after buy.
//    Don't use when: MULTIPLE transactions are allowed (LC122 -- greedily
//              sum every positive day-to-day difference instead), or a
//              transaction FEE / COOLDOWN is introduced (needs full DP --
//              see the DP topic file).
//    Similar problems: Best Time to Buy and Sell Stock II (LC122, multiple
//              transactions), III (LC123, at most 2 transactions), IV
//              (LC188, at most K transactions), with Cooldown (LC309), with
//              Transaction Fee (LC714).
//  --- Edge Cases ---
//    - Prices strictly decreasing every day -> maxProfit stays 0 (never
//      forced negative, since we initialize maxProfit=0 and profit is only
//      ever taken if it beats 0).
//    - Single price / empty array -> loop runs 0 or 1 times; no valid sell
//      day exists, correctly returns 0.
//    - All prices identical -> profit is always 0 at every step. Correct.
//
//  --- 🧪 Additional Senior-Level Prep (tests, gotchas, follow-ups) ---
//  assert(maxProfit([7,1,5,3,6,4]) === 5);
//  assert(maxProfit([7,6,4,3,1]) === 0);   // strictly decreasing -- never forced negative
//  assert(maxProfit([5]) === 0);            // single price, no valid sell day
//  Mistake Recovery : If asked the 'unlimited transactions' follow-up (LC122) and your first instinct is to reuse this exact single-pass-minimum logic, pause -- that's the wrong generalization. State explicitly that unlimited transactions is a GREEDY sum-of-positive-differences problem, structurally different from this one-transaction minimum-tracking problem.
//  Follow-Up / Scale-Up:
//    - Unlimited transactions allowed (LC122) -> greedily sum every positive day-to-day price increase.
//    - At most K transactions (LC188) or with a cooldown (LC309) or a transaction fee (LC714) -> all require full DP with explicit buy/sell/cooldown states, not a simple single-pass extension.
//
//  --- Interview Discussion ---
//    Q: How is this related to Kadane's Algorithm?
//    A: If you compute the array of day-to-day DIFFERENCES
//       (prices[i]-prices[i-1]), running Kadane's Maximum Subarray on that
//       difference array gives the exact same answer -- because a
//       contiguous run of differences IS the profit from buying at the
//       start of that run and selling at the end.
//    Q: Follow-up -- unlimited transactions allowed (LC122)?
//    A: Greedily sum every positive day-to-day price increase -- you can
//       "capture" every uphill segment independently since transactions
//       are unlimited and there's no cooldown/fee.
//  --- Related Problems ---
//    Medium : Best Time to Buy and Sell Stock II (LC122).
//    Medium : Best Time to Buy and Sell Stock with Cooldown (LC309).
//    Hard    : Best Time to Buy and Sell Stock IV (LC188, at most K transactions -- full DP).


// ================================================================================
//  PROBLEM 18 — LC 2149: REARRANGE ARRAY ELEMENTS BY SIGN
// ================================================================================
//
//  --- 🎯 Interview-Ready Add-Ons (constraints, timing, pitch) ---
//  Asked at      : Amazon, Google -- moderately common, mainly testing whether you correctly distinguish 'preserve relative order' problems from Sort-Colors-style in-place swap problems.
//  Constraints   : 2 <= n <= 2*10^5, n is even with EXACTLY n/2 positives and n/2 negatives (LC2149's specific guarantee) -> O(n) time, O(n) space expected for the direct-placement approach.
//  Time-boxing   : Total ~8 min: 1 min restate + confirm equal-count guarantee, 2 min bucket-and-interleave Brute Force, 5 min direct even/odd index placement Optimal + dry run.
//  60-Sec Pitch  : "Because there are guaranteed to be exactly n/2 of each sign, I place positives directly into even indices and negatives directly into odd indices using two independent running counters, which automatically preserves each sign group's original relative order."
//
//
//  --- How to Identify This Pattern ---
//  Keywords     : "rearrange so positive and negative alternate", "same
//                 number of positives and negatives" (or not)
//  Signal       : "Alternate by category while preserving RELATIVE ORDER
//                 within each category" is a Two-Pass Partition-and-Interleave
//                 signature -- separate into two buckets by category, then
//                 weave them back together.
//  Common mistake: Trying to solve this with a single in-place swap pass
//                 like Sort Colors -- that destroys the RELATIVE ORDER
//                 requirement (LC2149 explicitly requires each category's
//                 relative order to be preserved, unlike Sort Colors which
//                 has no such requirement).
//
//  --- Problem Understanding ---
//  What: rearrange the array so positive and negative numbers alternate,
//        starting with a positive number, while preserving each category's
//        original relative order. Two variants: equal counts (LC2149,
//        exactly n/2 each) and unequal counts (append the leftover tail).
//  Why it exists: teaches "bucket by category, then interleave" -- reusable
//    whenever an output must alternate between two preserved-order streams
//    (e.g., merge-interleaving two already-ordered lists).
//
//  --- Approach 1: Separate Arrays Then Rebuild Loop (Brute Force / Exploratory) ---
//  Intuition : split into $posArr and $negArr by sign, then interleave with
//              a single index loop assuming EQUAL counts.
//  TC: O(n)  |  SC: O(n)
//  Caveat: only correct when counts are exactly equal; shown for contrast
//          with the cleaner rearrangeEqual() below.
$demoNums = [1, 2, -4, -5];
$demoPos = $demoNeg = [];
foreach ($demoNums as $num) {
    if ($num > 0) $demoPos[] = $num; else $demoNeg[] = $num;
}
for ($i = 0; $i < count($demoNums) / 2; $i++) {
    $demoNums[2 * $i]     = $demoPos[$i];
    $demoNums[2 * $i + 1] = $demoNeg[$i];
}

//  --- Approach 2a: Equal Counts, Direct Index Placement (Optimal for the equal-count case) ---
//  Intuition : positives always land on EVEN indices (0,2,4...), negatives
//              always on ODD indices (1,3,5...) -- compute each element's
//              final position directly, no intermediate bucket needed
//              beyond tracking two independent running indices.
//  TC: O(n)  |  SC: O(n) for the output array
//
function rearrangeEqual(array $nums): array {
    $n = count($nums);
    $result = array_fill(0, $n, 0);
    $posIndex = 0;   // Next even slot for a positive number
    $negIndex = 1;   // Next odd slot for a negative number

    foreach ($nums as $num) {
        if ($num > 0) {
            $result[$posIndex] = $num;
            $posIndex += 2;
        } else {
            $result[$negIndex] = $num;
            $negIndex += 2;
        }
    }

    return $result;
}

//  --- Approach 2b: Unequal Counts (handles the general case) ---
//  Intuition : bucket into $posArr and $negArr (each preserves relative
//              order automatically via push). Interleave up to the shorter
//              bucket's length, then append whatever remains of the longer one.
//  TC: O(n)  |  SC: O(n)
//
function rearrangeUnequal(array $nums): array {
    $posArr = array_values(array_filter($nums, fn($x) => $x > 0));
    $negArr = array_values(array_filter($nums, fn($x) => $x < 0));
    $result = [];
    $minLen = min(count($posArr), count($negArr));

    for ($i = 0; $i < $minLen; $i++) {
        $result[] = $posArr[$i];
        $result[] = $negArr[$i];
    }
    for ($i = $minLen; $i < count($posArr); $i++) $result[] = $posArr[$i];   // Leftover positives, if any
    for ($i = $minLen; $i < count($negArr); $i++) $result[] = $negArr[$i];   // Leftover negatives, if any

    return $result;
}

//  --- Dry Run (equal counts) ---  nums = [3, 1, -2, -5, 2, -4]
//  posIndex starts 0, negIndex starts 1
//  3(+)->slot0, 1(+)->slot2, -2(-)->slot1, -5(-)->slot3, 2(+)->slot4, -4(-)->slot5
//  Output: [3,-2,1,-5,2,-4]

print_r(rearrangeEqual([3, 1, -2, -5, 2, -4]));    // [3,-2,1,-5,2,-4]
print_r(rearrangeUnequal([-1, 1, -2, -3, 2, 3]));  // interleaved + extra negatives appended

//  --- Patterns Used ---   Primary: Two-Bucket Partition + Interleave.  Secondary: Array Filtering.
//  --- Recognition Tips ---
//    Use when: alternate-by-category WHILE preserving each category's
//              relative order is required.
//    Don't use when: relative order does NOT need preserving -- then a
//              Sort-Colors-style single-pass in-place swap (O(1) space) is
//              strictly better.
//    Similar problems: Sort Array By Parity II (LC922, exact even/odd
//              index placement like Approach 2a), Merge Two Sorted Arrays
//              interleaving variants.
//  --- Edge Cases ---
//    - Equal positive/negative counts -> Approach 2a directly applicable.
//    - Unequal counts -> must use Approach 2b (Approach 2a would index out
//      of bounds or leave gaps).
//    - No negatives (or no positives) at all -> the "equal" approach isn't
//      applicable at all; Approach 2b naturally degenerates to "just append
//      everything from the one non-empty bucket."
//    - Zero in the array -> LC2149 guarantees no zeroes; if present, decide
//      explicitly whether 0 counts as positive or negative before coding.
//
//  --- 🧪 Additional Senior-Level Prep (tests, gotchas, follow-ups) ---
//  assert(rearrangeEqual([3,1,-2,-5,2,-4]) === [3,-2,1,-5,2,-4]);
//  print_r(rearrangeUnequal([-1,1,-2,-3,2,3]));   // unequal counts -- verify leftover negatives are appended in original relative order
//  PHP Gotcha       : This is one of the few problems in this file where a truly in-place O(1)-space solution is NOT straightforward -- say so explicitly if asked, rather than trying to force an in-place version under time pressure; O(n) extra space is the practical, expected answer here.
//  Mistake Recovery : If the interviewer removes the 'equal counts' guarantee mid-conversation, don't try to patch the even/odd-index version -- switch cleanly to the bucket-then-interleave-then-append-leftover approach and say why the first approach no longer applies.
//  Follow-Up / Scale-Up:
//    - Counts are NOT guaranteed equal -> must use the bucket-and-interleave-with-leftover-append approach (Approach 2b above), not direct index placement.
//    - What if you need this done truly in-place with O(1) extra space? -> acknowledge this is hard in the general case; a cycle-sort-style in-place rearrangement exists but is significantly more complex and rarely expected without an explicit prompt.
//
//  --- Interview Discussion ---
//    Q: Why can't Sort Colors' in-place swap technique be reused here?
//    A: Sort Colors has NO relative-order requirement within each color
//       group -- swapping is fine. This problem explicitly requires
//       preserving each sign-group's original relative order, which
//       in-place swapping would scramble.
//    Q: Could Approach 2a be done truly in-place (O(1) extra space)?
//    A: Not straightforwardly, because writing positives to even slots and
//       negatives to odd slots in-place would overwrite values before
//       they've been read, unless you carefully cycle-sort element by
//       element -- generally an interview-level answer is "no, O(n) extra
//       space is the practical/expected solution here."
//  --- Related Problems ---
//    Medium : Sort Array By Parity II (LC922).
//    Medium : Wiggle Sort (LC280).
//    Hard    : Wiggle Sort II (LC324).


// ================================================================================
//  PROBLEM 19 — LC 31: NEXT PERMUTATION                                    [BUG FIXED]
// ================================================================================
//
//  --- 🎯 Interview-Ready Add-Ons (constraints, timing, pitch) ---
//  Asked at      : Amazon, Google, Microsoft, Bloomberg -- a classic 'do you actually understand the algorithm or did you memorize it' filter question.
//  Constraints   : 1 <= n <= 100, in-place, O(1) extra space required -> O(n) time (each of the three steps is a single linear scan or reversal).
//  Time-boxing   : Total ~12 min: 2 min restate + the 'wraps to first permutation' edge case, 8 min derive + code the 3-step algorithm (this is where most time goes -- don't rush the dip-finding intuition), 2 min dry run.
//  60-Sec Pitch  : "I find the rightmost 'dip' where the sequence stops descending, swap it with the smallest value to its right that's still bigger than it, then reverse everything after the dip -- because that suffix is still descending after the swap, reversing it gives the smallest possible arrangement, which guarantees this is the immediate NEXT permutation, not some later one."
//
//
//  *** BUG FOUND IN ORIGINAL FILE (CRITICAL) ***
//  The original file contained a duplicate, orphaned, TOP-LEVEL copy of this
//  algorithm's logic (operating directly on a global $nums, outside any
//  function) that ended with an unconditional `return;` statement. In PHP,
//  a `return` at the top level of a script file HALTS THE ENTIRE SCRIPT --
//  meaning if Array.php were ever executed sequentially top-to-bottom via
//  `php Array.php`, every problem AFTER this point in the file (problems
//  20 through 27 -- roughly 40% of the file) would NEVER RUN. This is fixed
//  here by removing the orphaned top-level script block entirely and
//  keeping only the clean, self-contained function below (its exploratory
//  walkthrough commentary has been preserved and folded into the
//  explanation prose so no learning content is lost).
//
//  --- How to Identify This Pattern ---
//  Keywords     : "next permutation", "next lexicographically greater arrangement"
//  Signal       : "Next permutation" is ALWAYS a 3-step Reversal Trick
//                 problem -- there is no meaningful brute force worth
//                 writing beyond "generate all permutations and find the
//                 next one" (which is factorially expensive and never the
//                 expected answer).
//  Common mistake: Forgetting that if NO dip point exists (array is fully
//                 descending), the array IS the last permutation, and the
//                 answer wraps around to the FIRST permutation (i.e., fully
//                 ascending) -- achieved simply by reversing the whole array.
//
//  --- Problem Understanding ---
//  What: rearrange the array in-place into the lexicographically NEXT
//        greater permutation. If no such permutation exists (array is the
//        highest possible permutation, i.e., fully descending), rearrange
//        to the lowest possible order (fully ascending) instead.
//  Why it exists: teaches a clean, provable 3-step in-place algorithm for a
//    problem that looks like it needs combinatorial generation but doesn't.
//  Real-world analogy: odometer-style "next combination" logic -- find the
//    rightmost place that can be incremented, increment it minimally, then
//    reset everything to its right to the smallest possible arrangement.
//
//  --- Approach: 3-Step Reversal Trick (Optimal, the only sane approach) ---
//  Intuition :
//    Step 1 (find the "dip"): scan from the right, find the rightmost index
//      `dip` where nums[dip] < nums[dip+1]. Everything to the right of
//      `dip` is currently in DESCENDING order (a local maximum-arrangement
//      suffix) -- that's precisely why we're scanning from the right.
//    Step 2 (find the swap partner): scan from the right again, find the
//      rightmost index `j > dip` where nums[j] > nums[dip] (guaranteed to
//      exist since nums[dip+1] alone already satisfies this). Swap
//      nums[dip] and nums[j] -- this gives the smallest possible increase
//      at position `dip`.
//    Step 3 (reverse the suffix): the suffix after `dip` is STILL
//      descending after the swap (swapping doesn't change that a
//      descending run stays "almost" descending) -- reverse it to make it
//      ascending, which yields the SMALLEST possible arrangement for that
//      suffix, guaranteeing this is the immediate NEXT permutation, not
//      some later one.
//  TC: O(n)  |  SC: O(1)
//
function nextPermutation(array &$nums): void {
    $n = count($nums);
    $dip = -1;

    // Step 1: find rightmost index where nums[i] < nums[i+1]
    for ($i = $n - 2; $i >= 0; $i--) {
        if ($nums[$i] < $nums[$i + 1]) {
            $dip = $i;
            break;
        }
    }

    // No dip found -> array is fully descending -> it's the LAST permutation.
    // Wrap around to the FIRST permutation by reversing the whole array.
    if ($dip === -1) {
        reverseSegment($nums, 0, $n - 1);
        return;
    }

    // Step 2: find rightmost index j > dip where nums[j] > nums[dip], then swap
    for ($j = $n - 1; $j > $dip; $j--) {
        if ($nums[$j] > $nums[$dip]) {
            [$nums[$dip], $nums[$j]] = [$nums[$j], $nums[$dip]];
            break;
        }
    }

    // Step 3: reverse the suffix after dip -- turns the still-descending
    // tail into the smallest possible ascending arrangement.
    reverseSegment($nums, $dip + 1, $n - 1);
}

//  --- Dry Run ---  nums = [2,1,5,4,3,0,0]
//  ┌──────┬──────────────────────────────┬────────────────────────┐
//  │ Step │ Action                       │ Array State             │
//  ├──────┼──────────────────────────────┼────────────────────────┤
//  │ 1    │ scan right->left: 3>0 skip,  │ dip=1 (nums[1]=1<5)     │
//  │      │ 4>3 skip, 5>4 skip, 1<5 stop │                          │
//  │ 2    │ scan right for >nums[1]=1:   │ found 3 at idx4 -> swap │
//  │      │ swap(1,4)                    │ [2,3,5,4,1,0,0]         │
//  │ 3    │ reverse suffix after idx 1   │ [2,3,0,0,1,4,5]         │
//  └──────┴──────────────────────────────┴────────────────────────┘
//  Output: [2,3,0,0,1,4,5]

$nums = [2, 1, 5, 4, 3, 0, 0];
nextPermutation($nums);
echo "Next permutation: " . implode(", ", $nums) . "\n";  // 2,3,0,0,1,4,5

//  --- Bonus: Leaders in an Array (same "scan from the right, track a running best" family) ---
//  A "leader" is an element strictly greater than every element to its
//  right. Scanning right-to-left and tracking a running max lets you
//  identify all leaders in one pass -- reusing the exact same directional-
//  scan instinct as Step 1 of Next Permutation above.
function leadersInArray(array $nums): array {
    $n = count($nums);
    $leaders = [$nums[$n - 1]];   // The last element is always a leader (nothing to its right)
    $maxSoFar = $nums[$n - 1];

    for ($i = $n - 2; $i >= 0; $i--) {
        if ($nums[$i] > $maxSoFar) {
            $leaders[] = $nums[$i];
            $maxSoFar = $nums[$i];
        }
    }

    return array_reverse($leaders);   // Collected right-to-left; reverse for left-to-right order
}
print_r(leadersInArray([10, 22, 12, 3, 0, 6]));  // [22, 12, 6]

//  --- Patterns Used ---   Primary: Reversal Trick.  Secondary: Right-to-left directional scan.
//  --- Recognition Tips ---
//    Use when: "next/previous permutation" or "next lexicographic
//              arrangement" is explicitly asked.
//    Don't use when: you need ALL permutations (that's Backtracking, LC46)
//              or the Kth permutation directly (that's a different
//              factorial-number-system technique, LC60).
//    Similar problems: Permutations (LC46, Backtracking), Permutation
//              Sequence (LC60, factorial number system), Previous
//              Permutation With One Swap (LC1053).
//  --- Edge Cases ---
//    - Fully descending array -> no dip found -> wraps to fully ascending
//      (the "first" permutation). Explicitly handled.
//    - Fully ascending array -> dip is found at index 0 (or wherever the
//      last ascending pair is) -- correctly produces the very next
//      lexicographic step, NOT a full reset.
//    - Array with duplicate values -> Step 2's scan for "nums[j] > nums[dip]"
//      (strict) correctly skips over duplicates equal to nums[dip], which
//      matters for correctness with repeated values.
//    - Single element or empty array -> loop bounds guard naturally; no
//      permutation change is possible, function is a safe no-op.
//
//  --- 🧪 Additional Senior-Level Prep (tests, gotchas, follow-ups) ---
//  $demo = [2,1,5,4,3,0,0]; nextPermutation($demo); assert($demo === [2,3,0,0,1,4,5]);
//  $demo2 = [3,2,1]; nextPermutation($demo2); assert($demo2 === [1,2,3]);   // fully descending -- wraps to first permutation
//  $demo3 = [1]; nextPermutation($demo3); assert($demo3 === [1]);           // single element -- no-op
//  Mistake Recovery : This file's own original bug lived exactly here (see Bug Log, Bug 2) -- if you're debugging this live and your dry run seems to go into an infinite state or produces garbage, check FIRST whether you have any stray top-level `return` outside a function (a PHP-specific footgun), before assuming the algorithm itself is wrong.
//  Follow-Up / Scale-Up:
//    - Find the PREVIOUS permutation instead -> mirror every comparison direction (find a 'rise' instead of a 'dip', find the largest smaller-than value to swap with, reverse to make the suffix descending instead of ascending).
//    - Find the Kth permutation directly, without simulating K steps (LC60) -> factorial number system, a completely different (faster) technique -- name this distinction explicitly if asked to 'do this K times.'
//
//  --- Interview Discussion ---
//    Q: Why must the suffix be REVERSED (not re-sorted) in Step 3?
//    A: The suffix is ALREADY guaranteed to be in descending order before
//       the swap (that's exactly why `dip` was chosen as the rightmost
//       "break" point) -- reversing a descending sequence produces an
//       ascending one in O(n), which is both correct and faster than a
//       general O(n log n) sort.
//    Q: How would you find the PREVIOUS permutation instead?
//    A: Mirror the algorithm: find the rightmost index where nums[i] >
//       nums[i+1] (a "rise" instead of a "dip"), find the rightmost j with
//       nums[j] < nums[dip], swap, then reverse the suffix (which will now
//       be ascending, and needs to become descending -- the mirror image
//       of every comparison direction above).
//  --- Related Problems ---
//    Medium : Permutations (LC46) -- Backtracking, generates ALL permutations.
//    Medium : Permutation Sequence (LC60) -- factorial number system, no simulation needed.
//    Hard    : Previous Permutation With One Swap (LC1053).


// ================================================================================
//  PROBLEM 20 — LC 128: LONGEST CONSECUTIVE SEQUENCE                       [BUG FIXED]
// ================================================================================
//
//  --- 🎯 Interview-Ready Add-Ons (constraints, timing, pitch) ---
//  Asked at      : Amazon, Google, Microsoft -- known for the explicit 'must be O(n)' constraint that rules out the tempting sort-first instinct.
//  Constraints   : 0 <= n <= 10^5 -> the problem EXPLICITLY demands O(n), so sorting (O(n log n)) should be named as a valid-but-suboptimal 'Better' step, not the final answer.
//  Time-boxing   : Total ~10 min: 2 min restate + note the O(n) requirement out loud, 3 min sort-based Better approach, 5 min HashSet-with-sequence-heads Optimal + prove why it's amortized O(n) despite the nested loop shape.
//  60-Sec Pitch  : "I put every value into a HashSet, then for each value I only start counting a run if that value is the TRUE START of a sequence (its predecessor isn't in the set) -- this guarantee is what keeps the total work linear, since every element only ever gets walked once across the whole algorithm's lifetime."
//
//
//  *** BUG FOUND IN ORIGINAL FILE (CRITICAL) ***
//  `longestConsecutive(array $nums)` immediately discarded its own `$nums`
//  parameter with the line `$nums = [0,3,7,2,8,4,6,0,1];` at the very top
//  of the function body -- meaning the function COMPLETELY IGNORED whatever
//  array was passed in and always operated on a hardcoded test array
//  instead. This is a genuine, high-severity bug (a function that silently
//  ignores its input is worse than one that crashes, since it fails
//  silently with a plausible-looking wrong answer). Fixed below by deleting
//  that line entirely.
//
//  --- How to Identify This Pattern ---
//  Keywords     : "longest consecutive elements sequence", "O(n) time"
//                 explicitly required, unsorted array
//  Signal       : "Consecutive" (meaning consecutive INTEGERS like 3,4,5 --
//                 NOT consecutive array positions) + an O(n) requirement on
//                 an UNSORTED array is the classic "HashSet, only start
//                 counting from a sequence's true beginning" signature.
//  Hidden hint  : The O(n) requirement is a strong signal AGAINST sorting
//                 first (sorting alone costs O(n log n)) -- you're expected
//                 to find an O(n) hashing trick instead.
//  Common mistake: Starting a count from EVERY element instead of only from
//                 elements that are the BEGINNING of a sequence (i.e.,
//                 num-1 does NOT exist in the set) -- without that guard,
//                 the algorithm silently degrades to O(n^2) in the worst
//                 case (e.g., a fully consecutive array like [1..n] would
//                 redundantly re-count the same shrinking sequence from
//                 every single starting point).
//
//  --- Problem Understanding ---
//  What: given an unsorted array of integers, find the length of the
//        longest run of CONSECUTIVE integers (values, not positions) —
//        e.g., [100,4,200,1,3,2] contains the sequence 1,2,3,4 -> answer 4.
//  Why it exists: forces you to separate "sorted order" from "consecutive
//    VALUES" -- teaches the "only start counting from a true sequence head"
//    optimization that turns an apparent O(n^2) idea into genuine O(n).
//
//  --- Approach 1: Sort First (Better, not optimal on time) ---
//  Intuition : sort the array; walk through counting consecutive runs,
//              skipping duplicate adjacent values.
//  TC: O(n log n)  |  SC: O(1) extra (O(n) if the sort must not mutate input)
//
function longestConsecutiveSort(array $nums): int {
    if (empty($nums)) return 0;
    sort($nums);

    $maxRun = 1;
    $currentRun = 1;

    for ($i = 1; $i < count($nums); $i++) {
        if ($nums[$i] === $nums[$i - 1]) {
            continue;                          // Duplicate value -- doesn't break OR extend the run
        } elseif ($nums[$i] === $nums[$i - 1] + 1) {
            $currentRun++;                      // Truly consecutive -- extend the run
        } else {
            $currentRun = 1;                    // Gap found -- restart the run at length 1
        }
        $maxRun = max($maxRun, $currentRun);
    }

    return $maxRun;
}

//  --- Approach 2: HashSet, Count Only From Sequence Heads (Optimal) ---
//  Intuition : put every value into a HashSet for O(1) membership checks.
//              For each value, only bother counting a run STARTING from it
//              if (value - 1) is NOT in the set -- that guarantees `value`
//              is the true beginning of its sequence, so every sequence
//              gets counted exactly ONCE across the whole algorithm
//              (this is what makes the total work O(n), not O(n^2)).
//  TC: O(n) amortized (every element is visited by the inner while-loop
//      at most once across the ENTIRE algorithm's lifetime, not per outer
//      iteration)  |  SC: O(n) for the set
//
function longestConsecutive(array $nums): int {
    if (empty($nums)) return 0;

    $numSet = array_flip($nums);   // O(1) membership lookups via isset()
    $maxRun = 0;

    foreach ($numSet as $num => $_) {
        // Only start counting if `num` is the BEGINNING of a sequence --
        // i.e., no element one smaller than it exists. This single guard
        // is what keeps total work linear: every value is only ever
        // "walked" by the inner while-loop from its sequence's true head.
        if (!isset($numSet[$num - 1])) {
            $currentNum = $num;
            $currentRun = 1;

            while (isset($numSet[$currentNum + 1])) {   // Walk forward while the next consecutive value exists
                $currentNum++;
                $currentRun++;
            }

            $maxRun = max($maxRun, $currentRun);
        }
    }

    return $maxRun;
}

//  --- Dry Run ---  nums = [100,4,200,1,3,2]
//  Set = {100,4,200,1,3,2}
//  ┌─────┬───────────────────┬─────────────────────────────┐
//  │ num │ num-1 in set?      │ action                      │
//  ├─────┼───────────────────┼─────────────────────────────┤
//  │ 100 │ no (99 absent)     │ start run: 100 alone -> len=1│
//  │ 4   │ YES (3 present)    │ skip -- not a sequence head │
//  │ 200 │ no (199 absent)    │ start run: 200 alone -> len=1│
//  │ 1   │ no (0 absent)      │ start run: 1,2,3,4 -> len=4 │
//  │ 3   │ YES (2 present)    │ skip -- not a sequence head │
//  │ 2   │ YES (1 present)    │ skip -- not a sequence head │
//  └─────┴───────────────────┴─────────────────────────────┘
//  Output: 4  (the sequence 1,2,3,4)

echo "Longest consecutive: " . longestConsecutive([100, 4, 200, 1, 3, 2]) . "\n";  // 4

//  --- Patterns Used ---   Primary: Hashing (HashSet).  Secondary: Sorting (brute alternative).
//  --- Recognition Tips ---
//    Use when: "consecutive VALUES" (not positions) + O(n) time is required
//              on an unsorted array.
//    Don't use when: you only need to check IF a specific range is fully
//              present (simpler membership checks suffice) or when the
//              array is ALREADY sorted (then a plain linear scan without
//              any HashSet is sufficient and uses less memory).
//    Similar problems: Longest Consecutive Sequence II (premium, on a
//              2D grid), Binary Tree Longest Consecutive Sequence (LC298).
//  --- Edge Cases ---
//    - Empty array -> return 0 immediately (guarded explicitly above).
//    - Duplicate values -> array_flip naturally dedups (later duplicates
//      overwrite the same key), so the HashSet approach is unaffected;
//      the sort-based approach must explicitly `continue` on duplicates
//      (implemented above) to avoid incorrectly extending the run.
//    - All elements identical -> every value's predecessor IS present
//      (itself, after dedup, doesn't count as num-1) -- actually here,
//      only ONE distinct value exists, so it correctly becomes its own
//      sequence head with run length 1.
//    - Single element -> trivially a sequence of length 1.
//    - Negative numbers -> array_flip and isset() work identically
//      regardless of sign; no special handling needed.
//
//  --- 🧪 Additional Senior-Level Prep (tests, gotchas, follow-ups) ---
//  assert(longestConsecutive([100,4,200,1,3,2]) === 4);
//  assert(longestConsecutive([]) === 0);            // empty input
//  assert(longestConsecutive([1,2,0,1]) === 3);       // duplicate value (1 appears twice) -- array_flip naturally dedups
//  PHP Gotcha       : array_flip() silently drops duplicate VALUES down to a single key (last-write-wins on the flipped key) -- this is actually exactly the dedup behavior wanted here, but say so explicitly rather than letting it look like an accident.
//  Mistake Recovery : This is the exact function that had the parameter-ignoring bug in the original file (see Bug Log, Bug 3) -- if a function you're testing gives the SAME output regardless of what you pass in, that's an immediate signal to check whether the input parameter is being used at all, not just whether the algorithm inside is correct.
//  Follow-Up / Scale-Up:
//    - Prove out loud why this is O(n) despite the nested loop -> the inner while only ever runs for values proven to be sequence HEADS, so total inner-loop work across the ENTIRE run is bounded by n, not n per outer iteration.
//    - What if this needs to run on a 2D grid instead of a flat array (premium variant)? -> same HashSet-of-sequence-heads idea, but the 'predecessor' check must consider grid-adjacency, not just value-minus-one.
//
//  --- Interview Discussion ---
//    Q: Why is this actually O(n) and not O(n^2), given the nested loop
//       structure (outer foreach + inner while)?
//    A: The inner while-loop only ever executes for values that are proven
//       sequence HEADS (guarded by the `!isset($numSet[$num-1])` check).
//       Across the entire algorithm's execution, every element can be
//       "walked over" by the inner loop AT MOST ONCE (as part of exactly
//       one sequence), so the TOTAL work across all outer iterations
//       combined is bounded by n, not n per outer iteration.
//    Q: Why not just sort the array?
//    A: Sorting costs O(n log n), which is asymptotically worse than the
//       O(n) HashSet approach -- if the problem explicitly asks for O(n),
//       sorting fails that requirement even though it's simpler to write.
//  --- Related Problems ---
//    Medium : Binary Tree Longest Consecutive Sequence (LC298).
//    Medium : Longest Consecutive Sequence II - grid version (premium).
//    Hard    : Longest Increasing Subsequence (LC300) -- NOT the same
//              problem (LIS allows gaps/non-contiguous values; this
//              problem requires TRULY consecutive integer values).


// ================================================================================
//  PROBLEM 21 — LC 48: ROTATE IMAGE (90° CLOCKWISE, IN-PLACE)
// ================================================================================
//
//  --- 🎯 Interview-Ready Add-Ons (constraints, timing, pitch) ---
//  Asked at      : Amazon, Microsoft, Google -- a favorite because it's easy to get the CORRECT final answer with the WRONG rotation direction if you mix up transpose-then-reverse-rows vs. reverse-rows-then-transpose.
//  Constraints   : n == matrix.length == matrix[i].length (always SQUARE), 1 <= n <= 20, in-place required -> O(n^2) time, O(1) space expected; allocating a new matrix fails the explicit in-place constraint.
//  Time-boxing   : Total ~10 min: 2 min restate 'in-place' + confirm square matrix, 3 min extra-matrix Better approach, 5 min transpose+reverse Optimal + full dry run on a 3x3.
//  60-Sec Pitch  : "I transpose the matrix in place (mirror across the main diagonal, only touching the lower triangle to avoid double-swapping), then reverse each row -- composing those two reflections geometrically produces exactly a 90-degree clockwise rotation."
//
//
//  --- How to Identify This Pattern ---
//  Keywords     : "rotate the matrix/image", "in-place", "90 degrees"
//  Signal       : Any 2D matrix rotation + "in-place / O(1) extra space"
//                 constraint is the Transpose-Then-Reverse signature.
//  Hidden hint  : Clockwise = transpose + reverse EACH ROW. Counter-clockwise
//                 = transpose + reverse EACH COLUMN (or equivalently,
//                 reverse rows first then transpose) -- mixing these up is
//                 the single most common mistake on this problem.
//  Common mistake: Allocating a brand-new matrix for the rotated result --
//                 works, but violates the "in-place" constraint explicitly
//                 stated in the problem, which exists specifically to force
//                 the transpose+reverse insight.
//
//  --- Problem Understanding ---
//  What: rotate an n x n matrix 90 degrees clockwise, in-place.
//  Why it exists: teaches that a seemingly complex 2D geometric
//    transformation can be decomposed into two simple, well-understood 1D
//    operations (transpose = swap across the diagonal, then reverse each row).
//
//  --- Approach 1: Extra Matrix (Better, not optimal on space) ---
//  Intuition : place matrix[i][j] directly into its rotated destination
//              newMatrix[j][n-1-i], then copy back.
//  TC: O(n^2)  |  SC: O(n^2)
//
function rotateMatrixExtraSpace(array $matrix): array {
    $n = count($matrix);
    $result = array_fill(0, $n, array_fill(0, $n, 0));

    for ($i = 0; $i < $n; $i++) {
        for ($j = 0; $j < $n; $j++) {
            $result[$j][$n - 1 - $i] = $matrix[$i][$j];   // Direct destination formula for a 90-degree clockwise rotation
        }
    }

    return $result;
}

//  --- Approach 2: Transpose + Reverse Rows (Optimal, in-place) ---
//  Intuition : Step 1 (Transpose) mirrors the matrix along its main
//              diagonal: matrix[i][j] <-> matrix[j][i]. This alone produces
//              a COUNTER-clockwise-rotated-then-flipped result; Step 2
//              (reverse each row) completes the transformation into a true
//              90-degree CLOCKWISE rotation.
//  TC: O(n^2)  |  SC: O(1)
//
function rotateMatrix(array &$matrix): void {
    $n = count($matrix);

    // Step 1: Transpose (mirror along the main diagonal)
    for ($i = 0; $i < $n; $i++) {
        for ($j = 0; $j < $i; $j++) {   // Only j < i -- visiting the full square would swap every pair back to itself
            [$matrix[$i][$j], $matrix[$j][$i]] = [$matrix[$j][$i], $matrix[$i][$j]];
        }
    }

    // Step 2: Reverse each row -- completes the clockwise rotation
    foreach ($matrix as &$row) {
        $row = array_reverse($row);
    }
    unset($row);   // Break the reference from the foreach to avoid subtle bugs on later reuse of $row
}

//  --- Dry Run ---  [[1,2,3],[4,5,6],[7,8,9]]
//  After transpose: [[1,4,7],[2,5,8],[3,6,9]]
//  After row reverse: [[7,4,1],[8,5,2],[9,6,3]]

$matrix = [[1, 2, 3], [4, 5, 6], [7, 8, 9]];
rotateMatrix($matrix);
echo "Rotated matrix (90 CW):\n";
foreach ($matrix as $row) echo implode(" ", $row) . "\n";
// 7 4 1
// 8 5 2
// 9 6 3

//  --- Patterns Used ---   Primary: Matrix Boundary/Transform Trick.  Secondary: Two Pointers (inside row reverse).
//  --- Recognition Tips ---
//    Use when: in-place 2D rotation by a multiple of 90 degrees is asked.
//    Don't use when: rotation is by an ARBITRARY angle (needs actual
//              geometric transformation matrices, a different domain
//              entirely -- not a typical DSA interview question) or when
//              the matrix is NOT square (a non-square rotation must
//              produce a differently-shaped output, so true in-place
//              rotation isn't possible -- you'd need an extra matrix).
//    Similar problems: Rotate Image counter-clockwise (mirror the steps:
//              reverse rows first, then transpose), Transpose Matrix (LC867,
//              just Step 1 alone), Spiral Matrix (Problem 23 below -- a
//              different matrix-boundary technique).
//  --- Edge Cases ---
//    - 1x1 matrix -> transpose and reverse are both no-ops. Correct.
//    - Even n vs odd n -> both handled identically; no special-casing
//      needed since the transpose loop bound (j < i) and array_reverse()
//      both work uniformly regardless of parity.
//    - Non-square matrix -> this exact in-place technique does NOT apply;
//      flag this explicitly if asked, since true in-place rotation is
//      impossible when dimensions must swap (m x n -> n x m).
//
//  --- 🧪 Additional Senior-Level Prep (tests, gotchas, follow-ups) ---
//  $m = [[1,2,3],[4,5,6],[7,8,9]]; rotateMatrix($m); assert($m === [[7,4,1],[8,5,2],[9,6,3]]);
//  $m2 = [[1]]; rotateMatrix($m2); assert($m2 === [[1]]);   // 1x1 matrix -- both steps are no-ops
//  PHP Gotcha       : The `foreach ($matrix as &$row)` reference loop MUST be followed by `unset($row)` -- leaving a dangling reference from a by-reference foreach is a classic PHP footgun that can silently corrupt the LAST row if that reference variable name is reused later in the same scope.
//  Mistake Recovery : If your rotation comes out mirrored/flipped instead of rotated (a common symptom of transposing then NOT reversing, or reversing columns instead of rows), don't guess-and-check -- trace a single corner element (e.g., matrix[0][0]) through each step by hand and verify it lands where a true 90-degree clockwise rotation would put it.
//  Follow-Up / Scale-Up:
//    - Rotate counter-clockwise instead -> either reverse rows FIRST then transpose, or transpose then reverse COLUMNS instead of rows.
//    - Matrix is NOT square (rectangular) -> true in-place rotation is impossible (dimensions must swap m x n -> n x m); explicitly say you'd need a new matrix here rather than trying to force the square-matrix technique.
//
//  --- Interview Discussion ---
//    Q: Why does transpose-then-reverse-rows equal a clockwise rotation?
//    A: Transposing reflects the matrix across its main diagonal (top-left
//       to bottom-right). Reversing each row then reflects it horizontally.
//       Composing a diagonal reflection with a horizontal reflection
//       geometrically equals a 90-degree clockwise rotation -- this can be
//       verified directly by tracking where a single corner element ends up.
//    Q: How would you rotate 90 degrees COUNTER-clockwise instead?
//    A: Either (a) reverse each row FIRST, then transpose, or (b) transpose
//       then reverse each COLUMN instead of each row -- both are valid
//       equivalent formulations.
//  --- Related Problems ---
//    Easy   : Transpose Matrix (LC867).
//    Medium : Rotate Image counter-clockwise (variant, same technique).
//    Hard    : Rotate a non-square matrix (requires an extra matrix, no true in-place solution).


// ================================================================================
//  PROBLEM 22 — LC 73: SET MATRIX ZEROES (IN-PLACE, O(1) SPACE)
// ================================================================================
//
//  --- 🎯 Interview-Ready Add-Ons (constraints, timing, pitch) ---
//  Asked at      : Amazon, Microsoft, Google -- the canonical 'use the input itself as extra storage' problem; the O(1)-space follow-up is asked almost every time.
//  Constraints   : 1 <= rows,cols <= 200, follow-up EXPLICITLY asks for O(1) extra space -> O(m*n) time is fine at any tier; the SPACE tier (O(mn) -> O(m+n) -> O(1)) is what's actually being graded here.
//  Time-boxing   : Total ~15 min: 2 min restate, 3 min O(mn)-space Brute Force, 3 min O(m+n)-space Better, 7 min O(1)-space Optimal + dry run (budget extra time here -- the row0/col0-as-markers trick needs a careful walkthrough).
//  60-Sec Pitch  : "Instead of allocating separate marker arrays, I reuse the matrix's own first row and first column AS the marker storage -- with two separate boolean flags capturing whether row 0 or column 0 themselves originally had a zero, since they play double duty as both data and markers."
//
//
//  --- How to Identify This Pattern ---
//  Keywords     : "set entire row and column to 0", "in-place", "O(1) extra
//                 space" (as a follow-up constraint)
//  Signal       : "If a cell is 0, zero out its whole row AND column" +
//                 explicit O(1)-space follow-up is the classic "use the
//                 matrix's own first row/column as your marker storage"
//                 signature.
//  Hidden hint  : The very first row and first column are special because
//                 they're both a DATA region AND (in the optimal solution)
//                 the MARKER region -- this dual role is exactly why a
//                 separate `firstColZero` flag is needed (to avoid the
//                 first column's marker overwriting itself ambiguously).
//  Common mistake: Naively zeroing cells AS SOON AS a zero is found during
//                 the very first scan -- this cascades incorrectly (a
//                 newly-zeroed cell would then be mistaken for an
//                 ORIGINAL zero on a later iteration, incorrectly zeroing
//                 even MORE rows/columns than intended). You must fully
//                 IDENTIFY all zero locations before applying any changes.
//
//  --- Problem Understanding ---
//  What: given an m x n matrix, if an element is 0, set its entire row and
//        column to 0, in-place.
//  Why it exists: teaches the "use existing storage as your own marker
//    array" trick for hitting an O(1)-space bound that would otherwise
//    seem to require O(m+n) or O(m*n) auxiliary storage.
//
//  --- Approach 1: Brute Force with Hash Set of Coordinates (for contrast) ---
//  Intuition : first pass records every (row, col) that contains an
//              ORIGINAL zero. Second pass zeroes the full row+column for
//              each recorded coordinate.
//  TC: O(m*n)  |  SC: O(m*n) worst case (if most cells are zero)
//
function setZeroesBrute(array &$matrix): void {
    $rows = count($matrix);
    $cols = count($matrix[0]);
    $zeroCoords = [];

    for ($i = 0; $i < $rows; $i++) {
        for ($j = 0; $j < $cols; $j++) {
            if ($matrix[$i][$j] === 0) $zeroCoords[] = [$i, $j];   // Record ORIGINAL zero positions first
        }
    }

    foreach ($zeroCoords as [$r, $c]) {
        for ($j = 0; $j < $cols; $j++) $matrix[$r][$j] = 0;   // Zero the whole row
        for ($i = 0; $i < $rows; $i++) $matrix[$i][$c] = 0;   // Zero the whole column
    }
}

//  --- Approach 2: O(m+n) Space with Separate Row/Col Marker Arrays (Better) ---
//  Intuition : instead of storing every coordinate, just remember WHICH
//              rows and WHICH columns need zeroing using two small boolean
//              arrays.
//  TC: O(m*n)  |  SC: O(m+n)
//
function setZeroesLinearSpace(array &$matrix): void {
    $rows = count($matrix);
    $cols = count($matrix[0]);
    $zeroRows = array_fill(0, $rows, false);
    $zeroCols = array_fill(0, $cols, false);

    for ($i = 0; $i < $rows; $i++) {
        for ($j = 0; $j < $cols; $j++) {
            if ($matrix[$i][$j] === 0) {
                $zeroRows[$i] = true;
                $zeroCols[$j] = true;
            }
        }
    }

    for ($i = 0; $i < $rows; $i++) {
        for ($j = 0; $j < $cols; $j++) {
            if ($zeroRows[$i] || $zeroCols[$j]) $matrix[$i][$j] = 0;
        }
    }
}

//  --- Approach 3: O(1) Space Using First Row/Column as Markers (Optimal) ---
//  Intuition : reuse matrix[i][0] and matrix[0][j] themselves as the
//              marker storage for "row i needs zeroing" / "column j needs
//              zeroing" -- no auxiliary array needed at all. Because the
//              first row and first column are THEMSELVES part of the data
//              being marked, two separate boolean flags
//              ($firstRowHasZero, $firstColHasZero) capture whether row 0
//              / column 0 need zeroing, decoupled from their use as markers.
//  Algorithm : 1) Scan row 0 and column 0 up front; remember if EITHER
//                 originally contained a zero (separately from the marking
//                 process that's about to reuse them).
//              2) For i,j from 1 (skip row 0 / col 0): if matrix[i][j]==0,
//                 mark matrix[i][0]=0 and matrix[0][j]=0.
//              3) For i,j from 1: if matrix[i][0]==0 OR matrix[0][j]==0,
//                 zero matrix[i][j]. (Do this BEFORE touching row0/col0
//                 themselves, since they're still being read as markers here.)
//              4) Finally, zero row 0 entirely if firstRowHasZero was true,
//                 and column 0 entirely if firstColHasZero was true.
//  TC: O(m*n)  |  SC: O(1)
//
function setZeroes(array &$matrix): void {
    $rows = count($matrix);
    $cols = count($matrix[0]);
    $firstRowHasZero = false;
    $firstColHasZero = false;

    // Step 1: record (BEFORE any mutation) whether row 0 / col 0 originally had a zero
    for ($j = 0; $j < $cols; $j++) if ($matrix[0][$j] === 0) $firstRowHasZero = true;
    for ($i = 0; $i < $rows; $i++) if ($matrix[$i][0] === 0) $firstColHasZero = true;

    // Step 2: use row 0 / col 0 as marker storage for the REST of the matrix
    for ($i = 1; $i < $rows; $i++) {
        for ($j = 1; $j < $cols; $j++) {
            if ($matrix[$i][$j] === 0) {
                $matrix[$i][0] = 0;   // Mark: row i needs zeroing
                $matrix[0][$j] = 0;   // Mark: column j needs zeroing
            }
        }
    }

    // Step 3: apply the markers to zero out the interior (rows/cols 1..end)
    for ($i = 1; $i < $rows; $i++) {
        for ($j = 1; $j < $cols; $j++) {
            if ($matrix[$i][0] === 0 || $matrix[0][$j] === 0) {
                $matrix[$i][$j] = 0;
            }
        }
    }

    // Step 4: finally handle row 0 and column 0 themselves, using the
    // flags captured in Step 1 (must be done LAST, since Steps 2-3 were
    // still reading row 0 / col 0 as marker storage).
    if ($firstRowHasZero) {
        for ($j = 0; $j < $cols; $j++) $matrix[0][$j] = 0;
    }
    if ($firstColHasZero) {
        for ($i = 0; $i < $rows; $i++) $matrix[$i][0] = 0;
    }
}

//  --- Dry Run ---  [[1,1,1],[1,0,1],[1,1,1]]
//  Step1: firstRowHasZero=false (row0=[1,1,1]), firstColHasZero=false (col0=[1,1,1])
//  Step2: matrix[1][1]=0 -> mark matrix[1][0]=0, matrix[0][1]=0
//         matrix now: [[1,0,1],[0,0,1],[1,1,1]]
//  Step3: for i=1,j=1: matrix[1][0]=0 or matrix[0][1]=0 -> zero matrix[1][1] (already 0)
//         for i=1,j=2: matrix[1][0]=0 -> zero matrix[1][2]
//         for i=2,j=1: matrix[0][1]=0 -> zero matrix[2][1]
//         for i=2,j=2: neither marker set -> stays 1
//         matrix now: [[1,0,1],[0,0,0],[1,0,1]]
//  Step4: firstRowHasZero=false, firstColHasZero=false -> no change
//  Output: [[1,0,1],[0,0,0],[1,0,1]]

$matrix = [[1, 1, 1], [1, 0, 1], [1, 1, 1]];
setZeroes($matrix);
echo "Matrix with zeroes:\n";
foreach ($matrix as $row) echo implode(" ", $row) . "\n";

//  --- Patterns Used ---   Primary: Matrix Boundary/Marker Trick.  Secondary: Hashing (brute alternative).
//  --- Recognition Tips ---
//    Use when: "in-place" + "O(1) extra space" appears alongside a matrix
//              row/column propagation problem.
//    Don't use when: O(m+n) space is acceptable -- Approach 2 is simpler to
//              write correctly under interview time pressure and still
//              demonstrates good complexity awareness.
//    Similar problems: Game of Life (LC289, similar "encode extra state
//              in-place, decode after" trick), Rotting Oranges (multi-source
//              BFS, different technique but similar "propagate from marked
//              cells" flavor).
//  --- Edge Cases ---
//    - Zero in row 0 or column 0 -> exactly why the two separate boolean
//      flags exist; without them, using row0/col0 as BOTH data and marker
//      would create an unresolvable ambiguity about whether a 0 there was
//
//  --- 🧪 Additional Senior-Level Prep (tests, gotchas, follow-ups) ---
//  $m = [[1,1,1],[1,0,1],[1,1,1]]; setZeroes($m); assert($m === [[1,0,1],[0,0,0],[1,0,1]]);
//  $m2 = [[0,0,0]]; setZeroes($m2); assert($m2 === [[0,0,0]]);   // already all zero -- correct no-op-in-effect
//  Mistake Recovery : If you zero row 0 or column 0 too EARLY (before Steps 2-3 finish reading them as markers), you'll destroy marker information mid-algorithm and produce a matrix with extra incorrect zeroes. If a dry run shows more zeroes than expected, check whether Step 4 (handling row0/col0 themselves) accidentally ran before Steps 2-3 finished.
//  Follow-Up / Scale-Up:
//    - Same idea, different problem: Game of Life (LC289) -- encode extra state (about-to-die / about-to-be-born) IN the existing cell values using extra bits, decode in a second pass, same 'reuse existing storage' philosophy.
//    - What if the matrix is read-only / can't be mutated at all? -> falls back to the O(m+n)-space Better approach with separate marker arrays -- name this trade-off explicitly if the in-place constraint is dropped.
//
//      original or just a marker.
//    - Entire matrix is already all zero -> everything stays zero;
//      algorithm is a correct (if redundant) no-op in effect.
//    - Single row or single column matrix -> the loops from index 1
//      naturally do nothing if rows=1 or cols=1, and Step 4's row0/col0
//      handling correctly covers the entire matrix in that case.
//  --- Interview Discussion ---
//    Q: Why must Step 1 (recording firstRowHasZero/firstColHasZero) happen
//       BEFORE Step 2 starts writing markers into row 0/col 0?
//    A: Because Step 2 overwrites matrix[i][0] and matrix[0][j] as markers
//       -- if you checked for original zeroes in row0/col0 AFTER Step 2,
//       you could no longer distinguish an ORIGINAL zero from a marker
//       that Step 2 just wrote there for an unrelated reason.
//    Q: Why must Step 4 happen LAST, after Steps 2 and 3?
//    A: Steps 2 and 3 are still actively READING matrix[i][0] and
//       matrix[0][j] as marker storage -- zeroing row0/col0 early would
//       destroy that marker information before it's been fully consumed.
//  --- Related Problems ---
//    Medium : Game of Life (LC289).
//    Medium : Rotting Oranges (LC994) -- different technique, similar
//              "propagate a state change across a grid" flavor.


// ================================================================================
//  PROBLEM 23 — LC 54: SPIRAL MATRIX TRAVERSAL
// ================================================================================
//
//  --- 🎯 Interview-Ready Add-Ons (constraints, timing, pitch) ---
//  Asked at      : Amazon, Microsoft, Google, Bloomberg -- very common, and a favorite for testing careful boundary-guard reasoning rather than algorithmic novelty.
//  Constraints   : 1 <= rows,cols <= 10, matrix can be non-square -> O(m*n) time (every cell visited exactly once), O(1) extra space beyond the output array.
//  Time-boxing   : Total ~10 min: 2 min restate, 8 min four-shrinking-boundaries code + a FULL dry run on a non-square example (this is where the Leg 3/4 guards get tested).
//  60-Sec Pitch  : "I maintain four shrinking boundaries (top/bottom/left/right) and sweep top row, right column, bottom row, then left column in order, shrinking the corresponding boundary after each leg -- with explicit guards on the bottom-row and left-column legs, since a single remaining row or column could otherwise get traversed twice."
//
//
//  --- How to Identify This Pattern ---
//  Keywords     : "spiral order", "traverse in a spiral", clockwise from outside in
//  Signal       : "Spiral" traversal is ALWAYS the Four-Shrinking-Boundaries
//                 technique -- there is no meaningfully different alternative approach.
//  Common mistake: Forgetting to GUARD the third and fourth legs of each
//                 spiral loop (bottom row right-to-left, left column
//                 bottom-to-top) -- without guards, a single remaining row
//                 or column gets traversed TWICE (once forward as part of
//                 an earlier leg, then again backward), producing duplicate
//                 output elements.
//
//  --- Problem Understanding ---
//  What: given an m x n matrix, return all elements in spiral order
//        (clockwise, starting from the top-left, shrinking inward).
//  Why it exists: teaches careful boundary management with FOUR
//    independently-shrinking edges -- a foundational skill for any
//    "boundary sweep" matrix problem.
//
//  --- Approach: Four Shrinking Boundaries (Optimal, the only sane approach) ---
//  Intuition : maintain topRow, bottomRow, leftCol, rightCol. Traverse the
//              top row left-to-right, then the right column top-to-bottom,
//              then (IF a row remains) the bottom row right-to-left, then
//              (IF a column remains) the left column bottom-to-top. Shrink
//              the corresponding boundary after each leg; repeat until the
//              boundaries cross.
//  TC: O(m*n) -- every cell visited exactly once  |  SC: O(1) extra (output array aside)
//
function spiralOrder(array $matrix): array {
    $topRow = 0;
    $bottomRow = count($matrix) - 1;
    $leftCol = 0;
    $rightCol = count($matrix[0]) - 1;
    $result = [];

    while ($topRow <= $bottomRow && $leftCol <= $rightCol) {
        // Leg 1: traverse the top row, left to right
        for ($j = $leftCol; $j <= $rightCol; $j++) {
            $result[] = $matrix[$topRow][$j];
        }
        $topRow++;

        // Leg 2: traverse the right column, top to bottom
        for ($i = $topRow; $i <= $bottomRow; $i++) {
            $result[] = $matrix[$i][$rightCol];
        }
        $rightCol--;

        // Leg 3: traverse the bottom row, right to left -- GUARD: only if a row still remains
        if ($topRow <= $bottomRow) {
            for ($j = $rightCol; $j >= $leftCol; $j--) {
                $result[] = $matrix[$bottomRow][$j];
            }
            $bottomRow--;
        }

        // Leg 4: traverse the left column, bottom to top -- GUARD: only if a column still remains
        if ($leftCol <= $rightCol) {
            for ($i = $bottomRow; $i >= $topRow; $i--) {
                $result[] = $matrix[$i][$leftCol];
            }
            $leftCol++;
        }
    }

    return $result;
}

//  --- Dry Run ---  [[1,2,3,4],[5,6,7,8],[9,10,11,12]]
//  ┌──────┬────────────────────────┬──────────────────────────┐
//  │ Leg  │ Action                 │ Elements Collected        │
//  ├──────┼────────────────────────┼──────────────────────────┤
//  │ 1    │ top row L->R           │ 1,2,3,4                   │
//  │ 2    │ right col T->B         │ 8,12                       │
//  │ 3    │ bottom row R->L (guard)│ 11,10,9                   │
//  │ 4    │ left col B->T (guard)  │ 5                          │
//  │ 1(2) │ next top row L->R      │ 6,7                        │
//  └──────┴────────────────────────┴──────────────────────────┘
//  Output: [1,2,3,4,8,12,11,10,9,5,6,7]

$matrix = [[1, 2, 3, 4], [5, 6, 7, 8], [9, 10, 11, 12]];
echo "Spiral: " . implode(", ", spiralOrder($matrix)) . "\n";

//  --- Patterns Used ---   Primary: Matrix Boundary Sweep (Four Pointers).
//  --- Recognition Tips ---
//    Use when: any "spiral order" traversal (reading or GENERATING, see
//              Spiral Matrix II - LC59) is required.
//    Don't use when: the traversal order is NOT spiral -- diagonal
//              traversal (LC498) or zigzag traversal use different
//              boundary/direction logic entirely.
//    Similar problems: Spiral Matrix II (LC59, generate a spiral-filled
//              matrix instead of reading one), Spiral Matrix III (LC885,
//              spiral starting from an arbitrary cell), Diagonal Traverse (LC498).
//  --- Edge Cases ---
//    - Single row -> Leg 1 consumes everything; Leg 3's guard
//      (topRow <= bottomRow, now false) correctly prevents re-traversing it.
//    - Single column -> Leg 2 consumes everything; Leg 4's guard
//
//  --- 🧪 Additional Senior-Level Prep (tests, gotchas, follow-ups) ---
//  assert(spiralOrder([[1,2,3,4],[5,6,7,8],[9,10,11,12]]) === [1,2,3,4,8,12,11,10,9,5,6,7]);
//  assert(spiralOrder([[1]]) === [1]);            // 1x1 matrix
//  assert(spiralOrder([[1],[2],[3]]) === [1,2,3]); // single column -- exercises the Leg 4 guard
//  assert(spiralOrder([[1,2,3]]) === [1,2,3]);     // single row -- exercises the Leg 3 guard
//  Mistake Recovery : If your output has DUPLICATE elements, that's almost always a missing guard on Leg 3 or Leg 4 (traversing a row/column that a previous leg already fully consumed) -- check the single-row and single-column test cases FIRST, since those are exactly where the guards matter most.
//  Follow-Up / Scale-Up:
//    - Generate a matrix filled in spiral order instead of reading one (LC59, Spiral Matrix II) -> identical four-boundary skeleton, but WRITE an incrementing counter into matrix[i][j] instead of reading from it.
//    - Spiral starting from an arbitrary cell, not the top-left (LC885, Spiral Matrix III) -> a meaningfully harder variant requiring direction-vector simulation rather than the clean four-boundary shrink.
//
//      (leftCol <= rightCol, now false) correctly prevents re-traversing it.
//    - Single cell (1x1) -> Leg 1 alone captures it; loop terminates after one pass.
//    - Non-square (rectangular) matrix -> boundaries shrink independently
//      per dimension, handled naturally by the four-pointer approach.
//  --- Interview Discussion ---
//    Q: Why are Legs 3 and 4 guarded but Legs 1 and 2 are not?
//    A: By the time we reach Legs 3 and 4 in a given outer-loop iteration,
//       Legs 1 and 2 have ALREADY shrunk topRow and rightCol -- it's now
//       possible that no row or column remains for Leg 3/4 to validly
//       traverse (e.g., a single remaining row was already fully consumed
//       by Leg 1). Without the guard, the same row/column would be visited
//       a second time, producing duplicates.
//    Q: Follow-up -- generate a matrix filled in spiral order instead of
//       reading one (LC59)?
//    A: Identical four-boundary structure, but instead of READING
//       matrix[i][j] into the result, WRITE an incrementing counter INTO
//       matrix[i][j] at each step.
//  --- Related Problems ---
//    Medium : Spiral Matrix II (LC59).
//    Medium : Diagonal Traverse (LC498).
//    Hard    : Spiral Matrix III (LC885).


// ================================================================================
//  PROBLEM 24 — LC 560: SUBARRAY SUM EQUALS K  (COUNT, not length)
// ================================================================================
//
//  --- 🎯 Interview-Ready Add-Ons (constraints, timing, pitch) ---
//  Asked at      : Amazon, Google, Facebook/Meta -- a frequent 'medium that trips people up' because the FREQUENCY-storage insight (vs. Problem 12's first-index storage) isn't obvious until you've seen it.
//  Constraints   : 1 <= n <= 2*10^4, values (and k) can be negative -> O(n) Prefix Sum + HashMap expected; O(n^2) brute force should be named explicitly as the starting point before optimizing.
//  Time-boxing   : Total ~10 min: 2 min restate + explicitly contrast with Problem 12 ('this counts, that maximizes length'), 3 min brute force, 5 min prefix-sum-with-frequency Optimal + dry run showing hash[0]=1 mattering.
//  60-Sec Pitch  : "I track prefix sums in a hashmap of VALUE -> FREQUENCY (not first-index, since I need to count every valid subarray, not just find the longest one) -- seeding hash[0]=1 up front so subarrays that start at index 0 are correctly counted too."
//
//
//  --- How to Identify This Pattern ---
//  Keywords     : "number of continuous subarrays whose sum equals k",
//                 "count of subarrays"
//  Signal       : Same Prefix Sum + HashMap engine as Problem 12, but the
//                 hash now stores a FREQUENCY (how many times each prefix
//                 sum has occurred) instead of just the FIRST INDEX --
//                 because we're COUNTING matches, not maximizing a length.
//  Hidden hint  : `hash[0] = 1` MUST be initialized before the loop starts
//                 -- it accounts for subarrays that start from index 0
//                 itself (a prefix sum of exactly k with no "earlier"
//                 prefix to subtract against).
//  Common mistake: Forgetting the `hash[0] = 1` initialization -- without
//                 it, any subarray starting exactly at index 0 that sums to
//                 k gets silently missed from the count.
//
//  --- Problem Understanding ---
//  What: count how many contiguous subarrays sum to exactly k (can include
//        negative numbers).
//  Why it exists: the natural extension of Problem 12's "longest subarray"
//    into "how MANY subarrays" -- reinforces that Prefix Sum + HashMap
//    generalizes cleanly to counting questions by switching from
//    "first-index storage" to "frequency storage."
//
//  --- Approach 1: Brute Force -- all subarrays (for contrast) ---
//  Intuition : for every (start, end) pair, sum and compare to k.
//  TC: O(n^2)  |  SC: O(1)
//
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

//  --- Approach 2: Prefix Sum + HashMap of Frequencies (Optimal) ---
//  Intuition : let prefixSum[i] = sum(nums[0..i]). A subarray (j+1..i)
//              sums to k exactly when prefixSum[j] = prefixSum[i] - k. For
//              each i, add however many PRIOR indices had that exact
//              prefix sum -- each one represents a distinct valid subarray
//              ending at i.
//  TC: O(n)  |  SC: O(n)
//
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

//  --- Dry Run ---  nums = [1,1,1], k=2
//  ┌───┬─────┬─────┬─────────┬──────────────────────┬───────┐
//  │ i │ num │ sum │ needed  │ prefixSumFreq (after) │ count │
//  ├───┼─────┼─────┼─────────┼──────────────────────┼───────┤
//  │ - │ --  │ 0   │ --      │ {0:1}                 │ 0     │
//  │ 0 │ 1   │ 1   │ -1(miss)│ {0:1, 1:1}             │ 0     │
//  │ 1 │ 1   │ 2   │ 0 (hit1)│ {0:1, 1:1, 2:1}        │ 1     │
//  │ 2 │ 1   │ 3   │ 1 (hit1)│ {0:1, 1:1, 2:1, 3:1}   │ 2     │
//  └───┴─────┴─────┴─────────┴──────────────────────┴───────┘
//  Output: 2  (subarrays [1,1] at indices [0,1] and [1,2])

echo "Subarray sum count (k=2): " . subarraySum([1, 1, 1], 2) . "\n";  // 2

//  --- Patterns Used ---   Primary: Prefix Sum + HashMap (frequency variant).
//  --- Recognition Tips ---
//    Use when: "COUNT of subarrays" summing to k (contrast with Problem 12,
//              which asks for the LONGEST such subarray).
//    Don't use when: the array is guaranteed non-negative AND you only need
//              existence (not count) -- a sliding window could work, but
//              for COUNTING problems specifically, prefix-sum+frequency is
//              almost always cleaner even with non-negative constraints.
//    Similar problems: Longest Subarray with Sum K (Problem 12 -- same
//              engine, different storage strategy), Continuous Subarray
//              Sum (LC523, uses sum % k instead of sum - k), Subarray Sums
//              Divisible by K (LC974, same modulo idea).
//  --- Edge Cases ---
//    - k = 0 -> hash[0]=1 is essential; every prefix sum that repeats
//      exactly indicates a zero-sum subarray in between.
//    - All elements are k -> every single-element subarray counts individually.
//    - Negative numbers present -> handled natively; this is precisely WHY
//      prefix-sum+hashmap (not sliding window) is required here.
//    - Empty array -> loop never runs; returns 0. Correct.
//
//  --- 🧪 Additional Senior-Level Prep (tests, gotchas, follow-ups) ---
//  assert(subarraySum([1,1,1], 2) === 2);
//  assert(subarraySum([1,2,3], 3) === 2);        // [1,2] and [3] independently
//  assert(subarraySum([1], 0) === 0);             // k=0, no zero-sum subarray exists here
//  assert(subarraySum([0,0,0], 0) === 6);         // k=0 with actual zeroes -- exercises hash[0]=1 heavily
//  Mistake Recovery : If your count is systematically LOWER than expected, the very first thing to check is whether you seeded `hash[0] = 1` before the loop -- forgetting it silently undercounts every subarray that happens to start at index 0.
//  Follow-Up / Scale-Up:
//    - Subarrays divisible by k instead of summing to exactly k (LC974) -> use `sum % k` (normalized non-negative) as the hashmap key instead of the raw sum.
//    - What if k varies across many repeated queries on the SAME array? -> precompute the prefix sum array once; each query still needs its own hashmap pass unless you also precompute a shared structure, worth discussing the trade-off explicitly.
//
//  --- Interview Discussion ---
//    Q: Why store FREQUENCY here but only the FIRST INDEX in Problem 12?
//    A: Problem 12 wants the LONGEST subarray -- only the earliest matching
//       prefix index can maximize length, so later duplicates are useless
//       to record. This problem wants a COUNT of ALL valid subarrays -- every
//       occurrence of a matching prefix sum represents a DIFFERENT valid
//       subarray, so all of them must be tallied, not just the first.
//    Q: How would "divisible by k" (LC974) change this solution?
//    A: Use `sum % k` (normalized to be non-negative) as the hashmap key
//       instead of the raw sum -- two prefix sums with the same remainder
//       mod k mean the subarray between them is divisible by k.
//  --- Related Problems ---
//    Medium : Continuous Subarray Sum (LC523).
//    Medium : Subarray Sums Divisible by K (LC974).
//    Hard    : Count Number of Nice Subarrays (LC1248, same core engine --
//              already implemented in your Two Pointer & Sliding Window file).


// ================================================================================
//  PROBLEM 25 — LC 118: PASCAL'S TRIANGLE
// ================================================================================
//
//  --- 🎯 Interview-Ready Add-Ons (constraints, timing, pitch) ---
//  Asked at      : Amazon, Microsoft, Apple -- common easy/medium warm-up, occasionally with a 'single row only' (LC119) follow-up.
//  Constraints   : 1 <= numRows <= 30 -> O(numRows^2) time and space is expected and entirely sufficient at this scale; factorial-based computation risks unnecessary overflow/perf concerns that the multiplicative formula avoids.
//  Time-boxing   : Total ~8 min: 1 min restate, 3 min direct sum-of-two-above Better approach, 4 min multiplicative-formula Optimal + dry run.
//  60-Sec Pitch  : "Each row's values are computed incrementally from the previous value in the SAME row using the multiplicative binomial identity C(n,k) = C(n,k-1) * (n-k+1) / k -- avoiding both the need to reference the entire previous row array and any risk of large intermediate factorials."
//
//
//  --- How to Identify This Pattern ---
//  Keywords     : "Pascal's triangle", "each number is the sum of the two
//                 numbers directly above it"
//  Signal       : Combinatorics + row-by-row construction is a
//                 Dynamic-Programming-on-a-Triangle-Grid signature -- each
//                 row only depends on the PREVIOUS row, a classic 1D-DP
//                 rolling-state pattern applied to a growing 2D structure.
//  Common mistake: Recomputing binomial coefficients from scratch with
//                 factorials for every cell (large factorials risk overflow
//                 and are needlessly expensive) instead of using the
//                 CONSTRUCTIVE relationship (each cell = sum of the two
//                 above it, or the multiplicative formula shown below).
//
//  --- Problem Understanding ---
//  What: generate the first `numRows` rows of Pascal's Triangle, where each
//        row's interior values equal the sum of the two values diagonally
//        above it in the previous row (edges are always 1).
//  Why it exists: teaches recognizing a combinatorial identity
//    (C(n,k) = C(n-1,k-1) + C(n-1,k)) as a DP recurrence, and shows a
//    multiplicative shortcut to avoid ever computing a raw factorial.
//
//  --- Approach 1: Direct Recurrence -- Sum of Two Above (Better, very intuitive) ---
//  Intuition : row[i][j] = row[i-1][j-1] + row[i-1][j] (treating an
//              out-of-bounds index as 0). This IS the textbook definition
//              of Pascal's Triangle, applied directly.
//  TC: O(numRows^2)  |  SC: O(numRows^2) for the output
//
function generatePascalDirect(int $numRows): array {
    $triangle = [[1]];   // Row 0 is always just [1]

    for ($i = 1; $i < $numRows; $i++) {
        $prevRow = $triangle[$i - 1];
        $row = [];
        for ($j = 0; $j <= $i; $j++) {
            $left  = $prevRow[$j - 1] ?? 0;   // Out-of-bounds -> treat as 0 (edges of the triangle)
            $right = $prevRow[$j] ?? 0;
            $row[$j] = $left + $right;
        }
        $triangle[] = $row;
    }

    return $triangle;
}

//  --- Approach 2: Multiplicative Formula (Optimal -- avoids repeated array lookups) ---
//  Intuition : C(row, col) can be computed incrementally from C(row, col-1)
//              via C(row, col) = C(row, col-1) * (row - col) / col. Each
//              row starts and ends with 1, computed directly from the
//              PREVIOUS value in the SAME row (no need to reference the
//              previous row array at all).
//  TC: O(numRows^2)  |  SC: O(numRows^2) for the output
//
function generate(int $numRows): array {
    $triangle = [];

    for ($i = 1; $i <= $numRows; $i++) {
        $row = [];
        $value = 1;
        $row[0] = $value;

        for ($j = 1; $j < $i; $j++) {
            // C(i-1, j) derived incrementally from C(i-1, j-1):
            //   C(n,k) = C(n,k-1) * (n-k+1) / k  -- here n=i-1, k=j
            $value = (int) ($value * ($i - $j) / $j);
            $row[$j] = $value;
        }

        $triangle[] = $row;
    }

    return $triangle;
}

//  --- Dry Run ---  numRows=4
//  Row 1: [1]
//  Row 2: [1,1]
//  Row 3: [1,2,1]     -> value = 1*(3-1)/1 = 2
//  Row 4: [1,3,3,1]   -> value = 1*(4-1)/1 = 3, then value = 3*(4-2)/2 = 3
//  Output: [[1],[1,1],[1,2,1],[1,3,3,1]]

echo "Pascal's Triangle:\n";
foreach (generate(5) as $row) echo implode(" ", $row) . "\n";

//  --- Patterns Used ---   Primary: DP recurrence / Combinatorics.  Secondary: Row-by-row construction.
//  --- Recognition Tips ---
//    Use when: "each value derived from previous row/values" combinatorial
//              triangle construction is asked.
//    Don't use when: you only need a SINGLE row (LC119, Pascal's Triangle
//              II) -- then compute that ONE row directly using the
//              multiplicative formula without materializing the whole
//              triangle, achieving O(k) space instead of O(numRows^2).
//    Similar problems: Pascal's Triangle II (LC119, single row only),
//              Unique Paths (LC62, secretly a Pascal's Triangle
//              combinatorial identity C(m+n-2, m-1)).
//  --- Edge Cases ---
//    - numRows = 1 -> just [[1]]. Correctly handled (loop from i=1 to 1 -> one row, inner loop doesn't execute).
//
//  --- 🧪 Additional Senior-Level Prep (tests, gotchas, follow-ups) ---
//  assert(generate(1) === [[1]]);
//  assert(generate(4) === [[1],[1,1],[1,2,1],[1,3,3,1]]);
//  assert(generate(0) === []);   // if the problem allows numRows=0, guard for it explicitly
//  PHP Gotcha       : The multiplicative formula uses `(int) ($value * ($i - $j) / $j)` -- PHP evaluates this left-to-right, so the multiplication happens BEFORE the division; reordering to divide first would introduce fractional truncation errors for larger rows.
//  Mistake Recovery : If a row comes out with the wrong middle values but correct edges (still starts/ends with 1), suspect the multiplicative formula's indices ($i - $j vs $j) before suspecting the loop bounds -- the edges are structurally guaranteed correct regardless of that formula.
//  Follow-Up / Scale-Up:
//    - Return only row K, space-optimized to O(K) instead of the full triangle (LC119) -> build that one row iteratively, updating IN PLACE from right to left to avoid overwriting a value still needed later in the same pass.
//    - Connect this to Unique Paths (LC62) -> the number of paths in an m x n grid is itself a Pascal's Triangle binomial coefficient, C(m+n-2, m-1) -- worth naming this connection if the interviewer probes for 'where else does this identity show up.'
//
//    - numRows = 0 -> returns []. Handle explicitly if the problem allows this input.
//    - Very large numRows -> the multiplicative formula avoids factorial
//      overflow risk that a naive C(n,k)=n!/(k!(n-k)!) formula would hit.
//  --- Interview Discussion ---
//    Q: Why use the multiplicative formula instead of the direct two-above-sum recurrence?
//    A: Both are O(numRows^2) overall, but the multiplicative formula
//       avoids needing to keep the ENTIRE previous row in a separate
//       variable/lookup -- it derives each value purely from the
//       immediately preceding value in the SAME row, which can feel
//       cleaner and avoids an extra array reference per cell.
//    Q: Follow-up -- return only row K (LC119), space-optimized?
//    A: Build the row iteratively updating IN PLACE from right to left
//       (to avoid overwriting a value you still need to read), achieving
//       O(K) space instead of storing the whole triangle.
//  --- Related Problems ---
//    Easy   : Pascal's Triangle II (LC119).
//    Medium : Unique Paths (LC62) -- same combinatorial identity underneath.
//    Hard    : Probability-weighted variants (rare, advanced combinatorics).


// ================================================================================
//  PROBLEM 26 — LC 229: MAJORITY ELEMENT II
// ================================================================================
//  (Implemented together with Majority Element I in Problem 15 above, since
//   they share the exact same Boyer-Moore Voting engine -- see
//   `majorityElementII()` there for the full write-up, dry run, and the
//   critical note on why VERIFICATION is mandatory for this variant.)


// ================================================================================
//  PROBLEM 27 — LC 15 (3SUM) & LC 18 (4SUM)
// ================================================================================
//
//  --- 🎯 Interview-Ready Add-Ons (constraints, timing, pitch) ---
//  Asked at      : Amazon, Meta, Google, Microsoft -- 3Sum specifically is one of the highest-frequency MEDIUM problems industry-wide; 4Sum is a common 'can you generalize this' follow-up asked immediately after.
//  Constraints   : 3Sum: 3 <= n <= 3000. 4Sum: 1 <= n <= 200. Both need UNIQUE results, no duplicate triplets/quadruplets -> O(n^2) for 3Sum, O(n^3) for 4Sum expected; O(n^3)/O(n^4) brute force plus a dedup Set is markedly worse and should be named as the naive starting point only.
//  Time-boxing   : Total ~15 min for 3Sum alone (2 min restate + duplicate-handling rules, 3 min brute force, 10 min sort+two-pointer Optimal + a THOROUGH dry run on a duplicate-heavy input) -- budget 10 more minutes if 4Sum is asked as a follow-up.
//  60-Sec Pitch  : "I sort the array once, then fix all but the last two elements with (nested) outer loops, solving the remaining two-element sum as a two-pointer sweep on the sorted suffix -- skipping duplicate values at every level, but only AFTER the first use for outer loops and only AFTER recording a match for the inner two pointers."
//
//
//  --- How to Identify This Pattern ---
//  Keywords     : "triplet/quadruplet that sums to target", "unique
//                 triplets/quadruplets", "no duplicates in the result"
//  Signal       : "N numbers that sum to a target" (N >= 3) is ALWAYS
//                 solved by: SORT the array, fix the first (N-2) elements
//                 with nested outer loops, then finish the LAST TWO with
//                 Two Pointers. This generalizes to ANY fixed N >= 2.
//  Hidden hint  : The requirement for UNIQUE triplets/quadruplets (no
//                 duplicate results) is what makes sorting mandatory even
//                 beyond enabling two pointers -- sorting groups duplicate
//                 values together, making them trivial to skip.
//  Common mistake: Skipping duplicates INCORRECTLY -- you must skip
//                 duplicates for the OUTER loop(s) only AFTER the first
//                 iteration (checking i > 0), and for the INNER two
//                 pointers only AFTER successfully recording a match (not
//                 before) -- getting this ordering wrong either produces
//                 duplicate triplets or incorrectly skips valid ones.
//
//  --- Problem Understanding ---
//  What (3Sum): find all UNIQUE triplets [a,b,c] in the array such that
//        a+b+c = 0.
//  What (4Sum): find all UNIQUE quadruplets [a,b,c,d] such that a+b+c+d = target.
//  Why it exists: the definitive generalization of Two Sum (Problem 13) to
//    N elements -- demonstrates that "fix N-2 elements, two-pointer the
//    rest" scales cleanly, and that duplicate-skipping discipline is a
//    reusable skill, not a one-off trick.
//
//  --- Approach 1: Brute Force -- triple/quadruple nested loops (for contrast) ---
//  Intuition : check every combination directly, dedupe results at the end
//              (e.g., using a Set of sorted tuples).
//  TC: O(n^3) for 3Sum, O(n^4) for 4Sum  |  SC: O(n^3) or O(n^4) worst case for the dedup set
//  Disadvantage: quadratic-to-cubic blowup; the dedup step alone is
//              expensive and error-prone compared to sorting up front.
//
//  --- Approach 2: Sort + Fix Outer Loop(s) + Two Pointers (Optimal) ---
//  Intuition (3Sum) : sort the array. Fix nums[i] with an outer loop; solve
//              the remaining "nums[j] + nums[k] = -nums[i]" as a Two-Pointer
//              Two-Sum on the SORTED suffix. Skip duplicate values for `i`
//              (after the first use) and for `j`/`k` (after recording a match).
//  TC: O(n^2)  |  SC: O(1) extra (O(n) or O(log n) for the sort, output aside)
//
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

//  Intuition (4Sum) : identical idea with ONE MORE fixed outer loop --
//              sort, fix nums[i] AND nums[j] with two nested outer loops,
//              then two-pointer the remaining "nums[k]+nums[l] = target -
//              nums[i]-nums[j]" on the sorted suffix. Duplicate-skipping
//              discipline applies at EVERY level (i, j, AND k/l).
//  TC: O(n^3)  |  SC: O(1) extra (output aside)
//
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

//  --- Dry Run (3Sum) ---  nums = [-1,0,1,2,-1,-4]  ->  sorted: [-4,-1,-1,0,1,2]
//  ┌───┬────────┬───────────┬────────────────────────────────────┐
//  │ i │ nums[i]│ left,right│ action                              │
//  ├───┼────────┼───────────┼────────────────────────────────────┤
//  │ 0 │ -4     │ 1,5       │ sum=-4-1+2=-3<0, ... no match found │
//  │ 1 │ -1     │ 2,5       │ sum=-1-1+2=0 -> add[-1,-1,2]; l++,r--│
//  │   │        │ 3,4       │ sum=-1+0+1=0 -> add[-1,0,1]; l>=r stop│
//  │ 2 │ -1     │ (dup of i=1) -> skip                            │
//  │ 3 │ 0      │ 4,5       │ sum=0+1+2=3>0, r--; l>=r stop        │
//  └───┴────────┴───────────┴────────────────────────────────────┘
//  Output: [[-1,-1,2],[-1,0,1]]

print_r(threeSum([-1, 0, 1, 2, -1, -4]));
print_r(fourSum([1, 0, -1, 0, -2, 2], 0));

//  --- Patterns Used ---   Primary: Two Pointers (on sorted array).  Secondary: Sorting, Fixed-Outer-Loop generalization.
//  --- Recognition Tips ---
//    Use when: N-Sum for a FIXED, small N (2, 3, or 4) with a "unique
//              results, no duplicates" requirement.
//    Don't use when: N is large/variable at runtime -- then recursive
//              N-Sum (fix one element, recursively solve (N-1)-Sum on the
//              remainder) generalizes better, or backtracking with pruning
//              if the target sum has additional constraints.
//    Similar problems: Two Sum (Problem 13), 3Sum Closest (LC16, track
//              minimum |sum-target| instead of exact matches), 4Sum II
//              (LC454, four SEPARATE arrays -- a hashmap problem, not
//              two-pointer), 3Sum Smaller (LC259, count pairs below a threshold).
//  --- Edge Cases ---
//    - Fewer than N elements in the array -> loop bounds (`n - 2` for
//      3Sum, `n - 3` for 4Sum) naturally prevent any iteration; returns [].
//    - All zeros -> a single triplet/quadruplet [0,0,0] or [0,0,0,0] is
//      found once, correctly deduplicated by the skip logic.
//    - Many duplicate values -> exhaustively exercises the duplicate-skip
//      logic at every level; this is the primary source of interview bugs
//      on this problem, so a dry run with heavy duplicates is the best
//      self-test before declaring the solution done.
//    - Integer overflow (in fixed-width-integer languages) -> four summed
//
//  --- 🧪 Additional Senior-Level Prep (tests, gotchas, follow-ups) ---
//  assert(threeSum([-1,0,1,2,-1,-4]) === [[-1,-1,2],[-1,0,1]]);
//  assert(threeSum([0,0,0]) === [[0,0,0]]);            // heavy duplicates -- exercises every skip-guard
//  assert(threeSum([0,1,1]) === []);                    // no valid triplet sums to zero
//  assert(fourSum([1,0,-1,0,-2,2], 0) !== []);           // sanity: at least one quadruplet exists
//  PHP Gotcha       : Summing four potentially-large values could overflow a 32-bit int in Java/C++ -- PHP's native 64-bit ints sidestep this, but ALWAYS mention the overflow risk verbally regardless of the language you're actually coding in; interviewers are listening for the awareness, not just a language-specific pass.
//  Mistake Recovery : If your result list has DUPLICATE triplets/quadruplets, the fix is almost never in the two-pointer core logic -- it's a missing or misplaced duplicate-skip guard at one of the outer loop levels; check `i > 0` and `j > i+1` guards specifically before touching anything else.
//  Follow-Up / Scale-Up:
//    - Generalize to arbitrary N (kSum) -> recursive template: base case N=2 is the two-pointer Two-Sum; otherwise fix one element (skipping duplicates as shown) and recurse into (N-1)Sum on the remaining sorted suffix with an adjusted target.
//    - 3Sum Smaller (LC259) or 3Sum Closest (LC16) -> same sort+two-pointer skeleton, but the pointer-movement DECISION changes from 'exact match' to 'count how many satisfy an inequality' or 'track minimum absolute difference' respectively.
//
//      large values can overflow 32-bit int in Java/C++; PHP's native
//      64-bit integers make this a non-issue here, but ALWAYS mention it
//      verbally in an interview regardless of the language you're coding in.
//  --- Interview Discussion ---
//    Q: Why must outer-loop duplicate skipping check `i > 0` specifically?
//    A: To allow the FIRST occurrence of any value to still be processed
//       normally -- the check exists purely to prevent RE-processing the
//       exact same starting value on a later iteration, which would
//       produce duplicate result triplets.
//    Q: How would you generalize this to arbitrary N (kSum)?
//    A: Recursive template: if N == 2, run the two-pointer Two-Sum base
//       case; otherwise, fix one element with a loop (skipping duplicates
//       exactly as shown here) and recursively call (N-1)Sum on the
//       remaining sorted suffix with an adjusted target.
//  --- Related Problems ---
//    Medium : 3Sum Closest (LC16).
//    Medium : 4Sum II (LC454) -- four separate arrays, HashMap-based, not two-pointer.
//    Hard    : Generalized kSum via recursive template (common Staff-level follow-up).


// ================================================================================
//  SECTION Y — MASTER REVISION CHEAT SHEET (one-page night-before-interview scan)
// ================================================================================
//
//  ┌──────────────────────────────┬─────────────────────────────────────────┐
//  │ IF THE PROBLEM SAYS...        │ ...REACH FOR THIS ENGINE                 │
//  ├──────────────────────────────┼─────────────────────────────────────────┤
//  │ pair/triplet/quad sums to X   │ Sort + Two Pointers (fix N-2, 2-ptr rest)│
//  │ sorted + in-place removal     │ Two Pointers (slow/fast)                 │
//  │ longest/shortest contiguous   │ Sliding Window (non-negative) OR         │
//  │   run/subarray                │   Prefix Sum + HashMap (if negatives)    │
//  │ count of subarrays == K       │ Prefix Sum + HashMap (store FREQUENCY)   │
//  │ maximum subarray sum          │ Kadane's Algorithm                       │
//  │ appears twice except one      │ XOR (Bit Manipulation)                   │
//  │ majority (> n/2 or > n/3)     │ Boyer-Moore Voting (1 or 2 candidates)   │
//  │ sort 0s/1s/2s in one pass     │ Dutch National Flag (3-way partition)    │
//  │ rotate array by k             │ Reversal Trick (segment order matters!)  │
//  │ next lexicographic arrangement│ 3-Step Reversal Trick (dip/swap/reverse) │
//  │ rotate matrix / spiral / set  │ Matrix Boundary Tricks (transpose+       │
//  │   zeroes in-place              │   reverse, 4 shrinking pointers, row0/  │
//  │                                │   col0 as markers)                      │
//  │ consecutive VALUES (not idx)  │ HashSet, count only from sequence heads  │
//  │ union/missing/duplicate in a  │ Hashing OR Gauss Sum / XOR (if range is  │
//  │   known contiguous range      │   known and contiguous)                  │
//  └──────────────────────────────┴─────────────────────────────────────────┘
//
//  UNIVERSAL EDGE-CASE CHECKLIST (run through this for EVERY array problem)
//  --------------------------------------------------------------------------
//  [ ] Empty array
//  [ ] Single element
//  [ ] All elements identical
//  [ ] All negative / all positive / mixed signs
//  [ ] Already sorted / reverse sorted
//  [ ] Duplicates at the boundary of a two-pointer or voting algorithm
//  [ ] k = 0, k = n, k > n (for any "k" parameter -- window size, rotation, target)
//  [ ] Integer overflow (matters in Java/C++; PHP's 64-bit ints mostly protect
//      you, but ALWAYS mention it verbally in interviews regardless of language)
//
//  FREQUENTLY FORGOTTEN POINTS
//  --------------------------------------------------------------------------
//  - Initialize max/min accumulators to PHP_INT_MIN/MAX, NEVER 0 (breaks on
//    all-negative or all-positive inputs).
//  - `k = k % n` before ANY rotation logic -- k can exceed array length.
//  - RIGHT rotation reverses [first n-k, then last k, then whole]; LEFT
//    rotation reverses [first k, then last n-k, then whole] -- these are
//    NOT interchangeable (see Problem 6's bug fix).
//  - Skip duplicates in N-Sum problems ONLY after i>0 for outer loops, and
//    ONLY after recording a match for inner two-pointers.
//  - Prefix-sum hashmaps: store FIRST INDEX for "longest subarray" questions,
//    store FREQUENCY for "count of subarrays" questions -- these are different!
//  - `hash[0] = 1` must be pre-seeded for "count of subarrays summing to k"
//    (Problem 24) to correctly count subarrays starting at index 0.
//  - Sliding Window is INVALID the moment negative numbers can appear --
//    switch to Prefix Sum + HashMap instead.
//  - Verification passes after Boyer-Moore Voting are MANDATORY whenever
//    the problem does not GUARANTEE the target element exists (LC229, not
//    LC169).
//
// ================================================================================


// ================================================================================
//  SECTION Z — BUG LOG (every bug found in the original Array.php, with fixes)
// ================================================================================
//
//  BUG 1 — Rotate Array (Problem 6, LC189) — SEVERITY: HIGH (wrong output)
//    Original `Solution::rotate()` reversed segments in the wrong order,
//    producing a LEFT rotation when a RIGHT rotation was required. Verified
//    by tracing nums=[1,2,3,4,5,6,7], k=3: original code produced
//    [4,5,6,7,1,2,3] (left rotation); LC189 requires [5,6,7,1,2,3,4].
//    FIX: reverse segments in the order [first n-k] -> [last k] -> [whole
//    array] for a RIGHT rotation. See `rotateRight()` in Problem 6 above.
//
//  BUG 2 — Next Permutation (Problem 19, LC31) — SEVERITY: CRITICAL (silently
//    kills all subsequent code when the file is run as a script)
//    The original file had an orphaned, duplicate, TOP-LEVEL copy of the
//    Next Permutation logic (outside any function) ending in an
//    unconditional `return;`. In PHP, `return` at the top level of an
//    executed script file halts the ENTIRE script immediately. Since this
//    code appeared before roughly 40% of the file (Problems 20-27), running
//    `php Array.php` sequentially would never execute the demo output for
//    any of Longest Consecutive Sequence, Rotate Image, Set Matrix Zeroes,
//    Spiral Matrix, Subarray Sum Equals K, Pascal's Triangle, Majority
//    Element II, 3Sum, or 4Sum.
//    FIX: removed the orphaned top-level script block entirely; kept only
//    the clean `nextPermutation()` function. Its exploratory walkthrough
//    commentary was preserved and folded into the "Approach" prose in
//    Problem 19 above so no explanatory content was lost.
//
//  BUG 3 — Longest Consecutive Sequence (Problem 20, LC128) — SEVERITY:
//    CRITICAL (function ignores its own input)
//    `longestConsecutive(array $nums)` began with the line
//    `$nums = [0,3,7,2,8,4,6,0,1];`, immediately discarding whatever array
//    was actually passed in and always operating on a hardcoded test array
//    instead. Any caller passing a different array would silently get the
//    wrong answer with no error raised.
//    FIX: deleted the hardcoded overwrite line entirely. See the corrected
//    `longestConsecutive()` in Problem 20 above, which now genuinely
//    operates on its `$nums` parameter.
//
//  BUG 4 — Majority Element V2 (Problem 15, LC169) — SEVERITY: LOW (code
//    smell / strict-mode warning, not an incorrect result in practice)
//    `majorityElementV2()` used `$verify` inside its verification loop
//    without ever initializing it to 0, relying on PHP's implicit
//    null-to-0 coercion on first increment. A dead `$count = 0;` line sat
//    unused nearby, a likely leftover from refactoring. This triggers an
//    "Undefined variable $verify" warning under PHP 8+ and is fragile if
//    the function is ever restructured.
//    FIX: explicitly initialize `$verifyCount = 0;` before the loop and
//    removed the dead line. See `majorityElement()` in Problem 15 above.
//
//  MINOR NOTE (not a bug) — Move Zeroes (Problem 7, LC283)
//    The implemented swap-based approach performs a self-swap (i === j)
//    for every non-zero element encountered before the first zero -- this
//    is harmless (a value swapped with itself is unchanged) but does
//    slightly more work than strictly necessary. Documented as a
//    micro-optimization opportunity in Problem 7 above, not corrected in
//    the main implementation since it doesn't affect correctness or
//    asymptotic complexity.
//
// ================================================================================


// ================================================================================
//  SECTION Z1 — PRE-SUBMISSION CHECKLIST (run through this before saying "I'm done")
// ================================================================================
//
//  Whether in a live interview or self-practice, run through this list before
//  declaring any problem in this file solved:
//
//  [ ] Restated the problem back in my own words BEFORE writing any code.
//  [ ] Named the pattern explicitly out loud ("this is a sliding window
//      problem because...") -- not just solved it silently.
//  [ ] Stated time & space complexity BEFORE coding, not after the fact.
//  [ ] Checked the constraints and confirmed my chosen complexity is safe
//      for them (see each problem's "Constraints -> Safe complexity" line,
//      and the general n-to-complexity table in Section Y).
//  [ ] Walked through the edge-case checklist out loud (empty, single
//      element, all-same, all-negative, already-sorted, k=0/k=n) --
//      not just silently handled them in code.
//  [ ] Dry-ran the optimal solution on the given example BEFORE claiming
//      it works.
//  [ ] Ran (or mentally executed) the assert()-style test cases in each
//      problem's "Additional Senior-Level Prep" block.
//  [ ] Proactively offered at least one follow-up/optimization/scale-up
//      angle without being asked (see each problem's Follow-Up section).
//  [ ] If I changed approach mid-solve, named WHY out loud rather than
//      silently rewriting ("I realize this is O(n^2), let me reconsider").
//
// ================================================================================


// ================================================================================
//  SECTION Z2 — TOPIC SUMMARY
// ================================================================================
//
//  ✅ Concepts Learned
//     - Single-pass accumulator tracking (max/min, second max/min)
//     - Two Pointers: same-direction (slow/fast compaction) and
//       opposite-direction (sorted-array convergence)
//     - Sliding Window (implicit counter-based, and explicit expand/shrink)
//     - Prefix Sum + HashMap (both "first-index" and "frequency" storage modes)
//     - Kadane's Algorithm (1D DP: extend-or-restart decision at each index)
//     - Dutch National Flag (3-way in-place partitioning)
//     - Boyer-Moore Voting (1-candidate and 2-candidate generalizations)
//     - Reversal Trick (array rotation, next permutation)
//     - Matrix Boundary Tricks (transpose+reverse, four shrinking
//       boundaries, row0/col0-as-markers)
//     - Hashing for O(1) lookup / dedup / sequence-head detection
//     - Bit Manipulation (XOR cancellation)
//     - N-Sum generalization (sort + fix N-2 + two-pointer the rest)
//
//  ✅ Patterns Covered
//     Two Pointers · Sliding Window · Prefix Sum · Kadane's · Dutch National
//     Flag · Boyer-Moore Voting · Reversal Trick · Matrix Boundary Tricks ·
//     Hashing · Bit Manipulation (XOR) · Sort + Two-Pointer N-Sum
//
//  ✅ Variations Explored
//     Equal vs. unequal-count rearrangement, single-candidate vs.
//     two-candidate voting, longest-subarray vs. count-of-subarrays prefix
//     sum, left vs. right rotation, clockwise vs. counter-clockwise matrix
//     rotation, 3Sum vs. 4Sum vs. generalized kSum.
//
//  ✅ Applications
//     This topic underpins nearly every subsequent DSA topic: Prefix Sum
//     reappears in the DP-1D/2D topics; Two Pointers reappears in Linked
//     List and String topics; Boyer-Moore Voting and XOR tricks reappear in
//     Bit Manipulation; Matrix Boundary Tricks reappear in Graph/BFS grid
//     problems.
//
//  ✅ Related Topics To Revise Next
//     Two Pointer & Sliding Window (dedicated file, deeper variable-window
//     coverage) · Binary Search (especially Binary-Search-on-Answer) ·
//     Hashing (dedicated file) · Bit Manipulation (dedicated file, deeper
//     XOR/bit-counting coverage) · Sorting & Cyclic Sort (for the missing-
//     number / duplicate-number family this file only partially covers).
//
//  ✅ Difficulty Level Of This Topic Overall: Easy -> Medium, with a few
//     Hard-adjacent problems (3Sum/4Sum, Set Matrix Zeroes O(1) variant,
//     Next Permutation). A strong foundation for every later DSA topic --
//     revisit this file until every "How to Identify This Pattern" section
//     can be recalled from the problem title alone, without reading the code.
//
// ================================================================================


// ================================================================================
//  SECTION Z3 — SPACED-REPETITION REVISION SCHEDULE
// ================================================================================
//
//  Long-term retention beats one-time understanding. Suggested cadence for
//  every problem in this file: Day 1 (solve) -> Day 3 (re-solve without
//  looking) -> Day 7 -> Day 21 -> Day 60. On each revisit, try to reproduce
//  from memory, in order: (1) the pattern name, (2) the 60-Second Pitch,
//  (3) the code, (4) the edge cases -- in that order, since recalling the
//  PATTERN first is what actually transfers to unfamiliar problems in a
//  real interview, not memorizing this exact code.
//
//  Suggested tracking (update the checkboxes yourself as you revise):
//  [ ] Day 1  -- solved all 27 problems, understood every bug fix
//  [ ] Day 3  -- re-derived Problems 1-9   from the pattern name alone
//  [ ] Day 3  -- re-derived Problems 10-18 from the pattern name alone
//  [ ] Day 3  -- re-derived Problems 19-27 from the pattern name alone
//  [ ] Day 7  -- full re-solve, timed, using the Time-Boxing guidance per problem
//  [ ] Day 21 -- full re-solve, timed, cold (no notes)
//  [ ] Day 60 -- final check before live interviews begin
//
//  Prioritize revisiting: Next Permutation (19), Set Matrix Zeroes (22),
//  3Sum/4Sum (27), and Longest Consecutive Sequence (20) first -- these are
//  the four problems in this file most likely to be the "hard filter"
//  question in a Senior-level loop, and the ones with the least intuitive
//  algorithms to reconstruct from scratch under pressure.
//
// ================================================================================
?>
