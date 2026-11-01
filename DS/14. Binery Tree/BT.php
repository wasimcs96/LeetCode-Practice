<?php

class TreeNode{
    public int $val;

    public ?TreeNode $left;
    public ?TreeNode $right;

    public function __construct(int $val, ?TreeNode $left = null, ?TreeNode $right = null){
        $this->val = $val;
        $this->left = $left; 
        $this->right = $right;
    }
}

//DFS - INorder Traversal. // Left - Root - Right
function inOrderTraversalRecursive(?TreeNode $root, array &$answer){
    if($root == null) return;
    inOrderTraversalRecursive($root->left, $answer);
    $answer[] = $root->val;
    inOrderTraversalRecursive($root->right, $answer);
}

function inOrderTraversalIterative(?TreeNode $root){
    $stack =  $result = []; 

    while(true){
        if($root != null){
            array_push($stack, $root);
            $root = $root->left;
        }else{
            if(empty($stack)) break;

            $root = array_pop($stack);
            array_push($result, $root->val);

            $root= $root->right;
        }
    }

    return $result;
}

//DFS - PreOrder Traversal. - // Root - Left -  Right
function preOrderTraversalRecursive(?TreeNode $root, array &$answer){
    if($root == null) return;
    $answer[] = $root->val;
    preOrderTraversalRecursive($root->left, $answer);
    preOrderTraversalRecursive($root->right, $answer);
}

function inPreOrderTraversalIterative(TreeNode $root){
    $stack = $result = []; 
    array_push($stack, $root);

    while(count($stack) > 0){
        $root = array_pop($stack);
        array_push($result, $root->val);

        if($root->right != null){
            array_push($stack, $root->right);
        }

        if($root->left != null){
            array_push($stack, $root->left);
        }
    }
    return $result;
}

//DFS - PostOrder Traversal. - //  Left -  Right - Root
function postOrderTraversalRecursive(?TreeNode $root, array &$answer){
    if($root == null) return;
    postOrderTraversalRecursive($root->left, $answer);
    postOrderTraversalRecursive($root->right, $answer);
    $answer[] = $root->val;
}

function postOrderTraversalIterative(?TreeNode $root){
    $stack = []; $result = [];

    if ($root === null) {
        return [];
    }

    array_push($stack, $root);

    while(count($stack) > 0){
        
        $stackTopNode = array_pop($stack);
        array_push($result,  $stackTopNode->val);

        if($stackTopNode->left !== null){
            array_push($stack, $stackTopNode->left);
        }
        
        if($stackTopNode->right !== null) {
            array_push($stack, $stackTopNode->right);
        }
    }
    
    return array_reverse($result);
    
}

// Main function
function main() {
    // Creating a sample binary tree
    $root = new TreeNode(1);
    $root->left = new TreeNode(2);
    $root->right = new TreeNode(3);
    $root->left->left = new TreeNode(4);
    $root->left->right = new TreeNode(5);
    
    //DFS - Inorder Traversal
    $answer = [];
    inOrderTraversalRecursive($root, $answer);
    //$answer1 = inOrderTraversalIterative($root);
    // Displaying the inorder traversal result
    //echo "Inorder Traversal: ", implode("->", $answer) .PHP_EOL;
    //echo "Inorder Traversal: ", implode("->", $answer1) .PHP_EOL;

    //DFS - PreOrder Traversal
    $answer = [];
    preOrderTraversalRecursive($root, $answer);
    //$answer1 = inPreOrderTraversalIterative($root);
    // Displaying the preorder traversal result
    //echo "Preorder Traversal: ", implode("->", $answer) .PHP_EOL;
    //echo "Preorder Traversal: ", implode("->", $answer1) .PHP_EOL;

    //DFS - PostOrder Traversal
    $answer = [];
    //postOrderTraversalRecursive($root, $answer);
    $answer1 = postOrderTraversalIterative($root);
    // Displaying the preorder traversal result
    //echo "Postorder Traversal: ", implode("->", $answer) .PHP_EOL;
    echo "Postorder Traversal: ", implode("->", $answer1);

}
 
main();
