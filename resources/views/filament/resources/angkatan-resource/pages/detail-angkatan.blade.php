<x-filament::page>

    <div class="bg-white rounded p-3 flex justify-between">
        <div>
            <h2>Materi: <strong>{{ $record->kode_angkatan }}</strong> </h2>
            <h2>Group: <strong>{{ $record->gelombang->gel }}</strong></h2>            
        </div>

        <div>
            <span>Peserta: {{ $record->peserta->count() }}</span>
        </div>
    </div>

    <div class="bg-white rounded p-3">

        <?php 
            
            $formatter = new IntlDateFormatter('id_ID', IntlDateFormatter::FULL, IntlDateFormatter::NONE);

        ?>
        <h2 class="text-xl mb-2">Materi Hari ini: <span class="font-bold">{{ $formatter->format(new DateTime()) }}</span> </h2> 


        <dl class="max-w-md text-gray-900 divide-y divide-gray-200 dark:text-white dark:divide-gray-700 ms-3">
           
            <div class="flex flex-col py-3">
                <dt class="mb-1 text-gray-500 md:text-lg dark:text-gray-400">Link Materi</dt>
                <dd class="text-lg font-semibold">
                    <a href="{{ route('link_materi' , $materi_hari_ini->code) }}" target="_blank" class="text-blue-700">{{ route('link_materi' , $materi_hari_ini->code) }}</a> <br>
                   <span class="text-gray-500 md:text-lg dark:text-gray-400">Pertemuan {{ $materi_hari_ini->materi_detail->pertemuan }} - {{ $materi_hari_ini->materi_detail->judul }}</span>
                </dd>
            </div>
            <div class="flex flex-col pt-3">
                <dt class="mb-1 text-gray-500 md:text-lg dark:text-gray-400">Gelombang/Group Wa</dt>
                <dd class="text-lg font-semibold"> Group {{ $record->gelombang->gel }} </dd>
            </div>
        </dl>



    </div>

    <div class="bg-white rounded p-3">
        {{-- list materi hari ini --}}

        <ul>
            @foreach($jadwal_belajar as $jadwal)
            <li> 
                Pertemuan {{ $jadwal->materi_detail->pertemuan }} : 
                Tanggal:  {{ $jadwal->tanggal }} ,                
                <a href="{{ route('link_materi' , $jadwal->code) }}" class="text-blue-700">test</a>

            </li>
            @endforeach
        </ul>

    </div>



</x-filament::page>
