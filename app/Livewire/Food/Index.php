<?php

namespace App\Livewire\Food;

use Livewire\Component;
use App\Models\Food;
use Livewire\WithPagination;
use App\Models\FavoriteFood;
use Illuminate\Support\Facades\Auth;

class Index extends Component
{
    use WithPagination;

    public function addFavoriteFood(int $foodId): void
    {
        if (! Auth::check()) {
            return;
        }

        validator(['food_id' => $foodId], [
            'food_id' => ['required', 'integer', 'exists:food,id'],
        ])->validate();

        FavoriteFood::firstOrCreate([
            'user_id' => Auth::id(),
            'food_id' => $foodId,
        ]);
    }
    
    public function render()
    {
        $foods = Food::paginate(10);
        $favorite_foods = FavoriteFood::where('user_id', Auth::user()->id)->get();
        $random_foods = Food::inRandomOrder()->limit(10)->get();

        // dd($random_foods);

        return view('livewire.food.index', [
            'foods' => $foods,
            'random_foods' => $random_foods,
            'favorite_foods' => $favorite_foods,
        ]);
    }
}
