 <div>
 @push('styles')

@endpush
    <x-FrontTopNav />
    <div class="px-3 py-5">

        <br>
        <br>
        <p class="font-bold">Kategori: Peringkat Umum</p>
        <p class="text-sm text-gray-600">Data peringkat berdasarkan ujian setahun terakhir</p>

        <div class="pb-5 px-5 bg-white rounded-lg border shadow-sm my-3">
            <div class="flex justify-between items-center border-primary py-4">
                <div>
                    <p class="font-bold text-sm text-primary"><i class="fa-solid fa-graduation-cap"></i>{{auth()->user()->nip}}</p>
                    <p class="font-bold text-sm mt-1"><i class="fa-solid fa-user"></i> {{$user->name}}</p>
                </div>
                <div class="flex gap-3">
                    {{-- <div class="bg-accent text-center text-primary font-bold p-2 rounded-lg">
                        <p>Nilai</p>
                        <p>{{ $nilai_saya }}</p>
                    </div> --}}
                    <div class="bg-primary-500 text-center text-white font-bold p-2 rounded-lg">
                        <p>Peringkat</p>
                        <p>{{ $peringkat_saya }}</p>
                    </div>
                </div>
            </div>
            {{-- <p class="font-bold text-sm text-primary mt-3"><i class="fa-solid fa-book"></i> {{$angkatan->materi->nama_materi}}</p> --}}
        </div>

           
        @foreach($peringkat_user->where('total_nilai', '>', 0) as $row)   


                <div class="grid grid-cols-[10%_90%] border-b pb-2 my-2">
                    <p class="text-center font-bold text-sm">{{ $nomor_urut }}.</p>
                    <div>
                        <div class="flex items-center gap-1 font-bold">
                            <p class="capitalize">{{$row['nama']}}</p>
                        </div>
                   

                        <div class="flex items-center gap-1 font-bold ">
                            {{-- <p class="text-primary text-sm">{{ $row['total_soal'] }}/{{ $row['jawaban_benar'] }} </p>
                            <p class="text-primary">·</p> --}}
                            <p class="text-primary text-sm">Score: {{ $row['total_nilai'] }}</p>
                            <p class="text-primary hidden">·</p>
                            {{-- <p class="text-primary text-sm hidden">1312s</p> --}}
                        </div>
                    </div>
                </div>
            {{-- @endif --}}

            <?php 
                $nomor_urut ++;
            ?>
        @endforeach

    </div>
    <x-FrontBottomNav />

 </div>  

