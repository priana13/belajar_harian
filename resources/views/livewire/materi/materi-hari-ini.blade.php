<div>
    {{-- Do your work, then step back. --}}

  <x-FrontTopNav />

  <div class="mt-6">

    @if($jenis_konten == 'Video')

      @livewire('materi.halaman-materi-video' , ['code' => $code])

    @else 

      @livewire('materi.materi-audio' , ['code' => $code])

    @endif

  </div>




</div>
