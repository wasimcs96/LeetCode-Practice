<?php

/*
|--------------------------------------------------------------------------
| Problem: Middle of the Linked List (LC 876)
| Source: DS/6. LinkedList/9. Single-LL.php
| Pattern: Fast & Slow Pointers
|--------------------------------------------------------------------------
*/

/*
Problem Description:
MIDDLE OF LINKED LIST (LeetCode 876)
Intuition — Slow-Fast Pointer (Tortoise & Hare):
  Fast moves 2 steps, Slow moves 1 step.
  When Fast reaches end, Slow is at the middle.
  Condition: fast->next && fast->next->next  → returns FIRST middle.

Dry Run (odd): list=1→2→3→4→5
  Step 1: slow=1,fast=1 → slow=2,fast=3
  Step 2: slow=2,fast=3 → slow=3,fast=5; fast->next=null → STOP
  Return slow=3  ✓

Dry Run (even): list=1→2→3→4
  Step 1: slow=1,fast=1 → slow=2,fast=3; fast->next->next=null → STOP
  Return slow=2 (first mid)  ✓

TC: O(N)  SC: O(1)

Example:
Input: list = 1→2→3→4→5
Output: node with value 3 (the middle)

Input: list = 1→2→3→4→5→6
Output: node with value 4 (first of the two middles)

Why This Pattern:
Classic fast/slow pointers: fast moves two steps for every one step of slow, so when fast reaches the end, slow sits exactly at the middle - the foundational Fast & Slow Pointers technique.
*/

    public function findMiddle(): ?Node
    {
        if ($this->head === null) return null;

        $slow = $this->head;
        $fast = $this->head;

        while ($fast->next !== null && $fast->next->next !== null) {
            $slow = $slow->next;
            $fast = $fast->next->next;
        }

        return $slow; // First middle for even-length, middle for odd-length
    }


/*
|--------------------------------------------------------------------------
| Problem: Linked List Cycle (LC 141)
| Source: DS/6. LinkedList/9. Single-LL.php
| Pattern: Fast & Slow Pointers
|--------------------------------------------------------------------------
*/

/*
Problem Description:
DETECT CYCLE (LeetCode 141) — Floyd's Cycle Detection
Intuition:
  Fast moves 2 steps, Slow moves 1 step.
  If cycle exists, fast laps slow → they MEET inside the cycle.
  If no cycle, fast reaches null.

Dry Run: list=1→2→3→4→2 (cycle at node 2)
  slow=1,fast=1
  Iter 1: slow=2, fast=3
  Iter 2: slow=3, fast=2 (fast loops: 4→2)
  Iter 3: slow=4, fast=4 → slow===fast → CYCLE  ✓

TC: O(N)  SC: O(1)

Example:
Input: list = 3→2→0→-4, tail connects back to the node valued 2
Output: true

Why This Pattern:
Tortoise-and-Hare: a fast pointer gains one step on a slow pointer every iteration, so if a cycle exists they are guaranteed to meet inside it - the foundational Fast & Slow Pointers cycle-detection technique.
*/

    public function hasCycle(?Node $head): bool
    {
        //hash map approch if repeat element  found then it is a loop 
        // Approch -1 HasMap   
        // $visited = [];
        // $current = $head;
        // while ($current !== null) {
        //     $hash = spl_object_hash($current);
        //     if (isset($visited[$hash])) {
        //         return true;
        //     }
        //     $visited[$hash] = true;
        //     $current = $current->next;
        // }
        // return false;


        //OR
        //Tortoise and Hare Algorithm approch
        $slowPtr = $head;
        $fastPtr = $head;
        if($fastPtr == null || $fastPtr->next == null) return false;

        while($fastPtr != null && $fastPtr->next != null){
            $slowPtr = $slowPtr->next;
            $fastPtr = $fastPtr->next->next;
            
            if($slowPtr === $fastPtr) {
                return true;
            }
        }
        return false;

    }


/*
|--------------------------------------------------------------------------
| Problem: Linked List Cycle II (LC 142)
| Source: DS/6. LinkedList/9. Single-LL.php
| Pattern: Fast & Slow Pointers
|--------------------------------------------------------------------------
*/

/*
Problem Description:
FIND CYCLE START (LeetCode 142) — Floyd's + Math Proof
Intuition:
  Let: L = head → cycle start, C = cycle length,
       D = cycle start → meeting point
  When they meet: fast = 2×slow
  → L + D + C = 2(L + D)  →  L = C − D
  So: (head → start) == (meeting point → start).
  Reset slow to head, move both ONE step → they meet at start.

TC: O(N)  SC: O(1)

Example:
Input: list = 3→2→0→-4, tail connects back to the node valued 2
Output: the node valued 2 (cycle entry point)

Why This Pattern:
Phase two of Floyd's algorithm: once the fast/slow pointers meet, resetting one pointer to head and advancing both one step at a time makes them meet exactly at the cycle's start - a direct extension of the same Fast & Slow Pointers mechanics used in Linked List Cycle.
*/

    public function detectCycleStart(?Node $head): ?Node
    {
        if ($head === null || $head->next === null) return null;

        $slow = $head;
        $fast = $head;

        // Phase 1: Find meeting point inside the cycle
        while ($fast !== null && $fast->next !== null) {
            $slow = $slow->next;
            $fast = $fast->next->next;

            if ($slow === $fast) {
                // Phase 2: Find cycle start
                $slow = $head; // Reset slow to head
                while ($slow !== $fast) {
                    $slow = $slow->next;
                    $fast = $fast->next;
                }
                return $slow; // Both point to cycle start
            }
        }

        return null; // No cycle
    }


/*
|--------------------------------------------------------------------------
| Problem: Palindrome Linked List (LC 234)
| Source: DS/6. LinkedList/9. Single-LL.php
| Pattern: Fast & Slow Pointers
|--------------------------------------------------------------------------
*/

/*
Problem Description:
PALINDROME LINKED LIST (LeetCode 234)
Intuition:
  1. Find FIRST middle using slow-fast pointer.
  2. Reverse the SECOND HALF starting from slow->next.
  3. Compare first half and reversed second half node-by-node.

Dry Run: list=1→2→3→2→1
  Middle: slow=node(2) [first mid]
  Reverse [3→2→1] → [1→2→3]
  Compare: 1==1, 2==2, 3==3 → true  ✓

Dry Run: list=1→2
  Middle: slow=node(1); reverse [2]=[2]
  Compare: 1≠2 → false  ✓

TC: O(N)  SC: O(1)

Example:
Input: list = 1→2→2→1
Output: true

Input: list = 1→2
Output: false

Why This Pattern:
A dual-pattern solution: step one locates the first middle with a fast/slow pointer pair, the same mechanics as Middle of Linked List.
*/

    public function isPalindrome(?Node $head): bool
    {

    // $length = $this->getLength();
        // $current = $this->head; $stackArr = [];
        // while($current){
        //     $stackArr[] = $current->val;
        //     $current = $current->next;
        // }
        // $current = $this->head;
        // while($length >= 1){
        //     if($current->val !=  $stackArr[$length-1]) return false;
        //     $current = $current->next;
        //     $length--;
        // }
        // return true;
        if ($head === null || $head->next === null) return true;

        // Step 1: Find first middle (use &&, NOT || to avoid null crash)
        $slow = $head;
        $fast = $head;
        while ($fast->next !== null && $fast->next->next !== null) {
            $slow = $slow->next;
            $fast = $fast->next->next;
        }

        // Step 2: Reverse second half (starts at slow->next)
        $prev    = null;
        $current = $slow->next;
        while ($current !== null) {
            $next          = $current->next;
            $current->next = $prev;
            $prev          = $current;
            $current       = $next;
        }

        // Step 3: Compare both halves
        $left  = $head;
        $right = $prev;
        while ($right !== null) {
            if ($left->data !== $right->data) return false;
            $left  = $left->next;
            $right = $right->next;
        }

        return true;
    }


/*
|--------------------------------------------------------------------------
| Problem: Remove Nth Node From End of List (LC 19)
| Source: DS/6. LinkedList/9. Single-LL.php
| Pattern: Fast & Slow Pointers
|--------------------------------------------------------------------------
*/

/*
Problem Description:
REMOVE Nth NODE FROM END (LeetCode 19)
Intuition — Two Pointer with N-step gap:
  Move fast N steps ahead.
  Move both together until fast->next == null.
  Now slow is just BEFORE the node to delete.
  Special: if fast==null after N steps → head is the target.

Dry Run: list=1→2→3→4→5, n=2
  fast moves 2 steps: fast=node(3)
  Move together: fast=4,slow=2; fast=5,slow=3; fast->next=null → STOP
  slow=node(3), delete slow->next (node 4)
  Result: 1 → 2 → 3 → 5 → NULL  ✓

TC: O(N)  SC: O(1)

Example:
Input: list = 1→2→3→4→5, n = 2
Output: 1→2→3→5

Why This Pattern:
A fixed-gap fast/slow pointer pair: advancing the fast pointer n steps ahead first means that when fast reaches the end, slow is positioned exactly one node before the target - the same fast/slow mechanics as Middle of Linked List, tuned with an offset.
*/

    public function removeNthFromEnd(int $n): void
    {
        if ($this->head === null) return;

        $fast = $this->head;
        $slow = $this->head;

        // Move fast N steps ahead
        for ($i = 0; $i < $n; $i++) {
            if ($fast === null) return; // n > list length
            $fast = $fast->next;
        }

        // fast==null means the head itself is the Nth from end
        if ($fast === null) {
            $this->head = $this->head->next;
            return;
        }

        // Move both until fast is at the last node
        while ($fast->next !== null) {
            $fast = $fast->next;
            $slow = $slow->next;
        }

        // slow is just before the node to delete
        $slow->next = $slow->next->next;
    }


/*
|--------------------------------------------------------------------------
| Problem: Delete the Middle Node of a Linked List (LC 2095)
| Source: DS/6. LinkedList/9. Single-LL.php
| Pattern: Fast & Slow Pointers
|--------------------------------------------------------------------------
*/

/*
Problem Description:
DELETE MIDDLE NODE (LeetCode 2095)
Intuition:
  Fast starts 2 steps ahead of slow, so when fast reaches end,
  slow is the node JUST BEFORE the middle.

Dry Run: list=1→2→3→4→5
  slow=1, fast=3 (starts 2 ahead)
  Step 1: slow=2, fast=5; fast->next=null → STOP
  slow=2, delete slow->next (node 3)
  Result: 1 → 2 → 4 → 5 → NULL  ✓

TC: O(N)  SC: O(1)

Example:
Input: list = 1→3→4→7→1→2→6
Output: 1→3→4→1→2→6

Why This Pattern:
Same fast/slow mechanics as Middle of Linked List, just starting fast two steps ahead of slow so that slow naturally lands just before the middle node that needs to be unlinked.
*/

    public function deleteMiddle($head): void
 {
        //Mid Approch
        // $this->head = $head;
        // if($this->head == null || $this->head->next == null) {
        //     $this->head = null;
        //     return $this->head;
        // }

        // if($this->head->next->next == null){
        //     $this->head->next = null;
        //     return $this->head;
        // }
        // $length = $this->getLength();
        // $midNode = intval($length / 2);

        // $current = $this->head; $counter = 1;

        // while($counter < $midNode){
        //     $current = $current->next;
        //     $counter++;
        // }
        // $current->next = $current->next->next;
        // return $this->head;


        //Fast and Slow Pointer Approch
        if($head == null || $head->next == null) {
            return null;
        }
        if($head->next->next == null) {
            $head->next = null; 
            return $head;
        }

        $slowPtr = $fastPtr = $head;
        //$fastPtr = $fastPtr->next->next; //->second approch skip 1 step approch

        while($fastPtr != null && $fastPtr->next != null) {
            $fastPtr = $fastPtr->next->next;
            $slowPtr = $slowPtr->next ;
        }
        //$slowPtr->val = $slowPtr->next->val; //delete this line in second approch skip 1 step approch
        $slowPtr->next = $slowPtr->next->next;
        return $head;
    }


/*
|--------------------------------------------------------------------------
| Problem: Sort List — Merge Sort on a Linked List (LC 148)
| Source: DS/6. LinkedList/9. Single-LL.php
| Pattern: Fast & Slow Pointers
|--------------------------------------------------------------------------
*/

/*
Problem Description:
SORT A LINKED LIST — Merge Sort (LeetCode 148)
Intuition:
  1. Find middle (first-mid variant).
  2. Split into two halves (mid->next = null).
  3. Recursively sort each half.
  4. Merge the two sorted halves.

Dry Run: list=4→2→1→3
  Split: [4→2] and [1→3]
  Sort [4→2]: split [4] and [2] → merge → [2→4]
  Sort [1→3]: split [1] and [3] → merge → [1→3]
  Merge: 1→2→3→4  ✓

TC: O(N log N)  SC: O(log N) — recursion stack

Example:
Input: list = 4→2→1→3
Output: 1→2→3→4

Why This Pattern:
Finding the split point uses the same fast/slow middle-finding technique as Middle of Linked List before the list is divided in two.
*/

    private function getMidForSort(?Node $head): ?Node
    {
        $slow = $head;
        $fast = $head;
        while ($fast->next !== null && $fast->next->next !== null) {
            $slow = $slow->next;
            $fast = $fast->next->next;
        }
        return $slow; // Returns first middle (left half gets ≤ right half)
    }

    public function sortList(?Node $head): ?Node
    {
        if ($head === null || $head->next === null) return $head;

        $mid       = $this->getMidForSort($head);
        $rightHead = $mid->next; // Right half starts after mid
        $mid->next = null;       // Cut the list in two

        $sortedLeft  = $this->sortList($head);
        $sortedRight = $this->sortList($rightHead);

        return $this->mergeTwoSortedLists($sortedLeft, $sortedRight);
    }


/*
|--------------------------------------------------------------------------
| Problem: Intersection of Two Linked Lists (LC 160)
| Source: DS/6. LinkedList/9. Single-LL.php
| Pattern: Fast & Slow Pointers
|--------------------------------------------------------------------------
*/

/*
Problem Description:
FIND INTERSECTION NODE (LeetCode 160)
Intuition — Length difference trick:
  Advance the longer list's pointer by |LA − LB| steps.
  Both pointers are now equidistant from the intersection.
  Move both one step at a time until they point to the SAME node.

Dry Run: A=1→3→5→7→9 (len=5), B=2→4→7→9 (len=4)
  diff=1 → advance A by 1 → A starts at node(3)
  Compare: 3≠2, 5≠4, 7==7 → return node(7)  ✓

TC: O(N+M)  SC: O(1)

Example:
Input: listA = 4→1→8→4→5, listB = 5→6→1→8→4→5 (share tail 8→4→5)
Output: node valued 8

Why This Pattern:
Two pointers walk both lists and swap onto the other list's head when they hit null, which equalizes the remaining distance without precomputing list lengths - the user's own Fast & Slow Pointers handbook lists this as a related pointer-partitioning technique on a linked chain.
*/

    public function getIntersectionNode(?Node $headA, ?Node $headB): ?Node
    {
        if ($headA === null || $headB === null) return null;

        $lenA = 0; $nodeA = $headA;
        while ($nodeA !== null) { $lenA++; $nodeA = $nodeA->next; }

        $lenB = 0; $nodeB = $headB;
        while ($nodeB !== null) { $lenB++; $nodeB = $nodeB->next; }

        $nodeA = $headA;
        $nodeB = $headB;

        if ($lenA > $lenB) {
            $diff = $lenA - $lenB;
            while ($diff-- > 0) $nodeA = $nodeA->next;
        } elseif ($lenB > $lenA) {
            $diff = $lenB - $lenA;
            while ($diff-- > 0) $nodeB = $nodeB->next;
        }

        while ($nodeA !== null && $nodeB !== null) {
            if ($nodeA === $nodeB) return $nodeA; // === checks identity (same object)
            $nodeA = $nodeA->next;
            $nodeB = $nodeB->next;
        }

        return null;
    }

