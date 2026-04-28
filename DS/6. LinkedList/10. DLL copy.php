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
        // if ($next != null && $next instanceof Node && $next->getData() === $this->getData()) {
        //     throw new Exception('Linked nodes cannot  have themselves as next');
        // } 
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
        if($this->head == null || $this->head->next == null) return;
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
    //Delete all occurrences of a given key in a doubly linked list
    public function deleteAllKey($key){
        $currentNode = $this->head;
        while($currentNode != null){
            if($currentNode->data == $key){
                $prev = $currentNode->prev;
                $next = $currentNode->next;
                if($prev != null) $prev->next = $next;
                if($next != null) $next->prev = $prev; //if next is not null
                if($currentNode == $this->head) $this->head = $next;   //If head is to be deleted then point
            }
            $currentNode = $currentNode->next;  //Move to next node
        }
        return;
    }
    //Find pairs with given sum in Sorted doubly linked list
    public function findPairsWithSum($sum){
        //S(N) but O(N)
        // $result = array();
        // $map = array();
        // $curr = $this->head;
        // while($curr != null){
        //     $data = $curr->data;
        //     if(isset($map[$data])){
        //         array_push($result, array($map[$data], $data));
        //         unset($map[$data]);
        //     }else{
        //         $diff = $sum - $curr->data;
        //         $map[$diff] = $curr->data;
        //     }
        //     $curr = $curr->next;
        // }
        // return $result;

        //S(N) but O(N)
        // $result = array(); 
        // $map = array();
        // $curr = $this->head;
        // while ($curr != null) {
        //     $diff = $sum - $curr->data;
        //     if (isset($map[$diff])) {
        //         array_push($result, array("x" => $curr->data, "y" => $diff));
        //     }
        //     $map[$curr->data] = true;
        //     $curr = $curr->next;
        // }
        // return $result;

        // S(1) but O(N^2)
        // $result = array();
        // $curr = $this->head;
        // $low = $curr;
        // $high = $curr->next;
        // while($low != null && $high !=null){
        //     $hightmp = $hightmp = $high;;
        //     while($hightmp != null && $sum  >= $low->data + $hightmp->data){
        //         if($low->data + $hightmp->data == $sum)
        //              array_push($result, array('x' => $low->data , 'y' => $hightmp->data));

        //         $hightmp = $hightmp->next;
        //     }
        //     $low = $low->next;
        //     if($high !=null) $high = $high->next;
        // }
        // return $result;

        //if sorted DLL S(1) but O(N)
        //remebr if sorted then get random number sum use low and high pont always in array and LL also
        $result = array();
        $curr = $this->head;
        $low = $curr;

        while($curr->next != null){
            $curr = $curr->next;
        }
        $high = $curr;

        while($low != null && $high !=null && $low->data <= $high->data){
            $total = $high->data + $low->data;
            if($sum > $total){
                $low = $low->next;
            }else if($sum < $total){
                $high = $high->prev;
            }else{
                array_push($result, array('x' => $low->data , 'y' => $high->data));
                $high = $high->prev;
            }
        }
        return $result;
    }

    //Remove duplicates from a sorted doubly linked list
    public function removeDuplicates(){
        if($this->head == null) return;
        $curr = $this->head;
        $firstOccurrence = $curr;
        $lastOccurrence = $curr->next;
        while($lastOccurrence != null){
            $flag = false;
            while($lastOccurrence != null && $firstOccurrence->data == $lastOccurrence->data){
                $lastOccurrence = $lastOccurrence->next;
                $flag = true;
                //unset($lastOccurrence->prev);
            }
            if($flag){
                $firstOccurrence->next = $lastOccurrence;
                if($lastOccurrence !=null) $lastOccurrence->prev = $firstOccurrence;
            }
            if($firstOccurrence !=null) $firstOccurrence = $firstOccurrence->next;
            if($lastOccurrence !=null)  $lastOccurrence = $lastOccurrence->next;
        }
        return;
    }
}

$dLL = new DLL();
$testArray = array(1173,1535,2848,3158,4327,4941,5940,6908,7632,7734,9057,9353,9943,10863,10863,11483,11876,12537,12668,12944,14407,14562,16740,16876,17101,17547,18048,19792,20027,20832,20994,22365,22756,22926,23071,23669,24274,24681,24853,25090,25268,25864,26150,26535,26745,27050,27528,27827,28135,29458,30552,31032,31099,31229,32115,32742,32752,33806,34034,36300,36829,37820,38490,38651,38992,42272,42627,43318,43924,43951,46717,47346,48215,48585,48816,49278,49831,49943,50324,51149,51236,51628,52695,53619,54277,55795,57076,59675,60483,61946,62817,63317,64081,64991,65030,65301,66502,67499,69085,69752,70287,71513,71640,72892,73471,74608,74793,75226,75458,75671,76298,77000,77232,77277,79519,80112,80395,80630,81026,82580,83719,84507,84817,85322,85807,87126,87998,88008,88394,88455,89730,90773,91007,91194,91829,92429,93287,93458,93958,98560,98789,98862,98864,99134,99134);
$testArray = array(1,1,1,2,3,4);
$dLL->convertArrToDLL($testArray);
$dLL->removeDuplicates();

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
//$dLL->reverseNodes();
$dLL->traverse();


?>