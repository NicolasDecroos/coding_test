<?php namespace App\Http\Controllers;

use App\Interfaces\DiscountInterface;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;


class DiscountsController extends Controller
{

    private $discount;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct( DiscountInterface $discount)
    {
        $this->discount = $discount;
    }

    /**
     * Get the order from the JSON body and deserialize it.
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getDiscount(Request $request)
    {
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

        if (isset($request) && !empty($request)) {
            $data = $request->json()->all();
            $order = $this->discount->deserializeOrder($data);

            return $this->discount->calculateDiscount($order);
        }
    }
}
