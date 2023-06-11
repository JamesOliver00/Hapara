<?php

Class Node{
    public $value;
    public $key;
    public $next;
    public $prev;
    function __construct($key,$value){
        $this->value = $value;
        $this->key = $key;
        $this->next = NULL;
        $this->prev = NULL;
    }
    function setNext(&$node = NULL){
        $this->next=$node;
    }
    function setPrev(&$node=NULL){
        $this->prev=$node;
    }
}

Class LRUCache{
    public $head;
    public $tail;
    public $size;
    public $capacity;
    public $keysArray = [];

    function __construct(int $capacity) {
        $this->__checkCapacity($capacity);
        $this->head = NULL;
        $this->tail = NULL;
        $this->size = 0;
        $this->capacity = $capacity;
    } 

    function put(int $key, int $value) : void{
        $this->__checkKey($key);
        $this->__checkValue($value);
        #cache reached capacity - least recently used item is the tail
        if(($this->size >= $this->capacity) && $this->tail!==NULL){
            $currentTail = $this->tail;
            $currentKey = $currentTail->key;
            $newTail = $currentTail->prev;
            $this->tail=$newTail;
            $this->tail->setNext();
            unset($this->keysArray[$currentKey]);
            $this->size--;
        }
        #new element to put into cache
        $node = new Node($key,$value);
        $this->updateHead($node);
        $this->keysArray[$key] = $this->head;
        $this->size ++ ;
    }
    function updateHead(Node $newHead){
        $currentHead = $this->head;
        if($currentHead === NULL){
            $this->head = $newHead;
        }
        else{
            #initially sets the tail
            if($this->tail === NULL){
                $this->tail = $currentHead;
            }
            $this->head=$newHead;
            $newHead -> setNext($currentHead);
            $currentHead -> setPrev($newHead);
        }
        return;
    }
    function get(int $key) : int {
        $val = -1;
        if(isset($this->keysArray[$key])){
            $node = $this->keysArray[$key];
            $val = $node -> value;
            $prevNode = $node->prev;
            $nextNode = $node->next;
            #node isn't head
            if($prevNode !== NULL){
                if($nextNode === NULL ){ # $node is tail
                    #prevNode becomes new tail
                    $prevNode->setNext();
                    $this->tail = $prevNode;
                }
                else{
                    $prevNode->setNext($nextNode);
                    $nextNode->setPrev($prevNode);
                }
                $node->setnext();
                $node->setprev();
                $this->updateHead($node);
            }
        }
        return $val;
    }
    function delete(int $key) : int{
        $val = -1;
        if(isset($this->keysArray[$key])){
            $node = $this->keysArray[$key];
            $val = $node -> value;
            $prevNode = $node->prev;
            $nextNode = $node->next;

            if($prevNode === NULL){# deleting the head node
                if($nextNode === NULL){ #head is also the tail, reset
                    $this->head = NULL;
                    $this->tail = NULL;
                    $this->size = 0;
                }
                else{#next node becomes thead 
                    $nextNode->setPrev();
                    $this->head = $nextNode;
                }
            }
            elseif($nextNode === NULL){# deleting the tail node
                $prevNode->setNext();
                $this->tail = $prevNode;
            }
            else{# deleting node in middle of list
                $prevNode->setNext($nextNode);
                $nextNode->setPrev($prevNode);
            }
            unset($this->keysArray[$key]);
            $this->size --;
        }
        return $val;
    }
    function __checkCapacity(int $capacity){
        #constanit
        if( $capacity < 1 || $capacity >1000 ){
            throw new Exception("Capcitiy must be greater than or equal to 1 and less than or equal to 1000");
        }
        return;
    }
    function __checkKey(int $key) {
        #constraint
        if( $key < 0 || $key > 103 ){
           throw new Exception('Key must be greater than or equal to 0 and less than or equal to 103');
        }
        return;
    }
    function __checkValue(int $value) {
        #constraint
        if( $value < 0 || $value > 105 ){
            throw new Exception('Value must be greater than or equal to 0 and less than or equal to 105');
        }
        return;
    }
}
try{
    $cache = new LRUCache(2);
    $cache->put(1,1);
    $cache->put(2,2);
    echo $cache->get(1) . "\n";
    $cache->put(3,3);
    echo $cache->get(2) . "\n";
    $cache->put(4,4);
    echo $cache->get(1) . "\n";
    echo $cache->get(3) . "\n";
    echo $cache->get(4) . "\n";
    echo $cache->delete(3) . "\n";
    echo $cache->get(3) . "\n";
}
catch (Exception $e){
    echo "Error: " . $e->getMessage() . "<br>";
}
