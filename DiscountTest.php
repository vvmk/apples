<?php

require "Discount.php";

class DiscountTest extends PHPUnit\Framework\TestCase {
    public function testOrderAddItem() {
        $fruits = [
            'apples',
            'pinapples',
            'bananas',
            'tomatoes',
        ];
        $price = 30;

        $newItem = new CartItem($fruit, $price);
        $order = new Order([new Item(['dragonfruits'],25)]);

        $expected = count($order->items()) + 1;

        $order->addItem($newItem);
        $actual = count($order->items());

        $this->assertSame($expected, $actual);
    }
}
