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
                $totalAmount += $this->discountPlan('count',
                    $item['quantity'], $product->price * $item['quantity']);
                $totalCount += $item['quantity'];
            }
        }
        $totalAmount = $this->discountPlan('total', $totalCount, $totalAmount);
        $data = [
            'totalAmount' => $totalAmount,
            'totalCount' => $totalCount
        ];
        return view('checkout.index', $data);
    }

    /**
     * @param string $type
     * @param int $price
     * @param int $quantity
     * @return int
     */
    private function discountPlan(string $type, int $quantity, int $price): int
    {
        $plan = [
            'count' => ['count' => 2, 'discount' => 0.9],
            'total' => ['price' => 1000, 'discount' => 0.8]
        ];

        switch ($type) {
            case 'count':
                return $quantity >= $plan[$type]['count'] ? round($price * $plan[$type]['discount']) : $price;
            case 'total':
                return $price >= $plan[$type]['price'] ? round($price * $plan[$type]['discount']) : $price;
        }

        return $price;
    }
}
