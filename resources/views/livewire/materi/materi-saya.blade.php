<div>
    @push('head')
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
        <div class="p-3">
            <div x-data="{ activeTab: 'materi_ajaran' }" class="max-w-lg mx-auto mt-6">
                <div class="flex text-sm">
                    <button
                        x-on:click="activeTab = 'materi_ajaran'"
                        :class="{'bg-white text-primary': activeTab == 'materi_ajaran', 'bg-secondary text-white': activeTab !== 'materi_ajaran' }"
                        class="flex-1 py-2 px-4 text-center font-semibold  border-b-2 border-secondary"
                    >
                        MATERI AJAR
                    </button>
                    <button
                        x-on:click="activeTab = 'evaluasi'"
                        :class="{ 'bg-white text-primary': activeTab == 'evaluasi', 'bg-secondary text-white': activeTab !== 'evaluasi' }"
                        class="flex-1 py-2 px-4 text-center font-semibold border-b-2 border-secondary focus:outline-none"
                    >
                        EVALUASI
                    </button>
                    <button
                        x-on:click="activeTab = 'lainnya'"
                        :class="{ 'bg-white text-primary': activeTab == 'lainnya', 'bg-secondary text-white': activeTab !== 'lainnya' }"
                        class="flex-1 py-2 px-4 text-center font-semibold border-b-2 border-secondary "
                    >
                        LAINNYA
                    </button>
                </div>

               
                <div class="bg-white mt-4 py-1 px-4">
                    <div x-show="activeTab == 'materi_ajaran'">

                      @if(count($materi))


                        @foreach($materi as $key=>$row)

                        <div class="text-sm mt-3"> 
                            <p class="text-base font-semibold">{{$row->materi_detail->judul}}
                            </p>
                            <p class="text-sm">{{ $row->angkatan->kode_angkatan }} {{$row->materi_detail->materi->nama_materi}}</p>
                            <p class="mt-1">Dibuka : {{date('d-m-Y', strtotime($row->tanggal))}}</p>
                            <p class="mt-1">Berakhir : {{date('d-m-Y', strtotime($row->angkatan->tanggal_akhir))}}</p>
                            <button  onclick="open_modal({{$key}})" class="bg-primary-500 w-full rounded-lg py-2 px-4 text-white font-bold mt-2 open-modal text-left flex items-center open-modal"  data-modal-id="myModal">
                              <i id="playPauseIcon0" class="fa-solid fa-play" ></i>  <p class="mx-auto pr-3 open-modal"  data-modal-id="myModal">DENGARKAN MATERI INI</p>
                            </button>
                        </div>

                        @endforeach

                        <div class="my-3">{{ $materi->links() }}</div>

                      @else  

                        <p>Belum ada materi tersedia</p>

                      @endif
                        
                    </div>

                    <div x-show="activeTab == 'evaluasi'">
                       @foreach($evaluasi as $key=>$row)
                      {{-- 
                       // "jenis_ujian_id" => 1
                       // "nama_ujian" => "Ujian Materi"
                       // "user_id" => 2
                       // "nilai" => 0
                       // "created_at" => "2023-12-07 00:00:00"
                       // "updated_at" => "2023-12-07 18:37:01"
                       // "materi_id" => 1
                       // "keterangan" => "Tidak Lulus"
                       // "angkatan_id" => 7 --}}

                        <div class="text-sm mt-3"> 
                            <p class="text-base font-semibold">Ujian {{$row->jenis_ujian->nama}}</p>
                            <p class="mt-1">Tanggal : {{$row->created_at}}</p>                            
                            <a href="{{route('evaluasi_kuis',['materi_id' => $row->materi_id , 'ujian_id' => $row->id])}}" class="bg-primary-500 w-full block rounded-lg py-2 px-4 text-white font-bold mt-2  text-center "  >
                                <p class="mx-auto pr-3 "  >Lihat</p>
                            </a>
                        </div>
                        @endforeach
                    </div>

                    <div x-show="activeTab == 'lainnya'">
                        {{-- <a href="{{route('peringkat')}}" class="flex items-center gap-2 py-3 border-b">
                            <div class="p-4 rounded-full bg-slate-500"></div>
                            <p>Daftar Peringkat</p>
                        </a> --}}
                        <div class="flex items-center gap-2 py-3 border-b">
                            <div class="p-4 rounded-full bg-slate-500"></div>
                            <p>Arsip</p>
                        </div>
                        <div class="flex items-center gap-2 py-3 border-b">
                            <div class="p-4 rounded-full bg-slate-500"></div>
                            <p>Silabus</p>
                        </div>
                        <div class="flex items-center gap-2 py-3 border-b">
                            <div class="p-4 rounded-full bg-slate-500"></div>
                            <p>Kaldik</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <x-modal.ModalPopup id="myModal" default="close">
            <div class="flex flex-col items-center justify-center">
              <div class="details">        
                <div class="track-name font-semibold">Judul Materi</div>
                <div class="track-artist">Bab</div>
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
              <div class="slider_container">
                <i class="fa fa-volume-down"></i>
                <input type="range" min="1" max="100" value="99" class="volume_slider" onchange="setVolume()">
                <i class="fa fa-volume-up"></i>
              </div>
            </div>
            <div class="text-center">
                <button class="bg-primary-500 w-[70%] p-2 mx-auto  text-white font-bold mt-4 open-modal rounded-lg" data-modal-id="myModal">KERJAKAN SOAL</button>
            </div>
        </x-modal.ModalPopup>
         <x-FrontBottomNav />
         <script>
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
              @foreach($materi as $key=>$row)

              {
                id:1,
                name: "{{$row->materi_detail->judul}}",
                artist: "{{ $row->materi_detail->materi->nama_materi }}",
                path: "storage/{{$row->materi_detail->multimedia_url}}"
              },
            @endforeach
         
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
              curr_track.addEventListener("ended", nextTrack);
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
              document.getElementById('playPauseIcon'+track_index).classList.add('fa-pause')
              document.getElementById('playPauseIcon'+track_index).classList.remove('fa-play')
            }

            function pauseTrack() {
              curr_track.pause();
              isPlaying = false;
              playpause_btn.innerHTML = '<i class="fa fa-play-circle fa-5x text-primary"></i>';;
              document.getElementById('playPauseIcon'+track_index).classList.remove('fa-pause')
              document.getElementById('playPauseIcon'+track_index).classList.add('fa-play')
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
              }
            }
         </script>
</div>
