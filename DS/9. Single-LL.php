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

}

$linkListObj = new linkedList();
$linkListObj->insertNodeAtEnd("1");
$linkListObj->insertNodeAtEnd("2"); 
$linkListObj->insertNodeAtEnd("3");
$linkListObj->insertNodeAtEnd("4");
$linkListObj->insertNodeAtEnd("5");
//$linkListObj->insertNodeAtHead("4");
//$linkListObj->deleteHead();
//$linkListObj->deleteLastNode();
//$linkListObj->deleteKValueElement("4");
$linkListObj->traverse();

$linkListObj->insertNodeAtElementVal('40', '4');
// $linkListObj->insertNodeAtKthElement('178', '2');
// $linkListObj->insertNodeAtKthElement('1', '1');
// $linkListObj->insertNodeAtKthElement('1781', '3');



$linkListObj->traverse();


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