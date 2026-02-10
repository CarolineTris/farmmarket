<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]

class Listings extends Component
{
    public $showAnalytics = false;
    public function toggleAnalytics()
    {
        $this->showAnalytics = ! $this->showAnalytics;

        if ($this->showAnalytics) {
            $this->dispatch('renderChart'); 
        }
    }

    public function render()
    {
        $products = auth()->user()->products()->latest()->get();
        return view('livewire.listings', compact('products'));

    }
}
