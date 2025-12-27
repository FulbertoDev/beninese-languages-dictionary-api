<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PendingExpression extends Model
{
    /** @use HasFactory<\Database\Factories\PendingExpressionFactory> */
    use HasFactory;

    use HasUlids;

    protected $fillable = [
        'inFrench',
        'inFongbe',
        'inYoruba',
        'inBariba',
        'inAdja',
        'inBatonou',
        'inDendi',
        'inDitamari',
        'inFulfulde',
        'inGengbe',
        'inGungbe',
        'inYom',
    ];

    public function expression(): BelongsTo
    {
        return $this->belongsTo(Expression::class);
    }

}
