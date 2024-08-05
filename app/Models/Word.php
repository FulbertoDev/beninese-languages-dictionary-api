<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Word extends Model
{
    use HasFactory;
    use HasUlids;


    protected $fillable = [
        'inFrench',
        'inFongbe',
        'inYoruba',
        'isValidated'
    ];

    protected $casts = [
        'isValidated' => 'boolean'
    ];

    public function expressions(): HasMany
    {
        return $this->hasMany(Expression::class);
    }

    public function audio(): HasOne
    {
        return $this->hasOne(Audio::class);
    }

    public function suggestion(): HasOne
    {
        return $this->hasOne(Suggestion::class);
    }

}
