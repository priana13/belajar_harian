<?php

namespace App\Http\Livewire\Materi;

use Livewire\Component;

class MateriVideo extends Component
{
    public $video_url;

    public function mount($video_url = null){

        $this->video_url = $video_url;
    }

    public function render()
    {
        return view('livewire.materi.materi-video');
    }
}
