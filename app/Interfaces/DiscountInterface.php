<?php

namespace App\Interfaces;

interface DiscountInterface {

    public function deserializeOrder($order);
    public function calculateDiscount($order);

}