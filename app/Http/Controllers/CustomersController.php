<?php namespace App\Http\Controllers;

use App\Customer;
use Illuminate\Http\Request;

class CustomersController extends Controller {
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
     * Return every customer
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCustomers() {
        return response()->json(Customer::all());
    }

    /**
     * return customer associated with the id
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCustomer($id) {
        return response()->json(Customer::find($id));
    }

}
