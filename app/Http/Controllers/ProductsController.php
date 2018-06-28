<?php namespace App\Http\Controllers;

use App\Product;
use Illuminate\Http\Request;

class ProductsController extends Controller {
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
     * Return every product
     * @return \Illuminate\Http\JsonResponse
     */
    public function getProducts() {
        $products = Product::all();
        return response()->json($products);
    }

    /**
     * return product associated with the id
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getProduct($id) {
        return response()->json(Product::find($id));
    }
}
