<?php

namespace App\Livewire;

use Livewire\Component;

class Dashboard extends Component
{
    public function render()
    {
        $stats = [];

        if(auth()->user()->can('role.view')) {
            $stats['roles'] = \Spatie\Permission\Models\Role::count();
        }

        if(auth()->user()->can('car.view')) {
            $stats['cars'] = \App\Models\Car::count();
        }

        if(auth()->user()->can('user.view')) {
            $stats['users'] = \App\Models\User::count();
        }

        if(auth()->user()->can('brand.view')) {
            $stats['brands'] = \App\Models\Brand::count();
        }

        return view('livewire.dashboard', [
            'stats' => $stats
        ]);
    }
}
