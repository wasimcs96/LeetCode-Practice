<?php

// ============================================================
// STACK — Complete Revision Guide
// Topics : Stack using Array
//          Stack using Linked List
//          Implement Stack using Queue  (LeetCode 225)
//          Min Stack — Auxiliary Stack O(2N) (LeetCode 155)
//          Min Stack — Encoding Trick   O(N)  (LeetCode 155)
//          Valid Parentheses                  (LeetCode 20)
//          Infix to Postfix Conversion
//          Next Greater Element I             (LeetCode 496)
//          Sort a Stack (Recursive)
// ============================================================
// Core Intuition:
//   Stack = LIFO (Last In, First Out) data structure.
//   Think of a pile of plates: the LAST plate placed is the
//   FIRST one you can remove.
//
//   Four fundamental operations:
//     push(x)  → add x to the TOP             O(1)
//     pop()    → remove element from the TOP  O(1)
//     top/peek → read TOP without removing    O(1)
//     isEmpty  → check if stack has no items  O(1)
//
//   When to reach for a Stack:
//     - Track "what came before" (parenthesis matching, undo)
//     - Find the "next greater / smaller" element (monotonic stack)
//     - Reverse a sequence (DFS, browser back-button)
//     - Convert / evaluate expressions (infix → postfix)
// ============================================================


// ============================================================
// 1. STACK USING ARRAY (PHP Array)
// ============================================================
// Intuition:
//   Use a PHP array where the END is the TOP.
//   array_push() → push to TOP (end).
//   array_pop()  → pop from TOP (end).
//   end()        → peek at TOP without removing.
//
// TC: O(1) per operation  SC: O(N) — array grows with N pushes
// ----------------------------------------------------------

class Stack
{
    private array $items    = [];
    private int   $maxSize;

    public function __construct(int $maxSize = PHP_INT_MAX)
    {
        $this->maxSize = $maxSize;
    }

    // Add element to the TOP of the stack
    public function push(int $x): void
    {
        if (count($this->items) >= $this->maxSize) {
            throw new OverflowException("Stack Overflow");
        }
        $this->items[] = $x;
    }

    // Remove and return the TOP element
    public function pop(): int
    {
        if ($this->isEmpty()) {
            throw new UnderflowException("Stack Underflow");
        }
        return array_pop($this->items);
    }

    // Read the TOP element without removing it
    public function peek(): int
    {
        if ($this->isEmpty()) {
            throw new UnderflowException("Stack is empty");
        }
        return end($this->items);
    }

    // Return true if stack has no elements
    public function isEmpty(): bool
    {
        return empty($this->items);
    }

    public function size(): int { return count($this->items); }

    // Print stack from bottom → top
    public function printStack(): void
    {
        echo implode(' → ', $this->items) . " ← TOP\n";
    }
}


// ============================================================
// 2. STACK USING LINKED LIST
// ============================================================
// Intuition:
//   The TOP of the stack = the HEAD of the linked list.
//   push: create new node, point it to old head, make it new head.
//   pop:  save head data, move head to head->next.
//   This avoids array resizing and push/pop are always O(1).
//
// TC: O(1) per operation  SC: O(N) — N nodes in memory
// ----------------------------------------------------------

class StackNode
{
    public int       $data;
    public ?StackNode $next;

    public function __construct(int $data)
    {
        $this->data = $data;
        $this->next = null;
    }
}

class StackLL
{
    private ?StackNode $top;
    private int        $size;

    public function __construct()
    {
        $this->top  = null;
        $this->size = 0;
    }

    // Push: new node becomes the new head (TOP)
    public function push(int $x): void
    {
        $node       = new StackNode($x);
        $node->next = $this->top; // New node points to old top
        $this->top  = $node;      // New node is now the top
        $this->size++;
    }

    // Pop: save top's data, advance top to next node
    public function pop(): int
    {
        if ($this->isEmpty()) {
            throw new UnderflowException("Stack Underflow");
        }
        $data      = $this->top->data;
        $this->top = $this->top->next; // Move top down
        $this->size--;
        return $data;
    }

    // Peek without removing
    public function peek(): int
    {
        if ($this->isEmpty()) {
            throw new UnderflowException("Stack is empty");
        }
        return $this->top->data;
    }

    public function isEmpty(): bool { return $this->top === null; }
    public function size(): int     { return $this->size; }

    // Print stack top → bottom
    public function printStack(): void
    {
        $node  = $this->top;
        $parts = [];
        while ($node !== null) {
            $parts[] = (string)$node->data;
            $node    = $node->next;
        }
        echo "TOP → " . implode(' → ', $parts) . " → NULL\n";
    }
}


// ============================================================
// 3. IMPLEMENT STACK USING SINGLE QUEUE  (LeetCode 225)
// ============================================================
// Intuition:
//   A queue is FIFO (First In, First Out), but we need LIFO.
//   Trick: after enqueuing the new element x,
//     ROTATE all PREVIOUSLY existing elements to the back.
//     x ends up at the FRONT → acts as the TOP of the stack.
//
//   Why rotate (length - 1) times?
//     Before push:  queue = [3, 2, 1]  (3 is LIFO top)
//     push(4):      enqueue → [3, 2, 1, 4]
//     rotate 3×:    [2,1,4,3] → [1,4,3,2] → [4,3,2,1]
//     Now 4 is at the front → top/pop both read from front.
//
// Dry Run: push(1), push(2), push(3), top → pop
//   push(1): q=[1];  rotate 0×  → q=[1]           TOP=1
//   push(2): q=[1,2]; rotate 1×: shift 1, enqueue 1 → q=[2,1] TOP=2
//   push(3): q=[2,1,3]; rotate 2×:
//     [1,3,2] → [3,2,1]                             TOP=3
//   top()  → q[0] = 3  ✓
//   pop()  → shift 3, q=[2,1], return 3             TOP=2  ✓
//
// TC: push O(N)  |  pop/peek O(1)  SC: O(N)
// ----------------------------------------------------------
class StackUsingQueue
{
    private array $queue = [];

    public function push(int $x): void
    {
        $oldSize = count($this->queue);
        $this->queue[] = $x;                   // Enqueue new element at the back

        // Rotate all OLD elements to the back so $x becomes the front
        for ($i = 0; $i < $oldSize; $i++) {
            $this->queue[] = array_shift($this->queue); // Move front to back
        }
    }

    // TOP of stack = FRONT of queue
    public function pop(): int
    {
        if ($this->isEmpty()) {
            throw new UnderflowException("Stack Underflow");
        }
        return array_shift($this->queue); // Remove and return front
    }

    public function peek(): int
    {
        if ($this->isEmpty()) {
            throw new UnderflowException("Stack is empty");
        }
        return $this->queue[0]; // Peek front without removing
    }

    public function isEmpty(): bool { return empty($this->queue); }
    public function size(): int     { return count($this->queue); }
}


// ============================================================
// 4. MIN STACK — AUXILIARY STACK APPROACH  (LeetCode 155)
//    Get current minimum in O(1)
// ============================================================
// Intuition:
//   Use TWO stacks: one for values, one for running minimums.
//   Auxiliary (minSt) always stores the CURRENT minimum at every
//   point in history, so getMin() is always O(1).
//
//   push(x):
//     Main stack: push x as normal.
//     Min  stack: push min(x, current_min).
//   pop():
//     Pop from BOTH stacks in sync.
//   getMin():
//     Peek top of min stack — it holds the current minimum.
//
// Dry Run: push(-2), push(0), push(-3)  then getMin, pop, top, getMin
//   push(-2): main=[-2],    minSt=[-2]          min=-2
//   push(0):  main=[-2,0],  minSt=[-2,-2]       min=-2
//   push(-3): main=[-2,0,-3], minSt=[-2,-2,-3]  min=-3
//   getMin() → minSt.top = -3  ✓
//   pop()   → main=[-2,0], minSt=[-2,-2]
//   top()   → main.top = 0  ✓
//   getMin()→ minSt.top = -2  ✓
//
// TC: O(1) per operation  SC: O(2N) — two stacks
// ----------------------------------------------------------
class MinStackAux
{
    private array $main  = [];   // Stores actual values
    private array $minSt = [];   // Stores current minimum at each level

    public function push(int $x): void
    {
        $this->main[] = $x;

        // Current min = smaller of x and the previous minimum
        $prevMin         = empty($this->minSt) ? $x : end($this->minSt);
        $this->minSt[]   = min($x, $prevMin);
    }

    public function pop(): void
    {
        array_pop($this->main);
        array_pop($this->minSt); // Both stacks always stay in sync
    }

    public function top(): ?int
    {
        return empty($this->main) ? null : end($this->main);
    }

    public function getMin(): ?int
    {
        return empty($this->minSt) ? null : end($this->minSt);
    }
}


// ============================================================
// 5. MIN STACK — ENCODING TRICK  (LeetCode 155)
//    O(N) total space — SINGLE stack, one extra variable
// ============================================================
// Intuition:
//   Store the current minimum in a separate variable $mini.
//   Instead of storing the ACTUAL value when x < $mini, store
//   an ENCODED value (2x − mini) that is < $mini.
//   This encoding lets us detect that a pop crossed a minimum boundary.
//
//   push(x):
//     If x >= $mini: push x normally.
//     If x <  $mini: push (2x − $mini)  [encoded; always < $mini]
//                    then update $mini = x.
//   pop():
//     val = stack.pop()
//     If val >= $mini: nothing special (value was normal, min unchanged).
//     If val <  $mini: the popped value was a boundary.
//                      Recover previous min: $mini = 2×$mini − val.
//   top():
//     If top >= $mini: return top (actual value).
//     If top <  $mini: return $mini (top was encoded; real value = $mini).
//   getMin(): return $mini directly.
//
// Dry Run: push(3), push(5), push(2), pop, getMin
//   push(3): st=empty, mini=3, push 3.           st=[3],  mini=3
//   push(5): 5>=3, push 5.                        st=[3,5], mini=3
//   push(2): 2<3, encode=2*2-3=1, push 1; mini=2. st=[3,5,1], mini=2
//   top(): top=1 < mini=2 → return mini=2  ✓
//   pop():  val=1 < mini=2 → prev_mini = 2*2-1=3; st=[3,5], mini=3
//   getMin(): return mini=3  ✓
//
// TC: O(1) per operation  SC: O(N) — single stack
// ----------------------------------------------------------
class MinStackOpt
{
    private array $st   = [];
    private int   $mini = PHP_INT_MAX;

    public function push(int $x): void
    {
        if (empty($this->st)) {
            $this->mini  = $x;
            $this->st[]  = $x;
        } elseif ($x < $this->mini) {
            // Encode x to (2x − mini) so encoded value < mini
            // This signals during pop that a minimum boundary is crossed
            $this->st[]  = 2 * $x - $this->mini;
            $this->mini  = $x;              // Update current minimum
        } else {
            $this->st[] = $x;              // Normal push
        }
    }

    public function pop(): void
    {
        if (empty($this->st)) return;

        $val = array_pop($this->st);

        if ($val < $this->mini) {
            // Encoded value found → recover the PREVIOUS minimum
            // Previous mini = 2 × current_mini − encoded_val
            $this->mini = 2 * $this->mini - $val;
        }
        // If val >= mini, the minimum is unchanged — just discard
    }

    public function top(): ?int
    {
        if (empty($this->st)) return null;
        $val = end($this->st);
        // If stored value < mini, the ACTUAL top is mini (it was encoded)
        return ($val < $this->mini) ? $this->mini : $val;
    }

    public function getMin(): ?int
    {
        return empty($this->st) ? null : $this->mini;
    }
}


// ============================================================
// 6. VALID PARENTHESES  (LeetCode 20)
// ============================================================
// Intuition:
//   Use a stack to track UNMATCHED open brackets.
//   For every OPEN bracket   → push it.
//   For every CLOSE bracket  → check if top of stack is the
//                               matching open bracket.
//                               If yes → pop (matched).
//                               If no  → invalid, return false.
//   At the end: stack must be empty (all brackets matched).
//
// Dry Run: s = "({[]})"
//   '(' → push.  stack=['(']
//   '{' → push.  stack=['(', '{']
//   '[' → push.  stack=['(', '{', '[']
//   ']' → top='[' matches → pop. stack=['(', '{']
//   '}' → top='{' matches → pop. stack=['(']
//   ')' → top='(' matches → pop. stack=[]
//   stack is empty → true  ✓
//
// Dry Run: s = "([)]"
//   '(' → push.  stack=['(']
//   '[' → push.  stack=['(', '[']
//   ')' → top='[' ≠ matching '(' → return false  ✓
//
// TC: O(N)  SC: O(N) — stack can hold up to N/2 open brackets
// ----------------------------------------------------------
function isValidParentheses(string $s): bool
{
    $stack = [];

    // Map each CLOSE bracket to its corresponding OPEN bracket
    $matching = [')' => '(', ']' => '[', '}' => '{'];

    for ($i = 0; $i < strlen($s); $i++) {
        $ch = $s[$i];
        if ($ch === '(' || $ch === '[' || $ch === '{') {
            $stack[] = $ch;                // Open bracket → push
        } else {
            // Close bracket found
            if (empty($stack) || end($stack) !== $matching[$ch]) {
                return false;             // No match → invalid
            }
            array_pop($stack);            // Matched → pop the open bracket
        }
    }

    return empty($stack); // Valid iff all brackets were matched
}


// ============================================================
// 7. INFIX TO POSTFIX CONVERSION
// ============================================================
// Intuition:
//   Infix:   a + b * c     (operator between operands)
//   Postfix: a b c * +     (operator after operands, no parentheses)
//
//   Algorithm (Shunting-Yard):
//   For each character in the infix string:
//     OPERAND  → append directly to output.
//     '('      → push to stack (scope marker).
//     ')'      → pop and append until '(' is found; discard '('.
//     OPERATOR → pop and append operators of HIGHER or EQUAL
//                precedence from stack, then push current operator.
//   At end: pop all remaining operators to output.
//
//   Precedence: ^ (3) > * / (2) > + - (1)
//   Note: ^ is RIGHT-associative (a^b^c = a^(b^c)).
//         Use STRICTLY LESS THAN for ^ to avoid popping too early.
//
// Dry Run: infix = "a+b*(c^d-e)^(f+g*h)-i"
//   'a' → output="a"
//   '+' → stack empty, push.       stack=[+]
//   'b' → output="ab"
//   '*' → prec(*)=2 > prec(+)=1 → push. stack=[+,*]
//   '(' → push.                    stack=[+,*,(]
//   'c' → output="abc"
//   '^' → stack top='(' stop.push. stack=[+,*,(,^]
//   'd' → output="abcd"
//   '-' → prec(-)=1 < prec(^)=3 → pop '^' → output="abcd^"
//          stack top='(' stop. push '-'. stack=[+,*,(,-]
//   'e' → output="abcd^e"
//   ')' → pop until '(': pop '-' → output="abcd^e-". discard '('
//          stack=[+,*]
//   '^' → prec(^)=3 > prec(*)=2 → push. stack=[+,*,^]
//   (... continues to full postfix: abcd^e-fgh*+^*+i-)
//   Result: "abcd^e-fgh*+^*+i-"  ✓
//
// TC: O(N)  SC: O(N) — stack and output both grow with input
// ----------------------------------------------------------
function infixToPostfix(string $infix): string
{
    $stack   = [];
    $postfix = '';

    for ($i = 0; $i < strlen($infix); $i++) {
        $ch = $infix[$i];

        // OPERAND: letter or digit → append directly to output
        if (ctype_alnum($ch)) {
            $postfix .= $ch;

        } elseif ($ch === '(') {
            // Open bracket: push as a scope start marker
            $stack[] = $ch;

        } elseif ($ch === ')') {
            // Close bracket: pop and output until matching '(' found
            while (!empty($stack) && end($stack) !== '(') {
                $postfix .= array_pop($stack);
            }
            array_pop($stack); // Discard the '(' itself

        } else {
            // OPERATOR: pop operators of higher/equal precedence first
            // Note: ^ is right-associative → use strictly LESS THAN (<)
            while (!empty($stack)
                   && end($stack) !== '('
                   && getPrecedence(end($stack)) >= getPrecedence($ch)
                   && $ch !== '^')
            {
                $postfix .= array_pop($stack);
            }
            $stack[] = $ch; // Push current operator
        }
    }

    // Drain remaining operators from the stack to output
    while (!empty($stack)) {
        $postfix .= array_pop($stack);
    }

    return $postfix;
}

// Returns precedence level of an operator (higher = tighter binding)
function getPrecedence(string $op): int
{
    return match($op) {
        '^'        => 3,
        '*', '/'   => 2,
        '+', '-'   => 1,
        default    => -1,
    };
}


// ============================================================
// 8. NEXT GREATER ELEMENT I  (LeetCode 496)
// ============================================================
// Intuition — Monotonic Stack:
//   For each element, find the first element to its RIGHT
//   that is strictly GREATER. Return -1 if none exists.
//
//   Brute force: O(N²) — for each element, scan rightward.
//   Optimal   : O(N)  — traverse RIGHT TO LEFT.
//
//   Right-to-left scan with a DECREASING monotonic stack:
//     - Stack always holds elements in DECREASING order (largest at bottom).
//     - For each element arr[i]:
//       1. Pop all elements from stack that are ≤ arr[i]
//          (they can never be the answer for arr[i] or any future element).
//       2. If stack is empty → no greater element to the right → -1.
//       3. Else → stack.top() is the NEXT GREATER element.
//       4. Push arr[i] onto the stack.
//
// Dry Run: arr = [4, 5, 2, 10, 8]  (right-to-left)
//   i=4 (8):  stack=[].   ans[4]=-1.  push 8.  stack=[8]
//   i=3 (10): 8≤10 → pop. stack=[]. ans[3]=-1. push 10. stack=[10]
//   i=2 (2):  10>2.       ans[2]=10. push 2.  stack=[10,2]
//   i=1 (5):  2≤5 → pop.  10>5.     ans[1]=10. push 5. stack=[10,5]
//   i=0 (4):  5>4.        ans[0]=5.  push 4.  stack=[10,5,4]
//   Result: [5, 10, 10, -1, -1]  ✓
//
// TC: O(N)  SC: O(N) — each element pushed/popped at most once
// ----------------------------------------------------------
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


// ============================================================
// 9. SORT A STACK (Recursive)
// ============================================================
// Intuition:
//   We cannot access arbitrary positions in a stack.
//   Use RECURSION to simulate the sorting process:
//
//   sortStack(stack):
//     1. Pop the TOP element.
//     2. Recursively sort the remaining stack.
//     3. INSERT the popped element at its CORRECT SORTED POSITION
//        in the already-sorted stack.
//
//   insertSorted(stack, elem):
//     If stack is empty OR top ≥ elem → push elem (correct position).
//     Else → pop top, recurse insertSorted, then push top back.
//
// Dry Run: stack = [3, 1, 4, 2]  (2 is TOP)
//   Pop 2. Sort [3,1,4].
//     Pop 4. Sort [3,1].
//       Pop 1. Sort [3].
//         Pop 3. Sort []. Insert 3 → stack=[3].
//         Insert 1: top=3 > 1 → push 1. stack=[3,1]
//       Insert 4: top=1 < 4 → pop 1, top=3 < 4 → pop 3,
//                  stack=[]. push 4. push 3. push 1. stack=[4,3,1]
//     Insert 2: top=1 < 2 → pop 1, top=3 > 2 → push 2, push 1.
//                stack=[4,3,2,1]
//   Push sorted: stack = [4, 3, 2, 1]  (1 is TOP = smallest)
//   ✓ Stack sorted in descending order (smallest on top)
//
// TC: O(N²)  SC: O(N) — recursion depth
// ----------------------------------------------------------
function sortStack(array &$stack): void
{
    if (empty($stack)) return;

    $top = array_pop($stack);  // Remove the TOP element
    sortStack($stack);          // Recursively sort the rest
    insertSorted($stack, $top); // Insert popped element at correct position
}

function insertSorted(array &$stack, int $elem): void
{
    // Base case: empty stack OR top element is >= elem (insert here)
    if (empty($stack) || end($stack) >= $elem) {
        $stack[] = $elem;
        return;
    }
    $top = array_pop($stack);          // Pop the larger element temporarily
    insertSorted($stack, $elem);        // Recurse to find correct position
    $stack[] = $top;                   // Put the larger element back on top
}


// ============================================================
// DEMO — Run All Operations
// ============================================================

echo "=== 1. Stack (Array) ===\n";
$st = new Stack(10);
$st->push(1); $st->push(2); $st->push(3);
$st->printStack();                                   // 1 → 2 → 3 ← TOP
echo "peek: " . $st->peek()    . "\n";               // 3
echo "pop:  " . $st->pop()     . "\n";               // 3
echo "size: " . $st->size()    . "\n";               // 2
$st->printStack();                                   // 1 → 2 ← TOP

echo "\n=== 2. Stack (Linked List) ===\n";
$sll = new StackLL();
$sll->push(10); $sll->push(20); $sll->push(30);
$sll->printStack();                                  // TOP → 30 → 20 → 10 → NULL
echo "peek: " . $sll->peek() . "\n";                 // 30
echo "pop:  " . $sll->pop()  . "\n";                 // 30
$sll->printStack();                                  // TOP → 20 → 10 → NULL

echo "\n=== 3. Stack Using Queue (LC 225) ===\n";
$sq = new StackUsingQueue();
$sq->push(1); $sq->push(2); $sq->push(3); $sq->push(4);
echo "top:  " . $sq->peek() . "\n";                  // 4
echo "pop:  " . $sq->pop()  . "\n";                  // 4
echo "pop:  " . $sq->pop()  . "\n";                  // 3
echo "top:  " . $sq->peek() . "\n";                  // 2
echo "empty:" . ($sq->isEmpty() ? 'true' : 'false') . "\n"; // false

echo "\n=== 4. Min Stack — Auxiliary Stack O(2N) (LC 155) ===\n";
$ms = new MinStackAux();
$ms->push(-2); $ms->push(0); $ms->push(-3);
echo "getMin: " . $ms->getMin() . "\n";              // -3
$ms->pop();
echo "top:    " . $ms->top()    . "\n";              // 0
echo "getMin: " . $ms->getMin() . "\n";              // -2

echo "\n=== 5. Min Stack — Encoding Trick O(N) (LC 155) ===\n";
$ms2 = new MinStackOpt();
$ms2->push(3); $ms2->push(5); $ms2->push(2);
echo "top:    " . $ms2->top()    . "\n";             // 2
echo "getMin: " . $ms2->getMin() . "\n";             // 2
$ms2->pop();
echo "top:    " . $ms2->top()    . "\n";             // 5
echo "getMin: " . $ms2->getMin() . "\n";             // 3

echo "\n=== 6. Valid Parentheses (LC 20) ===\n";
echo isValidParentheses("({[]})") ? "valid\n" : "invalid\n";  // valid
echo isValidParentheses("([)]")   ? "valid\n" : "invalid\n";  // invalid
echo isValidParentheses("{[]}")   ? "valid\n" : "invalid\n";  // valid
echo isValidParentheses("((")     ? "valid\n" : "invalid\n";  // invalid

echo "\n=== 7. Infix to Postfix ===\n";
echo infixToPostfix("a+b*(c^d-e)^(f+g*h)-i") . "\n"; // abcd^e-fgh*+^*+i-
echo infixToPostfix("a+b*c")                   . "\n"; // abc*+
echo infixToPostfix("(a+b)*c")                 . "\n"; // ab+c*

echo "\n=== 8. Next Greater Element (LC 496) ===\n";
$nge = nextGreaterElement([4, 5, 2, 10, 8]);
echo implode(', ', $nge) . "\n";                     // 5, 10, 10, -1, -1
$nge2 = nextGreaterElement([1, 3, 2, 4]);
echo implode(', ', $nge2) . "\n";                    // 3, 4, 4, -1

echo "\n=== 9. Sort a Stack ===\n";
$stack = [3, 1, 4, 2];   // 2 is TOP
sortStack($stack);
// Sorted: smallest on top (1), largest at bottom (4)
echo implode(', ', array_reverse($stack)) . " ← TOP\n"; // 4, 3, 2, 1 ← TOP


// ============================================================
// COMPARISON SUMMARY
// ============================================================
//
//  Implementation                | push    | pop     | peek    | SC       | Notes
// -------------------------------+---------+---------+---------+----------+----------------
//  Stack (Array)                 | O(1)    | O(1)    | O(1)    | O(N)     | Simplest
//  Stack (Linked List)           | O(1)    | O(1)    | O(1)    | O(N)     | Dynamic memory
//  Stack using Queue             | O(N)    | O(1)    | O(1)    | O(N)     | Push is costly
//  Min Stack (Aux)               | O(1)    | O(1)    | O(1)    | O(2N)    | Extra min stack
//  Min Stack (Encoding)          | O(1)    | O(1)    | O(1)    | O(N)     | Single stack + var
// -------------------------------+---------+---------+---------+----------+----------------
//  Valid Parentheses             | O(N)    | —       | —       | O(N)     | Classic
//  Infix to Postfix              | O(N)    | —       | —       | O(N)     | Shunting-Yard
//  Next Greater Element          | O(N)    | —       | —       | O(N)     | Monotonic stack
//  Sort a Stack                  | O(N²)   | —       | —       | O(N)     | Recursive


// ============================================================
// PRACTICE PROBLEMS & APPLICATIONS
// ============================================================
//
//  EASY
//  1. Valid Parentheses (LeetCode 20)
//     → Stack to match open/close brackets
//  2. Implement Stack using Queues (LeetCode 225)
//     → Single queue with rotation on push
//  3. Implement Queue using Stacks (LeetCode 232)
//     → Two stacks: one for push, one for pop (lazy transfer)
//  4. Baseball Game (LeetCode 682)
//     → Simulate score operations with a stack
//  5. Remove All Adjacent Duplicates (LeetCode 1047)
//     → Push char; if top equals current char, pop both
//
//  MEDIUM
//  6. Min Stack (LeetCode 155)
//     → Two approaches: auxiliary stack O(2N), encoding trick O(N)
//  7. Next Greater Element I (LeetCode 496)
//     → Monotonic decreasing stack, right-to-left traversal
//  8. Next Greater Element II — Circular Array (LeetCode 503)
//     → Double the array traversal (0..2N) using index % N
//  9. Daily Temperatures (LeetCode 739)
//     → Next greater element variant; store INDICES on stack
// 10. Evaluate Reverse Polish Notation (LeetCode 150)
//     → Pop two operands per operator, push result back
// 11. Decode String (LeetCode 394)
//     → Two stacks: one for counts, one for current strings
// 12. Asteroid Collision (LeetCode 735)
//     → Stack simulation with positive/negative collision rules
// 13. Largest Rectangle in Histogram (LeetCode 84)
//     → Next Smaller Element left + right; O(N) with mono stack
//
//  HARD
// 14. Maximal Rectangle (LeetCode 85)
//     → Apply histogram approach row by row
// 15. Trapping Rain Water (LeetCode 42)
//     → Monotonic stack OR two-pointer; find walls on both sides
// 16. Remove K Digits (LeetCode 402)
//     → Monotonic increasing stack; remove larger digits early


// ============================================================
// KEY PATTERNS & VARIATIONS FOR REVISION
// ============================================================
//
//  PATTERN 1 — Monotonic Stack (Most important stack pattern!):
//    Maintain a stack in INCREASING or DECREASING order.
//    Traverse RIGHT TO LEFT for Next Greater/Smaller Element.
//    Traverse LEFT TO RIGHT for nearest smaller/greater to the left.
//    Each element is pushed and popped AT MOST ONCE → O(N) total.
//    Covers: Next Greater, Daily Temperatures, Largest Rectangle,
//            Trapping Rain Water, Stock Span, Asteroid Collision.
//
//  PATTERN 2 — Two-Stack Queue / One-Queue Stack:
//    Simulate one structure using another.
//    Stack using 2 queues: costly push OR costly pop (pick one).
//    Stack using 1 queue: rotate on push to keep newest at front.
//    Queue using 2 stacks: lazy transfer from push-stack to pop-stack.
//
//  PATTERN 3 — Min/Max Stack (Auxiliary Mirror):
//    Any time you need O(1) access to min/max of current stack state,
//    maintain a parallel auxiliary stack that mirrors the
//    min/max at every depth level.
//    Pair approach: store (value, currentMin) per node.
//    Encoding trick: avoids extra space using math (2x − mini).
//
//  PATTERN 4 — Shunting-Yard (Expression Conversion):
//    Operands → direct to output.
//    Operators → stack; pop higher/equal precedence first.
//    Parentheses → '(' is scope start; ')' pops until '('.
//    Handles: infix → postfix, infix → prefix (traverse right-to-left),
//             expression evaluation.
//
//  PATTERN 5 — Recursion to Simulate Stack Operations:
//    When direct stack access is restricted (e.g., sort a stack,
//    reverse a stack), use the CALL STACK as implicit extra storage.
//    sortStack: pop, sort rest, insert at correct position.
//    reverseStack: pop all to call stack, push back in reverse.
//
//  PATTERN 6 — Store INDICES (not values) on the stack:
//    When you need position information (e.g., Daily Temperatures,
//    Largest Rectangle, Trapping Rain Water), push the INDEX instead
//    of the value. Access the value via arr[index] when needed.


// ============================================================
// IMPORTANT TIPS & EDGE CASES
// ============================================================
//
//  1. Always check isEmpty before pop/peek:
//     Popping from an empty stack is undefined behaviour (throw exception
//     or return sentinel −1). In PHP, array_pop([]) returns null silently
//     without warning — always guard with isEmpty().
//
//  2. MinStack encoding trick — integer overflow risk:
//     The encoded value (2x − mini) can underflow PHP_INT_MIN on 64-bit PHP.
//     In interview/competitive contexts with 32-bit constraints,
//     2x − mini can exceed the 32-bit signed range.
//     The auxiliary stack approach is safer and just as common.
//
//  3. Right-associativity of ^ (exponentiation):
//     a^b^c = a^(b^c), NOT (a^b)^c.
//     In Shunting-Yard: use STRICTLY LESS THAN (<) instead of ≤ for ^
//     when comparing precedence — this prevents popping ^ prematurely.
//
//  4. Monotonic stack — strictly vs loosely monotonic:
//     Next Greater:  pop while top ≤ current  (strictly greater result)
//     Next Smaller:  pop while top ≥ current  (strictly smaller result)
//     For equal elements, use < or > depending on whether you want
//     the FIRST or LAST equal element to count as the answer.
//
//  5. Valid Parentheses — 3 edge cases to always test:
//     a) Extra open bracket at end   : "(("  → false (stack not empty)
//     b) Close bracket first         : ")("  → false (stack empty on close)
//     c) Mismatched types            : "([)]" → false (wrong matching)
//
//  6. PHP end() returns false on empty array (not null):
//     Always guard with empty() before calling end() to avoid
//     comparing false with integers in Min Stack or operator precedence.
//
//  7. Stack vs Queue for DFS/BFS:
//     Stack (LIFO) → DFS (go deep before wide).
//     Queue (FIFO) → BFS (go level by level).
//     In PHP, using a plain array for both: push+pop = stack;
//     array_push+array_shift = queue (but array_shift is O(N) —
//     use SplQueue for O(1) dequeue in performance-critical code).
//
//  8. Sort a Stack TC is O(N²), not O(N log N):
//     insertSorted does at most N pops and pushes per call.
//     sortStack makes N recursive calls → N × N = O(N²) total.
//     Cannot do better than O(N log N) comparisons for comparison-based sort,
//     but implementing merge/quick sort on a stack is non-trivial.

?>
