<?php

namespace App\Http\Controllers;

use App\Product;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    public function index(Request $request)
    {
        $totalAmount = 0;
        $totalCount = 0;

        if ($request->has('products')) {
            foreach ($request->input('products') as $item) {
                $product = Product::findOrFail($item['id']);
                $totalAmount += $product->price * $item['quantity'];
                $totalCount += $item['quantity'];
            }
        }
        $data = [
            'totalAmount' => $totalAmount,
            'totalCount' => $totalCount
        ];
        return view('checkout.index', $data);
    }

}
