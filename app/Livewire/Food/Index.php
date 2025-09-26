<?php

namespace App\Livewire\Food;

use Livewire\Component;
use App\Models\Food;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;
    
    public function render()
    {
        $foods = Food::paginate(10);

        return view('livewire.food.index', [
            'foods' => $foods,
            'random_foods' => Food::inRandomOrder()->limit(10)->get(),
        ]);
    }
}
