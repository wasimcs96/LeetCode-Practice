<?php
class Stack{
    private $stack = array();
    private int $top = -1;
    private int $stackSize = 0;

    public function __construct(int $stackSize = PHP_INT_MAX){
        $this->stackSize = $stackSize;
    }

    public function push($number){
        if($this->top >= $this->stackSize) throw new Exception("Stack Overflow");
        array_push($this->stack, $number);
        $this->top++;
    }

    public function pop(){
        array_pop($this->stack);
        $this->top--;
    }

    public function top(){
        return $this->top != -1 ? $this->stack[$this->top] : null;
    }

    public function getStack(){
        return $this->stack;
    }
}
//implement Stack Using Array
class implementStackWithArray{
    private $inputArr;
    private $stackObj;

    public function __construct(array $inputArr, Stack $stackObj){
        $this->inputArr = $inputArr;
        $this->stackObj = $stackObj;
        $this->createStack();
    }

    public function createStack(){
        $length = count($this->inputArr);
        for($i=0;$i<$length;$i++){
            $this->stackObj->push($this->inputArr[$i]);
        }
    }

    public function addElement(int $number){
        $this->stackObj->push($number);
    }

    public function removeElement(){
        $this->stackObj->pop();
    }

    public function getTopElement(){
        return $this->stackObj->top();
    }

    public function getStack(){
        return $this->stackObj->getStack();
    }
}

// $arr = [1,2,3,111,3,66,7];
// $stack = new Stack(10);
// $stackWithArray = new implementStackWithArray($arr, $stack);
// print_r($stackWithArray->getStack());


//implement Stack Using LinkedList
class stackNode {
    public $data;
    public $next;
    public $size;

    public function __construct($d) {
        $this->data = $d;
        $this->next = null;
    }
}
class StackLL {
    public $top;
    public $size;

    public function __construct() {
        $this->top = null;
        $this->size = 0;
    }

    public function stackPush($x) {
        $element = new stackNode($x);
        $element->next = $this->top;
        $this->top = $element;
        echo "Element pushed\n";
        $this->size++;
    }

    public function stackPop() {
        if ($this->top === null) {
            return -1;
        }
        $topData = $this->top->data;
        $temp = $this->top;
        $this->top = $this->top->next;
        unset($temp);
        $this->size--;
        return $topData;
    }

    public function stackSize() {
        return $this->size;
    }

    public function stackIsEmpty() {
        return $this->top === null;
    }

    public function stackPeek() {
        if ($this->top === null) {
            return -1;
        }
        return $this->top->data;
    }

    public function printStack() {
        $current = $this->top;
        while ($current !== null) {
            echo $current->data . " ";
            $current = $current->next;
        }
    }
}
// $s = new StackLL();
// $s->stackPush(10);
// echo "Element popped: " . $s->stackPop() . "\n";
// echo "Stack size: " . $s->stackSize() . "\n";
// echo "Stack empty or not? " . ($s->stackIsEmpty() ? "Empty" : "Not empty") . "\n";
// echo "Stack's top element: " . $s->stackPeek() . "\n";

//implement Stack Using Queue
class MyStack {
    /**
     */
    private $items;
    private $top;
    private $size;
    function __construct() {
        $this->items = array();
        $this->top = -1;
        $this->size = 0;
    }
  
    /**
     * @param Integer $x
     * @return NULL
     */
    function push($x) {
        $length = count($this->items);
        array_push($this->items, $x);  //$x = 3 then 1,2,3
        if(!$this->empty()){
            for($i = 0; $i < $length; $i++){//will perform //3,2,1
                    array_push($this->items,  $this->top());
                    array_shift($this->items);
            }
        }
        $this->size++;
    }
  
    /**
     * @return Integer
     */
    function pop() {
        if($this->empty()){
            return null;
        }
        $this->size--;
        $popItem = array_shift($this->items);
        return $popItem;
    }
  
    /**
     * @return Integer
     */
    function top() {
        if($this->empty()){
            return null;
        }
        return $this->items[0];
    }
  
    /**
     * @return Boolean
     */
    function empty() {
        return $this->size == 0;
    }
}


//  Your MyStack object will be instantiated and called as such:
//  Your MyStack object will be instantiated and called as such:
$obj = new MyStack();
$obj->push(1);
$obj->push(2);
$obj->push(3);
$obj->push(4);
echo $ret_3 = $obj->top(); echo "\n";
echo $ret_2 = $obj->pop();echo "\n";
echo$ret_2 = $obj->pop();echo "\n";
echo $ret_3 = $obj->top();echo "\n";
echo $ret_4 = $obj->empty();echo "\n";
$obj->push(10);echo "\n";
echo $ret_3 = $obj->top();echo "\n";
echo$ret_2 = $obj->pop();echo "\n";
echo$ret_2 = $obj->pop();echo "\n";
echo$ret_2 = $obj->pop();echo "\n";
echo$ret_2 = $obj->pop();echo "\n";
echo$ret_2 = $obj->pop();echo "\n";


//155. Min Stack
//Time Complexity: O(1)
//Space Complexity: O(2N)
class Pair {
    public int $x, $y;
    function __construct(int $x, int $y) {
        $this->x = $x;
        $this->y = $y;
    }
}
class MinStack {
    private $stack;

    public function __construct() {
        $this->stack = [];
    }

    public function push($x) {
        $min = empty($this->stack) ? $x : min(end($this->stack)->y, $x);
        array_push($this->stack, new Pair($x, $min));

    }

    public function pop() {
        array_pop($this->stack);
    }

    public function top() {
        return empty($stack) ? end($this->stack)->x : null;
    }

    public function getMin() {
        print_r($this->stack);
        return empty($stack) ? end($this->stack)->y : null;
    }
}
// Test the MinStack class
$minStack = new MinStack();
$minStack->push(-2);
$minStack->push(0);
$minStack->push(-3);
echo "Minimum: " . $minStack->getMin() . "\n"; // Returns -3
$minStack->pop();
echo "Top: " . $minStack->top() . "\n"; // Returns 0
echo "Minimum: " . $minStack->getMin() . "\n"; // Returns -2


// Time Complexity: O(1)
// Space Complexity: O(N)
class MinStack {
    private $st;
    private $mini;

    public function __construct() {
        $this->st = [];
        $this->mini = PHP_INT_MAX;
    }

    public function push($value) {
        $val = (int)$value;
        if (empty($this->st)) {
            $this->mini = $val;
            array_push($this->st, $val);
        } else {
            if ($val < $this->mini) {
                array_push($this->st, 2 * $val - $this->mini);
                $this->mini = $val;
            } else {
                array_push($this->st, $val);
            }
        }
    }

    public function pop() {
        if (empty($this->st)) return;

        $val = array_pop($this->st);
        if ($val < $this->mini) {
            $this->mini = 2 * $this->mini - $val;
        }
    }

    public function top() {
        $val = end($this->st);
        if ($val < $this->mini) {
            return $this->mini;
        }  
        return $val;
    }

    public function getMin() {
        return $this->mini;
    }
}