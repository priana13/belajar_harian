<div>
  @push('head')
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css" />
  <style>
   
    .modern-card {
        background: white;
        border-radius: 1.25rem;
        box-shadow: 0 8px 32px 0 rgba(30,64,175,0.10);
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        border-left: 6px solid #fbbf24;
        transition: box-shadow 0.3s, transform 0.3s;
    }
    .modern-card:hover {
        box-shadow: 0 16px 40px 0 rgba(30,64,175,0.18);
        transform: translateY(-4px) scale(1.01);
        border-left: 6px solid #2563eb;
    }
    .modern-btn {
        background: linear-gradient(135deg, #4061f1 0%, #4713d8 100%);
        color: #fff;
        border: none;
        border-radius: 0.75rem;
        padding: 0.75rem 2rem;
        font-weight: 600;
        font-size: 1rem;
        transition: background 0.3s, transform 0.2s;
        box-shadow: 0 4px 16px rgba(30,64,175,0.10);
    }
    .modern-btn:hover {
        background: linear-gradient(90deg, #0644c9 0%, #2445d8 100%);
        transform: translateY(-2px) scale(1.03);
    }
    .modern-badge {
        background: #fbbf24;
        color: #fff;
        border-radius: 9999px;
        padding: 0.25rem 0.75rem;
        font-size: 0.75rem;
        font-weight: 600;
        margin-left: 0.5rem;
    }
    .modern-title {
        color: #1e40af;
        font-size: 1.25rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
    }
    .modern-subtitle {
        color: #fbbf24;
        font-size: 1rem;
        font-weight: 500;
        margin-bottom: 0.25rem;
    }
    .modern-section {
        background: linear-gradient(135deg, #fff7ed 0%, #eff6ff 100%);
        border-radius: 1.5rem;
        padding: 2rem 1.5rem;
        margin-bottom: 2rem;
        box-shadow: 0 2px 8px rgba(30,64,175,0.06);
        border-top: 4px solid #fbbf24;
    }
    .modern-label {
        color: #2563eb;
        font-weight: 600;
        font-size: 0.95rem;
    }
    .modern-icon {
        background: linear-gradient(135deg, #fbbf24 0%, #2563eb 100%);
        color: #fff;
        border-radius: 50%;
        width: 2.5rem;
        height: 2.5rem;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        margin-right: 0.75rem;
    }
    .modern-modal {
        background: linear-gradient(135deg, #fff7ed 0%, #eff6ff 100%);
        border-radius: 1.5rem;
        box-shadow: 0 8px 32px 0 rgba(30,64,175,0.10);
        padding: 2rem 1.5rem;
        border-top: 4px solid #fbbf24;
    }
    .modern-slider {
        accent-color: #fbbf24;
    }
    .modern-section .modern-title {
        color: #2563eb;
    }
  </style>
  @endpush

 @auth
 
  <x-FrontTopNav />

 <p class="py-6 bg-white-700"></p>
 @endauth


  <div class="min-h-screen py-6 px-0 md:px-0 " style="background: linear-gradient(135deg, #9fcaf1ff 0%, #2f56d4ff 100%);">
    <div class="max-w-2xl mx-auto">
      <div class="text-center mb-6">
        <p class="text-2xl font-bold text-white">Ahlan wa Sahlan</p>
        <p class="text-white">Semoga hari ini mendapatkan tambahan ilmu yang bermanfaat</p>
      </div>

      @guest
      <div class="modern-section">
        @livewire('homepage.home-page-banner')
        <button wire:click.prevent="login" class="modern-btn w-full mt-5">Masuk</button>
        <button wire:click.prevent="register" class="modern-btn w-full mt-3 bg-white text-white border border-blue-200 hover:bg-blue-50 hover:text-blue-900">Daftar</button>
      </div>
      @else
      <div class="modern-section">

        @livewire('homepage.home-page-banner')      

        @if( \App\Models\Angkatan::count() == 0 )

          <div class="mb-2 text-center">Seperti nya belum ada materi yang tersedia</div>

        @endif


        @if(auth()->user()->angkatan_user()->aktif()->count() == 0)
          {{-- <div class="modern-title mb-2">Materi Tersedia:</div> --}}
          @foreach($angkatan as $row)
          <?php 
            $cek_mulai_pendaftaran = strtotime( $row->mulai_pendaftaran )  <= strtotime( date('Y-m-d') ); 
            $cek_akhir_pendaftaran = strtotime( $row->akhir_pendaftaran )  >= strtotime( date('Y-m-d') );           
          ?>
          @if( $row->angkatan_user->count() < $row->kuota && $cek_mulai_pendaftaran )
          <div class="modern-card">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-2">
              <div>
                <div class="modern-label">Materi:</div>
                <div class="modern-title">{{ $row->kode_angkatan }} - {{ $row->materi->nama_materi }}</div>
                <div class="text-gray-500 text-sm mb-2">Mulai Belajar: <span class="font-semibold text-blue-700">{{ date('d M Y' , strtotime($row->tanggal_mulai)) }}</span></div>
              </div>
              <button wire:click="mendaftar({{ $row->id }})" class="modern-btn mt-2 md:mt-0">Ikuti</button>
            </div>
          </div>
          @endif
          @endforeach
        @else
          {{-- <div class="modern-title mb-2">Anda Terdaftar sebagai Peserta Aktif:</div>
          <?php 
          $angkatan = ( auth()->user()->angkatan_user()->aktif()->first() ) ?  auth()->user()->angkatan_user()->aktif()->first()->angkatan : null;
          ?>
          <div class="modern-card">
            <div class="modern-label">Materi:</div>
            <div class="modern-title">{{ $angkatan->materi->nama_materi }}</div>
            @if($angkatan->status == 'Pendaftaran')
              <div class="text-gray-500 text-sm">Mulai Pembelajaran: <span class="font-semibold text-blue-700">{{ date('d M Y', strtotime( $angkatan->tanggal_mulai )) }}</span></div>
            @endif
            <div class="text-gray-500 text-sm">Ujian Akhir: <span class="font-semibold text-blue-700">{{ date('d M Y', strtotime( $angkatan->tanggal_ujian )) }}</span></div>
          </div> --}}
        @endif
        @if($materi)
        <div class="modern-title mt-6 mb-2">Materi Hari ini:</div>
        <div class="modern-card">
          <div class="flex flex-col gap-2">
            <div class="flex justify-between items-center">
              <span class="modern-badge">{{ $materi->materi_detail->materi->kategori->nama_kategori }}</span>
            </div>
            <div class="modern-title">{{ $materi->materi_detail->judul }}</div>
            <div class="text-xs font-semibold text-blue-900">{{ $materi->angkatan->kode_angkatan }}: {{ $materi->materi_detail->materi->nama_materi }}</div>
            <div class="flex gap-2 text-xs mt-2">
              <div class="bg-blue-100 text-blue-700 py-1 px-2 rounded-md font-semibold flex items-center justify-center">
                {{ $materi->materi_detail->pertemuan }}
              </div>
              <div class="bg-blue-100 text-blue-700 py-1 px-2 rounded-md font-semibold">
                {{date('d M Y', strtotime( $materi->tanggal ))}}
              </div>
            </div>
            @if($materi->materi_detail->jenis_kontent == 'Video')
              <livewire:materi.materi-video video_url="{{ $materi->materi_detail->video_url }}" />
            @else
              <button class="modern-btn w-full mt-4 open-modal" data-modal-id="myModal">DENGARKAN MATERI</button>
              @if($materi && $ujian_harian && $soal_harian > 0)
                <a href="{{route('kuis',['materi_id' => $materi->materi_detail->materi_id,'jadwal_id'=>$ujian_harian->id ])}}" class="modern-btn w-full mt-3 bg-white text-white text-center border border-blue-200 hover:bg-blue-50">Kerjakan</a>
              @endif
            @endif
          </div>
        </div>
        @endif
        @if(count( $jadwal_ujian ) > 0)
        <div class="modern-title mt-6 mb-2">Ujian Hari ini</div>
        @foreach($jadwal_ujian as $jadwal)
        <div class="modern-card">
          <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-2">
            <div>
              <div class="modern-label">Ujian {{ $jadwal->type }} {{ ($jadwal->type == 'Pekanan')? $jadwal->urutan : '' }}</div>
              <div class="modern-title">{{ $jadwal->angkatan->kode_angkatan }} - {{ $jadwal->angkatan->materi->nama_materi }}</div>
            </div>
            <a href="{{route('kuis',['materi_id' => $jadwal->angkatan->materi_id,'jadwal_id'=>$jadwal->id ])}}" class="modern-btn mt-2 md:mt-0 bg-white text-white border border-blue-200 hover:bg-blue-50">Kerjakan</a>
          </div>
        </div>
        @endforeach
        @endif
        <div wire:ignore>
          <x-modal.ModalPopup  id="myModal" default="close">
            <div class="modern-modal flex flex-col items-center justify-center">
              <div class="details mb-4">
                <div class="track-name font-semibold text-blue-900">Judul Materi</div>
                <div class="track-artist text-blue-700">Bab</div>
                <div id="status_absen" class="flex items-center justify-center"></div>
              </div>
              <div class="buttons flex gap-4 mb-4">
                <div class="prev-track" onclick="prevTrack()"><i class="fa fa-step-backward fa-2x text-blue-700"></i></div>
                <div class="playpause-track" onclick="playpauseTrack()"><i class="fa fa-play-circle text-blue-700 fa-5x"></i></div>
                <div class="next-track" onclick="nextTrack()"><i class="fa fa-step-forward fa-2x text-blue-700"></i></div>
              </div>
              <div class="slider_container flex items-center gap-2 mb-2">
                <div class="current-time text-blue-700">00:00</div>
                <input type="range" min="1" max="100" value="0" class="seek_slider modern-slider" onchange="seekTo()">
                <div class="total-duration text-blue-700">00:00</div>
              </div>
            </div>

            {{-- tambah tombol dengan icon gambar di sini untuk melihat screen shoot materi --}}
            <div>
             
              @if($materi && $materi->materi_detail->images->count() > 0)
                <div class="flex flex-wrap gap-2 justify-center mb-2">
                  @foreach($materi->materi_detail->images as $image)
                    {{-- <button wire:click="open_modal({{$loop->index}})" class="modern-btn bg-white text-blue-700 border border-blue-200 hover:bg-blue-50"> --}}
                      <a href="{{ asset('storage/'.$image->image) }}" target="_blank" class="flex items-center justify-center">
                        <img src="{{ asset('storage/'.$image->image) }}" alt="Materi Image" class="w-16 h-16 object-cover rounded-md">
                      </a>
                    {{-- </button> --}}
                  @endforeach
                </div>
              @endif

            </div>

            <div class="text-center mt-5">
              @if($materi && $ujian_harian && $soal_harian > 0)
                <a href="{{route('kuis',['materi_id' => $materi->materi_detail->materi_id ,'jadwal_id'=> $ujian_harian->id ])}}" class="modern-btn w-full mt-3 bg-white text-white border border-blue-200 hover:bg-blue-50">KERJAKAN SOAL</a>
              @endif
            </div>
          </x-modal.ModalPopup>
        </div>
      </div>
      @endguest
    </div>
  </div>
  <x-FrontBottomNav />
</div>

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


