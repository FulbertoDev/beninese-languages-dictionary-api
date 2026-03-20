<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Expression extends Model
{
    use HasFactory;

    use HasUlids;


    protected $fillable = [
        'word_id',
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


    public function word(): BelongsTo
    {
        return $this->belongsTo(Word::class);
    }
}
