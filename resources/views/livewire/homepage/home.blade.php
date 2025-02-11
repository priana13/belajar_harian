
<div>

  <x-FrontTopNav />

  <p class="mt-3 prose-md text-center text-xl">Ahlan wa Sahlan</p>

  @guest
  <div class="p-3">

      {{-- <img class="mx-auto rounded-lg" src="https://www.hasmi.org/wp-content/uploads/2022/08/EKJ.png">         --}}
      
       @livewire('homepage.home-page-banner')

      <button wire:click.prevent="login" class="bg-primary-600 w-full rounded-xl p-3  text-white mt-5">Masuk</button>
      <button wire:click.prevent="register" class="bg-gray-200 w-full rounded-xl p-3  text-gray-400 mt-5">Daftar</button>
  </div>
  @else
  <div class="p-3">       
       
      @livewire('homepage.home-page-banner')
      
      @if(auth()->user()->angkatan_user()->aktif()->count() == 0)

       <p class="prose-sm mt-5 font-semibold mb-2">Materi Tersedia:</p>
       
        @foreach($angkatan as $row)     
        
        
        <?php 

          $cek_mulai_pendaftaran = strtotime( $row->mulai_pendaftaran )  <= strtotime( date('Y-m-d') ); 
          $cek_akhir_pendaftaran = strtotime( $row->akhir_pendaftaran )  >= strtotime( date('Y-m-d') );           

        ?>

         @if( $row->angkatan_user->count() < $row->kuota && $cek_mulai_pendaftaran && $cek_akhir_pendaftaran)

          <div>            
            <p>Materi: <strong>{{ $row->kode_angkatan }} -{{ $row->materi->nama_materi }}</strong></p>
            <p>Mulai Belajar: <strong>{{ date('d M Y' , strtotime($row->tanggal_mulai)) }}</strong></p>
          </div>

          <button wire:click="mendaftar({{ $row->id }})" class="bg-primary-600 w-full rounded-lg p-2  text-white font-bold mt-4 open-modal">Ikuti</button>  

        @endif 

        @endforeach 
      @else

        <div class="hidden">

          
            <p class="mb-2 mt-6 font-bold">Anda Terdaftar sebagai Peserta Aktif: </p>

              <?php 

              $angkatan_saya = auth()->user()->angkatan_user()->aktif()->get();
              
              ?>

              @if( count($angkatan_saya) > 0 )

              @foreach($angkatan_saya as $angkatan_user)
            

              <p class="my-2">
                Kode Angkatan: <span class="font-bold">{{ $angkatan_user->angkatan->materi->nama_materi }}</span>  <br>
                Mulai Pembelajaran : <span class="font-bold">{{ date('d M Y', strtotime( $angkatan_user->angkatan->tanggal_mulai )) }}</span> 
              </p>

              @endforeach

            @endif
          

        </div>
      
        
        
      @endif

      @if($list_materi) 

      <p class="prose-sm mt-5 font-semibold">Materi Hari ini</p>          

      @foreach ($list_materi as $materi)
       
        <div class="p-2 border-b-2 shadow rounded-lg my-3">
            <div class="flex justify-between items-cente">
                <a class="bg-accent text-primary pb-0.5 px-1 text-sm rounded-sm font-semibold">{{$materi->materi_detail->materi->kategori->nama_kategori}}</a>
            </div>
            <p class="text-normal font-bold pt-2 pb-1" >{{ $materi->materi_detail->judul}}</p>
            <p class="text-xs font-semibold">{{ $materi->angkatan->kode_angkatan }}: {{$materi->materi_detail->materi->nama_materi}} </p>
            <div class="flex gap-2 text-xs mt-3">
                <div id="left-column" class="flex-shrink-0 bg-gray-200 py-1 px-2 rounded-md font-semibold flex items-center justify-center">
                  {{ $materi->materi_detail->pertemuan}}
                </div>
                <div id="right-column" class="flex-grow bg-gray-200 py-1 px-2  rounded-md font-semibold">
                  {{date('d M Y', strtotime( $materi->tanggal ))}}          
                </div>
            </div>
            <button class="bg-primary-600 w-full rounded-lg p-2  text-white font-bold mt-4 open-modal" data-modal-id="myModal{{ $materi->id }}">DENGARKAN MATERI</button>
            @if($materi)         
            
              <a href="#" class="bg-white block text-center text-primary w-full rounded-lg p-2 border-2 border-primary font-bold mt-3 open-modal"  >KERJAKAN SOAL</a>

            @endif

        </div>

        <div wire:ignore>

          <x-modal.ModalPopup  id="myModal{{ $materi->id }}" default="close" materi="{{ $materi }}"></x-modal.ModalPopup>
          
        </div>

      @endforeach


      @endif 


      @if(count( $jadwal_ujian ) > 0)

        <p class="prose-sm mt-5 font-semibold">Ujian Hari ini</p>
        
        @foreach($jadwal_ujian as $ujian)
        
        <div class="border p-2 rounded-lg mt-2">
          <h2>Ujian {{ $ujian->type }} {{ ($ujian->type == 'Pekanan')? $ujian->urutan : '' }}</h2>
          <h2> {{ $ujian->angkatan->kode_angkatan }} - {{ $ujian->angkatan->materi->nama_materi }}</h2>
          <a href="{{route('kuis',['materi_id' => $ujian->angkatan->materi_id,'jadwal_id'=>$ujian->id ])}}" class="bg-white block text-center text-primary w-full rounded-lg p-2 border-2 border-primary font-bold mt-3 open-modal"  >KERJAKAN SOAL</a>
        </div>       
        
        @endforeach

      @endif
      
       <x-FrontBottomNav />
  </div>
  @endguest



  <script>


    document.addEventListener('DOMContentLoaded', function() {
        const openModalButtons = document.querySelectorAll('.open-modal');
        const closeModalButtons = document.querySelectorAll('.modal-overlay, .modal-close');
        var modal_id = "myModal";
        openModalButtons.forEach(button => {
            button.addEventListener('click', function() {
                const modalId = button.getAttribute('data-modal-id');
                const modal = document.getElementById(modalId);
                modal_id = modal;
                modal.classList.remove('hidden');
                console.log(modal_id);
            });
        });        


        document.addEventListener('click', function(event) {
          const clickedElement = event.target;
        //   console.log(clickedElement);
            if (clickedElement.classList.contains('close_modal')) {
                modal_id.classList.add('hidden'); 
            }
        }); 
        
        // let track_name = document.querySelector(".track-name");
        // let track_artist = document.querySelector(".track-artist");

        // let playpause_btn = document.querySelector(".playpause-track");
        // let next_btn = document.querySelector(".next-track");
        // let prev_btn = document.querySelector(".prev-track");

        // let seek_slider = document.querySelector(".seek_slider");
        // let volume_slider = document.querySelector(".volume_slider");
        // let curr_time = document.querySelector(".current-time");
        // let total_duration = document.querySelector(".total-duration");

        // let track_index = 0;
        // let isPlaying = false;
        // let updateTimer;

        // // Create new audio element
        // let curr_track = document.createElement('audio');

        // @if($materi_terpilih)
        // // Define the tracks that have to be played
        // let track_list = [
        
        //     {
        //       id:1,
        //       name: "{{ $materi_terpilih['materi_detail']['judul'] }}",
        //       artist: "{{$materi_terpilih['materi_detail']['materi']['nama_materi']}}",
        //       path: "{{ asset('storage/' . $materi_terpilih['materi_detail']['multimedia_url'] ) }}"
        //     },
        
        // ];

        // @endif

        // function open_modal(id){
        //   track_index = id
        //   console.log(track_list[track_index].path)
        //   loadTrack(track_index)
        //   playpauseTrack()
        // }

        // function loadTrack(track_index) {
        //   clearInterval(updateTimer);
        //   resetValues();
        //   curr_track.src = track_list[track_index].path;
        //   curr_track.load();
        //   track_name.textContent = track_list[track_index].name;
        //   track_artist.textContent = track_list[track_index].artist;
        //   updateTimer = setInterval(seekUpdate, 1000);
        //   curr_track.addEventListener("ended", pauseTrack);
        // }

        // function resetValues() {
        //   curr_time.textContent = "00:00";
        //   total_duration.textContent = "00:00";
        //   seek_slider.value = 0;
        // }

        // // Load the first track in the tracklist
        // loadTrack(track_index);

        // function playpauseTrack() {
        //   if (!isPlaying) playTrack();
        //   else pauseTrack();
        // }

        // function playTrack() {
        //   console.log('playPauseIcon'+track_index)
        //   curr_track.play();
        //   isPlaying = true;
        //   playpause_btn.innerHTML = '<i class="fa fa-pause-circle fa-5x text-primary"></i>';  

        // }

        // function pauseTrack() {
        //   curr_track.pause();
        //   isPlaying = false;
        //   playpause_btn.innerHTML = '<i class="fa fa-play-circle fa-5x text-primary"></i>';;

        // }

        // function nextTrack() {
        //   if (track_index < track_list.length - 1)
        //     track_index += 1;
        //   else track_index = 0;
        //   loadTrack(track_index);
        //   playTrack();
        // }

        // function prevTrack() {
        //   if (track_index > 0)
        //     track_index -= 1;
        //   else track_index = track_list.length;
        //   loadTrack(track_index);
        //   playTrack();
        // }

        // function seekTo() {
        //   let seekto = curr_track.duration * (seek_slider.value / 100);
        //   curr_track.currentTime = seekto;
        // }

        // function setVolume() {
        //   curr_track.volume = volume_slider.value / 100;
        // }

        // function seekUpdate() {
        //   let seekPosition = 0;

        //   if (!isNaN(curr_track.duration)) {
        //     seekPosition = curr_track.currentTime * (100 / curr_track.duration);

        //     seek_slider.value = seekPosition;

        //     let currentMinutes = Math.floor(curr_track.currentTime / 60);
        //     let currentSeconds = Math.floor(curr_track.currentTime - currentMinutes * 60);
        //     let durationMinutes = Math.floor(curr_track.duration / 60);
        //     let durationSeconds = Math.floor(curr_track.duration - durationMinutes * 60);

        //     if (currentSeconds < 10) { currentSeconds = "0" + currentSeconds; }
        //     if (durationSeconds < 10) { durationSeconds = "0" + durationSeconds; }
        //     if (currentMinutes < 10) { currentMinutes = "0" + currentMinutes; }
        //     if (durationMinutes < 10) { durationMinutes = "0" + durationMinutes; }

        //     curr_time.textContent = currentMinutes + ":" + currentSeconds;
        //     total_duration.textContent = durationMinutes + ":" + durationSeconds;
            
        //     @if($materi_terpilih)
        //     if(currentSeconds == durationSeconds-1){
              
        //         livewire.emit('absen', 
                
        //           {{$materi_terpilih['materi_detail']['id']}}
                
        //         );              

        //     }

        //     @endif



        //   }
        // }
        
        
    });

    // @if($materi_terpilih)

     


    // @endif


    // setInterval(function(){

    //   console.log('tess');

    // }, 1000);


</script>


</div>


