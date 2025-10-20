<div>
  
    @push('head')

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        
        * {
            font-family: 'Inter', sans-serif;
        }

        .gradient-bg {
            background: linear-gradient(135deg, #9fcaf1ff 0%, #2f56d4ff 100%);
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
            background: linear-gradient(135deg, #9fcaf1ff 0%, #2f56d4ff 100%);
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
            box-shadow: 0 2px 2px -1px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .modern-card:hover {
            box-shadow: 0 8px 10px -5px rgba(0, 0, 0, 0.1);
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


    </style>
    
    @endpush
    

        <x-FrontTopNav />

        <br><br><br>


        <div x-data="{ activeTab: @entangle('activeTab') }" class="glass-effect rounded-2xl p-1 mb-6 bg-blue-200 mx-4">
            <div class="flex">
                <button
                    x-on:click="location.href='{{ route('history_belajar') }}?trial={{ request()->trial }}'"
                    :class="{'bg-white text-gray-800 shadow-lg': activeTab == 'history', 'text-gray-600 hover:text-gray-800': activeTab !== 'history' }"
                    class="tab-modern flex-1 py-3 px-4 text-center font-semibold text-sm rounded-xl transition-all duration-300"
                >
                    <i class="fas fa-book-open mr-2"></i>
                    HISTORY
                </button>
                <button
                    x-on:click="location.href='{{ route('sertifikat_saya') }}?trial={{ request()->trial }}'"
                    :class="{ 'bg-white text-gray-800 shadow-lg': activeTab == 'sertifikat', 'text-gray-600 hover:text-gray-800': activeTab !== 'sertifikat' }"
                    class="tab-modern flex-1 py-3 px-4 text-center font-semibold text-sm rounded-xl transition-all duration-300"
                >
                    <i class="fas fa-clipboard-check mr-2"></i>
                   SERTIFIKAT
                </button>
                <button
                    x-on:click="location.href='{{ route('history_belajar') }}?trial={{ request()->trial }}'"
                    :class="{ 'bg-white text-gray-800 shadow-lg': activeTab == 'daftar_nilai', 'text-gray-600 hover:text-gray-800': activeTab !== 'daftar_nilai' }"
                    class="tab-modern flex-1 py-3 px-4 text-center font-semibold text-sm rounded-xl transition-all duration-300"
                >
                    <i class="fas fa-ellipsis-h mr-2"></i>
                    DAFTAR NILAI
                </button>
            </div>
        </div>

        <h2 class="px-5 text-xl" >Sertifikat Saya</h2>

        <div class="p-5 glass-effect">
            @foreach($list_sertifikat as $row)

            {{-- {{ dd($row) }} --}}
            <div class="modern-card p-4 mb-4 flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold">{{ $row->materi->nama_materi }}</h3>
                    {{-- <p class="text-sm text-gray-600">Diterbitkan pada: {{ $row->tanggal }}</p> --}}
                </div>
                <div>
                    <a href="{{ route('sertifikat', ['code' => $row->code]) }}" class="btn-gradient text-white px-4 py-2 rounded-lg shadow-md hover:shadow-lg" target="_blank">
                        <i class="fas fa-download mr-2"></i> Unduh Sertifikat
                    </a>
                </div>
            </div>
            @endforeach

          
        </div>


    <x-FrontBottomNav />
</div>
