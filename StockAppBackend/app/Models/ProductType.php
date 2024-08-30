<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductType extends Model
{
    use HasFactory;
    protected $fillable = ['type_name'];

    public $timestamps = false;
    protected $primaryKey = 'id_type';


    public function products()
    {
        return $this->hasMany(Product::class, 'product_type', 'id_type');
    }
}
