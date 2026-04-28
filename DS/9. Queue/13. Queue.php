<?php

// ============================================================
// QUEUE — Complete Revision Guide
// Topics : Queue using Array (Circular Buffer)
//          Queue using Linked List
//          Implement Queue using Two Stacks  (LeetCode 232)
//          Design Circular Queue             (LeetCode 622)
//          Sliding Window Maximum — Monotonic Deque (LeetCode 239)
// ============================================================
// Core Intuition:
//   Queue = FIFO (First In, First Out) data structure.
//   Think of a checkout line at a supermarket: the person who
//   arrives FIRST gets served FIRST.
//
//   Four fundamental operations:
//     enqueue / push(x) → add x to the REAR (back)   O(1)
//     dequeue / pop()   → remove from the FRONT       O(1)
//     front / peek()    → read FRONT without removing O(1)
//     isEmpty()         → check if queue is empty     O(1)
//
//   When to reach for a Queue:
//     - Level-order traversal of trees / graphs (BFS)
//     - Scheduling: CPU tasks, print jobs, sliding window
//     - When order of arrival must be preserved
//     - Monotonic deque for O(N) sliding-window min/max
// ============================================================


// ============================================================
// 1. QUEUE USING ARRAY (Circular Buffer / Ring Buffer)
// ============================================================
// Intuition:
//   A naive array queue wastes space: after many enqueue/dequeue
//   operations, the FRONT pointer moves right while the beginning
//   of the array sits unused.
//
//   Circular Buffer fix: wrap both pointers using MODULO.
//     rear  = (rear  + 1) % capacity
//     front = (front + 1) % capacity
//   This reuses freed slots at the beginning → no wasted space.
//
//   Internal state:
//     $front   — index of the FIRST (oldest) element
//     $rear    — index of the LAST  (newest) element
//     $size    — number of elements currently in the queue
//     $cap     — fixed capacity (PHP_INT_MAX for unlimited)
//
// Dry Run: capacity=4, push 1,2,3, then pop twice, then push 4,5
//   push(1): front=0 rear=0 arr=[1,_,_,_] size=1
//   push(2): rear=(0+1)%4=1  arr=[1,2,_,_] size=2
//   push(3): rear=(1+1)%4=2  arr=[1,2,3,_] size=3
//   pop():   val=arr[0]=1, front=(0+1)%4=1  size=2
//   pop():   val=arr[1]=2, front=(1+1)%4=2  size=1
//   push(4): rear=(2+1)%4=3  arr=[1,2,3,4] size=2  (indices 2,3)
//   push(5): rear=(3+1)%4=0  arr=[5,2,3,4] size=3  (wraps to slot 0)
//   front()  → arr[2] = 3  ✓  (oldest remaining element)
//
// TC: O(1) per operation  SC: O(N) — fixed-capacity backing array
// ----------------------------------------------------------
class Queue
{
    private array $arr;       // Backing storage
    private int   $front = 0; // Index of the oldest element (FRONT)
    private int   $rear  = -1; // Index of the newest element (REAR)
    private int   $size  = 0;  // Current number of elements
    private int   $cap;        // Maximum capacity

    public function __construct(int $capacity = PHP_INT_MAX)
    {
        $this->cap = $capacity;
        $this->arr = [];
    }

    // Add element to the REAR of the queue
    public function push(int $x): void
    {
        if ($this->isFull()) {
            throw new OverflowException("Queue Overflow");
        }

        $this->rear             = ($this->rear + 1) % $this->cap; // Wrap rear pointer
        $this->arr[$this->rear] = $x;
        $this->size++;
    }

    // Remove and return the FRONT element
    public function pop(): int
    {
        if ($this->isEmpty()) {
            throw new UnderflowException("Queue Underflow");
        }

        $val         = $this->arr[$this->front];
        unset($this->arr[$this->front]);
        $this->front = ($this->front + 1) % $this->cap; // Advance front pointer
        $this->size--;

        // Reset pointers when queue becomes empty (clean state)
        if ($this->size === 0) {
            $this->front = 0;
            $this->rear  = -1;
        }

        return $val;
    }

    // Read the FRONT element without removing it
    public function front(): ?int
    {
        return $this->isEmpty() ? null : $this->arr[$this->front];
    }

    // Read the REAR element without removing it
    public function rear(): ?int
    {
        return $this->isEmpty() ? null : $this->arr[$this->rear];
    }

    public function isEmpty(): bool { return $this->size === 0; }
    public function isFull(): bool  { return $this->size === $this->cap; }
    public function size(): int     { return $this->size; }

    // Print queue from front → rear
    public function printQueue(): void
    {
        if ($this->isEmpty()) { echo "Queue is empty\n"; return; }
        $i     = $this->front;
        $parts = [];
        for ($k = 0; $k < $this->size; $k++) {
            $parts[] = (string)$this->arr[$i];
            $i       = ($i + 1) % $this->cap;
        }
        echo "FRONT → " . implode(' → ', $parts) . " ← REAR\n";
    }
}


// ============================================================
// 2. QUEUE USING LINKED LIST
// ============================================================
// Intuition:
//   Use two pointers: $front (head) and $rear (tail).
//   push: create a new node at the REAR (tail).
//   pop:  remove the FRONT (head) node.
//
//   Advantage over array queue: truly dynamic — no capacity limit
//   and no wasted slots; memory allocated only as needed.
//
// Dry Run: push 10, 20, 30 → pop → front
//   push(10): front=10→null, rear=10
//   push(20): 10→20→null, rear=20
//   push(30): 10→20→30→null, rear=30
//   pop():    front=10, advance front→20. return 10
//   front()   → 20  ✓
//
// TC: O(1) per operation  SC: O(N) — N nodes allocated
// ----------------------------------------------------------
class QueueNode
{
    public int       $data;
    public ?QueueNode $next;

    public function __construct(int $data)
    {
        $this->data = $data;
        $this->next = null;
    }
}

class QueueLL
{
    private ?QueueNode $front; // Points to the OLDEST element (head)
    private ?QueueNode $rear;  // Points to the NEWEST element (tail)
    private int        $size;

    public function __construct()
    {
        $this->front = null;
        $this->rear  = null;
        $this->size  = 0;
    }

    // Enqueue: add new node at the REAR (tail)
    public function push(int $x): void
    {
        $node = new QueueNode($x);
        if ($this->rear === null) {
            // First element: both front and rear point to it
            $this->front = $this->rear = $node;
        } else {
            $this->rear->next = $node; // Chain new node at the end
            $this->rear       = $node; // Move rear pointer forward
        }
        $this->size++;
    }

    // Dequeue: remove the FRONT (head) node
    public function pop(): int
    {
        if ($this->isEmpty()) {
            throw new UnderflowException("Queue Underflow");
        }
        $val         = $this->front->data;
        $this->front = $this->front->next; // Advance front to next node
        if ($this->front === null) {
            $this->rear = null; // Queue is now empty — reset rear too
        }
        $this->size--;
        return $val;
    }

    // Peek front without removing
    public function front(): ?int
    {
        return $this->front?->data;
    }

    public function isEmpty(): bool { return $this->front === null; }
    public function size(): int     { return $this->size; }

    // Print queue from front → rear → NULL
    public function printQueue(): void
    {
        $node  = $this->front;
        $parts = [];
        while ($node !== null) {
            $parts[] = (string)$node->data;
            $node    = $node->next;
        }
        echo "FRONT → " . implode(' → ', $parts) . " → NULL\n";
    }
}


// ============================================================
// 3. IMPLEMENT QUEUE USING TWO STACKS  (LeetCode 232)
// ============================================================
// Intuition:
//   A stack is LIFO; a queue is FIFO.
//   Key insight: reversing a stack gives FIFO order.
//
//   Use TWO stacks:
//     $pushSt  (input  stack) — all new elements pushed here.
//     $popSt   (output stack) — elements transferred here for pop/peek.
//
//   push(x):
//     Always push to $pushSt.  O(1) amortised.
//   pop() / peek():
//     If $popSt is empty → transfer ALL elements from $pushSt to $popSt.
//       This reversal makes the oldest element land on TOP of $popSt.
//     Pop / peek from $popSt.
//
//   Why amortised O(1)?
//     Each element is moved AT MOST ONCE from $pushSt to $popSt.
//     Over N operations the total work is O(N) → O(1) per operation.
//
// Dry Run: push 1,2,3 → pop → peek → push 4 → pop
//   push(1): pushSt=[1], popSt=[]
//   push(2): pushSt=[1,2], popSt=[]
//   push(3): pushSt=[1,2,3], popSt=[]
//   pop():   popSt empty → transfer: pushSt=[] popSt=[3,2,1]
//             pop top of popSt → return 1  (FIFO correct ✓)
//   peek():  popSt=[3,2] top=2 → return 2  ✓
//   push(4): pushSt=[4], popSt=[3,2]
//   pop():   popSt not empty → pop top=2 → return 2  ✓
//
// TC: push O(1) | pop/peek O(1) amortised  SC: O(N)
// ----------------------------------------------------------
class QueueUsingStacks
{
    private array $pushSt = []; // Input stack  — all pushes go here
    private array $popSt  = []; // Output stack — all pops  come from here

    public function push(int $x): void
    {
        $this->pushSt[] = $x; // Always push to the input stack
    }

    // Transfer elements only when the output stack is empty
    private function transfer(): void
    {
        if (empty($this->popSt)) {
            while (!empty($this->pushSt)) {
                $this->popSt[] = array_pop($this->pushSt); // Reverse order → FIFO
            }
        }
    }

    public function pop(): int
    {
        $this->transfer();
        if (empty($this->popSt)) {
            throw new UnderflowException("Queue Underflow");
        }
        return array_pop($this->popSt); // Top of output stack = oldest element
    }

    public function peek(): ?int
    {
        $this->transfer();
        return empty($this->popSt) ? null : end($this->popSt);
    }

    public function isEmpty(): bool
    {
        return empty($this->pushSt) && empty($this->popSt);
    }

    public function size(): int
    {
        return count($this->pushSt) + count($this->popSt);
    }
}


// ============================================================
// 4. DESIGN CIRCULAR QUEUE  (LeetCode 622)
// ============================================================
// Intuition:
//   A Circular Queue (ring buffer) of fixed capacity $k.
//   Reuses freed front slots by wrapping rear with modulo.
//
//   Operations required by LeetCode:
//     enQueue(val) → add to rear; return false if full.
//     deQueue()    → remove from front; return false if empty.
//     Front()      → peek front element; return -1 if empty.
//     Rear()       → peek rear element; return -1 if empty.
//     isEmpty()    → true when size == 0.
//     isFull()     → true when size == k.
//
//   State variables:
//     $arr[0..k-1]  — fixed backing array of size k
//     $head         — index of the FRONT element
//     $count        — current number of elements
//
//   rear index is always derived: ($head + $count - 1) % $k
//   No need for a separate $tail variable!
//
// Dry Run: k=3, enQueue 1,2,3, deQueue, enQueue 4, Rear, isFull
//   enQueue(1): arr=[1,_,_] head=0 count=1  rear=(0+1-1)%3=0 → arr[0]=1
//   enQueue(2): arr=[1,2,_] head=0 count=2  rear=1           → arr[1]=2
//   enQueue(3): arr=[1,2,3] head=0 count=3  rear=2 → FULL
//   deQueue():  head=(0+1)%3=1 count=2      arr[0] freed
//   enQueue(4): rear=(1+2)%3=0 arr=[4,2,3] count=3 wraps to slot 0  ✓
//   Rear()    → arr[(1+3-1)%3] = arr[0] = 4  ✓
//   isFull()  → count=3 == k=3 → true  ✓
//
// TC: O(1) per operation  SC: O(k) — fixed array of size k
// ----------------------------------------------------------
class CircularQueue
{
    private array $arr;
    private int   $head  = 0; // Index of FRONT element
    private int   $count = 0; // Current number of elements
    private int   $k;         // Maximum capacity

    public function __construct(int $k)
    {
        $this->k   = $k;
        $this->arr = array_fill(0, $k, 0); // Pre-allocate fixed array
    }

    public function enQueue(int $val): bool
    {
        if ($this->isFull()) return false;

        // rear index is one slot past the last element (wraps around)
        $rear              = ($this->head + $this->count) % $this->k;
        $this->arr[$rear]  = $val;
        $this->count++;
        return true;
    }

    public function deQueue(): bool
    {
        if ($this->isEmpty()) return false;

        $this->head  = ($this->head + 1) % $this->k; // Advance front pointer
        $this->count--;
        return true;
    }

    // Return FRONT element value; -1 if empty
    public function front(): int
    {
        return $this->isEmpty() ? -1 : $this->arr[$this->head];
    }

    // Return REAR element value; -1 if empty
    public function rear(): int
    {
        if ($this->isEmpty()) return -1;
        $rearIdx = ($this->head + $this->count - 1) % $this->k;
        return $this->arr[$rearIdx];
    }

    public function isEmpty(): bool { return $this->count === 0; }
    public function isFull(): bool  { return $this->count === $this->k; }
}


// ============================================================
// 5. SLIDING WINDOW MAXIMUM — MONOTONIC DEQUE  (LeetCode 239)
// ============================================================
// Intuition:
//   Given array $nums and window size $k, find the maximum in
//   each sliding window of size $k. Brute force is O(N×k).
//
//   Optimal: Monotonic Deque (double-ended queue) — O(N).
//   The deque stores INDICES and maintains a DECREASING order
//   of values (largest value's index at the front).
//
//   For each new index $i:
//   1. REMOVE OUT-OF-WINDOW indices from the FRONT:
//      If front index ≤ i - k → it's no longer in the window.
//   2. REMOVE USELESS indices from the REAR:
//      Pop from back while nums[back] ≤ nums[i].
//      (Smaller elements behind the current one can NEVER be
//       the window maximum — they'll be gone before $i leaves.)
//   3. ADD current index $i to the REAR.
//   4. RECORD ANSWER once the first full window is formed (i >= k-1).
//      The front of the deque holds the index of the WINDOW MAXIMUM.
//
// Dry Run: nums=[1,3,-1,-3,5,3,6,7], k=3
//   i=0 (1):  dq=[], push 0.      dq=[0]         window not full yet
//   i=1 (3):  3>nums[0]=1 → pop 0. push 1.        dq=[1]
//   i=2 (-1): -1<3 → push 2.      dq=[1,2]  i>=k-1=2 → max=nums[1]=3  ✓
//   i=3 (-3): -3<-1 → push 3.     dq=[1,2,3] front=1 (in window[1..3]) → max=nums[1]=3  ✓
//   i=4 (5):  5>nums[3]=-3→pop3, 5>nums[2]=-1→pop2, 5>nums[1]=3→pop1. push 4. dq=[4]
//              front=4, 4>4-3=1 (ok) → max=nums[4]=5  ✓
//   i=5 (3):  3<5 → push 5.       dq=[4,5] → max=nums[4]=5  ✓
//   i=6 (6):  6>nums[5]=3→pop5, 6>nums[4]=5→pop4. push 6. dq=[6] → max=6  ✓
//   i=7 (7):  7>nums[6]=6→pop6. push 7. dq=[7] → max=7  ✓
//   Result: [3, 3, 5, 5, 6, 7]  ✓
//
// TC: O(N) — each index is pushed and popped at most once
// SC: O(k) — deque holds at most k indices at any time
// ----------------------------------------------------------
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


// ============================================================
// DEMO — Run All Operations
// ============================================================

echo "=== 1. Queue (Array / Circular Buffer) ===\n";
$q = new Queue(5);
$q->push(1); $q->push(2); $q->push(3);
$q->printQueue();                                 // FRONT → 1 → 2 → 3 ← REAR
echo "front: " . $q->front() . "\n";              // 1
echo "rear:  " . $q->rear()  . "\n";              // 3
echo "pop:   " . $q->pop()   . "\n";              // 1
echo "pop:   " . $q->pop()   . "\n";              // 2
$q->push(4); $q->push(5); $q->push(6);
$q->printQueue();                                 // FRONT → 3 → 4 → 5 → 6 ← REAR
echo "size:  " . $q->size()  . "\n";              // 4

echo "\n=== 2. Queue (Linked List) ===\n";
$qll = new QueueLL();
$qll->push(10); $qll->push(20); $qll->push(30);
$qll->printQueue();                               // FRONT → 10 → 20 → 30 → NULL
echo "front: " . $qll->front() . "\n";            // 10
echo "pop:   " . $qll->pop()   . "\n";            // 10
$qll->printQueue();                               // FRONT → 20 → 30 → NULL

echo "\n=== 3. Queue Using Two Stacks (LC 232) ===\n";
$qs = new QueueUsingStacks();
$qs->push(1); $qs->push(2); $qs->push(3);
echo "peek:  " . $qs->peek()  . "\n";             // 1
echo "pop:   " . $qs->pop()   . "\n";             // 1
echo "pop:   " . $qs->pop()   . "\n";             // 2
$qs->push(4);
echo "peek:  " . $qs->peek()  . "\n";             // 3
echo "size:  " . $qs->size()  . "\n";             // 2
echo "empty: " . ($qs->isEmpty() ? 'true' : 'false') . "\n"; // false

echo "\n=== 4. Design Circular Queue (LC 622) ===\n";
$cq = new CircularQueue(3);
echo (int)$cq->enQueue(1) . "\n"; // 1 (success)
echo (int)$cq->enQueue(2) . "\n"; // 1
echo (int)$cq->enQueue(3) . "\n"; // 1
echo (int)$cq->enQueue(4) . "\n"; // 0 (full)
echo "Rear:  " . $cq->rear()   . "\n"; // 3
echo (int)$cq->deQueue()        . "\n"; // 1 (success)
echo (int)$cq->enQueue(4)       . "\n"; // 1 (slot freed)
echo "Rear:  " . $cq->rear()    . "\n"; // 4
echo "Full:  " . (int)$cq->isFull()  . "\n"; // 1
echo "Front: " . $cq->front()   . "\n"; // 2

echo "\n=== 5. Sliding Window Maximum (LC 239) ===\n";
$res = slidingWindowMax([1, 3, -1, -3, 5, 3, 6, 7], 3);
echo implode(', ', $res) . "\n"; // 3, 3, 5, 5, 6, 7

$res2 = slidingWindowMax([1], 1);
echo implode(', ', $res2) . "\n"; // 1

$res3 = slidingWindowMax([9, 11], 2);
echo implode(', ', $res3) . "\n"; // 11


// ============================================================
// COMPARISON SUMMARY
// ============================================================
//
//  Implementation                  | push    | pop     | peek    | SC       | Notes
// ---------------------------------+---------+---------+---------+----------+-----------------
//  Queue (Array / Ring Buffer)     | O(1)    | O(1)    | O(1)    | O(N)     | Fixed capacity
//  Queue (Linked List)             | O(1)    | O(1)    | O(1)    | O(N)     | Dynamic, no limit
//  Queue using Two Stacks          | O(1)†   | O(1)†   | O(1)†   | O(N)     | † Amortised
//  Circular Queue (LC 622)         | O(1)    | O(1)    | O(1)    | O(k)     | Fixed size k
//  Sliding Window Max (Deque)      | O(N)    | —       | —       | O(k)     | Per full input
// ---------------------------------+---------+---------+---------+----------+-----------------
//  PHP SplQueue                    | O(1)    | O(1)    | O(1)    | O(N)     | Built-in, fast
//  PHP array (array_shift)         | O(1)    | O(N)    | O(1)    | O(N)     | Shift is O(N)!


// ============================================================
// PRACTICE PROBLEMS & APPLICATIONS
// ============================================================
//
//  EASY
//  1. Implement Queue using Stacks (LeetCode 232)
//     → Two-stack approach; amortised O(1) per operation
//  2. Number of Recent Calls (LeetCode 933)
//     → Sliding window with a queue; pop old requests outside [t-3000, t]
//  3. First Unique Character in a String (LeetCode 387)
//     → Queue + frequency map to find first non-repeating character
//  4. Time Needed to Buy Tickets (LeetCode 2073)
//     → Simulate a queue; modular counting
//
//  MEDIUM
//  5. Design Circular Queue (LeetCode 622)
//     → Ring buffer with head pointer and count; derive rear index
//  6. Design Circular Deque (LeetCode 641)
//     → Double-ended circular buffer; front and rear both support add/remove
//  7. Sliding Window Maximum (LeetCode 239)
//     → Monotonic decreasing deque; O(N) with at-most-once push/pop per element
//  8. Walls and Gates (LeetCode 286)
//     → Multi-source BFS; start from all gates (0) simultaneously
//  9. Rotting Oranges (LeetCode 994)
//     → Multi-source BFS from all rotten oranges; spread level by level
// 10. Task Scheduler (LeetCode 621)
//     → Greedy + priority queue; schedule most-frequent tasks first
// 11. Design Hit Counter (LeetCode 362)
//     → Queue stores timestamps; pop entries older than 300 seconds
// 12. Generate Binary Numbers 1 to N
//     → Enqueue "1"; for each dequeued string s, enqueue s+"0" and s+"1"
//
//  HARD
// 13. Sliding Window Median (LeetCode 480)
//     → Two heaps (max + min) maintaining balance across the window
// 14. Maximum of Minimum for Every Window Size
//     → Monotonic stack + reverse traversal; O(N)
// 15. Shortest Subarray with Sum at Least K (LeetCode 862)
//     → Prefix sum + monotonic deque for O(N log N) solution
// 16. Largest Rectangle in Histogram (LeetCode 84)
//     → Monotonic increasing stack; conceptually a deque variant


// ============================================================
// KEY PATTERNS & VARIATIONS FOR REVISION
// ============================================================
//
//  PATTERN 1 — BFS (Breadth-First Search):
//    A queue is THE data structure for BFS.
//    Enqueue the starting node(s); process level by level.
//    Record the queue size at the START of each level to separate layers.
//    Covers: Binary tree level order, shortest path in grid,
//            multi-source BFS (Rotting Oranges, Walls and Gates).
//
//  PATTERN 2 — Monotonic Deque (Sliding Window Min/Max):
//    Maintain a deque of indices in DECREASING order for max,
//    or INCREASING order for min.
//    Before adding index i:
//      a) Remove out-of-window indices from the FRONT.
//      b) Remove useless indices from the REAR.
//    The FRONT always holds the answer for the current window.
//    Each element is pushed and popped at most once → O(N) total.
//    Covers: LC 239, LC 862, LC 480.
//
//  PATTERN 3 — Two-Stack Queue (Amortised O(1)):
//    Input stack + Output stack.
//    All pushes go to input stack.
//    Transfer to output stack only when output is empty.
//    Each element transferred at most once → amortised O(1) pop/peek.
//    Mirror problem: implement stack using two queues (LC 225).
//
//  PATTERN 4 — Circular / Ring Buffer:
//    Use MODULO arithmetic to wrap both front and rear pointers.
//    rear = (rear + 1) % capacity    (add to rear)
//    front = (front + 1) % capacity  (remove from front)
//    Track size separately (not rear − front) to distinguish full vs empty.
//    Derive rear index as: (front + count − 1) % k — no need for two pointers.
//    Covers: LC 622, LC 641, OS process scheduling simulation.
//
//  PATTERN 5 — Multi-Source BFS:
//    When you have MULTIPLE starting points, add ALL of them to
//    the queue at level 0 before starting BFS.
//    Distance from any source = level at which a cell is first reached.
//    Covers: LC 994 (Rotting Oranges), LC 286 (Walls and Gates),
//            LC 542 (01 Matrix).
//
//  PATTERN 6 — Queue Simulation (Order Processing):
//    Model real-world scheduling problems:
//     - Round-robin CPU scheduling
//     - Ticket counter (LC 2073)
//     - Recent call counter (LC 933)
//    Key idea: elements in a queue are processed in ARRIVAL order;
//    use peek + condition checks to decide when to pop.


// ============================================================
// IMPORTANT TIPS & EDGE CASES
// ============================================================
//
//  1. PHP array_shift() is O(N), NOT O(1):
//     PHP re-indexes the entire array after shifting.
//     For performance-critical queues use SplQueue (O(1) dequeue)
//     or maintain a $front index pointer to avoid shifting.
//
//  2. Circular Queue — Full vs Empty disambiguation:
//     With only front and rear pointers you cannot distinguish
//     full (front==rear) from empty (front==rear).
//     Solution: track a separate $count (or $size) variable.
//     Alternative: leave one slot unused and use (rear+1)%k==front for full.
//
//  3. Two-Stack Queue — peek() must also trigger a transfer:
//     A common mistake is to implement peek() by looking at pushSt.
//     Always call transfer() before peek() — the oldest element may
//     already be in popSt, not pushSt.
//
//  4. Deque (Double-Ended Queue) vs Queue:
//     PHP does NOT have a built-in deque with O(1) front operations.
//     For competitive-programming deque behavior use SplDoublyLinkedList.
//     The Sliding Window code above uses array_shift() for clarity (O(N));
//     replace with SplDoublyLinkedList for strict O(1) dequeue.
//
//  5. Monotonic Deque — STRICT vs non-strict inequality:
//     Removing from rear while nums[rear] <= nums[i]  → keeps LAST maximum.
//     Removing from rear while nums[rear] <  nums[i]  → keeps FIRST maximum.
//     Choose based on whether duplicate maxima matter.
//
//  6. BFS Level Separation — snapshot queue size:
//     Capture $levelSize = count($queue) at the START of each level.
//     Process exactly $levelSize nodes before incrementing the level counter.
//     Doing this INSIDE the level loop causes incorrect level detection.
//
//  7. Circular Queue vs Circular Buffer — same idea, different names:
//     Ring Buffer / Circular Buffer = fixed-size memory block.
//     Circular Queue = ADT that behaves like a ring buffer.
//     Both use the same modulo-wrapping technique.
//
//  8. Queue is always the right choice for "first seen" problems:
//     Because FIFO preserves insertion order, any time you need to
//     process elements in the order they were DISCOVERED (not priority),
//     reach for a plain queue rather than a heap or stack.

?>
