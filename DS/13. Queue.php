<?php
class Queue{
    private $queue = array();
    private int $start = -1;
    private int $end = -1;
    private int $queueSize = 0;
    private int $currentSize = 0;

    public function __construct(int $queueSize = PHP_INT_MAX){
        $this->queueSize = $queueSize;
    }

    public function push($number){
        if ($this->isFull()) throw new Exception("Queue Overflow");

        if ($this->currentSize == 0) $this->start = $this->end = 0;
        else $this->end = ($this->end + 1)%$this->queueSize;

        $this->queue[$this->end] = $number;
        $this->currentSize++;
    }

    public function pop(){
        if ($this->isEmpty()) throw new Exception("Queue Underflow");

        unset($this->queue[$this->start]);
        if ($this->currentSize == 1) {
            $this->start = $this->end = -1;
        }else{
            $this->start = ($this->start + 1)%$this->queueSize;
        }
        $this->currentSize--;
    }

    public function top(){
        return $this->currentSize ? $this->queue[$this->start] : null;
    }

    public function getQueue(){
        return $this->queue;
    }

    public function isFull(){
        return $this->queueSize <= count($this->queue);
    }

    public function isEmpty(){
        return $this->currentSize == 0;
    }
}
//implement Queue Using Array
class implementQueueWithArray{
    private $inputArr;
    private $queueObj;

    public function __construct(array $inputArr, Queue $queueObj){
        $this->inputArr = $inputArr;
        $this->queueObj = $queueObj;
        $this->createqueue();
    }

    public function createQueue(){
        $length = count($this->inputArr);
        for($i=0;$i<$length;$i++){
            $this->queueObj->push($this->inputArr[$i]);
        }
    }

    public function addElement(int $number){
        $this->queueObj->push($number);
    }

    public function removeElement(){
        $this->queueObj->pop();
    }

    public function getTopElement(){
        return $this->queueObj->top();
    }

    public function getQueue(){
        return $this->queueObj->getqueue();
    }
}
$arr = [1,2,3];
$queue = new Queue(3);
$queueWithArray = new implementQueueWithArray($arr, $queue);
print_r($queueWithArray->getQueue());

//implent Quese Using LL
class queueNode {
    public $data;
    public $next;
    public $size;

    public function __construct($d) {
        $this->data = $d;
        $this->next = null;
    }
}
class implementQueueUsingLL{
    private $start;
    private $end;
    private $size;

    public function __construct(){
        $this->start = null;
        $this->end = null;
        $this->size = 0;
    }

    public function push(int $number){
        $newNode = new queueNode($number);
        if($this->start == null){
            $this->start = $this->end = $newNode;
        } else {
            if($this->end != null) $this->end->next = $newNode;
            $this->end = $newNode;
        }
        $this->size++;
    }

    public function pop(){
        if($this->start == null) throw new Exception('Perform on Empty Queue');
        $temp = $this->start;
        $this->start = $this->start->next;
        unset($temp);
        $this->size--;
    }

    public function topElement(){
        if($this->start == null) throw new Exception('Perform on Empty Queue');
        return $this->start->data;
    }

    public function getQueueList(){
        if($this->start == null) throw new Exception('Perform on Empty Queue');
        $tempStart = $this->start;
        while($tempStart != null){
            echo $tempStart->data.'->';
            $tempStart = $tempStart->next;
        }
        echo 'null';
    }
}
$queueLL = new implementQueueUsingLL();
$queueLL->push(1);
$queueLL->push(2);
$queueLL->push(3);
$queueLL->push(4);
//$queueLL->getQueueList();
$queueLL->pop();
$queueLL->pop();
$queueLL->pop();
$queueLL->pop();
$queueLL->push(5);
$queueLL->push(5);
$queueLL->push(5);
$queueLL->push(6);
$queueLL->getQueueList();

//232. Implement Queue using Stacks
class MyQueue {
    /**
     */
    public $stacks = array();
    function __construct() {
        
    }
  
    /**
     * @param Integer $x
     * @return NULL
     */
    function push($x) { //add element at starting point
        // $temp = array_reverse($this->stacks);
        // $this->stacks = [];
        // array_push($this->stacks, $x);
        // $this->stacks = array_merge($this->stacks, array_reverse($temp));

        $temp = $this->stacks;
        array_unshift($temp, $x);
        $this->stacks = $temp;
    }
  
    /**
     * @return Integer
     */
    function pop() {
        $popElement = !$this->empty() ? array_pop($this->stacks) : null;
        return $popElement;
    }
  
    /**
     * @return Integer
     */
    function peek() {
        return !$this->empty() ? $this->stacks[count($this->stacks) - 1] : null;
    }
  
    /**
     * @return Boolean
     */
    function empty() {
        return count($this->stacks) == 0;
    }
}

 $obj = new MyQueue();
 $obj->push(1);
 $obj->push(2);
 $obj->push(3);
 $obj->push(4);
 print_r($obj->stacks);
 echo $ret_3 = $obj->peek();
 echo $ret_2 = $obj->pop();
 $obj->push(10);
 $ret_4 = $obj->empty();
 print_r($obj->stacks);
