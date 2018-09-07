<?php

namespace vvmk;

class Order {
    protected $items = [];
    protected $total = 0;

    public function __construct($items) {
        $this->items = $items;
    }
    
    public function items() {
        return $this->items;
    }

    public function total() {
        return $this->total;
    }

    public function addItem($item) {
        array_push($this->items, $item);
    }

    public function calcTotal() {
        $newTotal = 0;

        foreach ($this->items as $i) {
            $newTotal += $i->price();
        }

        $this->total = $newTotal;
    }

    public function sortItems() {
        usort($this->items, function($a, $b) {
            return $a->price() - $b->price();
        });
    }
}

class CartItem {
    
    protected $fruit = [];
    protected $price;

    public function __construct($fruit, $price) {
        $this->fruit = $fruit;
        $this->price = $price;
    }

    public function contains($target) {
        return nil;
    }

    public function price() {
        return $this->price;
    }

}

class Discount {
    public static function solution($items) {
        return nil;
    }
}
