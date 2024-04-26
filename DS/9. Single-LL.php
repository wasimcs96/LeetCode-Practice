<?php

class Node{
    public $data = null;
    public $next = null;

    function __construct($data = null){
        $this->data = $data;
    }
}

class linkedList{
    public $head = null;
    public function insertNodeAtEnd($data){
        $newNode = new Node($data);
        $currentNode = $this->head;
        if($this->head == null) {
            $this->head = $newNode;
        }else{
            while($currentNode->next !== null){
                $currentNode = $currentNode->next;
            }
            $currentNode->next = $newNode;
        }
    }

    public function insertNodeAtEnd1($data, $index){
        $newNode = new Node($data);
        $currentNode = $this->head;
        $countIndex = 0; $loopStartIndex = "";
        if($this->head == null) {
            $this->head = $newNode;
        }elseif($currentNode->next == null){
            $currentNode->next = $newNode;
            $newNode->next = $currentNode;
        }else{
            while($currentNode->next !== null){ 
                if($countIndex == $index) $loopStartIndex = $currentNode;
                $currentNode = $currentNode->next;
                $countIndex++;
            }
            $currentNode->next = $newNode;
            $newNode->next = $loopStartIndex;
            //print_r($newNode);
        }
    }

    public function insertNodeAtHead($data){
        $newNode = new Node($data);
        if($this->head == null) {
            $this->head = $newNode;
        }else{
            $newNode->next = $this->head;
            $this->head = $newNode;
        }

    }

    public function insertNodeAtKthElement($data, $key){
        $length = $this->getlengthLL();
        if($length+1 >= $key && 1 <= $key){
            if($this->head == null) {
                if($key == 1) $this->head = new Node($data);
                return;
            }
            if($key == 1) {
                $temp = new Node($data);
                $temp->next = $this->head;
                $this->head = $temp;
                return;
            }
            $counter = 0; $currentNode = $this->head;

            while($currentNode != null){
                $counter++;
                if($counter == $key-1) {
                    $newNode = new Node($data);
                    $newNode->next = $currentNode->next;
                    $currentNode->next = $newNode;
                    break;
                }
                $currentNode = $currentNode->next;
            }
        }
    }

    public function insertNodeAtElementVal($data, $value){
        $length = $this->getlengthLL();
        if($this->head == null) {
            return;
        }
        if($value == $this->head->data) {
            $temp = new Node($data);
            $temp->next = $this->head;
            $this->head = $temp;
            return;
        }
        $currentNode = $this->head;
        while($currentNode != null){
            if($currentNode->next->data == $value) {
                $newNode = new Node($data);
                $newNode->next = $currentNode->next;
                $currentNode->next = $newNode;
                break;
            }
            $currentNode = $currentNode->next;
        }
        
    }

    public function traverse(){
        if($this->head != null){
            $currentNode = $this->head;
            while($currentNode){
                echo $currentNode->data."\n";
                $currentNode = $currentNode->next;
            }
        }
        echo "END";
    }

    public function convertArrToLL($arr = array()){
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
                $temp = new Node($arr[$i]);
                $currentNode->next = $temp;
                $currentNode = $temp;
            }
            $this->traverse();
        }
    }

    public function getlengthLL(){
        $count = 0;
        if($this->head !== null){
            $count = 1;
            $currentNode = $this->head->next;
            while($currentNode != null){
                $count++;
                $currentNode = $currentNode->next;
            }
            //echo "count => $count "."\n";
        }
        return $count;
    }

    public function searchValueInLL($data){
        if($this->head !== null){
            $currentNode = $this->head;
            while($currentNode != null){
                if($currentNode->data == $data) return true; 
                $currentNode = $currentNode->next;
            }
        }
        return false;
    }

    public function deleteHead(){
        if($this->head != null){
            $this->head = $this->head->next;
        }
    }

    public function deleteLastNode(){
        if($this->head != null){
            if($this->head->next == null) {
                $this->head = null; 
                return;
            }

            // $current = $preNode = $this->head;
            // while($current->next){
            //     $preNode = $current;
            //     $current = $current->next; 
            // }
            // $preNode->next = null;

            //Second Approch
            $current = $this->head;
            while($current->next->next != null){
                 $current = $current->next; 
            }
            $current->next = null;

        }
    }

    public function deleteKthNode($key){
        $listLength = $this->getlengthLL();
        if($key > $listLength) return false;

        if($this->head == null || ($this->head->next == null && $key == 1)) {
            $this->head == null;
            return;
        }
        if($key == 1){
            $this->head = $this->head->next;
            return;
        }
        // $count = 2;
        // $preNode = $this->head;  $currentNode = $this->head->next;
        // while($count < $key){
        //     $preNode = $currentNode;
        //     $currentNode = $currentNode->next;
        //     $count++;
        // }
        // $preNode->next = $currentNode->next;

        // $count = 2;
        // $currentNode = $this->head;
        // while($count < $key){
        //     $currentNode = $currentNode->next;
        //     $count++;
        // }
        // $currentNode->next = $currentNode->next->next;

        $count = 0;
        $currentNode = $this->head;  $preNode = null;
        while($currentNode != null){ 
            $count++;
            if($count == $key){
                $preNode->next = $preNode->next->next;
                break;
            }
            $preNode = $currentNode;
            $currentNode = $currentNode->next;
        }
    }

    public function deleteKValueElement($node){
        $count = 0;
        $currentNode = $this->head;  $preNode = null;
        while($currentNode != null){ 
            if($currentNode->data == $node){
                $preNode->next = $preNode->next->next;
                break;
            }
            $preNode = $currentNode;
            $currentNode = $currentNode->next;
        }
    }

    //https://leetcode.com/problems/middle-of-the-linked-list/
    public function middleOfLL(){
        if($this->head == null || $this->head->next == null) return;
        //2N approch
        // $currentNode = $this->head;
        // $length = $this->getlengthLL();
        // $midLength = floor ($length / 2);
        // $counter = 1;
        // while($counter <= $midLength){
        //     $counter++;
        //     $currentNode = $currentNode->next;
        // }
        // $this->head = $currentNode;
        // return;

        //O(N) approch
        $slowPtr = $fastPtr = $this->head;

        while($fastPtr != null && $fastPtr->next != null){
            echo $slowPtr->data ."--".$fastPtr->data."\n";
            $slowPtr = $slowPtr->next;
            $fastPtr = $fastPtr->next->next;
            
        }

        $this->head = $slowPtr;
        return;
    }

    public function reverseLL($head, $prev = null){

        // $this->head = $head;
        // if($this->head == null || $this->head->next == null) return $this->head;
        // $currentNode = $this->head; $prev = null;
        // while($currentNode != null){
        //     $nextNode = $currentNode->next;
        //     $currentNode->next = $prev;
        //     $prev = $currentNode;
        //     $currentNode = $nextNode;
        // }
        // $this->head = $prev;
        // return $this->head;

        //Recursive Approch
        if($head == null) return $prev;
      
        $currentNode = $this->head = $head;
        $nextNode = $currentNode->next;
        $currentNode->next = $prev;

        $prev = $currentNode;
        $this->head = $prev;
        
        $this->reverseLL($nextNode, $prev);
    }

    public function hasCycle($head){
        //hash map approch if repeat element  found then it is a loop 
        //OR
        //Tortoise and Hare Algorithm approch
        //spl_object_hash =>point to This function returns a unique identifier for the object
        $slowPtr = $head;
        $fastPtr = $head;
        if($fastPtr == null || $fastPtr->next == null) return false;

        while($fastPtr != null && $fastPtr->next != null){
            $slowPtr = $slowPtr->next;
            $fastPtr = $fastPtr->next->next;
            if($fastPtr != null && spl_object_hash($slowPtr) ==  spl_object_hash($fastPtr)) { 
                return true;
            }
        }
        return false;

        //Getting some issue above solution like Line 26: PHP Fatal error:  Nesting level too deep - recursive dependency? in solution.php
        //So using hasp map approch   
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
    }


    function detectCycle($head) {
        //S(N) approch
        // $visited = [];
        // $current = $head;
        // $index = 0;
        // while ($current !== null) {
        //     $hash = spl_object_hash($current);
        //     if (isset($visited[$hash])) {
        //         return $current;
        //     }
        //     $visited[$hash] = $index; 
        //     $index++;
        //     $current = $current->next;
        // }
        // return null;

        $slowPtr = $fastPtr = $head;
        if($fastPtr == null && $fastPtr->next == null) return null;
        while ($fastPtr != null && $fastPtr->next != null){
            //head 1 to slowptr distance where slowptr 3 at circle start node distance = l = distance of (slowptr 3 - fast 5)
            //fast to slow other side disctance D 5-->3
            //total circle distance l+D
            //fast cover distance to reach slow is  2D then slow cover D distance 
            //then remaining distance to reach start Node circle is L bcz total circle distance l+D=L+2D so L=D
            //so fast will meet slow after  2*D times.
            //And we know that if start from head and cover L distance then reach start node of circle
            $slowPtr = $slowPtr->next;
            $fastPtr = $fastPtr->next->next;
            if($fastPtr != null && spl_object_hash($slowPtr) ==  spl_object_hash($fastPtr)) { 
                $slowPtr = $head; 
                while(spl_object_hash($slowPtr) !=  spl_object_hash($fastPtr)) { 
                    $slowPtr = $slowPtr->next;
                    $fastPtr = $fastPtr->next;
                }
                return $slowPtr;
            }
        }
        return null;

        //To find cycle  length, then use hashmap and every node store as key and counter as value counter value will be increment by one
        //if hash value exits thne after increment of currentcounter - hashvalue , return value will be answer

        //other approch  use extra space, not allowed then just after getting collide point of slow and fast
        //and after just make fastptr move to one step and counter increase by one then it will meet again to slow then return counter is aswer 
    }

    function isPalindrome($head) {
        $str = "";
        if($head == null || $head->next == null) return true;
        $current  = $head;
        $prev = null;
        while($current != null) {
            $str .=	$current->data;
            $next = $current->next;
            $current->next = $prev;
            $prev = $current;
            $current = $next;
        }

        $str1 = "";
        $head = $prev;
        $current  = $head;
        while($current != null) {
            $str1 .=	$current->data;
            $current = $current->next;
        }
        if($str1 == $str){
            return true;
        }
        return false;
    }
}

$linkListObj = new linkedList();
$linkListObj->insertNodeAtEnd("1");
$linkListObj->insertNodeAtEnd("2"); 
// $linkListObj->insertNodeAtEnd("3");
// $linkListObj->insertNodeAtEnd("2");
// $linkListObj->insertNodeAtEnd("2");
// $linkListObj->insertNodeAtEnd("1");
//$linkListObj->insertNodeAtEnd1("1", 0);
//print_r($linkListObj->head);
var_dump($linkListObj->isPalindrome($linkListObj->head));

// $linkListObj->insertNodeAtEnd("7");
// $linkListObj->insertNodeAtEnd("8");
// $linkListObj->insertNodeAtEnd("6");
//$linkListObj->deleteHead();
//$linkListObj->deleteLastNode();
//$linkListObj->deleteKValueElement("4");
//$linkListObj->traverse();

//$linkListObj->reverseLL($linkListObj->head);
// $linkListObj->insertNodeAtKthElement('178', '2');
// $linkListObj->insertNodeAtKthElement('1', '1');
// $linkListObj->insertNodeAtKthElement('1781', '3');



//$linkListObj->traverse();


//$linkListObj->deleteKthNode(4);



//$linkListObj->traverse();
//$linkListObj->getlengthLL();
// var_dump($linkListObj->searchValueInLL("4"));


// $linkListObj1 = new linkedList();
// $linkListObj1->convertArrToLL(array(3,2,1));
// $linkListObj1->getlengthLL();
// var_dump($linkListObj1->searchValueInLL("4"));

// $linkListObj3 = new linkedList();
// var_dump($linkListObj3->searchValueInLL("4"));







?>