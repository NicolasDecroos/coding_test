<?php namespace App\Http\Controllers;

use \stdClass;

use App\Discount;
Use App\Customer;
use App\Product;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;


class DiscountsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the order from the JSON body and deserialize it.
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getDiscount(Request $request)
    {
        if (isset($request) && !empty($request)) {
            $data = $request->json()->all();
            $order = $this->deserializeOrder($data);
            return $this->calculateDiscount($order);
        }
    }

    /**
     * Deserialize an a json object to a usable stdClass;
     * @param $data
     * @return stdClass
     */
    private function deserializeOrder($data)
    {
        $order = new stdClass();
        $order->customer = $data['customer-id'];
        $order->total = floatval($data['total']);
        $order->items = array();
        foreach ($data['items'] as $data_item) {
            $item = new stdClass();
            $item->product_id = $data_item['product-id'];
            $item->quantity = floatval($data_item['quantity']);
            $item->unit_price = floatval($data_item['unit-price']);
            $item->discounted_price = floatval($data_item['total']);
            $item->total = floatval($data_item['total']);
            array_push($order->items, $item);
        }
        return $order;
    }

    /**
     * Create a discount object
     * Check which discounts can be applied to this order, and apply them on the necessary items
     * Calculate total price
     * Returns a JSON discount object
     * @param $order
     * @return \Illuminate\Http\JsonResponse
     */
    private function calculateDiscount($order)
    {
        $discount = new stdClass();
        $discount->applied_discounts = array();
        $discount->discounted_items = array();
        $discount->discounted_price = $order->total;
        $discount->original_price = $order->total;

        $this->switchesDiscount($order, $discount);
        $this->toolsDiscount($order, $discount);
        $this->revenueDiscount($order, $discount);
        $this->calculateDiscountPrice($order, $discount);

        return response()->json($discount);
    }

    /**
     * Calculate a new total price with all the discounted items
     * Apply any discounts that are based on the total price
     * @param $order
     * @param $discount
     */
    private function calculateDiscountPrice($order, $discount)
    {
        /* Calculate the new discounted total price */
        $total = 0;
        foreach ($order->items as $item) {
            $total = $total + $item->discounted_price;
        }
        /* Apply discounts that require the new total price */
        if($this->arraySearch($discount->applied_discounts, 'id', 1)){
            $total = $total - (10 * $total) / 100;
        }
        $discount->discounted_price = $total;
        return;
    }

    /**
     * Check if a revenue discount applies to the order
     * @param $order
     * @param $discount
     */
    private function revenueDiscount($order, $discount)
    {
        if (isset($order->customer)) {
            $customer = Customer::find($order->customer);
            if ($customer->revenue > 1000) {
                $discount->applied_discounts = $this->addAppliedDiscount($discount->applied_discounts, 1);
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
    private function switchesDiscount($order, $discount)
    {
        if (isset($order->items) && !empty($order->items)) {
            foreach ($order->items as $item) {
                $product = Product::find($item->product_id);
                if ($product->category === 2 && $item->quantity > 5) {
                    $item->discounted_price = $item->total - $item->unit_price;
                    array_push($discount->discounted_items, $item);
                    $discount->applied_discounts = $this->addAppliedDiscount($discount->applied_discounts, 2);
                }
            }
        }
        return;
    }

    /**
     * Check if a tools discount applies to the order
     * If it applies: Set the item->discounted_price push the item in discount->discounted_items
     * If it applies: add the discount in $discount->applied_discounts
     * @param $order
     * @param $discount
     */
    private function toolsDiscount($order, $discount)
    {
        if (isset($order->items) && !empty($order->items)) {
            $productIds = array();
            foreach ($order->items as $item) {
                array_push($productIds, $item->product_id);
            }
            $tools = Product::whereIn('id', $productIds)->where("category", "=", 1)->get();
           if($tools->count() >= 2){
               $product = $tools->where('price', '>', 0)->sortByDesc('price')->first();
               if(isset($item)){
                   $item = $this->arraySearch($order->items, 'product_id', $product->id);
                   $item->discounted_price = $item->total - (20 * $item->total) / 100;
                   array_push($discount->discounted_items, $item);
                   $discount->applied_discounts = $this->addAppliedDiscount($discount->applied_discounts, 3);
               }
           }
        }
        return;
    }

    /**
     * Get the discount from the DB and adds it to an array
     * @param $appliedDiscounts
     * @param $discountId
     * @return mixed
     */
    private function addAppliedDiscount($appliedDiscounts, $discountId)
    {
        $appliedDiscount = Discount::where('id', '=', $discountId)->first();
        if (isset($appliedDiscount)) {
            $discount = new stdClass();
            $discount->id = $appliedDiscount->id;
            $discount->name = $appliedDiscount->name;
            $discount->description = $appliedDiscount->description;
            array_push($appliedDiscounts, $discount);
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
            if ($item->{$index} == $value) {
                return $item;
            }
        }
        return null;
    }
}
