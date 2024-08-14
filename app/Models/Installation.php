<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Installation extends Model
{
    use HasFactory;
    use HasUlids;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'identifier',
        'model',
        'systemName',
        'systemVersion',
        'brand',
        'hasSubscribed',
    ];


    protected $casts = [
        "hasSubscribed" => "boolean"
    ];

}
