<?php

// ============================================================
// STRING ALGORITHMS — Complete Revision Guide
// Topics : Reverse Words in a String  | Largest Odd Number
//          String to Integer (atoi)   | atoi — Recursive
// ============================================================
// Intuition behind string problems:
//   - Most string problems reduce to careful pointer / index control
//   - Read character by character, track state (word, sign, digit…)
//   - PHP strings are 0-indexed and immutable per-character;
//     build results in a variable rather than modifying in-place
// ============================================================

//Remove Outermost Parentheses
function removeOuterParentheses($s) {
        // String to store the final result after removing outermost parentheses
        $ans = "";

        // Tracks the number of currently open '(' parentheses
        // It increases when we see '(' and decreases when we see ')'
        $openParenthesisCount = 0;

        // Traverse each character of the input string
        for ($i = 0; $i < strlen($s); $i++) {

            // Get the current character
            $str = $s[$i];

            // If the current character is an opening parenthesis
            if ($str == "(") {

                // Increase the count because a new '(' is encountered
                $openParenthesisCount++;

                // If count is NOT 1, it means this is NOT the outermost '('
                // So, keep it in the answer
                if ($openParenthesisCount != 1) {
                    $ans .= $str;
                }

            } else {

                // Current character is ')'
                // Decrease the count because one '(' is now closed
                $openParenthesisCount--;

                // If count is NOT 0, it means this is NOT the outermost ')'
                // So, keep it in the answer
                if ($openParenthesisCount != 0) {
                    $ans .= $str;
                }
            }
        }

        // Return the string after removing all outermost parentheses
        return $ans;
    }


// ============================================================
// 1. REVERSE WORDS IN A STRING (LeetCode 151)
// ============================================================
// Intuition:
//   Read the trimmed string left-to-right, collect characters
//   into a temporary word.  Whenever a space is encountered
//   (and a word has been collected), PREPEND the word to the
//   answer.  After the loop, prepend the last word too.
//
//   Prepending each new word automatically reverses the order
//   without needing a second pass or an extra array.
//
//   Key difference from a simple split+reverse:
//     Multiple consecutive spaces are silently ignored because
//     we only prepend when temp !== ''.
//
// Dry Run: s = "a good   example"   (after trim: same)
//
//   i=0  'a'  → temp='a'
//   i=1  ' '  → temp≠'' → ans='a',           temp=''
//   i=2  'g'  → temp='g'
//   i=3  'o'  → temp='go'
//   i=4  'o'  → temp='goo'
//   i=5  'd'  → temp='good'
//   i=6  ' '  → temp≠'' → ans='good a',      temp=''
//   i=7  ' '  → temp='' → skip (multi-space)
//   i=8  ' '  → temp='' → skip (multi-space)
//   i=9  'e'  → temp='e'
//   …        → temp='example'
//   End       → temp≠'' → ans='example good a'
//
//   Result: "example good a"  ✓
//
// TC: O(N)  — single left-to-right pass; each character processed once
// SC: O(N)  — temporary word buffer + result string, both at most length N

function reverseWords(string $s): string
{
    $s    = trim($s);   // Remove leading and trailing whitespace
    $n    = strlen($s);
    $ans  = '';         // Final reversed-words result
    $temp = '';         // Current word being built

    for ($i = 0; $i < $n; $i++) {

        if ($s[$i] !== ' ') {
            $temp .= $s[$i]; // Keep appending characters to form the current word
        } else {
            // A space was hit — process the word we just finished building
            if ($temp !== '') {
                // Prepend this word to the answer to achieve reverse order
                $ans  = ($ans === '') ? $temp : $temp . ' ' . $ans;
                $temp = ''; // Reset for the next word
            }
            // If temp is '' we had consecutive spaces — just skip them
        }
    }

    // The loop ends before processing the very last word, so handle it here
    if ($temp !== '') {
        $ans = ($ans === '') ? $temp : $temp . ' ' . $ans;
    }

    return $ans;
}

// --- Run ---
echo "Reverse Words 'a good   example':    '" . reverseWords("a good   example")    . "'\n"; // 'example good a'
echo "Reverse Words '  hello world  ':     '" . reverseWords("  hello world  ")     . "'\n"; // 'world hello'
echo "Reverse Words 'a':                   '" . reverseWords("a")                   . "'\n"; // 'a'


// ============================================================
// 2. LARGEST ODD NUMBER IN A STRING (LeetCode 1903)
// ============================================================
// Intuition:
//   A number is odd if and only if its LAST digit is odd.
//   We want the longest prefix of $num whose last character
//   is an odd digit — so scan from RIGHT to LEFT and return
//   the substring the moment we find an odd digit.
//
//   If no odd digit exists, the answer is "" (empty string).
//
// Dry Run: num = "2221444"
//
//   Index:  0  1  2  3  4  5  6
//   Digit:  2  2  2  1  4  4  4
//
//   high=6 → '4' even → high=5
//   high=5 → '4' even → high=4
//   high=4 → '4' even → high=3
//   high=3 → '1' ODD  → return substr("2221444", 0, 4) = "2221"  ✓
//
// TC: O(N)  — at most one full scan of the string
// SC: O(N)  — the returned substring (unavoidable; no extra working space)

function largestOddNumber(string $num): string
{

    // Remove leading and trailing spaces
    $num = trim($num);

    // Find length of the string
    $stringLength = strlen($num);

    // If string is empty, nothing to process
    if ($stringLength == 0) return "";

    // Stores index of the last odd digit.
    // -1 means no odd digit found yet.
    $maxOddNumberLastDigit = -1;

    // Traverse from right to left because
    // we need the RIGHTMOST odd digit.
    for ($i = $stringLength - 1; $i >= 0; $i--) {
        // Convert character into number implicitly
        // and check if it is odd.
        if ($num[$i] % 2 != 0) {
            // Save index of odd digit
            $maxOddNumberLastDigit = $i;

            // No need to continue.
            // This is the largest possible odd substring.
            break;
        }
    }

    // Final answer
    $ans = "";

    // Copy characters from beginning
    // until the last odd digit.
    for ($i = 0; $i <= $maxOddNumberLastDigit; $i++) {
        // Skip leading zeros.
        // Example:
        // "000123" -> "123"
        if ($num[$i] == '0' && $ans == "")
            continue;

        // Append current digit
        $ans .= $num[$i];
    }

    return $ans;


    //Similer Sort version
    $high = strlen($num) - 1; // Start from the rightmost digit

    // Scan right-to-left until an odd digit is found
    while ($high >= 0) {
        if ((int)$num[$high] % 2 !== 0) {
            // This is the rightmost odd digit; the prefix up to here is the answer
            return substr($num, 0, $high + 1);
        }
        $high--; // Digit was even, try the next position to the left
    }

    return ''; // All digits are even → no odd number exists
}

// --- Run ---
echo "Largest Odd '2221444':  '" . largestOddNumber('2221444') . "'\n"; // '2221'
echo "Largest Odd '35619':    '" . largestOddNumber('35619')   . "'\n"; // '3561' (9 is odd — wait, 9 is odd → '35619')
echo "Largest Odd '4206':     '" . largestOddNumber('4206')    . "'\n"; // ''  (all even digits)
echo "Largest Odd '5':        '" . largestOddNumber('5')       . "'\n"; // '5'

$strs = ["dog","racecar","car"];
echo longestCommonPrefix($strs); echo "\n";


//Longest Common Prefix
function longestCommonPrefix($strs): string {
    // If array is empty, there is no common prefix.
    if (empty($strs))
        return "";

    // If only one string exists,
    // that string itself is the answer.
    if (count($strs) == 1)
        return $strs[0];

    // Sort strings in lexicographical (dictionary) order.
    sort($strs);

    // Smallest string after sorting.
    $first = $strs[0];

    // Largest string after sorting.
    $last = $strs[count($strs) - 1];

    // Stores the final common prefix.
    $commonPrefix = "";

    // We only compare up to the length
    // of the smaller string.
    $minLength = min(strlen($first), strlen($last));

    // Compare characters one by one.
    for ($i = 0; $i < $minLength; $i++) {

        // If characters match,
        // add them into answer.
        if ($first[$i] === $last[$i]) {

            $commonPrefix .= $first[$i];

        } else {

            // First mismatch found.
            // No more common prefix exists.
            break;
        }
    }

    return $commonPrefix;
}

//205. Isomorphic Strings

function isIsomorphic($s, $t) {
        $sLength = strlen($s);
        $tLength = strlen($t);

        if($sLength != $tLength) return false;

        for($i=0;$i<$sLength;$i++){
            $sChar = $s[$i];
            $tChar = $t[$i];
            // Find the first occurrence of each character.
            // If first occurrence positions differ,
            // mapping is inconsistent.
            if(strpos($s, $sChar) != strpos($t, $tChar)){
                return false;
            }

        }
        return true;

        //Solution 2
        // $strMap1 = $strMap2 = array();
        // $sLength = strlen($s);
        // $tLength = strlen($t);
        // if($sLength != $tLength) return false;

        // for($i = 0; $i < $sLength; $i++){
        //     if(isset($strMap1[$s[$i]])){
        //     if($strMap1[$s[$i]] != $t[$i]) {
        //         return false;
        //     }
        //     }else{
        //         $strMap1[$s[$i]] = $t[$i];
        //     }

        //     if(isset($strMap2[$t[$i]])){
        //     if($strMap2[$t[$i]] != $s[$i]) {
        //         return false;
        //     }
        //     }else{
        //         $strMap2[$t[$i]] = $s[$i];
        //     }
        // }
        // return true;
    }


//796. Rotate String

function rotateString($s, $goal) {
    $sLength = strlen($s);
    $goalLength = strlen($goal);

    if($sLength != $goalLength) return false;

    $concatenatedString = $s . $s;

    for($i=0;$i<2*$sLength;$i++){
        $tempString = "";
        for($j=$i;$j<$sLength+$i;$j++){
            $tempString .= $concatenatedString[$j];
        }
        if($tempString == $goal){
            return true;
        }
    }
    return false;

    //Another Approch
    // $rotation = 1;
    //     while($rotation <= $sLength){  
    //       $newStr = substr($s, $rotation, $sLength).substr($s, 0, $rotation);
    //       if($newStr == $goal) return true;
    //       $rotation++;
    //     }

    //Another Approch
    //$s .= $s; //abc => abcabc and gaol=cba , bca exits in abcabc
    //return strpos($s,$goal) !== false; //u can use for loop to get bca in string

    //Another Approch
    // $array = str_split($s);

    // for ($i = 0; $i < count($array); $i++) {
    //         $first_letter = array_shift($array);

    //         $array[] = $first_letter;

    //         if (implode($array) === $goal) {
    //             return true;
    //         }
    //     }
//242. Valid Anagram
    function isAnagram($s, $t) {
        //sort both string and compare both char one by one
        //OR use hasmap and store first string chr and unset for second string 
        $sLength = strlen($s);
        $tLength = strlen($t);

        if($sLength != $tLength) return false;

        $hashMap = array_fill(0,26,0);
        
        for($i=0;$i<$sLength;$i++){
            $chr = $s[$i];
            $hashMap[ord($chr) - ord('a')]++;
        }
        for($i=0;$i<$tLength;$i++){
            $chr = $t[$i];
            $hashMap[ord($chr) - ord('a')]--;
        }
        for($i=0;$i<26;$i++){
            if($hashMap[$i] != 0) return false;
        }

        return true;
    }


//Sort characters by frequency
$s = "tree";
print_r(frequencySort($s)); // Output: "eert" or "eetr"
function frequencySort($s)
{
    // Find length of the string
    $sLength = strlen($s);

    // If string contains 0 or 1 character,
    // it is already sorted.
    if ($sLength <= 1) {
        return $s;
    }

    // ---------------------------------------
    // Step 1:
    // Count frequency of every character.
    // ---------------------------------------
    //
    // Example:
    // "tree"
    //
    // Frequency Map
    // t => 1
    // r => 1
    // e => 2
    //
    $frequencyMap = [];

    foreach (str_split($s) as $char) {

        // If character appears first time
        if (!isset($frequencyMap[$char])) {

            $frequencyMap[$char] = 1;

        } else {

            // Increase frequency
            $frequencyMap[$char]++;
        }
    }

    // ---------------------------------------
    // Step 2:
    // Reverse the mapping.
    //
    // Character -> Count
    //
    // becomes
    //
    // Count -> List of Characters
    // ---------------------------------------

    $finalHashMap = [];

    foreach ($frequencyMap as $char => $count) {

        // Multiple characters may have
        // the same frequency.
        $finalHashMap[$count][] = $char;
    }

    /*
        Example

        Frequency Map

        t => 1
        r => 1
        e => 2

        Final Hash Map

        1 => [t,r]
        2 => [e]
    */

    // Sort frequencies in descending order.
    //
    // Example:
    //
    // Before
    // 1 => [t,r]
    // 2 => [e]
    //
    // After
    // 2 => [e]
    // 1 => [t,r]
    //
    krsort($finalHashMap);

    // Final answer
    $answer = "";

    // Traverse frequencies from highest to lowest.
    foreach ($finalHashMap as $count => $chars) {

        // If two characters have same frequency,
        // sort them alphabetically.
        sort($chars);

        foreach ($chars as $char) {

            // Repeat current character
            // exactly 'count' times.
            $j = $count;

            while ($j--) {

                $answer .= $char;
            }
        }
    }

    return $answer;
}
//Roman to Integer
$s = "MCMXCIV";
echo romanToInt($s);
function romanToInt($s) {
    $romanMap = [
        'I' => 1,
        'V' => 5,
        'X' => 10,
        'L' => 50,
        'C' => 100,
        'D' => 500,
        'M' => 1000,
        'IV' => 4,
        'IX' => 9,
        'XL' => 40,
        'XC' => 90,
        'CD' => 400,
        'CM' => 900
    ];

    $total = 0;
    $i = 0;
    while ($i < strlen($s)) {
        if($i+1 < strlen($s) && isset($romanMap[$s[$i].$s[$i+1]])){
            $total += $romanMap[$s[$i].$s[$i+1]];
            $i += 2;
        }
        else {
            $total += $romanMap[$s[$i]];
            $i++;
        }
    }

    return $total;
}

// ============================================================
// 3. STRING TO INTEGER — atoi  ITERATIVE  (LeetCode 8)
// ============================================================
// Intuition:
//   Simulate the real atoi() parsing rules step-by-step:
//     Step 1 — Skip leading whitespace.
//     Step 2 — Read an optional '+' or '-' sign.
//     Step 3 — Read consecutive digit characters, building the number.
//     Step 4 — Stop at any non-digit character.
//     Step 5 — Clamp the result to [INT_MIN, INT_MAX].
//
//   Overflow guard (before multiplying):
//     If result > INT_MAX / 10         → next multiply will overflow  → clamp now.
//     If result == INT_MAX / 10:
//       Positive and digit > 7  (INT_MAX last digit) → clamp to INT_MAX.
//       Negative and digit > 8  (|INT_MIN| last digit) → clamp to INT_MIN.
//
// Dry Run: s = "   -91283472332"
//
//   Step 1 — skip spaces:   i=0,1,2 are ' ' → i=3
//   Step 2 — sign:          s[3]='-' → isNegative=true, i=4
//   Step 3 — digits:
//     i=4 '9': result=0  ≤ 214748364 → result=9
//     i=5 '1': result=9  ≤ 214748364 → result=91
//     i=6 '2': result=91 ≤ 214748364 → result=912
//     ...
//     i=10 '4': result=912834 → result=9128347
//     i=11 '7': result=91283472 → result=912834723  > 214748364 → clamp → return INT_MIN
//
//   Result: -2147483648  ✓
//
// TC: O(N)  — single pass through the string
// SC: O(1)  — only a handful of scalar variables

function myAtoi(string $s): int
{
    // If the input string is empty,
    // return 0.
    if ($s === '') {
        return 0;
    }

    // 32-bit signed integer limits.
    $INT_MAX = 2147483647;
    $INT_MIN = -2147483648;

    // Indicates whether the number is negative.
    $isNegative = false;

    // Stores the converted integer.
    $result = 0;

    // Pointer used to traverse the string.
    $i = 0;

    // Length of the string.
    $n = strlen($s);

    // ----------------------------------------------------
    // Step 1: Skip only leading whitespaces.
    //
    // Example:
    // "    -42"
    //       ↑
    // Move pointer until first non-space character.
    // ----------------------------------------------------
    while ($i < $n && $s[$i] === ' ') {
        $i++;
    }

    // ----------------------------------------------------
    // Step 2: Check for optional '+' or '-'.
    //
    // Example:
    // "-42"
    //  ^
    //
    // If '-' is found,
    // remember that the final answer is negative.
    // ----------------------------------------------------
    if ($i < $n && ($s[$i] === '+' || $s[$i] === '-')) {

        $isNegative = ($s[$i] === '-');

        // Move to the first digit.
        $i++;
    }

    // ----------------------------------------------------
    // Step 3: Read consecutive digits.
    // Stop automatically when a non-digit is found.
    // ----------------------------------------------------
    while ($i < $n && $s[$i] >= '0' && $s[$i] <= '9') {

        // Convert current character into integer.
        //
        // '5' -> 5
        // '9' -> 9
        $digit = (int)$s[$i];

        // ------------------------------------------------
        // Overflow Check
        // ------------------------------------------------
        //
        // Suppose:
        //
        // result = 214748364
        //
        // Next digit = 8
        //
        // If we calculate:
        //
        // result * 10 + digit
        //
        // it becomes
        //
        // 2147483648
        //
        // which exceeds INT_MAX.
        //
        // Therefore, we must check BEFORE multiplying.
        //

        if ($result > intdiv($INT_MAX, 10)) {

            return $isNegative
                ? $INT_MIN
                : $INT_MAX;
        }

        // If result is exactly
        // 214748364
        //
        // then only the last digit matters.
        if ($result == intdiv($INT_MAX, 10)) {

            // Negative numbers may end with 8.
            //
            // -2147483648
            if ($isNegative && $digit > 8) {
                return $INT_MIN;
            }

            // Positive numbers may end with 7.
            //
            // 2147483647
            if (!$isNegative && $digit > 7) {
                return $INT_MAX;
            }
        }

        // Safe to append the digit.
        //
        // Example
        //
        // result = 42
        //
        // digit = 5
        //
        // result = 425
        $result = $result * 10 + $digit;

        $i++;
    }

    // Apply sign.
    return $isNegative
        ? -$result
        : $result;

}

// --- Run ---
echo "atoi '42':                " . myAtoi('42')                . "\n"; //  42
echo "atoi '   -42':            " . myAtoi('   -42')            . "\n"; // -42
echo "atoi '4193 with words':   " . myAtoi('4193 with words')   . "\n"; //  4193
echo "atoi '-91283472332':      " . myAtoi('-91283472332')      . "\n"; // -2147483648  (clamped)
echo "atoi '2147483648':        " . myAtoi('2147483648')        . "\n"; //  2147483647  (clamped)
echo "atoi '+-12':              " . myAtoi('+-12')              . "\n"; //  0  ('+' consumed, '-' is non-digit)
echo "atoi '':                  " . myAtoi('')                  . "\n"; //  0


// ============================================================
// 4. STRING TO INTEGER — atoi  RECURSIVE  (LeetCode 8)
// ============================================================
// Intuition:
//   Replace the digit-reading loop with a recursive helper.
//   The preprocessing (whitespace + sign) is still done
//   iteratively before the recursive call — only the
//   digit-accumulation loop is converted to recursion.
//
//   myAtoiHelper($s, $i, &$result, $isNegative):
//     Base case  : i >= strlen($s) OR s[$i] is not a digit → stop.
//     Recursive  : Apply same overflow check, update $result,
//                  then call helper(i+1).
//     Result is passed by reference so each call accumulates into it.
//
// Dry Run: s = "4193 with words"
//
//   Pre-process: no spaces, no sign. i=0.
//   Call(i=0) '4': result=4
//   Call(i=1) '1': result=41
//   Call(i=2) '9': result=419
//   Call(i=3) '3': result=4193
//   Call(i=4) ' ': non-digit → return
//   Apply sign (positive): return 4193  ✓
//
// TC: O(N)  — recursion depth ≤ number of digit characters ≤ N
// SC: O(N)  — recursion call stack (depth proportional to digit count)

function myAtoiHelper(string $s, int $i, int &$result, bool $isNegative): void
{
    $n       = strlen($s);
    $INT_MAX = 2147483647;

    // Base case: end of string or non-digit character → stop recursion
    if ($i >= $n || $s[$i] < '0' || $s[$i] > '9') {
        return;
    }

    $digit = (int)$s[$i]; // Convert current character to integer

    // -------------------------------------------------------------------
    // Overflow guard — IMPORTANT:
    //   $result always holds the RAW ABSOLUTE value here.
    //   Sign is applied in the caller (myAtoiRecursive), NOT here.
    //
    //   INT_MAX =  2147483647  (last digit 7) — positive bound
    //   |INT_MIN| = 2147483648 (last digit 8) — negative bound
    //
    //   If $result > INT_MAX/10 → any digit overflows → store bound, stop.
    //   If $result == INT_MAX/10:
    //     Positive: digit > 7 overflows  → store INT_MAX  (2147483647)
    //     Negative: digit > 8 overflows  → store |INT_MIN| (2147483648)
    // -------------------------------------------------------------------
    $limitLastDigit = $isNegative ? 8 : 7; // Last safe digit for each sign

    if ($result > intdiv($INT_MAX, 10)) {
        // Already too large; store the correct absolute bound and stop
        $result = $isNegative ? 2147483648 : $INT_MAX;
        return;
    }

    if ($result === intdiv($INT_MAX, 10) && $digit > $limitLastDigit) {
        $result = $isNegative ? 2147483648 : $INT_MAX;
        return;
    }

    $result = $result * 10 + $digit; // Safe to accumulate

    myAtoiHelper($s, $i + 1, $result, $isNegative); // Recurse on the next character
}

function myAtoiRecursive(string $s): int
{
    if ($s === '') return 0;

    $i          = 0;
    $n          = strlen($s);
    $isNegative = false;
    $result     = 0;

    // Step 1: Skip leading whitespace
    while ($i < $n && $s[$i] === ' ') {
        $i++;
    }

    // Step 2: Read optional sign
    if ($i < $n && ($s[$i] === '+' || $s[$i] === '-')) {
        $isNegative = ($s[$i] === '-');
        $i++;
    }

    // Step 3: Recursively read digits and accumulate into $result
    myAtoiHelper($s, $i, $result, $isNegative);

    // Step 4: Apply sign
    return $isNegative ? -$result : $result;
}

// --- Run ---
echo "atoi recursive '42':              " . myAtoiRecursive('42')              . "\n"; //  42
echo "atoi recursive '   -42':          " . myAtoiRecursive('   -42')          . "\n"; // -42
echo "atoi recursive '4193 with words': " . myAtoiRecursive('4193 with words') . "\n"; //  4193
echo "atoi recursive '-91283472332':    " . myAtoiRecursive('-91283472332')    . "\n"; // -2147483648

// Function to count substrings having AT MOST K distinct characters.
function atMostKDistinct($s, $k)
{
    // If k becomes negative,
    // no valid substring can exist.
    if ($k < 0) {
        return 0;
    }

    // Left pointer of the sliding window.
    $left = 0;

    // Stores the final count.
    $result = 0;

    // Frequency map:
    // character => frequency
    $freqMap = [];

    $n = strlen($s);

    // Expand the window using the right pointer.
    for ($right = 0; $right < $n; $right++) {

        $char = $s[$right];

        // Add the current character into the frequency map.
        if (!isset($freqMap[$char])) {
            $freqMap[$char] = 1;
        } else {
            $freqMap[$char]++;
        }

        // If distinct characters become greater than K,
        // shrink the window from the left.
        while (count($freqMap) > $k) {

            $leftChar = $s[$left];

            // Remove one occurrence of the left character.
            $freqMap[$leftChar]--;

            // If frequency becomes zero,
            // remove the character completely.
            if ($freqMap[$leftChar] == 0) {
                unset($freqMap[$leftChar]);
            }

            // Move the left pointer.
            $left++;
        }

        // Every substring ending at 'right'
        // and starting between
        // left...right
        // is valid.
        //
        // Count them.
        echo $k."== ".$right."_".$left."\n";
        $result += ($right - $left + 1);
    }

    return $result;
}


// Function to count substrings having EXACTLY K distinct characters.
function countSubstrings($s, $k)
{
    return atMostKDistinct($s, $k)
         - atMostKDistinct($s, $k - 1);
}

$s = "pqpqs";
$k = 2;
echo countSubstrings($s, $k);



function longestPalindrome($s)
{
    $n = strlen($s);

    // If the string has 0 or 1 character, it is already a palindrome.
    if ($n <= 1) {
        return $s;
    }

    // Start index of the longest palindrome.
    $start = 0;

    // Length of the longest palindrome.
    $maxLength = 1;

    // Try every character as the center.
    for ($i = 0; $i < $n; $i++) {

        // Case 1: Odd-length palindrome (center at i)
        $this->expandAroundCenter($s, $i, $i, $start, $maxLength);

        // Case 2: Even-length palindrome (center between i and i+1)
        $this->expandAroundCenter($s, $i, $i + 1, $start, $maxLength);
    }

    // Return the longest palindrome found.
    return substr($s, $start, $maxLength);
}

/**
* Expand from the given center and update the longest palindrome.
*/
public function expandAroundCenter($s, $left, $right, &$start, &$maxLength)
{
    $n = strlen($s);

    // Expand while characters are equal.
    while ($left >= 0 && $right < $n && $s[$left] == $s[$right]) {
        $left--;
        $right++;
    }

    /*
    * We expanded one step too far.
    *
    * Example:
    *
    * String: "abba"
    *
    * left = -1
    * right = 4
    *
    * Actual palindrome length:
    *
    * right - left - 1
    */
    $currentLength = $right - $left - 1;

    // Update answer if we found a longer palindrome.
    if ($currentLength > $maxLength) {
        $maxLength = $currentLength;
        $start = $left + 1;
    }
}

        $s = "ababad";
echo longestPalindrome($s);



function beautySum($s) {
        $length = strlen($s);
        if($length == 0 || $length == 1) return 0;

        $freqMap = [];
        $result = 0;
        
        for($i=0;$i<$length;$i++){
            $freqMap = [];
            for($j=$i;$j<$length;$j++){
                $chr = $s[$j];

                if(isset($freqMap[$chr])) $freqMap[$chr]++;
                else $freqMap[$chr] = 1;

                $result += max($freqMap) - min($freqMap);
            }
        }

        return $result;
        
    }


    function reverseWord($s)
    {
        // Remove leading and trailing spaces
        $s = trim($s);

        // Find the length of the trimmed string
        $stringLength = strlen($s);

        // If the string is empty after trimming, return an empty string
        if ($stringLength == 0) {
            return "";
        }

        // Stores the current word while traversing the string
        $tempWord = "";

        // Stores the final answer in reverse order
        $finalStr = "";

        // Traverse each character of the string
        for ($i = 0; $i < $stringLength; $i++) {

            // Get current character
            $str = $s[$i];

            // If current character is NOT a space,
            // keep building the current word
            if ($str != ' ') {

                $tempWord .= $str;

            } else {

                // Remove extra spaces from the current word
                $tempWord = trim($tempWord);

                // Ignore consecutive spaces
                if (!empty($tempWord)) {

                    // Insert current word at the beginning
                    if (!empty($finalStr)) {
                        $finalStr = $tempWord . " " . $finalStr;
                    } else {
                        // First word
                        $finalStr = $tempWord;
                    }
                }

                // Reset for the next word
                $tempWord = "";
            }
        }

        // Process the last word because the loop ends without a trailing space
        $tempWord = trim($tempWord);

        if (!empty($tempWord)) {
            if (!empty($finalStr)) {
                $finalStr = $tempWord . " " . $finalStr;
            } else {
                $finalStr = $tempWord;
            }
        }

        // Return reversed string
        return $finalStr;
    }
// ============================================================
// COMPARISON SUMMARY
// ============================================================
//
//  Problem                      | Approach                 | TC   | SC
// ------------------------------+--------------------------+------+------
//  Reverse Words in a String    | Scan + prepend words     | O(N) | O(N)
//  Largest Odd Number           | Scan right-to-left       | O(N) | O(N)
//  String to Integer (iterative)| State-machine loop       | O(N) | O(1)
//  String to Integer (recursive)| Same + recursive digits  | O(N) | O(N)
//
//  N = length of the input string.


// ============================================================
// PRACTICE PROBLEMS & APPLICATIONS
// ============================================================
//
//  EASY
//  1. Valid Palindrome (LeetCode 125)
//     → Two-pointer on alphanumeric characters; ignore case
//  2. Reverse String (LeetCode 344)
//     → Classic two-pointer: swap s[l] & s[r] until l >= r
//  3. Longest Common Prefix (LeetCode 14)
//     → Use first string as reference; shrink prefix while it
//       doesn't match subsequent strings
//  4. Valid Anagram (LeetCode 242)
//     → Frequency count array of size 26; check if both match
//  5. Isomorphic Strings (LeetCode 205)
//     → Two maps: char→char mapping both ways must be consistent
//
//  MEDIUM
//  6. Longest Palindromic Substring (LeetCode 5)
//     → Expand-around-center: for each index try odd & even centre
//  7. Rotate String (LeetCode 796)
//     → Check if s is a substring of s+s (using strpos or KMP)
//  8. Longest Substring Without Repeating Characters (LeetCode 3)
//     → Sliding window + hash set; shrink window on duplicate
//  9. String Compression (LeetCode 443)
//     → Two-pointer: count consecutive chars, write count back
// 10. Count and Say (LeetCode 38)
//     → Iteratively build by reading current string run-length style
//
//  HARD
// 11. Minimum Window Substring (LeetCode 76)
//     → Sliding window with two frequency maps; shrink when valid
// 12. Largest Number (LeetCode 179)
//     → Custom sort: compare "$a$b" vs "$b$a" lexicographically
// 13. Wildcard Matching (LeetCode 44)
//     → DP or two-pointer; '?' matches any single char, '*' matches any sequence
// 14. Regular Expression Matching (LeetCode 10)
//     → DP: dp[i][j] = does s[0..i-1] match p[0..j-1]


// ============================================================
// KEY PATTERNS & VARIATIONS FOR REVISION
// ============================================================
//
//  PATTERN 1 — Two-Pointer on strings:
//    Useful for palindrome checks, reversing, and validating.
//    Maintain $left=0, $right=n-1; move inward based on condition.
//    Skip non-alphanumeric in-place for problems like LeetCode 125.
//
//  PATTERN 2 — Sliding Window on strings:
//    Trigger: "longest/shortest substring satisfying condition".
//    Maintain a window [l, r]; expand $r, shrink $l when invalid.
//    Use a frequency map (array[26] for lowercase) for O(1) lookup.
//
//  PATTERN 3 — Frequency / Character count:
//    Allocate int[26] for lowercase letters.
//    Increment for one string, decrement for the other.
//    If all zeros at the end → anagram / isomorphic etc.
//
//  PATTERN 4 — Build result by prepending vs appending:
//    Prepend approach (Reverse Words): each new word goes to the
//    front → automatically reverses order in one pass.
//    Append approach: use an array + implode() to join at the end
//    (avoids O(N²) string concatenation in languages without rope).
//    In PHP, string concatenation is O(N) per op; for very large
//    inputs prefer array + implode().
//
//  PATTERN 5 — State machine for parsing (atoi-style):
//    States: INIT → WHITESPACE → SIGN → DIGITS → DONE.
//    Handle each state explicitly; break as soon as an invalid
//    character is seen in the DIGITS state.
//    Always apply overflow guard BEFORE updating the result.
//
//  PATTERN 6 — Expand-around-center (palindromes):
//    For each index i, expand for both ODD (center=i) and
//    EVEN (center between i and i+1) cases.
//    Track the maximum window seen. O(N²) time, O(1) space.


// ============================================================
// IMPORTANT TIPS & EDGE CASES
// ============================================================
//
//  1. Reverse Words — multiple / leading / trailing spaces:
//     Always trim() the input before processing.
//     Skip empty $temp (consecutive spaces produce empty $temp).
//     The approach here handles all three cases automatically.
//
//  2. Largest Odd Number — all even digits:
//     Return "" (empty string), NOT "0". The problem guarantees
//     the input has no leading zeros (except "0" itself).
//
//  3. atoi — sign ambiguity:
//     Only ONE sign character is allowed. "+-12" → 0 ('+' consumed,
//     '-' is non-digit → digit loop never runs → return 0).
//     "  +0 123" → 0 (space after '0' stops the loop).
//
//  4. atoi — overflow guard order matters:
//     Check overflow BEFORE doing $result = $result * 10 + digit.
//     Checking after is too late — the multiplication itself can
//     silently wrap around or lose precision in other languages
//     (PHP uses 64-bit int on 64-bit platforms, but the LeetCode
//     problem models 32-bit int, so enforce the 32-bit limits manually).
//
//  5. atoi — difference between INT_MAX (2147483647) boundary:
//     Positive overflow: digit > 7  → clamp to INT_MAX
//     Negative overflow: digit > 8  → clamp to INT_MIN
//     (Because |INT_MIN| = 2147483648, one more than INT_MAX.)
//
//  6. PHP string indexing:
//     $s[$i] returns a 1-character string, not an integer.
//     Use (int)$s[$i] or intval($s[$i]) for arithmetic.
//     Directly adding $s[$i] to a number works due to PHP type
//     coercion but is bad practice and can hide bugs.
//
//  7. PHP substr() vs string slicing:
//     substr($s, 0, $len) is O(len) — a new string is created.
//     For algorithms that build results incrementally, prefer
//     appending to an array and implode() at the end to avoid
//     O(N²) total work from repeated concatenation.
//
//  8. Empty / whitespace-only input:
//     reverseWords("   ") → trim gives "" → loop never runs →
//     $temp and $ans stay "" → returns "" ✓
//     myAtoi("") → early return 0 ✓
//     largestOddNumber("") → $high = -1 → loop never runs → "" ✓


$s =  "a good   example";
echo reverseWords($s) ;
function reverseWords($s) {
  $ans = ""; $temp = ""; $s = trim($s);
  $low = 0; $high = strlen($s)-1;

  while ($low <= $high){ 
      if($s[$low] != ' '){
        $temp .= $s[$low];
      } else {
          if($ans != ""){
            $ans = trim($temp)." ".trim($ans);
          }else{
            $ans = trim($temp);
          }
          $temp = "";
      }
      echo $ans."\n";
    $low++;
  }

  if($temp != ' ') {
    if($ans != ""){
      $ans = trim($temp)." ".trim($ans);
    }else{
      $ans = trim($temp);
    }
  }
  return $ans;
}

//Gretest odd number
$num = "2221444";
echo largestOddNumber($num) ;
function largestOddNumber($num) {
  $temp = "";
  $low = 0; $high = strlen($num)-1;

  while($high >= $low){
    if($num[$high] % 2 != 0){
        $num = substr($num, 0, strlen($num)-$high);
    }
    $high--;
  }
  return $num;
}

//myAtoi
$s = "2147 483647";
echo myAtoi($s);
function myAtoi($s) {
      if(empty($s)) return 0;
      $isNegative = false;
      $result = 0;

      $i=0;
      while($i < strlen($s) && $s[$i] == ' '){
        $i++;
      }
      while($i < strlen($s) && ($s[$i] == '-' || $s[$i] == '+')){
        $isNegative = $s[$i] == '-' ?  true : false;
        $i++;
        break;
      }
      $PHP_INT_MAX = 2147483647;
      $PHP_INT_MIN = -2147483648;

      while($i < strlen($s)){
        if(is_numeric($s[$i])){ 
            if($result > (int) ($PHP_INT_MAX / 10)){
              if($isNegative) return $PHP_INT_MIN;
              else return $PHP_INT_MAX;
            }elseif($result == (int) ($PHP_INT_MAX / 10)){
              $digit = intval($s[$i]);
              if($isNegative && $digit > $PHP_INT_MAX % 10){ 
                return $PHP_INT_MIN;
              }
              elseif(!$isNegative && $digit >= $PHP_INT_MAX % 10)
                return $PHP_INT_MAX;
              else
                $result = $result  * 10 + $s[$i];
            }else{
              $result = $result  * 10 + $s[$i];
            }
        }else{
          break;
        }
        $i++;
      }
      if($isNegative) return -$result;
      else return $result;
}


//Recursive approch
//Recursive Approch
$i=0; $result = 0; $isNegative = false; $str = $s;
if(empty($str)) return 0;  
while($i < strlen($str) && $str[$i] == ' ') $i++;
while($i < strlen($str) && ($str[$i] == '+' || $str[$i] == '-')){
    if($str[$i] == '-') $isNegative = true;
    $i++;
    break;
}
myAtoiRecursive($str, $result, $isNegative, $i);
return $isNegative == true ? -$result : $result;


function myAtoiRecursive($str, &$result, &$isNegative, $i)
{
if($i >= strlen($str)) return;        
$PHP_INT_MAX = 2147483647;
$PHP_INT_MIN = -2147483648;
if($i < strlen($str) && is_numeric($str[$i])){
    if($result > (int) ($PHP_INT_MAX/10)){
        if($isNegative) {
          $isNegative = false;
          return $result = $PHP_INT_MIN;
        }
        else return $result = $PHP_INT_MAX;
    }else if($result == (int) ($PHP_INT_MAX/10)){
        $lastDigit = intval($str[$i]);
        if($isNegative && $lastDigit > (int) ($PHP_INT_MAX%10)) {
$isNegative = false;
$result = $PHP_INT_MIN;
return;
}
if(!$isNegative && $lastDigit > (int) ($PHP_INT_MAX%10)) {
  $result = $PHP_INT_MAX;
  return;
}
$result = $result * 10 + $lastDigit;
    }else{
        $result = $result * 10 + $str[$i];
    }
    $i++;
    return myAtoiRecursive($str, $result, $isNegative, $i);
}else{
    return;
}
} 


?>










