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

        $newItem = new CartItem($fruits, $price);
        $order = new Order([new CartItem(['dragonfruits'],25)]);

        $expected = count($order->items()) + 1;

        $order->addItem($newItem);
        $actual = count($order->items());

        $this->assertEquals($expected, $actual);
    }
    
    public function testCalcTotal() {
        $price = 25;
        $item = new CartItem(['apples'], $price);
        $order = new Order([$item]);

        // check the raw property
        $this->assertNotEquals($order->total, $price);

        // now check the getter
        $this->assertEquals($order->total(), $price);
    }

    public function testSortItems() {
        $lowPrice = 1;
        $middlePrice = 500;
        $highPrice = 1000;

        $lowItem = new CartItem(['apples'], $lowPrice);
        $middleItem = new CartItem(['apples'], $middlePrice);
        $highItem = new CartItem(['apples'], $highPrice);

        $order = new Order([$lowItem, $highItem, $middleItem]);
        $expected = [
            $highItem,
            $middleItem,
            $lowItem,
        ];
        $this->assertNotEquals($expected, $order->items());

        $order->sortItems();

        $this->assertEquals($expected, $order->items());
    }

    public function testCartItemContains() {
        $inArray = 'apples';
        $notInArray = 'coconuts';

        $item = new CartItem([$inArray,'bananas'], 50);

        $this->assertTrue($item->contains($inArray));
        $this->assertFalse($item->contains($notInArray));
    }

    public function testSolutionHappy() {
        $items = [
            new CartItem(['apples'], 10),
            new CartItem(['apples'], 10),
            new CartItem(['apples'], 10),
            new CartItem(['apples'], 10),
            new CartItem(['apples'], 10),
            new CartItem(['apples'], 10),
        ];
        $subtotal = 60;
        $afterDiscount = 45;
        $expected = $subtotal - $afterDiscount;

        $this->assertEquals(
            $expected,
            Discount::applyDiscount($items)
        );
    }

}
