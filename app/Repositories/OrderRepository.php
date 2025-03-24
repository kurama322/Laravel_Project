<?php

namespace App\Repositories;

use App\Enums\OrderStatusEnum;
use App\Enums\PaymentSystemEnum;
use App\Enums\TransactionStatusesEnum;
use App\Models\Order;
use App\Repositories\Contracts\OrderRepositoryContract;
use Exception;
use Gloudemans\Shoppingcart\Facades\Cart;

class OrderRepository implements Contracts\OrderRepositoryContract
{

    public function create(array $data): Order|false
    {
        $data = [
            ...$data,
            'total' => Cart::instance('cart')->total(),
            'status' => OrderStatusEnum::InProgress,
        ];

        $order = auth()->check()
            ? auth()->user()->orders()->create($data)
            : Order::create($data);

        $this->addProductsToOrder($order);

        return $order;
    }

    public function setTransaction(string $vendorOrderId, PaymentSystemEnum $paymentSystem, TransactionStatusesEnum $status): void
    {
        $order = Order::where('vendor_order_id', $vendorOrderId)->firstOrFail();

        $order->transaction()->updateOrCreate([
            'payment_system' => $paymentSystem,
            'status' => $status,
        ]);

        $order->updateOrFail([
            'status' => match ($status) {
                TransactionStatusesEnum::Cancelled => OrderStatusEnum::Cancelled,
                TransactionStatusesEnum::Success => OrderStatusEnum::Paid,
                default => OrderStatusEnum::InProgress,

            }
        ]);

    }

    protected function addProductsToOrder(Order $order)
    {
        Cart::instance('cart')->content()->each(function ($item) use ($order) {
            $product = $item->model;

            $order->products()->attach($product, [
                'quantity' => $item->qty,
                'single_price' => $product->price,
                'name' => $product->title,
            ]);

            $quantity = $product->quantity - $item->qty;

            if ($quantity < 0 || !$product->update(['quantity' => $quantity])) {
                throw  new Exception("Product [$product->title] quantity must be greater than 0");
            }


        });
    }
}
