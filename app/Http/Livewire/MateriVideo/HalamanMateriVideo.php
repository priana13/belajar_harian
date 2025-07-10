<?php

namespace App\Http\Livewire\MateriVideo;

use Livewire\Component;

class HalamanMateriVideo extends Component
{
    public $kode;

    public $materi;

    public $materiDetail;

    public $selectedVideo;  

    public function mount($kode){

        $this->kode = $kode;

        $this->materi = \App\Models\Materi::where('kode_materi', $kode)->first();   
        
        if($this->materi) {

            $this->materiDetail = $this->materi->materi_detail;

        } else {

            abort(404, 'Materi not found');
        }

        $this->selectedVideo = $this->materiDetail->first() ?? null;        
        
    }

    public function render()
    {
        $video_url = $this->selectedVideo->video_url; 

        return view('livewire.materi-video.halaman-materi-video')->extends('layouts.video-app')->section('content');
    }

    public function selectVideo($id)
    {
        $this->selectedVideo = $this->materiDetail->find($id);
    }

    public function sudahPaham(){

        dd('oke');

    }
}
