<?php

namespace App\Helpers;

/**
 * Class MonerooHelpers.
 */
class MonerooHelpers

{
    const currency = 'XOF';
    const HASH_SECRET_NAME = 'x-moneroo-signature';
    const HASH_SECRET_VALUE = 'b7a0ea03787103a841071bc98e276982f26f0d6f';
    const paymentInitUrl = '/v1/payments/initialize';
    const paymentVerificationUrl = '/v1/payments/{paymentId}/verify';

}
