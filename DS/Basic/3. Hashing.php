<?php

/*
================================================================================
  HASHING — Complete DSA Revision Notes
  Source  : Striver's A2Z DSA Course (TakeUForward)
  Language: PHP

  HOW TO USE THIS FILE:
    • Read each SECTION in order — concepts build on each other
    • "DRY RUN" blocks trace exact variable values step by step
    • Every problem has: Problem Statement → Approach → Dry Run → Code
    • Time & Space complexity noted for every problem

  INDEX:
  ──────────────────────────────────────────────────────────────────────────────
  SECTION 1  : Core Concepts — What is Hashing, Hash Function, Collision,
               Chaining, Open Addressing, Big-O Complexity

  SECTION 2  : Integer Frequency Count
               2.1  Brute Force — Nested Loop            O(n²) time  O(n) space
               2.2  Optimal    — HashMap                 O(n)  time  O(n) space
               2.3  Divide & Collide — Manual Bucketing  O(n)  time  O(n) space

  SECTION 3  : Character / String Frequency Count
               3.1  Associative Array Approach           O(n log n)  O(n) space
               3.2  ASCII Index Array Approach           O(n)  time  O(1) space

  SECTION 4  : Min / Max Frequency Element               O(n)  time  O(n) space

  SECTION 5  : Classic HashMap Interview Problems (ascending difficulty)
               5.1  Two Sum                              O(n)   [LeetCode #1]
               5.2  Count Pairs With Given Sum           O(n)
               5.3  Check: Does Subarray With Sum=0 Exist? O(n)
               5.4  Longest Subarray With Sum = 0        O(n)
               5.5  Count Subarrays With Sum = K         O(n)   [LeetCode #560]
               5.6  Longest Subarray With Sum = K        O(n)
               5.7  Group Anagrams                       O(n·m·log m) [LC #49]
               5.8  Longest Consecutive Sequence         O(n)   [LeetCode #128]
               5.9  Majority Element  (> n/2 times)      O(n)   [LeetCode #169]
               5.10 Majority Elements (> n/3 times)      O(n)   [LeetCode #229]
               5.11 Frequency of Most Frequent Element   O(n log n) [LC #1838]
  ──────────────────────────────────────────────────────────────────────────────
================================================================================
*/

echo str_repeat("=", 80) . "\n";
echo "  HASHING — DSA Revision Notes  (Striver A2Z)\n";
echo str_repeat("=", 80) . "\n\n";


// ============================================================================
//  SECTION 1  :  CORE CONCEPTS — What is Hashing?
// ============================================================================

/*
  ┌───────────────────────────────────────────────────────────────────────────┐
  │                         WHAT IS HASHING?                                 │
  ├───────────────────────────────────────────────────────────────────────────┤
  │  Hashing  = mapping data (keys) → indices using a HASH FUNCTION          │
  │  Goal     = achieve O(1) average time for insert / lookup / delete       │
  │                                                                           │
  │  Real-life analogy:                                                       │
  │    A library assigns a shelf-number to each book by category.             │
  │    Instead of scanning every shelf, you go directly to the right one.    │
  └───────────────────────────────────────────────────────────────────────────┘

  HASH FUNCTION  h(key) → index
  ─────────────────────────────
  Converts any key (number / string) into a valid array index (0 to N-1).

  Common Methods:
    1. Division Method   : h(k) = k % tableSize
       e.g.  h(25) with tableSize=10  →  25 % 10 = 5  →  slot 5

    2. Folding Method    : Split key into equal chunks, add them, then mod
       e.g.  key=123456, parts=123+456=579,  579 % 100 = 79  →  slot 79

    3. Mid-Square Method : Square the key, extract middle digits
       e.g.  key=25, 25²=625, middle digit = '2'  →  slot 2

  ─────────────────────────────────────────────────────────────────────────────
  COLLISION
  ─────────
  When two different keys hash to the same index:
    h(10) = 0   and   h(20) = 0   →  both want slot 0  →  COLLISION!

  COLLISION RESOLUTION:
  ─────────────────────
    1. Separate Chaining:
       Each slot holds a linked list of all colliding keys.
       Slot 0  →  [10]  →  [20]  →  null

    2. Open Addressing — Linear Probing:
       If h(k) is occupied, try h(k)+1, h(k)+2 … until an empty slot is found.

    3. Open Addressing — Quadratic Probing:
       Try h(k), h(k)+1², h(k)+2², …

    4. Double Hashing:
       Use a second hash function as the step size.

  ─────────────────────────────────────────────────────────────────────────────
  TIME COMPLEXITY
  ───────────────
    Operation  │  Average  │  Worst Case (all keys collide → degrades to list)
    ───────────┼───────────┼───────────────────────────────────────────────────
    Insert     │   O(1)    │   O(n)
    Lookup     │   O(1)    │   O(n)
    Delete     │   O(1)    │   O(n)

  TYPES OF MAPS (important for interviews):
  ──────────────────────────────────────────
    • Unordered Map (PHP assoc. array $map=[]):  O(1) avg insert/lookup.
    • Ordered Map   (ksort + PHP array)       :  O(log n) insert/lookup, keys sorted.
    • Set           (PHP array used as a set) :  Store keys only, no value needed.

  PHP NOTES:
  ──────────
    • PHP associative arrays  ARE  hash tables internally.
    • isset($map[$key])     →  O(1) average lookup
    • $map[$key] = val      →  O(1) average insert
    • unset($map[$key])     →  O(1) average delete
    • foreach ($map as $k => $v)  →  O(n) iteration
*/


// ============================================================================
//  SECTION 2  :  FREQUENCY COUNT — INTEGER ARRAYS
// ============================================================================

echo "──── SECTION 2: Integer Frequency Count ────\n\n";

// ─────────────────────────────────────────────────────────────────────────────
//  2.1  BRUTE FORCE APPROACH  — Nested Loop
// ─────────────────────────────────────────────────────────────────────────────
/*
  PROBLEM:
    Count and print the frequency of each unique element in arr[].

  EXAMPLE:
    Input  : [10, 5, 10, 15, 10, 5]
    Output : 10 → 3,   5 → 2,   15 → 1

  APPROACH (Brute Force):
    • For each element arr[i], scan the rest of the array (arr[i+1 .. n-1])
      counting duplicates.
    • Use a visited[] map (keyed by element value) so we skip elements whose
      frequency was already printed in an earlier outer-loop iteration.

  DRY RUN:
    arr     = [10, 5, 10, 15, 10, 5]
    visited = {}          ← key=element, value='T' when that element is done

    i=0 | arr[0]=10  | visited[10] absent  →  inner loop j=1..5
          j=2: arr[2]=10 ✓  j=4: arr[4]=10 ✓  →  freq=3
          visited[10]='T'  →  print "10 → 3"

    i=1 | arr[1]=5   | visited[5] absent   →  inner loop j=2..5
          j=5: arr[5]=5 ✓  →  freq=2
          visited[5]='T'   →  print "5 → 2"

    i=2 | arr[2]=10  | visited[10]='T'     →  SKIP

    i=3 | arr[3]=15  | visited[15] absent  →  inner loop j=4..5  →  no match
          freq=1  →  print "15 → 1"

    i=4 | arr[4]=10  | visited[10]='T'     →  SKIP
    i=5 | arr[5]=5   | visited[5]='T'      →  SKIP

  TIME  : O(n²)  — each element may trigger a full inner scan
  SPACE : O(n)   — visited map holds at most n distinct element keys
*/
echo "2.1  Brute Force — O(n²):\n";
echo "     Input: [10, 5, 10, 15, 10, 5]\n";

$arr     = [10, 5, 10, 15, 10, 5];
$n       = count($arr);
$visited = [];              // {element => 'T'} once that element has been printed

for ($i = 0; $i < $n; $i++) {

    // Element was already counted in a previous outer iteration → skip
    if (isset($visited[$arr[$i]]) && $visited[$arr[$i]] === 'T') {
        continue;
    }

    $freq = 1;  // Count the element at arr[i] itself

    for ($j = $i + 1; $j < $n; $j++) {
        $visited[$arr[$i]] = 'T';       // Mark as being processed now
        if ($arr[$i] === $arr[$j]) {
            $freq++;                    // Found another occurrence
        }
    }

    echo "     {$arr[$i]} → $freq\n";
}

echo "\n";


// ─────────────────────────────────────────────────────────────────────────────
//  2.2  OPTIMAL APPROACH  — HashMap (Associative Array)
// ─────────────────────────────────────────────────────────────────────────────
/*
  APPROACH (Optimal — Standard Hashing Pattern):
    • Single pass through arr[].
    • $freqMap[element] = count (increment if key exists, set to 1 otherwise).
    • This is the core hashing technique used in almost every frequency problem.

  DRY RUN:
    arr     = [10, 5, 10, 15, 10, 5]
    freqMap = {}

    num=10 → freqMap[10] absent  →  freqMap = {10:1}
    num=5  → freqMap[5]  absent  →  freqMap = {10:1, 5:1}
    num=10 → freqMap[10] exists  →  freqMap = {10:2, 5:1}
    num=15 → freqMap[15] absent  →  freqMap = {10:2, 5:1, 15:1}
    num=10 → freqMap[10] exists  →  freqMap = {10:3, 5:1, 15:1}
    num=5  → freqMap[5]  exists  →  freqMap = {10:3, 5:2, 15:1}

    Print: 10→3, 5→2, 15→1

  TIME  : O(n)   — single pass
  SPACE : O(n)   — freqMap with at most n distinct keys
*/
echo "2.2  Optimal (HashMap) — O(n):\n";
echo "     Input: [10, 5, 10, 15, 10, 5]\n";

$arr     = [10, 5, 10, 15, 10, 5];
$freqMap = [];      // key = element,  value = frequency count

foreach ($arr as $num) {
    if (isset($freqMap[$num])) {
        $freqMap[$num]++;       // Seen before → increment count
    } else {
        $freqMap[$num] = 1;     // First occurrence → initialise to 1
    }
}

foreach ($freqMap as $element => $freq) {
    echo "     $element → $freq\n";
}

echo "\n";


// ─────────────────────────────────────────────────────────────────────────────
//  2.3  DIVIDE & COLLIDE METHOD  — Manual Bucket Hashing (Educational Demo)
// ─────────────────────────────────────────────────────────────────────────────
/*
  PURPOSE:
    Demonstrates how a hash table physically groups values into buckets using
    the last digit (units place) as the hash/bucket index.
    This is a teaching aid — in real code use the HashMap approach (2.2).

  HASH FUNCTION used here:  slot = num % 10  (last digit = bucket index)

  APPROACH:
    • slot = num % 10               →  bucket index  (0–9)
    • key  = num - slot             →  stored inside that bucket to identify the exact number
    • buckets[slot][key]++          →  count in that bucket cell
    • Reconstruction: actual_num = slot + key

  DRY RUN:
    arr = [1, 5, 1, 10, 10, 10, 5, 10, 8, 5, 10, 15]

    num=1  → slot=1%10=1,  key=1-1=0   → buckets[1][0]  = 1
    num=5  → slot=5%10=5,  key=5-5=0   → buckets[5][0]  = 1
    num=1  → slot=1,       key=0        → buckets[1][0]  = 2   ← collision, same bucket, same key
    num=10 → slot=10%10=0, key=10-0=10  → buckets[0][10] = 1
    num=10 → slot=0,       key=10       → buckets[0][10] = 2
    num=10 → slot=0,       key=10       → buckets[0][10] = 3
    num=5  → slot=5,       key=0        → buckets[5][0]  = 2
    num=10 → slot=0,       key=10       → buckets[0][10] = 4
    num=8  → slot=8%10=8,  key=8-8=0   → buckets[8][0]  = 1
    num=5  → slot=5,       key=0        → buckets[5][0]  = 3
    num=10 → slot=0,       key=10       → buckets[0][10] = 5
    num=15 → slot=15%10=5, key=15-5=10  → buckets[5][10] = 1   ← 5 and 15 share slot 5, different keys

    Final buckets:
      slot=0 → {10:5}        →  Element 10 → 5
      slot=1 → {0:2}         →  Element  1 → 2
      slot=5 → {0:3, 10:1}   →  Element  5 → 3,  Element 15 → 1
      slot=8 → {0:1}         →  Element  8 → 1

    Print: actual_num = slot + key
      buckets[0][10] → 0+10=10, freq=5
      buckets[1][0]  → 1+0 =1,  freq=2
      buckets[5][0]  → 5+0 =5,  freq=3
      buckets[5][10] → 5+10=15, freq=1
      buckets[8][0]  → 8+0 =8,  freq=1

  TIME  : O(n)
  SPACE : O(n)
*/
echo "2.3  Divide & Collide — Manual Bucket Hashing (Demo):\n";
echo "     Input: [1, 5, 1, 10, 10, 10, 5, 10, 8, 5, 10, 15]\n";

$arr     = [1, 5, 1, 10, 10, 10, 5, 10, 8, 5, 10, 15];
$buckets = [];      // $buckets[slot][key] = frequency

foreach ($arr as $num) {
    $slot = $num % 10;          // Hash function: last digit = bucket index
    $key  = $num - $slot;       // Remainder identifies the exact number within bucket

    if (isset($buckets[$slot][$key])) {
        $buckets[$slot][$key]++;
    } else {
        $buckets[$slot][$key] = 1;
    }
}

foreach ($buckets as $slot => $innerBucket) {
    foreach ($innerBucket as $key => $freq) {
        $actualNum = $key + $slot;      // Reconstruct original number from slot + key
        echo "     Element: $actualNum → Frequency: $freq\n";
    }
}

echo "\n";


// ============================================================================
//  SECTION 3  :  FREQUENCY COUNT — STRINGS / CHARACTERS
// ============================================================================

echo "──── SECTION 3: Character / String Frequency Count ────\n\n";

// ─────────────────────────────────────────────────────────────────────────────
//  3.1  ASSOCIATIVE ARRAY APPROACH  (HashMap, same pattern as Section 2.2)
// ─────────────────────────────────────────────────────────────────────────────
/*
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

echo "3.1  Associative Array — Character Frequency:\n";
echo "     Input: 'abcbdabfg'\n";
charFrequencyAssociative('abcbdabfg');
echo "\n";


// ─────────────────────────────────────────────────────────────────────────────
//  3.2  ASCII INDEX ARRAY APPROACH  (Fixed-size array of 26 for a-z)
// ─────────────────────────────────────────────────────────────────────────────
/*
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
*/
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

echo "3.2  ASCII Index Array — Character Frequency:\n";
echo "     Input: 'abcbdabfg'\n";
charFrequencyAscii('abcbdabfg');
echo "\n";


// ============================================================================
//  SECTION 4  :  MIN / MAX FREQUENCY ELEMENT
// ============================================================================

echo "──── SECTION 4: Min / Max Frequency Element ────\n\n";

/*
  PROBLEM:
    Find the element with the highest frequency AND the element with the
    lowest frequency in a given array.

  EXAMPLE:
    Input  : [10, 5, 10, 15, 10, 5]
    Output : Highest → 10 (3 times),  Lowest → 15 (1 time)

  APPROACH:
    Step 1 — Build a frequency map in one pass (same as Section 2.2).
    Step 2 — Scan the freqMap once tracking:
              maxFreq / maxEle  →  element & count with highest frequency
              minFreq / minEle  →  element & count with lowest  frequency

  DRY RUN:
    freqMap = {10:3, 5:2, 15:1}   ← after Step 1
    maxFreq = 0  (start at 0  so any real freq ≥ 1 is greater)
    minFreq = 6  (start at n=6 so any real freq ≤ n is smaller)

    element=10, count=3 → 3 > 0 → maxFreq=3, maxEle=10
                           3 < 6 → minFreq=3, minEle=10

    element=5,  count=2 → 2 < 3 (no max update)
                           2 < 3 → minFreq=2, minEle=5

    element=15, count=1 → 1 < 3 (no max update)
                           1 < 2 → minFreq=1, minEle=15

    → Highest: 10  /  Lowest: 15  ✓

  TIME  : O(n)   — two linear passes (one for counting, one for min/max)
  SPACE : O(n)   — freqMap
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

        $maxFreq = 0;       // Start low so any count (≥1) beats it
        $minFreq = $n;      // Start high so any count (≤n) beats it
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

echo "4.1  Min / Max Frequency Element:\n";
echo "     Input: [10, 5, 10, 15, 10, 5]\n";
$fc = new FrequencyCounter();
$fc->findMinMax([10, 5, 10, 15, 10, 5]);
echo "\n";


// ============================================================================
//  SECTION 5  :  CLASSIC HASHMAP INTERVIEW PROBLEMS
// ============================================================================

echo "──── SECTION 5: Classic HashMap Interview Problems ────\n\n";


// ─────────────────────────────────────────────────────────────────────────────
//  5.1  TWO SUM   [LeetCode #1]
// ─────────────────────────────────────────────────────────────────────────────
/*
  PROBLEM:
    Given an array nums[] and an integer target, return the indices [i, j]
    such that nums[i] + nums[j] == target.  Exactly one solution is guaranteed.

  EXAMPLE:
    Input  : nums=[2, 7, 11, 15],  target=9
    Output : [0, 1]    (nums[0]+nums[1] = 2+7 = 9)

  BRUTE FORCE  O(n²):
    Nested loops — check every pair (i, j).

  OPTIMAL  O(n)  — HashMap:
    KEY INSIGHT:
      For each nums[i], the value we NEED to complete the pair is:
        complement = target - nums[i]
      If complement was already seen → we found the answer!

    Store {value → index} as we iterate so we can look up the complement.

  DRY RUN:
    nums   = [2, 7, 11, 15],  target=9
    seen   = {}                ← {value: index}

    i=0 | num=2  | complement = 9-2 = 7  | 7 not in seen  → store seen = {2:0}
    i=1 | num=7  | complement = 9-7 = 2  | 2 IS in seen at index 0!
          → return [seen[2], 1] = [0, 1]  ✓

  TIME  : O(n)   — single pass
  SPACE : O(n)   — 'seen' map stores up to n entries
*/
function twoSum(array $nums, int $target): array
{
    $seen = [];     // {value → index} of elements seen so far

    foreach ($nums as $i => $num) {
        $complement = $target - $num;   // What value do we need to pair with $num?

        if (isset($seen[$complement])) {
            return [$seen[$complement], $i];    // Complement was seen earlier → answer found
        }

        $seen[$num] = $i;   // Store current value with its index for future lookups
    }

    return [];  // Problem guarantees exactly one solution, so this line is never reached
}

echo "5.1  Two Sum [LC #1]:\n";
echo "     Input: [2, 7, 11, 15], target=9\n";
$result = twoSum([2, 7, 11, 15], 9);
echo "     Output: [" . implode(", ", $result) . "]\n\n";


// ─────────────────────────────────────────────────────────────────────────────
//  5.2  COUNT PAIRS WITH GIVEN SUM
// ─────────────────────────────────────────────────────────────────────────────
/*
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

echo "5.2  Count Pairs With Given Sum:\n";
echo "     Input: [1, 5, 7, -1, 5], target=6\n";
echo "     Output: " . countPairsWithSum([1, 5, 7, -1, 5], 6) . " pairs\n\n";


// ─────────────────────────────────────────────────────────────────────────────
//  5.3  DOES A SUBARRAY WITH SUM = 0 EXIST?
// ─────────────────────────────────────────────────────────────────────────────
/*
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

echo "5.3  Subarray With Sum = 0 (Exists?):\n";
echo "     Input: [4, 2, -3, 1, 6]\n";
echo "     Output: " . (hasZeroSumSubarray([4, 2, -3, 1, 6]) ? "true" : "false") . "\n\n";


// ─────────────────────────────────────────────────────────────────────────────
//  5.4  LONGEST SUBARRAY WITH SUM = 0
// ─────────────────────────────────────────────────────────────────────────────
/*
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
*/
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

echo "5.4  Longest Subarray With Sum = 0:\n";
echo "     Input: [15, -2, 2, -8, 1, 7, 10, 23]\n";
echo "     Output: Length = " . longestSubarrayWithZeroSum([15, -2, 2, -8, 1, 7, 10, 23]) . "\n\n";


// ─────────────────────────────────────────────────────────────────────────────
//  5.5  COUNT SUBARRAYS WITH SUM = K   [LeetCode #560]
// ─────────────────────────────────────────────────────────────────────────────
/*
  PROBLEM:
    Count the total number of contiguous subarrays whose sum equals k.

  EXAMPLE:
    Input  : nums=[1, 1, 1],  k=2
    Output : 2    → subarrays [1,1] at indices 0..1  and  1..2

  KEY INSIGHT — Prefix Sum + HashMap:
    subarray(i+1, j)  =  prefixSum[j] - prefixSum[i]
    We want:  prefixSum[j] - prefixSum[i] = k
    →         prefixSum[i] = prefixSum[j] - k   ("need")

    For each index j, count how many past prefix sums equal (currentPrefixSum - k).
    Store {prefixSum → how many times it has occurred} in prefMap.
    Pre-insert prefMap[0]=1 for subarrays that start at index 0.

  DRY RUN:
    nums    = [1, 1, 1],  k=2
    prefMap = {0:1}       ← prefix sum 0 appeared once before the array
    prefixSum=0, count=0

    i=0 | num=1 → pS=1  | need=1-2=-1 | -1 not in prefMap     → count=0
                            store prefMap={0:1, 1:1}

    i=1 | num=1 → pS=2  | need=2-2=0  | 0 in prefMap (val=1)  → count += 1 = 1
                            store prefMap={0:1, 1:1, 2:1}

    i=2 | num=1 → pS=3  | need=3-2=1  | 1 in prefMap (val=1)  → count += 1 = 2
                            store prefMap={0:1, 1:1, 2:1, 3:1}

    → count = 2  ✓

  TIME  : O(n)
  SPACE : O(n)
*/
function countSubarraysWithSumK(array $nums, int $k): int
{
    $prefMap          = [];
    $prefMap[0]       = 1;      // Empty prefix (before index 0) has sum 0 — count it once
    $prefixSum        = 0;
    $count            = 0;

    foreach ($nums as $num) {
        $prefixSum += $num;
        $need       = $prefixSum - $k;     // Previous prefix sum we need to form sum=k subarray

        if (isset($prefMap[$need])) {
            $count += $prefMap[$need];     // Add all past occurrences of 'need'
        }

        // Update frequency of current prefix sum
        if (isset($prefMap[$prefixSum])) {
            $prefMap[$prefixSum]++;
        } else {
            $prefMap[$prefixSum] = 1;
        }
    }

    return $count;
}

echo "5.5  Count Subarrays With Sum = K [LC #560]:\n";
echo "     Input: [1, 1, 1], k=2\n";
echo "     Output: " . countSubarraysWithSumK([1, 1, 1], 2) . " subarrays\n\n";


// ─────────────────────────────────────────────────────────────────────────────
//  5.6  LONGEST SUBARRAY WITH SUM = K
// ─────────────────────────────────────────────────────────────────────────────
/*
  PROBLEM:
    Find the LENGTH of the longest contiguous subarray with sum exactly = k.
    Works for arrays containing positive, negative, and zero elements.

  EXAMPLE:
    Input  : arr=[1, -1, 5, -2, 3],  k=3
    Output : 4   (subarray [1,-1,5,-2] at indices 0..3, sum=1-1+5-2=3)

  KEY INSIGHT — Same idea as 5.4 but target is k (not 0):
    We want:  prefixSum[j] - prefixSum[i] = k
    →  prefixSum[i] = prefixSum[j] - k     ("need")
    Length = j - firstIndex[need].
    Store only the FIRST occurrence of each prefix sum.

  DRY RUN:
    arr        = [1, -1, 5, -2, 3],  k=3
    firstIndex = {0:-1},  prefixSum=0,  maxLen=0

    i=0 | num=1  → pS=1  | need=1-3=-2  | -2 not in map  → firstIndex[1]=0
    i=1 | num=-1 → pS=0  | need=0-3=-3  | -3 not in map
                            pS=0 already in map at -1 → do NOT overwrite
    i=2 | num=5  → pS=5  | need=5-3=2   | 2 not in map   → firstIndex[5]=2
    i=3 | num=-2 → pS=3  | need=3-3=0   | 0 IS in map at -1
                            len = 3-(-1) = 4  →  maxLen=4  ✓
                            firstIndex[3]=3
    i=4 | num=3  → pS=6  | need=6-3=3   | 3 IS in map at 3
                            len = 4-3 = 1   →  maxLen stays 4

    → maxLen = 4   subarray [1,-1,5,-2] = 3  ✓

  TIME  : O(n)
  SPACE : O(n)
*/
function longestSubarrayWithSumK(array $arr, int $k): int
{
    $firstIndex    = [];
    $firstIndex[0] = -1;       // Prefix sum 0 before the array = index -1
    $prefixSum     = 0;
    $maxLen        = 0;

    foreach ($arr as $i => $num) {
        $prefixSum += $num;
        $need       = $prefixSum - $k;

        if (isset($firstIndex[$need])) {
            $len    = $i - $firstIndex[$need];
            $maxLen = max($maxLen, $len);
        }

        // Store only the FIRST occurrence of this prefix sum
        if (!isset($firstIndex[$prefixSum])) {
            $firstIndex[$prefixSum] = $i;
        }
    }

    return $maxLen;
}

echo "5.6  Longest Subarray With Sum = K:\n";
echo "     Input: [1, -1, 5, -2, 3], k=3\n";
echo "     Output: Length = " . longestSubarrayWithSumK([1, -1, 5, -2, 3], 3) . "\n\n";


// ─────────────────────────────────────────────────────────────────────────────
//  5.7  GROUP ANAGRAMS   [LeetCode #49]
// ─────────────────────────────────────────────────────────────────────────────
/*
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

echo "5.7  Group Anagrams [LC #49]:\n";
echo "     Input: ['eat','tea','tan','ate','nat','bat']\n";
$groups = groupAnagrams(["eat", "tea", "tan", "ate", "nat", "bat"]);
foreach ($groups as $group) {
    echo "     [" . implode(", ", $group) . "]\n";
}
echo "\n";


// ─────────────────────────────────────────────────────────────────────────────
//  5.8  LONGEST CONSECUTIVE SEQUENCE   [LeetCode #128]
// ─────────────────────────────────────────────────────────────────────────────
/*
  PROBLEM:
    Find the length of the longest consecutive integer sequence.
    Elements can be in any order in the original array.

  EXAMPLE:
    Input  : [100, 4, 200, 1, 3, 2]
    Output : 4    (sequence 1 → 2 → 3 → 4)

  CONSTRAINT: Must run in O(n).

  KEY INSIGHT — HashSet:
    • Load all elements into a HashSet for O(1) lookup.
    • For each element x, ONLY start counting a sequence if (x - 1) is NOT
      in the set — this means x is the START of a fresh sequence.
    • Then extend: check x+1, x+2, x+3, … in O(1) each.

    WHY skip if (x-1) exists?
      If (x-1) is in the set, the sequence starting at (x-1) will already
      count x when it gets processed — no need to recount from x.
      This is what keeps the total time O(n).

  DRY RUN:
    nums   = [100, 4, 200, 1, 3, 2]
    numSet = {100:true, 4:true, 200:true, 1:true, 3:true, 2:true}

    x=100 | (100-1)=99 NOT in set  →  start sequence from 100
              101 in set? No  →  streak=1  →  maxLen=1

    x=4   | (4-1)=3 IS in set      →  skip (sequence starting at 3 will count 4)

    x=200 | (200-1)=199 NOT in set  →  start from 200
              201 in set? No  →  streak=1  →  maxLen=1

    x=1   | (1-1)=0 NOT in set     →  start sequence from 1
              2 in set? ✓ streak=2
              3 in set? ✓ streak=3
              4 in set? ✓ streak=4
              5 in set? No  →  streak=4  →  maxLen=4  ✓

    x=3   | (3-1)=2 IS in set      →  skip
    x=2   | (2-1)=1 IS in set      →  skip

    → maxLen = 4  ✓

  TIME  : O(n)  — every element is visited at most twice (as start + as member)
  SPACE : O(n)  — numSet
*/
function longestConsecutiveSequence(array $nums): int
{
    if (empty($nums)) {
        return 0;
    }

    $numSet = [];
    foreach ($nums as $num) {
        $numSet[$num] = true;       // Build O(1)-lookup HashSet
    }

    $maxLen = 0;

    foreach ($numSet as $num => $_) {
        // Only start counting if $num is the BEGINNING of a sequence
        // (no element just below it exists in the set)
        if (!isset($numSet[$num - 1])) {
            $current = $num;
            $streak  = 1;

            while (isset($numSet[$current + 1])) {
                $current++;
                $streak++;
            }

            $maxLen = max($maxLen, $streak);
        }
    }

    return $maxLen;
}

echo "5.8  Longest Consecutive Sequence [LC #128]:\n";
echo "     Input: [100, 4, 200, 1, 3, 2]\n";
echo "     Output: " . longestConsecutiveSequence([100, 4, 200, 1, 3, 2]) . "\n\n";


// ─────────────────────────────────────────────────────────────────────────────
//  5.9  MAJORITY ELEMENT  (> n/2 times)   [LeetCode #169]
// ─────────────────────────────────────────────────────────────────────────────
/*
  PROBLEM:
    Find the element that appears MORE THAN ⌊n/2⌋ times.
    A majority element is always guaranteed to exist.

  EXAMPLE:
    Input  : [2, 2, 1, 1, 1, 2, 2]
    Output : 2

  APPROACH 1  — HashMap  O(n) time, O(n) space:
    Count frequencies; return element where freq > n/2.

  APPROACH 2  — Boyer-Moore Voting Algorithm  O(n) time, O(1) space:
    KEY INSIGHT:
      The majority element has more than n/2 occurrences.
      If every majority element is "cancelled" by one non-majority element,
      majority elements still remain — because there are more of them.

    STEPS:
      • Start with candidate = first element, votes = 1.
      • For each next element:
          Same as candidate → votes++
          Different         → votes--
          votes becomes 0   → reset: candidate = current element, votes = 1
      • Final candidate = majority element.

  DRY RUN (Boyer-Moore):
    arr = [2, 2, 1, 1, 1, 2, 2],  n=7,  majority = element with count > 3.5
    2 appears 4 times → answer = 2

    Start: candidate=2, votes=1

    num=2 → same as candidate  → votes=2
    num=1 → different          → votes=1
    num=1 → different          → votes=0
              votes=0 → reset: candidate=1, votes=1
    num=1 → same as candidate  → votes=2
    num=2 → different          → votes=1
    num=2 → different          → votes=0
              votes=0 → reset: candidate=2, votes=1

    Final candidate = 2  ✓   (2 appears 4 > 7/2=3.5 times)

  TIME  : O(n)   O(1) space (Boyer-Moore variant)
*/
function majorityElementHalf(array $nums): int
{
    $candidate = $nums[0];  // Start with first element as candidate
    $votes     = 1;

    for ($i = 1; $i < count($nums); $i++) {
        if ($votes === 0) {
            $candidate = $nums[$i];     // Previous candidate eliminated → take current
            $votes     = 1;
        } elseif ($nums[$i] === $candidate) {
            $votes++;                   // Supports the candidate → gain vote
        } else {
            $votes--;                   // Opposes the candidate → lose vote
        }
    }

    return $candidate;  // Guaranteed to be majority element
}

echo "5.9  Majority Element > n/2 [LC #169] — Boyer-Moore Voting:\n";
echo "     Input: [2, 2, 1, 1, 1, 2, 2]\n";
echo "     Output: " . majorityElementHalf([2, 2, 1, 1, 1, 2, 2]) . "\n\n";


// ─────────────────────────────────────────────────────────────────────────────
//  5.10  MAJORITY ELEMENTS  (> n/3 times)   [LeetCode #229]
// ─────────────────────────────────────────────────────────────────────────────
/*
  PROBLEM:
    Find ALL elements that appear MORE THAN ⌊n/3⌋ times.
    There can be AT MOST 2 such elements (since 3 × (⌊n/3⌋+1) > n).

  EXAMPLE:
    Input  : [1, 1, 1, 3, 3, 2, 2, 2]
    Output : [1, 2]   (1 appears 3x, 2 appears 3x;  n=8, n/3≈2.67, need >2.67)

  APPROACH — Extended Boyer-Moore Voting (2 Candidates):
    Maintain TWO candidates (cand1, cand2) and their vote counts.
    After scanning, verify each candidate by counting its actual frequency.

    STEPS:
      For each element:
        • If  == cand1  →  votes1++
        • If  == cand2  →  votes2++
        • If votes1 == 0  →  cand1 = element, votes1 = 1
        • If votes2 == 0  →  cand2 = element, votes2 = 1
        • Else            →  votes1--,   votes2--  (element cancels both)

  DRY RUN:
    arr = [1, 1, 1, 3, 3, 2, 2, 2],  n=8,  threshold = 8/3 ≈ 2.67 → need count > 2
    Start: cand1=null, votes1=0,  cand2=null, votes2=0

    num=1 | ≠cand1(null), ≠cand2(null), votes1=0  → cand1=1, votes1=1
    num=1 | == cand1=1                              → votes1=2
    num=1 | == cand1=1                              → votes1=3
    num=3 | ≠cand1(1), ≠cand2(null), votes2=0      → cand2=3, votes2=1
    num=3 | == cand2=3                              → votes2=2
    num=2 | ≠cand1(1), ≠cand2(3), both votes > 0   → votes1=2, votes2=1
    num=2 | ≠cand1(1), ≠cand2(3), both votes > 0   → votes1=1, votes2=0
    num=2 | ≠cand1(1), ≠cand2(3), votes2=0         → cand2=2, votes2=1

    Final: cand1=1 (votes=1),  cand2=2 (votes=1)

    VERIFY by counting:
      1 → 3 times  |  3 > 2.67  ✓  → qualifies
      2 → 3 times  |  3 > 2.67  ✓  → qualifies
      3 → 2 times  |  2 ≤ 2.67  ✗  → rejected

    → Result: [1, 2]  ✓

  TIME  : O(n)   — two passes (voting + verification)
  SPACE : O(1)   — only 4 extra variables
*/
function majorityElementsThird(array $nums): array
{
    $n     = count($nums);
    $cand1 = null;  $votes1 = 0;
    $cand2 = null;  $votes2 = 0;

    // Phase 1: Find up to 2 candidates using extended Boyer-Moore Voting
    foreach ($nums as $num) {
        if ($num === $cand1) {
            $votes1++;
        } elseif ($num === $cand2) {
            $votes2++;
        } elseif ($votes1 === 0) {
            $cand1  = $num;
            $votes1 = 1;
        } elseif ($votes2 === 0) {
            $cand2  = $num;
            $votes2 = 1;
        } else {
            $votes1--;      // Current element cancels one vote from each candidate
            $votes2--;
        }
    }

    // Phase 2: Verify candidates actually appear > n/3 times
    $count1 = 0;
    $count2 = 0;

    foreach ($nums as $num) {
        if ($num === $cand1)      { $count1++; }
        elseif ($num === $cand2)  { $count2++; }
    }

    $result = [];
    if ($cand1 !== null && $count1 > intdiv($n, 3)) { $result[] = $cand1; }
    if ($cand2 !== null && $count2 > intdiv($n, 3)) { $result[] = $cand2; }

    return $result;
}

echo "5.10  Majority Elements > n/3 [LC #229] — Extended Boyer-Moore:\n";
echo "      Input: [1, 1, 1, 3, 3, 2, 2, 2]\n";
$result = majorityElementsThird([1, 1, 1, 3, 3, 2, 2, 2]);
echo "      Output: [" . implode(", ", $result) . "]\n\n";


// ─────────────────────────────────────────────────────────────────────────────
//  5.11  FREQUENCY OF THE MOST FREQUENT ELEMENT   [LeetCode #1838]
// ─────────────────────────────────────────────────────────────────────────────
/*
  PROBLEM:
    Given nums[] and integer k (max allowed increment operations, each +1),
    find the maximum achievable frequency of any element.

  EXAMPLE:
    Input  : nums=[1, 2, 4],  k=5
    Output : 3     (make all three equal to 4: cost = (4-1)+(4-2)+(4-4) = 3+2+0 = 5 ≤ k)

  KEY INSIGHT — Sort + Sliding Window:
    • After sorting, it is always cheapest to make all elements in a window
      equal to nums[r] (the largest element in the window).
    • Cost to make all elements in window [l..r] equal to nums[r]:
        cost = nums[r] × windowSize  -  sum(window elements)
      ← We'd need to increment each element up to nums[r], total difference.
    • If cost ≤ k → window is valid → update maxFreq.
    • If cost > k → shrink from the left (l++) until valid again.

  WHY SORT?
    After sorting, for window [l..r], target = nums[r] (largest, rightmost).
    We can only INCREMENT elements (not decrement), so picking the largest value
    in the window as the target is the only option that makes sense.

  DRY RUN:
    nums = [1, 2, 4],  k=5
    After sort: [1, 2, 4]
    l=0,  windowSum=0,  maxFreq=0

    r=0 | nums[0]=1 | windowSum = 0+1 = 1
          cost = 1×(0-0+1) - 1  =  1×1 - 1  =  0  ≤ 5  →  maxFreq = max(0,1) = 1

    r=1 | nums[1]=2 | windowSum = 1+2 = 3
          cost = 2×(1-0+1) - 3  =  2×2 - 3  =  1  ≤ 5  →  maxFreq = max(1,2) = 2

    r=2 | nums[2]=4 | windowSum = 3+4 = 7
          cost = 4×(2-0+1) - 7  =  4×3 - 7  =  5  ≤ 5  →  maxFreq = max(2,3) = 3  ✓

    → maxFreq = 3  ✓

  ─────────────────────────────────────────────────────────────────────────────
  ALTERNATE APPROACH — Binary Search (original approach — kept for reference):

    • Sort the array.
    • Binary search on the answer (frequency from 1 to n).
    • For a given mid (candidate frequency), use a sliding window of size mid
      to check if any window can be made uniform with ≤ k operations.
    • checkPossible (helper): checks if a window of size $mid is achievable.

  DRY RUN (Binary Search approach):
    nums=[1,2,3], k=3  → sort=[1,2,3]
    l=1, h=3, ans=1

    mid=2 | checkPossible([1,2,3], 2, 3)?
              totalCount = nums[1] × 2 = 2×2 = 4
              windowCount = nums[0]+nums[1] = 1+2 = 3
              4 - 3 = 1 ≤ 3 → true  → ans=2, l=3

    mid=3 | checkPossible([1,2,3], 3, 3)?
              totalCount = nums[2] × 3 = 3×3 = 9
              windowCount = 1+2+3 = 6
              9 - 6 = 3 ≤ 3 → true  → ans=3, l=4

    l=4 > h=3 → stop
    → ans = 3  ✓

  TIME  (Sliding Window)  : O(n log n) for sort + O(n) for window = O(n log n)
  TIME  (Binary Search)   : O(n log n) for sort + O(n log n) for BS = O(n log n)
  SPACE : O(1)  both approaches
*/

// --- APPROACH A: Sliding Window (Cleaner & Recommended) ---
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

// --- APPROACH B: Binary Search on Answer (Original approach kept for reference) ---
function maxFrequencyBinarySearch(array $nums, int $k): int
{
    sort($nums);
    $n   = count($nums);
    $l   = 1;
    $h   = $n;
    $ans = 1;

    while ($l <= $h) {
        $mid = intdiv($l + $h, 2);         // Binary search mid = candidate frequency

        if (_checkPossible($nums, $mid, $k)) {
            $ans = $mid;                    // mid frequency is achievable → try higher
            $l   = $mid + 1;
        } else {
            $h   = $mid - 1;               // mid frequency not achievable → try lower
        }
    }

    return $ans;
}

// Helper for Approach B: checks if frequency $mid is achievable within $k operations
function _checkPossible(array $nums, int $mid, int $k): bool
{
    // First window: nums[0 .. mid-1] → target = nums[mid-1]
    // Cost = nums[mid-1] × mid   minus   sum(nums[0..mid-1])
    $targetSum  = $nums[$mid - 1] * $mid;   // If all mid elements equal nums[mid-1]
    $windowSum  = 0;

    for ($i = 0; $i < $mid; $i++) {
        $windowSum += $nums[$i];            // Actual sum of first window
    }

    if ($targetSum - $windowSum <= $k) {
        return true;                        // First window is achievable
    }

    // Slide the window: remove nums[j], add nums[i], update target to nums[i]
    $j = 0;
    for ($i = $mid; $i < count($nums); $i++) {
        $windowSum -= $nums[$j];            // Remove leftmost element leaving window
        $windowSum += $nums[$i];            // Add new rightmost element to window
        $targetSum  = $nums[$i] * $mid;     // New target = nums[i] (new rightmost)
        $j++;

        if ($targetSum - $windowSum <= $k) {
            return true;
        }
    }

    return false;
}

echo "5.11  Frequency of Most Frequent Element [LC #1838]:\n";
echo "      Input: [1, 2, 4], k=5\n";
echo "      Output (Sliding Window):   " . maxFrequencySlidingWindow([1, 2, 4], 5) . "\n";
echo "      Output (Binary Search):    " . maxFrequencyBinarySearch([1, 2, 4], 5) . "\n\n";
echo "      Input: [1, 4, 8, 13], k=5\n";
echo "      Output (Sliding Window):   " . maxFrequencySlidingWindow([1, 4, 8, 13], 5) . "\n";
echo "      Output (Binary Search):    " . maxFrequencyBinarySearch([1, 4, 8, 13], 5) . "\n\n";


echo str_repeat("=", 80) . "\n";
echo "  END OF HASHING REVISION NOTES\n";
echo str_repeat("=", 80) . "\n";

/*
================================================================================
  QUICK REFERENCE — Pattern Cheat Sheet

  PROBLEM TYPE                          TECHNIQUE
  ──────────────────────────────────────────────────────────────────────────────
  Frequency count (int/char)          → HashMap: freqMap[$x]++
  Two elements summing to target      → HashMap: store {value→index}, check complement
  Count/longest subarray with sum=K   → Prefix Sum + HashMap (store prefixSum→index/count)
  Check zero-sum subarray             → Prefix Sum + HashSet (detect repeated prefix sum)
  Group by common property            → HashMap: use derived key (sorted chars etc.)
  Consecutive sequence O(n)           → HashSet + "start of sequence" trick
  Majority element > n/2              → Boyer-Moore Voting (1 candidate)
  Majority elements > n/3             → Extended Boyer-Moore Voting (2 candidates)
  Max frequency after k increments    → Sort + Sliding Window
  ──────────────────────────────────────────────────────────────────────────────
================================================================================
*/

// Example 1:
// Input: arr[] = {10,5,10,15,10,5};
// Output: 10  3
// 	    5  2
//         15  1

//TimeComplexity: O(n)
//SpaceComplexity: O(n)
//BFA
$arr =[10,5,10,15,10,5];
$count = count($arr);

$visited = array_fill(0, $count, 'F');

for($i=0;$i<$count;$i++){
    if(isset($visited[$arr[$i]]) && ($visited[$arr[$i]] == 'T')) continue;

    $counter = 1;
    for($j=$i+1;$j < $count;$j++){  
        $visited[$arr[$i]] = 'T';
        if($arr[$i] == $arr[$j]){
          $counter++;
        }
    }
    echo $arr[$i]." ".$counter."<BR>";
}
//OA

//Time Complexity: O(n)
//Space Complexity: O(n)
$arr =[10,5,10,15,10,5];
$count = count($arr);
$visited = [];

for($i=0;$i<$count;$i++){
    if(isset($visited[$arr[$i]])) $visited[$arr[$i]]++;
    else 
      $visited[$arr[$i]] = 1;
}
foreach($visited as $key=>$value){  
  echo $key." ".$value."<BR>";
}



// abcdafedcb
//count frequency of each charetecter and out with fequency
//echo ord("b")-ord("a");

function charFrequency($str) {
  $freqArr=[];
  $str = str_split($str);
  $count = count($str);
  //Assicate Array Approch

  foreach($str as $chr){
      if(isset($freqArr[$chr])) 
          $freqArr[$chr]++;
      else 
          $freqArr[$chr] = 1;
  }

  arsort($freqArr);
  foreach($freqArr as $chr => $freq){
      echo $chr.$freq;
  }

   //Second Approch - AscII approch

  for($i=0;$i<$count;$i++){
      // ord() to get ascii int value
      $index = ord($str[$i])-ord("a"); //it works like for chr 'b' ($str[$i]) 98-97, index become 1 and for 'a' index become 0 and so on
      if(isset($freqArr[$index])) 
          $freqArr[$index]++;
      else 
          $freqArr[$index] = 1;
  }
  
  arsort($freqArr); //asort to get ascending iorder
  foreach($freqArr as $chr => $freq){
    //ch() use to ascci int value to chareterec
    //chr(97 + 1) will return 'b' and chr(97 + 0) will return 'a' and so on
    echo chr(ord("a") + $chr).$freq;
  }
  
}

$str = 'abcbdabfg';
charFrequency($str); //b3a2c1d1f1g1


//Devidie AND COLLIDE METHOD  
$arr = [1,5,1,10,10,10,5,10,8,5,10,15];
$newArr = [];

for($i=0;$i<count($arr);$i++){
    $digit = $arr[$i] % 10;
    if(isset($newArr[$digit][$arr[$i]- $digit])) $newArr[$digit][$arr[$i]- $digit]++;
    else $newArr[$digit][$arr[$i]- $digit] = 1;
}
foreach($newArr as $key=>$value){
    foreach($value as $num=>$freq){
        echo "Digit: ".($num + $key)." with frequency: ".$freq."\n";
    }
    //echo "Digit: ".$key." with frequency: ".$freq."\n";
}

//Find the highest/lowest frequency element
class FrequencyCounter {

    public function frequency($arr) {
        $freqMap = []; // element => frequency

        // Count frequencies
        foreach ($arr as $num) {
            if (isset($freqMap[$num])) {
                $freqMap[$num]++;
            } else {
                $freqMap[$num] = 1;
            }
        }

        $maxFreq = 0;
        $minFreq = count($arr);

        $maxEle = null;
        $minEle = null;

        // Find max and min frequency elements
        foreach ($freqMap as $element => $count) {
            if ($count > $maxFreq) {
                $maxFreq = $count;
                $maxEle = $element;
            }

            if ($count < $minFreq) {
                $minFreq = $count;
                $minEle = $element;
            }
        }

        // Print results
        echo "The highest frequency element is: $maxEle\n";
        echo "The lowest frequency element is: $minEle\n";
    }
}

// Test
$fc = new FrequencyCounter();
$arr = [10, 5, 10, 15, 10, 5];
$fc->frequency($arr);

//1838. Frequency of the Most Frequent Element //Dom with Binery Search and Sliding Window
 function maxFrequency($nums, $key) {
  $count = count($nums);
  sort($nums);
  $l = 1; $h = $count;
  $ans = 1;
  while($l<=$h){
    $mid = (int) (($h + $l) / 2); //use binery search
    if(checkPossible($nums, $mid, $key)){
      $ans = $mid;
      $l = $mid+1;
    }else{
      $h = $mid-1;
    }
  }

  return $ans;

}

function checkPossible($nums, $mid, $key){
    
  $totalCount = $nums[$mid-1] * ($mid);  //sum all element till $mid in which all element is equal to $nums[$mid] 1,2,3 => 3,3,3 = 9 ==> will check 9-6 <= $KEY
  $windowCount = 0;
  for($i=0;$i<$mid;$i++){//count sum till mid-1 
    $windowCount += $nums[$i];
  }
  

  if($totalCount - $windowCount <= $key) return true; //$nums[$mid] 1,2,3 => 3,3,3 = 9 ==> will check 9-6 <= $KEY

  $j=0;
  for($i=$mid;$i<count($nums);$i++){ //REMOVE 1 and add 4 from both counting
    $windowCount -=$nums[$j]; 
    $windowCount +=$nums[$i];
    $totalCount = $nums[$i] * ($mid); 

    if($totalCount - $windowCount <= $key) return true;

    $j++;

  }

}


$nums = [1,2,4];
$key=5;
echo maxFrequency($nums, $key); 


?>