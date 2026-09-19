<?php

/*
|--------------------------------------------------------------------------
| Problem: Longest Palindromic Substring (LC 5)
| Source: DS/5. String/String_enhancement.php
| Pattern: Dynamic Programming (Strings)
|--------------------------------------------------------------------------
*/

/*
Problem Description:
 What: find the longest contiguous substring of s that reads the same
       forwards and backwards.
 Why it exists: the canonical intro to the Expand-Around-Center
   technique, and a common lead-in to discussing (but rarely
   implementing live) Manacher's O(n) algorithm as a named follow-up.


 --- 🎯 Interview-Ready Add-Ons (constraints, timing, pitch) ---
 Asked at      : Amazon, Meta, Google, Microsoft, Bloomberg -- one of the highest-frequency medium/hard string problems industry-wide; also a favorite for testing PHP scoping fundamentals given the bugs this exact code contained.
 Constraints   : 1 <= s.length <= 1000 -> O(n^2) Expand-Around-Center expected and fully sufficient at this scale; O(n^3) brute force should be named as the naive starting point, and Manacher's O(n) should be NAMED (rarely implemented live) as the theoretical optimum.
 Time-boxing   : Total ~12 min: 2 min restate + the odd/even center distinction, 3 min brute force + complexity, 7 min expand-around-center + a full dry run on an even-length example.
 60-Sec Pitch  : "For every one of the 2n-1 possible centers (n single-character centers plus n-1 between-character centers), I expand outward while characters match on both sides, tracking the longest palindrome found -- checking both center types is what catches both odd- and even-length palindromes."

Example:
Input: s="babad" -> Output: "bab" or "aba"
Input: s="cbbd" -> Output: "bb"

Why This Pattern:
Longest Palindromic Substring is the flagship interval-DP string problem (dp[i][j] = s[i]==s[j] && dp[i+1][j-1]); Expand-Around-Center is the well-known O(1)-space alternative to that same DP formulation, checked from every possible center instead of filling a DP table.
*/

function longestPalindromeBrute(string $s): string
{
    $n = strlen($s);
    $best = '';

    for ($start = 0; $start < $n; $start++) {
        for ($end = $start; $end < $n; $end++) {
            $candidate = substr($s, $start, $end - $start + 1);
            if ($candidate === strrev($candidate) && strlen($candidate) > strlen($best)) {
                $best = $candidate;
            }
        }
    }

    return $best;
}

function expandAroundCenter(string $s, int $left, int $right, int &$start, int &$maxLength): void
{
    $n = strlen($s);

    while ($left >= 0 && $right < $n && $s[$left] === $s[$right]) {   // Expand while symmetric characters match
        $left--;
        $right++;
    }

    // Loop over-expanded by exactly one step past the true boundary on both sides
    $currentLength = $right - $left - 1;

    if ($currentLength > $maxLength) {
        $maxLength = $currentLength;
        $start = $left + 1;   // True start is one step INSIDE the over-expanded left boundary
    }
}

function longestPalindrome(string $s): string
{
    $n = strlen($s);
    if ($n <= 1) return $s;

    $start = 0;
    $maxLength = 1;

    for ($i = 0; $i < $n; $i++) {
        expandAroundCenter($s, $i, $i, $start, $maxLength);          // Odd-length palindromes: center ON index i
        expandAroundCenter($s, $i, $i + 1, $start, $maxLength);      // Even-length palindromes: center BETWEEN i and i+1
    }

    return substr($s, $start, $maxLength);
}

