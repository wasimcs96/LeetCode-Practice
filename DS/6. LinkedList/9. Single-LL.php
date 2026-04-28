<?php

// ============================================================
// SINGLY LINKED LIST — Complete Revision Guide
// Topics : Node & List Setup    | Insertion (Head/Tail/Kth/Value)
//          Deletion (Head/Tail/Kth/Value) | Traversal & Search
//          Middle of LL (Slow-Fast)       | Reverse (Iter/Rec)
//          Cycle Detection (Floyd's)      | Palindrome Check
//          Remove Nth from End            | Delete Middle Node
//          Merge Two Sorted Lists         | Sort a Linked List
//          Sort 0s/1s/2s                  | Odd-Even Index (328)
//          Intersection of Two LLs        | Add 1 to LL Number
//          Add Two Numbers (LeetCode 2)
// ============================================================
// Key Intuitions:
//   1. Slow/Fast pointer  → find mid, cycle, Nth from end
//   2. Dummy/sentinel head→ simplifies head-edge cases
//   3. Reverse (iterative)→ prev / curr / next triad
//   4. Two-pointer gap    → Nth from end, intersection length trick
// ============================================================


// ============================================================
// NODE CLASS
// ============================================================

class Node
{
    public $data;
    public $next;

    public function __construct($data = null)
    {
        $this->data = $data;
        $this->next = null;
    }
}


// ============================================================
// SINGLY LINKED LIST CLASS
// ============================================================

class LinkedList
{
    public $head;

    public function __construct()
    {
        $this->head = null;
    }

    // ----------------------------------------------------------
    // HELPER — Print list as a readable chain
    // TC: O(N)  SC: O(1)
    // ----------------------------------------------------------
    public function printList(): void
    {
        $parts   = [];
        $current = $this->head;
        while ($current !== null) {
            $parts[] = (string)$current->data;
            $current = $current->next;
        }
        echo implode(' → ', $parts) . " → NULL\n";
    }


    // ==========================================================
    // CREATION
    // ==========================================================

    // ----------------------------------------------------------
    // Convert an array to a Linked List
    // Intuition: Create head from arr[0], then link remaining nodes.
    //
    // Dry Run: arr=[1,2,3,4,5]
    //   head=Node(1), curr=Node(1)
    //   i=1: curr->next=Node(2), curr=Node(2)
    //   i=2: curr->next=Node(3), curr=Node(3)  ...
    //   Result: 1 → 2 → 3 → 4 → 5 → NULL
    //
    // TC: O(N)  SC: O(N) — N new nodes created
    // ----------------------------------------------------------
    public function fromArray(array $arr): void
    {
        if (empty($arr)) return;

        $this->head = new Node($arr[0]);     // First element becomes the head
        $current    = $this->head;

        for ($i = 1; $i < count($arr); $i++) {
            $current->next = new Node($arr[$i]); // Append new node at the tail
            $current       = $current->next;     // Advance the tail pointer
        }
    }


    // ==========================================================
    // INSERTION
    // ==========================================================

    // ----------------------------------------------------------
    // Insert at the TAIL (end) of the list
    // Intuition: Walk to the last node, then attach new node.
    //
    // Dry Run: list=1→2→3, data=4
    //   Walk: 1→2→3 (3->next == null)
    //   3->next = Node(4)  →  1 → 2 → 3 → 4 → NULL  ✓
    //
    // TC: O(N)  SC: O(1)
    // ----------------------------------------------------------
    public function insertAtEnd(int $data): void
    {
        $newNode = new Node($data);

        if ($this->head === null) {
            $this->head = $newNode; // Empty list → new node becomes head
            return;
        }

        $current = $this->head;
        while ($current->next !== null) {
            $current = $current->next; // Walk to the last node
        }
        $current->next = $newNode;     // Attach new node at the tail
    }

    // ----------------------------------------------------------
    // Insert at the HEAD (beginning) of the list
    // Intuition: New node points to old head; update head pointer.
    //
    // Dry Run: list=1→2→3, data=0
    //   newNode=Node(0), newNode->next=Node(1)
    //   head = Node(0)  →  0 → 1 → 2 → 3 → NULL  ✓
    //
    // TC: O(1)  SC: O(1)
    // ----------------------------------------------------------
    public function insertAtHead(int $data): void
    {
        $newNode       = new Node($data);
        $newNode->next = $this->head;  // New node points to current head
        $this->head    = $newNode;     // Update head to new node
    }

    // ----------------------------------------------------------
    // Insert at the Kth POSITION (1-indexed)
    // Intuition:
    //   k=1 → insertAtHead.
    //   Otherwise walk to node at position k-1 and splice new node.
    //
    // Dry Run: list=1→2→3→4, k=3, data=99
    //   Walk to position 2 (node with data=2)
    //   newNode->next = node(3), node(2)->next = newNode
    //   Result: 1 → 2 → 99 → 3 → 4 → NULL  ✓
    //
    // TC: O(N)  SC: O(1)
    // ----------------------------------------------------------
    public function insertAtPosition(int $data, int $k): void
    {
        $length = $this->getLength();

        if ($k < 1 || $k > $length + 1) return; // Invalid position

        if ($k === 1) {
            $this->insertAtHead($data);
            return;
        }

        $counter = 1;
        $current = $this->head;

        while ($current !== null) {
            if ($counter === $k - 1) {
                $newNode       = new Node($data);
                $newNode->next = $current->next; // New node → old k-th node
                $current->next = $newNode;       // (k-1)-th node → new node
                return;
            }
            $counter++;
            $current = $current->next;
        }
    }

    // ----------------------------------------------------------
    // Insert BEFORE the first node with a given value
    // Intuition:
    //   Walk until current->next holds the target value.
    //   Splice new node between current and current->next.
    //
    // Dry Run: list=1→2→3→4, value=3, data=99
    //   Walk: node(2)->next->data=3 ✓
    //   newNode->next=node(3), node(2)->next=newNode
    //   Result: 1 → 2 → 99 → 3 → 4 → NULL  ✓
    //
    // TC: O(N)  SC: O(1)
    // ----------------------------------------------------------
    public function insertBeforeValue(int $data, int $value): void
    {
        if ($this->head === null) return;

        if ($this->head->data === $value) {
            $this->insertAtHead($data);
            return;
        }

        $current = $this->head;
        while ($current->next !== null) {
            if ($current->next->data === $value) {
                $newNode       = new Node($data);
                $newNode->next = $current->next;
                $current->next = $newNode;
                return;
            }
            $current = $current->next;
        }
    }


    // ==========================================================
    // TRAVERSAL & SEARCH
    // ==========================================================

    // ----------------------------------------------------------
    // Get the length of the list
    // TC: O(N)  SC: O(1)
    // ----------------------------------------------------------
    public function getLength(): int
    {
        $count   = 0;
        $current = $this->head;
        while ($current !== null) {
            $count++;
            $current = $current->next;
        }
        return $count;
    }

    // ----------------------------------------------------------
    // Search for a value; returns true if found.
    // TC: O(N)  SC: O(1)
    // ----------------------------------------------------------
    public function search(int $data): bool
    {
        $current = $this->head;
        while ($current !== null) {
            if ($current->data === $data) return true;
            $current = $current->next;
        }
        return false;
    }


    // ==========================================================
    // DELETION
    // ==========================================================

    // ----------------------------------------------------------
    // Delete the HEAD node
    // Intuition: Simply move head pointer to head->next.
    //
    // Dry Run: list=1→2→3  →  head = Node(2)
    //   Result: 2 → 3 → NULL  ✓
    //
    // TC: O(1)  SC: O(1)
    // ----------------------------------------------------------
    public function deleteHead(): void
    {
        if ($this->head === null) return;
        $this->head = $this->head->next;
    }

    // ----------------------------------------------------------
    // Delete the LAST node
    // Intuition:
    //   Walk until current->next->next is null (second-to-last).
    //   Set current->next = null.
    //
    // Dry Run: list=1→2→3→4
    //   Stop at node(3) because node(3)->next->next==null
    //   node(3)->next = null  →  1 → 2 → 3 → NULL  ✓
    //
    // TC: O(N)  SC: O(1)
    // ----------------------------------------------------------
    public function deleteLast(): void
    {
        if ($this->head === null) return;

        if ($this->head->next === null) {
            $this->head = null; // Single-node list
            return;
        }

        $current = $this->head;
        while ($current->next->next !== null) {
            $current = $current->next;
        }
        $current->next = null;
    }

    // ----------------------------------------------------------
    // Delete the Kth node (1-indexed)
    // Intuition:
    //   k=1 → deleteHead.
    //   Otherwise walk to node k-1 and skip node k.
    //
    // Dry Run: list=1→2→3→4→5, k=3
    //   Walk to node(2); node(2)->next = node(4)
    //   Result: 1 → 2 → 4 → 5 → NULL  ✓
    //
    // TC: O(N)  SC: O(1)
    // ----------------------------------------------------------
    public function deleteAtPosition(int $k): void
    {
        $length = $this->getLength();
        if ($k < 1 || $k > $length) return;

        if ($k === 1) {
            $this->deleteHead();
            return;
        }

        $counter = 1;
        $current = $this->head;

        while ($current !== null) {
            if ($counter === $k - 1) {
                $current->next = $current->next->next; // Skip the k-th node
                return;
            }
            $counter++;
            $current = $current->next;
        }
    }

    // ----------------------------------------------------------
    // Delete the first node with the given VALUE
    // Intuition:
    //   Walk until current->next holds the target value.
    //   Bypass that node.
    //
    // Dry Run: list=1→2→3→4, value=3
    //   Walk: node(2)->next->data=3 ✓
    //   node(2)->next = node(4)  →  1 → 2 → 4 → NULL  ✓
    //
    // TC: O(N)  SC: O(1)
    // ----------------------------------------------------------
    public function deleteByValue(int $value): void
    {
        if ($this->head === null) return;

        if ($this->head->data === $value) {
            $this->deleteHead();
            return;
        }

        $current = $this->head;
        while ($current->next !== null) {
            if ($current->next->data === $value) {
                $current->next = $current->next->next;
                return;
            }
            $current = $current->next;
        }
    }


    // ==========================================================
    // CORE ALGORITHMS
    // ==========================================================

    // ----------------------------------------------------------
    // MIDDLE OF LINKED LIST (LeetCode 876)
    // Intuition — Slow-Fast Pointer (Tortoise & Hare):
    //   Fast moves 2 steps, Slow moves 1 step.
    //   When Fast reaches end, Slow is at the middle.
    //   Condition: fast->next && fast->next->next  → returns FIRST middle.
    //
    // Dry Run (odd): list=1→2→3→4→5
    //   Step 1: slow=1,fast=1 → slow=2,fast=3
    //   Step 2: slow=2,fast=3 → slow=3,fast=5; fast->next=null → STOP
    //   Return slow=3  ✓
    //
    // Dry Run (even): list=1→2→3→4
    //   Step 1: slow=1,fast=1 → slow=2,fast=3; fast->next->next=null → STOP
    //   Return slow=2 (first mid)  ✓
    //
    // TC: O(N)  SC: O(1)
    // ----------------------------------------------------------
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


    // ----------------------------------------------------------
    // REVERSE A LINKED LIST — Iterative (LeetCode 206)
    // Intuition:
    //   Maintain three pointers: prev, curr, next.
    //   At each step: reverse link (curr->next = prev), advance all three.
    //
    // Dry Run: list=1→2→3→4→5
    //   prev=null, curr=1
    //   Step 1: next=2, 1->next=null, prev=1, curr=2
    //   Step 2: next=3, 2->next=1,    prev=2, curr=3
    //   Step 3: next=4, 3->next=2,    prev=3, curr=4
    //   Step 4: next=5, 4->next=3,    prev=4, curr=5
    //   Step 5: next=null, 5->next=4, prev=5, curr=null
    //   head = 5  →  5 → 4 → 3 → 2 → 1 → NULL  ✓
    //
    // TC: O(N)  SC: O(1)
    // ----------------------------------------------------------
    public function reverseIterative(): void
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
    }

    // ----------------------------------------------------------
    // REVERSE A LINKED LIST — Recursive (LeetCode 206)
    // Intuition:
    //   Recurse to the LAST node (new head).
    //   On the way BACK, reverse each link:
    //     head->next->next = head   (next node points back to current)
    //     head->next = null         (break forward link)
    //
    // Dry Run: list=1→2→3
    //   reverseRec(1): calls reverseRec(2)
    //     reverseRec(2): calls reverseRec(3)
    //       reverseRec(3): 3->next==null → BASE. return 3 (new head)
    //     Back at 2: 2->next->next=2 (3->next=2), 2->next=null. return 3
    //   Back at 1: 1->next->next=1 (2->next=1), 1->next=null. return 3
    //   head = 3  →  3 → 2 → 1 → NULL  ✓
    //
    // TC: O(N)  SC: O(N) — recursion stack depth
    // ----------------------------------------------------------
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


    // ----------------------------------------------------------
    // DETECT CYCLE (LeetCode 141) — Floyd's Cycle Detection
    // Intuition:
    //   Fast moves 2 steps, Slow moves 1 step.
    //   If cycle exists, fast laps slow → they MEET inside the cycle.
    //   If no cycle, fast reaches null.
    //
    // Dry Run: list=1→2→3→4→2 (cycle at node 2)
    //   slow=1,fast=1
    //   Iter 1: slow=2, fast=3
    //   Iter 2: slow=3, fast=2 (fast loops: 4→2)
    //   Iter 3: slow=4, fast=4 → slow===fast → CYCLE  ✓
    //
    // TC: O(N)  SC: O(1)
    // ----------------------------------------------------------
    public function hasCycle(?Node $head): bool
    {
        if ($head === null || $head->next === null) return false;

        $slow = $head;
        $fast = $head;

        while ($fast !== null && $fast->next !== null) {
            $slow = $slow->next;
            $fast = $fast->next->next;

            if ($slow === $fast) return true; // Same object → cycle exists
        }

        return false; // Fast reached null → no cycle
    }


    // ----------------------------------------------------------
    // FIND CYCLE START (LeetCode 142) — Floyd's + Math Proof
    // Intuition:
    //   Let: L = head → cycle start, C = cycle length,
    //        D = cycle start → meeting point
    //   When they meet: fast = 2×slow
    //   → L + D + C = 2(L + D)  →  L = C − D
    //   So: (head → start) == (meeting point → start).
    //   Reset slow to head, move both ONE step → they meet at start.
    //
    // TC: O(N)  SC: O(1)
    // ----------------------------------------------------------
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


    // ----------------------------------------------------------
    // PALINDROME LINKED LIST (LeetCode 234)
    // Intuition:
    //   1. Find FIRST middle using slow-fast pointer.
    //   2. Reverse the SECOND HALF starting from slow->next.
    //   3. Compare first half and reversed second half node-by-node.
    //
    // Dry Run: list=1→2→3→2→1
    //   Middle: slow=node(2) [first mid]
    //   Reverse [3→2→1] → [1→2→3]
    //   Compare: 1==1, 2==2, 3==3 → true  ✓
    //
    // Dry Run: list=1→2
    //   Middle: slow=node(1); reverse [2]=[2]
    //   Compare: 1≠2 → false  ✓
    //
    // TC: O(N)  SC: O(1)
    // ----------------------------------------------------------
    public function isPalindrome(?Node $head): bool
    {
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


    // ----------------------------------------------------------
    // REMOVE Nth NODE FROM END (LeetCode 19)
    // Intuition — Two Pointer with N-step gap:
    //   Move fast N steps ahead.
    //   Move both together until fast->next == null.
    //   Now slow is just BEFORE the node to delete.
    //   Special: if fast==null after N steps → head is the target.
    //
    // Dry Run: list=1→2→3→4→5, n=2
    //   fast moves 2 steps: fast=node(3)
    //   Move together: fast=4,slow=2; fast=5,slow=3; fast->next=null → STOP
    //   slow=node(3), delete slow->next (node 4)
    //   Result: 1 → 2 → 3 → 5 → NULL  ✓
    //
    // TC: O(N)  SC: O(1)
    // ----------------------------------------------------------
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


    // ----------------------------------------------------------
    // DELETE MIDDLE NODE (LeetCode 2095)
    // Intuition:
    //   Fast starts 2 steps ahead of slow, so when fast reaches end,
    //   slow is the node JUST BEFORE the middle.
    //
    // Dry Run: list=1→2→3→4→5
    //   slow=1, fast=3 (starts 2 ahead)
    //   Step 1: slow=2, fast=5; fast->next=null → STOP
    //   slow=2, delete slow->next (node 3)
    //   Result: 1 → 2 → 4 → 5 → NULL  ✓
    //
    // TC: O(N)  SC: O(1)
    // ----------------------------------------------------------
    public function deleteMiddle(): void
    {
        if ($this->head === null || $this->head->next === null) {
            $this->head = null;
            return;
        }

        $slow = $this->head;
        $fast = $this->head->next->next; // Fast starts 2 steps ahead of slow

        while ($fast !== null && $fast->next !== null) {
            $slow = $slow->next;
            $fast = $fast->next->next;
        }

        $slow->next = $slow->next->next; // Skip the middle node
    }


    // ==========================================================
    // MERGE & SORT
    // ==========================================================

    // ----------------------------------------------------------
    // MERGE TWO SORTED LINKED LISTS (LeetCode 21)
    // Intuition:
    //   Use a dummy head to simplify edge cases.
    //   Compare heads of both lists; attach the smaller one.
    //   Append remaining nodes after one list is exhausted.
    //
    // Dry Run: l1=1→3→5, l2=2→4→6
    //   1≤2: curr→1; 2<3: curr→2; 3≤4: curr→3
    //   4<5: curr→4; 5≤6: curr→5; l1 done: curr→6
    //   Result: 1 → 2 → 3 → 4 → 5 → 6 → NULL  ✓
    //
    // TC: O(N+M)  SC: O(1) — only re-linking, no new nodes
    // ----------------------------------------------------------
    public function mergeTwoSortedLists(?Node $l1, ?Node $l2): ?Node
    {
        $dummy   = new Node(-1); // Sentinel node
        $current = $dummy;

        while ($l1 !== null && $l2 !== null) {
            if ($l1->data <= $l2->data) {
                $current->next = $l1;
                $l1            = $l1->next;
            } else {
                $current->next = $l2;
                $l2            = $l2->next;
            }
            $current = $current->next;
        }

        $current->next = ($l1 !== null) ? $l1 : $l2; // Attach remaining nodes

        return $dummy->next;
    }


    // ----------------------------------------------------------
    // SORT A LINKED LIST — Merge Sort (LeetCode 148)
    // Intuition:
    //   1. Find middle (first-mid variant).
    //   2. Split into two halves (mid->next = null).
    //   3. Recursively sort each half.
    //   4. Merge the two sorted halves.
    //
    // Dry Run: list=4→2→1→3
    //   Split: [4→2] and [1→3]
    //   Sort [4→2]: split [4] and [2] → merge → [2→4]
    //   Sort [1→3]: split [1] and [3] → merge → [1→3]
    //   Merge: 1→2→3→4  ✓
    //
    // TC: O(N log N)  SC: O(log N) — recursion stack
    // ----------------------------------------------------------
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


    // ----------------------------------------------------------
    // SORT LINKED LIST OF 0s, 1s AND 2s
    // Intuition — Dutch National Flag for LL:
    //   Create three dummy heads (for 0, 1, 2 groups).
    //   Append each node to its group. Connect groups at end.
    //
    // Dry Run: list=1→0→2→1→0
    //   0-group: 0→0 | 1-group: 1→1 | 2-group: 2
    //   Connect: 0→0→1→1→2→NULL  ✓
    //
    // TC: O(N)  SC: O(1) — only 6 pointer variables, no new nodes
    // ----------------------------------------------------------
    public function sortZeroOneTwoList(?Node $head): ?Node
    {
        if ($head === null || $head->next === null) return $head;

        $d0 = new Node(-1); $c0 = $d0; // Dummy head for 0s
        $d1 = new Node(-1); $c1 = $d1; // Dummy head for 1s
        $d2 = new Node(-1); $c2 = $d2; // Dummy head for 2s

        $current = $head;
        while ($current !== null) {
            if ($current->data === 0) {
                $c0->next = $current; $c0 = $c0->next;
            } elseif ($current->data === 1) {
                $c1->next = $current; $c1 = $c1->next;
            } else {
                $c2->next = $current; $c2 = $c2->next;
            }
            $current = $current->next;
        }

        $c2->next = null;                                         // CRITICAL: terminate 2s group
        $c1->next = ($d2->next !== null) ? $d2->next : null;     // 1s → 2s (or null)
        $c0->next = ($d1->next !== null) ? $d1->next : $d2->next; // 0s → 1s (or 2s)

        $this->head = $d0->next;
        return $this->head;
    }


    // ==========================================================
    // REARRANGEMENT
    // ==========================================================

    // ----------------------------------------------------------
    // ODD-EVEN LINKED LIST — by INDEX (LeetCode 328)
    // Intuition:
    //   Odd-indexed nodes (1,3,5…) first, then even-indexed (2,4,6…).
    //   Maintain two pointers: $odd and $even.
    //   $odd  skips one to reach next odd-indexed node.
    //   $even skips one to reach next even-indexed node.
    //   Connect $odd's last to $evenHead at the end.
    //
    // Dry Run: list=1→2→3→4→5 (1-indexed)
    //   odd=1, even=2, evenHead=2
    //   Iter 1: odd->next=3, odd=3; even->next=4, even=4
    //   Iter 2: odd->next=5, odd=5; even->next=null → loop ends
    //   odd->next = evenHead = 2
    //   Result: 1 → 3 → 5 → 2 → 4 → NULL  ✓
    //
    // TC: O(N)  SC: O(1)
    // ----------------------------------------------------------
    public function oddEvenIndexList(?Node $head): ?Node
    {
        if ($head === null || $head->next === null) return $head;

        $odd      = $head;
        $even     = $head->next;
        $evenHead = $head->next; // Remember start of even chain

        while ($odd->next !== null && $even->next !== null) {
            $odd->next  = $even->next;       // Odd → next odd-indexed node
            $even->next = $odd->next->next;  // Even → next even-indexed node
            $odd        = $odd->next;
            $even       = $even->next;
        }

        $odd->next  = $evenHead; // Connect end of odd chain to start of even chain
        $this->head = $head;
        return $head;
    }


    // ==========================================================
    // INTERSECTION
    // ==========================================================

    // ----------------------------------------------------------
    // FIND INTERSECTION NODE (LeetCode 160)
    // Intuition — Length difference trick:
    //   Advance the longer list's pointer by |LA − LB| steps.
    //   Both pointers are now equidistant from the intersection.
    //   Move both one step at a time until they point to the SAME node.
    //
    // Dry Run: A=1→3→5→7→9 (len=5), B=2→4→7→9 (len=4)
    //   diff=1 → advance A by 1 → A starts at node(3)
    //   Compare: 3≠2, 5≠4, 7==7 → return node(7)  ✓
    //
    // TC: O(N+M)  SC: O(1)
    // ----------------------------------------------------------
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


    // ==========================================================
    // ADD NUMBERS
    // ==========================================================

    // ----------------------------------------------------------
    // ADD 1 TO NUMBER REPRESENTED AS LINKED LIST
    // Intuition — Recursive carry from tail:
    //   Recurse to the last node first (rightmost digit).
    //   Add carry=1; propagate carry back through the list.
    //   If carry remains after head, prepend Node(1).
    //
    // Dry Run: list=9→9→9 (represents 999)
    //   Recurse to tail Node(9): 9+1=10 → data=0, carry=1
    //   Back at mid  Node(9): 9+1=10 → data=0, carry=1
    //   Back at head Node(9): 9+1=10 → data=0, carry=1
    //   carry=1 → prepend Node(1)
    //   Result: 1 → 0 → 0 → 0 → NULL (represents 1000)  ✓
    //
    // TC: O(N)  SC: O(N) — recursion stack
    // ----------------------------------------------------------
    private function addOneHelper(?Node $node): int
    {
        if ($node === null) return 1; // Base: initial carry of 1

        $carry       = $this->addOneHelper($node->next); // Recurse to tail first
        $node->data += $carry;

        if ($node->data < 10) {
            return 0; // No overflow → carry stops here
        } else {
            $node->data = $node->data % 10; // Keep single digit
            return 1;                        // Propagate carry upward
        }
    }

    public function addOneToList(): void
    {
        $carry = $this->addOneHelper($this->head);

        if ($carry !== 0) {
            // Entire number rolled over (e.g. 999 → 1000)
            $newNode       = new Node(1);
            $newNode->next = $this->head;
            $this->head    = $newNode;
        }
    }


    // ----------------------------------------------------------
    // ADD TWO NUMBERS (LeetCode 2)
    // Numbers stored in REVERSE order: head = ones digit.
    //
    // Intuition:
    //   Process both lists simultaneously digit by digit.
    //   sum = l1->data + l2->data + carry.
    //   New node = sum % 10;  carry = sum / 10.
    //   Continue until both lists are exhausted AND carry == 0.
    //
    // Dry Run: l1=2→4→3 (342), l2=5→6→4 (465)
    //   2+5+0=7  → Node(7), carry=0
    //   4+6+0=10 → Node(0), carry=1
    //   3+4+1=8  → Node(8), carry=0
    //   Result: 7 → 0 → 8 → NULL  (807 = 342+465)  ✓
    //
    // TC: O(max(N,M))  SC: O(max(N,M)) — result list
    // ----------------------------------------------------------
    public function addTwoNumbers(?Node $l1, ?Node $l2): ?Node
    {
        $dummy   = new Node(-1); // Sentinel for result list
        $current = $dummy;
        $carry   = 0;

        while ($l1 !== null || $l2 !== null || $carry !== 0) {
            $sum = ($l1 !== null ? $l1->data : 0)
                 + ($l2 !== null ? $l2->data : 0)
                 + $carry;

            $carry         = intdiv($sum, 10); // Carry for next iteration
            $current->next = new Node($sum % 10);
            $current       = $current->next;

            $l1 = ($l1 !== null) ? $l1->next : null;
            $l2 = ($l2 !== null) ? $l2->next : null;
        }

        return $dummy->next;
    }
}


// ============================================================
// DEMO — Run All Operations
// ============================================================

echo "=== fromArray + printList ===\n";
$ll = new LinkedList();
$ll->fromArray([1, 2, 3, 4, 5]);
$ll->printList();                            // 1 → 2 → 3 → 4 → 5 → NULL

echo "\n=== Insertion ===\n";
$ll->insertAtHead(0);
$ll->printList();                            // 0 → 1 → 2 → 3 → 4 → 5 → NULL
$ll->insertAtEnd(6);
$ll->printList();                            // 0 → 1 → 2 → 3 → 4 → 5 → 6 → NULL
$ll->insertAtPosition(99, 3);
$ll->printList();                            // 0 → 1 → 99 → 2 → 3 → 4 → 5 → 6 → NULL
$ll->insertBeforeValue(88, 4);
$ll->printList();                            // 0 → 1 → 99 → 2 → 3 → 88 → 4 → 5 → 6 → NULL

echo "\n=== Deletion ===\n";
$ll->fromArray([1, 2, 3, 4, 5]);
$ll->deleteHead();
$ll->printList();                            // 2 → 3 → 4 → 5 → NULL
$ll->deleteLast();
$ll->printList();                            // 2 → 3 → 4 → NULL
$ll->fromArray([1, 2, 3, 4, 5]);
$ll->deleteAtPosition(3);
$ll->printList();                            // 1 → 2 → 4 → 5 → NULL
$ll->fromArray([1, 2, 3, 4, 5]);
$ll->deleteByValue(3);
$ll->printList();                            // 1 → 2 → 4 → 5 → NULL

echo "\n=== Traversal ===\n";
$ll->fromArray([1, 2, 3, 4, 5]);
echo "Length:       " . $ll->getLength() . "\n";                         // 5
echo "Search 3:     " . ($ll->search(3) ? 'true' : 'false') . "\n";     // true
echo "Search 9:     " . ($ll->search(9) ? 'true' : 'false') . "\n";     // false

echo "\n=== Middle of LL ===\n";
$ll->fromArray([1, 2, 3, 4, 5]);
echo "Middle (odd): " . $ll->findMiddle()->data . "\n";   // 3
$ll->fromArray([1, 2, 3, 4]);
echo "Middle (even):" . $ll->findMiddle()->data . "\n";   // 2 (first mid)

echo "\n=== Reverse LL ===\n";
$ll->fromArray([1, 2, 3, 4, 5]);
$ll->reverseIterative();
$ll->printList();                            // 5 → 4 → 3 → 2 → 1 → NULL

$ll->fromArray([1, 2, 3, 4, 5]);
$ll->reverseRecursive($ll->head);
$ll->printList();                            // 5 → 4 → 3 → 2 → 1 → NULL

echo "\n=== Palindrome ===\n";
$ll->fromArray([1, 2, 3, 2, 1]);
echo "Palindrome [1,2,3,2,1]: " . ($ll->isPalindrome($ll->head) ? 'true' : 'false') . "\n"; // true
$ll->fromArray([1, 2, 3, 4]);
echo "Palindrome [1,2,3,4]:   " . ($ll->isPalindrome($ll->head) ? 'true' : 'false') . "\n"; // false

echo "\n=== Remove Nth from End ===\n";
$ll->fromArray([1, 2, 3, 4, 5]);
$ll->removeNthFromEnd(2);
$ll->printList();                            // 1 → 2 → 3 → 5 → NULL

echo "\n=== Delete Middle ===\n";
$ll->fromArray([1, 2, 3, 4, 5]);
$ll->deleteMiddle();
$ll->printList();                            // 1 → 2 → 4 → 5 → NULL

echo "\n=== Merge Two Sorted Lists ===\n";
$ll->fromArray([1, 3, 5]);
$ll2 = new LinkedList();
$ll2->fromArray([2, 4, 6]);
$ll->head = $ll->mergeTwoSortedLists($ll->head, $ll2->head);
$ll->printList();                            // 1 → 2 → 3 → 4 → 5 → 6 → NULL

echo "\n=== Sort Linked List ===\n";
$ll->fromArray([4, 2, 1, 3]);
$ll->head = $ll->sortList($ll->head);
$ll->printList();                            // 1 → 2 → 3 → 4 → NULL

echo "\n=== Sort 0s 1s 2s ===\n";
$ll->fromArray([1, 0, 2, 1, 0]);
$ll->sortZeroOneTwoList($ll->head);
$ll->printList();                            // 0 → 0 → 1 → 1 → 2 → NULL

echo "\n=== Odd-Even Index List (LeetCode 328) ===\n";
$ll->fromArray([1, 2, 3, 4, 5]);
$ll->oddEvenIndexList($ll->head);
$ll->printList();                            // 1 → 3 → 5 → 2 → 4 → NULL

echo "\n=== Add 1 to LL Number ===\n";
$ll->fromArray([9, 9, 9]);
$ll->addOneToList();
$ll->printList();                            // 1 → 0 → 0 → 0 → NULL

echo "\n=== Add Two Numbers (LeetCode 2) ===\n";
$ll->fromArray([2, 4, 3]);   // 342
$ll2->fromArray([5, 6, 4]);  // 465
$ll->head = $ll->addTwoNumbers($ll->head, $ll2->head);
$ll->printList();                            // 7 → 0 → 8 → NULL (807 = 342+465)


// ============================================================
// COMPARISON SUMMARY
// ============================================================
//
//  Operation              | TC          | SC       | Notes
// ------------------------+-------------+----------+------------------
//  Insert at Head         | O(1)        | O(1)     | Fastest insert
//  Insert at Tail         | O(N)        | O(1)     | Walk to end
//  Insert at Position k   | O(k)        | O(1)     |
//  Delete Head            | O(1)        | O(1)     |
//  Delete Tail            | O(N)        | O(1)     |
//  Search                 | O(N)        | O(1)     |
//  Find Middle            | O(N)        | O(1)     | Slow-fast pointer
//  Reverse (iterative)    | O(N)        | O(1)     | Preferred
//  Reverse (recursive)    | O(N)        | O(N)     | Stack space
//  Detect Cycle           | O(N)        | O(1)     | Floyd's algorithm
//  Find Cycle Start       | O(N)        | O(1)     | Floyd's + math
//  Palindrome             | O(N)        | O(1)     | Reverse half
//  Remove Nth from End    | O(N)        | O(1)     | Two-pointer gap
//  Delete Middle          | O(N)        | O(1)     |
//  Merge Two Sorted       | O(N+M)      | O(1)     | Dummy head
//  Sort (Merge Sort)      | O(N log N)  | O(log N) | Recursion stack
//  Sort 0s/1s/2s          | O(N)        | O(1)     | Dutch flag for LL
//  Odd-Even Index         | O(N)        | O(1)     |
//  Intersection Node      | O(N+M)      | O(1)     | Length diff trick
//  Add 1 to LL            | O(N)        | O(N)     | Recursive carry
//  Add Two Numbers        | O(max(N,M)) | O(max)   |


// ============================================================
// PRACTICE PROBLEMS & APPLICATIONS
// ============================================================
//
//  EASY
//  1. Reverse Linked List (LeetCode 206)
//     → Classic prev/curr/next iterative reversal
//  2. Middle of the Linked List (LeetCode 876)
//     → Slow-fast pointer; second mid for even-length
//  3. Linked List Cycle (LeetCode 141)
//     → Floyd's: fast catches slow ↔ cycle exists
//  4. Merge Two Sorted Lists (LeetCode 21)
//     → Dummy head + two-pointer comparison
//  5. Palindrome Linked List (LeetCode 234)
//     → Find mid, reverse second half, compare
//
//  MEDIUM
//  6. Remove Nth Node From End (LeetCode 19)
//     → Two-pointer with N-step gap between fast and slow
//  7. Odd Even Linked List (LeetCode 328)
//     → Maintain two chains, connect at end
//  8. Linked List Cycle II — Find start (LeetCode 142)
//     → Floyd's detection + mathematical reset proof
//  9. Sort List (LeetCode 148)
//     → Merge sort on LL; O(N log N) time, O(1) extra space
// 10. Delete the Middle Node (LeetCode 2095)
//     → Slow-fast with fast starting 2 ahead
// 11. Add Two Numbers (LeetCode 2)
//     → Digit-by-digit addition with carry propagation
// 12. Intersection of Two Linked Lists (LeetCode 160)
//     → Align by length difference, walk together
//
//  HARD
// 13. Reverse Nodes in k-Group (LeetCode 25)
//     → Reverse k nodes at a time; recurse for the rest
// 14. Merge k Sorted Lists (LeetCode 23)
//     → Divide and conquer using mergeTwoSortedLists; O(N log k)
// 15. Copy List with Random Pointer (LeetCode 138)
//     → Interleave cloned nodes, set random pointers, un-interleave
// 16. LRU Cache (LeetCode 146)
//     → Doubly linked list + hash map for O(1) get/put


// ============================================================
// KEY PATTERNS & VARIATIONS FOR REVISION
// ============================================================
//
//  PATTERN 1 — Slow-Fast Pointer (Tortoise & Hare):
//    Fast=2 steps, Slow=1 step per iteration.
//    Uses:
//      a) Middle of LL      → slow at middle when fast hits end
//      b) Cycle detection   → they meet inside cycle
//      c) Cycle start       → reset slow to head after meeting point
//      d) Remove Nth        → use gap instead of speed difference
//
//  PATTERN 2 — Dummy Head Node (Sentinel):
//    Create Node(-1) before the real head.
//    Eliminates "is head null?" checks at insertion point.
//    Used in: mergeTwoSortedLists, addTwoNumbers, sortZeroOneTwoList.
//
//  PATTERN 3 — In-Place Reversal Triad (prev / curr / next):
//    save next → reverse link → advance prev and curr.
//    Essential for: reverseIterative, palindrome, k-group reverse.
//
//  PATTERN 4 — Length Difference (Intersection):
//    Equalize starting positions by skipping |lenA − lenB| nodes.
//    Walk both together until they point to the SAME node (===).
//
//  PATTERN 5 — Divide & Conquer on LL (Merge Sort):
//    Find middle → cut → sort each half → merge.
//    Preferred over Quick Sort for LL (no random access).
//    Always O(N log N), stable, O(1) extra space.
//
//  PATTERN 6 — Grouping with Dummy Heads:
//    Create a dummy head per group (0s/1s/2s or odd/even index).
//    Append nodes, then connect groups.
//    Always terminate the LAST group with null.


// ============================================================
// IMPORTANT TIPS & EDGE CASES
// ============================================================
//
//  1. Always check for null before accessing ->next:
//     $fast->next->next crashes if $fast or $fast->next is null.
//     Safe guard: while ($fast !== null && $fast->next !== null)
//
//  2. Cycle problems — use === for identity comparison:
//     $slow === $fast checks the same OBJECT (same memory address).
//     == checks structural equality → can cause infinite loops in PHP
//     when two different nodes happen to have the same data value.
//
//  3. Even vs Odd length in Middle/Palindrome:
//     while ($fast->next !== null && $fast->next->next !== null)
//       → stops at FIRST middle (for even-length lists)
//     while ($fast !== null && $fast->next !== null)
//       → stops at SECOND middle (for even-length lists)
//     Know which variant a problem requires!
//
//  4. Palindrome: use && NOT || in fast-pointer condition:
//     || would attempt $fast->next when $fast is already null → crash.
//
//  5. Add Two Numbers: numbers are stored in REVERSE order.
//     l1=2→4→3 represents 342, NOT 243.
//     No reversal needed; process from head (ones digit) directly.
//
//  6. sortZeroOneTwoList — MUST set $c2->next = null:
//     Without it, the last node in the 2s group retains its old
//     ->next pointer from the original list, potentially creating a cycle.
//
//  7. PHP pass-by-value for objects:
//     PHP passes object handles by value. Inside a method, reassigning
//     $node = new Node() does NOT update the caller's variable.
//     Use return value or pass &$ref when the caller's pointer must change.
//
//  8. Linked list vs array trade-offs:
//     Insert/Delete at head: LL O(1) vs array O(N) — LL wins.
//     Random access (get k-th): LL O(N) vs array O(1) — array wins.
//     LL is preferred when frequent insertions/deletions are needed
//     and random access is not.

?>
