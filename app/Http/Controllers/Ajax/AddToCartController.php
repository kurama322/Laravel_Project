<?php

namespace App\Http\Controllers\Ajax;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Http\Request;
use Throwable;

class AddToCartController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request,  Product $product)
    {


        try {

            Cart::instance('cart')->add($product)->associate(Product::class);

            return response()->json([
                'message' => 'Product added to cart successfully',
                'cart_count' => Cart::instance('cart')->countItems()
            ]);

        } catch (Throwable $th) {
            logs()->error("[AddToCartController] Failed to remove image from database {$th->getMessage()}", [
                    'image' => $product->id,
                    'exception' => $th
                ]
            );
            return response()->json([
                'message' => $th->getMessage(),
            ], 422);
        }

    }
}
