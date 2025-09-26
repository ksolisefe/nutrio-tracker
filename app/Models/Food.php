<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Food extends Model
{
    use HasFactory;

    protected $table = 'food'; // Singular table name from your migration

    protected $fillable = [
        'fdc_id', 'name', 'food_type', 'calories', 'protein', 
        'carbohydrates', 'fat', 'ingredients','brand_owner', 'category', 
        'gtin_upc', 'serving_size', 'serving_size_unit', 
        'fibre', 'sodium', 'sugars'
    ];
}