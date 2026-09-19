<?php

/*
|--------------------------------------------------------------------------
| Problem: Union of Two Sorted Arrays
| Source: DS/3. Array/Array_enhancement.php
| Pattern: Hashing
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
When the inputs cannot be assumed sorted, building a HashSet from both arrays gives O(1) membership/dedup checks and is the standard fallback technique for computing a union.
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
| Problem: Longest Subarray With Sum Equals K
| Source: DS/3. Array/Array_enhancement.php
| Pattern: Hashing
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
The core lookup structure is a hashmap keyed by prefix-sum value, giving O(1) average-case lookups for the earliest index at which a given prefix sum occurred.
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
| Problem: Two Sum (LC 1)
| Source: DS/3. Array/Array_enhancement.php
| Pattern: Hashing
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
A hashmap of value to index lets you check for a target's complement in O(1) as you scan once - the foundational 'have I seen this before' hashing pattern.
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
| Problem: Longest Consecutive Sequence (LC 128)
| Source: DS/3. Array/Array_enhancement.php
| Pattern: Hashing
|--------------------------------------------------------------------------
*/

/*
Problem Description:
 What: given an unsorted array of integers, find the length of the
       longest run of CONSECUTIVE integers (values, not positions) —
       e.g., [100,4,200,1,3,2] contains the sequence 1,2,3,4 -> answer 4.
 Why it exists: forces you to separate "sorted order" from "consecutive
   VALUES" -- teaches the "only start counting from a true sequence head"
   optimization that turns an apparent O(n^2) idea into genuine O(n).

Example:
Input: nums = [100,4,200,1,3,2]
Output: 4 (the sequence 1,2,3,4)

Why This Pattern:
A HashSet gives O(1) membership checks so you only start counting a sequence from its true head (a number with no predecessor in the set), turning an apparent O(n^2) idea into genuine O(n).
*/

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


/*
|--------------------------------------------------------------------------
| Problem: Subarray Sum Equals K - Count (LC 560)
| Source: DS/3. Array/Array_enhancement.php
| Pattern: Hashing
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
The frequency map (prefix sum -> count of times seen) is the hashing structure that makes the O(n) counting possible; this is the same 'Prefix Sum + Hashing' combination used across the Subarray-Sum family.
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
| Problem: Isomorphic Strings (LC 205)
| Source: DS/5. String/String_enhancement.php
| Pattern: Hashing
|--------------------------------------------------------------------------
*/

/*
Problem Description:
 What: two strings are isomorphic if characters in s can be replaced to
       get t, with each character mapping to exactly one other character
       (a bijection on the characters that actually appear), preserving order.
 Why it exists: tests whether you catch the ONE-TO-ONE requirement (not
   just "does a mapping exist" but "is it consistent and non-colliding
   in both directions").


 --- 🎯 Interview-Ready Add-Ons (constraints, timing, pitch) ---
 Asked at      : Amazon, Google, Bloomberg -- a favorite for catching candidates who only check the mapping in one direction.
 Constraints   : 1 <= s.length <= 5*10^4, s.length === t.length -> O(n) two-map approach expected; the strpos() trick is a valid but quietly O(n^2) alternative worth naming honestly.
 Time-boxing   : Total ~8 min: 2 min restate + the ab/aa collision example, 6 min two-map bidirectional solution + dry run.
 60-Sec Pitch  : "I maintain two hashmaps (s-char to t-char and t-char to s-char) and verify BOTH directions stay consistent on every pair -- this catches not just 'one character maps to two different targets' but also the reverse collision where two different characters map to the same target."

Example:
Input: s="egg", t="add" -> Output: true
Input: s="foo", t="bar" -> Output: false

Why This Pattern:
Two hashmaps (one per direction, s-char->t-char and t-char->s-char) enforce a consistent bijection - the same 'track what I've mapped so far' hashing technique used for Two Sum, just checked in both directions at once.
*/

function isIsomorphicFirstOccurrence(string $s, string $t): bool
{
    if (strlen($s) !== strlen($t)) return false;

    for ($i = 0; $i < strlen($s); $i++) {
        if (strpos($s, $s[$i]) !== strpos($t, $t[$i])) {   // First-occurrence indices must match
            return false;
        }
    }

    return true;
}

function isIsomorphic(string $s, string $t): bool
{
    if (strlen($s) !== strlen($t)) return false;

    $sToT = [];
    $tToS = [];

    for ($i = 0; $i < strlen($s); $i++) {
        $sChar = $s[$i];
        $tChar = $t[$i];

        if (isset($sToT[$sChar]) && $sToT[$sChar] !== $tChar) {
            return false;    // s-char already mapped to a DIFFERENT t-char
        }
        if (isset($tToS[$tChar]) && $tToS[$tChar] !== $sChar) {
            return false;    // t-char already mapped to a DIFFERENT s-char (catches collisions)
        }

        $sToT[$sChar] = $tChar;
        $tToS[$tChar] = $sChar;
    }

    return true;
}


/*
|--------------------------------------------------------------------------
| Problem: Valid Anagram (LC 242)
| Source: DS/5. String/String_enhancement.php
| Pattern: Hashing
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
A frequency count (here a fixed 26-slot array instead of a general hashmap, since the alphabet is bounded) is the canonical hashing technique for 'do these have the same character composition' questions.
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
| Problem: Sort Characters by Frequency (LC 451)
| Source: DS/5. String/String_enhancement.php
| Pattern: Hashing
|--------------------------------------------------------------------------
*/

/*
Problem Description:
 What: given a string, rearrange it so characters are ordered by
       DECREASING frequency (ties can be broken arbitrarily, though this
       implementation breaks ties alphabetically for determinism).
 Why it exists: introduces the "invert a frequency map into a
   count-to-characters map" technique, a stepping stone toward true
   Bucket Sort and toward heap-based Top-K-Frequent-style problems.


 --- 🎯 Interview-Ready Add-Ons (constraints, timing, pitch) ---
 Asked at      : Amazon, Google, Bloomberg -- a moderately common medium testing the 'invert a frequency map into buckets' technique.
 Constraints   : 1 <= s.length <= 5*10^5 -> O(n + k log k) where k is the distinct-character count expected (effectively O(n) for a bounded alphabet); avoid re-scanning the full string more than a constant number of times.
 Time-boxing   : Total ~10 min: 2 min restate, 8 min count-then-invert-then-rebuild + dry run showing the frequency-to-characters inversion clearly.
 60-Sec Pitch  : "I count character frequencies, then INVERT that map so each distinct count maps to a list of characters sharing it, sort those counts descending, and rebuild the answer tier by tier, breaking ties alphabetically within each tier."

Example:
Input: s="tree"
Output: "eert" (or "eetr" - either is a valid LC451 answer)

Why This Pattern:
The first step is a hashmap frequency count of every character - the same counting technique that anchors every 'frequency of X' hashing problem.
*/

function frequencySort(string $s): string
{
    $n = strlen($s);
    if ($n <= 1) return $s;   // Already trivially "sorted" for 0 or 1 characters

    // Step 1: count frequency of every character
    $frequencyMap = [];
    foreach (str_split($s) as $char) {
        $frequencyMap[$char] = ($frequencyMap[$char] ?? 0) + 1;
    }

    // Step 2: invert the map -- count -> list of characters with that count
    $countToChars = [];
    foreach ($frequencyMap as $char => $count) {
        $countToChars[$count][] = $char;
    }

    krsort($countToChars);   // Highest frequency first

    // Step 3: rebuild the answer, highest frequency tier first, alphabetical within a tier
    $answer = '';
    foreach ($countToChars as $count => $chars) {
        sort($chars);   // Deterministic tie-breaking within the same frequency tier
        foreach ($chars as $char) {
            $answer .= str_repeat($char, $count);
        }
    }

    return $answer;
}


/*
|--------------------------------------------------------------------------
| Problem: Sum of Beauty of All Substrings (LC 1781)
| Source: DS/5. String/String_enhancement.php
| Pattern: Hashing
|--------------------------------------------------------------------------
*/

/*
Problem Description:
 What: the "beauty" of a string is (frequency of its most frequent
       character) minus (frequency of its least frequent character).
       Sum the beauty of every possible substring of s.
 Why it exists: reinforces the "incremental frequency map inside a
   double loop" technique -- avoiding redundant recomputation is the
   entire difference between an accepted O(n^2) solution and a
   too-slow O(n^3) one on this problem's constraints.


 --- 🎯 Interview-Ready Add-Ons (constraints, timing, pitch) ---
 Asked at      : Amazon, Google -- a moderately common medium testing whether you avoid the O(n^3) trap of recomputing frequency counts from scratch for every substring.
 Constraints   : 1 <= s.length <= 500, lowercase English letters -> O(n^2) with an incrementally-updated frequency map expected; recomputing frequencies per substring silently degrades this to O(n^3).
 Time-boxing   : Total ~10 min: 2 min restate 'beauty = max freq - min freq', 8 min fixed-start/incremental-frequency-map double loop + dry run showing the map growing incrementally.
 60-Sec Pitch  : "I fix the substring's start index in an outer loop and extend the end index in an inner loop, incrementally updating ONE frequency map per start (reset only when start advances) -- this avoids ever rebuilding frequency counts from scratch, keeping the whole algorithm O(n^2) instead of O(n^3)."

Example:
Input: s="aabcb"
Output: 5

Why This Pattern:
Each substring's 'beauty' is computed from an incrementally-maintained character frequency map - the same hashmap-based frequency tracking used throughout the Hashing pattern, just updated incrementally instead of rebuilt from scratch.
*/

function beautySum(string $s): int
{
    $n = strlen($s);
    if ($n <= 1) return 0;   // No substring of length >= 2 with a meaningful spread exists

    $totalBeauty = 0;

    for ($start = 0; $start < $n; $start++) {
        $freqMap = [];   // Reset ONLY when the start index advances -- reused across all `end` values for this start

        for ($end = $start; $end < $n; $end++) {
            $char = $s[$end];
            $freqMap[$char] = ($freqMap[$char] ?? 0) + 1;   // Incrementally extend the frequency map by one character

            $totalBeauty += max($freqMap) - min($freqMap);   // Beauty of substring s[start..end]
        }
    }

    return $totalBeauty;
}


/*
|--------------------------------------------------------------------------
| Problem: Binary Subarrays With Sum — Prefix-Sum + HashMap (LC 930)
| Source: DS/11.Two Pointer & Sliding Window/Combined_Problems.php
| Pattern: Hashing
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
The frequency map (prefix sum -> count of times seen) is the hashing structure that makes the O(n) counting possible - the same 'have I seen this prefix sum before' technique used throughout the Hashing pattern.
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
| Problem: Character Frequency Count - Associative Array vs Fixed ASCII Array
| Source: DS/1. Basic/3. Hashing.php
| Pattern: Hashing
|--------------------------------------------------------------------------
*/

/*
Problem Description:
  PROBLEM:
    Count frequency of each character in a string.
    Print results sorted by frequency in descending order.

  EXAMPLE:
    Input  : "abcbdabfg"
    Output : b→3  a→2  c→1  d→1  f→1  g→1

  APPROACH:
    • Split string into individual characters using str_split().
    • Use $freqMap[char] = count  (same HashMap pattern as Section 2.2).
    • Sort by value descending with arsort() (preserves key-value pairs).

  DRY RUN:
    str     = "abcbdabfg"
    chars   = ['a','b','c','b','d','a','b','f','g']
    freqMap = {}

    'a' → {a:1}
    'b' → {a:1,  b:1}
    'c' → {a:1,  b:1,  c:1}
    'b' → {a:1,  b:2,  c:1}
    'd' → {a:1,  b:2,  c:1,  d:1}
    'a' → {a:2,  b:2,  c:1,  d:1}
    'b' → {a:2,  b:3,  c:1,  d:1}
    'f' → {a:2,  b:3,  c:1,  d:1,  f:1}
    'g' → {a:2,  b:3,  c:1,  d:1,  f:1,  g:1}

    After arsort (descending by value):
      {b:3,  a:2,  c:1,  d:1,  f:1,  g:1}

    Output: b→3  a→2  c→1  d→1  f→1  g→1

  TIME  : O(n log n)  — O(n) for counting + O(k log k) for arsort (k=distinct chars)
  SPACE : O(n)        — freqMap holds at most n entries

---

  APPROACH:
    • Assume input contains only lowercase English letters ('a'–'z').
    • Use a fixed array of size 26 where index = ord(char) - ord('a').
    • This maps each letter to a guaranteed unique index with zero collisions:
        'a' → 97-97 = 0
        'b' → 98-97 = 1
        'c' → 99-97 = 2
        ...
        'z' → 122-97 = 25

    WHY ord() / chr()?
      ord()  converts a character to its ASCII integer value.
        e.g.  ord('b') = 98,  ord('a') = 97
      chr()  converts an ASCII integer back to its character.
        e.g.  chr(97+1) = chr(98) = 'b'

    SPACE advantage:
      Array is always exactly size 26 — independent of input length.
      Compare this to the HashMap approach which can grow up to n entries.

  DRY RUN:
    str  = "abcbdabfg"
    freq = [0, 0, 0, 0, 0, 0, 0, 0, 0 ... 0]   ← 26 zeros

    'a' → index = 97-97 = 0  →  freq[0]++ = 1
    'b' → index = 98-97 = 1  →  freq[1]++ = 1
    'c' → index = 99-97 = 2  →  freq[2]++ = 1
    'b' → index = 1          →  freq[1]++ = 2
    'd' → index = 100-97 = 3 →  freq[3]++ = 1
    'a' → index = 0          →  freq[0]++ = 2
    'b' → index = 1          →  freq[1]++ = 3
    'f' → index = 102-97 = 5 →  freq[5]++ = 1
    'g' → index = 103-97 = 6 →  freq[6]++ = 1

    freq = [2, 3, 1, 1, 0, 1, 1, 0, ..., 0]
           [a=2,b=3,c=1,d=1,e=0,f=1,g=1]

    To print: chr(ord('a') + index) reconstructs the character
      index=0 → chr(97+0) = 'a', freq=2
      index=1 → chr(97+1) = 'b', freq=3
      index=2 → chr(97+2) = 'c', freq=1
      ...

    NOTE: Iterates 0→25 so output is in alphabetical order, not frequency order.

  TIME  : O(n)  — one pass over string + one pass over 26-element array
  SPACE : O(1)  — fixed 26-element array (does NOT grow with input size)

Example:
Input: 'abcbdabfg'
Associative (sorted by frequency): b->3 a->2 c->1 d->1 f->1 g->1
ASCII array (alphabetical): a->2 b->3 c->1 d->1 f->1 g->1

Why This Pattern:
Two hashmap-style frequency-counting techniques for the same task: a general associative-array hashmap (works for any character set) versus a fixed 26-slot array keyed by ord(char)-ord('a') (O(1) space, zero collisions, only valid for lowercase a-z) - the same frequency-map-building block that anchors every 'frequency of X' hashing problem in this codebase.
*/

function charFrequencyAssociative(string $str): void
{
    $chars   = str_split($str);     // Break string into individual characters
    $freqMap = [];                  // key = character,  value = frequency count

    foreach ($chars as $chr) {
        if (isset($freqMap[$chr])) {
            $freqMap[$chr]++;       // Character seen before → increment
        } else {
            $freqMap[$chr] = 1;     // First occurrence → initialise to 1
        }
    }

    arsort($freqMap);               // Sort descending by frequency (keys preserved)

    $output = "";
    foreach ($freqMap as $chr => $freq) {
        $output .= "$chr→$freq  ";
    }
    echo "     Output: " . rtrim($output) . "\n";
}

function charFrequencyAscii(string $str): void
{
    $chars = str_split($str);
    $freq  = array_fill(0, 26, 0);     // Fixed array: index 0='a', 1='b', ..., 25='z'

    foreach ($chars as $chr) {
        $index = ord($chr) - ord('a'); // Map 'a'→0, 'b'→1, 'c'→2, ..., 'z'→25
        $freq[$index]++;
    }

    // Collect only characters that actually appeared in the string
    $result = [];
    for ($i = 0; $i < 26; $i++) {
        if ($freq[$i] > 0) {
            $char     = chr(ord('a') + $i);     // Reconstruct character from index
            $result[] = "$char→{$freq[$i]}";
        }
    }

    echo "     Output (alphabetical): " . implode("  ", $result) . "\n";
}


/*
|--------------------------------------------------------------------------
| Problem: Min / Max Frequency Element
| Source: DS/1. Basic/3. Hashing.php
| Pattern: Hashing
|--------------------------------------------------------------------------
*/

/*
Problem Description:
Find the element with the highest frequency AND the element with the lowest frequency in a given array.

Approach: build a frequency map in one pass, then scan the freqMap once tracking maxFreq/maxEle and minFreq/minEle.

Example:
Input: [10, 5, 10, 15, 10, 5]
Output: Highest -> 10 (3 times), Lowest -> 15 (1 time)

Why This Pattern:
A single-pass hashmap frequency count followed by a linear scan for the running min/max is the direct 'have I seen this before, how many times' hashing technique applied to a min/max-tracking variant.
*/

class FrequencyCounter
{
    public function findMinMax(array $arr): void
    {
        $n       = count($arr);
        $freqMap = [];

        // Step 1: Build frequency map
        foreach ($arr as $num) {
            if (isset($freqMap[$num])) {
                $freqMap[$num]++;
            } else {
                $freqMap[$num] = 1;
            }
        }

        $maxFreq = 0;       // Start low so any count (>=1) beats it
        $minFreq = $n;      // Start high so any count (<=n) beats it
        $maxEle  = null;
        $minEle  = null;

        // Step 2: Scan freqMap to find min and max frequency elements
        foreach ($freqMap as $element => $count) {
            if ($count > $maxFreq) {
                $maxFreq = $count;
                $maxEle  = $element;
            }
            if ($count < $minFreq) {
                $minFreq = $count;
                $minEle  = $element;
            }
        }

        echo "     Highest frequency element: $maxEle  (appears $maxFreq times)\n";
        echo "     Lowest  frequency element: $minEle  (appears $minFreq times)\n";
    }
}


/*
|--------------------------------------------------------------------------
| Problem: Count Pairs With Given Sum
| Source: DS/1. Basic/3. Hashing.php
| Pattern: Hashing
|--------------------------------------------------------------------------
*/

/*
Problem Description:
  PROBLEM:
    Count the total number of pairs (i, j) where i < j and
    nums[i] + nums[j] == target.

  EXAMPLE:
    Input  : nums=[1, 5, 7, -1, 5],  target=6
    Output : 3    → pairs: (1,5),  (7,-1),  (1, second 5)

  APPROACH  O(n):
    • For each num, complement = target - num.
    • Count how many times complement appeared BEFORE current num → those are all
      valid pairs with the current num.
    • Add current num to freqMap AFTER checking, to prevent self-pairing.

  DRY RUN:
    nums    = [1, 5, 7, -1, 5],  target=6
    freqMap = {},  pairs=0

    num=1   | complement=6-1=5   | 5 not in freqMap         → pairs=0
              store freqMap = {1:1}

    num=5   | complement=6-5=1   | 1 in freqMap, count=1    → pairs += 1 = 1
              store freqMap = {1:1, 5:1}

    num=7   | complement=6-7=-1  | -1 not in freqMap        → pairs=1
              store freqMap = {1:1, 5:1, 7:1}

    num=-1  | complement=6-(-1)=7| 7 in freqMap, count=1    → pairs += 1 = 2
              store freqMap = {1:1, 5:1, 7:1, -1:1}

    num=5   | complement=6-5=1   | 1 in freqMap, count=1    → pairs += 1 = 3
              store freqMap = {1:1, 5:2, 7:1, -1:1}

    → Total pairs = 3  ✓

  TIME  : O(n)
  SPACE : O(n)

Example:
Input: nums=[1, 5, 7, -1, 5], target=6
Output: 3 pairs -> (1,5), (7,-1), (1, second 5)

Why This Pattern:
A hashmap of value to frequency lets you look up how many times a pair's complement has already been seen in O(1) as you scan once - the same complement-lookup idea as Two Sum, generalized from 'find one pair' to 'count all pairs'.
*/

function countPairsWithSum(array $nums, int $target): int
{
    $freqMap = [];      // {value → how many times seen so far}
    $pairs   = 0;

    foreach ($nums as $num) {
        $complement = $target - $num;

        if (isset($freqMap[$complement])) {
            $pairs += $freqMap[$complement];    // All past occurrences of complement pair with $num
        }

        // Store AFTER checking to avoid pairing an element with itself
        if (isset($freqMap[$num])) {
            $freqMap[$num]++;
        } else {
            $freqMap[$num] = 1;
        }
    }

    return $pairs;
}


/*
|--------------------------------------------------------------------------
| Problem: Subarray With Sum = 0 - Existence Check and Longest Length
| Source: DS/1. Basic/3. Hashing.php
| Pattern: Hashing
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
A running prefix sum stored in a hashset/hashmap turns 'does a zero-sum subarray exist' and 'how long is the longest one' into an O(1)-per-step 'have I seen this prefix sum before' lookup instead of an O(n^2) scan - the special k=0 case of the same prefix-sum-hashing family as the general-k versions already covered elsewhere in this pattern.
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


/*
|--------------------------------------------------------------------------
| Problem: Group Anagrams (LC 49)
| Source: DS/1. Basic/3. Hashing.php
| Pattern: Hashing
|--------------------------------------------------------------------------
*/

/*
Problem Description:
  PROBLEM:
    Given an array of strings, group those that are anagrams of each other.
    Two strings are anagrams if they contain the same characters (any order).

  EXAMPLE:
    Input  : ["eat","tea","tan","ate","nat","bat"]
    Output : [["eat","tea","ate"], ["tan","nat"], ["bat"]]

  KEY INSIGHT:
    Two strings are anagrams  ↔  their sorted character versions are identical.
      "eat" sorted → "aet"
      "tea" sorted → "aet"   ← same key!  →  they belong to the same group

    Use the sorted string as the HashMap key to group all anagrams together.

  DRY RUN:
    words  = ["eat","tea","tan","ate","nat","bat"]
    groups = {}

    "eat" → sort → "aet"  →  groups = {"aet":["eat"]}
    "tea" → sort → "aet"  →  groups = {"aet":["eat","tea"]}
    "tan" → sort → "ant"  →  groups = {"aet":["eat","tea"], "ant":["tan"]}
    "ate" → sort → "aet"  →  groups = {"aet":["eat","tea","ate"], "ant":["tan"]}
    "nat" → sort → "ant"  →  groups = {"aet":["eat","tea","ate"], "ant":["tan","nat"]}
    "bat" → sort → "abt"  →  groups = {..., "abt":["bat"]}

    Extract values → [["eat","tea","ate"], ["tan","nat"], ["bat"]]  ✓

  TIME  : O(n · m log m)   n = number of words,  m = maximum word length
  SPACE : O(n · m)         storing all characters in the map

Example:
Input: ["eat","tea","tan","ate","nat","bat"]
Output: [["eat","tea","ate"], ["tan","nat"], ["bat"]]

Why This Pattern:
The sorted-character version of each word is used as a hashmap key so every anagram of the same word collides into the same bucket - the canonical 'derive a key that collapses equivalent inputs' hashing technique, directly extending the same-technique Valid Anagram (LC 242) already covered in this pattern.
*/

function groupAnagrams(array $words): array
{
    $groups = [];       // {sorted_word → [original anagram words]}

    foreach ($words as $word) {
        $chars = str_split($word);
        sort($chars);                           // Sort characters alphabetically
        $key = implode('', $chars);             // Sorted string = the group's key

        if (isset($groups[$key])) {
            $groups[$key][] = $word;            // Add to existing anagram group
        } else {
            $groups[$key] = [$word];            // Start a new anagram group
        }
    }

    return array_values($groups);               // Return groups as a simple indexed array
}

