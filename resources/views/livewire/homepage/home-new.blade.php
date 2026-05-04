<div>
  @push('head')
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css" />
  <link rel="stylesheet" href="{{ asset('css/home.css') }}">
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

        {{-- pengumuman --}}

        @if($pengumuman->is_active)

        <div class="flex items-center mt-6 p-4 mb-4 text-sm text-blue-800 rounded-lg bg-blue-100 dark:bg-gray-800 dark:text-blue-400" role="alert">
          <svg class="shrink-0 inline w-4 h-4 me-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
            <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
          </svg>
          <span class="sr-only">Info</span>
          <div>
            <h3 class="font-medium text-xl mb-2">Pengumuman!</h3>

            <p>{!! $pengumuman ? $pengumuman->value : 'Tidak ada pengumuman terbaru' !!}</p>
          </div>
        </div>

        @endif
     
  
        
        @if($jadwal) 

        <h2 class="modern-title mt-6 mb-2">Materi Hari ini </h2>

          <div class="bg-white p-4 rounded-lg shadow-md mb-4 hover:shadow-lg transition-shadow duration-300 cursor-pointer">
            <div class="flex flex-col gap-2">
                <div class="flex justify-between items-center">
                  <span class="modern-badge">{{ $materi->kategori->nama_kategori }}</span>
                </div>
                <div class="modern-title">{{ $jadwal->materi_detail->judul }}</div>
                <div class="text-xs font-semibold text-blue-900">{{ $materi->nama_materi }}</div>
                <div class="flex gap-2 text-xs mt-2">
                  <div class="bg-blue-100 text-blue-700 py-1 px-2 rounded-md font-semibold flex items-center justify-center">
                    {{ $jadwal->materi_detail->pertemuan }}
                  </div>
                  <div class="bg-blue-100 text-blue-700 py-1 px-2 rounded-md font-semibold">
                    {{date('d M Y', strtotime( $jadwal->tanggal ))}}
                  </div>
                </div>
              @if($jadwal->materi_detail->jenis_kontent == 'Video')
                <livewire:materi.materi-video video_url="{{ $jadwal->materi_detail->video_url }}" />
              @else
                <button class="modern-btn w-full mt-4 open-modal listen-materi-btn" data-modal-id="myModal" data-track-index="0">DENGARKAN MATERI</button>
                <div class="audio-playing-notice mt-3 hidden" role="status" aria-live="polite">
                  <span class="pulse-dot" aria-hidden="true"></span>
                  <span>Ada audio yang sedang diputar. Klik "Tampilkan Audio" untuk membuka pemutar.</span>
                </div>
                @if($jadwal && $ujian_harian && $soal_harian > 0)
                  <a href="{{route('kuis',['materi_id' => $materi->id,'jadwal_id'=>$ujian_harian->id ])}}?trial={{ request()->trial }}" class="modern-btn w-full mt-3 bg-white text-white text-center border border-blue-200 hover:bg-blue-50">Kerjakan Soal</a>
                @endif
              @endif
            </div>
        </div>
        
        @endif

        @if($jadwal_khusus)
    

          <div class="bg-white p-4 rounded-lg shadow-md mb-4 hover:shadow-lg transition-shadow duration-300 cursor-pointer">
            <div class="flex flex-col gap-2">
                <div class="flex justify-between items-center">
                  <span class="modern-badge">{{ $materi_khusus->kategori->nama_kategori }}</span>
                </div>
                <div class="modern-title">{{ $jadwal_khusus->materi_detail->judul }}</div>
                <div class="text-xs font-semibold text-blue-900">{{ $materi_khusus->nama_materi }}</div>
                <div class="flex gap-2 text-xs mt-2">
                  <div class="bg-blue-100 text-blue-700 py-1 px-2 rounded-md font-semibold flex items-center justify-center">
                    {{ $jadwal_khusus->materi_detail->pertemuan }}
                  </div>
                  <div class="bg-blue-100 text-blue-700 py-1 px-2 rounded-md font-semibold">
                    {{date('d M Y', strtotime( $jadwal_khusus->tanggal ))}}
                  </div>
                </div>
              @if($jadwal_khusus->materi_detail->jenis_kontent == 'Video')
                <livewire:materi.materi-video video_url="{{ $jadwal_khusus->materi_detail->video_url }}" />
              @else
                <button class="modern-btn w-full mt-4 open-modal listen-materi-btn" data-modal-id="myModal" data-track-index="{{ $jadwal ? 1 : 0 }}">DENGARKAN MATERI</button>
                <div class="audio-playing-notice mt-3 hidden" role="status" aria-live="polite">
                  <span class="pulse-dot" aria-hidden="true"></span>
                  <span>Ada audio yang sedang diputar. Klik "Tampilkan Audio" untuk membuka pemutar.</span>
                </div>
                @if($jadwal_khusus && $ujian_harian_khusus)
                  <a href="{{route('kuis',['materi_id' => $materi_khusus->id,'jadwal_id'=>$ujian_harian_khusus->id ])}}?trial={{ request()->trial }}" class="modern-btn w-full mt-3 bg-white text-white text-center border border-blue-200 hover:bg-blue-50">Kerjakan Soal</a>
                @endif
              @endif
            </div>
        </div>

        @else 

          @if(auth()->user()->groups->count() > 0 && $mulai_belajar->tanggal_mulai > date('Y-m-d') && $mulai_belajar->tanggal_mulai != null)

          <div class="mt-5 text-center text-gray-700 bg-white p-4 rounded-lg shadow-md">

              Anda terdaftar di Group: {{ auth()->user()->groups->pluck('nama_group')->join(', ') }}
              <br>
    
              Mulai Pembelajaran: <strong>{{ date("d M Y", strtotime($mulai_belajar->tanggal_mulai ?? "-")) }}</strong>
    
              <br> 

          </div>

          @endif

        @endif


        @if(count( $jadwal_ujian ) > 0)    


          <div class="modern-title mt-6 mb-2">Ujian Hari ini</div>
          @foreach($jadwal_ujian as $row)       
          <div class="modern-card">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-2">
              <div>
                <div class="modern-label">Ujian {{ $row->type }} {{ ($row->type == 'Pekanan')? $row->urutan : '' }}</div>
                <div class="modern-title">{{ $row->materi->nama_materi }}</div>
              </div>
              <a href="{{route('kuis',['materi_id' => $row->materi_id,'jadwal_id'=>$row->id ])}}" class="modern-btn mt-2 md:mt-0 bg-white text-white border border-blue-200 hover:bg-blue-50">Kerjakan Soal</a>
            </div>
          </div>
          @endforeach
        @endif
      

        @livewire('materi-berikutnya')

        <div wire:ignore>
          {{-- modal popup untuk pemutar audio --}}
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
              <div class="slider_container flex items-center gap-2 mb-4">
                <div class="current-time text-blue-700">00:00</div>
                <input type="range" min="1" max="100" value="0" class="seek_slider modern-slider" onchange="seekTo()">
                <div class="total-duration text-blue-700">00:00</div>
              </div>

              {{-- Foto dan Nama Pemateri --}}
              <div class="w-full border-t border-blue-200 pt-4 mb-4">             
                <div class="flex items-center justify-center gap-3">
                  <img src="{{ asset('img/pemateri1.png') }}" alt="Pemateri" class="w-10 h-10 rounded-full object-cover shadow-md border-2 border-blue-500">
                  <div class="flex flex-col items-start">
                    <p class="text-sm font-semibold text-blue-900">Ustadz Ugun Gunansyah</p>
                    <div class="sound-wave paused" id="soundWave">
                      <span class="bar"></span>
                      <span class="bar"></span>
                      <span class="bar"></span>
                      <span class="bar"></span>
                      <span class="bar"></span>
                      <span class="bar"></span>
                      <span class="bar"></span>
                      <span class="bar"></span>
                      <span class="bar"></span>
                      <span class="bar"></span>
                      <span class="bar"></span>
                      <span class="bar"></span>
                      <span class="bar"></span>
                      <span class="bar"></span>
                      <span class="bar"></span>
                      <span class="bar"></span>
                      <span class="bar"></span>
                      <span class="bar"></span>
                      <span class="bar"></span>
                      <span class="bar"></span>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            {{-- tambah tombol dengan icon gambar di sini untuk melihat screen shoot materi --}}
            <div>

              @if($jadwal && $jadwal->materi_detail->images->count() > 0)
                <p class="text-center mb-3 font-bold text-gray-800">Gambar {{ $materi->nama_materi }}</p>
                <div class="flex flex-wrap gap-2 justify-center mb-2">
                  @foreach($jadwal->materi_detail->images as $image)
                    <button onclick="openGalleryModal('jadwal')" class="flex items-center justify-center border-2 border-green-500 p-2 overflow-hidden rounded-md hover:border-green-700 transition-colors">
                     {{ $loop->iteration }}. <img src="{{ asset('storage/'.$image->image) }}" alt="Materi Image" class="w-16 h-16 object-cover rounded-md transition-transform duration-300 hover:scale-150">
                    </button>
                  @endforeach
                </div>
                {{-- <button onclick="openGalleryModal('jadwal')" class="view-all-images-btn">Lihat Semua Gambar Materi</button> --}}
              @endif

              @if($jadwal_khusus && $jadwal_khusus->materi_detail->images->count() > 0)
                <p class="text-center mb-3 font-bold text-gray-800">Gambar {{ $materi_khusus->nama_materi }}</p>
                <div class="flex flex-wrap gap-2 justify-center mb-2">
                  @foreach($jadwal_khusus->materi_detail->images as $image)
                    <button onclick="openGalleryModal('jadwal_khusus')" class="flex items-center justify-center border-2 border-green-500 p-2 overflow-hidden rounded-md hover:border-green-700 transition-colors">
                     {{ $loop->iteration }}. <img src="{{ asset('storage/'.$image->image) }}" alt="Materi Image" class="w-16 h-16 object-cover rounded-md transition-transform duration-300 hover:scale-150">
                    </button>
                  @endforeach
                </div>
                {{-- <button onclick="openGalleryModal('jadwal_khusus')" class="view-all-images-btn">Lihat Semua Gambar Materi</button> --}}
              @endif


            </div>

            <div class="text-center mt-5">
              @if($jadwal && $ujian_harian && $soal_harian > 0)
                {{-- <a href="{{route('kuis',['materi_id' => $jadwal->materi_detail->materi_id ,'jadwal_id'=> $ujian_harian->id ])}}" class="modern-btn w-full mt-3 bg-white text-white border border-blue-200 hover:bg-blue-50">KERJAKAN SOAL</a> --}}
              @endif
            </div>
          </x-modal.ModalPopup>
        </div>

        <!-- Gallery Modal -->
        <div id="galleryModal" class="gallery-modal-overlay">
          <div class="gallery-modal-content">
            <div class="gallery-modal-header">
              <h3>Gambar Materi</h3>
              <button class="gallery-modal-close" onclick="closeGalleryModal()">&times;</button>
            </div>
            <div class="gallery-swiper-container">
              <div class="gallery-swiper swiper">
                <div class="swiper-wrapper" id="galleryWrapper">
                  <!-- Slides will be populated by JavaScript -->
                </div>
                <div class="swiper-button-prev"></div>
                <div class="swiper-button-next"></div>
              </div>
            </div>
            <div class="gallery-counter" id="galleryCounter"></div>
            <div class="gallery-thumbnails" id="galleryThumbnails">
              <!-- Thumbnails will be populated by JavaScript -->
            </div>
          </div>
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

  // Gallery Images Data
  const galleryImages = {
    jadwal: [
      @if($jadwal && $jadwal->materi_detail->images->count() > 0)
        @foreach($jadwal->materi_detail->images as $image)
          "{{ asset('storage/'.$image->image) }}",
        @endforeach
      @endif
    ],
    jadwal_khusus: [
      @if($jadwal_khusus && $jadwal_khusus->materi_detail->images->count() > 0)
        @foreach($jadwal_khusus->materi_detail->images as $image)
          "{{ asset('storage/'.$image->image) }}",
        @endforeach
      @endif
    ]
  };

  let gallerySwiperInstance = null;
  let currentGalleryType = null;

  function openGalleryModal(type) {
    if (!galleryImages[type] || galleryImages[type].length === 0) return;

    currentGalleryType = type;
    const images = galleryImages[type];
    const modal = document.getElementById('galleryModal');
    const wrapper = document.getElementById('galleryWrapper');
    const thumbnails = document.getElementById('galleryThumbnails');

    // Clear previous slides
    wrapper.innerHTML = '';
    thumbnails.innerHTML = '';

    // Add slides and thumbnails
    images.forEach((image, index) => {
      const slide = document.createElement('div');
      slide.className = 'swiper-slide gallery-slide';
      slide.innerHTML = `<img src="${image}" alt="Gallery Image ${index + 1}">`;
      wrapper.appendChild(slide);

      const thumb = document.createElement('div');
      thumb.className = 'gallery-thumbnail' + (index === 0 ? ' active' : '');
      thumb.innerHTML = `<img src="${image}" alt="Thumbnail ${index + 1}">`;
      thumb.onclick = () => {
        if (gallerySwiperInstance) {
          gallerySwiperInstance.slideTo(index);
        }
      };
      thumbnails.appendChild(thumb);
    });

    // Initialize or update Swiper
    if (!gallerySwiperInstance) {
      gallerySwiperInstance = new Swiper(".gallery-swiper", {
        loop: false,
        navigation: {
          nextEl: ".swiper-button-next",
          prevEl: ".swiper-button-prev",
        },
        on: {
          slideChange: updateGalleryThumbnails
        }
      });
    } else {
      gallerySwiperInstance.update();
    }

    // Show modal
    modal.classList.add('active');
    updateGalleryCounter();
    updateGalleryThumbnails();
  }

  function closeGalleryModal() {
    const modal = document.getElementById('galleryModal');
    modal.classList.remove('active');
  }

  function updateGalleryCounter() {
    const counter = document.getElementById('galleryCounter');
    if (gallerySwiperInstance && currentGalleryType) {
      const current = gallerySwiperInstance.activeIndex + 1;
      const total = galleryImages[currentGalleryType].length;
      counter.textContent = `${current} dari ${total}`;
    }
  }

  function updateGalleryThumbnails() {
    const thumbnails = document.querySelectorAll('.gallery-thumbnail');
    thumbnails.forEach((thumb, index) => {
      thumb.classList.toggle('active', index === gallerySwiperInstance.activeIndex);
    });
    updateGalleryCounter();
  }

  // Close modal when clicking outside
  document.getElementById('galleryModal').addEventListener('click', function(e) {
    if (e.target === this) {
      closeGalleryModal();
    }
  });

  // Close modal with Escape key
  document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
      closeGalleryModal();
    }
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
          let listenMateriBtns = document.querySelectorAll(".listen-materi-btn");
          let audioPlayingNotices = document.querySelectorAll(".audio-playing-notice");

          let track_index = 0;
          let isPlaying = false;
          let updateTimer;

          // Create new audio element
          let curr_track = document.createElement('audio');
          
          // Get sound wave element
          let soundWave = document.getElementById('soundWave');
          let bars = soundWave ? soundWave.querySelectorAll('.bar') : [];
          
          // Audio context for frequency analysis
          let audioContext = null;
          let analyser = null;
          let dataArray = null;
          let animationId = null;

          // Define the tracks that have to be played
          let track_list = [];

          @if($jadwal && $jadwal->materi_detail->jenis_kontent != 'Video' && $jadwal->materi_detail->multimedia_url)
          track_list.push({
            id: 1,
            name: "{{ $jadwal->materi_detail->judul }}",
            artist: "{{ $jadwal->materi_detail->materi->nama_materi }}",
            path: "storage/{{ $jadwal->materi_detail->multimedia_url }}"
          });
          @endif

          @if($jadwal_khusus && $jadwal_khusus->materi_detail->jenis_kontent != 'Video' && $jadwal_khusus->materi_detail->multimedia_url)
          track_list.push({
            id: 2,
            name: "{{ $jadwal_khusus->materi_detail->judul }}",
            artist: "{{ $jadwal_khusus->materi_detail->materi->nama_materi }}",
            path: "storage/{{ $jadwal_khusus->materi_detail->multimedia_url }}"
          });
          @endif

          function syncAudioUiState() {
            listenMateriBtns.forEach((button) => {
              button.textContent = isPlaying ? "TAMPILKAN AUDIO" : "DENGARKAN MATERI";
              button.classList.toggle('is-audio-playing', isPlaying);
            });

            audioPlayingNotices.forEach((notice) => {
              notice.classList.toggle('hidden', !isPlaying);
            });
          }

          function open_modal(id){
            if (!track_list[id]) return;
            track_index = id;
            loadTrack(track_index);
            playTrack();
          }

          function loadTrack(track_index) {
            if (!track_list[track_index]) return;
            clearInterval(updateTimer);
            resetValues();
            curr_track.src = track_list[track_index].path;
            curr_track.load();
            track_name.textContent = track_list[track_index].name;
            track_artist.textContent = track_list[track_index].artist;
            updateTimer = setInterval(seekUpdate, 1000);
            curr_track.addEventListener("ended", pauseTrack);
            syncAudioUiState();
          }

          function resetValues() {
            curr_time.textContent = "00:00";
            total_duration.textContent = "00:00";
            seek_slider.value = 0;
          }

          // Load the first track in the tracklist
          if (track_list.length > 0) {
            loadTrack(track_index);
          }

          listenMateriBtns.forEach((button) => {
            button.addEventListener("click", function () {
              const selectedIndex = Number(this.dataset.trackIndex);
              if (!Number.isInteger(selectedIndex)) return;
              open_modal(selectedIndex);
            });
          });

          function playpauseTrack() {
            if (!isPlaying) playTrack();
            else pauseTrack();
          }

          function initAudioContext() {
            if (!audioContext) {
              audioContext = new (window.AudioContext || window.webkitAudioContext)();
              analyser = audioContext.createAnalyser();
              analyser.fftSize = 64;
              const source = audioContext.createMediaElementSource(curr_track);
              source.connect(analyser);
              analyser.connect(audioContext.destination);
              dataArray = new Uint8Array(analyser.frequencyBinCount);
            }
          }
          
          function visualize() {
            if (!analyser || !isPlaying) return;
            
            animationId = requestAnimationFrame(visualize);
            analyser.getByteFrequencyData(dataArray);
            
            // Update each bar height based on frequency data
            bars.forEach((bar, index) => {
              const dataIndex = Math.floor(index * dataArray.length / bars.length);
              const value = dataArray[dataIndex];
              const height = Math.max(8, (value / 255) * 40); // Min 8px, max 40px
              bar.style.height = height + 'px';
            });
          }

          function playTrack() {
            console.log('playPauseIcon'+track_index)
            
            // Initialize audio context on first play (user interaction required)
            if (!audioContext) {
              initAudioContext();
            }
            
            if (audioContext.state === 'suspended') {
              audioContext.resume();
            }
            
            curr_track.play();
            isPlaying = true;
            playpause_btn.innerHTML = '<i class="fa fa-pause-circle fa-5x text-primary"></i>';
            
            // Activate sound wave animation
            if (soundWave) {
              soundWave.classList.remove('paused');
              visualize();
            }

            syncAudioUiState();
          }

          function pauseTrack() {
            curr_track.pause();
            isPlaying = false;
            playpause_btn.innerHTML = '<i class="fa fa-play-circle fa-5x text-primary"></i>';
            
            // Pause sound wave animation
            if (soundWave) {
              soundWave.classList.add('paused');
              if (animationId) {
                cancelAnimationFrame(animationId);
              }
              // Reset bars to minimum height
              bars.forEach(bar => {
                bar.style.height = '8px';
              });
            }

            syncAudioUiState();
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
            else track_index = track_list.length - 1;
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

          syncAudioUiState();
       </script>


