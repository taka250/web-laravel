<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $table = 'products';
protected $primaryKey = 'id';
protected $fillable = ['name', 'price', 'stock', 'admin_id'];
protected $attributes = [
    'admin_id' => 1,
];


}
