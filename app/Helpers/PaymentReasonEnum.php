<?php

namespace App\Helpers;

enum PaymentReasonEnum: string
{
    case SUBSCRIPTION = 'subscription';
    case GIFT = 'gift';
}

