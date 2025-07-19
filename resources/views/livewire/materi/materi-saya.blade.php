@push('head')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
    
    * {
        font-family: 'Inter', sans-serif;
    }

    .gradient-bg {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }

    .glass-effect {
        backdrop-filter: blur(20px);
        background: rgba(255, 255, 255, 0.95);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }

    .card-shadow {
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
    }

    .btn-gradient {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        transition: all 0.3s ease;
    }

    .btn-gradient:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(102, 126, 234, 0.4);
    }

    .track-item {
        transition: all 0.3s ease;
        border-left: 4px solid transparent;
    }

    .track-item:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        border-left-color: #667eea;
    }

    .tab-modern {
        position: relative;
        overflow: hidden;
        transition: all 0.3s ease;
    }

    .tab-modern::before {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        height: 3px;
        background: linear-gradient(90deg, #667eea, #764ba2);
        transform: scaleX(0);
        transition: transform 0.3s ease;
    }

    .tab-modern.active::before {
        transform: scaleX(1);
    }

    .audio-slider {
        -webkit-appearance: none;
        appearance: none;
        height: 6px;
        background: linear-gradient(90deg, #e2e8f0, #cbd5e1);
        border-radius: 3px;
        outline: none;
        transition: all 0.3s ease;
    }

    .audio-slider::-webkit-slider-thumb {
        -webkit-appearance: none;
        appearance: none;
        width: 18px;
        height: 18px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 50%;
        cursor: pointer;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        transition: all 0.3s ease;
    }

    .audio-slider::-webkit-slider-thumb:hover {
        transform: scale(1.2);
        box-shadow: 0 6px 12px rgba(0, 0, 0, 0.3);
    }

    .audio-slider::-moz-range-thumb {
        width: 18px;
        height: 18px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 50%;
        cursor: pointer;
        border: none;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    }

    .pulse-animation {
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.05); }
    }

    .modal-backdrop {
        backdrop-filter: blur(8px);
        background: rgba(0, 0, 0, 0.5);
    }

    .floating-player {
        background: linear-gradient(135deg, rgba(255, 255, 255, 0.98), rgba(255, 255, 255, 0.95));
        backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.3);
    }

    .modern-pagination {
        display: flex;
        justify-content: center;
        gap: 0.5rem;
        margin-top: 1.5rem;
    }

    .modern-pagination a, .modern-pagination span {
        padding: 0.5rem 1rem;
        border-radius: 0.75rem;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .modern-pagination a {
        background: white;
        color: #667eea;
        border: 1px solid #e2e8f0;
    }

    .modern-pagination a:hover {
        background: #667eea;
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
    }

    .modern-pagination .current {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }

    .feature-icon {
        width: 3rem;
        height: 3rem;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.25rem;
        transition: all 0.3s ease;
    }

    .feature-icon:hover {
        transform: scale(1.1);
        box-shadow: 0 8px 20px rgba(102, 126, 234, 0.4);
    }

    .status-badge {
        padding: 0.25rem 0.75rem;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 600;
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
    }

    .modern-card {
        background: white;
        border-radius: 1rem;
        overflow: hidden;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
    }

    .modern-card:hover {
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
    }
</style>
@endpush

<div>

    <x-FrontTopNav />

    <div class="gradient-bg min-h-screen pt-4">
        <div class="p-3">
            <div x-data="{ activeTab: 'materi_ajaran' }" class="max-w-lg mx-auto mt-6">
                <!-- Modern Tab Navigation -->
                <div class="glass-effect rounded-2xl p-1 mb-6">
                    <div class="flex">
                        <button
                            x-on:click="activeTab = 'materi_ajaran'"
                            :class="{'bg-white text-gray-800 shadow-lg': activeTab == 'materi_ajaran', 'text-gray-600 hover:text-gray-800': activeTab !== 'materi_ajaran' }"
                            class="tab-modern flex-1 py-3 px-4 text-center font-semibold text-sm rounded-xl transition-all duration-300"
                        >
                            <i class="fas fa-book-open mr-2"></i>
                            MATERI AJAR
                        </button>
                        <button
                            x-on:click="activeTab = 'evaluasi'"
                            :class="{ 'bg-white text-gray-800 shadow-lg': activeTab == 'evaluasi', 'text-gray-600 hover:text-gray-800': activeTab !== 'evaluasi' }"
                            class="tab-modern flex-1 py-3 px-4 text-center font-semibold text-sm rounded-xl transition-all duration-300"
                        >
                            <i class="fas fa-clipboard-check mr-2"></i>
                            EVALUASI
                        </button>
                        <button
                            x-on:click="activeTab = 'lainnya'"
                            :class="{ 'bg-white text-gray-800 shadow-lg': activeTab == 'lainnya', 'text-gray-600 hover:text-gray-800': activeTab !== 'lainnya' }"
                            class="tab-modern flex-1 py-3 px-4 text-center font-semibold text-sm rounded-xl transition-all duration-300"
                        >
                            <i class="fas fa-ellipsis-h mr-2"></i>
                            LAINNYA
                        </button>
                    </div>
                </div>

                <!-- Content Container -->
                <div class="glass-effect rounded-3xl card-shadow overflow-hidden">
                    <!-- Header -->
                    <div class="bg-gradient-to-r from-blue-600 to-purple-600 px-6 py-4">
                        <h2 class="text-xl font-bold text-white">
                            <i class="fas fa-headphones mr-2"></i>
                            Materi Tersedia
                        </h2>
                    </div>

                    <!-- Materi Ajar Tab -->
                    <div class="px-6 py-6">
                        <div x-show="activeTab == 'materi_ajaran'">
                            @if(count($materi_detail))
                                <div class="space-y-4">
                                    @foreach($materi_detail as $key => $row)
                                    <div class="track-item modern-card p-4">
                                        <div class="flex items-center justify-between">
                                            <div class="flex-1">
                                                <div class="flex items-center gap-2 mb-2">
                                                    <span class="inline-flex items-center justify-center w-8 h-8 bg-gradient-to-r from-blue-500 to-purple-600 text-white rounded-full text-sm font-bold">
                                                        {{$row->materi_detail->pertemuan}}
                                                    </span>
                                                    <h3 class="font-semibold text-gray-800">
                                                        {{$row->materi_detail->judul}}
                                                    </h3>
                                                </div>
                                                <div class="flex items-center gap-4 text-sm text-gray-500 mb-2">
                                                    <div class="flex items-center gap-1">
                                                        <i class="fas fa-calendar-alt text-blue-500"></i>
                                                        <span class="text-xs">{{date('d-m-y', strtotime($row->tanggal))}}</span>
                                                    </div>
                                                    <div class="flex items-center gap-1 text-xs">
                                                        {{-- <i class="fas fa-users text-green-500"></i> --}}
                                                        <span>{{ $row->angkatan->kode_angkatan }}</span>
                                                    </div>
                                                </div>
                                                {{-- <div class="flex items-center gap-2">
                                                    <span class="status-badge">
                                                        <i class="fas fa-play mr-1"></i>
                                                        Audio Ready
                                                    </span>
                                                </div> --}}
                                            </div>
                                            <button onclick="open_modal({{$key}})" class="btn-gradient text-white px-6 py-3 rounded-xl font-semibold ml-4 hover:shadow-lg transform hover:scale-105 transition-all duration-300">
                                                <i id="playPauseIcon{{$key}}" class="fas fa-play mr-2"></i>
                                                Dengarkan
                                            </button>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>

                                <!-- Modern Pagination -->
                                <div class="modern-pagination mt-6">
                                    {{-- {{ $materi_detail->links() }} --}}

                                    <button wire:click="tambahPaginate" class="btn-gradient text-white px-6 py-3 rounded-xl font-semibold ml-4 hover:shadow-lg transform hover:scale-105 transition-all duration-300">
                                        Berikutnya
                                    </button>


                                </div>

                            @else  
                                <div class="text-center py-12">
                                    <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                        <i class="fas fa-music text-gray-400 text-2xl"></i>
                                    </div>
                                    <h3 class="text-lg font-semibold text-gray-600 mb-2">Belum Ada Materi</h3>
                                    <p class="text-gray-500">Materi pembelajaran akan segera tersedia</p>
                                </div>
                            @endif
                        </div>

                        <!-- Evaluasi Tab -->
                        <div x-show="activeTab == 'evaluasi'">
                            @if(count($evaluasi))
                                <div class="space-y-4">
                                    @foreach($evaluasi as $key=>$row)
                                    <div class="modern-card p-4">
                                        <div class="flex items-center justify-between">
                                            <div class="flex-1">
                                                <div class="flex items-center gap-2 mb-2">
                                                    <div class="feature-icon w-10 h-10">
                                                        <i class="fas fa-clipboard-check"></i>
                                                    </div>
                                                    <h3 class="font-semibold text-gray-800">
                                                        Ujian {{$row->jenis_ujian->nama}}
                                                    </h3>
                                                </div>
                                                <div class="flex items-center gap-4 text-sm text-gray-500 mb-2">
                                                    <div class="flex items-center gap-1">
                                                        <i class="fas fa-calendar-alt text-blue-500"></i>
                                                        <span>{{$row->created_at}}</span>
                                                    </div>
                                                    <span class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded-full text-xs font-medium">
                                                        {{-- {{$row->keterangan}} --}}
                                                    </span>
                                                </div>
                                            </div>
                                            <a href="{{route('evaluasi_kuis',['materi_id' => $row->materi_id , 'ujian_id' => $row->id])}}" class="btn-gradient text-white px-6 py-3 rounded-xl font-semibold ml-4 hover:shadow-lg transform hover:scale-105 transition-all duration-300">
                                                <i class="fas fa-eye mr-2"></i>
                                                Lihat
                                            </a>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-12">
                                    <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                        <i class="fas fa-clipboard-check text-gray-400 text-2xl"></i>
                                    </div>
                                    <h3 class="text-lg font-semibold text-gray-600 mb-2">Belum Ada Evaluasi</h3>
                                    <p class="text-gray-500">Evaluasi akan tersedia setelah materi selesai</p>
                                </div>
                            @endif
                        </div>

                        <!-- Lainnya Tab -->
                        <div x-show="activeTab == 'lainnya'">
                            <div class="space-y-4">
                                <div class="modern-card p-4 cursor-pointer hover:shadow-lg transition-all duration-300">
                                    <div class="flex items-center gap-4">
                                        <div class="feature-icon">
                                            <i class="fas fa-archive"></i>
                                        </div>
                                        <div class="flex-1">
                                            <h3 class="font-semibold text-gray-800">Arsip</h3>
                                            <p class="text-sm text-gray-500">Lihat materi yang sudah selesai</p>
                                        </div>
                                        <i class="fas fa-chevron-right text-gray-400"></i>
                                    </div>
                                </div>

                                <div class="modern-card p-4 cursor-pointer hover:shadow-lg transition-all duration-300">
                                    <div class="flex items-center gap-4">
                                        <div class="feature-icon">
                                            <i class="fas fa-book"></i>
                                        </div>
                                        <div class="flex-1">
                                            <h3 class="font-semibold text-gray-800">Silabus</h3>
                                            <p class="text-sm text-gray-500">Download silabus pembelajaran</p>
                                        </div>
                                        <i class="fas fa-chevron-right text-gray-400"></i>
                                    </div>
                                </div>

                                <div class="modern-card p-4 cursor-pointer hover:shadow-lg transition-all duration-300">
                                    <div class="flex items-center gap-4">
                                        <div class="feature-icon">
                                            <i class="fas fa-calendar"></i>
                                        </div>
                                        <div class="flex-1">
                                            <h3 class="font-semibold text-gray-800">Kalender Akademik</h3>
                                            <p class="text-sm text-gray-500">Jadwal kegiatan pembelajaran</p>
                                        </div>
                                        <i class="fas fa-chevron-right text-gray-400"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modern Audio Player Modal -->
    <x-modal.ModalPopup id="myModal" default="close">
        <div class="floating-player rounded-3xl p-8">
            <!-- Track Info -->
            <div class="text-center mb-8">
                <div class="w-32 h-32 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full mx-auto mb-4 flex items-center justify-center pulse-animation">
                    <i class="fas fa-music text-white text-3xl"></i>
                </div>
                <div class="track-name text-xl font-bold text-gray-800 mb-2">Judul Materi</div>
                <div class="track-artist text-gray-600">Mata Pelajaran</div>
            </div>

            <!-- Controls -->
            <div class="flex items-center justify-center gap-6 mb-8">
                <button onclick="prevTrack()" class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center hover:bg-gray-200 transition-colors">
                    <i class="fas fa-step-backward text-gray-700"></i>
                </button>
                <div class="playpause-track">
                    <button onclick="playpauseTrack()" class="w-16 h-16 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center hover:shadow-lg transform hover:scale-105 transition-all duration-300">
                        <i class="fas fa-play text-white text-xl"></i>
                    </button>
                </div>
                <button onclick="nextTrack()" class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center hover:bg-gray-200 transition-colors">
                    <i class="fas fa-step-forward text-gray-700"></i>
                </button>
            </div>

            <!-- Progress Bar -->
            <div class="mb-6">
                <div class="flex items-center gap-3">
                    <div class="current-time text-sm text-gray-600">00:00</div>
                    <input type="range" min="1" max="100" value="0" class="audio-slider flex-1 seek_slider" onchange="seekTo()">
                    <div class="total-duration text-sm text-gray-600">00:00</div>
                </div>
            </div>

            <!-- Volume Control -->
            <div class="mb-6">
                <div class="flex items-center gap-3">
                    <i class="fas fa-volume-down text-gray-600"></i>
                    <input type="range" min="1" max="100" value="99" class="audio-slider flex-1 volume_slider" onchange="setVolume()">
                    <i class="fas fa-volume-up text-gray-600"></i>
                </div>
            </div>

            <!-- Action Button -->
            <div class="text-center" id="tombolSoal">
                <button class="btn-gradient text-white w-full py-3 rounded-xl font-semibold hover:shadow-lg transform hover:scale-105 transition-all duration-300">
                    <i class="fas fa-pencil-alt mr-2"></i>
                    KERJAKAN SOAL
                </button>
            </div>
        </div>
    </x-modal.ModalPopup>

    <x-FrontBottomNav />

    <script>
        // Audio Player JavaScript (preserved original functionality)
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
        let curr_track = document.createElement('audio');

        // Define the tracks that have to be played (preserved original structure)
        let track_list = [
            @foreach($materi_detail as $key=>$row)
            {
                id: {{$row->id}},
                name: "{{$row->materi_detail->judul}}",
                artist: "{{ $row->materi_detail->materi->nama_materi }}",
                path: "storage/{{$row->materi_detail->multimedia_url}}",
                tanggal: "{{ date('d-m-Y' , strtotime( $row->tanggal )) }}"
            },
            @endforeach
        ];

        function showModal() {
            document.getElementById('myModal').classList.remove('hidden');
        }


        function open_modal(id){

        showModal() 

            track_index = id;
            console.log(track_list[track_index].path);
            loadTrack(track_index);
            playpauseTrack();
        }

        function loadTrack(track_index) {
            let materi = track_list[track_index];          

            clearInterval(updateTimer);
            resetValues();
            curr_track.src = materi.path;
            curr_track.load();
            track_name.textContent = materi.name;
            track_artist.textContent = materi.artist;
            updateTimer = setInterval(seekUpdate, 1000);
            curr_track.addEventListener("ended", nextTrack);

            let tombolSoal = document.getElementById('tombolSoal');
            if(materi.tanggal == '{{ date("d-m-Y") }}'){
                tombolSoal.style.display = 'block';
            } else {
                tombolSoal.style.display = 'none';
            }
        }

        function resetValues() {
            curr_time.textContent = "00:00";
            total_duration.textContent = "00:00";
            seek_slider.value = 0;
        }

        loadTrack(track_index);

        function playpauseTrack() {
            if (!isPlaying) playTrack();
            else pauseTrack();
        }

        function playTrack() {
            curr_track.play();
            isPlaying = true;
            playpause_btn.innerHTML = '<button onclick="playpauseTrack()" class="w-16 h-16 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center hover:shadow-lg transform hover:scale-105 transition-all duration-300"><i class="fas fa-pause text-white text-xl"></i></button>';
            if(document.getElementById('playPauseIcon'+track_index)) {
                document.getElementById('playPauseIcon'+track_index).classList.add('fa-pause');
                document.getElementById('playPauseIcon'+track_index).classList.remove('fa-play');
            }
        }

        function pauseTrack() {
            curr_track.pause();
            isPlaying = false;
            playpause_btn.innerHTML = '<button onclick="playpauseTrack()" class="w-16 h-16 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center hover:shadow-lg transform hover:scale-105 transition-all duration-300"><i class="fas fa-play text-white text-xl"></i></button>';
            if(document.getElementById('playPauseIcon'+track_index)) {
                document.getElementById('playPauseIcon'+track_index).classList.remove('fa-pause');
                document.getElementById('playPauseIcon'+track_index).classList.add('fa-play');
            }
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
    </script>


</div>