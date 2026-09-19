<?php

/*
|--------------------------------------------------------------------------
| Problem: Next Greater Element I (LC 496)
| Source: DS/8. Stack/12. Stack.php
| Pattern: Monotonic Stack
|--------------------------------------------------------------------------
*/

/*
Problem Description:
============================================================
8. NEXT GREATER ELEMENT I  (LeetCode 496)
============================================================
Intuition — Monotonic Stack:
  For each element, find the first element to its RIGHT
  that is strictly GREATER. Return -1 if none exists.

  Brute force: O(N²) — for each element, scan rightward.
  Optimal   : O(N)  — traverse RIGHT TO LEFT.

  Right-to-left scan with a DECREASING monotonic stack:
    - Stack always holds elements in DECREASING order (largest at bottom).
    - For each element arr[i]:
      1. Pop all elements from stack that are ≤ arr[i]
         (they can never be the answer for arr[i] or any future element).
      2. If stack is empty → no greater element to the right → -1.
      3. Else → stack.top() is the NEXT GREATER element.
      4. Push arr[i] onto the stack.

Dry Run: arr = [4, 5, 2, 10, 8]  (right-to-left)
  i=4 (8):  stack=[].   ans[4]=-1.  push 8.  stack=[8]
  i=3 (10): 8≤10 → pop. stack=[]. ans[3]=-1. push 10. stack=[10]
  i=2 (2):  10>2.       ans[2]=10. push 2.  stack=[10,2]
  i=1 (5):  2≤5 → pop.  10>5.     ans[1]=10. push 5. stack=[10,5]
  i=0 (4):  5>4.        ans[0]=5.  push 4.  stack=[10,5,4]
  Result: [5, 10, 10, -1, -1]  ✓

TC: O(N)  SC: O(N) — each element pushed/popped at most once
----------------------------------------------------------

Example:
Input: arr=[4,5,2,10,8]
Output: [5,10,10,-1,-1]

Why This Pattern:
A stack of indices/values is kept strictly decreasing from bottom to top; scanning right to left (or left to right with a pop-while-smaller loop), each element pops every smaller element off the top before being pushed itself, since those popped elements have just found their next-greater - the handbook's own explicit #1 core/flagship problem for this pattern ('Foundational mechanics').
*/

function nextGreaterElement(array $arr): array
{
    $n      = count($arr);
    $result = array_fill(0, $n, -1); // Default: -1 (no greater element)
    $stack  = [];                    // Monotonic decreasing stack

    for ($i = $n - 1; $i >= 0; $i--) { // Traverse RIGHT to LEFT
        // Pop elements ≤ current (they're no longer useful)
        while (!empty($stack) && end($stack) <= $arr[$i]) {
            array_pop($stack);
        }
        // Stack top (if exists) is the NEXT GREATER element
        if (!empty($stack)) {
            $result[$i] = end($stack);
        }
        $stack[] = $arr[$i]; // Push current element for future comparisons
    }

    return $result;
}

