
<div>
  @push('head')
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css" />
  <style>
      .swiper {
      width: 100%;
      height: 100%;
      }

      .swiper-slide {
      text-align: center;
      font-size: 18px;
      background: #fff;
      display: flex;
      justify-content: center;
      align-items: center;
      }

      .swiper-slide img {
      display: block;
      width: 100%;
      /* height: 300px; */
      object-fit: cover;
      border-radius: 0.75rem;
      }

      .swiper-pagination-bullet {
          height: 10px;
          width: 10px;
          border-radius: 24px;
      }

      .swiper-pagination-bullet-active {
          height: 10px;
          width: 30px;
          border-radius: 24px;
          background-color: #41B02F;
      }

      /* radio */

      input[type="radio"]:checked + label span {
          background-color: #41B02F; 
          box-shadow: 0px 0px 0px 2px white inset;
      }

      input[type="radio"]:checked + label{
          color: #41B02F; 
      }
  </style>

<style>
 

 .details {
   display: flex;
   align-items: center;
   flex-direction: column;
   justify-content: center;
 }



 .buttons {
   display: flex;
   flex-direction: row;
   align-items: center;
 }

 .playpause-track, .prev-track, .next-track {
   padding: 25px;
   opacity: 0.8;

   /* Smoothly transition the opacity */
   transition: opacity .2s;
 }

 .playpause-track:hover, .prev-track:hover, .next-track:hover {
   opacity: 1.0;
 }

 .slider_container {
   width: 100%;
   max-width: 400px;
   display: flex;
   justify-content: center;
   align-items: center;
 }

 /* Modify the appearance of the slider */
 .seek_slider, .volume_slider {
   -webkit-appearance: none;
   -moz-appearance: none;
   appearance: none;
   height: 5px;
   background: #0E6400;
 }

 /* Modify the appearance of the slider thumb */
 .seek_slider::-webkit-slider-thumb, .volume_slider::-webkit-slider-thumb {
   -webkit-appearance: none;
   -moz-appearance: none;
   appearance: none;
   width: 15px;
   height: 15px;
   background: #41B02F;
   cursor: pointer;
   border-radius: 50%;
 }

 .seek_slider:hover, .volume_slider:hover {
   opacity: 1.0;
 }

 .seek_slider {
   width: 100%;
 }

 .volume_slider {
   width: 30%;
 }

 .current-time, .total-duration {
   padding: 10px;
 }

 i.fa-volume-down, i.fa-volume-up {
   padding: 10px;
 }

 i.fa-play-circle, i.fa-pause-circle, i.fa-step-forward, i.fa-step-backward {
   cursor: pointer;
 }
 </style>
  @endpush

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

      {{-- {{ dd( $angkatan ) }} --}}
      
      @if(auth()->user()->angkatan_user()->aktif()->count() == 0)

       <p class="prose-sm mt-5 font-semibold mb-2 text-xl font-bold">Materi Tersedia:</p>

        @foreach($angkatan as $row)     
        
        
        <?php 

          $cek_mulai_pendaftaran = strtotime( $row->mulai_pendaftaran )  <= strtotime( date('Y-m-d') ); 
          $cek_akhir_pendaftaran = strtotime( $row->akhir_pendaftaran )  >= strtotime( date('Y-m-d') );           

        ?>

         @if( $row->angkatan_user->count() < $row->kuota && $cek_mulai_pendaftaran )


          <div class="mb-4 card shadow p-2 bg-blue-50">  
            
            
            <p>Materi: <strong>{{ $row->kode_angkatan }} -{{ $row->materi->nama_materi }}</strong></p>
            <p>Mulai Belajar: <strong>{{ date('d M Y' , strtotime($row->tanggal_mulai)) }}</strong></p>

            <button wire:click="mendaftar({{ $row->id }})" class="bg-primary-600 rounded-lg px-2 py-1  text-white font-bold mt-4 open-modal">Ikuti</button>  

          </div>


        @endif 

        @endforeach 
      @else
   
      
        <p class="mb-2 mt-6 font-bold">Anda Terdaftar sebagai Peserta Aktif: </p>

        <?php 

        $angkatan = ( auth()->user()->angkatan_user()->aktif()->first() ) ?  auth()->user()->angkatan_user()->aktif()->first()->angkatan : null;


        ?>

        <p class="my-2">
          Materi: <span class="font-bold">{{ $angkatan->materi->nama_materi }}</span>  <br>

          @if($angkatan->status == 'Pendaftaran')
            Mulai Pembelajaran : <span class="font-bold">{{ date('d M Y', strtotime( $angkatan->tanggal_mulai )) }}</span>  <br>
          @endif

          Ujian Akhir : <span class="font-bold">{{ date('d M Y', strtotime( $angkatan->tanggal_ujian )) }}</span> 
        </p>
        
      @endif

      @if($materi) 

      <p class="prose-sm mt-5 font-semibold text-xl">Materi Hari ini:</p>      

      {{-- @foreach ($materi as $row) --}}
       
        <div class="p-2 border rounded-lg my-3">
            <div class="flex justify-between items-cente">
                <a class="bg-accent text-primary pb-0.5 px-1 text-sm rounded-sm font-semibold">{{$materi->materi_detail->materi->kategori->nama_kategori}}</a>
                {{-- <a class="pb-0.5 px-1 text-sm rounded-sm font-semibold">{{$materi->materi_detail->materi->type}}</a> --}}
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

            @if($materi->materi_detail->jenis_kontent == 'Video'  && \Priana\Membership\Mbs::check())          

              <livewire:materi.materi-video video_url="{{ $materi->materi_detail->video_url }}" />

            @else

              <button class="bg-primary-600 w-full rounded-lg p-2  text-white font-bold mt-4 open-modal" data-modal-id="myModal">DENGARKAN MATERI</button>          
              
              @if($materi && $ujian_harian && $soal_harian > 0)                        
              
                <a href="{{route('kuis',['materi_id' => $materi->materi_detail->materi_id,'jadwal_id'=>$ujian_harian->id ])}}" class="bg-white block text-center text-primary w-full rounded-lg p-2 border-2 border-primary font-bold mt-3 open-modal"  >KERJAKAN SOAL</a>

              @endif

            @endif
          </div>
      {{-- @endforeach --}}

      @endif 


      @if(count( $jadwal_ujian ) > 0)

      <p class="prose-sm mt-5 font-semibold">Ujian Hari ini</p>
      
      {{-- Ujian akhir / pekanan --}}
      @foreach($jadwal_ujian as $jadwal)
      
      <div class="border p-2 rounded-lg mt-2">
        <h2>Ujian {{ $jadwal->type }} {{ ($jadwal->type == 'Pekanan')? $jadwal->urutan : '' }}</h2>
        <h2> {{ $jadwal->angkatan->kode_angkatan }} - {{ $jadwal->angkatan->materi->nama_materi }}</h2>
        <a href="{{route('kuis',['materi_id' => $jadwal->angkatan->materi_id,'jadwal_id'=>$jadwal->id ])}}" class="bg-white block text-center text-primary w-full rounded-lg p-2 border-2 border-primary font-bold mt-3 open-modal"  >KERJAKAN SOAL</a>
      </div>       
      
      @endforeach

      

      @endif

      {{-- popup --}}

      <div wire:ignore>
        <x-modal.ModalPopup  id="myModal" default="close">
          <div class="flex flex-col items-center justify-center">
            <div class="details">        
              <div class="track-name font-semibold">Judul Materi</div>
              <div class="track-artist">Bab</div>
              <div id="status_absen" class="flex items-center justify-center">

              </div>
              <p> </p>
            </div>
            <div class="buttons">
              <div class="prev-track" onclick="prevTrack()"><i class="fa fa-step-backward fa-2x"></i></div>
              <div class="playpause-track" onclick="playpauseTrack()"><i class="fa fa-play-circle text-primary fa-5x"></i></div>
              <div class="next-track" onclick="nextTrack()"><i class="fa fa-step-forward fa-2x"></i></div>
            </div>
            <div class="slider_container">
              <div class="current-time">00:00</div>
              <input type="range" min="1" max="100" value="0" class="seek_slider" onchange="seekTo()">
              <div class="total-duration">00:00</div>
            </div>
            {{-- <div class="slider_container">
              <i class="fa fa-volume-down"></i>
              <input type="range" min="1" max="100" value="99" class="volume_slider" onchange="setVolume()">
              <i class="fa fa-volume-up"></i>
            </div> --}}
           
          </div>
          <div class="text-center mt-2">
            @if($materi && $ujian_harian && $soal_harian > 0)

              <a href="{{route('kuis',['materi_id' => $materi->materi_detail->materi_id ,'jadwal_id'=> $ujian_harian->id ])}}" class="bg-primary-600 px-8 py-2 mx-auto  text-white font-bold mt-4 rounded-lg" >KERJAKAN SOAL</a>
            @endif
            </div>
        </x-modal.ModalPopup>
      </div>
      
       <x-FrontBottomNav />
  </div>
  @endguest


    <!-- Swiper JS -->
<script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>

<!-- Initialize Swiper -->
<script>
  var swiper = new Swiper(".mySwiper", {
      spaceBetween: 20,
      speed: 900,
      loop: true,
      centeredSlides: false,
      autoplay: {
          delay: 2500,
          disableOnInteraction: false
      },
      pagination: {
              el: ".swiper-pagination",
              clickable: true,
      },
  });

</script>

<script wire:ignore>
          let track_name = document.querySelector(".track-name");
          let track_artist = document.querySelector(".track-artist");

          let playpause_btn = document.querySelector(".playpause-track");
          let next_btn = document.querySelector(".next-track");
          let prev_btn = document.querySelector(".prev-track");

          let seek_slider = document.querySelector(".seek_slider");
          let volume_slider = document.querySelector(".volume_slider");
          let curr_time = document.querySelector(".current-time");
          let total_duration = document.querySelector(".total-duration");

          let track_index = 0;
          let isPlaying = false;
          let updateTimer;

          // Create new audio element
          let curr_track = document.createElement('audio');

          // Define the tracks that have to be played
          let track_list = [
            @if($materi)
              {
                id:1,
                name: "{{ $materi->materi_detail->judul }}",
                artist: "{{$materi->materi_detail->materi->nama_materi}}",
                path: "storage/{{$materi->materi_detail->multimedia_url}}"
              },
             @endif
          ];

          function open_modal(id){
            track_index = id
            console.log(track_list[track_index].path)
            loadTrack(track_index)
            playpauseTrack()
          }

          function loadTrack(track_index) {
            clearInterval(updateTimer);
            resetValues();
            curr_track.src = track_list[track_index].path;
            curr_track.load();
            track_name.textContent = track_list[track_index].name;
            track_artist.textContent = track_list[track_index].artist;
            updateTimer = setInterval(seekUpdate, 1000);
            curr_track.addEventListener("ended", pauseTrack);
          }

          function resetValues() {
            curr_time.textContent = "00:00";
            total_duration.textContent = "00:00";
            seek_slider.value = 0;
          }

          // Load the first track in the tracklist
          loadTrack(track_index);

          function playpauseTrack() {
            if (!isPlaying) playTrack();
            else pauseTrack();
          }

          function playTrack() {
            console.log('playPauseIcon'+track_index)
            curr_track.play();
            isPlaying = true;
            playpause_btn.innerHTML = '<i class="fa fa-pause-circle fa-5x text-primary"></i>';  

          }

          function pauseTrack() {
            curr_track.pause();
            isPlaying = false;
            playpause_btn.innerHTML = '<i class="fa fa-play-circle fa-5x text-primary"></i>';;
    
          }

          function nextTrack() {
            if (track_index < track_list.length - 1)
              track_index += 1;
            else track_index = 0;
            loadTrack(track_index);
            playTrack();
          }

          function prevTrack() {
            if (track_index > 0)
              track_index -= 1;
            else track_index = track_list.length;
            loadTrack(track_index);
            playTrack();
          }

          function seekTo() {
            let seekto = curr_track.duration * (seek_slider.value / 100);
            curr_track.currentTime = seekto;
          }

          function setVolume() {
            curr_track.volume = volume_slider.value / 100;
          }

          function seekUpdate() {
            let seekPosition = 0;

            if (!isNaN(curr_track.duration)) {
              seekPosition = curr_track.currentTime * (100 / curr_track.duration);

              seek_slider.value = seekPosition;

              let currentMinutes = Math.floor(curr_track.currentTime / 60);
              let currentSeconds = Math.floor(curr_track.currentTime - currentMinutes * 60);
              let durationMinutes = Math.floor(curr_track.duration / 60);
              let durationSeconds = Math.floor(curr_track.duration - durationMinutes * 60);

              if (currentSeconds < 10) { currentSeconds = "0" + currentSeconds; }
              if (durationSeconds < 10) { durationSeconds = "0" + durationSeconds; }
              if (currentMinutes < 10) { currentMinutes = "0" + currentMinutes; }
              if (durationMinutes < 10) { durationMinutes = "0" + durationMinutes; }

              curr_time.textContent = currentMinutes + ":" + currentSeconds;
              total_duration.textContent = durationMinutes + ":" + durationSeconds;
              if(currentSeconds == durationSeconds-1){
                  @if($materi)

                  livewire.emit('absen', 
                   
                    {{$materi->materi_detail->id}}
                   
                  );

                  @endif
              }

            }
          }
       </script>


</div>


