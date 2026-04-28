<?php

// ============================================================
// BIT MANIPULATION — Complete Revision Guide
// Topics : Reference Table (Dec / Hex / Binary)
//          Basic Ops  : Get | Set | Clear | Toggle | Update Bit
//          Odd / Even Check
//          Reverse a Number (Digit Reversal)
//          Decimal ↔ Binary Conversions (Manual)
//          Swap Two Numbers — XOR Trick
//          Remove Rightmost Set Bit  | Check Power of 2
//          Count Set Bits: Right-Shift Approach | Brian Kernighan's
//          Check K-th Bit
//          Count Bits to Flip A → B  (LC 2220)
//          XOR from 1 to N — O(1) Pattern Trick
//          XOR from L to R
//          Position of the Only Set Bit
//          Single Number                          (LC 136)
//          Two Non-Repeating Elements             (LC 260)
//          Generate All Subsets — Bit Masking     (LC 78)
//          Divide Two Integers Without *, /       (LC 29)
//          Square Without *, /, pow() — Bit Shift
//          Count Total Set Bits from 1 to N
//          Copy Set Bits in a Range
// ============================================================
// Core Intuition:
//   Every integer is stored in BINARY in memory.
//   Bitwise operations execute in a SINGLE CPU clock cycle —
//   faster than arithmetic (+, *, /) for the same logical task.
//   Learn the five fundamental bit tricks below and most
//   bit manipulation problems become pattern-matching exercises.
//
// Operator Quick Reference:
//   &   (AND)    : 1 only if BOTH bits are 1           → isolate / check bits
//   |   (OR)     : 1 if AT LEAST ONE bit is 1          → set bits
//   ^   (XOR)    : 1 if bits DIFFER  (0 ≠ 1)           → flip / detect difference
//   ~   (NOT)    : flip ALL bits (bitwise complement)   → invert
//   <<  (Left  shift k) : n × 2^k  (append k zeros on right)
//   >>  (Right shift k) : n ÷ 2^k  (drop k bits from right)
//
// Five Essential Bit Tricks:
//   1.  n & 1        → check LSB (odd/even)
//   2.  n & (n-1)    → remove rightmost set bit
//   3.  n & (-n)     → isolate rightmost set bit  (two's complement)
//   4.  a ^ b        → cancel equal pairs (XOR same value twice = 0)
//   5.  1 << k       → create mask for bit at position k
// ============================================================


// ============================================================
// REFERENCE TABLE — Decimal / Hex / Binary (0 to 15)
// ============================================================
//
//  Dec   Hex   Binary    |  Dec   Hex   Binary
//   0     0    0000      |   8     8    1000
//   1     1    0001      |   9     9    1001
//   2     2    0010      |  10     A    1010
//   3     3    0011      |  11     B    1011
//   4     4    0100      |  12     C    1100
//   5     5    0101      |  13     D    1101
//   6     6    0110      |  14     E    1110
//   7     7    0111      |  15     F    1111


// ============================================================
// 1. BASIC BIT OPERATIONS
//    Get | Set | Clear | Toggle | Update  (bit at position k)
//    k is 0-indexed from the RIGHT  (k=0 is the Least Significant Bit)
// ============================================================
// Intuition — the MASK pattern:
//   mask = 1 << k    →  a 1 at position k, 0s everywhere else
//
//   GET    : shift n right k positions, check LSB   O(1)
//   SET    : OR  with mask  (forces bit-k to 1)    O(1)
//   CLEAR  : AND with ~mask (forces bit-k to 0)    O(1)
//   TOGGLE : XOR with mask  (flips bit-k)           O(1)
//   UPDATE : CLEAR first, then OR with new value   O(1)
//
// Dry Run: n = 6 = 0110₂,  k = 1
//   mask = 1 << 1 = 0010
//   GET    : (0110 >> 1) & 1 = 011 & 001 = 1          (bit-1 of 6 is 1)   ✓
//   SET    : 0110 | 0010    = 0110 = 6                 (already 1, no change) ✓
//   CLEAR  : 0110 & ~0010   = 0110 & 1101 = 0100 = 4  (bit-1 forced to 0)  ✓
//   TOGGLE : 0110 ^ 0010    = 0100 = 4                 (1 flipped to 0)     ✓
//   UPDATE(k=1, bit=0): clearBit(6,1) | (0<<1) = 4 | 0 = 4                ✓
//
// TC: O(1)  SC: O(1) — single CPU instruction each
// ----------------------------------------------------------

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


// ============================================================
// 2. CHECK ODD OR EVEN
// ============================================================
// Intuition:
//   The Least Significant Bit (bit-0) determines odd/even.
//   All ODD numbers end in 1 in binary.  All EVEN numbers end in 0.
//     n & 1 == 1  →  n is ODD
//     n & 1 == 0  →  n is EVEN
//
// Dry Run:
//   n=7 (0111): 0111 & 0001 = 1  → ODD   ✓
//   n=8 (1000): 1000 & 0001 = 0  → EVEN  ✓
//
// TC: O(1)  SC: O(1)
// ----------------------------------------------------------
function isOdd(int $n): bool
{
    return ($n & 1) === 1;
}


// ============================================================
// 3. REVERSE A NUMBER (Digit Reversal, Not Bit Reversal)
// ============================================================
// Intuition:
//   Extract the LAST decimal digit with (n % 10).
//   Append it to result: result = result*10 + lastDigit.
//   Remove the last digit: n = floor(n / 10).
//   Repeat until n becomes 0.
//
// Dry Run: n = 1234
//   Iter 1: last=4, rev=0*10+4=4,     n=123
//   Iter 2: last=3, rev=4*10+3=43,    n=12
//   Iter 3: last=2, rev=43*10+2=432,  n=1
//   Iter 4: last=1, rev=432*10+1=4321, n=0 → STOP
//   Result: 4321  ✓
//
// TC: O(d) where d = number of decimal digits  SC: O(1)
// ----------------------------------------------------------
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


// ============================================================
// 4. DECIMAL TO BINARY (Manual Conversion)
// ============================================================
// Intuition:
//   Repeatedly divide n by 2.
//   The remainder (0 or 1) at each step = next bit from the RIGHT.
//   PREPEND each remainder to build the binary string.
//
// Dry Run: n = 13
//   13 ÷ 2 = 6  rem 1  → "1"
//    6 ÷ 2 = 3  rem 0  → "01"
//    3 ÷ 2 = 1  rem 1  → "101"
//    1 ÷ 2 = 0  rem 1  → "1101"
//   Result: "1101"  (13 = 8+4+1)  ✓
//
// TC: O(log N)  SC: O(log N) — string of log₂(N) characters
// ----------------------------------------------------------
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


// ============================================================
// 5. BINARY TO DECIMAL (Manual Conversion)
// ============================================================
// Intuition:
//   Each bit at position i (0-indexed from right) contributes bit × 2^i.
//   Process the binary string from RIGHTMOST character backwards.
//
// Dry Run: bin = "1101"
//   pos=0 (char '1'): decimal +=  1×1  = 1,  pow=2
//   pos=1 (char '0'): decimal +=  0×2  = 1,  pow=4
//   pos=2 (char '1'): decimal +=  1×4  = 5,  pow=8
//   pos=3 (char '1'): decimal +=  1×8  = 13, pow=16
//   Result: 13  (1101₂ = 8+4+1 = 13)  ✓
//
// TC: O(d) where d = length of binary string  SC: O(1)
// ----------------------------------------------------------
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


// ============================================================
// 6. SWAP TWO NUMBERS — XOR Trick (No Temporary Variable)
// ============================================================
// Intuition:
//   XOR properties: a^a=0  and  a^0=a
//   Step 1: a = a ^ b           (a now encodes both values)
//   Step 2: b = a ^ b = (a^b)^b = a   (b becomes original a)
//   Step 3: a = a ^ b = (a^b)^a = b   (a becomes original b)
//
// ⚠ Guard: if $a === $b (same variable or same value), three XORs
//   make it zero. Always guard before using.
//
// Dry Run: a=5 (101), b=3 (011)
//   Step 1: a = 101^011 = 110 (=6)
//   Step 2: b = 110^011 = 101 (=5)  ← original a  ✓
//   Step 3: a = 110^101 = 011 (=3)  ← original b  ✓
//
// TC: O(1)  SC: O(1)
// ----------------------------------------------------------
function swapXOR(int &$a, int &$b): void
{
    if ($a === $b) return;  // Guard: same value → all three XORs give 0
    $a = $a ^ $b;           // Step 1: a holds XOR of both
    $b = $a ^ $b;           // Step 2: b = (a^b)^b = original a
    $a = $a ^ $b;           // Step 3: a = (a^b)^a = original b
}


// ============================================================
// 7. REMOVE RIGHTMOST SET BIT  —  n & (n−1)
// ============================================================
// Intuition:
//   n−1  flips the rightmost 1-bit AND all 0-bits to its right.
//   n & (n−1) cancels exactly those changed bits → rightmost 1 removed.
//
// Dry Run: n = 12 = 1100₂
//   n−1 = 11 = 1011₂
//   n & (n-1) = 1100 & 1011 = 1000 = 8   (rightmost set bit removed)  ✓
//
//   n = 6 = 0110₂
//   n−1 = 5 = 0101₂
//   n & (n-1) = 0110 & 0101 = 0100 = 4   (rightmost set bit removed)  ✓
//
// TC: O(1)  SC: O(1)
// ----------------------------------------------------------
function removeRightmostSetBit(int $n): int
{
    return $n & ($n - 1);
}


// ============================================================
// 8. CHECK IF NUMBER IS A POWER OF 2
// ============================================================
// Intuition:
//   Powers of 2 have EXACTLY ONE set bit: 1=0001, 2=0010, 4=0100 ...
//   n & (n−1) removes the only set bit → result is 0.
//   Guard: n must be > 0  (0 is NOT a power of 2).
//
// Dry Run: n=8 (1000): 8 & 7 = 1000 & 0111 = 0000 = 0  → true   ✓
//          n=6 (0110): 6 & 5 = 0110 & 0101 = 0100 ≠ 0  → false  ✓
//          n=0       : guard 0 > 0 fails               → false  ✓
//
// TC: O(1)  SC: O(1)
// ----------------------------------------------------------
function isPowerOf2(int $n): bool
{
    return $n > 0 && ($n & ($n - 1)) === 0;
}


// ============================================================
// 9. COUNT SET BITS (Number of 1s in Binary Representation)
// ============================================================
// APPROACH 1 — Right-Shift Method (O(log N)):
//   Check the LSB with (n & 1), add it to count.
//   Right-shift n by 1 (= divide by 2) to process the next bit.
//   Repeat until n == 0.
//
// Dry Run: n = 13 = 1101₂
//   13 & 1 = 1, count=1, n = 0110 = 6
//    6 & 1 = 0, count=1, n = 0011 = 3
//    3 & 1 = 1, count=2, n = 0001 = 1
//    1 & 1 = 1, count=3, n = 0000 = 0 → STOP
//   Result: 3 set bits  ✓
//
// TC: O(log N)  SC: O(1)
//
// APPROACH 2 — Brian Kernighan's Algorithm (O(K)):
//   n & (n−1) removes the RIGHTMOST set bit in one operation.
//   Count how many times we can do this before n reaches 0.
//   K iterations = K set bits. Faster when K << log N.
//
// Dry Run: n = 13 = 1101₂
//   Iter 1: 1101 & 1100 = 1100 = 12, count=1
//   Iter 2: 1100 & 1011 = 1000 =  8, count=2
//   Iter 3: 1000 & 0111 = 0000 =  0, count=3 → STOP
//   Result: 3 set bits  ✓
//
// TC: O(K) where K = number of set bits  SC: O(1)
// ----------------------------------------------------------

// Approach 1: Right-shift — iterates log₂(N) times
function countSetBitsShift(int $n): int
{
    $count = 0;
    while ($n > 0) {
        $count += $n & 1;   // Add 1 if the least significant bit is set
        $n     >>= 1;       // Shift right by 1 (divide by 2)
    }
    return $count;
}

// Approach 2: Brian Kernighan's — iterates only K times (number of set bits)
function countSetBitsBK(int $n): int
{
    $count = 0;
    while ($n > 0) {
        $n &= ($n - 1);     // Remove the rightmost set bit in one step
        $count++;
    }
    return $count;
}


// ============================================================
// 10. CHECK K-TH BIT (0-indexed from right)
// ============================================================
// Intuition:
//   Shift n RIGHT by k positions → bit-k lands at position 0.
//   Mask with 1 to isolate that single bit.
//   Result: 1 if bit-k is set, 0 if not.
//
// Dry Run: n = 13 = 1101₂
//   k=2: (13 >> 2) = 0011; 0011 & 0001 = 1  → bit-2 is SET    ✓
//   k=1: (13 >> 1) = 0110; 0110 & 0001 = 0  → bit-1 is NOT set ✓
//   (13 = 1101₂: bit0=1, bit1=0, bit2=1, bit3=1)
//
// ⚠ Common Bug: ($n & ($k << 1)) is WRONG.
//   It shifts the KEY $k, not a 1-bit mask.
//   Correct formula: ($n >> $k) & 1   OR   ($n & (1 << $k)) != 0
//
// TC: O(1)  SC: O(1)
// ----------------------------------------------------------
function checkKthBit(int $n, int $k): bool
{
    return (($n >> $k) & 1) === 1; // Shift n to bring bit-k to LSB, then mask
}


// ============================================================
// 11. COUNT BITS TO FLIP A → B  (LeetCode 2220)
// ============================================================
// Intuition:
//   A XOR B produces a 1 wherever A and B DIFFER.
//   Each 1-bit in (A ^ B) is one flip needed.
//   Count the 1-bits in (A ^ B) using Brian Kernighan's algorithm.
//
// Dry Run: A = 10 = 1010₂,  B = 7 = 0111₂
//   A ^ B = 1010 ^ 0111 = 1101 = 13   (bits that differ)
//   Count set bits in 1101: 3 flips needed  ✓
//
// TC: O(K) where K = differing bits  SC: O(1)
// ----------------------------------------------------------
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


// ============================================================
// 12. XOR FROM 1 TO N — O(1) PATTERN TRICK
// ============================================================
// Intuition:
//   XOR(1..N) follows a 4-step repeating cycle based on N % 4:
//     N % 4 == 0  →  N
//     N % 4 == 1  →  1
//     N % 4 == 2  →  N + 1
//     N % 4 == 3  →  0
//
//   Verify N=1..8:
//     XOR(1)    = 1      N%4=1 → 1      ✓
//     XOR(1..2) = 3      N%4=2 → N+1=3  ✓
//     XOR(1..3) = 0      N%4=3 → 0      ✓
//     XOR(1..4) = 4      N%4=0 → 4      ✓
//     XOR(1..5) = 1      N%4=1 → 1      ✓
//     XOR(1..6) = 7      N%4=2 → 7      ✓
//     XOR(1..7) = 0      N%4=3 → 0      ✓
//     XOR(1..8) = 8      N%4=0 → 8      ✓
//
// TC: O(1)  SC: O(1)
// ----------------------------------------------------------
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


// ============================================================
// 13. XOR FROM L TO R
// ============================================================
// Intuition:
//   XOR(L..R) = XOR(1..R) ^ XOR(1..L-1)
//   All terms from 1 to L-1 cancel: they appear in both halves.
//   Use the O(1) xorOneTo() function for each half.
//
// Dry Run: L=3, R=7
//   xorOneTo(7) = 7%4=3  → 0
//   xorOneTo(2) = 2%4=2  → 3
//   XOR(3..7) = 0 ^ 3 = 3
//   Manual: 3^4^5^6^7 = 7^5^6^7 = 5^6 = 3  ✓
//
// TC: O(1)  SC: O(1)
// ----------------------------------------------------------
function xorLtoR(int $l, int $r): int
{
    return xorOneTo($r) ^ xorOneTo($l - 1);
}


// ============================================================
// 14. POSITION OF THE ONLY SET BIT (1-indexed from right)
// ============================================================
// Intuition:
//   First verify n is a power of 2 (exactly one set bit).
//   If not → return -1 (undefined — multiple set bits).
//   Then shift a mask left until it matches the set bit.
//   Count shifts → that count is the bit position.
//
// Dry Run: n = 8 = 1000₂
//   isPowerOf2(8) → true
//   mask=1 (0001): 0001 & 1000 = 0, shift. pos=2
//   mask=2 (0010): 0010 & 1000 = 0, shift. pos=3
//   mask=4 (0100): 0100 & 1000 = 0, shift. pos=4
//   mask=8 (1000): 1000 & 1000 ≠ 0 → return 4  ✓
//
// TC: O(log N)  SC: O(1)
// ----------------------------------------------------------
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


// ============================================================
// 15. SINGLE NUMBER  (LeetCode 136)
//     Every element appears TWICE except one unique element.
// ============================================================
// Intuition:
//   XOR property: a ^ a = 0  and  a ^ 0 = a.
//   XOR ALL elements together: every pair cancels (=0),
//   only the unique element survives.
//
// Dry Run: nums = [1, 2, 3, 1, 2]
//   0 ^ 1 = 1
//   1 ^ 2 = 3
//   3 ^ 3 = 0
//   0 ^ 1 = 1
//   1 ^ 2 = 3  ← result  ✓
//
// TC: O(N)  SC: O(1) — no HashMap needed
// ----------------------------------------------------------
function singleNumber(array $nums): int
{
    $result = 0;
    foreach ($nums as $num) {
        $result ^= $num;    // Pairs cancel (a^a=0); unique element survives
    }
    return $result;
}


// ============================================================
// 16. TWO NON-REPEATING ELEMENTS  (LeetCode 260 — Single Number III)
//     Every element appears TWICE except two unique elements x and y.
// ============================================================
// Intuition:
//   Step 1: XOR all elements.
//           Pairs cancel → xorAll = x ^ y  (≠ 0 because x ≠ y).
//
//   Step 2: Find the RIGHTMOST SET BIT of xorAll.
//           This bit DIFFERS between x and y (one has 1, other has 0).
//           Formula: n & (-n)  isolates the rightmost set bit.
//           (Two's complement: -n = ~n+1; the lowest set bit is preserved.)
//
//   Step 3: Divide all elements into two groups by that bit:
//             Group A → elements with this bit SET
//             Group B → elements with this bit NOT SET
//           XOR within each group cancels all pairs →
//             Group A XOR = x  (or y)
//             Group B XOR = y  (or x)
//
// Dry Run: nums = [1, 2, 1, 3, 2, 5]  (unique: 3 and 5)
//   Step 1: xorAll = 1^2^1^3^2^5 = 3^5 = 011^101 = 110 = 6
//   Step 2: rightmostBit = 6 & (-6) = 0110 & ...1010 = 0010 = 2
//           (bit-1 differs: 3=011 has bit-1=1, 5=101 has bit-1=0)
//   Step 3: Group with bit-1 set   : {2,3,2} → x = 0^2^3^2 = 3  ✓
//           Group without bit-1 set: {1,1,5} → y = 0^1^1^5 = 5  ✓
//   Result: [3, 5]  ✓
//
// TC: O(N)  SC: O(1)
// ----------------------------------------------------------
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


// ============================================================
// 17. GENERATE ALL SUBSETS — BIT MASKING  (LeetCode 78)
// ============================================================
// Intuition:
//   An N-element set has 2^N subsets (each element is IN or OUT).
//   Use numbers 0 to 2^N−1 as BITMASKS.
//   Bit j of mask i = 1  →  include arr[j] in this subset.
//   Bit j of mask i = 0  →  exclude arr[j].
//
//   Example: arr=[a,b,c], N=3, masks 0..7:
//     000={},  001={a},  010={b},  011={a,b}
//     100={c}, 101={a,c},110={b,c},111={a,b,c}
//
// Dry Run: arr=[1,2,3], mask=5 (101₂)
//   j=0: 101 & (1<<0) = 101 & 001 = 1 (set) → include arr[0]=1
//   j=1: 101 & (1<<1) = 101 & 010 = 0       → skip arr[1]
//   j=2: 101 & (1<<2) = 101 & 100 = 4 (set) → include arr[2]=3
//   Subset for mask=5: {1, 3}  ✓
//
// TC: O(N × 2^N)  SC: O(N × 2^N) — 2^N subsets each up to N elements
// ----------------------------------------------------------
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


// ============================================================
// 18. DIVIDE TWO INTEGERS WITHOUT *, /  (LeetCode 29)
// ============================================================
// Intuition:
//   Simulate division using BIT SHIFTS (powers of 2).
//   Find the largest k such that  divisor × 2^k ≤ dividend.
//   Add 2^k to the answer, subtract (divisor × 2^k) from dividend.
//   Repeat until dividend < divisor.
//
//   Why bit shifts? They double in O(1) instead of subtracting
//   one-by-one (which would be O(N) per remainder).
//
// Dry Run: dividend=22, divisor=3
//   Pass 1: 3<<1=6, 3<<2=12, 3<<3=24>22 → k=2
//           ans += 4 (2^2), n = 22-12 = 10
//   Pass 2: 3<<1=6, 3<<2=12>10 → k=1
//           ans += 2 (2^1), n = 10-6 = 4
//   Pass 3: 3<<1=6>4 → k=0
//           ans += 1 (2^0), n = 4-3 = 1
//   n=1 < d=3 → STOP.  ans = 4+2+1 = 7  ✓
//
// TC: O(log² N)  SC: O(1)
// ----------------------------------------------------------
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


// ============================================================
// 19. SQUARE WITHOUT *, /, pow() — BIT SHIFTING
// ============================================================
// Intuition:
//   Split n into h = n >> 1  (= floor(n/2)).
//   Use the algebraic identity with LEFT SHIFTS (× 4 = << 2):
//
//   If n is EVEN: n = 2h  → n² = 4h²      = squareBits(h) << 2
//   If n is ODD : n = 2h+1 → n² = 4h²+4h+1 = (squareBits(h) << 2) + (h << 2) + 1
//
// Dry Run: n=5, h=2 (ODD)
//   square(5) = 4×square(2) + 4×2 + 1
//   square(2): h=1, EVEN → 4×square(1)
//   square(1): h=0, ODD  → 4×square(0) + 0 + 1 = 0+0+1 = 1
//   square(2): 4×1 = 4
//   square(5): 4×4 + 8 + 1 = 25  ✓
//
// TC: O(log N) — log₂ recursive calls  SC: O(log N) — recursion stack
// ----------------------------------------------------------
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


// ============================================================
// 20. COUNT TOTAL SET BITS FROM 1 TO N
// ============================================================
// Intuition:
//   For each number i from 1 to N, count its set bits using
//   Brian Kernighan's algorithm and accumulate the total.
//
// Dry Run: N = 4
//   i=1 (0001): 1 set bit  → total=1
//   i=2 (0010): 1 set bit  → total=2
//   i=3 (0011): 2 set bits → total=4
//   i=4 (0100): 1 set bit  → total=5
//   Result: 5  ✓
//
// TC: O(N log N)  SC: O(1)
// (An O(log N) formula exists using bit-position patterns — O(log² N))
// ----------------------------------------------------------
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


// ============================================================
// 21. COPY SET BITS IN A RANGE (from Y into X, positions l to r)
// ============================================================
// Intuition:
//   l and r are 1-indexed bit positions (1 = rightmost bit).
//   For each position i from l to r:
//     If Y has a 1-bit at position i → set that bit in X as well.
//   Uses a mask for each position: mask = 1 << (i-1).
//
// Dry Run: x=8 (1000), y=7 (0111), l=1, r=2
//   i=1: mask=1 (0001). y & 1 = 1 (set) → x = 8 | 1 = 9  (1001)
//   i=2: mask=2 (0010). y & 2 = 2 (set) → x = 9 | 2 = 11 (1011)
//   Result: 11  ✓  (bits 1 and 2 of y were both 1, now copied into x)
//
// TC: O(r − l + 1)  SC: O(1)
// ----------------------------------------------------------
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


// ============================================================
// DEMO — Run All Operations
// ============================================================

echo "=== 1. Basic Bit Operations (n=6=0110₂, k=1) ===\n";
echo "getBit(6, 1):        " . getBit(6, 1)        . "\n"; // 1
echo "setBit(6, 1):        " . setBit(6, 1)        . "\n"; // 6
echo "clearBit(6, 1):      " . clearBit(6, 1)      . "\n"; // 4
echo "toggleBit(6, 1):     " . toggleBit(6, 1)     . "\n"; // 4
echo "updateBit(6,1, 0):   " . updateBit(6, 1, 0)  . "\n"; // 4
echo "updateBit(6,1, 1):   " . updateBit(6, 1, 1)  . "\n"; // 6

echo "\n=== 2. Odd / Even Check ===\n";
echo "isOdd(7): " . (isOdd(7) ? 'true' : 'false') . "\n"; // true
echo "isOdd(8): " . (isOdd(8) ? 'true' : 'false') . "\n"; // false

echo "\n=== 3. Reverse Number ===\n";
echo "reverseNumber(1234):  " . reverseNumber(1234) . "\n"; // 4321
echo "reverseNumber(-560):  " . reverseNumber(-560)  . "\n"; // -65

echo "\n=== 4. Decimal → Binary ===\n";
echo "decToBin(13): " . decToBin(13) . "\n"; // 1101
echo "decToBin(45): " . decToBin(45) . "\n"; // 101101

echo "\n=== 5. Binary → Decimal ===\n";
echo "binToDec('1101'):   " . binToDec('1101')   . "\n"; // 13
echo "binToDec('101101'): " . binToDec('101101') . "\n"; // 45

echo "\n=== 6. Swap using XOR ===\n";
$a = 5; $b = 3;
swapXOR($a, $b);
echo "swap(5, 3): a=$a, b=$b\n"; // a=3, b=5

echo "\n=== 7. Remove Rightmost Set Bit ===\n";
echo "removeRightmostSetBit(12): " . removeRightmostSetBit(12) . "\n"; // 8
echo "removeRightmostSetBit(6):  " . removeRightmostSetBit(6)  . "\n"; // 4

echo "\n=== 8. Power of 2 Check ===\n";
echo "isPowerOf2(8): " . (isPowerOf2(8) ? 'true' : 'false') . "\n"; // true
echo "isPowerOf2(6): " . (isPowerOf2(6) ? 'true' : 'false') . "\n"; // false
echo "isPowerOf2(0): " . (isPowerOf2(0) ? 'true' : 'false') . "\n"; // false

echo "\n=== 9. Count Set Bits (n=13=1101₂, 3 set bits) ===\n";
echo "countSetBitsShift(13): " . countSetBitsShift(13) . "\n"; // 3
echo "countSetBitsBK(13):    " . countSetBitsBK(13)    . "\n"; // 3

echo "\n=== 10. Check K-th Bit (n=13=1101₂) ===\n";
echo "checkKthBit(13, 0): " . (checkKthBit(13, 0) ? 'true' : 'false') . "\n"; // true  (bit0=1)
echo "checkKthBit(13, 1): " . (checkKthBit(13, 1) ? 'true' : 'false') . "\n"; // false (bit1=0)
echo "checkKthBit(13, 2): " . (checkKthBit(13, 2) ? 'true' : 'false') . "\n"; // true  (bit2=1)

echo "\n=== 11. Count Bits to Flip A→B ===\n";
echo "countBitsToFlip(10, 7): " . countBitsToFlip(10, 7) . "\n"; // 3

echo "\n=== 12. XOR 1 to N ===\n";
echo "xorOneTo(5): " . xorOneTo(5) . "\n"; // 1
echo "xorOneTo(6): " . xorOneTo(6) . "\n"; // 7
echo "xorOneTo(7): " . xorOneTo(7) . "\n"; // 0

echo "\n=== 13. XOR L to R ===\n";
echo "xorLtoR(3, 7): " . xorLtoR(3, 7) . "\n"; // 3

echo "\n=== 14. Position of Only Set Bit ===\n";
echo "positionOfOnlySetBit(8):  " . positionOfOnlySetBit(8)  . "\n"; // 4
echo "positionOfOnlySetBit(16): " . positionOfOnlySetBit(16) . "\n"; // 5
echo "positionOfOnlySetBit(6):  " . positionOfOnlySetBit(6)  . "\n"; // -1 (not power of 2)

echo "\n=== 15. Single Number (LC 136) ===\n";
echo "singleNumber([1,2,3,1,2]): " . singleNumber([1, 2, 3, 1, 2]) . "\n"; // 3

echo "\n=== 16. Two Non-Repeating Elements (LC 260) ===\n";
$pair = twoSingleNumbers([1, 2, 1, 3, 2, 5]);
echo "twoSingleNumbers([1,2,1,3,2,5]): [" . $pair[0] . ", " . $pair[1] . "]\n"; // [3, 5]

echo "\n=== 17. All Subsets (LC 78) ===\n";
$subsets = getAllSubsets([1, 2, 3]);
echo "getAllSubsets([1,2,3]): " . count($subsets) . " subsets\n"; // 8 subsets
foreach ($subsets as $s) {
    echo "  {" . (empty($s) ? '' : implode(', ', $s)) . "}\n";
}

echo "\n=== 18. Divide Without * or / (LC 29) ===\n";
echo "divide(22, 3):    " . divide(22, 3)    . "\n"; // 7
echo "divide(-10, 2):   " . divide(-10, 2)   . "\n"; // -5
echo "divide(7, -2):    " . divide(7, -2)    . "\n"; // -3

echo "\n=== 19. Square Without * / pow() ===\n";
echo "squareBits(5): " . squareBits(5) . "\n"; // 25
echo "squareBits(9): " . squareBits(9) . "\n"; // 81

echo "\n=== 20. Count Total Set Bits 1 to N ===\n";
echo "countTotalSetBits(4): " . countTotalSetBits(4) . "\n"; // 5
echo "countTotalSetBits(7): " . countTotalSetBits(7) . "\n"; // 12

echo "\n=== 21. Copy Set Bits in Range ===\n";
echo "copySetBitsInRange(8, 7, 1, 2): " . copySetBitsInRange(8, 7, 1, 2) . "\n"; // 11


// ============================================================
// COMPARISON SUMMARY
// ============================================================
//
//  Operation                       | Formula / Method           | TC           | SC
// ---------------------------------+----------------------------+--------------+------
//  Get k-th bit                    | (n >> k) & 1               | O(1)         | O(1)
//  Set k-th bit                    | n | (1 << k)               | O(1)         | O(1)
//  Clear k-th bit                  | n & ~(1 << k)              | O(1)         | O(1)
//  Toggle k-th bit                 | n ^ (1 << k)               | O(1)         | O(1)
//  Check odd/even                  | n & 1                      | O(1)         | O(1)
//  Remove rightmost set bit        | n & (n-1)                  | O(1)         | O(1)
//  Isolate rightmost set bit       | n & (-n)                   | O(1)         | O(1)
//  Check power of 2                | n>0 && (n&(n-1))==0        | O(1)         | O(1)
//  XOR from 1 to N                 | 4-cycle pattern (switch)   | O(1)         | O(1)
//  XOR from L to R                 | xorOneTo(R) ^ xorOneTo(L-1)| O(1)         | O(1)
//  Swap (XOR)                      | 3 XOR steps                | O(1)         | O(1)
//  Count set bits (shift)          | right-shift loop           | O(log N)     | O(1)
//  Count set bits (Brian Kernighan)| n & (n-1) loop             | O(K)         | O(1)
//  Position of only set bit        | shift mask until match     | O(log N)     | O(1)
//  Bits to flip A→B                | count bits in A^B          | O(K)         | O(1)
//  Decimal ↔ Binary                | divide/multiply by 2       | O(log N)     | O(log N)
//  Square (bit shift)              | recursive halving          | O(log N)     | O(log N)
//  Generate all subsets            | 0 to 2^N-1 bitmasks        | O(N × 2^N)   | O(N × 2^N)
//  Single Number                   | XOR all                    | O(N)         | O(1)
//  Two Non-Repeating Elements      | XOR + rightmost set bit    | O(N)         | O(1)
//  Divide without * /              | bit-shift subtraction      | O(log² N)    | O(1)
//  Count total set bits 1..N       | BK for each number         | O(N log N)   | O(1)
//  Copy set bits in range          | mask loop                  | O(r-l+1)     | O(1)


// ============================================================
// PRACTICE PROBLEMS & APPLICATIONS
// ============================================================
//
//  EASY
//  1. Number of 1 Bits / Hamming Weight (LeetCode 191)
//     → countSetBitsBK(n): Brian Kernighan's O(K)
//  2. Power of Two (LeetCode 231)
//     → isPowerOf2(n): one-liner O(1)
//  3. Reverse Bits (LeetCode 190)
//     → Process bit by bit: res = (res << 1) | (n & 1), n >>= 1
//  4. Missing Number (LeetCode 268) — array [0..N] with one missing
//     → XOR indices 0..N with all array values; missing one survives
//  5. Single Number (LeetCode 136)
//     → XOR all elements; pairs cancel
//  6. Minimum Bit Flips to Convert Number (LeetCode 2220)
//     → countBitsToFlip(start, goal) = count set bits in (start ^ goal)
//
//  MEDIUM
//  7. Single Number II (LeetCode 137)
//     → Each element appears 3 times except one.
//     → Use ones/twos bitmask variables to count set bit appearances mod 3
//  8. Single Number III (LeetCode 260)
//     → twoSingleNumbers() — XOR + rightmost set bit partitioning
//  9. Subsets (LeetCode 78)
//     → getAllSubsets() — bit masking over 0..2^N−1
// 10. Sum of Two Integers Without +/- (LeetCode 371)
//     → Carry = a & b, Sum = a ^ b; repeat until carry is 0
// 11. Counting Bits (LeetCode 338)
//     → For each i from 0..N: ans[i] = ans[i >> 1] + (i & 1) (DP)
// 12. Find the Duplicate Number using XOR
//     → XOR array values with XOR of expected indices; extra survives
// 13. Divide Two Integers (LeetCode 29)
//     → Bit-shift based subtraction; O(log² N)
//
//  HARD
// 14. Maximum XOR of Two Numbers in an Array (LeetCode 421)
//     → Build a Trie of binary representations; greedy pick of opposite bit
// 15. Maximum XOR with an Element from Array (LeetCode 1707)
//     → Sort queries offline + Trie; O((N+Q) log N)
// 16. XOR Queries of a Subarray (LeetCode 1310)
//     → Prefix XOR array: xor(L..R) = prefix[R] ^ prefix[L-1]


// ============================================================
// KEY PATTERNS & VARIATIONS FOR REVISION
// ============================================================
//
//  PATTERN 1 — XOR Cancels Equal Pairs:
//    a ^ a = 0,  a ^ 0 = a.
//    XOR all elements → duplicates cancel, unique element survives.
//    Used in: Single Number, Missing Number, Swap without temp variable.
//    Extension: Partition array by a distinguishing bit → isolate TWO unique.
//
//  PATTERN 2 — n & (n-1) Removes Rightmost Set Bit:
//    Counts set bits in O(K) — loop terminates in K iterations.
//    Checks power of 2 in O(1) — one set bit → n & (n-1) == 0.
//    Any problem counting "how many times can we remove the lowest 1-bit?"
//    maps to Brian Kernighan's algorithm.
//
//  PATTERN 3 — n & (-n) Isolates Rightmost Set Bit:
//    Two's complement: -n = ~n + 1.
//    The only bit both n and -n share is the rightmost set bit.
//    Used in: Two Non-Repeating Elements (partition by differing bit),
//             LRU cache, Fenwick (Binary Indexed) Tree operations.
//
//  PATTERN 4 — Bit Masking for Subsets / Combinations:
//    Represent each subset as an integer 0..2^N−1.
//    Bit j of mask = is arr[j] included?
//    Used in: Subsets (LC 78), Combination Sum (bitmask DP),
//             Travelling Salesman Problem (bitmask DP over visited cities).
//
//  PATTERN 5 — Prefix XOR for Range Queries:
//    prefix[i] = XOR of arr[0..i].
//    XOR(L..R) = prefix[R] ^ prefix[L-1].
//    Reduces O(N) per query to O(1) after O(N) preprocessing.
//    Used in: XOR Queries of a Subarray (LC 1310).
//
//  PATTERN 6 — Left/Right Shift = Multiply/Divide by Power of 2:
//    n << k = n × 2^k  (no multiplication operator needed).
//    n >> k = n ÷ 2^k  (no division operator needed).
//    Used in: Divide Two Integers, Square without operators,
//             generating mask (1 << k) for any bit position.
//
//  PATTERN 7 — XOR(1..N) Four-Cycle Pattern:
//    N%4==0→N, N%4==1→1, N%4==2→N+1, N%4==3→0.
//    Reduces O(N) XOR computation to O(1).
//    Combine two calls: XOR(L..R) = xorOneTo(R) ^ xorOneTo(L-1).


// ============================================================
// IMPORTANT TIPS & EDGE CASES
// ============================================================
//
//  1. PHP operator precedence trap — ALWAYS use explicit parentheses:
//     $n & $n-1  is  $n & ($n-1)   in PHP  (subtraction before &) ← OK here
//     But ($n & $n-1) ^ $n  is  (($n & $n-1)) ^ $n  — easily misread.
//     Use  $n & (-$n)  for rightmost set bit isolation — it's unambiguous.
//
//  2. countSetBits dead-code trap:
//     Two while-loops with the SAME variable $n are a common mistake.
//     After the first loop, $n == 0. The second loop never runs.
//     Fix: use two SEPARATE functions (as done here) or reset $n.
//
//  3. PHP uses 64-bit integers on 64-bit systems (PHP_INT_SIZE = 8).
//     For LeetCode 32-bit constraints, manually clamp output to
//     [-2147483648, 2147483647] using min/max.
//     The ~  operator in PHP flips ALL 64 bits, not just 32.
//
//  4. Swap XOR same-value guard:
//     If $a and $b are passed by reference AND point to the same
//     variable, or both hold the same value, three XORs zero out.
//     Always guard with: if ($a === $b) return.
//
//  5. n & (-n) works correctly in PHP's 64-bit two's complement:
//     For n=6 (0…0110): -6 = 1…1010. 6 & -6 = 0…0010 = 2. ✓
//     This is safe and preferred over ($n & ($n-1)) ^ $n for clarity.
//
//  6. checkKthBit correct formula:
//     ✓  ($n >> $k) & 1         or   ($n & (1 << $k)) !== 0
//     ✗  ($n & ($k << 1))       — shifts the KEY, not a 1-bit mask
//     The wrong formula works accidentally for k=1,2 but fails for k≥3.
//
//  7. XOR is NOT signed-safe for comparison:
//     Use identity checks ($a === $b) not XOR to check equality of objects.
//     XOR is best for integer arithmetic — don't apply it to floats.
//
//  8. All Subsets memory warning:
//     For N=20: 2^20 = 1,048,576 subsets.
//     For N=30: 2^30 ≈ 1 billion — will run out of memory.
//     Always check N before using the O(N × 2^N) bitmask approach.

?>
