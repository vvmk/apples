<?php

/*
 * Order is just a wrapper around the required 'CartItems' 
 * parameter. It encapsulates some of the beefier logic
 * to keep the final solution clean and declarative.
 */
class Order {
    protected $items = [];
    public $total; // set to public for test integrity

    public function __construct($items) {
        $this->items = $items;
    }
    
    public function items() {
        return $this->items;
    }

    public function total() {
        if (! isset($this->total))
            $this->calcTotal();

        return $this->total;
    }

    public function addItem($item) {
        array_push($this->items, $item);
        $this->calcTotal();
    }

    private function calcTotal() {
        $newTotal = 0;

        foreach ($this->items as $i) {
            $newTotal += $i->price();
        }

        $this->total = $newTotal;
    }

    public function sortItems() {
        usort($this->items, array($this, "descending"));
    }

    private function descending($a, $b) {
        return $b->price() - $a->price();
    }
}

/*
 * CartItem represents a 'fruit basket' with only as much 
 * data as I need to complete the solution: fruits and price.
 */
class CartItem {
    
    protected $fruit = [];
    protected $price;

    public function __construct($fruit, $price) {
        $this->fruit = $fruit;
        $this->price = $price;
    }

    public function contains($target) {
        return in_array($target, $this->fruit, true);
    }

    public function price() {
        return $this->price;
    }
}

/*
 * This implementation assumes that an item which has had its price
 * discounted can not 'activate' the discount for another item. The 
 * cart items can be said to act in 'pairs'
 *
 * I should point out that, while I believe this is how a similar
 * situation would be handled in reality, this behavior was not
 * specified in the instruction document and thus could be
 * considered open to interpretation :)
 */
class Discount {
    public static function applyDiscount($items) {
        $order = new Order($items);
        $discount = 0.5;
        $discountedItem = 'apples';

        $subtotal = $order->total();
        $afterDiscount = 0;

        $order->sortItems();
        $discountActive = false;

        foreach ($order->items() as $item) {
            if ($discountActive) {
                $afterDiscount += ($item->price() * (1.0 - $discount));
                $discountActive = false;
            } else {
                $discountActive = $item->contains($discountedItem);
                $afterDiscount += $item->price();
            }
        }

        return round($subtotal - $afterDiscount, 2);
    }
}
