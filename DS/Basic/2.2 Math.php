<?php

/*
|==========================================================================
| 2. MATH - DSA Preparation
|==========================================================================
|
| Topics Covered:
|   1. Count Digits in a Number
|   2. Reverse a Number
|   3. Check Armstrong Number
|   4. Print All Divisors
|   5. Check Prime Number
|   6. Find GCD (HCF) of Two Numbers
|   7. Find Unique Prime Factors
|   8. Prime Factorization using Sieve
|
*/


/*
|--------------------------------------------------------------------------
| 1. COUNT DIGITS IN A NUMBER
|--------------------------------------------------------------------------
|
| Problem : Given a number N, count how many digits it has.
| Example : N = 7789  => Output: 4 (digits: 7, 7, 8, 9)
|           N = 100   => Output: 3 (digits: 1, 0, 0)
|
*/

// ==================== Approach 1: Loop Division (Brute Force) ====================
// Time: O(log10(N)) - number of digits = log10(N), so we loop that many times
// Space: O(1)
//
// Idea: Keep dividing by 10 until number becomes 0.
//       Each division removes one digit from the right.
//
// Dry Run: N = 7789
// ┌──────────┬──────────┬────────┐
// │ Iteration│  x       │ digits │
// ├──────────┼──────────┼────────┤
// │ start    │ 7789     │ 0      │
// │ 1        │ 778      │ 1      │  (7789 / 10 = 778)
// │ 2        │ 77       │ 2      │  (778 / 10 = 77)
// │ 3        │ 7        │ 3      │  (77 / 10 = 7)
// │ 4        │ 0        │ 4      │  (7 / 10 = 0) → loop ends
// └──────────┴──────────┴────────┘
// Result: 4

function countDigits($n) {
    $x = $n;
    $digits = 0;
    while ((int)$x != 0) {
        $x = (int)($x / 10);  // Remove last digit
        $digits++;             // Count each removal
    }
    return $digits;
}


// ==================== Approach 2: String Conversion ====================
// Time: O(1) - strlen is O(1) in PHP for stored length
// Space: O(1)
//
// Idea: Convert number to string, return its length.
// Example: 7789 → "7789" → strlen = 4

function countDigitsString($n) {
    $n = (string)$n;
    return strlen($n);
}


// ==================== Approach 3: Logarithmic (Optimal) ====================
// Time: O(1)
// Space: O(1)
//
// Idea: Number of digits = floor(log10(N)) + 1
//       log10(7789) = 3.891... → floor = 3 → +1 = 4
//       log10(100)  = 2.0      → floor = 2 → +1 = 3
//       log10(9)    = 0.954... → floor = 0 → +1 = 1
//
// Note: Does NOT work for N = 0 (log10(0) is undefined)

function countDigitsLog($n) {
    return ((int)log10($n)) + 1;
}


/*
|--------------------------------------------------------------------------
| 2. REVERSE A NUMBER
|--------------------------------------------------------------------------
|
| Problem : Given a number N, reverse its digits.
| Example : N = 1234  => Output: 4321
|           N = -123  => Output: -321
|           N = 100   => Output: 1
|
*/

// ==================== Approach 1: Basic Reversal ====================
// Time: O(log10(N)) - processes each digit once
// Space: O(1)
//
// Idea: Extract last digit using % 10, build reversed number by
//       multiplying result by 10 and adding the digit.
//
// Dry Run: N = 1234
// ┌──────────┬──────────┬───────────┬────────────────┐
// │ Iteration│ lastDigit│ n         │ reverseNumber   │
// ├──────────┼──────────┼───────────┼────────────────┤
// │ start    │ -        │ 1234      │ 0               │
// │ 1        │ 4        │ 123       │ 0*10 + 4 = 4    │
// │ 2        │ 3        │ 12        │ 4*10 + 3 = 43   │
// │ 3        │ 2        │ 1         │ 43*10 + 2 = 432  │
// │ 4        │ 1        │ 0         │ 432*10 + 1 = 4321│
// └──────────┴──────────┴───────────┴────────────────┘
// Result: 4321

function reversNum($n) {
    $reverseNumber = 0;
    while ((int)$n != 0) {
        $lastDigit = $n % 10;                          // Extract last digit
        $reverseNumber = $reverseNumber * 10 + $lastDigit; // Append to result
        $n = (int)($n / 10);                           // Remove last digit
    }
    return $reverseNumber;
}


// ==================== Approach 2: With Overflow Check (LeetCode #7) ====================
// Time: O(log10(N))
// Space: O(1)
//
// Constraint: 32-bit signed integer range: -2,147,483,648 to 2,147,483,647
//             If reversed number overflows, return 0.
//
// Overflow Logic:
//   Before doing: result = result * 10 + digit
//   We check:
//     - If result > 214748364, then result*10 >= 2147483650 > 2147483647 (overflow!)
//     - If result == 214748364 AND digit > 7, then result*10+digit > 2147483647
//     - Same logic for negative: < -214748364 or (== -214748364 AND digit < -8)
//
// Dry Run: x = 1534236469 (this would overflow when reversed)
//   reversed digits: 9, 6, 4, 6, 3, 2, 4, 3, 5, 1
//   At some point result*10 + digit > 2147483647 → return 0

class Solution {

    /**
     * @param Integer $x
     * @return Integer
     */
    function reverse($x) {
        $result = 0;

        while ($x != 0) {
            $digit = $x % 10;           // Extract last digit (preserves sign)
            $x = (int)($x / 10);        // Remove last digit

            // Positive overflow check
            // 2147483647 / 10 = 214748364 remainder 7
            if ($result > 214748364 || ($result == 214748364 && $digit > 7)) {
                return 0;
            }

            // Negative overflow check
            // -2147483648 / 10 = -214748364 remainder -8
            if ($result < -214748364 || ($result == -214748364 && $digit < -8)) {
                return 0;
            }

            $result = $result * 10 + $digit;  // Build reversed number
        }
        return $result;
    }
}


/*
|--------------------------------------------------------------------------
| 3. CHECK ARMSTRONG NUMBER (Narcissistic Number)
|--------------------------------------------------------------------------
|
| Problem : A number is Armstrong if the sum of its digits each raised
|           to the power of total digits equals the original number.
|
| Formula : For a number with 'k' digits: d1^k + d2^k + ... + dk^k == N
|
| Examples:
|   153  → digits=3 → 1^3 + 5^3 + 3^3 = 1 + 125 + 27 = 153  ✅ Armstrong
|   370  → digits=3 → 3^3 + 7^3 + 0^3 = 27 + 343 + 0 = 370  ✅ Armstrong
|   1634 → digits=4 → 1^4 + 6^4 + 3^4 + 4^4 = 1 + 1296 + 81 + 256 = 1634 ✅
|   123  → digits=3 → 1^3 + 2^3 + 3^3 = 1 + 8 + 27 = 36     ❌ Not Armstrong
|
| Note: Implementation not provided here — only concept documented.
|
| Approach:
|   1. Count digits (k)
|   2. Extract each digit, raise to power k, sum them
|   3. Compare sum with original number
|
| Time : O(log10(N)) — we process each digit once
| Space: O(1)
|
| Pseudocode:
|   k = countDigits(n)
|   temp = n, sum = 0
|   while temp > 0:
|       digit = temp % 10
|       sum += pow(digit, k)
|       temp = temp / 10
|   return sum == n
*/


/*
|--------------------------------------------------------------------------
| 4. PRINT ALL DIVISORS OF A GIVEN NUMBER
|--------------------------------------------------------------------------
|
| Problem : Given a number N, find all numbers that divide N evenly.
| Example : N = 36 => Divisors: 1, 2, 3, 4, 6, 9, 12, 18, 36
|
*/

// ==================== Optimal Approach: Loop till sqrt(N) ====================
// Time: O(√N)
// Space: O(1) — or O(√N) if collecting in array
//
// Key Insight: Divisors come in pairs.
//   If i divides N, then N/i also divides N.
//   Example: 36 → (1,36), (2,18), (3,12), (4,9), (6,6)
//   So we only need to check from 1 to √N = 6
//
// Dry Run: N = 36, √36 = 6
// ┌───┬──────────┬────────┬──────────────────────────┐
// │ i │ 36 % i   │ 36/i   │ Output                   │
// ├───┼──────────┼────────┼──────────────────────────┤
// │ 1 │ 0 ✅     │ 36     │ Print 1, 36  (1 ≠ 36)   │
// │ 2 │ 0 ✅     │ 18     │ Print 2, 18  (2 ≠ 18)   │
// │ 3 │ 0 ✅     │ 12     │ Print 3, 12  (3 ≠ 12)   │
// │ 4 │ 0 ✅     │ 9      │ Print 4, 9   (4 ≠ 9)    │
// │ 5 │ 1 ❌     │ -      │ Skip                     │
// │ 6 │ 0 ✅     │ 6      │ Print 6 only (6 == 6) ⚠️ │
// └───┴──────────┴────────┴──────────────────────────┘
// Result: 1, 36, 2, 18, 3, 12, 4, 9, 6 → sorted: 1,2,3,4,6,9,12,18,36
//
// Note: When i == N/i (like 6 == 6), print only once to avoid duplicates.
//       To get sorted output, collect in array and sort.

function printDivisorsOptimal($num) {
    for ($i = 1; $i <= sqrt($num); $i++) {
        if ($num % $i == 0) {
            echo $i . "\n";
            if ($i !== (int)($num / $i)) {  // Avoid duplicate when i == num/i
                echo (int)($num / $i) . "\n";
            }
        }
    }
}
// echo printDivisorsOptimal(36);  // Output: 1 36 2 18 3 12 4 9 6


/*
|--------------------------------------------------------------------------
| 5. CHECK PRIME NUMBER
|--------------------------------------------------------------------------
|
| Problem : A prime number is divisible only by 1 and itself.
| Example : 7 → PRIME  (divisors: 1, 7)
|           12 → NOT Prime (divisors: 1, 2, 3, 4, 6, 12)
|           227 → PRIME
|
| Key Facts:
|   - 0 and 1 are NOT prime
|   - 2 is the smallest and only even prime
|   - A prime has exactly 2 divisors: 1 and itself
|
*/

// ==================== Approach 1: Brute Force ====================
// Time: O(N) — check every number from 1 to N
// Space: O(1)
//
// Idea: Count all divisors. If count == 2, it's prime.
//
// Dry Run: N = 7
//   i=1: 7%1==0 → count=1
//   i=2: 7%2!=0
//   i=3: 7%3!=0
//   i=4: 7%4!=0
//   i=5: 7%5!=0
//   i=6: 7%6!=0
//   i=7: 7%7==0 → count=2
//   count == 2 → PRIME ✅

function isPrimeBrute($num) {
    $count = 0;
    for ($i = 1; $i <= $num; $i++) {
        if ($num % $i == 0) {
            $count++;
        }
    }

    // A prime number has exactly 2 divisors: 1 and itself
    if ($count == 2) {
        return "PRIME";
    } else {
        return "NOT Prime";
    }
}


// ==================== Approach 2: Loop till sqrt(N) ====================
// Time: O(√N)
// Space: O(1)
//
// Key Insight: Same as divisors — if i divides N, then N/i also divides N.
//              We only need to check up to √N.
//              But we must count BOTH divisors of each pair.
//
// Dry Run: N = 36, √36 = 6
//   i=1: 36%1==0 → count++ → 36/1=36 ≠ 1 → count++ → count=2
//   i=2: 36%2==0 → count++ → 36/2=18 ≠ 2 → count++ → count=4
//   i=3: 36%3==0 → count++ → 36/3=12 ≠ 3 → count++ → count=6
//   i=4: 36%4==0 → count++ → 36/4=9  ≠ 4 → count++ → count=8
//   i=5: 36%5!=0
//   i=6: 36%6==0 → count++ → 36/6=6  == 6 → skip    → count=9
//   count = 9 ≠ 2 → NOT Prime ✅
//
// Dry Run: N = 227, √227 ≈ 15.06
//   i=1:  227%1==0  → count++ → 227/1=227 ≠ 1 → count++ → count=2
//   i=2:  227%2!=0
//   i=3:  227%3!=0
//   ...   (no other divisor found up to 15)
//   i=15: 227%15!=0
//   count = 2 → PRIME ✅

function isPrimeSqrt($num) {
    $count = 0;
    for ($i = 1; $i <= sqrt($num); $i++) {
        if ($num % $i == 0) {
            $count++;                      // Count i as a divisor
            if ($num / $i != $i) {         // If pair divisor is different
                $count++;                  // Count N/i as well
            }
        }
    }

    // A prime number has exactly 2 divisors: 1 and itself
    if ($count == 2) {
        return "PRIME";
    } else {
        return "NOT Prime";
    }
}
// echo isPrimeSqrt(227);  // Output: PRIME


/*
|--------------------------------------------------------------------------
| 6. FIND GCD (HCF) OF TWO NUMBERS
|--------------------------------------------------------------------------
|
| Problem : Find the Greatest Common Divisor of two numbers.
|           GCD = Greatest Common Divisor = HCF (Highest Common Factor)
|
| Example : GCD(12, 8) = 4   → common divisors: 1,2,4 → greatest = 4
|           GCD(52, 10) = 2  → common divisors: 1,2 → greatest = 2
|           GCD(14, 21) = 7  → common divisors: 1,7 → greatest = 7
|
| Useful Formula: LCM(a,b) * GCD(a,b) = a * b
|   So: LCM(a,b) = (a * b) / GCD(a,b)
|
*/

// ==================== Approach 1: Brute Force ====================
// Time: O(min(a,b))
// Space: O(1)
//
// Idea: Check every number from 1 to min(a,b).
//       If it divides both a and b, it's a common divisor.
//       Track the largest one.
//
// Dry Run: a=12, b=8, min=8
// ┌───┬────────┬────────┬──────────┐
// │ i │ 12%i   │ 8%i    │ gcd      │
// ├───┼────────┼────────┼──────────┤
// │ 1 │ 0 ✅   │ 0 ✅   │ 1        │
// │ 2 │ 0 ✅   │ 0 ✅   │ 2        │
// │ 3 │ 0 ✅   │ 2 ❌   │ 2        │
// │ 4 │ 0 ✅   │ 0 ✅   │ 4 ← ans │
// │ 5 │ 2 ❌   │ 3 ❌   │ 4        │
// │ 6 │ 0 ✅   │ 2 ❌   │ 4        │
// │ 7 │ 5 ❌   │ 1 ❌   │ 4        │
// │ 8 │ 4 ❌   │ 0 ✅   │ 4        │
// └───┴────────┴────────┴──────────┘
// Result: 4

function findGCDBrute($a, $b) {
    if ($a == 0 || $b == 0) {
        return $a == 0 ? $b : $a;  // GCD(0,b) = b, GCD(a,0) = a
    }
    $gcd = 1;
    for ($i = 1; $i <= min($a, $b); $i++) {
        if (($a % $i == 0) && ($b % $i == 0)) {
            $gcd = $i;
        }
    }
    return $gcd;
}


// ==================== Approach 2: Subtraction Method (Recursive) ====================
// Time: O(max(a,b)) — worst case when one number is much larger
// Space: O(max(a,b)) — recursion stack
//
// Idea: Repeatedly subtract smaller from larger.
//       GCD(a,b) = GCD(a-b, b) when a > b
//       Base case: when a == 0, return b
//
// Dry Run: GCD(52, 10)
//   GCD(52, 10) → 52>10 → GCD(52-10, 10) = GCD(42, 10)
//   GCD(42, 10) → 42>10 → GCD(42-10, 10) = GCD(32, 10)
//   GCD(32, 10) → 32>10 → GCD(32-10, 10) = GCD(22, 10)
//   GCD(22, 10) → 22>10 → GCD(22-10, 10) = GCD(12, 10)
//   GCD(12, 10) → 12>10 → GCD(12-10, 10) = GCD(2, 10)
//   GCD(2, 10)  → 10>2  → GCD(10-2, 2)   = GCD(8, 2)
//   GCD(8, 2)   → 8>2   → GCD(8-2, 2)    = GCD(6, 2)
//   GCD(6, 2)   → 6>2   → GCD(6-2, 2)    = GCD(4, 2)
//   GCD(4, 2)   → 4>2   → GCD(4-2, 2)    = GCD(2, 2)
//   GCD(2, 2)   → 2==2  → GCD(2-2, 2)    = GCD(0, 2)
//   GCD(0, 2)   → a==0  → return 2
//   Result: 2
//
// Problem: Too many recursive calls! (52-10) needs 4 subtractions.
//          Using modulo (52%10 = 2) does it in 1 step.

function findGCDSubtraction($a, $b) {
    if ($a == 0) return $b;
    if ($b == 0) return $a;

    if ($a > $b) return findGCDSubtraction($a - $b, $b);
    else return findGCDSubtraction($b - $a, $a);
}


// ==================== Approach 3: Euclidean Algorithm (Optimal) ====================
// Time: O(log(min(a,b)))
// Space: O(log(min(a,b))) — recursion stack
//
// Formula: GCD(a, b) = GCD(a % b, b) where a > b
//
// Why it works:
//   If g divides both a and b, then g also divides (a % b).
//   Modulo is just repeated subtraction done in one step.
//   52 % 10 = 2 (same as subtracting 10 from 52 five times → 52-50 = 2)
//
// Dry Run: GCD(52, 10)
//   GCD(52, 10) → 52>10 → GCD(52%10, 10) = GCD(2, 10)
//   GCD(2, 10)  → 10>2  → GCD(10%2, 2)   = GCD(0, 2)
//   GCD(0, 2)   → a==0  → return 2
//   Result: 2  (only 3 calls vs 11 calls with subtraction!)
//
// Dry Run: GCD(14, 21)
//   GCD(14, 21) → 21>14 → GCD(21%14, 14) = GCD(7, 14)
//   GCD(7, 14)  → 14>7  → GCD(14%7, 7)   = GCD(0, 7)
//   GCD(0, 7)   → a==0  → return 7
//   Result: 7

function findGCDEuclidean($a, $b) {
    if ($a == 0) return $b;
    if ($b == 0) return $a;

    if ($a > $b) return findGCDEuclidean($a % $b, $b);
    else return findGCDEuclidean($b % $a, $a);
}
// echo findGCDEuclidean(52, 10);  // Output: 2
// echo findGCDEuclidean(14, 21);  // Output: 7


/*
|--------------------------------------------------------------------------
| 7. FIND UNIQUE PRIME FACTORS OF A NUMBER
|--------------------------------------------------------------------------
|
| Problem : Given N, find all unique prime factors in increasing order.
| Example : N = 780 = 2 × 2 × 3 × 5 × 13
|           Unique prime factors: [2, 3, 5, 13]
|
|           N = 20 = 2 × 2 × 5
|           Unique prime factors: [2, 5]
|
*/

// ==================== Approach 1: Check Each Divisor for Primality ====================
// Time: O(√N × √N) = O(N) — for each divisor up to √N, check if prime in O(√N)
// Space: O(number of prime factors)
//
// Idea: Find all divisors up to √N. For each divisor and its pair,
//       check if they are prime using isPrime().
//
// Dry Run: N = 780, √780 ≈ 27.9
//   i=2:  780%2==0 → isPrime(2)=true  → add 2.  780/2=390, isPrime(390)=false
//   i=3:  780%3==0 → isPrime(3)=true  → add 3.  780/3=260, isPrime(260)=false
//   i=4:  780%4==0 → isPrime(4)=false.          780/4=195, isPrime(195)=false
//   i=5:  780%5==0 → isPrime(5)=true  → add 5.  780/5=156, isPrime(156)=false
//   i=6:  780%6==0 → isPrime(6)=false.          780/6=130, isPrime(130)=false
//   i=10: 780%10==0→ isPrime(10)=false.         780/10=78, isPrime(78)=false
//   i=12: 780%12==0→ isPrime(12)=false.         780/12=65, isPrime(65)=false
//   i=13: 780%13==0→ isPrime(13)=true → add 13. 780/13=60, isPrime(60)=false
//   i=15: 780%15==0→ isPrime(15)=false.         780/15=52, isPrime(52)=false
//   i=20: 780%20==0→ isPrime(20)=false.         780/20=39, isPrime(39)=false
//   i=26: 780%26==0→ isPrime(26)=false.         780/26=30, isPrime(30)=false
//   Result: [2, 3, 5, 13]

function isPrimeHelper(int $n) {
    if ($n < 2) return false;
    for ($i = 2; $i <= sqrt($n); $i++) {
        if ($n % $i == 0) {
            return false;
        }
    }
    return true;
}

function primeFactorsApproach1($n) {
    $prime_factors = [];
    for ($i = 2; $i <= sqrt($n); $i++) {
        if ($n % $i == 0) {
            if (isPrimeHelper($i)) {
                $prime_factors[] = $i;
            }
            if ($n / $i != $i && isPrimeHelper($n / $i)) {
                $prime_factors[] = $n / $i;
            }
        }
    }
    sort($prime_factors);
    return $prime_factors;
}
// print_r(primeFactorsApproach1(780));  // [2, 3, 5, 13]


// ==================== Approach 2: Divide Out Each Factor ====================
// Time: O(N) — outer loop goes up to N in worst case
// Space: O(number of prime factors)
//
// Idea: Start from i=2. If i divides N, it MUST be prime (because all smaller
//       primes have already been divided out). Keep dividing N by i until
//       it no longer divides. Move to next i.
//
// Why does this only find primes?
//   When we reach i=4, all factors of 2 are already removed from N.
//   So N%4 can never be 0. Same for 6,8,9,10... Only true primes survive.
//
// Dry Run: N = 780
// ┌───────┬───────┬──────────────────────────────────────────────┐
// │ i     │ N     │ Action                                       │
// ├───────┼───────┼──────────────────────────────────────────────┤
// │ 2     │ 780   │ 780%2==0 → add 2, divide: 780→390→195       │
// │ 3     │ 195   │ 195%3==0 → add 3, divide: 195→65            │
// │ 4     │ 65    │ 65%4!=0  → skip                              │
// │ 5     │ 65    │ 65%5==0  → add 5, divide: 65→13             │
// │ 6-12  │ 13    │ 13%i!=0  → skip all                          │
// │ 13    │ 13    │ 13%13==0 → add 13, divide: 13→1             │
// └───────┴───────┴──────────────────────────────────────────────┘
// Result: [2, 3, 5, 13]
//
// Problem: Loop runs up to N (i goes from 2 to 13 here, but for
//          a large prime N, loop runs up to N itself).

function primeFactorsApproach2($n) {
    $prime_factors = [];
    for ($i = 2; $i <= $n; $i++) {
        if ($n % $i == 0) {
            $prime_factors[] = $i;                // i is a prime factor
            while ($n % $i == 0) {                // Remove ALL occurrences of i
                $n = $n / $i;
            }
        }
    }
    return $prime_factors;
}
// print_r(primeFactorsApproach2(780));  // [2, 3, 5, 13]


// ==================== Approach 3: Divide Out + sqrt(N) Optimization (Optimal) ====================
// Time: O(√N) — loop only goes up to √N
// Space: O(number of prime factors)
//
// Idea: Same as Approach 2 but loop only till √N.
//       After the loop, if N > 1, then N itself is a prime factor.
//
// Why? After dividing by all primes up to √N, if N > 1, then the remaining
//      N must be a prime number (it has no factor ≤ √N).
//
// Key Insight: The loop condition √N changes dynamically as N shrinks!
//   As we divide out factors, N gets smaller, so √N also shrinks,
//   making the loop terminate even faster.
//
// Dry Run: N = 780, initial √780 ≈ 27.9
// ┌───────┬───────┬──────────┬──────────────────────────────────────────┐
// │ i     │ N     │ √N       │ Action                                   │
// ├───────┼───────┼──────────┼──────────────────────────────────────────┤
// │ 2     │ 780   │ 27.9     │ 780%2==0 → add 2, divide: 780→390→195   │
// │ 3     │ 195   │ 13.96    │ 195%3==0 → add 3, divide: 195→65        │
// │ 4     │ 65    │ 8.06     │ 65%4!=0  → skip (4 ≤ 8.06 → continue)   │
// │ 5     │ 65    │ 8.06     │ 65%5==0  → add 5, divide: 65→13         │
// │ 6     │ 13    │ 3.61     │ 6 > 3.61 → loop ends!                   │
// └───────┴───────┴──────────┴──────────────────────────────────────────┘
// After loop: N = 13 > 1 → add 13 as remaining prime factor
// Result: [2, 3, 5, 13]

function primeFactorsApproach3($n) {
    $prime_factors = [];
    for ($i = 2; $i <= sqrt($n); $i++) {     // Only loop till √N (dynamic!)
        if ($n % $i == 0) {
            $prime_factors[] = $i;             // i is a prime factor
            while ($n % $i == 0) {             // Remove ALL occurrences of i
                $n = $n / $i;
            }
        }
    }
    // If N > 1 after the loop, it is a remaining prime factor
    // (no factor ≤ √N could divide it, so it must be prime)
    if ($n != 1) {
        $prime_factors[] = $n;
    }
    return $prime_factors;
}
// print_r(primeFactorsApproach3(780));  // [2, 3, 5, 13]


/*
|--------------------------------------------------------------------------
| 8. PRIME FACTORIZATION USING SIEVE (Smallest Prime Factor)
|--------------------------------------------------------------------------
|
| Problem : Find unique prime factors of N using precomputed SPF table.
|           Useful when you need to factorize MANY numbers efficiently.
|
| Concept: Build a Sieve where each index stores its Smallest Prime Factor (SPF).
|          Then to factorize N, keep dividing by SPF[N] until N becomes 1.
|
| Time : O(N log log N) for sieve + O(log N) per query
| Space: O(N) for the SPF table
|
| Step 1 — Build SPF (Smallest Prime Factor) table:
|   Initialize SPF[i] = i for all i (each number is its own smallest factor initially)
|   For each prime i (where SPF[i] == i), update all multiples j of i:
|     SPF[j] = i  (but only if SPF[j] hasn't been set to something smaller)
|
| SPF Table for N=20:
| ┌────────┬──┬──┬──┬──┬──┬──┬──┬──┬──┬───┬───┬───┬───┬───┬───┬───┬───┬───┬───┐
| │ index  │ 2│ 3│ 4│ 5│ 6│ 7│ 8│ 9│10│ 11│ 12│ 13│ 14│ 15│ 16│ 17│ 18│ 19│ 20│
| │ SPF[i] │ 2│ 3│ 2│ 5│ 2│ 7│ 2│ 3│ 2│ 11│  2│ 13│  2│  3│  2│ 17│  2│ 19│  2│
| └────────┴──┴──┴──┴──┴──┴──┴──┴──┴──┴───┴───┴───┴───┴───┴───┴───┴───┴───┴───┘
|
| Step 2 — Factorize N=20 using SPF:
|   N=20 → SPF[20]=2  → record 2, N=20/2=10
|   N=10 → SPF[10]=2  → record 2 (skip duplicate), N=10/2=5
|   N=5  → SPF[5]=5   → record 5, N=5/5=1
|   N=1  → stop
|   Result: unique primes = [2, 5]
|
| Another example — Factorize N=12:
|   N=12 → SPF[12]=2  → record 2, N=12/2=6
|   N=6  → SPF[6]=2   → skip 2, N=6/2=3
|   N=3  → SPF[3]=3   → record 3, N=3/3=1
|   N=1  → stop
|   Result: unique primes = [2, 3]
|
*/

function printPrimeFactorsWithSieve($n) {
    $ans = [];

    // Step 1: Build Smallest Prime Factor (SPF) table
    // Initialize: SPF[i] = i (each number is its own factor initially)
    $spf = array_fill(2, $n - 1, 1);
    $keys = array_keys($spf);
    $spf = array_combine($keys, $keys);  // Now spf[2]=2, spf[3]=3, ..., spf[n]=n

    // Sieve: For each number, if it's still its own SPF, it's prime.
    // Update all its multiples (starting from i*i) with i as their SPF.
    for ($i = 2; $i <= $n; $i++) {
        if ($spf[$i] == $i) {    // i is prime (no smaller factor found)
            // Mark all multiples of i, starting from i*i
            // (multiples < i*i are already handled by smaller primes)
            for ($j = $i * $i; $j <= $n; $j += $i) {
                if ($spf[$j] == $j) {   // Only update if not already set
                    $spf[$j] = $i;
                }
            }
        }
    }

    // Step 2: Factorize N using the SPF table
    // Keep dividing by smallest prime factor until N becomes 1
    while ($n > 1) {
        $ans[$spf[$n]] = $spf[$n];   // Store unique prime factor (key=value deduplicates)
        $n = $n / $spf[$n];          // Divide out the smallest prime factor
    }

    print_r($ans);
}
// printPrimeFactorsWithSieve(20);   // Output: [2, 5]
// printPrimeFactorsWithSieve(780);  // Output: [2, 3, 5, 13]


?>

