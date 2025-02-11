<?php

namespace App\View\Components\Modal;

use Illuminate\View\Component;

class ModalPopup extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */

     public $id;     
     public $default;


    public function __construct($id,$default)
    {
        //
        $this->id = $id;
        $this->default = $default;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.modal.modal-popup');
    }
}
