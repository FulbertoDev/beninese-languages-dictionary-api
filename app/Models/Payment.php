<?php

namespace App\Models;

use App\Helpers\PaymentStatusEnum;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;
    use HasUlids;

    protected $fillable = [
        'last_name',
        'first_name',
        'contact',
        'amount',
        'status',
        'transactionId',
        'deviceUuid',
    ];

    protected $casts = [
        "status" => PaymentStatusEnum::class
    ];


    public function installation()
    {
        return $this->belongsTo(Installation::class, 'deviceUuid', 'id');
    }
}
