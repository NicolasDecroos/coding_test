<?php

class DiscountTest extends TestCase
{
    /**
     * A basic functional test example.
     *
     * @return void
     */
    public function testToolsDiscount()
    {
        $this->json('POST', 'api/discount',
            [
                'customer-id' => '3',
                'items' => [
                    [
                        'product-id' => 'A101',
                        'quantity' => '2',
                        'unit-price' => '9.75',
                        'total' => '19.50'
                    ],
                    [
                        'product-id' => 'A102',
                        'quantity' => '1',
                        'unit-price' => '49.50',
                        'total' => '49.50'
                    ]
                ],
                'total' => '69.00'
            ])
            ->assertJson(json_encode([
                'applied_discounts' => ['id' => 3]
            ]));
    }

    public function testSwitchesDiscount()
    {
        $this->json('POST', 'api/discount',
            [
                'customer-id' => '3',
                'items' => [
                    [
                        'product-id' => 'A101',
                        'quantity' => '2',
                        'unit-price' => '9.75',
                        'total' => '19.50'
                    ],
                    [
                        'product-id' => 'A102',
                        'quantity' => '1',
                        'unit-price' => '49.50',
                        'total' => '49.50'
                    ]
                ],
                'total' => '69.00'
            ])
            ->assertJson(json_encode([
                'applied_discounts' => ['id' => 3]
            ]));
    }

    public function testCustomerDiscount()
    {
        $this->json('POST', 'api/discount',
            [
                'customer-id' => '2',
                'items' => [
                    [
                        'product-id' => 'B102',
                        'quantity' => '5',
                        'unit-price' => '4.99',
                        'total' => '24.95'
                    ]
                ],
                'total' => '24.95'
            ])
            ->assertJson(json_encode([
                'applied_discounts' => ['id' => 1]
            ]));
    }
}