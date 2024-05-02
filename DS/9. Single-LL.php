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
            //$this->traverse();
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
        // $str = "";
        // if($head == null || $head->next == null) return true;
        // $current  = $head;
        // $prev = null;
        // while($current != null) {
        //     $str .=	$current->data;
        //     $next = $current->next;
        //     $current->next = $prev;
        //     $prev = $current;
        //     $current = $next;
        // }

        // $str1 = "";
        // $head = $prev;
        // $current  = $head;
        // while($current != null) {
        //     $str1 .=	$current->data;
        //     $current = $current->next;
        // }
        // if($str1 == $str){
        //     return true;
        // }
        // return false;
        //make a stack and perform push and pop opration at last if stack is empay then retur true

        //compare half - half LL
        //get mid point and make second half reverse and compare with first half

        if($head == null || $head->next == null) return true;
        $slowPtr = $fastPtr = $head;

        while($fastPtr != null || $fastPtr->next != null){ //at this condition slowPt will be lie at midpoint of LL
            $slowPtr = $slowPtr -> next;
            $fastPtr = $fastPtr->next->next;
        }
        //reverse second half LL
        $pre = null;
        $current = $slowPtr;
        while($current != null) {
            $next = $current->next;
            $current->next = $pre;
            $pre = $current;
            $current = $next;
        }

        $preHead = $pre;
        while($preHead != null && $head != null ){
            if($preHead->data != $head->data) return false;
            $preHead = $preHead->next;
            $head = $head->next;
        }
        return true;
    }

    function removeNthFromEnd($head, $n) {
        //find length and get nth node from Head and deleete it
        //O(2L) approch
        // $getlength = $this->getlengthLL($head);
        // if ($getlength < $n) return $head;
        // if(($head == null || $head->next == null) && $n == 1) return $this->head = null;
        // $deleteAfterNthNode =  $getlength - $n;
        // if($deleteAfterNthNode == 0) {
        //     $this->head = $head->next; 
        //     return;
        // }
        // //echo $deleteAfterNthNode;
        // $counter = 1;
        // $currentNode = $head;
        // while($counter < $deleteAfterNthNode){
        //     $counter++;
        //     $currentNode = $currentNode->next;
        // }
        // $deletedNode = $currentNode->next;
        // $currentNode->next = $currentNode->next->next;
        // unset($deletedNode);
        // return $head;

        //O(L) approch
        //Maintain two pointers and update one with a delay of n steps.

        if(($head == null || $head->next == null) && $n == 1) return $this->head = null;
        $slowPtr = $head; $fastPtr = $head;
        for($i = 0; $i < $n; $i++){
            $fastPtr = $fastPtr->next;
        }
        if($fastPtr == null) return $head->next;
        while($fastPtr->next != null){
            $fastPtr = $fastPtr->next;
            $slowPtr = $slowPtr->next;
        }
        
        $deletedNode =  $slowPtr->next;
        $slowPtr->next = $slowPtr->next->next;
        
        unset($deletedNode);
        return $this->head;
    }

    function deleteMiddle($head) {
        if($head == null || $head->next == null) return $head;
        if($head->next->next == null) return $head->next = null;
        $slowPtr = $fastPtr = $head;
        $fastPtr = $fastPtr->next->next;
        while($fastPtr != null && $fastPtr->next != null) {
            $fastPtr = $fastPtr->next->next;
            $slowPtr = $slowPtr->next;
        }
        echo $slowPtr->data."->"; 
        //$slowPtr->data = $slowPtr->next->data;
        $slowPtr->next = $slowPtr->next->next;
        return $head;
        
    }

    //Sort a Linked list
    function mergeSortedLists($l1,  $l2) {
        $head1 = $l1;
        $head2 =  $l2;
        $dummy = new Node('-1');
        $curr = $dummy;
        while($head1 != null && $head2 != null) {
            if($head1->data <=  $head2->data) {
                $curr->next = $head1;
                $head1 = $head1->next;
            } else {
                $curr->next = $head2;
                $head2 = $head2->next;
            }
            $curr = $curr->next;
        }
        /* If any node is remaining then add it to the end */
        if ($head1 != null) $curr->next = $head1;
        else $curr->next = $head2;
    
        //echo $dummy->next->data. "\n";
        return $dummy->next;
    }

    function getMiddleOfLL($head){
        if($head == null) return null;
        $slowPtr = $head;
        $fastPtr = $head;
        while($fastPtr->next != null && $fastPtr->next->next != null) {
            $slowPtr = $slowPtr->next;
            $fastPtr = $fastPtr->next->next;
        }
        return $slowPtr;
    }
    function sortList($head) {
        if($head == null  || $head->next == null) return $head;
        //find middle
        $middleNode = $this->getMiddleOfLL($head);
        $headOfMiddle = $middleNode->next;
        $middleNode->next = null;
        //recursively sort two halves
        $head1 = self::sortList($head);
        $head12 = self::sortList($headOfMiddle);
        //merge sorted lists
        $sortedList = $this->mergeSortedLists($head1, $head12);
        return $sortedList;
    }

    //Sort a LL of 0's 1's and 2's by changing link
    function sortOfLLValues($head){
        if($head == null || $head->next == null) return $head;

        $currentNode = $head;

        $h1  = new Node(-1);
        $h2 = new Node(-1);
        $h3 = new Node(-1);

        $c1 = $h1; 
        $c2 = $h2; 
        $c3 = $h3;
      
        while($currentNode != null){
            $currentData =  $currentNode->data; 
            if($currentData == 0){
                    $c1->next = $currentNode;
                    $c1 = $c1->next;
            }
            else if($currentData == 1){
                $c2->next = $currentNode;
                $c2 = $c2->next;
                
            }
            else{
                $c3->next = $currentNode;
                $c3 = $c3->next;
            }
            $currentNode = $currentNode->next;
        }
       
        $c1->next = ($h2->next) ? $h2->next : $h3->next;  
        $c2->next = ($h3->next) ? $h3->next : null;  
        $c3->next = null;
        $this->head = $h1->next;  
    }

    //arrrange first all Odd number then even numbers
    //1,3,5,2,4,6,8
    function oddEvenList($head){
        if($head == null || $head->next == null) return $head;

        $currentNode = $head;

        $oddHead  = new Node(-1);
        $evenHead = new Node(-1);
       
        $odd = $oddHead; 
        $even = $evenHead; 
      
        while($currentNode != null){
            $numberIsEven =  (( $currentNode->data % 2) == 0) ? true  : false ; 
            if($numberIsEven){
                $even->next = $currentNode;
                $even = $even->next;
            }
            else{ 
                $odd->next = $currentNode;
                $odd = $odd->next;
            }
            $currentNode = $currentNode->next;
            
        }
       
        $odd->next = ($evenHead->next) ? $evenHead->next : null;  
        $even->next =  null;  
        $this->head = $oddHead->next;  
    }

    //Arrange first Odd index Node then even index nodes
    //1,3,5,2,4,6,8
    function oddEvenIndexList($head){
        // if($head == null || $head->next == null) return $head;

        // $currentNode = $head;

        // $oddHead  = new Node(-1);
        // $evenHead = new Node(-1);
       
        // $odd = $oddHead; 
        // $even = $evenHead; 
        // $countIndex = 1;
        // while($currentNode != null){
        //     $numberIsEven =  (($countIndex % 2) == 0) ? true  : false ; 
        //     if($numberIsEven){
        //         $even->next = $currentNode;
        //         $even = $even->next;
        //     }
        //     else{ 
        //         $odd->next = $currentNode;
        //         $odd = $odd->next;
        //     }
        //     $currentNode = $currentNode->next;
        //     $countIndex++;
        // }
       
        // $odd->next = ($evenHead->next) ? $evenHead->next : null;  
        // $even->next =  null;  
        // $this->head = $oddHead->next;  

        //Second Approch
        if($head == null || $head->next == null) return $head;
        $odd = $head;
        $even = $head->next;
        $evenHead = $head->next;
        while($odd->next != null && $even->next != null){
            $odd->next = $even->next;
            $even->next = $odd->next->next;
            $odd = $odd->next;
            $even = $even->next;
        }
        $odd->next = $evenHead;
        return $head;
    }

    function getIntersectionNode($headA, $headB) {
        if($headA == null || $headB == null) return null;

        $lenA = 0; //7
        $nodeA = $headA;
        while($nodeA != null) {
          $nodeA = $nodeA->next;
          $lenA++;
        }

        $lenB = 0; //5
        $nodeB = $headB;
        while($nodeB != null) {
          $nodeB = $nodeB->next;
          $lenB++;
        }

        $nodeA = $headA;
        $nodeB = $headB;
        if($lenA > $lenB){ //7-5 = 2 si higher length LL will be skip 2 steps then compare with another LL nodes
            $diff = $lenA-$lenB;
            while($diff){
                $diff--;
                $nodeA = $nodeA->next;
            }
        }else if($lenA < $lenB){
            $diff = $lenB-$lenA;
            while($diff){
                $diff--;
                $nodeB = $nodeB->next;
            }
        }
        while($nodeA != null && $nodeB != null){
            if(spl_object_hash($nodeA) == spl_object_hash($nodeB)){
                return $nodeA;
            }else{
                $nodeA = $nodeA->next;
                $nodeB = $nodeB->next;
            }
        }
        return null;
        
    }


}

$linkListObj = new linkedList();
$linkListObj->insertNodeAtEnd("1");
$linkListObj->insertNodeAtEnd("2"); 
$linkListObj->insertNodeAtEnd("3");
$linkListObj->insertNodeAtEnd("4");
// $linkListObj->insertNodeAtEnd("5"); 
// $linkListObj->insertNodeAtEnd("6");
// $linkListObj->insertNodeAtEnd("7");
// $linkListObj->insertNodeAtEnd("8");
// $linkListObj->insertNodeAtEnd("9");
// $linkListObj->insertNodeAtEnd("10");
// $linkListObj->insertNodeAtEnd("11");
$linkListObj1 = new linkedList();
$linkListObj1->insertNodeAtEnd("8");
$linkListObj1->insertNodeAtEnd("9");
$linkListObj1->insertNodeAtEnd("3");
$linkListObj->insertNodeAtEnd("11");
// $linkListObj1->mergeSortedLists($linkListObj->head, $linkListObj1->head);

//$linkListObj->head = $linkListObj->sortList($linkListObj->head);
// $linkListObj->sortOfLLValues($linkListObj->head);
$linkListObj->convertArrToLL([2,1,3,5,6,4,7]);
$linkListObj->head = $linkListObj->oddEvenIndexList($linkListObj->head);



$linkListObj->traverse();
//$linkListObj->insertNodeAtEnd1("1", 0);
//print_r($linkListObj->head);
//var_dump($linkListObj->isPalindrome($linkListObj->head));

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