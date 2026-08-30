<?php

class Node {
    public $val;
    public $next;

    public function __construct($val){
        $this->val = $val;
        $this->next = null;
    }
}

class LinkedList{
    public $head;

    public function __construct(){
        $this->head = null;
    }

    public function getLength($head = null): int{
        $length = 0;
        $current = $head ?? $this->head;

        while($current != null){
            $length++;
            $current = $current->next;
        }
        return $length;
    }

    public function printList() : string{
        $list = [];
        $current = $this->head;
        if($current == null) return "Empty Linklist";

        while($current != null){
            $list[] = (string) $current->val;
            $current = $current->next;
        }
        echo "Head -> ";
        return implode(' → ', $list) . " → NULL\n";
    }

    public function arrayToLinkedList(array $arr){
        $current = $this->head = new Node($arr[0]);

        for($i=1; $i<count($arr); $i++){
            $current->next = new Node($arr[$i]);
            $current = $current->next;
        }
        echo $this->printList();
    }

    public function insertAtHead(int $val): void{
        $firstNode = $this->head;
        $this->head = new Node($val);
        $this->head->next = $firstNode;
        echo $this->printList();
    }

    public function insertAtEnd(int $val): void{
        $newNode = new Node($val);
        $current = $this->head;
        $lastNode = null;

        while($current->next != null){
            $current = $lastNode = $current->next;
        }

        $lastNode->next = $newNode;
        echo $this->printList();
    }

    public function insertAtPosition($val, int $k): void
    {
        $length = $this->getLength();

        if($k > $length + 1 || $k < 1) return;

        if($k == 1) {
            $this->insertAtHead($val);
            return;
        }

        if($k == $length+1){
            $this->insertAtEnd($val);
            return;
        }

        $position = 1;
        $current = $this->head;
        while($current != null){
            if($position == $k-1) break;
            
            $current = $current->next;
            $position++;
            
        }

        $beforeNode = $current;
        $afterNode = $current->next;

        $insertedNode = new Node($val);
        $beforeNode->next = $insertedNode;
        $insertedNode->next = $afterNode;

        echo $this->printList();
    }

    public function insertBeforeValue($val, int $value): void
    {
        $length = $this->getLength();
        $current = $this->head;
        
        if($current->val == $value){
            $this->insertAtHead($val);
            return;
        }

        while($current != null){
            if($current->next->val == $value){
                $newNode = new Node($val);
                $newNode->next = $current->next;
                $current->next = $newNode;
                break;
            }
            $current = $current->next;
        }
        echo $this->printList();
    }

    public function search($val): bool{
        $current = $this->head;

        while($current != null){
            if($current->val == $val) return true;
            $current = $current->next;
        }
        return false;
    }

    public function deleteHead(){
        if($this->head == null) return;
        $this->head = $this->head->next;
        echo $this->printList();

    }

    public function deleteEnd(){
        if($this->head == null) return;
        if($this->head->next == null) {
            $this->head = null;
            return;
        }
        $current = $this->head;
        while($current->next->next != null){
            $current = $current->next;
        }
        $current->next = null;
        echo $this->printList();
    }

    public function deleteByValue($val){
        $current = $this->head;
        if($current->val == $val){
            $this->deleteHead();    
            return;
        }

        while($current != null){
            if($current->next->val == $val){
                $current->next = $current->next->next;
                break;
            }
            $current = $current->next;
        }
        return $this->printList();
    }

    function middleNode($head) {
        if($head == null || $head->next == null) return $head;
        $slowPtr = $fastPtr = $head;
        while($fastPtr != null && $fastPtr->next != null){
            $slowPtr = $slowPtr->next;
            $fastPtr = $fastPtr->next->next;
        }
        return $slowPtr;
    }

    function reversList($head = null) {
        $current = $head ?? $this->head;
        $prev = null;
        while($current != null){
            $next = $current->next;
            $current->next = $prev;
            $prev = $current;
            $current = $next;
        }
        //$this->head = $prev;  
        return $prev;     
    }

    function reverseListRecursive($head, $prev = null) {
        if($head == null) {
            return $prev;
        }
        $current = $head;
        $next = $current->next;
        $current->next = $prev;
        $prev = $current;
        return $this->reverseListRecursive($next, $prev);
    }

    function addTwoNumbers($l1, $l2) {
        $current  = $dummy = new Node(-1);
        $carry = 0;
        while($l1 !=null || $l2 !=null || $carry != 0){
            $sum = ($l1?$l1->val:0) + ($l2?$l2->val:0) + $carry;
            
            $carry = intval($sum / 10);
            $newNode = new Node($sum % 10); 
            $current->next = $newNode;
            $current = $newNode;

            $l1 = $l1 ? $l1->next : $l1;
            $l2 = $l2 ? $l2->next : $l2;
        }

        return $dummy->next;

    }

    public function oddEvenList($head){
        $oddNodes = $dummy1 = new Node(-1);
        $evenNodes = $dummy2 = new Node(-1);

        $current = $head;
        while($current != null){
            if($current->val % 2 == 0){
                $evenNodes->next = $current;
                $evenNodes = $evenNodes->next;
            }else{
                $oddNodes->next = $current;
                $oddNodes = $oddNodes->next;
            }
            $current = $current->next;
        }
        $oddNodes->next = $dummy2->next;
        $evenNodes->next = null;
        return $dummy1->next;
    }

    public function sortZeroOneTwoList(){
        $temp1 = $temp2 = $temp3 = null;

        $current = $this->head; 
        
        $zeroHead = $oneHead = $twoHead = null;
        while($current != null){
            if($current->val == 0){
                if($temp1 == null) {
                    $temp1 = $current;
                    $zeroHead = $temp1;
                }else{
                    $temp1->next = $current;
                    $temp1 = $temp1->next;
                }
            }elseif($current->val == 1){
                if($temp2 == null) {
                    $temp2 = $current;
                    $oneHead = $temp2;
                }else{
                    $temp2->next = $current;
                    $temp2 = $temp2->next;
                }
            }else{
                if($temp3 == null) {
                    $temp3 = $current;
                    $twoHead = $temp3;
                }else{
                    $temp3->next = $current;
                    $temp3 = $temp3->next;
                }
            }

            $current = $current->next;
        }

        $head = $zeroHead ?? $oneHead ?? $twoHead;
        if($head == $zeroHead)  {
            if($temp2 != null)  {
                $temp1->next = $oneHead;
                $temp2->next = $twoHead;
            }
            else {
                $temp1->next = $twoHead;
            }
        }elseif ($head == $oneHead)  {
            $temp2->next = $twoHead;
        }
        $temp3->next = null;

        
        return $head;
    }

    public function removeNthFromEnd(int $n){
        $length = $this->getLength();
        $m = $length - $n;
        if($m < 0) return;
        if($m == 0){
            $this->head = $this->head->next;
            return;
        }
        $current = $this->head;

        while($current != null){
            $m--;
            if($m == 0){
                $current->next = $current->next->next;
                break;
            }
            $current = $current->next;
        }
    }

    public function addOneToList(){
        $this->reversList();
        $current = $this->head;
        
        $carry = 1;
        while($current != null){
            $sum = $current->val + $carry;

            $carry = intval($sum / 10);
            $current->val = $sum % 10;
            // if($sum == 10){
            //     $current->val = 0;
            //     $carry = 1;
            // }else{
            //     $current->val = $sum;
            //     $carry = 0;
            // }
            if($current->next == null && $carry == 1){
                $current->next = new Node(1);
                break;
            }
            $current=$current->next;
        }
        $this->reversList();
    }

    public function deleteMiddle(){
        if($this->head == null || $this->head->next == null) {
            $this->head = null;
            return;
        }

        if($this->head->next->next == null){ 
            $this->head = $this->head->next;
            return;
        }
        $length = $this->getLength();
        $midNode = intval($length / 2);

        $current = $this->head; $counter = 1;

        while($counter < $midNode){
            $current = $current->next;
            $counter++;
        }
        $current->next = $current->next->next;
    }

    public function isPalindrome(){
        // $length = $this->getLength();
        // $current = $this->head; $stackArr = [];
        // while($current){
        //     $stackArr[] = $current->val;
        //     $current = $current->next;
        // }
        // $current = $this->head;
        // while($length >= 1){
        //     if($current->val !=  $stackArr[$length-1]) return false;
        //     $current = $current->next;
        //     $length--;
        // }
        // return true;

        $slow = $this->head;
        $fast = $this->head;

        while($fast != null && $fast->next != null){
            $slow = $slow->next;
            $fast = $fast->next->next;
        }

        $rightNewNode = $this->reversList($slow);

        $leftNode = $this->head;
        $rightNode = $rightNewNode;

        while($rightNode != null){
            if($leftNode->val != $rightNode->val) {
                $this->reversList($rightNewNode);
                return false;
            }
            $leftNode = $leftNode->next;
            $rightNode = $rightNode->next;
        }
        $this->reversList($rightNewNode);
        return true;

    }

    public function getIntersectionNode($list1, $list2){
        // $a == $b    // true  → same values/properties
        // $a === $b   // false → different Node objects
        // $a === $c   // true  → same Node object


        if($headA == null || $headB == null) return null;
        // Brute force aproch
        // $l1 = $headA;
        // $l2 = $headB;
        // while($l1 != null){
        //     $l2 = $headB;
        //     while($l2 != null){
        //         if($l1 === $l2){
        //             return $l1;
        //         }
        //         $l2 = $l2->next;
        //     }
        //     $l1 = $l1->next;
        // }
        // return null;

        //Next Approch : Shortest List 
        // $l1 = $headA;
        // $l2 = $headB;
        
        // $shorterList = $this->getLength($l1) < $this->getLength($l2) ? $l1 : $l2;
        // $longerList = $shorterList === $l1 ? $l2 : $l1;
        // $diff = abs($this->getLength($l1) - $this->getLength($l2));
        // while($diff > 0){
        //     $longerList = $longerList->next;
        //     $diff--;
        // }
        
        // while($longerList != null && $shorterList != null){
            
        //     if($shorterList === $longerList)
        //         return $shorterList;
            
        //     $longerList = $longerList->next;
        //     $shorterList = $shorterList->next;
        // }
        // return null;

        //Approach 3: Using HashSet
        // $list1 = $headA;
        // $list2 = $headB;
        // $set = [];
        // $current1 = $list1;
        // while($current1 != null){
        //     $set[spl_object_hash($current1)] = true;
        //     $current1 = $current1->next;
        // }

        // $current2 = $list2;
        // while($current2 != null){
        //     if(isset($set[spl_object_hash($current2)])){
        //         return $current2;
        //     }
        //     $set[spl_object_hash($current2)] = true;
        //     $current2 = $current2->next;
        // }
        // return null;


        //Approach 3: Using two pointers
        $p1 = $headA;
        $p2 = $headB;

        while ($p1 !== $p2) {

            $p1 = ($p1 === null) ? $headB : $p1->next;
            $p2 = ($p2 === null) ? $headA : $p2->next;
        }

        return $p1;
    }

    unction hasCycle($head) {
        //hash map approch if repeat element  found then it is a loop 
        // Approch -1 HasMap   
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


        //OR
        //Tortoise and Hare Algorithm approch
        $slowPtr = $head;
        $fastPtr = $head;
        if($fastPtr == null || $fastPtr->next == null) return false;

        while($fastPtr != null && $fastPtr->next != null){
            $slowPtr = $slowPtr->next;
            $fastPtr = $fastPtr->next->next;
            
            if($slowPtr === $fastPtr) {
                return true;
            }
        }
        return false;

        
    }



    



}

//$ll1 = new LinkedList();
//$ll1->arrayToLinkedList([1,2,3,4,5]);
//echo "\n";
// $ll1->insertAtHead(0);
// echo "\n";
// $ll1->insertAtEnd(6);
// echo "\n";
// $ll1->getLength();
// echo "\n";
// $ll1->insertAtPosition(1.5, 3);
// echo "\n";
// $ll1->insertBeforeValue(2.5, 3);
// echo "\n";
// var_dump($ll1->search(22.5));
// echo "\n";
// $ll1->deleteHead();
// echo "\n";
// $ll1->deleteEnd();
// echo "\n";
// $ll1->deleteByValue(2.5);
// echo "\n";
// $ll1->deleteByValue(5);
// echo $ll1->printList();
// echo "\n";
// echo $ll1->middleNode($ll1->head)->val;
//$ll1->head = $ll1->reverseListRecursive($ll1->head);
// $ll1->insertAtPosition(1.5, 2);
// echo $ll1->printList();

$list1 = [1,2,3,4,5]; $list2 = [1,2]; $list3 = [1,0,0,2,1,0];
// $linkedList1 = new LinkedList();
// $linkedList1->arrayToLinkedList($list1);
// $linkedList2 = new LinkedList();
// $linkedList2->arrayToLinkedList($list2);
//$linkedList2->oddEvenList($linkedList2->head);
//$linkedList3 = new LinkedList();    
// $linkedList3->arrayToLinkedList($list3);
// $linkedList3->head = $linkedList3->sortZeroOneTwoList();
// echo $linkedList3->head->val;
// echo $linkedList3->printList();
//$linkedList2->addOneToList();
//$linkedList2->deleteMiddle();
$linkedList3 = new LinkedList(); 
$l1 = $linkedList3->head = new Node(1);
$l1->next = new Node(2);
$l1->next->next = new Node(3);
$l1->next->next->next = new Node(4);
$l1->next->next->next->next = new Node(5);

$linkedList4 = new LinkedList(); 
$l2 = $linkedList4->head = new Node(1);
$l2->next = new Node(10);
// $l2->next->next = new Node(6);



// Create intersection
//$l2->next = $l1->next->next->next;

echo $linkedList3->printList();
echo $linkedList4->printList();
echo "Answer---->"; var_dump($linkedList3->test($linkedList3->head, $linkedList4->head));

//echo $linkedList3->printList();

die;
//$ll = new LinkedList();
//$ll->head = $ll->addTwoNumbers($linkedList1->head, $linkedList2->head);
//echo $ll->printList();


class DoublyNode {
    public ?DoublyNode $prev;
    public $val;
    public ?DoublyNode $next;

    public function __construct($val){
        $this->val = $val;
        $this->prev = null;
        $this->next = null;
    }
}


class DoublyLinkedList{
    public ?DoublyNode $head = null;

    public function __construct(){
        $this->head = null;
    }

    function printForward(){
        if($this->head == null) return "Empty Linklist";
        $list = [];
        $current = $this->head;
        while($current != null){
            $list[] = (string) $current->val;
            $current = $current->next;  
        }
        echo "HEAD ↔ " . implode(" ↔ ", $list) . " ↔ NULL\n";

    }

    function printBackward(){
        if($this->head == null) return "Empty Linklist";

        $current = $this->head;
        while($current->next != null){
            $current = $current->next;  
        }
        $last = $current;
        $list = [];
        while($last != null){
            $list[] = (string) $last->val;
            $last = $last->prev;  
        }
        echo "NULL ↔ " . implode(" ↔ ", $list) . " ↔ HEAD\n";

    }

    public function convertToDoublyLinkedList(array $arr){

        $current = $this->head = new DoublyNode($arr[0]);
        $counter = 1;
        while($counter < count($arr)){
            $newNode = new DoublyNode($arr[$counter]);
             $newNode->prev = $current;
             $newNode->next = null;
             $current->next = $newNode;
             $current = $newNode;
             $counter++;
        }

                // echo $this->printForward();
                // echo $this->printBackward();
    
    }

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

    public function deletionHead(): void
    {
        if($this->head == null) return;
        if($this->head->next == null) {
            $this->head = null;
            return;
        }

        $current = $this->head;
        $next = $current->next;
        $this->head = $next;
        $this->head->prev = null;

        $current->next = null;

    }

    public function deleteionTail(){
        if($this->head == null) return;
        if($this->head->next == null) {
            $this->head = null;
            return;
        }
        $current = $this->head;
        while($current->next != null){
            $current = $current->next;
        }
        $secondLast = $current->prev;
        $secondLast->next = null;

        $current->prev = null;
    }

    public function deleteAtPosition($k){
        if($this->head == null) return;
        if($k == 1) {
            $this->deletionHead();
            return;
        }

        if($k > $this->getLength()) return;

        $counter = 1;
        $current = $this->head;
        while($current != null){
            if($counter == $k){
                $prevNode = $current->prev;
                $nextNode = $current->next;

                $prevNode->next = $nextNode;
                if($nextNode != null){
                    $nextNode->prev = $prevNode;
                }
                break;
            }
            $current = $current->next;
            $counter++;
        }
    }

    public function deleteByValue($val){
        if($this->head == null) return;
        if($this->head->val == $val) {
            $this->deletionHead();
            return;
        }

        $current = $this->head;
        while($current != null){
            if($current->val == $val){
                $prevNode = $current->prev;
                $nextNode = $current->next;

                $prevNode->next = $nextNode;
                if($nextNode != null){
                    $nextNode->prev = $prevNode;
                }
                break;
            }
            $current = $current->next;
        }
    }

    public function deleteNode($node){
        $prevNode = $node->prev;
        $nextNode = $node->next;

        $prevNode->next = $nextNode;
        if($nextNode !== null){
            $nextNode->prev = $prevNode;
        }

        return;
    }

    public function insertAtHead($x){
        $newHead = new DoublyNode($x);
        $current = $this->head;
        if($current != null){
            $newHead->next = $current;
            $current->prev = $newHead;
        }
        $this->head = $newHead;
        return;
    }

    public function insertAtTail($x){
        $newNode = new DoublyNode($x);
        $current = $this->head;
        if($current == null){
            $this->head = $newNode;
            return; 
        }
        while($current->next != null)
        {
            $current = $current->next;
        }

        $newNode->prev= $current;
        $newNode->next = $current->next;
        $current->next = $newNode;

        if($newNode->next != null){
            $newNode->next->prev = $newNode;
        }
        return;
    }

    public function insertAtPosition($x, $k){
        $current = $this->head;
        if($current == null || $k == 1){
            $this->head = new DoublyNode($x);
            $this->head->next = $current;
            if($current != null){
                $current->prev = $this->head;
            }
            return;
        }

        if($k > $this->getLength() + 1) return;
        if($k == $this->getLength() + 1){
            $this->insertAtTail($x);
            return;
        }

        $counter = 1;
        while($current != null){
            if($counter == $k-1) break;
            $current = $current->next;
            $counter++;
        }

        $newNode = new DoublyNode($x);
        $newNode->next = $current->next;
        $current->next = $newNode;
        $newNode->prev = $current;
        if($newNode->next != null){
            $newNode->next->prev = $newNode;
        }

        return;
    }

    function insertNodeBeforeByReference($x, $node)
    {
        $currentNode = $node;
        if($currentNode == null){
            return;
        }
        $newNode = new DoublyNode($x);
        $newNode->next = $node;
        $newNode->prev = $node->prev;
        $newNode->prev->next = $newNode;
        $node->prev = $newNode;
        return;
    }
        
}   


//$dll = new DoublyLinkedList();
//$dll->convertToDoublyLinkedList([1,2,30,10,4,5]);
//$dll->printForward();
//$dll->deletionHead();
//$dll->deleteionTail();

//$dll->deleteAtPosition(6);
// $dll->deleteByValue(10);
// $dll->deleteByValue(30);
// $dll->deleteByValue(1);
//$dll->deleteNode($dll->head->next->next); // delete node with value 30
// $dll->insertAtHead(100);
// $dll->insertAtTail(500);
//$dll->insertAtPosition(1000, 9);
//$dll->insertNodeByReference(111, $dll->head->next->next->next);
//$dll->printForward();
//$dll->printBackward();


?>

