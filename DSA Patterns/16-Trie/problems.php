<?php

/*
|--------------------------------------------------------------------------
| Problem: Longest Common Prefix (LC 14)
| Source: DS/5. String/String_enhancement.php
| Pattern: Trie
|--------------------------------------------------------------------------
*/

/*
Problem Description:
 What: given an array of strings, find the longest string that is a
       prefix of ALL of them; return "" if no common prefix exists.
 Why it exists: a foundational "reduce N-way comparison to a smarter
   2-way or column-wise comparison" problem -- the sort-based trick in
   particular is a nice example of exploiting a property (lexicographic
   ordering) that isn't obviously relevant at first glance.


 --- 🎯 Interview-Ready Add-Ons (constraints, timing, pitch) ---
 Asked at      : Amazon, Google, Microsoft, Facebook/Meta -- one of the most frequently asked easy string problems industry-wide.
 Constraints   : 1 <= strs.length <= 200, 0 <= strs[i].length <= 200 -> O(n*m) Vertical Scanning or O(n log n + m) sort-based approach, both expected as acceptable answers.
 Time-boxing   : Total ~7 min: 1 min restate, 3 min Vertical Scanning, 3 min sort-based alternative + explain the trade-off between the two.
 60-Sec Pitch  : "I either scan column by column comparing the first string against all others (Vertical Scanning), or sort the array and compare only the lexicographically first and last strings -- both exploit the fact that a common prefix must be shared by every string, so I only need to find the first mismatch point."

Example:
Input: strs = ["flower","flow","flight"] -> Output: "fl"
Input: strs = ["dog","racecar","car"] -> Output: ""

Why This Pattern:
Longest Common Prefix is the flagship 'shared prefix' problem for the Trie pattern - inserting every string into a Trie and walking down while every node has exactly one child (shared by all strings) gives the same answer as this file's sort+compare approach; both are recognized approaches to the same underlying prefix-sharing problem.
*/

function longestCommonPrefixVertical(array $strs): string
{
    if (empty($strs)) return '';

    for ($col = 0; $col < strlen($strs[0]); $col++) {
        $char = $strs[0][$col];
        for ($row = 1; $row < count($strs); $row++) {
            if ($col >= strlen($strs[$row]) || $strs[$row][$col] !== $char) {
                return substr($strs[0], 0, $col);   // Mismatch or ran out of characters -- stop here
            }
        }
    }

    return $strs[0];   // The entire first string is a prefix of every other string
}

function longestCommonPrefix(array $strs): string
{
    if (empty($strs)) return '';
    if (count($strs) === 1) return $strs[0];

    sort($strs);                                 // Lexicographic sort
    $first = $strs[0];                            // Smallest string after sorting
    $last = $strs[count($strs) - 1];               // Largest string after sorting
    $minLength = min(strlen($first), strlen($last));

    $commonPrefix = '';
    for ($i = 0; $i < $minLength; $i++) {
        if ($first[$i] === $last[$i]) {
            $commonPrefix .= $first[$i];
        } else {
            break;   // First mismatch -- no more common prefix possible
        }
    }

    return $commonPrefix;
}

