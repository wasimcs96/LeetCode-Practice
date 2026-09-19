<?php

/*
|--------------------------------------------------------------------------
| Problem: Missing Number (LC 268)
| Source: DS/3. Array/Array_enhancement.php
| Pattern: Sorting / Cyclic Sort
|--------------------------------------------------------------------------
*/

/*
Problem Description:
 What: given n distinct numbers taken from [0, n], exactly one number in
       that range is missing from the array (which has n elements, not n+1).
       Find it.
 Why it exists: teaches that a "closed-form expected total" (Gauss's
   formula) or a "self-cancelling operation" (XOR) can replace an
   explicit membership-tracking structure entirely.

Example:
Input: nums = [3,0,1] -> Output: 2
Input: nums = [1,2,3,4,5,7,8,9,10] -> Output: 6

Why This Pattern:
The array holds n elements whose values are bounded to the range [0, n] - the exact structural signal ('n numbers, range 0..n, one missing') that flags this as a foundational Cyclic-Sort-family problem, where a value's own magnitude tells you where it belongs. (This file solves it via Math/XOR rather than an explicit index-swap pass, but it is the flagship example problem for recognizing the Cyclic Sort signal.)
*/

function missingNumberGaussSum(array $nums): int {
    $n = count($nums);                    // nums has n elements, representing range [0, n]
    $expectedSum = intdiv($n * ($n + 1), 2);   // Sum of 0..n via Gauss's formula
    $actualSum   = array_sum($nums);

    return $expectedSum - $actualSum;     // Whatever's missing accounts for the shortfall
}

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


/*
|--------------------------------------------------------------------------
| Problem: Valid Anagram (LC 242)
| Source: DS/5. String/String_enhancement.php
| Pattern: Sorting / Cyclic Sort
|--------------------------------------------------------------------------
*/

/*
Problem Description:
 What: return true if t is an anagram of s (same characters, same
       counts, any order).
 Why it exists: the canonical frequency-count problem -- almost every
   later "compare character compositions" problem reduces to this exact technique.


 --- 🎯 Interview-Ready Add-Ons (constraints, timing, pitch) ---
 Asked at      : Nearly every company -- one of the most common easy warm-ups, often paired immediately with 'now do Group Anagrams' as a harder follow-up.
 Constraints   : 1 <= s.length, t.length <= 5*10^4, lowercase English letters only -> O(n) fixed-size array[26] frequency count expected.
 Time-boxing   : Total ~5 min: near-instant -- if this takes more than 5-6 minutes, drill fundamentals before moving to harder problems.
 60-Sec Pitch  : "I use a single 26-slot counter array, incrementing for every character in s and decrementing for every character in t -- if every slot returns to zero, the two strings have identical character compositions."

Example:
Input: s="anagram", t="nagaram" -> Output: true
Input: s="rat", t="car" -> Output: false

Why This Pattern:
Sorting both strings and comparing the results character-by-character is the standard alternative to frequency counting for anagram detection - if the sorted strings are identical, the character multisets match.
*/

function isAnagramSort(string $s, string $t): bool
{
    if (strlen($s) !== strlen($t)) return false;

    $sChars = str_split($s);
    $tChars = str_split($t);
    sort($sChars);
    sort($tChars);

    return $sChars === $tChars;
}

function isAnagram(string $s, string $t): bool
{
    if (strlen($s) !== strlen($t)) return false;

    $charCounts = array_fill(0, 26, 0);

    for ($i = 0; $i < strlen($s); $i++) {
        $charCounts[ord($s[$i]) - ord('a')]++;   // Tally characters from s
    }
    for ($i = 0; $i < strlen($t); $i++) {
        $charCounts[ord($t[$i]) - ord('a')]--;   // Untally characters from t
    }

    foreach ($charCounts as $count) {
        if ($count !== 0) return false;           // Any nonzero count means the multisets differ
    }

    return true;
}


/*
|--------------------------------------------------------------------------
| Problem: Sort List — Merge Sort on a Linked List (LC 148)
| Source: DS/6. LinkedList/9. Single-LL.php
| Pattern: Sorting / Cyclic Sort
|--------------------------------------------------------------------------
*/

/*
Problem Description:
SORT A LINKED LIST — Merge Sort (LeetCode 148)
Intuition:
  1. Find middle (first-mid variant).
  2. Split into two halves (mid->next = null).
  3. Recursively sort each half.
  4. Merge the two sorted halves.

Dry Run: list=4→2→1→3
  Split: [4→2] and [1→3]
  Sort [4→2]: split [4] and [2] → merge → [2→4]
  Sort [1→3]: split [1] and [3] → merge → [1→3]
  Merge: 1→2→3→4  ✓

TC: O(N log N)  SC: O(log N) — recursion stack

Example:
Input: list = 4→2→1→3
Output: 1→2→3→4

Why This Pattern:
The overall algorithm is a textbook recursive merge sort applied to a linked list - split at the middle, sort each half, merge the sorted halves back together.
*/

    private function getMidForSort(?Node $head): ?Node
    {
        $slow = $head;
        $fast = $head;
        while ($fast->next !== null && $fast->next->next !== null) {
            $slow = $slow->next;
            $fast = $fast->next->next;
        }
        return $slow; // Returns first middle (left half gets ≤ right half)
    }

    public function sortList(?Node $head): ?Node
    {
        if ($head === null || $head->next === null) return $head;

        $mid       = $this->getMidForSort($head);
        $rightHead = $mid->next; // Right half starts after mid
        $mid->next = null;       // Cut the list in two

        $sortedLeft  = $this->sortList($head);
        $sortedRight = $this->sortList($rightHead);

        return $this->mergeTwoSortedLists($sortedLeft, $sortedRight);
    }

