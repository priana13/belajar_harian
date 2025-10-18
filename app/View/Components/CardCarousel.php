<?php

namespace App\View\Components;

use App\Models\Page;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class CardCarousel extends Component
{
    public $items;
    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        $this->items = Page::all();
        
        return view('components.card-carousel');
    }
}
