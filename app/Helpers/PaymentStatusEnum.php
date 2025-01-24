<?php

namespace App\Helpers;

enum PaymentStatusEnum: string
{
    case PENDING = 'pending';
    case CONFIRMED = 'confirmed';
}
