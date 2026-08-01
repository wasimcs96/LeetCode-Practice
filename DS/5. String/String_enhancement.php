<?php
// ================================================================================
//  STRINGS — PRODUCTION-QUALITY INTERVIEW REVISION GUIDE  (Enhanced Edition v2)
// ================================================================================
//  Source file   : String.php  (original, untouched — kept as-is)
//  This file     : String_enhancement.php — a single source of truth for the
//                  String topic, built ON TOP OF the original file's logic.
//  Target bar    : Senior/Staff SDE — Saudi Arabia, UAE (Dubai/Abu Dhabi),
//                  India Tier-1/Tier-2 (₹60LPA+), FAANG-level standards.
//  Built against : DS/00-Interview-Enhancement-Master-Prompt.md (the
//                  reusable spec for every topic file in this repo)
//
//  HOW TO USE THIS FILE
//  ---------------------
//  Each problem below follows the same structure used in Array_enhancement.php:
//    1. How to Identify This Pattern   (keywords, hidden hints, mistakes)
//    2. Problem Understanding          (what/why/constraints/analogy)
//    3. Interview-Ready Add-Ons        (companies, constraints->complexity,
//       time-boxing, 60-second verbal pitch)
//    4. Approaches (Brute -> Better -> Optimal): intuition, algorithm,
//       code, complexity, pros/cons
//    5. Complete Dry Run (table form)
//    6. Patterns Used (primary + secondary)
//    7. Pattern Recognition Tips (when to use / not use / similar problems)
//    8. Edge Cases
//    9. Additional Senior-Level Prep   (test assertions, PHP gotchas,
//       mistake-recovery tip, follow-up/scale-up extensions)
//   10. Interview Discussion (Q&A you should be able to answer instantly)
//   11. Related Problems (Easy -> Medium -> Hard)
//   12. (implicit) Clean, commented, production-quality PHP code
//
//  A BUG LOG is kept in Section Z at the end of this file. This topic's bug
//  log is unusually important: the original String.php contains a PARSE
//  ERROR (an access modifier used outside a class) that prevents the
//  ENTIRE original file from running at all via `php String.php` — see
//  Bug 1 in Section Z for the full trace. Every function in this enhanced
//  file has been fixed and is independently runnable.
// ================================================================================


// ================================================================================
//  SECTION 0 — MASTER PATTERN RECOGNITION GUIDE (read this first, every time)
// ================================================================================
//
//  String problems in interviews almost always reduce to ONE of these 6 engines.
//  Train yourself to name the engine within 30 seconds of reading the problem.
//
//  ┌────────────────────────────┬──────────────────────────────────────────────┐
//  │ ENGINE                     │ TRIGGER KEYWORDS / SIGNALS                    │
//  ├────────────────────────────┼──────────────────────────────────────────────┤
//  │ 1. Two Pointers            │ "palindrome check", "reverse in place",       │
//  │                             │ "compare from both ends"                      │
//  │ 2. Sliding Window          │ "longest/shortest substring", "at most K      │
//  │                             │ distinct characters", "contains all of"       │
//  │ 3. Frequency Map / Hashing │ "anagram", "isomorphic", "same characters",   │
//  │                             │ "sort by frequency"                           │
//  │ 4. State-Machine Parsing   │ "convert string to integer", "parse", sign/   │
//  │                             │ digit/whitespace handling (atoi-style)        │
//  │ 5. Expand-Around-Center /  │ "longest palindromic substring", "palindrome  │
//  │    Interval DP on strings  │ partitioning" -- O(n^2) center-expansion or DP │
//  │ 6. Build-by-Prepend/Append │ "reverse the words", building a result while  │
//  │    + String-Matching Trick │ scanning once; "rotate string" -> check        │
//  │                             │ substring of (s+s)                            │
//  └────────────────────────────┴──────────────────────────────────────────────┘
//
//  COMMON MISTAKES WHILE IDENTIFYING THE PATTERN (across this entire topic)
//  --------------------------------------------------------------------------
//  - Reaching for a full HashMap<char,count> when a fixed-size array[26] would
//    do (lowercase-only constraints) -- both are O(1)-ish per op, but the
//    array version is faster in practice and signals you read the constraints.
//  - Using Sliding Window without a clear SHRINK condition -- if you can't
//    articulate "the window shrinks when ___ becomes true," you don't have a
//    sliding window solution yet, you have a nested loop pretending to be one.
//  - Forgetting that PHP string indexing ($s[$i]) returns a 1-CHARACTER
//    STRING, not an integer -- arithmetic on it works via implicit coercion
//    but comparing it to an int literal without casting is a common source
//    of subtle bugs.
//  - For "rotate string" style problems, missing the classic trick that a
//    string is a rotation of another iff it's a substring of the other
//    concatenated with itself (s2 is a rotation of s1 iff s2 is a substring
//    of s1+s1) -- brute-force checking N rotations one by one still works
//    but is a weaker answer.
//  - For palindrome problems, confusing "expand around center" (O(n^2) time,
//    O(1) space, easy to derive live) with Manacher's Algorithm (O(n) time,
//    much harder to derive live) -- know that Manacher's exists and can be
//    NAMED as a follow-up, but expand-around-center is what you should
//    actually implement under interview time pressure unless explicitly
//    asked for the linear-time version.
//
//  GENERAL COMPLEXITY CHEAT SHEET
//  --------------------------------------------------------------------------
//  Two Pointers                        : O(n) time,        O(1) space
//  Sliding Window                      : O(n) time,        O(k) space (k = alphabet/window state size)
//  Frequency Map / Hashing             : O(n) time,        O(1) space (fixed alphabet) or O(n)
//  State-Machine Parsing (atoi)        : O(n) time,        O(1) space
//  Expand-Around-Center                : O(n^2) time,      O(1) space
//  Manacher's Algorithm (named, rarely implemented live) : O(n) time, O(n) space
//  Build-by-Prepend/Append             : O(n) time,        O(n) space
//
// ================================================================================


// ================================================================================
//  PROBLEM 1 — LC 1021: REMOVE OUTERMOST PARENTHESES
// ================================================================================
//
//  --- How to Identify This Pattern ---
//  Keywords     : "primitive decomposition of a valid parentheses string",
//                 "remove the outermost parentheses of every primitive"
//  Signal       : Any "valid parentheses" structural problem is a counter-
//                 based (or explicit stack-based) balance-tracking problem --
//                 you rarely need an actual stack here since you only need
//                 the DEPTH, not the actual matched characters.
//  Common mistake: Reaching for an explicit stack of characters when a
//                 single integer depth counter is sufficient -- a full
//                 stack is only needed when you must recall WHICH bracket
//                 type was opened (irrelevant here, since there's only one type).
//
//  --- Problem Understanding ---
//  What: a valid parentheses string decomposes uniquely into "primitive"
//        substrings (each balanced on its own, non-empty, cannot be split
//        further at depth 0). For each primitive, remove its outermost
//        matching pair and concatenate the results.
//  Why it exists: teaches that "depth == 1" is the litmus test for "this is
//    an outermost bracket," a reusable idea whenever nesting depth defines
//    structure (JSON-like parsing, expression trees).
//
//
//  --- 🎯 Interview-Ready Add-Ons (constraints, timing, pitch) ---
//  Asked at      : Amazon, Google, Facebook/Meta -- a common warm-up testing balance/depth-counter thinking before harder stack problems.
//  Constraints   : 1 <= s.length <= 10^5, s is a valid parentheses string -> O(n) single pass with a depth counter is expected; an explicit stack works too but is unnecessary overhead here.
//  Time-boxing   : Total ~6 min: 1 min restate 'primitive decomposition', 1 min explain why only depth matters (not a full stack), 4 min code + dry run.
//  60-Sec Pitch  : "I track nesting depth with a single counter -- a '(' is kept only if it's NOT the very first one of a primitive (depth becomes 2+), and a ')' is kept only if it's NOT the very last one closing a primitive (depth returns to something above 0)."
//
//  --- Approach: Single-Pass Depth Counter (Optimal, the only approach needed) ---
//  Intuition : track the current nesting depth with a counter. A '(' is
//              "outermost" exactly when it takes depth from 0 to 1 (so
//              INCREMENT the counter, THEN check if the PRE-increment value
//              was 0 -- equivalently, only keep it if depth != 1 AFTER
//              incrementing). A ')' is "outermost" exactly when it takes
//              depth from 1 to 0 (DECREMENT first, keep only if depth != 0
//              after decrementing).
//  TC: O(n)  |  SC: O(n) for the output string
//
function removeOuterParentheses(string $s): string
{
    $result = '';
    $depth = 0;   // Current nesting depth of parentheses

    for ($i = 0; $i < strlen($s); $i++) {
        $char = $s[$i];

        if ($char === '(') {
            $depth++;                       // Entering one level deeper
            if ($depth !== 1) {              // depth==1 means THIS '(' was the outermost one -> drop it
                $result .= $char;
            }
        } else {                             // $char === ')'
            $depth--;                        // Leaving one level
            if ($depth !== 0) {              // depth==0 (after decrement) means THIS ')' was the outermost one -> drop it
                $result .= $char;
            }
        }
    }

    return $result;
}

//  --- Dry Run ---  s = "(()())(())"
//  ┌───┬──────┬───────┬────────────────────┬──────────┐
//  │ i │ char │ depth │ kept?              │ result   │
//  ├───┼──────┼───────┼────────────────────┼──────────┤
//  │ 0 │ (    │ 1     │ no (outermost open)│ ""       │
//  │ 1 │ (    │ 2     │ yes                │ "("      │
//  │ 2 │ )    │ 1     │ yes                │ "()"     │
//  │ 3 │ (    │ 2     │ yes                │ "()("    │
//  │ 4 │ )    │ 1     │ yes                │ "()()"   │
//  │ 5 │ )    │ 0     │ no (outermost close)│ "()()"  │
//  │ 6 │ (    │ 1     │ no (outermost open)│ "()()"   │
//  │ 7 │ (    │ 2     │ yes                │ "()()("  │
//  │ 8 │ )    │ 1     │ yes                │ "()()()" │
//  │ 9 │ )    │ 0     │ no (outermost close)│ "()()()"│
//  └───┴──────┴───────┴────────────────────┴──────────┘
//  Output: "()()()"

echo "Remove Outer Parens '(()())(())': '" . removeOuterParentheses("(()())(())") . "'\n";  // "()()()"

//  --- Patterns Used ---   Primary: Depth-Counter Scan.  Secondary: none (stack not needed).
//  --- Recognition Tips ---
//    Use when: only the NESTING DEPTH matters, not which specific characters
//              are matched.
//    Don't use when: multiple bracket TYPES exist ( ), [ ], { } -- then you
//              genuinely need a stack to verify matching TYPES, not just depth.
//    Similar problems: Valid Parentheses (LC20, needs a real stack), Maximum
//              Nesting Depth of the Parentheses (LC1614, literally just the
//              max depth value from this same counter).
//  --- Edge Cases ---
//    - Empty string -> loop never runs, returns "". Correct.
//    - Single primitive "()" -> both characters are outermost, dropped, returns "".
//    - Already has no removable structure, e.g. "()()"->"" for both -> returns "".
//
//  --- 🧪 Additional Senior-Level Prep (tests, gotchas, follow-ups) ---
//  assert(removeOuterParentheses("(()())(())") === "()()()");
//  assert(removeOuterParentheses("(()())(())(()(()))") === "()()()()(())");
//  assert(removeOuterParentheses("()()") === "");
//  assert(removeOuterParentheses("") === "");
//  PHP Gotcha       : String concatenation with `.=` in a loop is O(n) per operation in the worst case for very
//                     large strings due to potential reallocation -- for this problem's typical constraints (n <= 10^5) it's a
//                     non-issue, but for much larger inputs prefer collecting characters into an array and imploding once.
//  Mistake Recovery : If your output has an extra or missing bracket, check whether you're testing depth BEFORE or
//                     AFTER the increment/decrement -- the open-bracket check must happen AFTER incrementing, the
//                     close-bracket check must happen AFTER decrementing; swapping this order is the classic off-by-one here.
//  Follow-Up / Scale-Up:
//    - What if there were multiple bracket types mixed together? -> depth-counter alone is insufficient; you'd need a real
//      stack to also verify TYPE matching, not just count balance.
//    - What if the string is streamed character-by-character (not available all at once)? -> the depth counter and
//      output buffer both update naturally in O(1) per character, so this algorithm is already streaming-friendly.
//
//  --- Interview Discussion ---
//    Q: Why does checking depth AFTER the increment/decrement work correctly?
//    A: An opening bracket is "outermost" precisely when it's the FIRST '(' of a new primitive -- i.e., it moves
//       depth from 0 to 1. Checking depth==1 right after incrementing captures exactly that moment. Symmetrically,
//       a closing bracket is outermost when it moves depth from 1 to 0, captured by checking depth==0 right after decrementing.
//    Q: Could you solve this with an explicit stack instead?
//    A: Yes -- push on '(', pop on ')'; keep a character only when the stack size (before push / after pop)
//       isn't 0 in the relevant direction. It's equivalent but strictly more memory (O(n) stack vs O(1) counter) for no benefit here.
//  --- Related Problems ---
//    Easy   : Maximum Nesting Depth of the Parentheses (LC1614).
//    Medium : Valid Parentheses (LC20) -- needs a real stack for multiple bracket types.
//    Medium : Minimum Add to Make Parentheses Valid (LC921) -- similar counter-based balance tracking.


// ================================================================================
//  PROBLEM 2 — LC 151: REVERSE WORDS IN A STRING
// ================================================================================
//
//  --- How to Identify This Pattern ---
//  Keywords     : "reverse the order of words", "multiple spaces", "leading/
//                 trailing spaces"
//  Signal       : "Reverse the ORDER of words" (not the characters within
//                 each word) + messy whitespace handling is the classic
//                 Build-by-Prepend trigger -- prepending each new word to
//                 the growing result automatically reverses word order in
//                 a SINGLE pass, no second reversal step needed.
//  Common mistake: Using `explode(' ', $s)` naively -- multiple consecutive
//                 spaces produce EMPTY STRING entries in the resulting
//                 array, which then need explicit filtering; many
//                 candidates forget this and produce extra blank "words"
//                 in the output.
//
//  --- Problem Understanding ---
//  What: given a string with words separated by spaces (possibly multiple,
//        leading, or trailing spaces), return the words in REVERSE order,
//        single-space separated, no leading/trailing spaces.
//  Why it exists: tests careful whitespace/edge handling under a
//    deceptively simple-sounding prompt -- this is a very common "gotcha
//    on the edge cases, not the core algorithm" interview question.
//  Real-world analogy: reversing the word order of a sentence while typing
//    it backwards, skipping any accidental double-spaces from a sloppy keyboard.
//
//
//  --- 🎯 Interview-Ready Add-Ons (constraints, timing, pitch) ---
//  Asked at      : Amazon, Microsoft, Google, Bloomberg -- extremely common, often used specifically to test edge-case discipline around whitespace.
//  Constraints   : 1 <= s.length <= 10^4, s contains English letters, spaces, and digits, at least one non-space character -> O(n) time, O(n) space expected.
//  Time-boxing   : Total ~8 min: 2 min restate + explicitly call out the multi-space/leading/trailing-space edge cases, 6 min single-pass prepend solution + dry run.
//  60-Sec Pitch  : "I scan the trimmed string once, building each word into a buffer, and PREPEND each completed word to the growing answer -- prepending naturally reverses word order in a single pass without a separate reversal step, and skipping empty buffers handles consecutive spaces for free."
//
//  --- Approach 1: Split + Filter + Reverse + Join (Better, uses more built-ins) ---
//  Intuition : split on whitespace via a regex that collapses runs of
//              spaces, filter out any resulting empty entries, reverse the
//              array, join with single spaces.
//  TC: O(n)  |  SC: O(n)
//
function reverseWordsBuiltIn(string $s): string
{
    $words = preg_split('/\s+/', trim($s));      // Collapse any run of whitespace into one delimiter
    return implode(' ', array_reverse($words));
}

//  --- Approach 2: Single-Pass Build-by-Prepend (Optimal, demonstrates the core technique) ---
//  Intuition : scan the trimmed string once, accumulating characters into a
//              temporary word buffer. Whenever a space is hit AND the
//              buffer is non-empty, PREPEND that word to the answer (not
//              append) -- prepending naturally reverses word order without
//              a separate reversal pass. Skip spaces when the buffer is
//              already empty (handles consecutive spaces for free).
//  TC: O(n)  |  SC: O(n)
//
function reverseWords(string $s): string
{
    $s = trim($s);            // Remove leading and trailing whitespace up front
    $n = strlen($s);
    $answer = '';              // Final reversed-words result, built via prepending
    $currentWord = '';         // Word currently being accumulated

    for ($i = 0; $i < $n; $i++) {
        if ($s[$i] !== ' ') {
            $currentWord .= $s[$i];     // Keep building the current word
        } else {
            if ($currentWord !== '') {   // A real word just finished (not a run of extra spaces)
                $answer = ($answer === '') ? $currentWord : $currentWord . ' ' . $answer;   // PREPEND -> reverses order
                $currentWord = '';
            }
            // currentWord === '' here means we hit a SECOND consecutive space -- just skip it
        }
    }

    // The loop never sees a trailing space after the last word (input was trimmed), so handle it here
    if ($currentWord !== '') {
        $answer = ($answer === '') ? $currentWord : $currentWord . ' ' . $answer;
    }

    return $answer;
}

//  --- Dry Run ---  s = "a good   example"  (already trimmed)
//  ┌───┬──────┬─────────────┬───────────────────┐
//  │ i │ char │ currentWord │ answer             │
//  ├───┼──────┼─────────────┼───────────────────┤
//  │ 0 │ a    │ "a"         │ ""                 │
//  │ 1 │ ' '  │ "" (reset)  │ "a"                │
//  │ 2-5│g,o,o,d│ "good"    │ "a"                │
//  │ 6 │ ' '  │ "" (reset)  │ "good a"           │
//  │ 7 │ ' '  │ "" (skip)   │ "good a"           │
//  │ 8 │ ' '  │ "" (skip)   │ "good a"           │
//  │9-15│example│ "example" │ "good a"           │
//  │end│  --  │ "example"   │ "example good a"   │
//  └───┴──────┴─────────────┴───────────────────┘
//  Output: "example good a"

echo "Reverse Words 'a good   example': '" . reverseWords("a good   example") . "'\n";  // 'example good a'
echo "Reverse Words '  hello world  ':  '" . reverseWords("  hello world  ")  . "'\n";  // 'world hello'

//  --- Patterns Used ---   Primary: Build-by-Prepend Single Pass.  Secondary: none.
//  --- Recognition Tips ---
//    Use when: word ORDER must reverse but word CONTENT stays intact, with
//              messy whitespace in the input.
//    Don't use when: you need to reverse CHARACTERS within each word too --
//              that's a different (simpler) two-pointer-per-word problem.
//    Similar problems: Reverse Words in a String III (LC557, reverse
//              characters WITHIN each word, keep word order), Reverse String (LC344).
//
//  --- 🧪 Additional Senior-Level Prep (tests, gotchas, follow-ups) ---
//  assert(reverseWords("a good   example") === "example good a");
//  assert(reverseWords("  hello world  ") === "world hello");
//  assert(reverseWords("a") === "a");
//  assert(reverseWords("   ") === "");     // whitespace-only input
//  PHP Gotcha       : `preg_split('/\s+/', trim($s))` on an ALREADY-EMPTY string (after trim) returns [""], a
//                     single-element array containing an empty string, NOT an empty array -- if using Approach 1, guard
//                     for this explicitly (the single-pass Approach 2 handles it correctly with no special-casing).
//  Mistake Recovery : If your output has an extra leading/trailing space, the fix is almost always a missing `trim()`
//                     up front, not a bug in the prepend logic itself -- verify the trim happens BEFORE the main loop.
//  Follow-Up / Scale-Up:
//    - What if the input is a massive string streamed in chunks (can't fit in memory)? -> process word-by-word as
//      chunks arrive, but note that REVERSING the order fundamentally requires seeing the whole input first (or
//      buffering to disk / a deque) -- this can't be done in true O(1) memory for a streaming reversal.
//    - Reverse words but keep leading/trailing spaces exactly as they were (a stricter LC186-style in-place variant)?
//      -> reverse the WHOLE string first, then reverse each word back individually -- a different, in-place-friendly technique.
//
//  --- Interview Discussion ---
//    Q: Why prepend instead of append-then-reverse-the-array?
//    A: Both are O(n) and valid -- prepending demonstrates you can reverse order in a SINGLE pass without a second
//       explicit reversal step, which is a slightly more elegant answer to volunteer, though either is acceptable.
//    Q: How would you do this truly in-place (O(1) extra space), as LC186 (premium) asks?
//    A: Reverse the entire character array first (e.g., "the sky is blue" -> "eulb si yks eht"), then reverse each
//       individual word back to normal within that reversed string -- the word ORDER stays reversed while each
//       word's internal characters are corrected, all using only swaps, no extra buffer.
//  --- Related Problems ---
//    Easy   : Reverse Words in a String III (LC557).
//    Medium : Reverse Words in a String II - in-place (LC186, premium).


// ================================================================================
//  PROBLEM 3 — LC 1903: LARGEST ODD NUMBER IN A STRING
// ================================================================================
//
//  --- How to Identify This Pattern ---
//  Keywords     : "largest-value odd number", digit string, "no leading zeros"
//  Signal       : Any "is this number odd/even" question about a numeric
//                 STRING reduces to checking only the LAST digit -- never
//                 examine the whole number, that's the entire insight.
//  Common mistake: Trying to actually convert the substring to an integer
//                 to check oddness -- for very long digit strings this
//                 could exceed native integer range; checking the last
//                 CHARACTER directly avoids that entirely and is O(1) per check.
//
//  --- Problem Understanding ---
//  What: given a digit string, return the largest-value substring (a
//        PREFIX, since removing trailing digits only ever shrinks the
//        value) that represents an odd number; return "" if none exists.
//  Why it exists: the "oddness depends only on the last digit" observation
//    is a small but genuinely useful number-theory shortcut that shows up
//    disguised in several other problems.
//
//
//  --- 🎯 Interview-Ready Add-Ons (constraints, timing, pitch) ---
//  Asked at      : Amazon, Adobe -- a quick warm-up testing the 'only the last digit determines parity' number-theory shortcut.
//  Constraints   : 1 <= num.length <= 10^5, num contains only digits, no leading zeros (except '0' itself) -> O(n) single right-to-left scan is expected.
//  Time-boxing   : Total ~5 min: near-instant once the 'scan from the right' insight clicks -- if it's taking longer, the insight (not the code) is the bottleneck.
//  60-Sec Pitch  : "Since a number's parity depends only on its last digit, I scan from the right for the first odd digit -- the prefix ending there is the largest possible odd-valued substring, since any longer prefix would end in an even digit."
//
//  --- Approach: Scan Right-to-Left for the Rightmost Odd Digit (Optimal, only approach needed) ---
//  Intuition : the LARGEST valid odd prefix is obtained by keeping as many
//              leading digits as possible -- so scan from the right end
//              looking for the first (rightmost) odd digit; the prefix
//              ending there is the answer, since any prefix ending further
//              right would end in an even digit (not odd) and any prefix
//              ending further left is strictly smaller in value.
//  TC: O(n)  |  SC: O(n) for the returned substring
//
function largestOddNumber(string $num): string
{
    $n = strlen($num);

    for ($i = $n - 1; $i >= 0; $i--) {          // Scan from the right for the rightmost odd digit
        if (((int) $num[$i]) % 2 !== 0) {        // Explicit (int) cast -- do not rely on implicit coercion
            return substr($num, 0, $i + 1);       // Prefix up to and including this odd digit is the answer
        }
    }

    return '';   // No odd digit anywhere -- no valid odd number exists
}

//  --- Dry Run ---  num = "2221444"
//  ┌───────┬───────┬─────────────────────────┐
//  │ index │ digit │ odd?                    │
//  ├───────┼───────┼─────────────────────────┤
//  │ 6     │ 4     │ even -> continue         │
//  │ 5     │ 4     │ even -> continue         │
//  │ 4     │ 4     │ even -> continue         │
//  │ 3     │ 1     │ ODD -> return substr(0,4)│
//  └───────┴───────┴─────────────────────────┘
//  Output: "2221"

echo "Largest Odd '2221444': '" . largestOddNumber('2221444') . "'\n";  // '2221'
echo "Largest Odd '35619':   '" . largestOddNumber('35619')   . "'\n";  // '35619' (last digit 9 is odd)
echo "Largest Odd '4206':    '" . largestOddNumber('4206')    . "'\n";  // '' (all even digits)

//  --- Patterns Used ---   Primary: Right-to-Left Scan.  Secondary: none.
//  --- Recognition Tips ---
//    Use when: "is this number odd/even" needs checking on a numeric string
//              too large to safely convert to a native integer.
//    Don't use when: leading zeros need special stripping -- LC1903
//              guarantees no leading zeros except a lone "0", so this
//              implementation doesn't need extra zero-trimming logic; if a
//              variant DOES allow leading zeros in the input, add an
//              explicit strip step.
//    Similar problems: Check if a number is odd/even in general (trivial
//              extension), Remove Trailing Zeros From a String (LC2710, a
//              similar "trim from one end based on a digit property" idea).
//
//  --- 🧪 Additional Senior-Level Prep (tests, gotchas, follow-ups) ---
//  assert(largestOddNumber('2221444') === '2221');
//  assert(largestOddNumber('35619') === '35619');
//  assert(largestOddNumber('4206') === '');
//  assert(largestOddNumber('5') === '5');
//  assert(largestOddNumber('0') === '');    // '0' is even -- correctly returns empty
//  PHP Gotcha       : `$num[$i] % 2` (without an explicit (int) cast) relies on PHP's implicit numeric-string-to-int
//                     coercion during arithmetic -- it happens to work here, but casting explicitly with `(int) $num[$i]`
//                     is safer and self-documenting; the original file's uncast version is a code smell to flag if you see it.
//  Mistake Recovery : If your answer is missing digits at the front, double-check you're using `substr($num, 0, $i + 1)`
//                     and not `substr($num, 0, $i)` -- the `+1` is required because $i is the INDEX of the odd digit,
//                     but substr's length parameter needs the COUNT of characters to include.
//  Follow-Up / Scale-Up:
//    - What if you needed the SMALLEST odd number obtainable by deleting digits (not just taking a prefix)? -> a
//      structurally different, harder problem requiring a greedy-with-stack or DP approach, not a simple right-to-left scan.
//    - Input is a live stream of digits and you need the answer after EVERY digit arrives? -> track the rightmost
//      odd-digit index seen so far as new digits stream in; O(1) amortized update per new digit.
//
//  --- Interview Discussion ---
//    Q: Why scan right-to-left instead of left-to-right?
//    A: The answer must be a PREFIX (since it needs to be the "largest odd number," and any prefix is
//       automatically larger in value than a shorter prefix). We want the LONGEST valid prefix, so we look for the
//       rightmost position where cutting there is still valid (ends in an odd digit) -- scanning from the right finds
//       that longest valid cut point directly, in one pass, without needing to compare candidate prefixes against each other.
//    Q: Why does only the LAST digit matter for odd/even?
//    A: A number's parity is entirely determined by its last digit in base 10 -- every other digit contributes a
//       multiple of 10 (always even), so it can never flip the overall parity.
//  --- Related Problems ---
//    Easy   : Remove Trailing Zeros From a String (LC2710).
//    Medium : Smallest Odd Number in Range (conceptual variant, not a direct LC problem).


// ================================================================================
//  PROBLEM 4 — LC 14: LONGEST COMMON PREFIX
// ================================================================================
//
//  --- How to Identify This Pattern ---
//  Keywords     : "longest common prefix", "array of strings", "shared prefix"
//  Signal       : "Common prefix across MULTIPLE strings" reduces the search
//                 space the instant you realize you only need to compare
//                 the LEXICOGRAPHICALLY SMALLEST and LARGEST strings after
//                 sorting -- their shared prefix IS the answer for the
//                 whole set, since every other string lies "between" them alphabetically.
//  Common mistake: Comparing every string against every other string
//                 (O(n^2 * m)) instead of the smarter sort-then-compare-
//                 endpoints trick, or instead of the equally valid
//                 "compare all strings against the first one, character by
//                 character" vertical-scanning approach.
//
//  --- Problem Understanding ---
//  What: given an array of strings, find the longest string that is a
//        prefix of ALL of them; return "" if no common prefix exists.
//  Why it exists: a foundational "reduce N-way comparison to a smarter
//    2-way or column-wise comparison" problem -- the sort-based trick in
//    particular is a nice example of exploiting a property (lexicographic
//    ordering) that isn't obviously relevant at first glance.
//
//
//  --- 🎯 Interview-Ready Add-Ons (constraints, timing, pitch) ---
//  Asked at      : Amazon, Google, Microsoft, Facebook/Meta -- one of the most frequently asked easy string problems industry-wide.
//  Constraints   : 1 <= strs.length <= 200, 0 <= strs[i].length <= 200 -> O(n*m) Vertical Scanning or O(n log n + m) sort-based approach, both expected as acceptable answers.
//  Time-boxing   : Total ~7 min: 1 min restate, 3 min Vertical Scanning, 3 min sort-based alternative + explain the trade-off between the two.
//  60-Sec Pitch  : "I either scan column by column comparing the first string against all others (Vertical Scanning), or sort the array and compare only the lexicographically first and last strings -- both exploit the fact that a common prefix must be shared by every string, so I only need to find the first mismatch point."
//
//  --- Approach 1: Vertical Scanning (Better, no sort needed) ---
//  Intuition : compare the first string's characters, column by column,
//              against every OTHER string at that same column index; stop
//              at the first mismatch or the first string that's too short.
//  TC: O(n*m) where n=number of strings, m=length of the shortest string  |  SC: O(1) extra
//
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

//  --- Approach 2: Sort + Compare Endpoints (Optimal for readability, same asymptotic cost) ---
//  Intuition : after sorting the array lexicographically, the common
//              prefix of the ENTIRE array equals the common prefix of just
//              the FIRST and LAST strings -- because every string in
//              between shares at least as much of a prefix with its
//              neighbors as the two extremes do with each other.
//  TC: O(n log n) for the sort + O(m) for the comparison  |  SC: O(1) extra (sort may or may not mutate in place)
//
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

//  --- Dry Run ---  strs = ["flower", "flow", "flight"]
//  After sort: ["flight", "flow", "flower"] -> first="flight", last="flower"
//  i=0: 'f'==='f' -> "f"
//  i=1: 'l'==='l' -> "fl"
//  i=2: 'i' vs 'o' -> mismatch -> stop
//  Output: "fl"

echo "LCP ['flower','flow','flight']: '" . longestCommonPrefix(["flower", "flow", "flight"]) . "'\n";  // "fl"
echo "LCP ['dog','racecar','car']:    '" . longestCommonPrefix(["dog", "racecar", "car"])     . "'\n";  // ""

//  --- Patterns Used ---   Primary: Sort + Endpoint Comparison.  Secondary: Vertical Scanning (alternative).
//  --- Recognition Tips ---
//    Use when: comparing a common property across MANY strings at once.
//    Don't use when: the array is very large and sorting cost matters more
//              than scanning cost -- Vertical Scanning avoids the O(n log n)
//              sort entirely and is often the better answer to lead with.
//    Similar problems: Longest Common Subsequence (LC1143, a completely
//              different DP problem despite the similar name -- doesn't
//              require a PREFIX, any subsequence counts), Longest Common
//              Prefix using a Trie (a scalable alternative for very large
//              string sets or repeated queries).
//
//  --- 🧪 Additional Senior-Level Prep (tests, gotchas, follow-ups) ---
//  assert(longestCommonPrefix(["flower","flow","flight"]) === "fl");
//  assert(longestCommonPrefix(["dog","racecar","car"]) === "");
//  assert(longestCommonPrefix(["single"]) === "single");
//  assert(longestCommonPrefix([]) === "");
//  assert(longestCommonPrefix(["", "abc"]) === "");   // one empty string -- no common prefix possible
//  PHP Gotcha       : `sort()` mutates the array IN PLACE and re-indexes keys -- if the caller needs to preserve the
//                     original array (e.g., original ordering matters elsewhere), pass a COPY, since PHP arrays are
//                     value types on assignment but this function receives `array $strs` by value already, so the
//                     caller's original array is safe, only the LOCAL copy inside this function is mutated.
//  Mistake Recovery : If the sort-based approach gives a WRONG (too-long) prefix, double-check you're comparing
//                     `$first` and `$last` from the SORTED array, not the original unsorted order -- a common slip is
//                     sorting into a new variable but then still reading from the original `$strs[0]` and `$strs[count-1]`.
//  Follow-Up / Scale-Up:
//    - MANY repeated prefix queries against the SAME large string set? -> build a Trie once (Pattern #16 cross-reference);
//      each query then walks the Trie in O(query length) instead of re-scanning all strings every time.
//    - What if strings could be extremely long and there are millions of them (doesn't fit in memory)? -> a
//      distributed/streaming variant: maintain a running "current common prefix" and fold in each new string one at
//      a time, shrinking the running prefix as needed -- O(1) memory beyond the current prefix itself.
//
//  --- Interview Discussion ---
//    Q: Why does comparing only the sorted first and last string work for the WHOLE array?
//    A: After lexicographic sorting, any two ADJACENT strings share a prefix at least as long as the prefix shared
//       by the two EXTREME (first and last) strings -- because lexicographic ordering is transitive on shared
//       prefixes. So the minimum shared prefix across the whole sorted array is realized at the two endpoints.
//    Q: Which approach would you actually lead with in an interview?
//    A: Vertical Scanning -- it avoids the O(n log n) sort entirely, achieving the same O(n*m) bound as the
//       sort-based approach's O(n log n + m) in the typical case where m (string length) is comparable to or larger
//       than log n, and it doesn't mutate the input array.
//  --- Related Problems ---
//    Medium : Longest Common Subsequence (LC1143) -- different problem, don't confuse the two.
//    Hard    : Longest Common Prefix via Trie for repeated queries (design-style follow-up).


// ================================================================================
//  PROBLEM 5 — LC 205: ISOMORPHIC STRINGS
// ================================================================================
//
//  --- How to Identify This Pattern ---
//  Keywords     : "isomorphic", "characters in s can be replaced to get t",
//                 "one-to-one mapping"
//  Signal       : "Can this string be transformed into that one via a
//                 CONSISTENT character substitution" is always a
//                 bidirectional-mapping problem -- you need to verify the
//                 mapping holds in BOTH directions (s->t AND t->s), or use
//                 the "first occurrence index must match" trick shown below.
//  Common mistake: Only checking the mapping in ONE direction (s[i] -> t[i])
//                 -- this misses cases where two DIFFERENT characters in s
//                 both map to the SAME character in t (e.g., s="ab", t="aa"
//                 -- 'a'->'a' and 'b'->'a' each look fine individually, but
//                 this isn't a valid one-to-one isomorphism).
//
//  --- Problem Understanding ---
//  What: two strings are isomorphic if characters in s can be replaced to
//        get t, with each character mapping to exactly one other character
//        (a bijection on the characters that actually appear), preserving order.
//  Why it exists: tests whether you catch the ONE-TO-ONE requirement (not
//    just "does a mapping exist" but "is it consistent and non-colliding
//    in both directions").
//
//
//  --- 🎯 Interview-Ready Add-Ons (constraints, timing, pitch) ---
//  Asked at      : Amazon, Google, Bloomberg -- a favorite for catching candidates who only check the mapping in one direction.
//  Constraints   : 1 <= s.length <= 5*10^4, s.length === t.length -> O(n) two-map approach expected; the strpos() trick is a valid but quietly O(n^2) alternative worth naming honestly.
//  Time-boxing   : Total ~8 min: 2 min restate + the ab/aa collision example, 6 min two-map bidirectional solution + dry run.
//  60-Sec Pitch  : "I maintain two hashmaps (s-char to t-char and t-char to s-char) and verify BOTH directions stay consistent on every pair -- this catches not just 'one character maps to two different targets' but also the reverse collision where two different characters map to the same target."
//
//  --- Approach 1: strpos() First-Occurrence Trick (matches original file, clever but has a hidden cost) ---
//  Intuition : for each position i, the first occurrence of s[i] in s and
//              the first occurrence of t[i] in t must be at the SAME
//              index -- if they're at different indices, the mapping is inconsistent.
//  TC: O(n^2) worst case (strpos scans from the start each time)  |  SC: O(1) extra
//  Disadvantage: strpos() re-scans from the beginning on every call, making
//              this quadratic in the worst case despite looking like a
//              simple one-pass loop -- worth naming this cost explicitly.
//
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

//  --- Approach 2: Two HashMaps, Bidirectional Check (Optimal -- true O(n)) ---
//  Intuition : maintain TWO maps (s-char -> t-char AND t-char -> s-char).
//              On each pair, verify BOTH directions are consistent with any
//              prior mapping seen -- this catches both "one s-char maps to
//              two different t-chars" AND "two different s-chars map to
//              the same t-char" in a single linear pass.
//  TC: O(n)  |  SC: O(1) (bounded by alphabet size, effectively O(1) for ASCII)
//
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

//  --- Dry Run ---  s = "egg", t = "add"
//  ┌───┬────┬────┬───────────────────┬───────────────────┐
//  │ i │ s[i]│ t[i]│ sToT check       │ tToS check         │
//  ├───┼────┼────┼───────────────────┼───────────────────┤
//  │ 0 │ e  │ a  │ new -> e:a         │ new -> a:e         │
//  │ 1 │ g  │ d  │ new -> g:d         │ new -> d:g         │
//  │ 2 │ g  │ d  │ g:d matches -> ok  │ d:g matches -> ok  │
//  └───┴────┴────┴───────────────────┴───────────────────┘
//  Output: true

echo "Isomorphic 'egg','add': " . (isIsomorphic("egg", "add") ? "true" : "false") . "\n";  // true
echo "Isomorphic 'foo','bar': " . (isIsomorphic("foo", "bar") ? "true" : "false") . "\n";  // false

//  --- Patterns Used ---   Primary: Bidirectional Frequency/Position Map.  Secondary: none.
//  --- Recognition Tips ---
//    Use when: "consistent one-to-one character replacement" is asked.
//    Don't use when: the mapping doesn't need to be one-to-one (e.g., just
//              "can s be rearranged into t" is Valid Anagram, Problem 7 --
//              a much weaker and different condition).
//    Similar problems: Word Pattern (LC290, the exact same bidirectional-
//              map idea applied to words instead of characters), Valid Anagram (Problem 7).
//
//  --- 🧪 Additional Senior-Level Prep (tests, gotchas, follow-ups) ---
//  assert(isIsomorphic("egg", "add") === true);
//  assert(isIsomorphic("foo", "bar") === false);
//  assert(isIsomorphic("ab", "aa") === false);    // the exact collision case a one-directional check would miss
//  assert(isIsomorphic("", "") === true);
//  PHP Gotcha       : Using strpos() for the "first occurrence" trick is correct but silently quadratic -- if asked
//                     to state complexity for that approach, don't claim O(n) without qualifying "n calls to an O(n)
//                     scan in the worst case."
//  Mistake Recovery : If a test like s="ab", t="aa" passes when it shouldn't, that's the signature symptom of only
//                     checking ONE direction of the mapping -- add the reverse map check rather than trying to patch
//                     the single-map version with extra conditionals.
//  Follow-Up / Scale-Up:
//    - Extend to Word Pattern (LC290) -> identical bidirectional-map logic, just tokenize into words first instead
//      of iterating characters.
//    - What if the alphabet were Unicode instead of ASCII? -> the HashMap-based approach (Approach 2) is already
//      alphabet-agnostic and needs no changes; only a FIXED-SIZE array[26]-based approach would need to change to a HashMap.
//
//  --- Interview Discussion ---
//    Q: Why do you need TWO maps instead of one?
//    A: A single s-to-t map only catches the case where one s-character tries to map to two different
//       t-characters. It does NOT catch the reverse collision -- two different s-characters both mapping to the SAME
//       t-character -- which is exactly what breaks the "one-to-one" (bijective) requirement. The second map closes that gap.
//    Q: What's the complexity trade-off between the strpos() trick and the two-map approach?
//    A: strpos() is more concise to write but hides an O(n) rescan inside each call, making the whole loop O(n^2)
//       worst case; the two-map approach is a few more lines but is genuinely O(n) -- name this trade-off explicitly if
//       you start with strpos() for brevity.
//  --- Related Problems ---
//    Easy   : Word Pattern (LC290).
//    Easy   : Valid Anagram (Problem 7 below) -- a related but distinct, weaker condition.


// ================================================================================
//  PROBLEM 6 — LC 796: ROTATE STRING
// ================================================================================
//
//  --- How to Identify This Pattern ---
//  Keywords     : "rotate string", "shift left/right", "can s become goal
//                 by rotating"
//  Signal       : "Is one string a ROTATION of another" is always solvable
//                 with the doubling trick: goal is a rotation of s if and
//                 only if goal is a SUBSTRING of (s concatenated with itself).
//  Common mistake: Generating and comparing all N rotations one by one with
//                 nested loops (O(n^2)) instead of recognizing the
//                 doubling trick reduces this to a single substring search.
//
//  --- Problem Understanding ---
//  What: return true if `goal` can be obtained by rotating `s` some number
//        of positions (0 to n-1) to the left (or right, both directions
//        are covered since a full rotation cycle includes both).
//  Why it exists: a small, elegant example of "transform the problem into
//    a form a built-in tool (substring search) can solve directly."
//
//
//  --- 🎯 Interview-Ready Add-Ons (constraints, timing, pitch) ---
//  Asked at      : Amazon, Google -- moderately common, mainly testing whether you know the string-doubling trick versus brute-force rotation checking.
//  Constraints   : 1 <= s.length <= 10^5, s.length === goal.length -> O(n)-ish (single substring search on a doubled string) expected; O(n^2) brute-force rotation generation should be named explicitly as the naive starting point.
//  Time-boxing   : Total ~6 min: 1 min restate, 2 min brute force, 3 min doubling-trick + explain WHY it works.
//  60-Sec Pitch  : "I concatenate s with itself and check whether goal is a substring of that doubled string -- every possible rotation of s appears as a contiguous substring somewhere inside s+s, so a single substring search covers all rotation offsets at once."
//
//  --- Approach 1: Brute Force -- Try All N Rotations (for contrast) ---
//  Intuition : physically construct each of the n possible rotations of s
//              and compare against goal.
//  TC: O(n^2)  |  SC: O(n) per rotation constructed
//
function rotateStringBrute(string $s, string $goal): bool
{
    if (strlen($s) !== strlen($goal)) return false;
    $n = strlen($s);

    for ($shift = 0; $shift < $n; $shift++) {
        $rotated = substr($s, $shift) . substr($s, 0, $shift);   // Rotate left by $shift positions
        if ($rotated === $goal) return true;
    }

    return false;
}

//  --- Approach 2: Doubling Trick + Substring Search (Optimal) ---
//  Intuition : every possible rotation of s appears as a contiguous
//              substring somewhere inside (s . s) -- concatenating s with
//              itself "unrolls" the circular rotation space into a linear
//              one that a single substring search can check in one shot.
//  TC: O(n) amortized in practice (PHP's strpos uses an efficient search
//      algorithm internally), O(n^2) for a naive substring search
//      implementation  |  SC: O(n) for the doubled string
//
function rotateString(string $s, string $goal): bool
{
    if (strlen($s) !== strlen($goal)) return false;   // Different lengths can never be rotations of each other
    if ($s === '') return true;                         // Two empty strings -- trivially a rotation of each other

    $doubled = $s . $s;                                  // Every rotation of s is a substring of s+s
    return strpos($doubled, $goal) !== false;
}

//  --- Dry Run ---  s = "abcde", goal = "cdeab"
//  doubled = "abcdeabcde"
//  strpos("abcdeabcde", "cdeab") -> found at index 2 -> not false -> true

echo "Rotate String 'abcde'->'cdeab': " . (rotateString("abcde", "cdeab") ? "true" : "false") . "\n";  // true
echo "Rotate String 'abcde'->'abced': " . (rotateString("abcde", "abced") ? "true" : "false") . "\n";  // false

//  --- Patterns Used ---   Primary: String-Doubling + Substring Search.  Secondary: none.
//  --- Recognition Tips ---
//    Use when: checking whether one string is a CYCLIC rotation of another.
//    Don't use when: you need to know WHICH rotation amount works, not
//              just whether one exists -- then track the index returned by
//              strpos() (it tells you the rotation offset directly).
//    Similar problems: Repeated String Match (LC686, a related "does s+s+...
//              contain goal" family), Find the Index of the First
//              Occurrence in a String (LC28, the underlying substring-
//              search primitive, worth knowing KMP for the true O(n) guarantee).
//
//  --- 🧪 Additional Senior-Level Prep (tests, gotchas, follow-ups) ---
//  assert(rotateString("abcde", "cdeab") === true);
//  assert(rotateString("abcde", "abced") === false);
//  assert(rotateString("", "") === true);
//  assert(rotateString("a", "") === false);   // different lengths
//  PHP Gotcha       : `strpos($doubled, $goal) !== false` -- the `!==` (strict) comparison is MANDATORY here, not
//                     just style preference: if the rotation is found at index 0, strpos returns 0, and `0 == false`
//                     is TRUE in PHP's loose comparison, which would silently produce the WRONG boolean result with `!=`.
//  Mistake Recovery : If a same-string rotation (shift=0, s === goal) fails, check whether you're using `!=`/`==`
//                     instead of `!==`/`===` against strpos's return value -- this is the single most common PHP
//                     footgun with strpos() across ALL string problems, not just this one.
//  Follow-Up / Scale-Up:
//    - Determine the ACTUAL rotation amount, not just yes/no -> the index returned by strpos() IS the rotation
//      offset directly, no extra work needed.
//    - What if s is extremely long (megabytes) and this check happens frequently? -> use KMP or Z-function for a
//      guaranteed O(n) substring search instead of relying on PHP's internal strpos implementation's average-case behavior.
//
//  --- Interview Discussion ---
//    Q: Why does concatenating s with itself capture every possible rotation?
//    A: Rotating s by k positions is equivalent to reading k characters into (s+s) and taking the next n
//       characters -- since (s+s) contains s followed immediately by a second copy of s, every "wrap-around" read
//       starting anywhere in the first copy is fully covered by the doubled string.
//    Q: What if s contains duplicate characters -- could this give a false positive?
//    A: No -- strpos() checks for an EXACT contiguous substring match, not just "these characters exist
//       somewhere," so duplicate characters don't create false positives; the match must be a real, position-consistent substring.
//  --- Related Problems ---
//    Medium : Repeated String Match (LC686).
//    Hard    : Find the Index of the First Occurrence in a String (LC28) -- implement the underlying search yourself (KMP).


// ================================================================================
//  PROBLEM 7 — LC 242: VALID ANAGRAM                                        [BUG FIXED]
// ================================================================================
//
//  *** BUG FOUND IN ORIGINAL FILE (CRITICAL — SCOPING) ***
//  The original file declared `isAnagram()` NESTED INSIDE the body of
//  `rotateString()` (there was no closing brace for rotateString before
//  `function isAnagram(...)` began). In PHP, a function declared inside
//  another function is NOT registered/callable until the OUTER function has
//  actually been CALLED at least once. This means calling `isAnagram()`
//  BEFORE `rotateString()` has ever executed would throw "Fatal error: Call
//  to undefined function isAnagram()" -- a real, order-dependent landmine.
//  Fixed below by declaring `isAnagram()` as its own independent top-level function.
//
//  --- How to Identify This Pattern ---
//  Keywords     : "anagram", "rearrangement of letters", "same character
//                 counts"
//  Signal       : "Anagram" always means: same multiset of characters,
//                 order doesn't matter -- a frequency-count comparison
//                 (fixed-size array for lowercase-only constraints, or a
//                 HashMap for a general alphabet).
//  Common mistake: Sorting both strings and comparing (also correct, O(n
//                 log n)) when the problem's constraints (lowercase English
//                 letters only) hint strongly at an O(n) fixed-array frequency count instead.
//
//  --- Problem Understanding ---
//  What: return true if t is an anagram of s (same characters, same
//        counts, any order).
//  Why it exists: the canonical frequency-count problem -- almost every
//    later "compare character compositions" problem reduces to this exact technique.
//
//
//  --- 🎯 Interview-Ready Add-Ons (constraints, timing, pitch) ---
//  Asked at      : Nearly every company -- one of the most common easy warm-ups, often paired immediately with 'now do Group Anagrams' as a harder follow-up.
//  Constraints   : 1 <= s.length, t.length <= 5*10^4, lowercase English letters only -> O(n) fixed-size array[26] frequency count expected.
//  Time-boxing   : Total ~5 min: near-instant -- if this takes more than 5-6 minutes, drill fundamentals before moving to harder problems.
//  60-Sec Pitch  : "I use a single 26-slot counter array, incrementing for every character in s and decrementing for every character in t -- if every slot returns to zero, the two strings have identical character compositions."
//
//  --- Approach 1: Sort and Compare (Better, simple but not optimal on time) ---
//  Intuition : two strings are anagrams iff sorting both produces identical strings.
//  TC: O(n log n)  |  SC: O(n) for the sorted copies
//
function isAnagramSort(string $s, string $t): bool
{
    if (strlen($s) !== strlen($t)) return false;

    $sChars = str_split($s);
    $tChars = str_split($t);
    sort($sChars);
    sort($tChars);

    return $sChars === $tChars;
}

//  --- Approach 2: Fixed-Size Frequency Array (Optimal for lowercase-only constraints) ---
//  Intuition : allocate a 26-slot counter (one per lowercase letter),
//              increment for every character in s, decrement for every
//              character in t -- if all counts return to zero, the
//              multisets of characters are identical.
//  TC: O(n)  |  SC: O(1) -- the counter array is a FIXED size 26, independent of input length
//
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

//  --- Dry Run ---  s = "anagram", t = "nagaram"
//  After tallying s and untallying t, every letter's net count returns to 0
//  (both strings have identical letter compositions: a:3, n:1, g:1, r:1, m:1)
//  Output: true

echo "Anagram 'anagram','nagaram': " . (isAnagram("anagram", "nagaram") ? "true" : "false") . "\n";  // true
echo "Anagram 'rat','car':         " . (isAnagram("rat", "car")         ? "true" : "false") . "\n";  // false

//  --- Patterns Used ---   Primary: Fixed-Size Frequency Array.  Secondary: Sorting (alternative).
//  --- Recognition Tips ---
//    Use when: constraints guarantee a small, fixed alphabet (lowercase
//              English letters is the classic case).
//    Don't use when: the alphabet is large/Unicode -- switch the fixed
//              array to a HashMap keyed by character, same core logic.
//    Similar problems: Group Anagrams (LC49, bucket strings by their
//              sorted-or-frequency-signature "key"), Find All Anagrams in
//              a String (LC438, sliding window + frequency array combination).
//
//  --- 🧪 Additional Senior-Level Prep (tests, gotchas, follow-ups) ---
//  assert(isAnagram("anagram", "nagaram") === true);
//  assert(isAnagram("rat", "car") === false);
//  assert(isAnagram("", "") === true);
//  assert(isAnagram("a", "ab") === false);   // different lengths -- short-circuits immediately
//  PHP Gotcha       : `ord($char) - ord('a')` assumes strictly lowercase 'a'-'z' input -- an uppercase letter or a
//                     non-letter character produces an out-of-range array index (negative or >25), which PHP will
//                     silently accept as a new associative-style key rather than erroring, masking the real bug; validate
//                     input character range explicitly if the "lowercase only" constraint isn't 100% guaranteed.
//  Mistake Recovery : This is the exact function that was accidentally nested inside another function in the
//                     original file (see Bug Log) -- if a function you KNOW you defined throws "undefined function," check
//                     immediately whether it's accidentally nested inside another function's body rather than assuming
//                     you forgot to define it at all.
//  Follow-Up / Scale-Up:
//    - Group an entire array of strings into anagram buckets (LC49) -> use each string's SORTED form (or frequency
//      signature) as a HashMap key, appending matching strings to the same bucket.
//    - Find all anagram substrings within a larger text (LC438) -> combine this frequency-array technique with a
//      fixed-size Sliding Window that slides across the larger text, maintaining a running frequency count.
//
//  --- Interview Discussion ---
//    Q: Why use a single shared counter array (increment for s, decrement for t) instead of two separate frequency maps?
//    A: It's a minor constant-factor optimization -- one pass building two separate maps and then comparing them
//       is equally correct, but the increment/decrement-then-check-all-zero trick avoids a separate comparison pass at the end.
//    Q: What's the length pre-check for, and why check it FIRST?
//    A: Two strings of different lengths can never be anagrams of each other -- checking this first is an O(1)
//       short-circuit that avoids doing any real work on inputs that are trivially not anagrams.
//  --- Related Problems ---
//    Medium : Group Anagrams (LC49).
//    Medium : Find All Anagrams in a String (LC438).


// ================================================================================
//  PROBLEM 8 — LC 451: SORT CHARACTERS BY FREQUENCY
// ================================================================================
//
//  --- How to Identify This Pattern ---
//  Keywords     : "sort characters by decreasing frequency", "rearrange"
//  Signal       : "Sort BY frequency" (not sort the characters themselves)
//                 is a two-step signal: (1) count frequencies with a
//                 HashMap, (2) invert/group by count so you can process
//                 from highest frequency to lowest -- a "bucket by value"
//                 technique, related to Bucket Sort.
//  Common mistake: Trying to sort the frequency map directly with a
//                 general-purpose comparator when a cleaner "invert the
//                 map, then sort/iterate by count descending" approach is
//                 both clearer and (with true bucket sort) can be made linear.
//
//  --- Problem Understanding ---
//  What: given a string, rearrange it so characters are ordered by
//        DECREASING frequency (ties can be broken arbitrarily, though this
//        implementation breaks ties alphabetically for determinism).
//  Why it exists: introduces the "invert a frequency map into a
//    count-to-characters map" technique, a stepping stone toward true
//    Bucket Sort and toward heap-based Top-K-Frequent-style problems.
//
//
//  --- 🎯 Interview-Ready Add-Ons (constraints, timing, pitch) ---
//  Asked at      : Amazon, Google, Bloomberg -- a moderately common medium testing the 'invert a frequency map into buckets' technique.
//  Constraints   : 1 <= s.length <= 5*10^5 -> O(n + k log k) where k is the distinct-character count expected (effectively O(n) for a bounded alphabet); avoid re-scanning the full string more than a constant number of times.
//  Time-boxing   : Total ~10 min: 2 min restate, 8 min count-then-invert-then-rebuild + dry run showing the frequency-to-characters inversion clearly.
//  60-Sec Pitch  : "I count character frequencies, then INVERT that map so each distinct count maps to a list of characters sharing it, sort those counts descending, and rebuild the answer tier by tier, breaking ties alphabetically within each tier."
//
//  --- Approach 1: Count + Sort by Frequency Descending (Better, O(n log n)) ---
//  Intuition : count character frequencies, then sort the (char,count)
//              pairs by count descending using a general-purpose sort, then rebuild the string.
//  TC: O(n + k log k) where k = number of DISTINCT characters  |  SC: O(n)
//
//  --- Approach 2: Invert Frequency Map into Count Buckets (Optimal, matches original file's approach) ---
//  Intuition : after counting frequencies, invert the map so each COUNT
//              maps to a LIST of characters with that count. Sort the
//              count-keys descending (krsort), then within each count tier
//              sort characters alphabetically for determinism, and repeat
//              each character exactly `count` times into the answer.
//  TC: O(n + k log k) where k = number of distinct characters (the
//      krsort/sort calls operate on at most k entries, not n)  |  SC: O(n)
//
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

//  --- Dry Run ---  s = "tree"
//  frequencyMap: t=>1, r=>1, e=>2
//  countToChars: 1=>['t','r'], 2=>['e']
//  After krsort: 2=>['e'], 1=>['t','r']  (sorted alphabetically within tier: 'r' before 't')
//  Answer: "ee" + "rt" = "eert"

echo "Frequency Sort 'tree': '" . frequencySort("tree") . "'\n";  // "eert" (or "eetr" -- either is a valid LC451 answer)

//  --- Patterns Used ---   Primary: Frequency Map + Bucket-by-Value.  Secondary: none.
//  --- Recognition Tips ---
//    Use when: output order depends on FREQUENCY, not on the characters' natural order.
//    Don't use when: you only need the TOP K most frequent (not a full
//              sort of everything) -- a heap of size K (LC347-style) avoids
//              sorting all k distinct entries when K is much smaller.
//    Similar problems: Top K Frequent Elements (LC347, heap-based
//              variant), Top K Frequent Words (LC692, ties broken
//              lexicographically like this problem).
//
//  --- 🧪 Additional Senior-Level Prep (tests, gotchas, follow-ups) ---
//  $result = frequencySort("tree"); assert($result === "eert" || $result === "eetr");
//  assert(frequencySort("cccaaa") === "aaaccc" || frequencySort("cccaaa") === "cccaaa");
//  assert(frequencySort("a") === "a");
//  assert(frequencySort("") === "");
//  PHP Gotcha       : `krsort()` sorts an array by KEY descending while preserving key=>value association -- it's
//                     easy to confuse with `rsort()` (which re-indexes and sorts by VALUE, losing the count-as-key
//                     structure this algorithm depends on); using the wrong one silently breaks the frequency-tier grouping.
//  Mistake Recovery : If output has the right characters but wrong tier ordering, verify you used `krsort`
//                     (key-descending) and not `ksort`/`rsort` -- a one-letter function name mixup here produces
//                     plausible-looking but incorrect output rather than an obvious crash.
//  Follow-Up / Scale-Up:
//    - Only need the TOP K most frequent characters, not a full sort? -> maintain a min-heap of size K instead of
//      sorting all distinct characters -- O(n log K) instead of O(n + k log k).
//    - What if this needs to run over a continuously growing stream of characters, re-querying "sorted so far" periodically?
//      -> maintain the frequency map incrementally; only re-run the bucket/sort step when a query is actually made,
//      not on every single character insertion.
//
//  --- Interview Discussion ---
//    Q: Why is this effectively O(n) rather than O(n log n) despite the sort calls?
//    A: The `krsort` and per-tier `sort` calls operate on the DISTINCT character set (at most 26 for lowercase
//       English, or generally k << n), not on all n characters -- so the sorting cost is O(k log k), dominated in
//       practice by the O(n) frequency-counting and answer-rebuilding passes.
//    Q: How would you make the tie-breaking rule different (e.g., first-occurrence order instead of alphabetical)?
//    A: Track each character's first-seen index alongside its count during Step 1, then sort within each frequency
//       tier by that recorded index instead of calling `sort($chars)` alphabetically.
//  --- Related Problems ---
//    Medium : Top K Frequent Elements (LC347).
//    Medium : Top K Frequent Words (LC692).


// ================================================================================
//  PROBLEM 9 — LC 13: ROMAN TO INTEGER
// ================================================================================
//
//  --- How to Identify This Pattern ---
//  Keywords     : "Roman numeral", "convert to integer", subtractive
//                 notation (IV, IX, XL, XC, CD, CM)
//  Signal       : Any "convert between two representations using a lookup
//                 table plus a special-case rule" problem is a
//                 single-pass-with-lookahead problem -- the special case
//                 here is the SUBTRACTIVE pairs (IV=4, not I+V=6).
//  Common mistake: Handling the subtractive cases with a long if/else chain
//                 of specific letter pairs instead of the cleaner "compare
//                 current symbol's value to the NEXT symbol's value" rule
//                 (if current < next, SUBTRACT current; otherwise ADD it) --
//                 both work, but the comparison-based rule generalizes
//                 better and is less error-prone to write from memory.
//
//  --- Problem Understanding ---
//  What: convert a Roman numeral string into its integer value, handling
//        the six subtractive pairs (IV, IX, XL, XC, CD, CM).
//  Why it exists: a classic "lookup table + one special rule" parsing
//    problem, testing careful handling of the subtractive-notation exception.
//
//
//  --- 🎯 Interview-Ready Add-Ons (constraints, timing, pitch) ---
//  Asked at      : Amazon, Microsoft, Google -- a common easy/medium warm-up, occasionally paired with the LC12 (Integer to Roman) inverse as a follow-up.
//  Constraints   : 1 <= s.length <= 15, valid Roman numeral guaranteed -> O(n) single pass with a lookup table expected; the lookup table size (7 vs 13 entries) is the main design choice to discuss.
//  Time-boxing   : Total ~7 min: 1 min restate, 3 min two-character-lookahead map approach, 3 min compare-to-next-symbol alternative + discuss trade-off.
//  60-Sec Pitch  : "I compare each Roman symbol's value to the NEXT symbol's value -- if the current symbol is smaller, it's part of subtractive notation and gets SUBTRACTED; otherwise it's added normally, which handles all six subtractive pairs with one general rule instead of memorizing each pair explicitly."
//
//  --- Approach 1: Two-Character Lookahead Map (matches original file, very direct) ---
//  Intuition : build a map that includes BOTH single symbols (I=1, V=5,
//              ...) AND all six two-character subtractive pairs (IV=4,
//              IX=9, ...). At each position, first check if the CURRENT
//              plus NEXT character forms a known subtractive pair; if so,
//              consume both characters and add that pair's value,
//              otherwise consume just one character.
//  TC: O(n)  |  SC: O(1) -- the lookup map has a fixed 13 entries
//
function romanToInt(string $s): int
{
    $romanMap = [
        'I' => 1, 'V' => 5, 'X' => 10, 'L' => 50, 'C' => 100, 'D' => 500, 'M' => 1000,
        'IV' => 4, 'IX' => 9, 'XL' => 40, 'XC' => 90, 'CD' => 400, 'CM' => 900,
    ];

    $total = 0;
    $i = 0;
    $n = strlen($s);

    while ($i < $n) {
        if ($i + 1 < $n && isset($romanMap[$s[$i] . $s[$i + 1]])) {
            $total += $romanMap[$s[$i] . $s[$i + 1]];   // Subtractive pair found -- consume 2 characters
            $i += 2;
        } else {
            $total += $romanMap[$s[$i]];                 // Regular symbol -- consume 1 character
            $i++;
        }
    }

    return $total;
}

//  --- Approach 2: Compare-to-Next-Symbol Rule (Optimal -- simpler map, more general rule) ---
//  Intuition : only store the SEVEN single-symbol values. Scan left to
//              right; if the current symbol's value is LESS than the next
//              symbol's value, it's part of a subtractive pair -- subtract
//              it instead of adding. Otherwise add it normally. This
//              avoids needing to enumerate all six subtractive pairs explicitly.
//  TC: O(n)  |  SC: O(1) -- the lookup map has a fixed 7 entries
//
function romanToIntCompareNext(string $s): int
{
    $values = ['I' => 1, 'V' => 5, 'X' => 10, 'L' => 50, 'C' => 100, 'D' => 500, 'M' => 1000];
    $total = 0;
    $n = strlen($s);

    for ($i = 0; $i < $n; $i++) {
        $current = $values[$s[$i]];
        $next = ($i + 1 < $n) ? $values[$s[$i + 1]] : 0;

        if ($current < $next) {
            $total -= $current;   // e.g., 'I' before 'V' in "IV" -- subtract the smaller value
        } else {
            $total += $current;
        }
    }

    return $total;
}

//  --- Dry Run ---  s = "MCMXCIV"
//  ┌───┬──────┬──────────────────────────────────┬───────┐
//  │ i │ s[i] │ action                            │ total │
//  ├───┼──────┼──────────────────────────────────┼───────┤
//  │ 0 │ M    │ single, +1000                     │ 1000  │
//  │ 1 │ C    │ pair "CM"=900, consume 2           │ 1900  │
//  │ 3 │ X    │ pair "XC"=90, consume 2            │ 1990  │
//  │ 5 │ I    │ pair "IV"=4, consume 2             │ 1994  │
//  └───┴──────┴──────────────────────────────────┴───────┘
//  Output: 1994

echo "Roman 'MCMXCIV' = " . romanToInt("MCMXCIV") . "\n";  // 1994
echo "Roman 'LVIII' = " . romanToInt("LVIII") . "\n";        // 58

//  --- Patterns Used ---   Primary: Lookup Table + Lookahead.  Secondary: none.
//  --- Recognition Tips ---
//    Use when: converting FROM a symbolic/positional notation TO a numeric value.
//    Don't use when: converting the OTHER direction (integer -> Roman,
//              LC12) -- that's a greedy largest-value-first subtraction
//              problem, a different (though related) technique.
//    Similar problems: Integer to Roman (LC12, the inverse problem),
//              Excel Sheet Column Number (LC171, a similar positional-value parsing idea).
//
//  --- 🧪 Additional Senior-Level Prep (tests, gotchas, follow-ups) ---
//  assert(romanToInt("MCMXCIV") === 1994);
//  assert(romanToInt("LVIII") === 58);
//  assert(romanToInt("III") === 3);
//  assert(romanToInt("IX") === 9);
//  assert(romanToIntCompareNext("MCMXCIV") === 1994);   // both approaches must agree
//  PHP Gotcha       : String concatenation `$s[$i] . $s[$i+1]` as an array key works fine for small fixed
//                     lookups, but be mindful this creates a NEW string on every iteration -- for this problem's small
//                     input sizes it's irrelevant, but it's worth recognizing as a micro-cost if asked about performance at scale.
//  Mistake Recovery : If your total is off for inputs containing a subtractive pair, check the LOOKAHEAD boundary
//                     condition `$i + 1 < $n` -- forgetting it causes an out-of-bounds read attempt on the last character.
//  Follow-Up / Scale-Up:
//    - Convert an integer back to a Roman numeral (LC12) -> greedily subtract the largest possible symbol/pair
//      value repeatedly, appending its symbol each time -- the inverse operation, greedy instead of table-lookup-based.
//    - Validate whether a given string is a WELL-FORMED Roman numeral (not just convert it) -> would need a regex
//      or explicit state machine validating the subtractive-pair usage rules, since not all symbol combinations are
//      valid Roman numerals even if this conversion function would produce SOME number for them.
//
//  --- Interview Discussion ---
//    Q: Why does the "compare current to next" rule correctly handle subtraction?
//    A: In valid Roman numeral notation, a smaller-value symbol appearing immediately before a larger-value
//       symbol ALWAYS indicates subtractive notation (e.g., I before V or X, X before L or C, C before D or M) --
//       there's no other reason a smaller symbol would precede a larger one in valid notation.
//    Q: Which approach would you lead with?
//    A: The compare-to-next-symbol rule (Approach 2) -- it needs a smaller lookup table (7 entries instead of
//       13) and generalizes the special case into one comparison rather than memorizing six explicit pairs, which is
//       easier to reproduce correctly from memory under interview pressure.
//  --- Related Problems ---
//    Easy   : Excel Sheet Column Number (LC171).
//    Medium : Integer to Roman (LC12) -- the inverse problem.


// ================================================================================
//  PROBLEM 10 — LC 8: STRING TO INTEGER (ATOI) — ITERATIVE
// ================================================================================
//
//  --- How to Identify This Pattern ---
//  Keywords     : "implement atoi", "convert string to integer", "clamp to
//                 32-bit range", whitespace/sign/overflow handling
//  Signal       : Any "parse this string according to a set of sequential
//                 rules" problem (skip whitespace, then sign, then digits,
//                 then stop) is a STATE-MACHINE problem -- process each
//                 phase in order, and the moment a phase's condition fails,
//                 move to the next phase or stop entirely.
//  Common mistake: Checking for overflow AFTER computing
//                 `result * 10 + digit` -- by then the overflow may have
//                 already silently corrupted the value (in languages with
//                 fixed-width integers) or the check becomes needlessly
//                 complex; the overflow guard must be evaluated BEFORE the
//                 multiply-and-add step.
//
//  --- Problem Understanding ---
//  What: implement the classic C `atoi` behavior: skip leading whitespace,
//        read one optional sign, read consecutive digits, stop at the
//        first non-digit, and clamp the result to the 32-bit signed
//        integer range [-2147483648, 2147483647].
//  Why it exists: THE canonical state-machine parsing problem -- appears
//    in some form (parsing numbers, dates, expressions) constantly in real systems code.
//
//
//  --- 🎯 Interview-Ready Add-Ons (constraints, timing, pitch) ---
//  Asked at      : Amazon, Meta, Google, Bloomberg -- an extremely common medium/hard-flavored problem specifically testing careful edge-case and overflow handling under time pressure.
//  Constraints   : 0 <= s.length <= 200, s may contain leading/trailing whitespace, an optional sign, digits, and trailing non-digit characters -> O(n) single pass with a pre-multiply overflow guard expected.
//  Time-boxing   : Total ~12 min: 2 min restate all four phases explicitly, 8 min state-machine code + a FULL dry run on an overflow-triggering example, 2 min edge cases (empty string, sign-only, no digits).
//  60-Sec Pitch  : "I process the string in strict sequential phases -- skip whitespace, read one optional sign, then accumulate digits while checking BEFORE each multiply whether the next digit would overflow the 32-bit range, clamping immediately if so."
//
//  --- Approach: Sequential State Machine with Pre-Multiply Overflow Guard (Optimal, the only approach needed) ---
//  Intuition : process the string in strict phases -- (1) skip whitespace,
//              (2) read an optional sign, (3) accumulate digits while
//              checking for overflow BEFORE each multiply, (4) apply the
//              sign and return. The overflow check compares the
//              accumulated result so far against INT_MAX/10 BEFORE
//              multiplying, which safely predicts whether the next
//              operation would overflow without ever actually computing an overflowed value.
//  TC: O(n)  |  SC: O(1)
//
function myAtoi(string $s): int
{
    if ($s === '') return 0;

    $INT_MAX = 2147483647;
    $INT_MIN = -2147483648;
    $isNegative = false;
    $result = 0;
    $i = 0;
    $n = strlen($s);

    // Phase 1: skip leading whitespace
    while ($i < $n && $s[$i] === ' ') {
        $i++;
    }

    // Phase 2: read an optional sign (only ONE is allowed)
    if ($i < $n && ($s[$i] === '+' || $s[$i] === '-')) {
        $isNegative = ($s[$i] === '-');
        $i++;
    }

    // Phase 3: read consecutive digits, guarding against overflow BEFORE each multiply
    while ($i < $n && $s[$i] >= '0' && $s[$i] <= '9') {
        $digit = (int) $s[$i];

        if ($result > intdiv($INT_MAX, 10)) {
            return $isNegative ? $INT_MIN : $INT_MAX;   // Any further digit would overflow -- clamp now
        }
        if ($result === intdiv($INT_MAX, 10)) {
            if ($isNegative && $digit > 8) return $INT_MIN;   // |INT_MIN| ends in 8
            if (!$isNegative && $digit > 7) return $INT_MAX;   // INT_MAX ends in 7
        }

        $result = $result * 10 + $digit;
        $i++;
    }

    // Phase 4: apply sign
    return $isNegative ? -$result : $result;
}

//  --- Dry Run ---  s = "   -91283472332"
//  Phase 1: skip 3 spaces -> i=3
//  Phase 2: '-' -> isNegative=true, i=4
//  Phase 3: 9,1,2,8,3,4,7 -> result=9128347, then '2': result(9128347) > INT_MAX/10(214748364)? No.
//           next digit accumulates until result exceeds INT_MAX/10, at which point clamp fires -> INT_MIN
//  Output: -2147483648

echo "atoi '   -91283472332' = " . myAtoi('   -91283472332') . "\n";  // -2147483648 (clamped)
echo "atoi '4193 with words' = " . myAtoi('4193 with words')  . "\n";  // 4193
echo "atoi '+-12' = "            . myAtoi('+-12')             . "\n";  // 0

//  --- Patterns Used ---   Primary: State-Machine Parsing.  Secondary: Overflow-Safe Accumulation.
//  --- Recognition Tips ---
//    Use when: parsing a string according to an ordered set of rules with early-stop conditions.
//    Don't use when: the format is more complex/ambiguous (e.g., floating-
//              point numbers, scientific notation) -- those need a richer
//              state machine with more phases (decimal point, exponent sign, exponent digits).
//    Similar problems: Valid Number (LC65, a much more elaborate state
//              machine for validating floats/exponents), Basic Calculator (LC224/227, parsing + evaluating expressions).
//
//  --- 🧪 Additional Senior-Level Prep (tests, gotchas, follow-ups) ---
//  assert(myAtoi("42") === 42);
//  assert(myAtoi("   -42") === -42);
//  assert(myAtoi("4193 with words") === 4193);
//  assert(myAtoi("-91283472332") === -2147483648);
//  assert(myAtoi("2147483648") === 2147483647);
//  assert(myAtoi("+-12") === 0);
//  assert(myAtoi("") === 0);
//  PHP Gotcha       : PHP's native int is 64-bit on 64-bit systems, so this function would never ACTUALLY overflow
//                     internally -- the 32-bit clamping logic is entirely a DELIBERATE SIMULATION of the LeetCode
//                     problem's constraints, not a real overflow protection PHP needs. Say this explicitly in an
//                     interview: "PHP wouldn't overflow here natively, but I'm enforcing the 32-bit bound because the
//                     problem requires it."
//  Mistake Recovery : If clamping produces the WRONG bound (INT_MAX when INT_MIN was expected or vice versa), check
//                     that you're checking `$isNegative` correctly in BOTH the `> INT_MAX/10` branch and the
//                     `=== INT_MAX/10` branch -- it's easy to handle the sign check in one branch and forget it in the other.
//  Follow-Up / Scale-Up:
//    - Extend to parse floating-point numbers (LC65, Valid Number) -> add phases for an optional decimal point
//      followed by more digits, and an optional exponent (e/E, sign, digits) -- significantly more state transitions to track.
//    - What if leading whitespace could include tabs/newlines, not just spaces? -> broaden the Phase 1 check from
//      `$s[$i] === ' '` to a whitespace-class check (e.g., `ctype_space($s[$i])`).
//
//  --- Interview Discussion ---
//    Q: Why must the overflow check happen BEFORE the multiply-and-add, not after?
//    A: If you multiply first and check after, you're checking a value that (in a fixed-width-integer language)
//       may have ALREADY silently wrapped around to garbage -- the check would be operating on corrupted data. Checking
//       before, using the ALGEBRAIC bound (result > INT_MAX/10 implies result*10+digit > INT_MAX), predicts the
//       overflow without ever computing the overflowed value.
//    Q: Why is `intdiv($INT_MAX, 10)` used instead of a hardcoded literal?
//    A: It's self-documenting and removes any risk of a typo in the hardcoded threshold; `intdiv` also
//       guarantees integer (not float) division, avoiding any floating-point comparison precision issues.
//  --- Related Problems ---
//    Hard    : Valid Number (LC65).
//    Hard    : Basic Calculator (LC224, LC227) -- a much larger state machine for expression parsing.


// ================================================================================
//  PROBLEM 11 — LC 8: STRING TO INTEGER (ATOI) — RECURSIVE
// ================================================================================
//
//  --- How to Identify This Pattern ---
//  Keywords     : same as Problem 10, but with an explicit ask to
//                 "implement it recursively" -- common as a direct follow-up
//                 immediately after the iterative version.
//  Signal       : Whenever an interviewer says "now do it recursively"
//                 right after an iterative loop-based solution, the
//                 mechanical conversion is: the loop's PER-ITERATION body
//                 becomes the recursive function's body, the loop's
//                 continue-condition becomes the recursive case, and the
//                 loop's exit-condition becomes the base case.
//  Common mistake: Forgetting to pass the accumulator (result) BY
//                 REFERENCE (or return it and thread it through every
//                 call's return value) -- without one of these, each
//                 recursive call operates on its own disconnected local copy.
//
//  --- Problem Understanding ---
//  What: identical to Problem 10, but the digit-accumulation loop is
//        replaced with recursion; the whitespace/sign preprocessing stays iterative.
//  Why it exists: a good exercise in recognizing which PART of an
//    algorithm genuinely benefits from being reframed recursively (here,
//    just the "keep consuming digits" loop) versus which part is more
//    naturally left iterative (the one-time preprocessing).
//
//
//  --- 🎯 Interview-Ready Add-Ons (constraints, timing, pitch) ---
//  Asked at      : Amazon, Google -- almost always asked as a direct follow-up immediately after the iterative atoi, specifically to test recursion-conversion instincts.
//  Constraints   : Same constraints as the iterative version (0 <= s.length <= 200) -> O(n) time, O(n) space (call stack depth) expected -- explicitly naming the space trade-off versus the iterative O(1) version is part of a strong answer.
//  Time-boxing   : Total ~8 min (assuming the iterative version is already done): 2 min identify which PART becomes recursive (just digit consumption, not the whitespace/sign preprocessing), 6 min code the by-reference recursive helper + dry run.
//  60-Sec Pitch  : "I keep the whitespace and sign preprocessing iterative exactly as before, and convert only the digit-accumulation loop into a recursive helper that takes the running result BY REFERENCE so every recursive call accumulates into the same variable."
//
//  --- Approach: Recursive Helper with By-Reference Accumulator (Optimal, the only approach needed) ---
//  Intuition : preprocessing (skip whitespace, read sign) stays a simple
//              iterative prefix, exactly as in Problem 10. The
//              digit-reading loop becomes a recursive helper:
//              `myAtoiHelper($s, $i, &$result, $isNegative)` -- base case
//              is "index out of bounds OR current character isn't a
//              digit," recursive case applies the same overflow guard as
//              Problem 10 then recurses on `i+1`. `$result` is passed BY
//              REFERENCE so every recursive call accumulates into the SAME variable.
//  TC: O(n)  |  SC: O(n) -- recursion depth equals the number of digit characters (the call stack itself costs space)
//
function myAtoiHelper(string $s, int $i, int &$result, bool $isNegative): void
{
    $n = strlen($s);
    $INT_MAX = 2147483647;

    if ($i >= $n || $s[$i] < '0' || $s[$i] > '9') {   // Base case: end of string or non-digit
        return;
    }

    $digit = (int) $s[$i];
    $limitLastDigit = $isNegative ? 8 : 7;              // |INT_MIN| ends in 8, INT_MAX ends in 7

    if ($result > intdiv($INT_MAX, 10)) {
        $result = $isNegative ? 2147483648 : $INT_MAX;   // Store the RAW absolute bound -- sign applied by the caller
        return;
    }
    if ($result === intdiv($INT_MAX, 10) && $digit > $limitLastDigit) {
        $result = $isNegative ? 2147483648 : $INT_MAX;
        return;
    }

    $result = $result * 10 + $digit;                     // Safe to accumulate this digit
    myAtoiHelper($s, $i + 1, $result, $isNegative);       // Recurse on the next character
}

function myAtoiRecursive(string $s): int
{
    if ($s === '') return 0;

    $i = 0;
    $n = strlen($s);
    $isNegative = false;
    $result = 0;

    while ($i < $n && $s[$i] === ' ') $i++;   // Preprocessing stays iterative -- only digit consumption recurses

    if ($i < $n && ($s[$i] === '+' || $s[$i] === '-')) {
        $isNegative = ($s[$i] === '-');
        $i++;
    }

    myAtoiHelper($s, $i, $result, $isNegative);   // Recursively accumulate digits into $result

    return $isNegative ? -$result : $result;
}

//  --- Dry Run ---  s = "4193 with words"
//  Preprocessing: no space, no sign, i=0
//  Call(i=0,'4'): result=4  -> recurse(i=1)
//  Call(i=1,'1'): result=41 -> recurse(i=2)
//  Call(i=2,'9'): result=419 -> recurse(i=3)
//  Call(i=3,'3'): result=4193 -> recurse(i=4)
//  Call(i=4,' '): non-digit -> base case, return
//  Apply sign (positive): 4193

echo "atoi recursive '4193 with words' = " . myAtoiRecursive('4193 with words') . "\n";  // 4193
echo "atoi recursive '   -42' = "          . myAtoiRecursive('   -42')          . "\n";  // -42

//  --- Patterns Used ---   Primary: Recursion (tail-call style).  Secondary: State-Machine Parsing.
//  --- Recognition Tips ---
//    Use when: explicitly asked to convert an iterative solution to recursion.
//    Don't use when: no such requirement exists -- prefer Problem 10's
//              iterative version for production code, since it avoids
//              O(n) call-stack space and any risk of stack-depth limits on
//              pathologically long digit strings.
//    Similar problems: any "convert this loop to recursion" follow-up
//              (Reverse a Linked List recursively, Binary Search recursively, etc.).
//
//  --- 🧪 Additional Senior-Level Prep (tests, gotchas, follow-ups) ---
//  assert(myAtoiRecursive("42") === 42);
//  assert(myAtoiRecursive("   -42") === -42);
//  assert(myAtoiRecursive("4193 with words") === 4193);
//  assert(myAtoiRecursive("-91283472332") === -2147483648);
//  PHP Gotcha       : The `int &$result` by-reference parameter is what makes this recursive accumulation work at
//                     all -- if the `&` is accidentally dropped, each recursive call would silently operate on its OWN
//                     copy of `$result`, and the final accumulated value would never propagate back to the caller
//                     (the function would appear to "run" without error but always return 0).
//  Mistake Recovery : If the recursive version always returns 0 regardless of input, the very first thing to check
//                     is whether the `&` was dropped from the `$result` parameter somewhere in the call chain.
//  Follow-Up / Scale-Up:
//    - Convert this recursion to be properly TAIL-RECURSIVE and discuss whether PHP optimizes tail calls -> PHP
//      does NOT perform tail-call optimization (unlike some functional languages), so a very long digit string could
//      still exhaust the call stack -- worth naming this as a real limitation of the recursive version versus the
//      iterative one for adversarially long inputs.
//    - What's the practical maximum recursion depth here? -> bounded by the number of digit characters in the
//      input, which for LC8's stated constraints (string length <= 200) is a complete non-issue, but would matter for
//      much longer inputs.
//
//  --- Interview Discussion ---
//    Q: Why does $result need to be passed by reference instead of returned?
//    A: The helper function's return type is `void` by design (mirroring the "just keep consuming" nature of the
//       original loop) -- passing by reference lets every recursive call accumulate directly into the CALLER's
//       variable, avoiding the need to thread a return value back up through every stack frame.
//    Q: What's the real trade-off versus the iterative version?
//    A: Identical time complexity, but O(n) space for the call stack instead of O(1) -- purely a demonstration
//       of recursive thinking, not an actual improvement; say this honestly rather than implying the recursive
//       version is "better."
//  --- Related Problems ---
//    Any "convert loop to recursion" style follow-up across topics (Reverse Linked List, Binary Search, Power function).


// ================================================================================
//  PROBLEM 12 — COUNT SUBSTRINGS WITH EXACTLY K DISTINCT CHARACTERS
// ================================================================================
//
//  --- How to Identify This Pattern ---
//  Keywords     : "exactly K distinct characters", "count of substrings"
//  Signal       : Any "count substrings/subarrays with EXACTLY K of some
//                 property" problem decomposes into
//                 `atMost(K) - atMost(K-1)` -- counting "at most K" is a
//                 much easier sliding-window problem than counting
//                 "exactly K" directly, and the difference of two "at
//                 most" counts gives you "exactly K" for free.
//  Common mistake: Trying to build a sliding window that directly tracks
//                 "exactly K distinct characters" -- this window is NOT
//                 monotonic (adding a character can either keep distinct
//                 count the same, increase it, or you might need to shrink
//                 from a completely different spot), making a direct
//                 window unnecessarily fiddly compared to the
//                 atMost(K)-minus-atMost(K-1) trick.
//
//  --- Problem Understanding ---
//  What: count the number of substrings of s that contain EXACTLY k
//        distinct characters.
//  Why it exists: the atMost(K)-minus-atMost(K-1) decomposition is one of
//    the most reusable tricks across sliding-window counting problems
//    (also used for "exactly K odd numbers," "binary subarrays with sum," etc.).
//
//
//  --- 🎯 Interview-Ready Add-Ons (constraints, timing, pitch) ---
//  Asked at      : Amazon, Google -- a strong signal question for whether you know the atMost(K)-minus-atMost(K-1) decomposition, which appears repeatedly across sliding-window counting problems.
//  Constraints   : 1 <= s.length <= 10^4, 1 <= k <= 26 -> O(n) expected via two calls to a monotonic sliding-window helper, not a single 'track exactly K directly' window (which is structurally awkward).
//  Time-boxing   : Total ~10 min: 2 min restate + explain why 'exactly K' resists a direct window, 3 min build atMostKDistinct as a clean sliding window, 3 min compose the subtraction, 2 min dry run.
//  60-Sec Pitch  : "I count substrings with AT MOST k distinct characters using a standard expand/shrink sliding window, then subtract the AT MOST (k-1) count from it -- the difference isolates exactly the substrings with EXACTLY k distinct characters."
//
//  --- Approach: atMost(K) - atMost(K-1), Each via Sliding Window (Optimal, the only approach needed) ---
//  Intuition : `atMostKDistinct($s, $k)` counts ALL substrings with AT
//              MOST k distinct characters using a classic expand/shrink
//              sliding window (for each right endpoint, every substring
//              starting between the current left boundary and right is
//              valid, contributing `right - left + 1` new substrings).
//              `countSubstrings($s, $k)` = atMost(k) - atMost(k-1) isolates
//              exactly the substrings with EXACTLY k distinct characters
//              (those counted in "at most k" but NOT in "at most k-1").
//  TC: O(n) for each atMost call -> O(n) overall  |  SC: O(k) for the frequency map (bounded by alphabet size)
//
function atMostKDistinct(string $s, int $k): int
{
    if ($k < 0) return 0;   // No valid substring can have a negative distinct-character budget

    $left = 0;
    $result = 0;
    $freqMap = [];
    $n = strlen($s);

    for ($right = 0; $right < $n; $right++) {
        $char = $s[$right];
        $freqMap[$char] = ($freqMap[$char] ?? 0) + 1;   // Expand: include s[right] in the window

        while (count($freqMap) > $k) {                    // Too many distinct characters -- shrink from the left
            $leftChar = $s[$left];
            $freqMap[$leftChar]--;
            if ($freqMap[$leftChar] === 0) {
                unset($freqMap[$leftChar]);                // Character fully removed from the window
            }
            $left++;
        }

        $result += ($right - $left + 1);   // Every substring ending at `right`, starting in [left..right], is valid
    }

    return $result;
}

function countSubstringsExactlyKDistinct(string $s, int $k): int
{
    return atMostKDistinct($s, $k) - atMostKDistinct($s, $k - 1);
}

//  --- Dry Run ---  s = "pqpqs", k = 2
//  atMostKDistinct("pqpqs", 2):
//  ┌───────┬──────┬────────────┬──────┬────────┐
//  │ right │ char │ freqMap    │ left │ result │
//  ├───────┼──────┼────────────┼──────┼────────┤
//  │ 0     │ p    │ {p:1}      │ 0    │ 1      │
//  │ 1     │ q    │ {p:1,q:1}  │ 0    │ 3      │
//  │ 2     │ p    │ {p:2,q:1}  │ 0    │ 6      │
//  │ 3     │ q    │ {p:2,q:2}  │ 0    │ 10     │
//  │ 4     │ s    │ shrink until <=2 distinct -> left moves to 3, {q:1,s:1} │ 3 │ 12 │
//  └───────┴──────┴────────────┴──────┴────────┘
//  atMost(2) = 12, atMost(1) = 1("p") + 1("q") + 1("p") + 1("q") + 1("s") = 5 (each single char alone)
//  Exactly-2 count = 12 - 5 = 7

echo "Substrings with exactly 2 distinct chars in 'pqpqs': " . countSubstringsExactlyKDistinct("pqpqs", 2) . "\n";  // 7

//  --- Patterns Used ---   Primary: Sliding Window + atMost(K)-minus-atMost(K-1) Decomposition.
//  --- Recognition Tips ---
//    Use when: "exactly K" of some sliding-window-trackable property needs counting.
//    Don't use when: you only need to know IF a substring with exactly K
//              distinct characters EXISTS (not count them) -- a single
//              sliding window pass tracking distinct count directly is simpler for that.
//    Similar problems: Subarrays with K Different Integers (LC992, the
//              array analogue of this exact technique), Binary Subarrays
//              With Sum (LC930, same atMost(K)-minus-atMost(K-1) trick applied to sum instead of distinct count).
//
//  --- 🧪 Additional Senior-Level Prep (tests, gotchas, follow-ups) ---
//  assert(countSubstringsExactlyKDistinct("pqpqs", 2) === 7);
//  assert(atMostKDistinct("pqpqs", 0) === 0);     // 0 distinct characters allowed -- no valid non-empty substring
//  assert(atMostKDistinct("aaaa", 1) === 10);      // every substring of an all-same-char string has exactly 1 distinct char
//  assert(countSubstringsExactlyKDistinct("aaaa", 1) === 10);
//  PHP Gotcha       : `count($freqMap) > $k` re-evaluates `count()` on every while-loop check -- for PHP arrays this
//                     is an O(1) operation (PHP tracks array length internally), so this is NOT a hidden performance
//                     issue here, but it's worth confirming this awareness if asked, since in some other languages
//                     checking a map's size isn't always guaranteed O(1).
//  Mistake Recovery : If your "exactly K" count comes out negative or nonsensical, double check the SUBTRACTION
//                     order (`atMost($k) - atMost($k-1)`, not the reverse) and that `atMostKDistinct` correctly
//                     returns 0 (not an error) when called with k-1 = -1.
//  Follow-Up / Scale-Up:
//    - Adapt to arrays of integers instead of characters (LC992, Subarrays with K Different Integers) -> identical
//      algorithm, just swap the frequency map's keys from characters to integers.
//    - What if K changes frequently and the string is queried repeatedly? -> each query is still a fresh O(n) pair
//      of atMost() calls; there's no straightforward way to precompute across arbitrary K values without
//      restructuring into a different data structure (e.g., a Fenwick tree over distinct-character transitions), which
//      is usually overkill unless queries are extremely frequent.
//
//  --- Interview Discussion ---
//    Q: Why is "exactly K" harder to track directly with a sliding window than "at most K"?
//    A: "At most K" is MONOTONIC -- as the window grows, distinct count can only increase or stay the same, and
//       shrinking always decreases or maintains it, giving a clean invariant to maintain. "Exactly K" has no such
//       clean monotonic invariant -- a window with exactly K distinct characters can become K+1 OR could need to
//       shrink from a completely different point when composition changes, making direct tracking fragile.
//    Q: Does this technique generalize beyond "distinct character count"?
//    A: Yes -- any property where you can cleanly compute "at most X" via a monotonic sliding window can use this
//       same subtraction trick for "exactly X" (sum thresholds, odd-count thresholds, etc.).
//  --- Related Problems ---
//    Medium : Subarrays with K Different Integers (LC992).
//    Medium : Binary Subarrays With Sum (LC930) -- already implemented in your Two Pointer & Sliding Window file.


// ================================================================================
//  PROBLEM 13 — LC 5: LONGEST PALINDROMIC SUBSTRING                        [BUG FIXED]
// ================================================================================
//
//  *** BUGS FOUND IN ORIGINAL FILE (CRITICAL — THE MOST SEVERE IN THIS FILE) ***
//  The original file declared `expandAroundCenter` as
//  `public function expandAroundCenter(...)` at the TOP LEVEL of the
//  script (not inside a class). In PHP, access modifiers (public/private/
//  protected) are ONLY valid on class methods -- using one on a
//  freestanding function is a hard PARSE ERROR. A parse error is a
//  COMPILE-TIME failure: PHP must successfully parse the ENTIRE script
//  before executing ANY of it, meaning this single line breaks EVERY
//  function in the original String.php, not just this one -- running
//  `php String.php` on the original file would fail immediately with a
//  syntax error and produce NO output at all.
//
//  A SECOND, independent bug sits right next to it: `longestPalindrome()`
//  called `$this->expandAroundCenter(...)` -- but `longestPalindrome` is a
//  plain top-level function, not a class method, so `$this` does not exist
//  in that context. Even if the `public` keyword were removed, this call
//  would still fail at RUNTIME with "Fatal error: Using $this when not in
//  object context."
//
//  FIX: `expandAroundCenter` is declared below as an ordinary top-level
//  function (no `public` modifier), and `longestPalindrome()` calls it
//  directly as `expandAroundCenter(...)`, not `$this->expandAroundCenter(...)`.
//
//  --- How to Identify This Pattern ---
//  Keywords     : "longest palindromic substring", "return the palindrome itself"
//  Signal       : "Longest palindromic SUBSTRING" (must be contiguous,
//                 unlike "subsequence") on a single string is the
//                 flagship Expand-Around-Center problem -- for every
//                 possible center (2n-1 of them: n single-character
//                 centers + n-1 between-character centers), expand
//                 outward while characters match.
//  Common mistake: Forgetting the EVEN-length case (center BETWEEN two
//                 characters, e.g., "abba") and only checking odd-length
//                 centers (center ON a character, e.g., "aba") -- this
//                 silently misses palindromes of even length.
//
//  --- Problem Understanding ---
//  What: find the longest contiguous substring of s that reads the same
//        forwards and backwards.
//  Why it exists: the canonical intro to the Expand-Around-Center
//    technique, and a common lead-in to discussing (but rarely
//    implementing live) Manacher's O(n) algorithm as a named follow-up.
//
//
//  --- 🎯 Interview-Ready Add-Ons (constraints, timing, pitch) ---
//  Asked at      : Amazon, Meta, Google, Microsoft, Bloomberg -- one of the highest-frequency medium/hard string problems industry-wide; also a favorite for testing PHP scoping fundamentals given the bugs this exact code contained.
//  Constraints   : 1 <= s.length <= 1000 -> O(n^2) Expand-Around-Center expected and fully sufficient at this scale; O(n^3) brute force should be named as the naive starting point, and Manacher's O(n) should be NAMED (rarely implemented live) as the theoretical optimum.
//  Time-boxing   : Total ~12 min: 2 min restate + the odd/even center distinction, 3 min brute force + complexity, 7 min expand-around-center + a full dry run on an even-length example.
//  60-Sec Pitch  : "For every one of the 2n-1 possible centers (n single-character centers plus n-1 between-character centers), I expand outward while characters match on both sides, tracking the longest palindrome found -- checking both center types is what catches both odd- and even-length palindromes."
//
//  --- Approach 1: Brute Force -- Check Every Substring (for contrast) ---
//  Intuition : for every (start, end) pair, check if that substring is a palindrome.
//  TC: O(n^3) (O(n^2) substrings, O(n) to check each)  |  SC: O(1) extra
//
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

//  --- Approach 2: Expand Around Center (Optimal for interview time pressure) ---
//  Intuition : for each of the 2n-1 possible centers (n odd-length centers
//              at each index, n-1 even-length centers between adjacent
//              indices), expand outward symmetrically while the characters
//              on both sides match. Track the longest palindrome found across all centers.
//  TC: O(n^2)  |  SC: O(1) extra beyond the returned substring
//
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

//  --- Dry Run ---  s = "babad"
//  ┌───┬───────────────────────────┬─────────────────────────┐
//  │ i │ odd center (i,i)          │ even center (i,i+1)     │
//  ├───┼───────────────────────────┼─────────────────────────┤
//  │ 0 │ "b" len=1                 │ "b"|"a" no match, len=0  │
//  │ 1 │ expand: "bab" len=3       │ "a"|"b" no match         │
//  │ 2 │ "a" alone initially...    │ ...                      │
//  │ 3 │ expand: "aba" len=3 (tie) │                          │
//  └───┴───────────────────────────┴─────────────────────────┘
//  maxLength=3, first found at i=1 ("bab") -- output "bab" (or "aba", both valid LC5 answers)

echo "Longest Palindrome 'babad': '" . longestPalindrome("babad") . "'\n";   // "bab" or "aba"
echo "Longest Palindrome 'cbbd':  '" . longestPalindrome("cbbd")  . "'\n";   // "bb"

//  --- Patterns Used ---   Primary: Expand-Around-Center.  Secondary: none (Manacher's is a named-only follow-up).
//  --- Recognition Tips ---
//    Use when: need the actual LONGEST palindromic substring (not just its
//              length, and not a subsequence).
//    Don't use when: you need the COUNT of all palindromic substrings
//              (LC647, a very similar expand-around-center loop but
//              incrementing a counter on every successful expansion step
//              instead of tracking a single max) or when O(n) time is
//              explicitly required (name Manacher's Algorithm, but expand-
//              around-center is the practical interview answer).
//    Similar problems: Palindromic Substrings (LC647, count instead of
//              find-longest), Palindrome Partitioning (LC131, backtracking
//              over palindromic prefixes), Longest Palindromic Subsequence
//              (LC516, a DIFFERENT DP problem -- subsequence doesn't need to be contiguous).
//
//  --- 🧪 Additional Senior-Level Prep (tests, gotchas, follow-ups) ---
//  $r = longestPalindrome("babad"); assert($r === "bab" || $r === "aba");
//  assert(longestPalindrome("cbbd") === "bb");
//  assert(longestPalindrome("a") === "a");
//  assert(longestPalindrome("") === "");
//  assert(longestPalindrome("ac") === "a" || longestPalindrome("ac") === "c");   // no palindrome longer than 1
//  PHP Gotcha       : This is the exact bug pair documented above (`public function` at top level is a PARSE
//                     ERROR; `$this->` outside a class is a RUNTIME error) -- if you EVER see `public`/`private`/`protected`
//                     on a function and can't immediately point to the enclosing `class { ... }` block, that's an
//                     instant red flag to fix before anything else, since it breaks the WHOLE FILE's parseability, not
//                     just that one function.
//  Mistake Recovery : If PHP reports a syntax error pointing at a seemingly unrelated LATER line in the file, remember
//                     that parse errors are about the file as a WHOLE -- the reported line is often just where the
//                     parser finally gave up, not necessarily where the actual mistake is; scan backwards for stray
//                     class-only keywords (public/private/protected/static outside a class) as a first troubleshooting step.
//  Follow-Up / Scale-Up:
//    - Count ALL palindromic substrings instead of finding the longest one (LC647) -> same expand-around-center
//      loop structure, but increment a running COUNTER once per successful expansion step instead of tracking a single max.
//    - Achieve true O(n) time (Manacher's Algorithm) -> name it as a known technique that transforms the string
//      (inserting separator characters) and reuses previously-computed palindrome radii to avoid redundant expansion
//      work -- correctly describing WHAT it achieves and WHY is usually sufficient without live-coding it.
//
//  --- Interview Discussion ---
//    Q: Why do you need to check BOTH (i,i) and (i,i+1) as centers?
//    A: A palindrome's center is either ON a single character (odd length, e.g., "aba" centered on 'b') or
//       BETWEEN two characters (even length, e.g., "abba" centered between the two 'b's) -- checking only one center
//       type would systematically miss all palindromes of the other length parity.
//    Q: Walk through why `$right - $left - 1` gives the correct length after the while loop exits.
//    A: The while loop always overshoots by exactly one step on each side the moment it finds a mismatch (or
//       goes out of bounds) -- so the TRUE palindrome boundary is one step INSIDE both `$left` and `$right` at that
//       point, giving an actual length of `($right - 1) - ($left + 1) + 1`, which simplifies to `$right - $left - 1`.
//  --- Related Problems ---
//    Medium : Palindromic Substrings (LC647).
//    Medium : Palindrome Partitioning (LC131).
//    Hard    : Longest Palindromic Subsequence (LC516) -- a different DP problem, don't confuse the two.


// ================================================================================
//  PROBLEM 14 — LC 1781: SUM OF BEAUTY OF ALL SUBSTRINGS
// ================================================================================
//
//  --- How to Identify This Pattern ---
//  Keywords     : "beauty of a string = max freq - min freq of characters",
//                 "sum over all substrings"
//  Signal       : "Sum some per-substring metric over ALL substrings" is
//                 almost always an O(n^2) double-loop where the OUTER loop
//                 fixes the start and the INNER loop extends the end while
//                 INCREMENTALLY updating a running frequency map (never
//                 recomputing frequencies from scratch for each substring).
//  Common mistake: Recomputing the character frequency count from scratch
//                 for every single substring (an extra O(n) factor,
//                 pushing total complexity to O(n^3)) instead of
//                 incrementally updating one running frequency map as the
//                 inner loop extends the substring by one character at a time.
//
//  --- Problem Understanding ---
//  What: the "beauty" of a string is (frequency of its most frequent
//        character) minus (frequency of its least frequent character).
//        Sum the beauty of every possible substring of s.
//  Why it exists: reinforces the "incremental frequency map inside a
//    double loop" technique -- avoiding redundant recomputation is the
//    entire difference between an accepted O(n^2) solution and a
//    too-slow O(n^3) one on this problem's constraints.
//
//
//  --- 🎯 Interview-Ready Add-Ons (constraints, timing, pitch) ---
//  Asked at      : Amazon, Google -- a moderately common medium testing whether you avoid the O(n^3) trap of recomputing frequency counts from scratch for every substring.
//  Constraints   : 1 <= s.length <= 500, lowercase English letters -> O(n^2) with an incrementally-updated frequency map expected; recomputing frequencies per substring silently degrades this to O(n^3).
//  Time-boxing   : Total ~10 min: 2 min restate 'beauty = max freq - min freq', 8 min fixed-start/incremental-frequency-map double loop + dry run showing the map growing incrementally.
//  60-Sec Pitch  : "I fix the substring's start index in an outer loop and extend the end index in an inner loop, incrementally updating ONE frequency map per start (reset only when start advances) -- this avoids ever rebuilding frequency counts from scratch, keeping the whole algorithm O(n^2) instead of O(n^3)."
//
//  --- Approach: Fix Start, Extend End, Incremental Frequency Map (Optimal, the only approach needed) ---
//  Intuition : the outer loop fixes the substring's START index; the inner
//              loop extends the END index one character at a time,
//              incrementally updating a SINGLE frequency map (reset only
//              when the start index changes) rather than rebuilding it
//              from scratch for every substring.
//  TC: O(n^2)  |  SC: O(1) -- the frequency map is bounded by the alphabet size (26 for lowercase)
//
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

//  --- Dry Run ---  s = "aabcb"  (partial trace for start=0)
//  ┌───────┬──────┬───────────────┬───────────────────┐
//  │ start │ end  │ freqMap       │ beauty (max-min)   │
//  ├───────┼──────┼───────────────┼───────────────────┤
//  │ 0     │ 0    │ {a:1}         │ 1-1=0              │
//  │ 0     │ 1    │ {a:2}         │ 2-2=0              │
//  │ 0     │ 2    │ {a:2,b:1}     │ 2-1=1              │
//  │ 0     │ 3    │ {a:2,b:1,c:1} │ 2-1=1              │
//  │ 0     │ 4    │ {a:2,b:2,c:1} │ 2-1=1              │
//  └───────┴──────┴───────────────┴───────────────────┘
//  (start=1..4 continue similarly; full sum for "aabcb" = 5)

echo "Sum of Beauty 'aabcb' = " . beautySum("aabcb") . "\n";  // 5

//  --- Patterns Used ---   Primary: Fixed-Start, Incremental Frequency Map.  Secondary: Frequency Map / Hashing.
//  --- Recognition Tips ---
//    Use when: summing/aggregating a per-substring metric across ALL O(n^2) substrings.
//    Don't use when: the metric can be computed with a PREFIX-SUM-style
//              trick instead of needing the full frequency composition
//              (e.g., simple sum of characters as numbers would just need prefix sums, no frequency map at all).
//    Similar problems: Count Substrings Exactly K Distinct Characters
//              (Problem 12 above -- also O(n^2)-adjacent territory but
//              solved via sliding window instead since it doesn't need a full min/max sweep).
//
//  --- 🧪 Additional Senior-Level Prep (tests, gotchas, follow-ups) ---
//  assert(beautySum("aabcb") === 5);
//  assert(beautySum("aabcbaa") === 17);
//  assert(beautySum("a") === 0);
//  assert(beautySum("") === 0);
//  PHP Gotcha       : `max($freqMap)` and `min($freqMap)` operate on the ARRAY VALUES (the frequency counts), not
//                     the keys (the characters) -- this is exactly the intended behavior here, but it's a common
//                     point of confusion since `max()`/`min()` on an associative array can look ambiguous at a glance;
//                     confirm out loud which one you mean if asked to explain this line.
//  Mistake Recovery : If your total is way too large, the most likely cause is rebuilding `$freqMap = []` inside the
//                     INNER loop instead of the outer one -- that silently turns this into an O(n^3) algorithm that
//                     also happens to compute a different (still plausible-looking) number, not just a slower one.
//  Follow-Up / Scale-Up:
//    - What if the alphabet were much larger (Unicode) instead of lowercase-only? -> the associative-array-based
//      frequency map already handles this with no changes needed; only a FIXED-SIZE array[26]-based approach would
//      need to switch to an associative map.
//    - Compute this over a SLIDING WINDOW of substrings instead of ALL substrings (e.g., only substrings of length
//      exactly L)? -> restructure the inner loop bound to `min($n, $start + $L)`, keeping the same incremental
//      frequency-map technique.
//
//  --- Interview Discussion ---
//    Q: Why reset the frequency map only when `start` changes, not on every `end` increment?
//    A: For a FIXED start, extending `end` by one character only ADDS information (one more character to
//       account for) -- there's no reason to discard everything already counted; incrementally updating preserves
//       correctness while avoiding the O(n) cost of rebuilding from scratch for every single substring.
//    Q: Could this be optimized below O(n^2)?
//    A: Not straightforwardly for the general "max freq minus min freq" metric across ALL substrings -- unlike
//       simple sum-based metrics that admit a prefix-sum O(n) trick, max/min of a frequency distribution doesn't have
//       an obvious O(1)-per-substring update, so O(n^2) is the practical, expected complexity here.
//  --- Related Problems ---
//    Medium : Count Substrings Exactly K Distinct Characters (Problem 12 above).
//    Medium : Contiguous Array (LC525) -- a different metric, but same "fixed start, incremental state" family.


// ================================================================================
//  SECTION Y — MASTER REVISION CHEAT SHEET (one-page night-before-interview scan)
// ================================================================================
//
//  ┌──────────────────────────────┬─────────────────────────────────────────┐
//  │ IF THE PROBLEM SAYS...        │ ...REACH FOR THIS ENGINE                 │
//  ├──────────────────────────────┼─────────────────────────────────────────┤
//  │ palindrome check / reverse    │ Two Pointers (opposite-direction)        │
//  │ longest/shortest substring    │ Sliding Window (expand right, shrink     │
//  │   with a condition            │   left when the condition is violated)   │
//  │ exactly K of some property    │ atMost(K) - atMost(K-1), each via a      │
//  │                                │   monotonic sliding window               │
//  │ anagram / isomorphic /        │ Frequency Map (fixed array[26] for       │
//  │   "same characters"           │   lowercase-only, else HashMap)          │
//  │ convert string to a number    │ State-Machine Parsing (ordered phases,   │
//  │   (atoi-style)                 │   overflow guard BEFORE each multiply)   │
//  │ longest palindromic substring │ Expand-Around-Center (check BOTH odd     │
//  │                                │   and even-length centers)               │
//  │ reverse word ORDER (not       │ Build-by-Prepend single pass             │
//  │   character order)             │                                          │
//  │ is one string a rotation of   │ Doubling Trick: goal is a rotation of s  │
//  │   another                      │   iff goal is a substring of (s+s)       │
//  │ sort by frequency              │ Invert frequency map into count-buckets  │
//  │ sum a metric over ALL          │ Fixed outer start, incremental inner     │
//  │   substrings                   │   state (avoid O(n^3) recomputation)     │
//  └──────────────────────────────┴─────────────────────────────────────────┘
//
//  UNIVERSAL EDGE-CASE CHECKLIST (run through this for EVERY string problem)
//  --------------------------------------------------------------------------
//  [ ] Empty string
//  [ ] Single character
//  [ ] All identical characters
//  [ ] Leading / trailing / multiple consecutive whitespace
//  [ ] Mixed case (if the problem doesn't explicitly say lowercase-only)
//  [ ] Non-alphanumeric characters (if the problem doesn't restrict to letters)
//  [ ] Very long input (does your approach stay within the stated time limit?)
//  [ ] Integer overflow simulation, if converting text to numbers (atoi-style)
//
//  FREQUENTLY FORGOTTEN POINTS
//  --------------------------------------------------------------------------
//  - PHP string indexing `$s[$i]` returns a 1-CHARACTER STRING, not an int
//    -- cast explicitly with `(int)` before arithmetic to avoid relying on
//    implicit coercion.
//  - `strpos($haystack, $needle)` can return `0` (a valid, falsy-looking
//    result) -- ALWAYS compare with `!==` / `===`, never `!=` / `==`,
//    against its return value.
//  - Access modifiers (`public`/`private`/`protected`) are ONLY valid
//    inside a `class { ... }` block -- using one on a freestanding
//    function is a fatal PARSE ERROR that breaks the ENTIRE file, not just
//    that function (see Bug 1 in the Bug Log below).
//  - `$this` only exists inside an object method -- calling
//    `$this->something()` from a plain top-level function is a fatal
//    runtime error.
//  - A function declared INSIDE another function's body is a PHP "nested
//    function" -- it only becomes callable AFTER the outer function has
//    executed at least once; this is almost always an accidental missing
//    closing brace, not an intentional design choice.
//  - An early closing tag silently ends PHP execution mode for the
//    REST of the file -- anything after it is treated as literal HTML/text
//    output, not executed code, even if it looks exactly like valid PHP.
//  - Sliding window is only valid when the "shrink" condition is
//    MONOTONIC -- for "exactly K" style counts, decompose into
//    atMost(K)-minus-atMost(K-1) rather than trying to track "exactly K" directly.
//
// ================================================================================


// ================================================================================
//  SECTION Z — BUG LOG (every bug found in the original String.php, with fixes)
// ================================================================================
//
//  BUG 1 — Longest Palindromic Substring (Problem 13) — SEVERITY: CRITICAL
//    (breaks the ENTIRE original file's ability to run at all)
//    The original file declared `public function expandAroundCenter(...)`
//    at the top level of the script, outside any class. PHP does not allow
//    access modifiers (public/private/protected) on freestanding
//    functions -- this is a hard PARSE ERROR. Because PHP must fully parse
//    a script before executing ANY of it, this single line means running
//    `php String.php` on the original file fails immediately with a syntax
//    error and produces NO output whatsoever -- not even from the many
//    correctly-written functions earlier in the file.
//    FIX: declared `expandAroundCenter()` as an ordinary top-level function
//    (no `public` keyword). See Problem 13 above.
//
//  BUG 2 — Longest Palindromic Substring (Problem 13) — SEVERITY: CRITICAL
//    (independent of Bug 1 -- would still crash even if Bug 1 were fixed)
//    `longestPalindrome()` called `$this->expandAroundCenter(...)` from
//    inside a plain top-level function. `$this` only exists inside an
//    object's method -- calling it here throws "Fatal error: Using $this
//    when not in object context" at runtime.
//    FIX: changed the call to a direct function call, `expandAroundCenter(...)`.
//
//  BUG 3 — Valid Anagram (Problem 7) — SEVERITY: HIGH (order-dependent
//    fatal error)
//    `isAnagram()` was declared NESTED inside `rotateString()`'s function
//    body (the original file was missing `rotateString`'s closing brace
//    before declaring `isAnagram`). In PHP, a function declared inside
//    another function only becomes callable AFTER the outer function has
//    been invoked at least once. Calling `isAnagram()` before
//    `rotateString()` had ever executed would throw "Fatal error: Call to
//    undefined function isAnagram()".
//    FIX: `isAnagram()` is declared as its own independent top-level
//    function. See Problem 7 above.
//
//  BUG 4 — Largest Odd Number (Problem 3) — SEVERITY: LOW (dead code, not fatal)
//    The original `largestOddNumber()` contained an unconditional
//    `return $ans;` statement followed by roughly 13 more lines of a
//    "similar/sort version" alternative implementation -- code that can
//    NEVER execute (unreachable code after a return), misleadingly
//    suggesting a working fallback exists when it's actually inert.
//    FIX: the enhanced version keeps only the single, correct
//    right-to-left scan implementation; the dead alternative was removed
//    (its intent is preserved conceptually in the Approach discussion above).
//
//  BUG 5 — Whole-File Structural Issue — SEVERITY: CRITICAL (silently
//    disables ~150 lines and risks masking a redeclaration error)
//    Partway through the original file (right after the "Important Tips &
//    Edge Cases" comment block), a closing tag exits PHP execution
//    mode. Everything after it -- roughly 150 lines, including duplicate
//    exploratory re-implementations of `reverseWords`, `largestOddNumber`,
//    `myAtoi`, and `myAtoiRecursive`, plus a stray top-level `return 0;`
//    -- is silently treated as literal HTML/TEXT OUTPUT rather than
//    executed PHP code. If this script were run via `php String.php`, all
//    of that "code" would be dumped to stdout as raw, garbled text rather
//    than doing anything. (It also incidentally PREVENTS a "Cannot
//    redeclare reverseWords()" fatal error that would otherwise occur from
//    defining the same function twice -- but only by accident, not by design.)
//    FIX: the enhanced file contains exactly ONE clean implementation of
//    each function, entirely within a single continuous PHP block with no
//    stray closing tags.
//
//  BUG 6 — Count Substrings At Most K Distinct (Problem 12) — SEVERITY: LOW
//    (debug leftover, not fatal, but pollutes output)
//    The original `atMostKDistinct()` contained a leftover debug
//    statement, `echo $k."== ".$right."_".$left."\n";`, inside its main
//    loop -- meaning every real call to this function would flood output
//    with debug noise.
//    FIX: removed. See Problem 12 above.
//
// ================================================================================


// ================================================================================
//  SECTION Z1 — PRE-SUBMISSION CHECKLIST (run through this before saying "I'm done")
// ================================================================================
//
//  [ ] Restated the problem back in my own words BEFORE writing any code.
//  [ ] Named the pattern explicitly out loud ("this is expand-around-center
//      because...") -- not just solved it silently.
//  [ ] Stated time & space complexity BEFORE coding, not after the fact.
//  [ ] Confirmed the alphabet assumption (lowercase-only vs. full Unicode)
//      BEFORE choosing between a fixed-size array[26] and a HashMap.
//  [ ] Walked through the edge-case checklist out loud (empty, single
//      character, all-same, whitespace-heavy, mixed case).
//  [ ] Dry-ran the optimal solution on the given example BEFORE claiming
//      it works.
//  [ ] Ran (or mentally executed) the assert()-style test cases in each
//      problem's "Additional Senior-Level Prep" block.
//  [ ] Double-checked every `strpos()` comparison uses `!==`/`===`, never
//      `!=`/`==`.
//  [ ] Confirmed no access modifiers (public/private/protected) appear
//      outside an actual class definition.
//  [ ] Proactively offered at least one follow-up/optimization/scale-up
//      angle without being asked.
//
// ================================================================================


// ================================================================================
//  SECTION Z2 — TOPIC SUMMARY
// ================================================================================
//
//  ✅ Concepts Learned
//     - Depth-counter scanning for nested-structure problems (parentheses)
//     - Build-by-Prepend single-pass word reversal
//     - Right-to-left scanning for "last digit determines a property" problems
//     - Sort + endpoint comparison AND vertical scanning for multi-string
//       common-prefix problems
//     - Bidirectional frequency/position mapping (isomorphism)
//     - The string-doubling trick for rotation detection
//     - Fixed-size frequency arrays vs. general HashMaps for anagram/
//       frequency-composition checks
//     - Frequency-map inversion into count-buckets (frequency sort)
//     - Lookup-table + lookahead parsing (Roman numerals)
//     - Sequential state-machine parsing with pre-multiply overflow guards (atoi)
//     - Converting an iterative accumulation loop into recursion with a
//       by-reference accumulator
//     - atMost(K)-minus-atMost(K-1) decomposition for "exactly K" sliding-window counts
//     - Expand-Around-Center for palindrome detection (both odd and even centers)
//     - Fixed-outer-start, incremental-inner-state for summing a metric
//       across all substrings
//
//  ✅ Patterns Covered
//     Two Pointers · Sliding Window · Frequency Map / Hashing · State-Machine
//     Parsing · Expand-Around-Center · Build-by-Prepend · String-Doubling
//
//  ✅ Variations Explored
//     Iterative vs. recursive atoi, sort-based vs. two-map isomorphism
//     checking, sort-based vs. fixed-array anagram checking, vertical-scan
//     vs. sort-based longest common prefix, brute-force vs.
//     expand-around-center palindrome search.
//
//  ✅ Applications
//     This topic underpins parsing/validation logic used constantly in
//     real systems (input sanitization, tokenizers, protocol parsers);
//     the Sliding Window and Frequency Map techniques reappear directly in
//     the Two Pointer & Sliding Window and Hashing topic files; the
//     State-Machine Parsing idea generalizes to any structured-text
//     parsing task (JSON, config files, expressions).
//
//  ✅ Related Topics To Revise Next
//     Two Pointer & Sliding Window (dedicated file, deeper variable-window
//     coverage) · Hashing (dedicated file) · Recursion & Backtracking (for
//     Palindrome Partitioning, which builds directly on this file's
//     palindrome-checking foundation) · Stack (for Valid Parentheses,
//     which builds on this file's depth-counter intuition from Problem 1).
//
//  ✅ Difficulty Level Of This Topic Overall: Easy -> Medium, with one
//     Hard-adjacent problem (Longest Palindromic Substring, especially if
//     Manacher's O(n) follow-up is pursued). A strong foundation for the
//     Recursion/Backtracking and DP-Strings topics that build directly on
//     palindrome and substring reasoning introduced here.
//
// ================================================================================


// ================================================================================
//  SECTION Z3 — SPACED-REPETITION REVISION SCHEDULE
// ================================================================================
//
//  Suggested cadence for every problem in this file: Day 1 (solve) ->
//  Day 3 (re-solve without looking) -> Day 7 -> Day 21 -> Day 60. On each
//  revisit, try to reproduce from memory, in order: (1) the pattern name,
//  (2) the 60-Second Pitch, (3) the code, (4) the edge cases -- recalling
//  the PATTERN first is what actually transfers to unfamiliar problems in
//  a real interview, not memorizing this exact code.
//
//  [ ] Day 1  -- solved all 14 problems, understood every bug fix (especially Bugs 1-2-5, the file-breaking ones)
//  [ ] Day 3  -- re-derived Problems 1-7  from the pattern name alone
//  [ ] Day 3  -- re-derived Problems 8-14 from the pattern name alone
//  [ ] Day 7  -- full re-solve, timed, using the Time-Boxing guidance per problem
//  [ ] Day 21 -- full re-solve, timed, cold (no notes)
//  [ ] Day 60 -- final check before live interviews begin
//
//  Prioritize revisiting: Longest Palindromic Substring (13, both for the
//  algorithm AND as a reminder of the PHP scoping bugs), atoi Iterative
//  (10, the overflow-guard ordering is easy to get subtly wrong from
//  memory), and Count Substrings Exactly K Distinct (12, the atMost(K)-
//  minus-atMost(K-1) decomposition is a genuinely non-obvious trick worth
//  over-rehearsing) -- these three are the most likely to trip you up cold.
//
// ================================================================================
?>
