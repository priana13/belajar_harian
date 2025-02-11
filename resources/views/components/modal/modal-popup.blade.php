

<div id="{{ $id }}" class="@if($default == 'close') hidden @endif modal-content " 

x-data="{
    pesan: 'ini adalah isi pesan dari data sumber {{ $id }}',  
    materi : {
      'judul' : '{{ $materi['materi_detail']['materi']['nama_materi'] }}',
      'pertemuan' : '{{ $materi['materi_detail']['judul'] }}'
    },
    isPlaying : false,
    audio: '',
    playpauseTrack(){  

      if (!this.isPlaying) this.playTrack();
      else this.pauseTrack();

    },

    playTrack(){
      // putar audio
      {{-- curr_track.play(); --}}
      this.audio = $refs.audio;

      this.audio.play();

      {{-- $refs.audio.play(); --}}
      this.isPlaying = true;     
      
    },
    pauseTrack(){
      //matikan audio
      $refs.audio.pause();
      this.isPlaying = false; 

    },

    load(){
      alert('oke loaded');
    },
    getCurrentTime(){
      return 200;
    },
    seekTo(){

      {{-- let seekto = audio.duration * (seekSlider / 100); --}}
          this.audio.currentTime = this.audio.duration * (this.seekSlider / 100);
    },
    totalDuration: '00:00',
    currentTime : '00:00', 
    seekSlider: 0,  
  

}"

x-init="

  {{-- console.log(audio); --}}


    setInterval(function(){


        if (!isNaN(audio.duration)) {         

          seekSlider = audio.currentTime * (100 / audio.duration);

          {{-- seek_slider.value = seekPosition; --}}

          let currentMinutes = Math.floor(audio.currentTime / 60);
          let currentSeconds = Math.floor(audio.currentTime - currentMinutes * 60);
          let durationMinutes = Math.floor(audio.duration / 60);
          let durationSeconds = Math.floor(audio.duration - durationMinutes * 60);

          if (currentSeconds < 10) { currentSeconds = '0' + currentSeconds; }
          if (durationSeconds < 10) { durationSeconds = '0' + durationSeconds; }
          if (currentMinutes < 10) { currentMinutes = '0' + currentMinutes; }
          if (durationMinutes < 10) { durationMinutes = '0' + durationMinutes; }

          currentTime = currentMinutes + ':' + currentSeconds;

          totalDuration = durationMinutes + ':' + durationSeconds;

        }


      
    }, 1000);

"


>
  <div  class="fixed inset-x-0 bottom-0 h-full w-full  bg-black opacity-70 z-10 @if($default == 'close')  @endif close_modal">
  </div>
    <div  class="fixed inset-x-3  -bottom-[1px] z-20 bg-white shadow-top rounded-xl p-5 max-w-[30rem] mx-auto ">
    <div class="">
           

        <div class="flex flex-col items-center justify-center">
            <div class="details">        
              <div class="track-name font-semibold" x-text="materi.pertemuan">Judul Materi</div>              

              <div class="track-artist" x-text="materi.judul">Bab</div>
              <div id="status_absen" class="flex items-center justify-center">

              </div>
              <p> </p>
            </div>

           

            <div class="buttons">
              <div class="prev-track"><i class="fa fa-step-backward fa-2x"></i></div>

              <div class="playpause-track" x-on:click="playpauseTrack();">
                <i class="fa fa-pause-circle fa-5x text-primary" x-show="isPlaying"></i>
                <i class="fa fa-play-circle text-primary fa-5x" x-show="!isPlaying"></i>
              </div>


              <div class="next-track" ><i class="fa fa-step-forward fa-2x"></i></div>
            </div>
            <div class="slider_container mb-20">
              <div class="current-time" x-text="currentTime">00:00</div>
              <input type="range" min="1" max="100" value="0" class="seek_slider" x-model="seekSlider" x-on:change="seekTo()">
              <div class="total-duration" x-text="totalDuration">00:00</div>
            </div>

            <div class="slider_container hidden">
              <i class="fa fa-volume-down"></i>
              <input type="range" min="1" max="100" value="99" class="volume_slider" onchange="setVolume()">
              <i class="fa fa-volume-up"></i>
            </div>
           
        </div>

        <div class="bg-blue-500">

          <audio x-ref="audio" id="audio{{ $materi['id'] }}">
              <source src="{{ asset('storage/' . $materi['materi_detail']['multimedia_url'] ) }}" type="audio/mpeg" />
          </audio>

        </div>
        <div class="text-center">         

            <a href="#" class="bg-primary-600 px-8 py-2 mx-auto  text-white font-bold mt-4 rounded-lg" >KERJAKAN SOAL</a>
           
        </div>

    </div>

    </div>


    <script>

      

  </script>
 



</div>

