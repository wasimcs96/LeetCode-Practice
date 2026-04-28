<?php

// ============================================================
// DOUBLY LINKED LIST — Complete Revision Guide
// Topics : Node Setup & Bi-Directional Links
//          Traversal (Forward & Backward)
//          Convert Array → DLL
//          Insertion : Head | Tail | K-th Position | Before/After Value
//          Deletion  : Head | Tail | K-th Position | By Value | All Occurrences
//          Reverse DLL (In-Place Pointer Swap)
//          Remove Duplicates from Sorted DLL
//          Find All Pairs with Given Sum in Sorted DLL (Two-Pointer O(N))
// ============================================================
// Key advantage over Singly Linked List:
//   Every node stores a PREV pointer → O(1) deletion when you
//   already HAVE the node reference (no separate "previous" tracking).
//   Also enables O(N) backward traversal without reversing first.
// ============================================================


// ============================================================
// NODE CLASS
// ============================================================

class Node
{
    public int   $data;
    public ?Node $prev;   // Points to the node BEFORE this one
    public ?Node $next;   // Points to the node AFTER  this one

    public function __construct(int $data)
    {
        $this->data = $data;
        $this->prev = null;
        $this->next = null;
    }
}


// ============================================================
// DOUBLY LINKED LIST CLASS
// ============================================================

class DLL
{
    public ?Node $head;

    public function __construct()
    {
        $this->head = null;
    }

    // ----------------------------------------------------------
    // HELPER — Print list forward (head → tail)
    // TC: O(N)  SC: O(1)
    // ----------------------------------------------------------
    public function printForward(): void
    {
        $parts   = [];
        $current = $this->head;
        while ($current !== null) {
            $parts[] = (string)$current->data;
            $current = $current->next;
        }
        echo "NULL ↔ " . implode(" ↔ ", $parts) . " ↔ NULL\n";
    }

    // ----------------------------------------------------------
    // HELPER — Print list backward (tail → head)
    //   Uses prev pointers — unique advantage of DLL.
    // TC: O(N)  SC: O(1)
    // ----------------------------------------------------------
    public function printBackward(): void
    {
        if ($this->head === null) { echo "NULL\n"; return; }

        $current = $this->head;
        while ($current->next !== null) {
            $current = $current->next;      // Walk forward to the tail
        }

        $parts = [];
        while ($current !== null) {
            $parts[] = (string)$current->data;
            $current = $current->prev;      // Walk BACKWARD using prev pointers
        }
        echo "NULL ↔ " . implode(" ↔ ", $parts) . " ↔ NULL\n";
    }


    // ==========================================================
    // CREATION
    // ==========================================================

    // ----------------------------------------------------------
    // 1. CONVERT ARRAY TO DLL
    // Intuition:
    //   Create head from arr[0]. For each subsequent element:
    //     1. Create new node.
    //     2. new->prev = currentTail   (new looks BACKWARD to tail)
    //     3. currentTail->next = new   (tail looks FORWARD to new)
    //     4. Advance tail pointer.
    //
    // Dry Run: arr=[1,2,3]
    //   head=Node(1), tail=Node(1)
    //   i=1: Node(2)->prev=1,  Node(1)->next=2,  tail=Node(2)
    //   i=2: Node(3)->prev=2,  Node(2)->next=3,  tail=Node(3)
    //   Result: NULL ↔ 1 ↔ 2 ↔ 3 ↔ NULL  ✓
    //
    // TC: O(N)  SC: O(N) — N new nodes created
    // ----------------------------------------------------------
    public function fromArray(array $arr): void
    {
        if (empty($arr)) return;

        $this->head = new Node($arr[0]);
        $current    = $this->head;

        for ($i = 1; $i < count($arr); $i++) {
            $newNode       = new Node($arr[$i]);
            $newNode->prev = $current;  // New node looks BACKWARD to current tail
            $current->next = $newNode;  // Current tail looks FORWARD to new node
            $current       = $newNode;  // Advance tail pointer
        }
    }


    // ==========================================================
    // TRAVERSAL & SEARCH
    // ==========================================================

    // ----------------------------------------------------------
    // Get list length
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
    // INSERTION
    // ==========================================================

    // ----------------------------------------------------------
    // 2. INSERT AT HEAD
    // Intuition:
    //   newNode->next = old head     (new points FORWARD to old head)
    //   old head->prev = newNode     (old head points BACK  to new)
    //   head = newNode
    //
    // Dry Run: list=1 ↔ 2 ↔ 3, data=0
    //   Node(0)->next=Node(1), Node(1)->prev=Node(0), head=Node(0)
    //   Result: NULL ↔ 0 ↔ 1 ↔ 2 ↔ 3 ↔ NULL  ✓
    //
    // TC: O(1)  SC: O(1)
    // ----------------------------------------------------------
    public function insertAtHead(int $data): void
    {
        $newNode = new Node($data);

        if ($this->head !== null) {
            $newNode->next    = $this->head; // New → old head
            $this->head->prev = $newNode;    // Old head ← new
        }

        $this->head = $newNode;
    }

    // ----------------------------------------------------------
    // 3. INSERT AT TAIL
    // Intuition:
    //   Walk to the last node (last->next == null).
    //   last->next = new,  new->prev = last.
    //
    // Dry Run: list=1 ↔ 2 ↔ 3, data=4
    //   Walk to Node(3) (last: 3->next==null)
    //   Node(3)->next=Node(4), Node(4)->prev=Node(3)
    //   Result: NULL ↔ 1 ↔ 2 ↔ 3 ↔ 4 ↔ NULL  ✓
    //
    // TC: O(N)  SC: O(1)
    // ----------------------------------------------------------
    public function insertAtTail(int $data): void
    {
        $newNode = new Node($data);

        if ($this->head === null) {
            $this->head = $newNode;
            return;
        }

        $current = $this->head;
        while ($current->next !== null) {
            $current = $current->next;
        }
        $current->next = $newNode; // Last node → new node
        $newNode->prev = $current; // New node ← last node
    }

    // ----------------------------------------------------------
    // 4. INSERT AT Kth POSITION (1-indexed)
    // Intuition:
    //   k=1       → insertAtHead.
    //   k=len+1   → insertAtTail.
    //   Otherwise : walk to (k-1)-th node, splice new between
    //               (k-1)-th and k-th.
    //   Splice:
    //     new->prev = (k-1)th     new->next = (k)th
    //     (k-1)th->next = new     (k)th->prev = new
    //
    // Dry Run: list=1 ↔ 2 ↔ 3 ↔ 4, k=3, data=99
    //   Walk to position 2 → Node(2)
    //   prevNode=Node(2), nextNode=Node(3)
    //   Node(99): prev=2, next=3 | 2->next=99 | 3->prev=99
    //   Result: NULL ↔ 1 ↔ 2 ↔ 99 ↔ 3 ↔ 4 ↔ NULL  ✓
    //
    // TC: O(N)  SC: O(1)
    // ----------------------------------------------------------
    public function insertAtPosition(int $data, int $k): void
    {
        $length = $this->getLength();
        if ($k < 1 || $k > $length + 1) return;

        if ($k === 1)           { $this->insertAtHead($data); return; }
        if ($k === $length + 1) { $this->insertAtTail($data); return; }

        $counter = 1;
        $current = $this->head;
        while ($counter < $k - 1) {          // Walk to (k-1)-th node
            $current = $current->next;
            $counter++;
        }

        $newNode       = new Node($data);
        $nextNode      = $current->next;      // Old k-th node

        $newNode->prev  = $current;           // new ← (k-1)th
        $newNode->next  = $nextNode;          // new → old k-th
        $current->next  = $newNode;           // (k-1)th → new
        $nextNode->prev = $newNode;           // old k-th ← new
    }

    // ----------------------------------------------------------
    // 5. INSERT BEFORE first node with given VALUE
    // Intuition:
    //   Walk until data == value.
    //   Splice new between current->prev and current.
    //
    // Dry Run: list=1 ↔ 2 ↔ 3 ↔ 4, value=3, data=99
    //   Walk to Node(3): data==3 ✓
    //   prev=Node(2); Node(99): prev=2, next=3 | 2->next=99 | 3->prev=99
    //   Result: NULL ↔ 1 ↔ 2 ↔ 99 ↔ 3 ↔ 4 ↔ NULL  ✓
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
        while ($current !== null) {
            if ($current->data === $value) {
                $newNode            = new Node($data);
                $prevNode           = $current->prev;
                $newNode->prev      = $prevNode;   // new ← prevNode
                $newNode->next      = $current;    // new → current
                $prevNode->next     = $newNode;    // prevNode → new
                $current->prev      = $newNode;    // current ← new
                return;
            }
            $current = $current->next;
        }
    }

    // ----------------------------------------------------------
    // 6. INSERT AFTER first node with given VALUE
    // Intuition:
    //   Walk until data == value.
    //   Splice new between current and current->next.
    //
    // Dry Run: list=1 ↔ 2 ↔ 3 ↔ 4, value=2, data=99
    //   Walk to Node(2): data==2 ✓
    //   next=Node(3); Node(99): prev=2, next=3 | 2->next=99 | 3->prev=99
    //   Result: NULL ↔ 1 ↔ 2 ↔ 99 ↔ 3 ↔ 4 ↔ NULL  ✓
    //
    // TC: O(N)  SC: O(1)
    // ----------------------------------------------------------
    public function insertAfterValue(int $data, int $value): void
    {
        if ($this->head === null) return;

        $current = $this->head;
        while ($current !== null) {
            if ($current->data === $value) {
                $newNode       = new Node($data);
                $nextNode      = $current->next;
                $newNode->prev = $current;          // new ← current
                $newNode->next = $nextNode;          // new → nextNode
                $current->next = $newNode;           // current → new
                if ($nextNode !== null) {
                    $nextNode->prev = $newNode;      // nextNode ← new
                }
                return;
            }
            $current = $current->next;
        }
    }


    // ==========================================================
    // DELETION
    // ==========================================================

    // ----------------------------------------------------------
    // 7. DELETE HEAD
    // Intuition:
    //   Move head forward by one. Clear prev of the new head
    //   (so it no longer points back to the deleted node).
    //
    // Dry Run: list=1 ↔ 2 ↔ 3
    //   head = Node(2), Node(2)->prev = null
    //   Result: NULL ↔ 2 ↔ 3 ↔ NULL  ✓
    //
    // TC: O(1)  SC: O(1)
    // ----------------------------------------------------------
    public function deleteHead(): void
    {
        if ($this->head === null) return;

        $this->head = $this->head->next;        // Move head forward

        if ($this->head !== null) {
            $this->head->prev = null;           // Clear backward link to deleted node
        }
    }

    // ----------------------------------------------------------
    // 8. DELETE TAIL
    // Intuition:
    //   Walk to the tail node. Use tail->prev to reach second-to-last.
    //   Set second-to-last->next = null.
    //   (DLL advantage: tail already knows its predecessor!)
    //
    // Dry Run: list=1 ↔ 2 ↔ 3 ↔ 4
    //   Walk to Node(4) (tail: 4->next==null)
    //   Node(4)->prev = Node(3) → Node(3)->next = null
    //   Result: NULL ↔ 1 ↔ 2 ↔ 3 ↔ NULL  ✓
    //
    // TC: O(N)  SC: O(1)
    // ----------------------------------------------------------
    public function deleteTail(): void
    {
        if ($this->head === null) return;

        if ($this->head->next === null) {
            $this->head = null;                 // Single-node list
            return;
        }

        $current = $this->head;
        while ($current->next !== null) {
            $current = $current->next;          // Walk to tail
        }
        $current->prev->next = null;            // Second-to-last stops pointing at tail
    }

    // ----------------------------------------------------------
    // 9. DELETE AT POSITION k (1-indexed)
    // Intuition:
    //   Walk to the k-th node. Bypass it using its OWN prev and next.
    //   DLL advantage: the node itself knows its predecessor —
    //   no separate "previous" variable needed.
    //
    // Dry Run: list=1 ↔ 2 ↔ 3 ↔ 4 ↔ 5, k=3
    //   Walk to Node(3) (k=3)
    //   Node(2)->next = Node(4), Node(4)->prev = Node(2)
    //   Result: NULL ↔ 1 ↔ 2 ↔ 4 ↔ 5 ↔ NULL  ✓
    //
    // TC: O(N)  SC: O(1)
    // ----------------------------------------------------------
    public function deleteAtPosition(int $k): void
    {
        $length = $this->getLength();
        if ($k < 1 || $k > $length) return;

        if ($k === 1)       { $this->deleteHead(); return; }
        if ($k === $length) { $this->deleteTail(); return; }

        $counter = 1;
        $current = $this->head;
        while ($counter < $k) {
            $current = $current->next;
            $counter++;
        }
        // current is now the k-th node to delete
        $current->prev->next = $current->next; // prev → next (skip current)
        $current->next->prev = $current->prev; // prev ← next (skip current)
    }

    // ----------------------------------------------------------
    // 10. DELETE BY VALUE (first occurrence)
    // Intuition:
    //   Walk until data matches.
    //   Bypass node using its own prev and next pointers.
    //
    // Dry Run: list=1 ↔ 2 ↔ 3 ↔ 4, value=3
    //   Walk to Node(3): data==3 ✓
    //   Node(2)->next=Node(4), Node(4)->prev=Node(2)
    //   Result: NULL ↔ 1 ↔ 2 ↔ 4 ↔ NULL  ✓
    //
    // TC: O(N)  SC: O(1)
    // ----------------------------------------------------------
    public function deleteByValue(int $value): void
    {
        $current = $this->head;
        while ($current !== null) {
            if ($current->data === $value) {
                if ($current === $this->head) {
                    // Deleting head: move head forward
                    $this->head = $current->next;
                    if ($this->head !== null) $this->head->prev = null;
                } else {
                    $current->prev->next = $current->next;          // prev → next
                    if ($current->next !== null) {
                        $current->next->prev = $current->prev;      // prev ← next
                    }
                }
                return; // Delete first occurrence only
            }
            $current = $current->next;
        }
    }

    // ----------------------------------------------------------
    // 11. DELETE ALL OCCURRENCES OF A VALUE
    // Intuition:
    //   Traverse the entire list. For every match:
    //     - Save current->next BEFORE deleting (it's lost after bypass).
    //     - Bypass the node using its own prev/next.
    //     - If it's the head, update head pointer.
    //   Use the saved next to continue traversal.
    //
    // Dry Run: list=1 ↔ 2 ↔ 2 ↔ 3 ↔ 2, value=2
    //   curr=Node(2,p2): save next=Node(2,p3). Node(1)->next=p3, p3->prev=1. curr=p3
    //   curr=Node(2,p3): save next=Node(3).   Node(1)->next=3,  3->prev=1.   curr=3
    //   curr=Node(3):    no match.             curr=Node(2,p5)
    //   curr=Node(2,p5): save next=null. Node(3)->next=null. curr=null. STOP.
    //   Result: NULL ↔ 1 ↔ 3 ↔ NULL  ✓
    //
    // TC: O(N)  SC: O(1)
    // ----------------------------------------------------------
    public function deleteAllOccurrences(int $value): void
    {
        $current = $this->head;

        while ($current !== null) {
            $nextNode = $current->next;         // Save BEFORE possibly deleting

            if ($current->data === $value) {
                if ($current === $this->head) {
                    // Update head and clear backward link
                    $this->head = $nextNode;
                    if ($nextNode !== null) $nextNode->prev = null;
                } else {
                    $current->prev->next = $nextNode;
                    if ($nextNode !== null) $nextNode->prev = $current->prev;
                }
            }

            $current = $nextNode;               // Always use saved next to advance
        }
    }


    // ==========================================================
    // CORE ALGORITHMS
    // ==========================================================

    // ----------------------------------------------------------
    // 12. REVERSE A DOUBLY LINKED LIST (In-Place Pointer Swap)
    // Intuition:
    //   For each node: SWAP its prev and next pointers.
    //   After the swap, current->prev holds the ORIGINAL next.
    //   Advance: current = current->prev  (moves to original next node).
    //   When current->prev becomes null after swap
    //     → original next was null → we're at the original tail → new head.
    //
    // Dry Run: list=1 ↔ 2 ↔ 3
    //   curr=Node(1) [prev=null,next=2]: swap → prev=2,next=null. curr=Node(2)
    //   curr=Node(2) [prev=1,  next=3]: swap → prev=3,next=1.    curr=Node(3)
    //   curr=Node(3) [prev=2,  next=null]: swap → prev=null,next=2.
    //                                      prev==null → head=Node(3). curr=null. STOP.
    //   Final: 3->next=2, 2->prev=3, 2->next=1, 1->prev=2, 1->next=null
    //   Result: NULL ↔ 3 ↔ 2 ↔ 1 ↔ NULL  ✓
    //
    // TC: O(N)  SC: O(1)
    // ----------------------------------------------------------
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


    // ----------------------------------------------------------
    // 13. REMOVE DUPLICATES FROM SORTED DLL
    // Intuition:
    //   In a SORTED list, all duplicates are adjacent.
    //   At each node: if current->next has the same data,
    //     use a runner to skip ALL consecutive duplicates.
    //     Link current directly to the first non-duplicate node.
    //   Don't advance current after removing — the new current->next
    //   might also be a duplicate of current.
    //
    // Dry Run: list=1 ↔ 1 ↔ 2 ↔ 3 ↔ 3 ↔ 3 ↔ 4
    //   curr=1: next->data==1 (dup). runner: 1→1→2 (stop). 1->next=2, 2->prev=1.
    //   curr=1: next->data==2 (ok). advance → curr=Node(2).
    //   curr=2: next->data==3 (ok). advance → curr=Node(3).
    //   curr=3: next->data==3 (dup). runner: 3→3→4 (stop). 3->next=4, 4->prev=3.
    //   curr=3: next->data==4 (ok). advance → curr=Node(4).
    //   curr=4: next==null. STOP.
    //   Result: NULL ↔ 1 ↔ 2 ↔ 3 ↔ 4 ↔ NULL  ✓
    //
    // TC: O(N)  SC: O(1)
    // ----------------------------------------------------------
    public function removeDuplicatesSorted(): void
    {
        if ($this->head === null) return;

        $current = $this->head;

        while ($current !== null && $current->next !== null) {
            if ($current->data === $current->next->data) {
                // Find the first node AFTER all consecutive duplicates
                $runner = $current->next;
                while ($runner !== null && $runner->data === $current->data) {
                    $runner = $runner->next;
                }
                // Bypass all duplicates in one shot
                $current->next = $runner;
                if ($runner !== null) $runner->prev = $current;
                // Do NOT advance current — new next might still be a dup
            } else {
                $current = $current->next;  // No duplicate, safe to advance
            }
        }
    }


    // ----------------------------------------------------------
    // 14. FIND ALL PAIRS WITH GIVEN SUM IN SORTED DLL (Two-Pointer)
    // Intuition:
    //   SORTED list → two-pointer (same as two-sum on sorted array):
    //     $low  starts at HEAD (smallest element).
    //     $high starts at TAIL (largest element).
    //   Each iteration:
    //     total == sum → found pair; shrink window from both sides.
    //     total <  sum → need a larger total → move low  right (+).
    //     total >  sum → need a smaller total → move high left  (−).
    //   Stop when $low === $high (pointers meet) or they cross
    //   ($low->prev === $high means low passed high by one step).
    //
    // Dry Run: list=1 ↔ 2 ↔ 3 ↔ 4 ↔ 5 ↔ 6 ↔ 7, sum=7
    //   low=1, high=7: 1+7=8 > 7 → high=6
    //   low=1, high=6: 1+6=7     → pair(1,6)! low=2, high=5
    //   low=2, high=5: 2+5=7     → pair(2,5)! low=3, high=4
    //   low=3, high=4: 3+4=7     → pair(3,4)! low=4, high=3 → crossed STOP
    //   Pairs: [[1,6],[2,5],[3,4]]  ✓
    //
    // TC: O(N)  SC: O(1) — not counting the result array output
    // ----------------------------------------------------------
    public function findPairsWithSum(int $sum): array
    {
        $result = [];
        if ($this->head === null) return $result;

        // Walk to tail → $high pointer
        $high = $this->head;
        while ($high->next !== null) {
            $high = $high->next;
        }

        $low = $this->head;

        // Continue while pointers haven't met ($low !== $high) and
        // low hasn't passed high ($low->prev !== $high)
        while ($low !== $high && $low->prev !== $high) {
            $total = $low->data + $high->data;

            if ($total === $sum) {
                $result[] = [$low->data, $high->data];  // Found a valid pair
                $low      = $low->next;                 // Shrink window from left
                $high     = $high->prev;                // Shrink window from right
            } elseif ($total < $sum) {
                $low  = $low->next;     // Total too small → move low right
            } else {
                $high = $high->prev;    // Total too large → move high left
            }
        }

        return $result;
    }
}


// ============================================================
// DEMO — Run All Operations
// ============================================================

echo "=== fromArray + printForward ===\n";
$dll = new DLL();
$dll->fromArray([1, 2, 3, 4, 5]);
$dll->printForward();                          // NULL ↔ 1 ↔ 2 ↔ 3 ↔ 4 ↔ 5 ↔ NULL

echo "\n=== printBackward (confirms prev links) ===\n";
$dll->printBackward();                         // NULL ↔ 5 ↔ 4 ↔ 3 ↔ 2 ↔ 1 ↔ NULL

echo "\n=== Insertion ===\n";
$dll->fromArray([1, 2, 3, 4, 5]);
$dll->insertAtHead(0);
$dll->printForward();                          // NULL ↔ 0 ↔ 1 ↔ 2 ↔ 3 ↔ 4 ↔ 5 ↔ NULL
$dll->insertAtTail(6);
$dll->printForward();                          // NULL ↔ 0 ↔ 1 ↔ 2 ↔ 3 ↔ 4 ↔ 5 ↔ 6 ↔ NULL
$dll->insertAtPosition(99, 3);
$dll->printForward();                          // NULL ↔ 0 ↔ 1 ↔ 99 ↔ 2 ↔ 3 ↔ 4 ↔ 5 ↔ 6 ↔ NULL
$dll->insertBeforeValue(88, 4);
$dll->printForward();                          // NULL ↔ 0 ↔ 1 ↔ 99 ↔ 2 ↔ 3 ↔ 88 ↔ 4 ↔ 5 ↔ 6 ↔ NULL
$dll->insertAfterValue(77, 4);
$dll->printForward();                          // NULL ↔ 0 ↔ 1 ↔ 99 ↔ 2 ↔ 3 ↔ 88 ↔ 4 ↔ 77 ↔ 5 ↔ 6 ↔ NULL

echo "\n=== Deletion ===\n";
$dll->fromArray([1, 2, 3, 4, 5]);
$dll->deleteHead();
$dll->printForward();                          // NULL ↔ 2 ↔ 3 ↔ 4 ↔ 5 ↔ NULL
$dll->deleteTail();
$dll->printForward();                          // NULL ↔ 2 ↔ 3 ↔ 4 ↔ NULL
$dll->fromArray([1, 2, 3, 4, 5]);
$dll->deleteAtPosition(3);
$dll->printForward();                          // NULL ↔ 1 ↔ 2 ↔ 4 ↔ 5 ↔ NULL
$dll->fromArray([1, 2, 3, 4, 5]);
$dll->deleteByValue(3);
$dll->printForward();                          // NULL ↔ 1 ↔ 2 ↔ 4 ↔ 5 ↔ NULL
$dll->fromArray([1, 2, 2, 3, 2]);
$dll->deleteAllOccurrences(2);
$dll->printForward();                          // NULL ↔ 1 ↔ 3 ↔ NULL

echo "\n=== Traversal ===\n";
$dll->fromArray([1, 2, 3, 4, 5]);
echo "Length:   " . $dll->getLength() . "\n";                       // 5
echo "Search 3: " . ($dll->search(3) ? 'true' : 'false') . "\n";   // true
echo "Search 9: " . ($dll->search(9) ? 'true' : 'false') . "\n";   // false

echo "\n=== Reverse DLL ===\n";
$dll->fromArray([1, 2, 3, 4, 5]);
$dll->reverse();
$dll->printForward();                          // NULL ↔ 5 ↔ 4 ↔ 3 ↔ 2 ↔ 1 ↔ NULL
$dll->printBackward();                         // NULL ↔ 1 ↔ 2 ↔ 3 ↔ 4 ↔ 5 ↔ NULL (prev links intact)

echo "\n=== Remove Duplicates (Sorted) ===\n";
$dll->fromArray([1, 1, 2, 3, 3, 3, 4]);
$dll->removeDuplicatesSorted();
$dll->printForward();                          // NULL ↔ 1 ↔ 2 ↔ 3 ↔ 4 ↔ NULL

echo "\n=== Find Pairs with Sum (Sorted) ===\n";
$dll->fromArray([1, 2, 3, 4, 5, 6, 7]);
$pairs = $dll->findPairsWithSum(7);
foreach ($pairs as $pair) {
    echo "  ({$pair[0]}, {$pair[1]})\n";       // (1, 6), (2, 5), (3, 4)
}

echo "\n=== Edge Cases ===\n";
// Single-node list reverse
$dll->fromArray([42]);
$dll->reverse();
$dll->printForward();                          // NULL ↔ 42 ↔ NULL

// All duplicates
$dll->fromArray([5, 5, 5, 5]);
$dll->removeDuplicatesSorted();
$dll->printForward();                          // NULL ↔ 5 ↔ NULL

// No pair found
$dll->fromArray([1, 2, 3]);
$pairs = $dll->findPairsWithSum(10);
echo "Pairs sum=10: " . (empty($pairs) ? 'none' : '') . "\n"; // none


// ============================================================
// COMPARISON SUMMARY
// ============================================================
//
//  Operation                    | TC           | SC      | Notes
// ------------------------------+--------------+---------+--------------------
//  Insert at Head               | O(1)         | O(1)    | Fastest insert
//  Insert at Tail               | O(N)         | O(1)    | Walk to end
//  Insert at Position k         | O(k)         | O(1)    |
//  Insert Before/After Value    | O(N)         | O(1)    | Walk until match
//  Delete Head                  | O(1)         | O(1)    | Use prev link
//  Delete Tail                  | O(N)         | O(1)    | Walk to tail
//  Delete at Position k         | O(k)         | O(1)    | Node knows its prev
//  Delete by Value              | O(N)         | O(1)    | No separate prev var
//  Delete All Occurrences       | O(N)         | O(1)    | Save next before del
//  Search / getLength           | O(N)         | O(1)    |
//  Reverse (in-place swap)      | O(N)         | O(1)    | No extra space
//  Remove Duplicates (sorted)   | O(N)         | O(1)    | Runner pointer
//  Find Pairs with Sum (sorted) | O(N)         | O(1)    | Two-pointer O(N)
// ------------------------------+--------------+---------+--------------------
//  DLL vs SLL: deletion         | O(1)*        | —       | *when node ref known
//  DLL vs SLL: backward walk    | O(N) with DLL| —       | O(N) extra w/ SLL


// ============================================================
// PRACTICE PROBLEMS & APPLICATIONS
// ============================================================
//
//  EASY
//  1. Reverse a Doubly Linked List
//     → In-place pointer swap on each node; O(N) time, O(1) space
//  2. Delete a Given Node in DLL (when given the node directly)
//     → DLL advantage: bypass using node->prev and node->next; O(1)
//  3. Convert Array to DLL and back
//     → Build fromArray(); collect data with printForward()
//  4. Insert at Head / Tail in DLL
//     → Standard warm-up; reinforce prev-link setup
//  5. Remove Duplicates from Sorted DLL
//     → Adjacent-duplicate removal; runner pointer pattern
//
//  MEDIUM
//  6. Find Pairs with Given Sum in Sorted DLL
//     → Two-pointer from head and tail; O(N) time, O(1) space
//     (Same pattern as Two-Sum on sorted array — LeetCode 167)
//  7. Sort a Doubly Linked List
//     → Merge sort adapted for DLL (maintain prev links in merge step)
//  8. Flatten a Multilevel Doubly Linked List (LeetCode 430)
//     → Recursion or stack to flatten child lists into the main list
//  9. Design a Browser History (LeetCode 1472)
//     → DLL where curr node is current page; back/forward use prev/next
// 10. LRU Cache (LeetCode 146)
//     → DLL (most-recently-used order) + HashMap (O(1) lookup)
//     → Most important DLL application in interviews!
// 11. All Unique Triplets with Given Sum in Sorted DLL
//     → Fix one pointer, run two-pointer on the rest; O(N²)
//
//  HARD
// 12. LFU Cache (LeetCode 460)
//     → Extend LRU; DLL per frequency bucket + two HashMaps
// 13. Merge k Sorted DLLs
//     → Divide and conquer using merge-two-sorted-DLLs; O(N log k)
// 14. Design a Text Editor (LeetCode 2296)
//     → DLL of characters with cursor; add/delete/move operations


// ============================================================
// KEY PATTERNS & VARIATIONS FOR REVISION
// ============================================================
//
//  PATTERN 1 — Always Link BOTH Directions on Insert:
//    Every splice needs FOUR pointer updates:
//      new->prev = prevNode       new->next = nextNode
//      prevNode->next = new       nextNode->prev = new
//    Missing any one link corrupts backward traversal.
//
//  PATTERN 2 — Save Next BEFORE Deleting (deleteAllOccurrences):
//    When deleting a node inside a loop, $current->next becomes
//    inaccessible after bypass. Always save:
//      $nextNode = $current->next;
//    before modifying pointers, then advance with $current = $nextNode.
//
//  PATTERN 3 — In-Place Reversal by Pointer Swap:
//    Instead of a separate reverse pass, SWAP prev↔next for each node.
//    Advance using the post-swap prev (which holds original next).
//    Last processed node (post-swap prev == null) becomes new head.
//
//  PATTERN 4 — Two-Pointer on Sorted DLL (Pairs / Triplets):
//    Low = head (min), High = tail (max).
//    sum < target → low++  (need bigger)
//    sum > target → high-- (need smaller)
//    sum == target → record pair, shrink both.
//    Stop: low === high OR low->prev === high (crossed).
//    Works identically to two-sum on sorted array.
//
//  PATTERN 5 — Runner Pointer for Sorted Duplicates:
//    When current->next has the same value, run a separate pointer
//    forward until value changes, then link current directly to it.
//    Much cleaner than checking one node at a time.
//
//  PATTERN 6 — DLL as O(1) Delete Structure (LRU/LFU):
//    If you store a HashMap<key → Node>, you can reach any node in O(1).
//    Then use DLL to delete/move it in O(1) using its own prev/next.
//    This is the core insight behind LRU Cache — the most asked
//    DLL problem in FAANG interviews.


// ============================================================
// IMPORTANT TIPS & EDGE CASES
// ============================================================
//
//  1. Four-pointer rule for splice operations:
//     Every insert between two existing nodes requires updating
//     FOUR pointers (prev/next on both the new node and its neighbours).
//     Updating only 2 or 3 is the most common DLL bug.
//
//  2. Head deletion clears prev of new head:
//     After head = head->next, always set head->prev = null.
//     Otherwise the new head still points back to the freed node.
//
//  3. Single-node edge case:
//     Reverse, deleteTail, deleteDuplicates — all must handle
//     a list with exactly ONE node without crashing.
//
//  4. Termination in findPairsWithSum:
//     Use identity check $low !== $high AND $low->prev !== $high.
//     The second guard catches the case where low has PASSED high
//     by one step (pointers crossed without meeting at the same node).
//
//  5. removeDuplicatesSorted — do NOT advance after removing:
//     After linking current to the first non-duplicate runner,
//     stay at current — the new current->next might ALSO be a
//     duplicate of current (e.g. list=1→1→1→2→2 needs two passes
//     at the same current node).
//
//  6. Reverse confirms prev links — always print backward after reverse:
//     If prev pointers are wrong, printBackward will show corruption.
//     This is the fastest sanity check for DLL correctness.
//
//  7. DLL vs SLL trade-off:
//     DLL deletion (given node ref) : O(1) vs SLL O(N) — DLL wins.
//     DLL memory per node           : 2 pointers vs 1 — SLL wins.
//     Choose DLL when backward traversal or O(1) deletion is needed.
//
//  8. PHP-specific: use === for node identity comparison:
//     $low === $high checks they are the SAME object in memory.
//     $low == $high would do structural comparison and can give
//     unexpected true when two different nodes have the same data.

?>
