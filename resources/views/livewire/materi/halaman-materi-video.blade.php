<div>
   

    <h3 class="text-center text-xl">Materi: {{ $pertemuan->judul  }}</h3>


    @if($pertemuan->jenis_kontent == 'Video')       

        <div class="p-2">

            <livewire:materi.materi-video video_url="{{ $pertemuan->video_url }}" />

        </div>
       

    @else

        <div class="text-center">

            <button class="bg-primary-600 rounded-lg p-2  mx-auto text-white font-bold mt-4 open-modal" data-modal-id="myModal">DENGARKAN MATERI</button> 

        </div>

                 
        
        {{-- @if($materi && $ujian_harian && $soal_harian > 0)                        
        
        <a href="{{route('kuis',['materi_id' => $materi->materi_detail->materi_id,'jadwal_id'=>$ujian_harian->id ])}}" class="bg-white block text-center text-primary w-full rounded-lg p-2 border-2 border-primary font-bold mt-3 open-modal"  >KERJAKAN SOAL</a>

        @endif --}}

    @endif

   <div class="px-3">

        <a href="{{route('kuis',['materi_id' => $pertemuan->materi_id,'jadwal_id'=>$jadwal_belajar->id ])}}" class="bg-blue-500 block text-center text-primary w-full rounded-lg p-2 border-2 border-primary font-bold mt-3 open-modal"  >KERJAKAN SOAL</a>
        
   </div>
   


</div>
