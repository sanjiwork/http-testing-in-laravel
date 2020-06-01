<?php

namespace App\Http\Controllers;

use App\Product;
use Illuminate\Http\Request;
use phpDocumentor\Reflection\Types\Integer;

class CheckoutController extends Controller
{
    public function index(Request $request)
    {
        $totalAmount = 0;
        $totalCount = 0;

        if ($request->has('products')) {
            foreach ($request->input('products') as $item) {
                $product = Product::findOrFail($item['id']);
                $totalAmount += $this->discountPlan('gte2',
                    $item['quantity'], $product->price * $item['quantity']);
                $totalCount += $item['quantity'];
            }
        }
        $totalAmount = $this->discountPlan('total1000', $totalCount, $totalAmount);
        $data = [
            'totalAmount' => $totalAmount,
            'totalCount' => $totalCount
        ];
        return view('checkout.index', $data);
    }

    /**
     * @param string $plan
     * @param int $price
     * @param int $quantity
     * @return int
     */
    private function discountPlan(string $plan, int $quantity, int $price): int
    {
        switch ($plan) {
            case 'gte2':
                return $quantity >= 2 ? round($price * 0.9) : $price;
            case 'total1000':
                return $price >= 1000 ? round($price * 0.8) : $price;
        }
        return $price;
    }
}
