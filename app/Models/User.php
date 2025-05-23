<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'pix_key',
    ];

    protected $casts = [
        'pix_key' => 'array',
    ];

    protected $hidden = [];


    public function bills()
    {
        return $this->hasMany(Bill::class);
    }
}

