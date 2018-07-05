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
        if (isset($request) && !empty($request)) {
            return $this->discount->calculateDiscount($request->json()->all());
        }
    }
}
