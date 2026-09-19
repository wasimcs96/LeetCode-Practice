<?php

/*
|--------------------------------------------------------------------------
| Problem: Reverse Linked List — Iterative Solution (LC 206)
| Source: DS/6. LinkedList/9. Single-LL.php
| Pattern: Linked List Reversal
|--------------------------------------------------------------------------
*/

/*
Problem Description:
REVERSE A LINKED LIST — Iterative (LeetCode 206)
Intuition:
  Maintain three pointers: prev, curr, next.
  At each step: reverse link (curr->next = prev), advance all three.

Dry Run: list=1→2→3→4→5
  prev=null, curr=1
  Step 1: next=2, 1->next=null, prev=1, curr=2
  Step 2: next=3, 2->next=1,    prev=2, curr=3
  Step 3: next=4, 3->next=2,    prev=3, curr=4
  Step 4: next=5, 4->next=3,    prev=4, curr=5
  Step 5: next=null, 5->next=4, prev=5, curr=null
  head = 5  →  5 → 4 → 3 → 2 → 1 → NULL  ✓

TC: O(N)  SC: O(1)

Example:
Input: list = 1→2→3→4→5
Output: 5→4→3→2→1

Why This Pattern:
The flagship Linked List Reversal problem, iterative form: a prev/curr/next triple of pointers rewires every next-link one node at a time - the technique the whole pattern is named after.
*/

    public function reverseIterative(): ?Node
    {
        $prev    = null;
        $current = $this->head;

        while ($current !== null) {
            $next          = $current->next; // Save next before breaking the link
            $current->next = $prev;          // Reverse the link
            $prev          = $current;       // Advance prev
            $current       = $next;          // Advance current
        }

        $this->head = $prev; // prev now points to the new head (old tail)
        return $prev;
    }


/*
|--------------------------------------------------------------------------
| Problem: Reverse Linked List — Recursive Solution (LC 206)
| Source: DS/6. LinkedList/9. Single-LL.php
| Pattern: Linked List Reversal
|--------------------------------------------------------------------------
*/

/*
Problem Description:
REVERSE A LINKED LIST — Recursive (LeetCode 206)
Intuition:
  Recurse to the LAST node (new head).
  On the way BACK, reverse each link:
    head->next->next = head   (next node points back to current)
    head->next = null         (break forward link)

Dry Run: list=1→2→3
  reverseRec(1): calls reverseRec(2)
    reverseRec(2): calls reverseRec(3)
      reverseRec(3): 3->next==null → BASE. return 3 (new head)
    Back at 2: 2->next->next=2 (3->next=2), 2->next=null. return 3
  Back at 1: 1->next->next=1 (2->next=1), 1->next=null. return 3
  head = 3  →  3 → 2 → 1 → NULL  ✓

TC: O(N)  SC: O(N) — recursion stack depth

Example:
Input: list = 1→2→3→4→5
Output: 5→4→3→2→1

Why This Pattern:
The same flagship reversal problem solved recursively: the list is reversed on the way back up the call stack (head->next->next = head), the standard recursive derivation the handbook uses to generalize into k-group reversal.
*/

    public function reverseRecursive(?Node $head): ?Node
    {
        // Base case: empty or single node — already reversed
        if ($head === null || $head->next === null) {
            $this->head = $head;
            return $head;
        }

        // Recurse to the end; newHead is the last node
        $newHead = $this->reverseRecursive($head->next);

        $head->next->next = $head; // Next node points BACK to current
        $head->next       = null;  // Break the original forward link

        $this->head = $newHead;
        return $newHead;
    }


/*
|--------------------------------------------------------------------------
| Problem: Palindrome Linked List (LC 234)
| Source: DS/6. LinkedList/9. Single-LL.php
| Pattern: Linked List Reversal
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
A dual-pattern solution: step two reverses the second half in place before comparing it against the first half - a direct application of the Linked List Reversal pattern as the second half of this solution.
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
| Problem: Reverse Nodes in k-Group (LC 25)
| Source: DS/6. LinkedList/9. Single-LL.php
| Pattern: Linked List Reversal
|--------------------------------------------------------------------------
*/

/*
Problem Description:
(No written description in the source beyond the code/title itself.)

Example:
Input: list = 1→2→3→4→5, k = 2
Output: 2→1→4→3→5

Why This Pattern:
The hardest flagship problem in the Linked List Reversal pattern: find the k-th node of each group, reverse just that group with the same prev/curr/next mechanics as basic list reversal, then stitch the reversed groups back together - the user's own Linked-List-Reversal handbook lists 'Reverse Nodes in k-Group' as a core Advanced/Hard example (#4 in its problem list).
*/

    function reverseKGroupWithProperComments($head, $k)
    {
        // Dummy node helps us easily connect the reversed groups.
        $dummy = new ListNode(-1);

        // Tail of the already processed/reversed part.
        $previousGroupTail = $dummy;

        // Start processing from the first node.
        $currentNode = $head;

        while ($currentNode != null) {

            /*
            * ---------------------------------------------------------
            * STEP 1: Find the end of the current group
            * ---------------------------------------------------------
            *
            * Example: k = 2
            *
            * 1 → 2 → 3 → 4 → 5
            * ↑   ↑
            * start end
            */

            $groupStart = $currentNode;

            $groupEnd = $currentNode;

            // Move k-1 times to find the kth node.
            for ($i = 1; $i < $k; $i++) {

                // Not enough nodes remaining.
                if ($groupEnd == null) {
                    return $dummy->next;
                }

                $groupEnd = $groupEnd->next;
            }

            // If fewer than k nodes remain, don't reverse them.
            if ($groupEnd == null) {
                break;
            }

            /*
            * ---------------------------------------------------------
            * STEP 2: Save the next group
            * ---------------------------------------------------------
            *
            * Example:
            *
            * 1 → 2 → 3 → 4 → 5
            *     ↑
            *  groupEnd
            *
            * nextGroupStart = 3
            */

            $nextGroupStart = $groupEnd->next;

            /*
            * ---------------------------------------------------------
            * STEP 3: Reverse current group
            * ---------------------------------------------------------
            *
            * Before:
            *
            * 1 → 2 → 3
            *
            * Current group:
            *
            * 1 → 2
            *
            * After:
            *
            * 2 → 1 → 3
            */

            $previousNode = $nextGroupStart;
            $currentNode = $groupStart;

            while ($currentNode != $nextGroupStart) {

                $nextNode = $currentNode->next;

                $currentNode->next = $previousNode;

                $previousNode = $currentNode;
                $currentNode = $nextNode;
            }

            /*
            * ---------------------------------------------------------
            * STEP 4: Connect previous group to reversed group
            * ---------------------------------------------------------
            *
            * previousGroupTail
            *       ↓
            * dummy → 2 → 1 → 3
            *
            * groupStart (1) is now the TAIL of reversed group.
            */

            $previousGroupTail->next = $groupEnd;

            /*
            * ---------------------------------------------------------
            * STEP 5: Update previousGroupTail
            * ---------------------------------------------------------
            *
            * groupStart is now the tail.
            *
            * dummy → 2 → 1 → 3
            *              ↑
            *      previousGroupTail
            */

            $previousGroupTail = $groupStart;

            /*
            * ---------------------------------------------------------
            * STEP 6: Move to next group
            * ---------------------------------------------------------
            */

            $currentNode = $nextGroupStart;
        }

        return $dummy->next;
    }


/*
|--------------------------------------------------------------------------
| Problem: Reverse a Doubly Linked List
| Source: DS/6. LinkedList/10. DLL.php
| Pattern: Linked List Reversal
|--------------------------------------------------------------------------
*/

/*
Problem Description:
12. REVERSE A DOUBLY LINKED LIST (In-Place Pointer Swap)
Intuition:
  For each node: SWAP its prev and next pointers.
  After the swap, current->prev holds the ORIGINAL next.
  Advance: current = current->prev  (moves to original next node).
  When current->prev becomes null after swap
    → original next was null → we're at the original tail → new head.

Dry Run: list=1 ↔ 2 ↔ 3
  curr=Node(1) [prev=null,next=2]: swap → prev=2,next=null. curr=Node(2)
  curr=Node(2) [prev=1,  next=3]: swap → prev=3,next=1.    curr=Node(3)
  curr=Node(3) [prev=2,  next=null]: swap → prev=null,next=2.
                                     prev==null → head=Node(3). curr=null. STOP.
  Final: 3->next=2, 2->prev=3, 2->next=1, 1->prev=2, 1->next=null
  Result: NULL ↔ 3 ↔ 2 ↔ 1 ↔ NULL  ✓

TC: O(N)  SC: O(1)

Example:
Input: list = 1↔2↔3
Output: 3↔2↔1

Why This Pattern:
A single-pass in-place pointer swap (swap prev/next on every node) - the doubly-linked-list counterpart of the singly-linked Reverse Linked List problem, same reversal mechanics applied to both pointers per node.
*/

    public function reverse(): void
    {
        if ($this->head === null || $this->head->next === null) return;

        $current = $this->head;

        while ($current !== null) {
            // Swap prev and next for current node
            $temp          = $current->prev;
            $current->prev = $current->next;    // prev ← original next
            $current->next = $temp;             // next ← original prev (saved)

            // After swap, current->prev is the ORIGINAL next node.
            // If it's null, we just processed the original tail → new head.
            if ($current->prev === null) {
                $this->head = $current;
            }

            $current = $current->prev;          // Move to original next (now in prev slot)
        }
    }

