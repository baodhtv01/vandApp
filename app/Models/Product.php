<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Product extends Authenticatable
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'store_id',
    ];
    public function store()
    {
        return $this->belongsTo(Store::class);
    }

}
