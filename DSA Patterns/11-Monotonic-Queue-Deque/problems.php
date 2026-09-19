<?php

/*
|--------------------------------------------------------------------------
| Problem: Sliding Window Maximum - Monotonic Deque (LC 239)
| Source: DS/9. Queue/13. Queue.php
| Pattern: Monotonic Queue / Deque
|--------------------------------------------------------------------------
*/

/*
Problem Description:
============================================================
5. SLIDING WINDOW MAXIMUM — MONOTONIC DEQUE  (LeetCode 239)
============================================================
Intuition:
  Given array $nums and window size $k, find the maximum in
  each sliding window of size $k. Brute force is O(N×k).

  Optimal: Monotonic Deque (double-ended queue) — O(N).
  The deque stores INDICES and maintains a DECREASING order
  of values (largest value's index at the front).

  For each new index $i:
  1. REMOVE OUT-OF-WINDOW indices from the FRONT:
     If front index ≤ i - k → it's no longer in the window.
  2. REMOVE USELESS indices from the REAR:
     Pop from back while nums[back] ≤ nums[i].
     (Smaller elements behind the current one can NEVER be
      the window maximum — they'll be gone before $i leaves.)
  3. ADD current index $i to the REAR.
  4. RECORD ANSWER once the first full window is formed (i >= k-1).
     The front of the deque holds the index of the WINDOW MAXIMUM.

Dry Run: nums=[1,3,-1,-3,5,3,6,7], k=3
  i=0 (1):  dq=[], push 0.      dq=[0]         window not full yet
  i=1 (3):  3>nums[0]=1 → pop 0. push 1.        dq=[1]
  i=2 (-1): -1<3 → push 2.      dq=[1,2]  i>=k-1=2 → max=nums[1]=3  ✓
  i=3 (-3): -3<-1 → push 3.     dq=[1,2,3] front=1 (in window[1..3]) → max=nums[1]=3  ✓
  i=4 (5):  5>nums[3]=-3→pop3, 5>nums[2]=-1→pop2, 5>nums[1]=3→pop1. push 4. dq=[4]
             front=4, 4>4-3=1 (ok) → max=nums[4]=5  ✓
  i=5 (3):  3<5 → push 5.       dq=[4,5] → max=nums[4]=5  ✓
  i=6 (6):  6>nums[5]=3→pop5, 6>nums[4]=5→pop4. push 6. dq=[6] → max=6  ✓
  i=7 (7):  7>nums[6]=6→pop6. push 7. dq=[7] → max=7  ✓
  Result: [3, 3, 5, 5, 6, 7]  ✓

TC: O(N) — each index is pushed and popped at most once
SC: O(k) — deque holds at most k indices at any time
----------------------------------------------------------

Example:
Input: nums=[1,3,-1,-3,5,3,6,7], k=3
Output: [3,3,5,5,6,7]

Why This Pattern:
A deque of indices is kept with strictly decreasing values from front to back; every new element pops smaller elements off the back before being pushed, and any index that has fallen out of the window is popped off the front - the handbook's own explicit core Beginner example ('dual-eviction mechanics') for Sliding Window Maximum (239), the flagship problem for this pattern.
*/

function slidingWindowMax(array $nums, int $k): array
{
    $n      = count($nums);
    $result = [];
    $deque  = []; // Stores INDICES; values in DECREASING order front→rear

    for ($i = 0; $i < $n; $i++) {

        // Step 1: Remove indices that have fallen outside the current window
        while (!empty($deque) && $deque[0] <= $i - $k) {
            array_shift($deque); // Pop from FRONT (O(N) here; use SplDeque for O(1))
        }

        // Step 2: Remove indices from REAR whose values are ≤ nums[i]
        // They can never be the maximum for any future window
        while (!empty($deque) && $nums[end($deque)] <= $nums[$i]) {
            array_pop($deque); // Pop from REAR
        }

        // Step 3: Add current index to the REAR
        $deque[] = $i;

        // Step 4: Record the maximum once the first full window is complete
        if ($i >= $k - 1) {
            $result[] = $nums[$deque[0]]; // Front holds index of window maximum
        }
    }

    return $result;
}

