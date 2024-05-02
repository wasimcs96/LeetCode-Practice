<?php
class Node {
    public $data;
    public $next;

    public function __construct($data) {
        $this->data = $data;
        $this->next = null;
    }
}

function sortList($head) {
    if (!$head || !$head->next) {
        return $head;
    }

    $zeroD = new Node(0);
    $oneD = new Node(0);
    $twoD = new Node(0);
    $zero = $zeroD;
    $one = $oneD;
    $two = $twoD;

    $curr = $head;
    while ($curr) {
        if ($curr->data === 0) {
            $zero->next = $curr;
            $zero = $zero->next;
        } elseif ($curr->data === 1) {
            $one->next = $curr;
            $one = $one->next;
        } else {
            $two->next = $curr;
            $two = $two->next;
        }
        $curr = $curr->next;
    }

    $zero->next = $oneD->next ?: $twoD->next;
    $one->next = $twoD->next;
    $two->next = null;

    // Updated head
    $head = $zeroD->next;

    return $head;
}

// Helper function to print linked list
function printList($node) {
    while ($node !== null) {
        echo $node->data . " ";
        $node = $node->next;
    }
    echo "\n";
}

// Example usage
$head = new Node(1);
$head->next = new Node(2);
$head->next->next = new Node(0);
$head->next->next->next = new Node(1);

echo "Linked List Before Sorting:\n";
printList($head);

$head = sortList($head);

echo "Linked List After Sorting:\n";
printList($head);
?>
