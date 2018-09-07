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

    public function testHappyPath() {
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

    public function testEmptyList() {
        $items = [];

        $expected = 0;

        $this->assertEquals(
            $expected,
            Discount::applyDiscount($items)
        );
    }

    public function testNoDiscount() {
        $items = [
            new CartItem(['dragonfruit'], 10.49),
            new CartItem(['kiwis'], 10.19),
            new CartItem(['tomatoes'], 4.10),
            new CartItem(['coconuts'], 1.10),
            new CartItem(['potatoes'], 10.08),
            new CartItem(['grizzly bear'], 123.47),
        ];
        $order = new Order($items);
        $afterDiscount = $order->total;
        $expected = $order->total - $afterDiscount;

        $this->assertEquals(
            $expected,
            Discount::applyDiscount($items)
        );
    }

    public function testDecimals() {
        $items = [
            new CartItem(['oranges'], 40.99),
            new CartItem(['apples'], 134.49),
            new CartItem(['apples'], 120.01),
            new CartItem(['kiwi'], 199.33),
            new CartItem(['apples'], 12.22),
            new CartItem(['oranges'], 10.00),
        ];
        $subtotal = 517.04;
        $afterDiscount = 452.03;
        $expected = $subtotal - $afterDiscount;

        $this->assertEquals(
            $expected,
            Discount::applyDiscount($items)
        );
    }

    /*
     * This scenario will definitely break my algorithm :(
     * In descending order by price: 
     *
     * apples1
     * apples2
     * oranges1
     * oranges2
     * 
     * anything like this will break, as it is now, my algorithm will:
     * 1) activate the discount upon seeing apples1
     * 2) apply the discount to the next highest item (apples2)
     * 3) Continue to oranges1, oranges2
     * 
     * It is possible the discount from apples2 is actually going to give 
     * a higher discount than having a discount apply to two other items.
     *
     * But its a pretty big hole.
     */
    public function testBrokenScenario() {
        $items = [
            new CartItem(['apples'], 40.00),
            new CartItem(['apples'], 30.00),
            new CartItem(['oranges'], 30.00),
            new CartItem(['oranges'], 30.00),
        ];
        $subtotal = 130;
        $afterDiscount = 100;
        $expected = $subtotal - $afterDiscount;

        $this->assertEquals(
            $expected,
            Discount::applyDiscount($items)
        );
    }
}
