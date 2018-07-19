<?php

namespace App\Repositories;

Use App\Interfaces\DiscountInterface;
use \stdClass;

use App\Discount;
Use App\Customer;
use App\Product;

class DiscountRepository implements DiscountInterface {

    /**
     * Create a discount object
     * Check which discounts can be applied to this order, and apply them on the necessary items
     * Calculate total price
     * Returns a JSON discount object
     * @param $order
     * @return \Illuminate\Http\JsonResponse
     */
    public function calculateDiscount($order)
    {
        $discount = $this->createDiscount($order);
        $this->switchesDiscount($order, $discount);
        $this->toolsDiscount($order, $discount);
        $this->revenueDiscount($order, $discount);
        $this->calculateDiscountPrice($order, $discount);

        return response()->json($discount);
    }

    /**
     * Create an empty discount object
     * @param $order
     * @return array
     */
    private function createDiscount($order){
        $discount = array();
        $discount['applied_discounts'] = array();
        $discount['discounted_items'] = array();
        $discount['discounted_price'] =  $order['total'];
        $discount['original_price'] = $order['total'];
        return $discount;
    }

    /**
     * Calculate a new total price with all the discounted items
     * Apply any discounts that are based on the total price
     * @param $order
     * @param $discount
     */
    private function calculateDiscountPrice(&$order, &$discount)
    {
        /* Calculate the new discounted total price */
        $total = 0;
        foreach ($order['items'] as &$item) {
            if (!array_key_exists('discounted_price', $item)) {
                $item['discounted_price'] = $item['total'];
            }
            $total = $total + $item['discounted_price'];
        }
        /* Apply discounts that require the new total price */
        if($this->arraySearch($discount['applied_discounts'], 'id', 1)){
            $total = $total - (10 * $total) / 100;
        }
        $discount['discounted_price'] = $total;
        return;
    }

    /**
     * Check if a revenue discount applies to the order
     * @param $order
     * @param $discount
     */
    private function revenueDiscount(&$order, &$discount)
    {
        if (isset($order['customer-id'])) {
            $customer = Customer::find($order['customer-id']);
            if ($customer['revenue'] > 1000) {
                $discount['applied_discounts'] = $this->addAppliedDiscount($discount['applied_discounts'], 1);
            }
        }
        return;
    }

    /**
     * Check if a switches discount applies to the order
     * If it applies: Set item->discounted_price push the item in discount->discounted_items
     * If it applies: add the discount in $discount->applied_discounts
     * @param $order
     * @param $discount
     */
    private function switchesDiscount(&$order, &$discount)
    {
        if (isset($order['items']) && !empty($order['items'])) {
            foreach ($order['items'] as &$item) {
                $product = Product::find($item['product-id']);
                if ($product->category === 2 && $item['quantity'] > 5) {
                    $item['discounted_price'] = intval($item['total']) - intval($item['unit-price']);
                    array_push($discount['discounted_items'], $item);
                    $discount['applied_discounts'] = $this->addAppliedDiscount($discount['applied_discounts'], 2);
                }
            }
        }
    }

    /**
     * Check if a tools discount applies to the order
     * If it applies: Set the item->discounted_price push the item in discount->discounted_items
     * If it applies: add the discount in $discount->applied_discounts
     * @param $order
     * @param $discount
     */
    private function toolsDiscount(&$order, &$discount)
    {
        if (isset($order['items']) && !empty($order['items'])) {
            $productIds = array();
            foreach ($order['items'] as $item) {
                array_push($productIds, $item['product-id']);
            }
            $tools = Product::whereIn('id', $productIds)->where("category", "=", 1)->get();
            if($tools->count() >= 2){
                $product = $tools->where('price', '>', 0)->sortByDesc('price')->first();
                $index = array_search($this->arraySearch($order['items'], 'product-id', $product->id), $order['items']);
                if($index > -1){
                    $order['items'][$index]['discounted_price'] = $order['items'][$index]['total'] - (20 * $order['items'][$index]['total']) / 100;
                    array_push($discount['discounted_items'], $order['items'][$index]);
                    $discount['applied_discounts'] = $this->addAppliedDiscount($discount['applied_discounts'], 3);
                }
            }
        }
    }

    /**
     * Get the discount from the DB and adds it to an array
     * @param $appliedDiscounts
     * @param $discountId
     * @return mixed
     */
    private function addAppliedDiscount(&$appliedDiscounts, $discountId)
    {
        $appliedDiscount = Discount::where('id', '=', $discountId)->get(['id', 'name', 'description'])->first();
        if (isset($appliedDiscount)) {
            array_push($appliedDiscounts, $appliedDiscount->toArray());
        }
        return $appliedDiscounts;
    }

    /**
     * Search an array of objects by object[$index] value
     * @param $array
     * @param $index
     * @param $value
     * @return null
     */
    private static function arraySearch($array, $index, $value)
    {
        foreach ($array as $item) {
            if ($item{$index} == $value) {
                return $item;
            }
        }
        return null;
    }
}