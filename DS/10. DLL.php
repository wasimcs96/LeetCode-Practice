<?php 

class Node {
    public $data = null;
    public $prev = null;
    public $next = null;

    public function __construct($data = null, $prev = null, $next = null) {
        $this->data = $data;
        $this->setPrev($prev);
        $this->setNext($next);
    }

    public function getData() { 
        return $this->data; 
    }

    public function setPrev($prev) {
        if ($prev == null){
            return false;
        } elseif (!$prev instanceof Node) {
            throw new Exception("Cannot link non-Node element");
        } else {
            $this->prev = $prev;
            $prev->setNext($this);
        }   
    }
    
    public function getPrev() {     
        return $this->prev;     
    }

    public function setNext($next) {

        // Avoid creating a loop in the linked list.
        if ($next != null && $next instanceof Node && $next->getData() === $this->getData()) {
            throw new Exception('Linked nodes cannot  have themselves as next');
        } 
        $this->next = $next;    
    }  
        
    public function getNext()   
    {      
        return $this->next;        
    } 

}

class Stack {
    private $top;

    public function __construct(){
	$this->top = null;
    }

    /* Returns true if the stack is empty, false otherwise */
    public function isEmpty() {
	    return !isset($this->top);
    }

    /* Pushes an element onto the top of the stack */
    public function push($data){
	if (!isset($this->top)) {
	    $this->top = new Node($data);
	} else {
	    $node = new Node($data); //$node = new Node($data, null, $this->top);
	    $node->setNext($this->top);
	    $this->top = $node;
	}
    }

    /* Removes and returns the topmost element from the stack */
    public function pop() {
	if ($this->isEmpty()) {
	    throw new Exception("Stack underflow");
	}
	$res = $this->top->getData();
	$this->top = $this->top->getNext();
	return $res;
    }
    
    /* Returns the topmost element without removing it */
    public function peek() {
        if ($this->isEmpty()) {
            throw new Exception("Stack underflow");
        } 
        return $this->top->getData();
    }

    /* Returns the size of the stack */
    public function getSize() {
        $size = 0;
        $curr = $this->top;
        while (isset($curr)) {
            $size++;
            $curr = $curr->getNext();
        }
        return $size;
    }

}


class DLL{
    public $head;

    public function traverse(){
        if($this->head != null){
            $currentNode = $this->head;
            echo "NULL--> ";
            while($currentNode != null){
                echo $currentNode->data."--> ";
                $currentNode = $currentNode->next;
            }
        }
        echo "NULL";
    }

    public function convertArrToDLL($arr = array()){
        // if(!empty($arr)){
        //     foreach($arr as $data){
        //         $this->insertNodeAtEnd($data);
        //     }
        //     $this->traverse();
        // }

        if(!empty($arr)){
            $this->head = new Node($arr[0]);
            $currentNode = $this->head;
            for($i=1;$i<count($arr);$i++){
                $temp = new Node($arr[$i], $currentNode);
                $currentNode = $temp;
            }
            //$this->traverse();
        }
    }

    //Deletion
    public function deleteHead(){
        if($this->head == null || $this->head->next == null){
            $this->head = null;
        }
        if($this->head != null){
            $temp = new Node( $this->head->next->data, null, $this->head->next->next);
            $this->head->next = null;
            $this->head = $temp;
        }
    }

    public function deleteTail(){
        if($this->head != null){
            $currentNode = $this->head;
            if($currentNode->next == null){
                $this->head = null;
                return;
            }
            while($currentNode->next != null){
                if($currentNode->next->next == null){
                    $currentNode->next->prev = null;
                    $currentNode->next = null;
                    unset($currentNode->next->next);
                    break;
                }
                $currentNode = $currentNode->next;
            }
        }
    }

    public function kthElementFromDLL($key){
        if ($this->head == NULL) {
            return;
        }

        // if($key == 1){
        //     $this->head->next->prev = null;
        //     $this->head = $this->head->next;
        //     return;
        // }
        // $currentNode = $this->head;    
        // $count = 1;
        // while($currentNode != null){
        //     $count++;
        //     if($count == $key){
        //         $currentNode->next = $currentNode->next->next;
        //         $currentNode->next->next->prev = $currentNode;
        //     }
        //     $currentNode = $currentNode->next;
        // }

        $currentNode = $this->head;    
        $count = 0;
        while($currentNode != null){
            $count++;
            if($count == $key){
               break;
            }
            $currentNode = $currentNode->next;
        }

        $prev = $currentNode->prev;
        $next = $currentNode->next;

        if($prev == null && $next == null){
            $this->head = null;
        }elseif($prev == null){
            $this->deleteHead();return;
        }elseif($next == null){
            $this->deleteTail();return;
        }else{
            $prev->next = $next;
            $next->prev = $prev;
            return;
        }
    }

    public function kValueElementFromDLL($key){
        if ($this->head == NULL) {
            return;
        }

        // if($key == 1){
        //     $this->head->next->prev = null;
        //     $this->head = $this->head->next;
        //     return;
        // }
        // $currentNode = $this->head;    
        // $count = 1;
        // while($currentNode != null){
        //     $count++;
        //     if($count == $key){
        //         $currentNode->next = $currentNode->next->next;
        //         $currentNode->next->next->prev = $currentNode;
        //     }
        //     $currentNode = $currentNode->next;
        // }

        $currentNode = $this->head;    
        while($currentNode != null){
            if($currentNode->data == $key){
                $prev = $currentNode->prev;
                $next = $currentNode->next;
        
                if($prev == null && $next == null){
                    $this->head = null;
                }elseif($prev == null){
                    $this->deleteHead();return;
                }elseif($next == null){
                    $this->deleteTail();return;
                }else{
                    $prev->next = $next;
                    $next->prev = $prev;
                    return;
                }
                break;
            }
            $currentNode = $currentNode->next;
        }

        
    }

    //insertion
    public function validKey($key){
        $currentNode = $this->head;
        $countNode = 0;
        while($currentNode != null){
            $countNode++;
            $currentNode = $currentNode->next;
        }
        if($key >= 1 && $key <= $countNode){
            return $countNode;
        }else{
            return false;
        }
    }

    public function addNodeBeforeHead($data){
        $newNode = new Node($data, null, $this->head);
        if($this->head == null){
            $this->head->data = $data;
        }else{
            $this->head->pre = $newNode;
            $this->head = $newNode;
        }
        return;
    }

    public function addNodeAfterHead($data){
        if($this->head == null){
            $newNode = new Node($data);
            $this->head = $newNode;
        }else{
            $newNode = new Node($data, $this->head, $this->head->next);
            $this->head->next = $newNode;
        }
        return;
    }

    public function addNodeAfterTail($data){
        if($this->head == null) {
            $newNode = new Node($data);
            $this->head = $newNode;
        }else{
            $currentNode = $this->head;
            while($currentNode->next != null){
                $currentNode = $currentNode->next;
            }
            $newNode = new Node($data, $currentNode);
            $currentNode->next = $newNode;
        }
        return;
    }

    public function addNodeBeforeTail($data){
        if($this->head == null) {
            $newNode = new Node($data);
            $this->head = $newNode;
        }else{
            $currentNode = $this->head;
            if($currentNode->next == null){
                $newNode = new Node($data, null, $currentNode);
                $currentNode->prev = $newNode;
                $this->head = $newNode;
                return;
            }
            while($currentNode->next->next != null){
                $currentNode = $currentNode->next;
            }
            $tailNodeNode = $currentNode->next;
            $newNode = new Node($data, $currentNode, $tailNodeNode);
            $tailNodeNode->prev = $newNode;
            $currentNode->next = $newNode;
        }
        return;
    }

    public function addNodeAfterKthNode($data, $key){
        // check if the key is valid or not. If it's greater than number of nodes then throw an exception
        if(!$this->validKey($key)) return;

        if($this->head == null) return;

        $currentCount = 0;
        $currentNode = $this->head;
        while($currentNode != null){
            $currentCount++;
            if($currentCount == $key){
                break;
            }
            $currentNode = $currentNode->next;
        }
        $tempNode = new Node($data, $currentNode, $currentNode->next);
        $currentNode->next = $currentNode->next->prev = $tempNode;
        return;
    }

    public function addNodeBeforeKthNode($data, $key){
        if(!$this->validKey($key)) return;
        if($key == 1) {
            $this->addNodeBeforeHead($data); return;
        }elseif($this->validKey($key) == $key){
            $this->addNodeBeforeTail($data);
        }else{
            $currentNode = $this->head;
            $beforeKthNode = 0;
            while($currentNode != null){
                $beforeKthNode++;
                if($beforeKthNode == $key-1){
                    break;
                }
                $currentNode = $currentNode->next;
            }
            $prevNode = $currentNode;
            $nextNode = $currentNode->next;
            $newAddedNode = new Node($data, $prevNode, $nextNode);
            $prevNode->next = $nextNode->prev = $newAddedNode;
        }
    }

    public function addNodeBeforeNodeValue($data, $key){
        if($key == $this->head->data) {
            $this->addNodeBeforeHead($data); return;
        }else{
            $currentNode = $this->head;
            while($currentNode != null){
                if($currentNode->data == $key){
                    $nextNode = $currentNode;
                    $prevNode = $currentNode->prev;
                    $newAddedNode = new Node($data, $prevNode, $nextNode);
                    $prevNode->next = $nextNode->prev = $newAddedNode;
                    break;
                }
                $currentNode = $currentNode->next;
            }
            return;
        }
    }

    public function addNodeAftereNodeValue($data, $key){
        if($key == $this->head->data) {
            $this->addNodeAfterHead($data); return;
        }else{
            $currentNode = $this->head;
            while($currentNode != null){
                if($currentNode->data == $key){
                    $prevNode = $currentNode;
                    $nextNode = $currentNode->next;
                    $newAddedNode = new Node($data, $prevNode, $nextNode);
                    $prevNode->next = $newAddedNode;
                    if($nextNode != null) $nextNode->prev = $newAddedNode;
                    break;
                }
                $currentNode = $currentNode->next;
            }
            return;
        }
    }

    //Revrse
    public function reverseNodes(){
        //2N approch
        // if($this->head == null) return;
        // $stack = new Stack();
        // $currentNode = $this->head;
        // while($currentNode != null){
        //     $stack->push($currentNode->data);
        //     $currentNode = $currentNode->next;
        // }
        // $currentNode = $this->head;
        // while($currentNode != null){
        //     $temp = $stack->pop();
        //     $currentNode->data = $temp;
        //     $currentNode = $currentNode->next;
        // }

        //Optimize Approch
        if($this->head == null) return;
        $currentNode = $this->head;
        while($currentNode != null){
            $prev = $currentNode->prev;
            $next = $currentNode->next;
            $currentNode->next = $prev;
            $currentNode->prev = $next;
            if($next == null) $this->head = $currentNode; //reach tail Node ans make it head
            $currentNode = $next;
        }
        return;
    }
}

$dLL = new DLL();
$testArray = array(1,2,3,4,5);
$dLL->convertArrToDLL($testArray);
// $dLL->deleteHead();
// $dLL->deleteHead();
//$dLL->deleteTail();
//$dLL->kthElementFromDLL(5);
//$dLL->kValueElementFromDLL(6);
//$dLL->addNodeBeforeHead(0);
//$dLL->addNodeAfterHead(100);
 //$dLL->addNodeAfterTail(7);
//$dLL->addNodeAftereNodeValue(4.5, 4);



//echo $dLL->head->data;
$dLL->reverseNodes();




$dLL->traverse();


?>