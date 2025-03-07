<?php

namespace App\Enums;

enum TransactionStatusesEnum:string
{

    case Success = 'success';
    case Pending = 'pending';
    case Cancelled = 'Cancelled';

}
