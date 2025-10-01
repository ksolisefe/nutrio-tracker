<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Food;

class FavoriteFood extends Model
{
    /** @use HasFactory<\Database\Factories\FavoriteFoodFactory> */
    use HasFactory;

    protected $table = 'favorite_food';

    protected $fillable = [
        'user_id',
        'food_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function food()
    {
        return $this->belongsTo(Food::class);
    }
}
