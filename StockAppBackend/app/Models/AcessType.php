<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AcessType extends Model
{
    use HasFactory;

    protected $primaryKey =  'id_access_type';
    protected $table = 'access_type';
    public $timestamps = false;
    protected $fillable = [
        'access_name',
        'access_level'
    ];

    public function users()
    {
        return $this->hasMany(User::class, 'access_id', 'id_access_type');
    }
}
