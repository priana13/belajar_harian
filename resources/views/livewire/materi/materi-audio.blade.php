<div class="mx-4"

x-data="{

    audioMateri : document.createElement('audio'),
    UrlAudioMateri : '{{ asset('storage/' . $pertemuan->multimedia_url ) }}',   
    currentTime: '',
    totalDuration: '',
    progress: 0,


  }"
  
  x-init="
    audioMateri.src = UrlAudioMateri;       
    {{-- audioMateri.play(); --}}

    setInterval(function(){

        {{-- console.log(currentTime); --}}

        seekPosition = audioMateri.currentTime * (100 / audioMateri.duration);

        progress = seekPosition;
        
        {{-- seek_slider.value = seekPosition; --}}

        let currentMinutes = Math.floor(audioMateri.currentTime / 60);
        let currentSeconds = Math.floor(audioMateri.currentTime - currentMinutes * 60);
        let durationMinutes = Math.floor(audioMateri.duration / 60);
        let durationSeconds = Math.floor(audioMateri.duration - durationMinutes * 60);

        if (currentSeconds < 10) { currentSeconds = '0' + currentSeconds; }
        if (durationSeconds < 10) { durationSeconds = '0' + durationSeconds; }
        if (currentMinutes < 10) { currentMinutes = '0' + currentMinutes; }
        if (durationMinutes < 10) { durationMinutes = '0' + durationMinutes; }

        currentTime = currentMinutes + ':' + currentSeconds;
        totalDuration = durationMinutes + ':' + durationSeconds;



    }, 1000)

  
  "

>


    <div class="relative p-4 w-full max-w-2xl max-h-full">
        <!-- Modal content -->
        <div class="relative border-2 shadow-md rounded-lg dark:bg-gray-700">
            <!-- Modal header -->
            <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
              <div>
                  <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                      {{ $pertemuan->judul }}
                  </h3>
                  <p>{{ $pertemuan->materi->nama_materi }}</p>
              </div>                
               
            </div>
            <!-- Modal body -->
            <div class="p-4 md:p-5 space-y-4">
                              

              <!-- component -->
             
                  <!-- Album Cover -->
                  {{-- <img src="https://telegra.ph/file/2acfcad8d39e49d95addd.jpg" alt="idk - Highvyn, Taylor Shin" class="w-64 h-64 mx-auto rounded-lg mb-4 shadow-lg shadow-teal-50"> --}}
                  <!-- Song Title -->
                  <h2 class="text-xl font-semibold text-center">{{ $pertemuan->judul }}</h2>                  
                  <p class="text-gray-600 text-sm text-center">Ustadz Mus'ab</p>

                  <!-- Music Controls -->
                  <div class="mt-6 flex justify-center items-center">
                      <button class="p-3 rounded-full bg-gray-200 hover:bg-gray-300 focus:outline-none prev-track" onclick="prevTrack()">
                      <svg width="64px" height="64px" viewBox="0 0 24 24" class="w-4 h-4 text-gray-600" fill="none" xmlns="http://www.w3.org/2000/svg" transform="matrix(-1, 0, 0, 1, 0, 0)">
                          <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                          <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                          <g id="SVGRepo_iconCarrier">
                          <path d="M16.6598 14.6474C18.4467 13.4935 18.4467 10.5065 16.6598 9.35258L5.87083 2.38548C4.13419 1.26402 2 2.72368 2 5.0329V18.9671C2 21.2763 4.13419 22.736 5.87083 21.6145L16.6598 14.6474Z" fill="#000000"></path>
                          <path d="M22.75 5C22.75 4.58579 22.4142 4.25 22 4.25C21.5858 4.25 21.25 4.58579 21.25 5V19C21.25 19.4142 21.5858 19.75 22 19.75C22.4142 19.75 22.75 19.4142 22.75 19V5Z" fill="#000000"></path>
                          </g>
                      </svg>
                      </button>
                      <button class="p-4 rounded-full bg-gray-200 hover:bg-gray-300 focus:outline-none mx-4 playpause-track" x-on:click="$store.audio.toggle(audioMateri)"  id="playpause_btn">
                          
                        <i class="fa fa-play-circle fa-5x text-primary"></i>
                      </button>
                      <button class="p-3 rounded-full bg-gray-200 hover:bg-gray-300 focus:outline-none next-track" onclick="nextTrack()">
                      <svg width="64px" height="64px" viewBox="0 0 24 24" class="w-4 h-4 text-gray-600" fill="none" xmlns="http://www.w3.org/2000/svg">
                          <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                          <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                          <g id="SVGRepo_iconCarrier">
                          <path d="M16.6598 14.6474C18.4467 13.4935 18.4467 10.5065 16.6598 9.35258L5.87083 2.38548C4.13419 1.26402 2 2.72368 2 5.0329V18.9671C2 21.2763 4.13419 22.736 5.87083 21.6145L16.6598 14.6474Z" fill="#000000"></path>
                          <path d="M22.75 5C22.75 4.58579 22.4142 4.25 22 4.25C21.5858 4.25 21.25 4.58579 21.25 5V19C21.25 19.4142 21.5858 19.75 22 19.75C22.4142 19.75 22.75 19.4142 22.75 19V5Z" fill="#000000"></path>
                          </g>
                      </svg>
                      </button>
                  </div>


                  {{-- <label for="default-range" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Default range</label> --}}
                  <input x-model="progress" id="default-range" type="range" value="0" class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer dark:bg-gray-700">


                  <!-- Progress Bar -->
                  {{-- <div class="mt-6 bg-gray-200 h-2 rounded-full">
                      <div class="bg-teal-500 h-2 rounded-full w-[0%] seek_slider" ></div>
                  </div> --}}
                  <!-- Time Information -->
                  <div class="flex justify-between mt-2 text-sm text-gray-600">
                      <span x-text="currentTime">00:00</span>
                      <span x-text="totalDuration">00:00</span>
                  </div>
           
              
                  {{-- <p class="text-base leading-relaxed text-gray-500 dark:text-gray-400">
                    With less than a month to go before the European Union enacts new consumer privacy laws for its citizens, companies around the world are updating their terms of service agreements to comply.
                </p> --}}
               

                @if($ujian_harian)

                <p>Setelah selesai mendengarkan materi, jangan lupa mengerjakan soal!</p>


                    <div class="text-center">
                        
                        <a href="{{route('kuis',['materi_id' => $pertemuan->materi_id ,'jadwal_id'=> $ujian_harian->id ])}}" class="bg-primary-600 hover:bg-primary-700 w-full rounded-lg py-2 px-6  text-white font-bold mt-12 shadow-md" >KERJAKAN SOAL</a>

                    </div>
                    
                @endif


                {{-- <button class="bg-primary-600 w-full rounded-lg p-2  text-white font-bold mt-4">KERJAKAN SOAL</button> --}}

            </div>
        

        </div>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.store('audio', {
                on: false,                
     
                toggle(audioMateri) {

                    let playpause_btn = document.getElementById('playpause_btn');

                    this.on = ! this.on                   

                    if(this.on){

                        audioMateri.play();
                        playpause_btn.innerHTML = '<i class="fa fa-pause-circle fa-5x text-primary"></i>';
                    }else{

                        audioMateri.pause();
                        playpause_btn.innerHTML = '<i class="fa fa-play-circle fa-5x text-primary"></i>';

                    }
                }

               
            })
        })
    </script>

    

</div>
