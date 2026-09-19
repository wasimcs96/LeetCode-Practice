<?php

/*
|--------------------------------------------------------------------------
| Problem: Missing Number (LC 268)
| Source: DS/3. Array/Array_enhancement.php
| Pattern: Bit Manipulation
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
XOR-ing every array value together with every index in [0, n] cancels all paired numbers, leaving only the missing one - the same x^x=0 cancellation trick used in Single Number.
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
| Problem: Single Number (LC 136)
| Source: DS/3. Array/Array_enhancement.php
| Pattern: Bit Manipulation
|--------------------------------------------------------------------------
*/

/*
Problem Description:
 What: every element in the array appears exactly twice except one, which
       appears exactly once. Find that element.
 Why it exists: the foundational XOR-cancellation problem -- everything
   in Bit Manipulation's "Single Number" family (I/II/III) builds on this
   core insight: x^x=0, x^0=x, XOR is commutative and associative.

Example:
Input: nums = [4,1,2,1,2]
Output: 4

Why This Pattern:
XOR-ing the whole array cancels every value that appears twice (x^x=0), leaving only the element that appears once - the foundational XOR-cancellation trick.
*/

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

function singleNumber(array $nums): int {
    $result = 0;
    foreach ($nums as $num) {
        $result ^= $num;   // Duplicates cancel (a^a=0); result ends up holding the lone value
    }
    return $result;
}


/*
|--------------------------------------------------------------------------
| Problem: Basic Bit Operations — Get, Set, Clear, Toggle, Update Bit
| Source: DS/7. Bit-Manipulation/11. BitManipulation.php
| Pattern: Bit Manipulation
|--------------------------------------------------------------------------
*/

/*
Problem Description:
1. BASIC BIT OPERATIONS
   Get | Set | Clear | Toggle | Update  (bit at position k)
   k is 0-indexed from the RIGHT  (k=0 is the Least Significant Bit)
Intuition — the MASK pattern:
  mask = 1 << k    →  a 1 at position k, 0s everywhere else

  GET    : shift n right k positions, check LSB   O(1)
  SET    : OR  with mask  (forces bit-k to 1)    O(1)
  CLEAR  : AND with ~mask (forces bit-k to 0)    O(1)
  TOGGLE : XOR with mask  (flips bit-k)           O(1)
  UPDATE : CLEAR first, then OR with new value   O(1)

Dry Run: n = 6 = 0110₂,  k = 1
  mask = 1 << 1 = 0010
  GET    : (0110 >> 1) & 1 = 011 & 001 = 1          (bit-1 of 6 is 1)   ✓
  SET    : 0110 | 0010    = 0110 = 6                 (already 1, no change) ✓
  CLEAR  : 0110 & ~0010   = 0110 & 1101 = 0100 = 4  (bit-1 forced to 0)  ✓
  TOGGLE : 0110 ^ 0010    = 0100 = 4                 (1 flipped to 0)     ✓
  UPDATE(k=1, bit=0): clearBit(6,1) | (0<<1) = 4 | 0 = 4                ✓

TC: O(1)  SC: O(1) — single CPU instruction each

Example:
Input: n = 6 (0110), k = 1
Output: getBit=1, setBit=6, clearBit=4, toggleBit=4, updateBit(k=1,bit=0)=4

Why This Pattern:
The get/set/clear/toggle/update-bit mask operations are the five foundational primitives every other Bit Manipulation technique is built from - the pattern's own handbook opens with exactly this mask template (mask = 1 << k) as the starting point for the whole topic.
*/

function getBit(int $n, int $k): int
{
    return ($n >> $k) & 1;           // Shift bit-k to position 0, extract with &1
}

function setBit(int $n, int $k): int
{
    return $n | (1 << $k);           // OR with mask: force bit-k to 1
}

function clearBit(int $n, int $k): int
{
    return $n & ~(1 << $k);          // AND with inverted mask: force bit-k to 0
}

function toggleBit(int $n, int $k): int
{
    return $n ^ (1 << $k);           // XOR with mask: flip bit-k
}

function updateBit(int $n, int $k, int $bit): int
{
    return clearBit($n, $k) | ($bit << $k); // Clear first, then OR with new value
}


/*
|--------------------------------------------------------------------------
| Problem: Check Odd or Even Using Bit Manipulation
| Source: DS/7. Bit-Manipulation/11. BitManipulation.php
| Pattern: Bit Manipulation
|--------------------------------------------------------------------------
*/

/*
Problem Description:
2. CHECK ODD OR EVEN
Intuition:
  The Least Significant Bit (bit-0) determines odd/even.
  All ODD numbers end in 1 in binary.  All EVEN numbers end in 0.
    n & 1 == 1  →  n is ODD
    n & 1 == 0  →  n is EVEN

Dry Run:
  n=7 (0111): 0111 & 0001 = 1  → ODD   ✓
  n=8 (1000): 1000 & 0001 = 0  → EVEN  ✓

TC: O(1)  SC: O(1)

Example:
Input: n = 7
Output: true

Input: n = 8
Output: false

Why This Pattern:
Checking the least significant bit (n & 1) instead of using the modulo operator is the canonical first bit trick - a direct application of bit isolation.
*/

function isOdd(int $n): bool
{
    return ($n & 1) === 1;
}


/*
|--------------------------------------------------------------------------
| Problem: Reverse a Number (Digit Reversal)
| Source: DS/7. Bit-Manipulation/11. BitManipulation.php
| Pattern: Bit Manipulation
|--------------------------------------------------------------------------
*/

/*
Problem Description:
3. REVERSE A NUMBER (Digit Reversal, Not Bit Reversal)
Intuition:
  Extract the LAST decimal digit with (n % 10).
  Append it to result: result = result*10 + lastDigit.
  Remove the last digit: n = floor(n / 10).
  Repeat until n becomes 0.

Dry Run: n = 1234
  Iter 1: last=4, rev=0*10+4=4,     n=123
  Iter 2: last=3, rev=4*10+3=43,    n=12
  Iter 3: last=2, rev=43*10+2=432,  n=1
  Iter 4: last=1, rev=432*10+1=4321, n=0 → STOP
  Result: 4321  ✓

TC: O(d) where d = number of decimal digits  SC: O(1)

Example:
Input: n = 1234
Output: 4321

Input: n = -560
Output: -65

Why This Pattern:
Included as part of the same revision-guide progression of foundational numeric manipulation techniques (digit extraction via % and /) that the file builds on before moving into true bitwise tricks.
*/

function reverseNumber(int $n): int
{
    $isNeg    = $n < 0;
    $n        = abs($n);
    $reversed = 0;

    while ($n > 0) {
        $lastDigit = $n % 10;
        $reversed  = $reversed * 10 + $lastDigit; // Shift left and append digit
        $n         = intdiv($n, 10);              // Remove last decimal digit
    }

    return $isNeg ? -$reversed : $reversed;
}


/*
|--------------------------------------------------------------------------
| Problem: Decimal to Binary (Manual Conversion)
| Source: DS/7. Bit-Manipulation/11. BitManipulation.php
| Pattern: Bit Manipulation
|--------------------------------------------------------------------------
*/

/*
Problem Description:
4. DECIMAL TO BINARY (Manual Conversion)
Intuition:
  Repeatedly divide n by 2.
  The remainder (0 or 1) at each step = next bit from the RIGHT.
  PREPEND each remainder to build the binary string.

Dry Run: n = 13
  13 ÷ 2 = 6  rem 1  → "1"
   6 ÷ 2 = 3  rem 0  → "01"
   3 ÷ 2 = 1  rem 1  → "101"
   1 ÷ 2 = 0  rem 1  → "1101"
  Result: "1101"  (13 = 8+4+1)  ✓

TC: O(log N)  SC: O(log N) — string of log₂(N) characters

Example:
Input: n = 13
Output: "1101"

Why This Pattern:
Manual decimal-to-binary conversion is the base representation every other bit trick in this file assumes the reader can compute by hand - foundational to the whole pattern.
*/

function decToBin(int $n): string
{
    if ($n === 0) return "0";

    $bin = '';
    while ($n > 0) {
        $bin = ($n % 2) . $bin;  // Prepend remainder (builds right-to-left)
        $n   = intdiv($n, 2);
    }
    return $bin;
}


/*
|--------------------------------------------------------------------------
| Problem: Binary to Decimal (Manual Conversion)
| Source: DS/7. Bit-Manipulation/11. BitManipulation.php
| Pattern: Bit Manipulation
|--------------------------------------------------------------------------
*/

/*
Problem Description:
5. BINARY TO DECIMAL (Manual Conversion)
Intuition:
  Each bit at position i (0-indexed from right) contributes bit × 2^i.
  Process the binary string from RIGHTMOST character backwards.

Dry Run: bin = "1101"
  pos=0 (char '1'): decimal +=  1×1  = 1,  pow=2
  pos=1 (char '0'): decimal +=  0×2  = 1,  pow=4
  pos=2 (char '1'): decimal +=  1×4  = 5,  pow=8
  pos=3 (char '1'): decimal +=  1×8  = 13, pow=16
  Result: 13  (1101₂ = 8+4+1 = 13)  ✓

TC: O(d) where d = length of binary string  SC: O(1)

Example:
Input: bin = "1101"
Output: 13

Why This Pattern:
The inverse of decimal-to-binary conversion, completing the manual base-conversion pair that underlies reasoning about binary representations throughout the pattern.
*/

function binToDec(string $bin): int
{
    $decimal = 0;
    $pow     = 1;
    $len     = strlen($bin);

    for ($i = $len - 1; $i >= 0; $i--) {  // Start from rightmost bit
        if ($bin[$i] === '1') {
            $decimal += $pow;              // This bit is set, add its place value
        }
        $pow *= 2;                         // Next position is worth twice as much
    }
    return $decimal;
}


/*
|--------------------------------------------------------------------------
| Problem: Swap Two Numbers Without a Temporary Variable (XOR Trick)
| Source: DS/7. Bit-Manipulation/11. BitManipulation.php
| Pattern: Bit Manipulation
|--------------------------------------------------------------------------
*/

/*
Problem Description:
6. SWAP TWO NUMBERS — XOR Trick (No Temporary Variable)
Intuition:
  XOR properties: a^a=0  and  a^0=a
  Step 1: a = a ^ b           (a now encodes both values)
  Step 2: b = a ^ b = (a^b)^b = a   (b becomes original a)
  Step 3: a = a ^ b = (a^b)^a = b   (a becomes original b)

⚠ Guard: if $a === $b (same variable or same value), three XORs
  make it zero. Always guard before using.

Dry Run: a=5 (101), b=3 (011)
  Step 1: a = 101^011 = 110 (=6)
  Step 2: b = 110^011 = 101 (=5)  ← original a  ✓
  Step 3: a = 110^101 = 011 (=3)  ← original b  ✓

TC: O(1)  SC: O(1)

Example:
Input: a = 5, b = 3
Output: a = 3, b = 5 (no temp variable used)

Why This Pattern:
The classic XOR self-cancellation trick (a^a=0) used to swap two values with zero extra space - the same a^a=0 property that powers Single Number and Single Number III elsewhere in this pattern.
*/

function swapXOR(int &$a, int &$b): void
{
    if ($a === $b) return;  // Guard: same value → all three XORs give 0
    $a = $a ^ $b;           // Step 1: a holds XOR of both
    $b = $a ^ $b;           // Step 2: b = (a^b)^b = original a
    $a = $a ^ $b;           // Step 3: a = (a^b)^a = original b
}


/*
|--------------------------------------------------------------------------
| Problem: Remove the Rightmost Set Bit
| Source: DS/7. Bit-Manipulation/11. BitManipulation.php
| Pattern: Bit Manipulation
|--------------------------------------------------------------------------
*/

/*
Problem Description:
7. REMOVE RIGHTMOST SET BIT  —  n & (n−1)
Intuition:
  n−1  flips the rightmost 1-bit AND all 0-bits to its right.
  n & (n−1) cancels exactly those changed bits → rightmost 1 removed.

Dry Run: n = 12 = 1100₂
  n−1 = 11 = 1011₂
  n & (n-1) = 1100 & 1011 = 1000 = 8   (rightmost set bit removed)  ✓

  n = 6 = 0110₂
  n−1 = 5 = 0101₂
  n & (n-1) = 0110 & 0101 = 0100 = 4   (rightmost set bit removed)  ✓

TC: O(1)  SC: O(1)

Example:
Input: n = 12 (1100)
Output: 8 (1000)

Why This Pattern:
n & (n-1) removing the rightmost set bit is one of the five essential bit tricks the pattern's handbook calls out by name, and it directly powers both Brian Kernighan's set-bit counting and the power-of-two check.
*/

function removeRightmostSetBit(int $n): int
{
    return $n & ($n - 1);
}


/*
|--------------------------------------------------------------------------
| Problem: Check if a Number is a Power of Two (LC 231)
| Source: DS/7. Bit-Manipulation/11. BitManipulation.php
| Pattern: Bit Manipulation
|--------------------------------------------------------------------------
*/

/*
Problem Description:
8. CHECK IF NUMBER IS A POWER OF 2
Intuition:
  Powers of 2 have EXACTLY ONE set bit: 1=0001, 2=0010, 4=0100 ...
  n & (n−1) removes the only set bit → result is 0.
  Guard: n must be > 0  (0 is NOT a power of 2).

Dry Run: n=8 (1000): 8 & 7 = 1000 & 0111 = 0000 = 0  → true   ✓
         n=6 (0110): 6 & 5 = 0110 & 0101 = 0100 ≠ 0  → false  ✓
         n=0       : guard 0 > 0 fails               → false  ✓

TC: O(1)  SC: O(1)

Example:
Input: n = 8
Output: true

Input: n = 6
Output: false

Why This Pattern:
n > 0 && (n & (n-1)) == 0 is the textbook power-of-two check via bit isolation - the user's own Bit-Manipulation handbook lists 'Power of Two' (LC 231) as a core Easy problem built on exactly this trick.
*/

function isPowerOf2(int $n): bool
{
    return $n > 0 && ($n & ($n - 1)) === 0;
}


/*
|--------------------------------------------------------------------------
| Problem: Count Set Bits — Right-Shift and Brian Kernighan's Approaches (LC 191)
| Source: DS/7. Bit-Manipulation/11. BitManipulation.php
| Pattern: Bit Manipulation
|--------------------------------------------------------------------------
*/

/*
Problem Description:
9. COUNT SET BITS (Number of 1s in Binary Representation)
APPROACH 1 — Right-Shift Method (O(log N)):
  Check the LSB with (n & 1), add it to count.
  Right-shift n by 1 (= divide by 2) to process the next bit.
  Repeat until n == 0.

Dry Run: n = 13 = 1101₂
  13 & 1 = 1, count=1, n = 0110 = 6
   6 & 1 = 0, count=1, n = 0011 = 3
   3 & 1 = 1, count=2, n = 0001 = 1
   1 & 1 = 1, count=3, n = 0000 = 0 → STOP
  Result: 3 set bits  ✓

TC: O(log N)  SC: O(1)

APPROACH 2 — Brian Kernighan's Algorithm (O(K)):
  n & (n−1) removes the RIGHTMOST set bit in one operation.
  Count how many times we can do this before n reaches 0.
  K iterations = K set bits. Faster when K << log N.

Dry Run: n = 13 = 1101₂
  Iter 1: 1101 & 1100 = 1100 = 12, count=1
  Iter 2: 1100 & 1011 = 1000 =  8, count=2
  Iter 3: 1000 & 0111 = 0000 =  0, count=3 → STOP
  Result: 3 set bits  ✓

TC: O(K) where K = number of set bits  SC: O(1)

Approach 1: Right-shift — iterates log₂(N) times

Example:
Input: n = 13 (1101)
Output: 3 (via both the right-shift and Brian Kernighan approaches)

Why This Pattern:
Counting set bits - via the right-shift scan or Brian Kernighan's n & (n-1) removal - is the flagship 'Number of 1 Bits' (LC 191) problem the handbook lists as a core Beginner example, with both classic approaches implemented side by side here.
*/

function countSetBitsShift(int $n): int
{
    $count = 0;
    while ($n > 0) {
        $count += $n & 1;   // Add 1 if the least significant bit is set
        $n     >>= 1;       // Shift right by 1 (divide by 2)
    }
    return $count;
}

function countSetBitsBK(int $n): int
{
    $count = 0;
    while ($n > 0) {
        $n &= ($n - 1);     // Remove the rightmost set bit in one step
        $count++;
    }
    return $count;
}


/*
|--------------------------------------------------------------------------
| Problem: Check the K-th Bit of a Number
| Source: DS/7. Bit-Manipulation/11. BitManipulation.php
| Pattern: Bit Manipulation
|--------------------------------------------------------------------------
*/

/*
Problem Description:
10. CHECK K-TH BIT (0-indexed from right)
Intuition:
  Shift n RIGHT by k positions → bit-k lands at position 0.
  Mask with 1 to isolate that single bit.
  Result: 1 if bit-k is set, 0 if not.

Dry Run: n = 13 = 1101₂
  k=2: (13 >> 2) = 0011; 0011 & 0001 = 1  → bit-2 is SET    ✓
  k=1: (13 >> 1) = 0110; 0110 & 0001 = 0  → bit-1 is NOT set ✓
  (13 = 1101₂: bit0=1, bit1=0, bit2=1, bit3=1)

⚠ Common Bug: ($n & ($k << 1)) is WRONG.
  It shifts the KEY $k, not a 1-bit mask.
  Correct formula: ($n >> $k) & 1   OR   ($n & (1 << $k)) != 0

TC: O(1)  SC: O(1)

Example:
Input: n = 13 (1101), k = 2
Output: true (bit-2 is 1)

Why This Pattern:
(n >> k) & 1 to test a specific bit position is the same mask-and-check primitive as getBit, applied as a standalone boolean check - a direct bit-isolation technique.
*/

function checkKthBit(int $n, int $k): bool
{
    return (($n >> $k) & 1) === 1; // Shift n to bring bit-k to LSB, then mask
}


/*
|--------------------------------------------------------------------------
| Problem: Count Bits to Flip to Convert A to B (LC 2220)
| Source: DS/7. Bit-Manipulation/11. BitManipulation.php
| Pattern: Bit Manipulation
|--------------------------------------------------------------------------
*/

/*
Problem Description:
11. COUNT BITS TO FLIP A → B  (LeetCode 2220)
Intuition:
  A XOR B produces a 1 wherever A and B DIFFER.
  Each 1-bit in (A ^ B) is one flip needed.
  Count the 1-bits in (A ^ B) using Brian Kernighan's algorithm.

Dry Run: A = 10 = 1010₂,  B = 7 = 0111₂
  A ^ B = 1010 ^ 0111 = 1101 = 13   (bits that differ)
  Count set bits in 1101: 3 flips needed  ✓

TC: O(K) where K = differing bits  SC: O(1)

Example:
Input: a = 10, b = 7
Output: 3

Why This Pattern:
XOR-ing A and B turns every differing bit into a 1, so counting the set bits of A^B (via Brian Kernighan's trick) directly answers 'how many bits differ' - the exact technique behind LeetCode 2220 (Minimum Bit Flips to Convert Number).
*/

function countBitsToFlip(int $a, int $b): int
{
    $xorResult = $a ^ $b;   // 1 at every position where a and b differ
    $count     = 0;

    while ($xorResult > 0) {
        $xorResult &= ($xorResult - 1); // Remove rightmost set bit
        $count++;
    }

    return $count;
}


/*
|--------------------------------------------------------------------------
| Problem: XOR of Numbers from 1 to N — O(1) Pattern
| Source: DS/7. Bit-Manipulation/11. BitManipulation.php
| Pattern: Bit Manipulation
|--------------------------------------------------------------------------
*/

/*
Problem Description:
12. XOR FROM 1 TO N — O(1) PATTERN TRICK
Intuition:
  XOR(1..N) follows a 4-step repeating cycle based on N % 4:
    N % 4 == 0  →  N
    N % 4 == 1  →  1
    N % 4 == 2  →  N + 1
    N % 4 == 3  →  0

  Verify N=1..8:
    XOR(1)    = 1      N%4=1 → 1      ✓
    XOR(1..2) = 3      N%4=2 → N+1=3  ✓
    XOR(1..3) = 0      N%4=3 → 0      ✓
    XOR(1..4) = 4      N%4=0 → 4      ✓
    XOR(1..5) = 1      N%4=1 → 1      ✓
    XOR(1..6) = 7      N%4=2 → 7      ✓
    XOR(1..7) = 0      N%4=3 → 0      ✓
    XOR(1..8) = 8      N%4=0 → 8      ✓

TC: O(1)  SC: O(1)

Example:
Input: n = 6
Output: 7

Why This Pattern:
XOR of 1..N follows a repeating 4-cycle based on n % 4, letting the whole prefix-XOR be computed in O(1) instead of a loop - a specialized XOR-pattern-recognition trick used as the building block for XOR over an arbitrary range.
*/

function xorOneTo(int $n): int
{
    switch ($n % 4) {
        case 0: return $n;
        case 1: return 1;
        case 2: return $n + 1;
        case 3: return 0;
    }
    return 0;
}


/*
|--------------------------------------------------------------------------
| Problem: XOR of Numbers in a Range [L, R]
| Source: DS/7. Bit-Manipulation/11. BitManipulation.php
| Pattern: Bit Manipulation
|--------------------------------------------------------------------------
*/

/*
Problem Description:
13. XOR FROM L TO R
Intuition:
  XOR(L..R) = XOR(1..R) ^ XOR(1..L-1)
  All terms from 1 to L-1 cancel: they appear in both halves.
  Use the O(1) xorOneTo() function for each half.

Dry Run: L=3, R=7
  xorOneTo(7) = 7%4=3  → 0
  xorOneTo(2) = 2%4=2  → 3
  XOR(3..7) = 0 ^ 3 = 3
  Manual: 3^4^5^6^7 = 7^5^6^7 = 5^6 = 3  ✓

TC: O(1)  SC: O(1)

Example:
Input: l = 3, r = 7
Output: 3

Why This Pattern:
XOR over [L, R] is computed as xorOneTo(R) ^ xorOneTo(L-1), the same prefix-XOR-difference idea used for range-sum queries but applied to XOR instead of addition.
*/

function xorLtoR(int $l, int $r): int
{
    return xorOneTo($r) ^ xorOneTo($l - 1);
}


/*
|--------------------------------------------------------------------------
| Problem: Position of the Only Set Bit
| Source: DS/7. Bit-Manipulation/11. BitManipulation.php
| Pattern: Bit Manipulation
|--------------------------------------------------------------------------
*/

/*
Problem Description:
14. POSITION OF THE ONLY SET BIT (1-indexed from right)
Intuition:
  First verify n is a power of 2 (exactly one set bit).
  If not → return -1 (undefined — multiple set bits).
  Then shift a mask left until it matches the set bit.
  Count shifts → that count is the bit position.

Dry Run: n = 8 = 1000₂
  isPowerOf2(8) → true
  mask=1 (0001): 0001 & 1000 = 0, shift. pos=2
  mask=2 (0010): 0010 & 1000 = 0, shift. pos=3
  mask=4 (0100): 0100 & 1000 = 0, shift. pos=4
  mask=8 (1000): 1000 & 1000 ≠ 0 → return 4  ✓

TC: O(log N)  SC: O(1)

Example:
Input: n = 16
Output: 5 (16 = 2^4, the only set bit is at 1-indexed position 5)

Why This Pattern:
Shifting a mask left until it matches the number isolates the single set bit's position - a direct application of the power-of-two / bit-isolation trick to a position-finding variant.
*/

function positionOfOnlySetBit(int $n): int
{
    if (!isPowerOf2($n)) return -1; // n has more than one set bit

    $pos  = 1;
    $mask = 1;
    while (($mask & $n) === 0) {
        $mask <<= 1; // Shift mask left until it aligns with the set bit
        $pos++;
    }
    return $pos;
}


/*
|--------------------------------------------------------------------------
| Problem: Two Non-Repeating Elements (LC 260 — Single Number III)
| Source: DS/7. Bit-Manipulation/11. BitManipulation.php
| Pattern: Bit Manipulation
|--------------------------------------------------------------------------
*/

/*
Problem Description:
16. TWO NON-REPEATING ELEMENTS  (LeetCode 260 — Single Number III)
    Every element appears TWICE except two unique elements x and y.
Intuition:
  Step 1: XOR all elements.
          Pairs cancel → xorAll = x ^ y  (≠ 0 because x ≠ y).

  Step 2: Find the RIGHTMOST SET BIT of xorAll.
          This bit DIFFERS between x and y (one has 1, other has 0).
          Formula: n & (-n)  isolates the rightmost set bit.
          (Two's complement: -n = ~n+1; the lowest set bit is preserved.)

  Step 3: Divide all elements into two groups by that bit:
            Group A → elements with this bit SET
            Group B → elements with this bit NOT SET
          XOR within each group cancels all pairs →
            Group A XOR = x  (or y)
            Group B XOR = y  (or x)

Dry Run: nums = [1, 2, 1, 3, 2, 5]  (unique: 3 and 5)
  Step 1: xorAll = 1^2^1^3^2^5 = 3^5 = 011^101 = 110 = 6
  Step 2: rightmostBit = 6 & (-6) = 0110 & ...1010 = 0010 = 2
          (bit-1 differs: 3=011 has bit-1=1, 5=101 has bit-1=0)
  Step 3: Group with bit-1 set   : {2,3,2} → x = 0^2^3^2 = 3  ✓
          Group without bit-1 set: {1,1,5} → y = 0^1^1^5 = 5  ✓
  Result: [3, 5]  ✓

TC: O(N)  SC: O(1)

Example:
Input: nums = [1,2,1,3,2,5]
Output: [3, 5]

Why This Pattern:
XOR all elements to get x^y, isolate their one differing bit with n & (-n), then partition and XOR within each group - the user's own Bit-Manipulation handbook lists this exact technique as its core 'Advanced two-unique-element XOR' example (#8, Single Number III, LC 260).
*/

function twoSingleNumbers(array $nums): array
{
    // Step 1: XOR all — pairs cancel, xorAll = x ^ y
    $xorAll = 0;
    foreach ($nums as $num) {
        $xorAll ^= $num;
    }

    // Step 2: Isolate rightmost set bit (bit that differs between x and y)
    // n & (-n): two's complement trick — isolates the lowest set bit
    $rightmostBit = $xorAll & (-$xorAll);

    // Step 3: Separate into two groups, XOR within each
    $x = 0;
    $y = 0;
    foreach ($nums as $num) {
        if ($num & $rightmostBit) {
            $x ^= $num; // Group A: this bit is set
        } else {
            $y ^= $num; // Group B: this bit is not set
        }
    }

    // Return in sorted order for consistency
    return [$x < $y ? $x : $y, $x < $y ? $y : $x];
}


/*
|--------------------------------------------------------------------------
| Problem: Generate All Subsets Using Bit Masking (LC 78)
| Source: DS/7. Bit-Manipulation/11. BitManipulation.php
| Pattern: Bit Manipulation
|--------------------------------------------------------------------------
*/

/*
Problem Description:
17. GENERATE ALL SUBSETS — BIT MASKING  (LeetCode 78)
Intuition:
  An N-element set has 2^N subsets (each element is IN or OUT).
  Use numbers 0 to 2^N−1 as BITMASKS.
  Bit j of mask i = 1  →  include arr[j] in this subset.
  Bit j of mask i = 0  →  exclude arr[j].

  Example: arr=[a,b,c], N=3, masks 0..7:
    000={},  001={a},  010={b},  011={a,b}
    100={c}, 101={a,c},110={b,c},111={a,b,c}

Dry Run: arr=[1,2,3], mask=5 (101₂)
  j=0: 101 & (1<<0) = 101 & 001 = 1 (set) → include arr[0]=1
  j=1: 101 & (1<<1) = 101 & 010 = 0       → skip arr[1]
  j=2: 101 & (1<<2) = 101 & 100 = 4 (set) → include arr[2]=3
  Subset for mask=5: {1, 3}  ✓

TC: O(N × 2^N)  SC: O(N × 2^N) — 2^N subsets each up to N elements

Example:
Input: arr = [1,2,3]
Output: 8 subsets: {}, {1}, {2}, {1,2}, {3}, {1,3}, {2,3}, {1,2,3}

Why This Pattern:
Enumerating masks 0 to 2^N-1 and reading bit j as 'include arr[j]' is the handbook's own named 'Bitmask Subset Enumeration' technique (#16, core problem list) - classified here as Bit Manipulation rather than Recursion/Backtracking because the actual implementation is pure mask iteration, not recursive choice-exploration.
*/

function getAllSubsets(array $arr): array
{
    $n       = count($arr);
    $total   = 1 << $n;       // 2^N total subsets
    $subsets = [];

    for ($mask = 0; $mask < $total; $mask++) {
        $subset = [];
        for ($j = 0; $j < $n; $j++) {
            if ($mask & (1 << $j)) {    // Is bit j of this mask set?
                $subset[] = $arr[$j];  // Yes → include arr[j]
            }
        }
        $subsets[] = $subset;           // Append subset (includes empty set {})
    }

    return $subsets;
}


/*
|--------------------------------------------------------------------------
| Problem: Divide Two Integers Without Using Multiplication or Division (LC 29)
| Source: DS/7. Bit-Manipulation/11. BitManipulation.php
| Pattern: Bit Manipulation
|--------------------------------------------------------------------------
*/

/*
Problem Description:
18. DIVIDE TWO INTEGERS WITHOUT *, /  (LeetCode 29)
Intuition:
  Simulate division using BIT SHIFTS (powers of 2).
  Find the largest k such that  divisor × 2^k ≤ dividend.
  Add 2^k to the answer, subtract (divisor × 2^k) from dividend.
  Repeat until dividend < divisor.

  Why bit shifts? They double in O(1) instead of subtracting
  one-by-one (which would be O(N) per remainder).

Dry Run: dividend=22, divisor=3
  Pass 1: 3<<1=6, 3<<2=12, 3<<3=24>22 → k=2
          ans += 4 (2^2), n = 22-12 = 10
  Pass 2: 3<<1=6, 3<<2=12>10 → k=1
          ans += 2 (2^1), n = 10-6 = 4
  Pass 3: 3<<1=6>4 → k=0
          ans += 1 (2^0), n = 4-3 = 1
  n=1 < d=3 → STOP.  ans = 4+2+1 = 7  ✓

TC: O(log² N)  SC: O(1)

Example:
Input: dividend = 22, divisor = 3
Output: 7

Why This Pattern:
Simulating division by repeatedly finding the largest divisor*2^k that still fits and subtracting it is a direct bit-shift arithmetic technique - the handbook lists 'Divide Two Integers' (LC 29, #13) as a core Medium 'bitwise arithmetic (division)' example.
*/

function divide(int $dividend, int $divisor): int
{
    $INT_MAX =  2147483647; //  2^31 − 1
    $INT_MIN = -2147483648; // −2^31

    // Special case: only overflow scenario
    if ($dividend === $INT_MIN && $divisor === -1) return $INT_MAX;

    $isNegative = ($dividend > 0) !== ($divisor > 0); // XOR of signs

    $n = abs($dividend);
    $d = abs($divisor);
    $ans = 0;

    while ($n >= $d) {
        $cnt = 0;
        // Find largest k so that d * 2^k <= n  (cap at 30 to prevent overflow)
        while ($cnt < 30 && $n >= ($d << ($cnt + 1))) {
            $cnt++;
        }
        $ans += (1 << $cnt);    // Add 2^k to the quotient
        $n   -= ($d << $cnt);   // Subtract d × 2^k from the remaining dividend
    }

    $result = $isNegative ? -$ans : $ans;
    return max($INT_MIN, min($INT_MAX, $result)); // Clamp to 32-bit signed range
}


/*
|--------------------------------------------------------------------------
| Problem: Square a Number Without *, /, or pow()
| Source: DS/7. Bit-Manipulation/11. BitManipulation.php
| Pattern: Bit Manipulation
|--------------------------------------------------------------------------
*/

/*
Problem Description:
19. SQUARE WITHOUT *, /, pow() — BIT SHIFTING
Intuition:
  Split n into h = n >> 1  (= floor(n/2)).
  Use the algebraic identity with LEFT SHIFTS (× 4 = << 2):

  If n is EVEN: n = 2h  → n² = 4h²      = squareBits(h) << 2
  If n is ODD : n = 2h+1 → n² = 4h²+4h+1 = (squareBits(h) << 2) + (h << 2) + 1

Dry Run: n=5, h=2 (ODD)
  square(5) = 4×square(2) + 4×2 + 1
  square(2): h=1, EVEN → 4×square(1)
  square(1): h=0, ODD  → 4×square(0) + 0 + 1 = 0+0+1 = 1
  square(2): 4×1 = 4
  square(5): 4×4 + 8 + 1 = 25  ✓

TC: O(log N) — log₂ recursive calls  SC: O(log N) — recursion stack

Example:
Input: n = 5
Output: 25

Why This Pattern:
Squaring via the algebraic identity (2h)^2=4h^2 / (2h+1)^2=4h^2+4h+1, computed entirely with left shifts (<<2 for *4) instead of multiplication, is a direct bit-shift-arithmetic technique in the same family as the shift-based Divide implementation.
*/

function squareBits(int $n): int
{
    if ($n === 0) return 0;
    if ($n < 0)   $n = -$n;            // Squaring is symmetric: (−n)² = n²

    $h = $n >> 1;                       // h = floor(n/2)

    if ($n & 1) {
        // n is ODD: n² = (2h+1)² = 4h² + 4h + 1
        return (squareBits($h) << 2) + ($h << 2) + 1;
    } else {
        // n is EVEN: n² = (2h)² = 4h²
        return squareBits($h) << 2;
    }
}


/*
|--------------------------------------------------------------------------
| Problem: Count Total Set Bits from 1 to N
| Source: DS/7. Bit-Manipulation/11. BitManipulation.php
| Pattern: Bit Manipulation
|--------------------------------------------------------------------------
*/

/*
Problem Description:
20. COUNT TOTAL SET BITS FROM 1 TO N
Intuition:
  For each number i from 1 to N, count its set bits using
  Brian Kernighan's algorithm and accumulate the total.

Dry Run: N = 4
  i=1 (0001): 1 set bit  → total=1
  i=2 (0010): 1 set bit  → total=2
  i=3 (0011): 2 set bits → total=4
  i=4 (0100): 1 set bit  → total=5
  Result: 5  ✓

TC: O(N log N)  SC: O(1)
(An O(log N) formula exists using bit-position patterns — O(log² N))

Example:
Input: n = 4
Output: 5 (1 + 1 + 2 + 1 across 1,2,3,4)

Why This Pattern:
A direct extension of Brian Kernighan's set-bit-counting trick, applied to every number from 1 to N and accumulated - the same n & (n-1) primitive the handbook calls out as one of the five essential bit tricks.
*/

function countTotalSetBits(int $n): int
{
    $total = 0;
    for ($i = 1; $i <= $n; $i++) {
        $num = $i;
        while ($num > 0) {
            $num   &= ($num - 1); // Brian Kernighan: remove rightmost set bit
            $total++;
        }
    }
    return $total;
}


/*
|--------------------------------------------------------------------------
| Problem: Copy Set Bits in a Range from One Number to Another
| Source: DS/7. Bit-Manipulation/11. BitManipulation.php
| Pattern: Bit Manipulation
|--------------------------------------------------------------------------
*/

/*
Problem Description:
21. COPY SET BITS IN A RANGE (from Y into X, positions l to r)
Intuition:
  l and r are 1-indexed bit positions (1 = rightmost bit).
  For each position i from l to r:
    If Y has a 1-bit at position i → set that bit in X as well.
  Uses a mask for each position: mask = 1 << (i-1).

Dry Run: x=8 (1000), y=7 (0111), l=1, r=2
  i=1: mask=1 (0001). y & 1 = 1 (set) → x = 8 | 1 = 9  (1001)
  i=2: mask=2 (0010). y & 2 = 2 (set) → x = 9 | 2 = 11 (1011)
  Result: 11  ✓  (bits 1 and 2 of y were both 1, now copied into x)

TC: O(r − l + 1)  SC: O(1)

Example:
Input: x = 8 (1000), y = 7 (0111), l = 1, r = 2
Output: 11 (1011)

Why This Pattern:
Building a positional mask (1 << (i-1)) and using it to test-then-set a specific bit is the same mask pattern as getBit/setBit, applied position-by-position across a range.
*/

function copySetBitsInRange(int $x, int $y, int $l, int $r): int
{
    for ($i = $l; $i <= $r; $i++) {
        $mask = 1 << ($i - 1);   // 1-indexed: position i → shift by (i−1)
        if ($y & $mask) {         // If Y has a 1 at this position...
            $x |= $mask;          // ...set that same bit in X
        }
    }
    return $x;
}

