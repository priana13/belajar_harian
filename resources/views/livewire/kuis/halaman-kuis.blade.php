<div >
@push('styles')

@endpush
    <x-FrontTopNav />

    <br>
    <br>

    @if($ujian)   

    <div class="p-5">
        <p class="font-semibold">Soal {{ $ujian->jenis_ujian->nama }}</p>
        <p class="mb-2">Materi: <strong>{{ $ujian->materi->nama_materi }}</strong> </p>
        <div class="flex gap-5">
            <div class="flex items-center gap-1.5">
                <i class="fa-solid fa-clipboard-list text-primary"></i>
                <p class="">{{count($list_soal)}} Soal</p>
            </div>
            <div class="flex items-center gap-1.5">
                <i class="fa-solid fa-stopwatch text-primary"></i>
                <p class="">{{$ujian->jenis_ujian->waktu}}  MENIT</p>
            </div>
        </div>
        <p class="font-bold py-3">PERATURAN UJIAN</p>
        <div class="bg-gray-300 w-full mb-3 p-2 rounded-md">Kerjakan soal berikut dengan seksama, periksa kembali jawaban jika masih ada waktu tersisa</div>
        {{-- <div class="bg-gray-300 w-full h-7 mb-3"></div> --}}
        @foreach($list_soal as $row)
        <div class="border-b px-4 py-3  leading-5 mt-1">
            <ol class="list-decimal">
                <li value="{{$row['nomor']}}">
                    <p class="text-justify ">{!! $row['judul']!!}</p>

                    @error('jawaban.' . $row['nomor']) <span class="text-red-500">{{ $message }}</span> @enderror

                    <div class=" mt-3">
                        <div class="grid grid-cols-[19px_auto] mt-2">
                            <div>
                                <input class="" wire:model="jawaban.{{ $row['nomor'] }}" wire:click="simpan_jawaban({{$row['id']}},'a')" value="a"  type="radio" name="soal{{$row['nomor']}}" id="a{{$row['id']}}" >
                            </div>
                            <div>
                                <label class="" for="a{{$row['id']}}">
                                   A. {{$row['a']}}
                                </label>  
                            </div>
                        </div>
                        <div class="grid grid-cols-[19px_auto] mt-2">
                            <div>
                                <input class="" wire:model="jawaban.{{ $row['nomor'] }}"  wire:click="simpan_jawaban({{$row['id']}},'b')" value="b" type="radio" name="soal{{$row['nomor']}}" id="b{{$row['id']}}">
                            </div>
                            <div>
                                <label class="" for="b{{$row['id']}}">
                                   B. {{$row['b']}}
                                </label>  
                            </div>
                        </div>

                        {{-- @if($jenis_ujian[$row['jenis_ujian_id']] != 'Harian') --}}

                      

                        <div class="grid grid-cols-[19px_auto] mt-2">
                            <div>
                                <input class="" wire:model="jawaban.{{ $row['nomor'] }}"  wire:click="simpan_jawaban({{$row['id']}},'c')" value="c" type="radio" name="soal{{$row['nomor']}}" id="c{{$row['id']}}">
                            </div>
                            <div>
                                <label class="" for="c{{$row['id']}}">
                                    C. {{$row['c']}}
                                </label>  
                            </div>
                        </div>
                      

                    </div>
                </li>
            </ol>  
        </div> 
        @endforeach   
        
        
        <div class="flex sticky bottom-0 justify-between items-center pt-12 pb-24 ">
            <p class="p-3 w-52  text-blue-500 font-bold text-center ">
                TERJAWAB {{count($jawaban_user)}} DARI {{count($list_soal)}}
            </p> 
            {{-- <div class="flex sticky top-0 ">
                <button class="py-3 px-5  rounded-l-full bg-accent font-bold text-center">
                    <i class="fa-solid fa-magnifying-glass-plus"></i>
                </button> 
                <button class="py-3 px-5 border-l-2 rounded-r-full bg-accent font-bold text-center">
                    <i class="fa-solid fa-magnifying-glass-plus"></i>
                </button>  
            </div>  --}}
        </div>
        
    </div>
    <x-modal.ModalPopup id="myModal1" default="close">
        <div class="grid place-items-center">
            <p class="py-5 font-bold">YAKIN KIRIM JAWABAN?</p>
            <i class="fa-solid fa-paper-plane text-primary text-8xl mr-5"></i>
            <div class="text-center">
                <button class="bg-danger-600 w-52 py-2 mx-auto  text-white font-bold mt-4 rounded-lg"  wire:click="evaluasi" type="button">KIRIM JAWABAN</button><br>
                <button class="bg-secondary w-52 py-2 mx-auto  text-white font-bold mt-2 rounded-lg" >PERIKSA LAGI</button>
            </div>
        </div>
    </x-modal.ModalPopup>

    @endif


    <section class="fixed flex justify-between items-center inset-x-0 -bottom-[1px] z-5 bg-primary-700 shadow-top  py-5 px-9 max-w-lg mx-auto text-gray-400">
        <div class="flex items-center gap-1.5">
            <i class="fa-solid fa-stopwatch text-white"></i>
            <p id="countdown" wire:ignore class="text-white"></p>
        </div>      
        <button type="button" class="text-gray-900 bg-gradient-to-r from-lime-200 via-lime-400 to-lime-500 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-lime-300 dark:focus:ring-lime-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2 open-modal" data-modal-id="myModal1">Kirim Jawaban</button>  
    </section> 
    <script>
        @foreach($jawaban_user as $row)
            document.getElementById("{{$row['jawaban'].$row['soal_id']}}").checked = true;
        @endforeach



        // Get the start_time and finish_time from your API as strings
        const start_time_str = "{{$mulai}}";
        const finish_time_str =  "{{$sampai}}";

        // Parse the time strings into Date objects
        const startTime = new Date();
        const finishTime = new Date();
        const [startHour, startMinute] = start_time_str.split(":").map(Number);
        const [finishHour, finishMinute] = finish_time_str.split(":").map(Number);
        startTime.setHours(startHour, startMinute, 0, 0);
        finishTime.setHours(finishHour, finishMinute, 0, 0);

        function updateCountdown() {
            const currentTime = new Date();
            const timeDifference = finishTime - currentTime;

            if (timeDifference <= 0) {

                document.getElementById("countdown").innerHTML = "-";

                //livewire.emit('expired');

            } else {
                const hours = Math.floor(timeDifference / (1000 * 60 * 60));
                const minutes = Math.floor((timeDifference % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((timeDifference % (1000 * 60)) / 1000);

                const countdownText = `${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
                document.getElementById("countdown").innerHTML = countdownText;
            }
        }

        // Update the countdown every second
        setInterval(updateCountdown, 1000);

        // Initial update
        updateCountdown();
    </script>    
</div>

