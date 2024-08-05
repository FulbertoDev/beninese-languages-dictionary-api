<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Release extends Model
{
    use HasFactory;
    use HasUlids;

    protected $fillable = [
        'versionCode',
        'versionName',
        'details',
    ];


    protected $casts = [
        'details' => 'json'
    ];

}
