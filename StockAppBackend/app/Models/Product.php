<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;


    protected $fillable = [
        'product_name'
    ];

    public $timestamps = false;

    protected $primarykey = 'product_type';

    public function productTypes()
    {
        return $this->belongsTo(ProductType::class, 'product_type', 'id_type');
    }
}
