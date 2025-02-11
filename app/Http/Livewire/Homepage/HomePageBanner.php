<?php

namespace App\Http\Livewire\Homepage;

use App\Models\Banner;
use Livewire\Component;

class HomePageBanner extends Component
{
    public function render()
    {
        return view('livewire.homepage.home-page-banner' , [
            "banners" => Banner::where('status', true)->get(),
        ]);
    }
}
