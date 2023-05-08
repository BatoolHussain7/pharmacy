<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class PharmacyProduct extends pivot
{
    use HasFactory;
    protected $fillable = [
    'product_id',
    'pharmacy_id',
    'customer_net'
];
    public function info()
    {
        return $this->hasMany(Info::class);
    }
}
