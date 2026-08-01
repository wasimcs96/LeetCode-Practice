<?php

class Node {
    public $data;
    public $next;

    public function __construct($data){
        $this->data = $data;
        $this->next = null;
    }
}

class LinkedList{
    public $head;

    public function __construct(){
        $this->head = null;
    }

    public function getLength(){
        $length = 0;
        $current = $this->head;

        while($current != null){
            $length++;
            $current = $current->next;
        }
        return $length;
    }

    public function printList() : string{
        $list = [];
        $current = $this->head;

        while($current != null){
            $list[] = (string) $current->data;
            $current = $current->next;
        }

        return implode(',', $list);
    }

    public function arrayToLinkedList(array $arr){
        $current = $this->head = new Node($arr[0]);

        for($i=1; $i<count($arr); $i++){
            $current->next = new Node($arr[$i]);
            $current = $current->next;
        }
        echo $this->printList();
    }

    public function insertAtHead(int $data): void{
        $firstNode = $this->head;
        $this->head = new Node($data);
        $this->head->next = $firstNode;
        echo $this->printList();
    }

    public function insertAtEnd(int $data): void{
        $newNode = new Node($data);
        $current = $this->head;
        $lastNode = null;

        while($current->next != null){
            $current = $lastNode = $current->next;
        }

        $lastNode->next = $newNode;
        echo $this->printList();
    }

    public function insertAtPosition($data, int $k): void
    {
        $length = $this->getLength();

        if($k > $length + 1 || $k < 1) return;

        if($k == 1) {
            $this->insertAtHead($data);
            return;
        }

        if($k == $length+1){
            $this->insertAtEnd($data);
            return;
        }

        $position = 1;
        $current = $this->head;
        while($current != null){
            $current = $current->next;
            $position++;
            if($position == $k-1) break;
            
        }

        $beforeNode = $current;
        $afterNode = $current->next;

        $insertedNode = new Node($data);
        $beforeNode->next = $insertedNode;
        $insertedNode->next = $afterNode;

        echo $this->printList();
    }

    public function insertBeforeValue($data, int $value): void
    {
        $length = $this->getLength();
        $current = $this->head;
        
        if($current->data == $value){
            $this->insertAtHead($data);
            return;
        }

        while($current != null){
            if($current->next->data == $value){
                $newNode = new Node($data);
                $newNode->next = $current->next;
                $current->next = $newNode;
                break;
            }
            $current = $current->next;
        }
        echo $this->printList();
    }

    public function search($data): bool{
        $current = $this->head;

        while($current != null){
            if($current->data == $data) return true;
            $current = $current->next;
        }
        return false;
    }

}

$ll1 = new LinkedList();
$ll1->arrayToLinkedList([1,2,3,4,5]);
echo "\n";
$ll1->insertAtHead(0);
echo "\n";
$ll1->insertAtEnd(6);
echo "\n";
$ll1->getLength();
echo "\n";
$ll1->insertAtPosition(1.5, 3);
echo "\n";
$ll1->insertBeforeValue(2.5, 3);
echo "\n";
var_dump($ll1->search(22.5));


?>

