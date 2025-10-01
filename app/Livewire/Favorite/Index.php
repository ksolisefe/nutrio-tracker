<?php

namespace App\Livewire\Favorite;

use Livewire\Component;
use App\Models\FavoriteFood;
use Illuminate\Support\Facades\Auth;

class Index extends Component
{
    public $favorite_foods;
    
    public function mount() {
        $this->favorite_foods = FavoriteFood::where('user_id', Auth::user()->id)->get();
    }
    
    public function render()
    {
        return view('livewire.favorite.index')
            ->layout('layouts.base')
            ->layoutData([
                'title' => 'Favorite Foods'
            ]);
    }
}
