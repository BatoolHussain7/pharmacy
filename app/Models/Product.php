<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $fillable = [
        'product_name',
        'pharmacist_net',
        'customer_net',
        'description',
        'img',
        'company_id'
    ];
    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
