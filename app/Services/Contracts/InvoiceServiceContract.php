<?php

namespace App\Services\Contracts;

use App\Models\Order;
use LaravelDaily\Invoices\Invoice;

interface InvoiceServiceContract
{
    public static function generate(Order $order):Invoice;
}
