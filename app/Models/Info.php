<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Info extends Model
{
    use HasFactory;
    protected $fillable = [
        'quantity'
    ];
    public function pharmacyProduct()
    {
        return $this->belongsTo(PharmacyProduct::class);
    }
}
