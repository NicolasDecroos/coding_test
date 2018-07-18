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
    public function __construct(DiscountInterface $discount)
    {
        $this->discount = $discount;
    }

    /**
     * @SWG\Post(
     *   path="/api/discount",
     *   summary="Calculate discounts for an order",
     *   @SWG\Parameter(
     *      name="body",
     *      in="body",
     *      description="Order to calculate the discounts",
     *      required=true,
     *      @SWG\Schema(
     *          type="object",
     *          @SWG\Property(property="customer-id", type="string", description="Customer id"),
     *          @SWG\Property(
     *              property="items",
     *              type="array",
     *              description="Ordered items",
     *              @SWG\Items(
     *                  type="object",
     *                  @SWG\Property(property="product-id", type="string", description="Product id"),
     *                  @SWG\Property(property="quantity", type="string", description="Product quantity"),
     *                  @SWG\Property(property="unit-price", type="string", description="Product unit price"),
     *                  @SWG\Property(property="total", type="string", description="Product total price"),
     *              ),
     *          ),
     *          @SWG\Property(property="total", type="string", description="Total order price"),
     *      ),
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="Success"
     *   ),
     *   @SWG\Response(
     *     response="410",
     *     description="No order given"
     *   )
     * )
     */
    public function getDiscount(Request $request)
    {
        if (isset($request) && !empty($request->json()->all())) {
            return response()->json($this->discount->calculateDiscount($request->json()->all()))->getOriginalContent();
        } else {
            return response()->json(['error' => 'No order given'], 410);
        }
    }
}