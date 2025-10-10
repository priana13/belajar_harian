<x-filament::page>

 <h2 class="text-xl font-bold">Judul: {{ $record->nama_roadmap }}</h2>

    <div class="sm:w-1/4">

        <label for="" class="block mb-2 text-normal font-medium text-gray-900 dark:text-white">Pilih Gelombang</label>
        <select wire:model.live="gelombang" name="" id="" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
            
            @foreach($list_gelombang as $row)      
            
            <option value="{{ $row->id }}">Gelombang {{ $row->gel }}</option>           
            
            @endforeach

            <option value="semua">Semua Gelombang</option>
        </select>

        @error('gelombang')
        <p>{{ $message }}</p>
        @enderror

        <label for="" class="block mb-2 text-normal font-medium text-gray-900 dark:text-white">Bulan Mulai Belajar</label>

        <input wire:model.live="tanggal_mulai" type="month" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" />
  
        <button wire:click="tampilkan" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800 mt-3">Tampilkan</button>


        <button wire:click="buatJadwal" class="text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-red-600 dark:hover:bg-red-700 focus:outline-none dark:focus:ring-red-800 mt-3">Buat Jadwal Bulanan</button>

    </div>  


    <div class="">
    
        @if($record->jadwalRoadmaps)
            <h2 class="text-xl font-bold mt-5">Jadwal Roadmap</h2>
            <table class="table-auto border-collapse border border-slate-400 mt-3">
                <thead>
                    <tr>
                        {{-- <th class="border border-slate-300 px-4 py-2">No</th> --}}
                        <th class="border border-slate-300 px-4 py-2">Gelombang</th>
                        <th class="border border-slate-300 px-4 py-2">Materi</th>                       
                        <th class="border border-slate-300 px-4 py-2">Tanggal Mulai</th>
                        <th class="border border-slate-300 px-4 py-2">Tanggal Ujian</th>
                        <th class="border border-slate-300 px-4 py-2">Jadwal Belajar</th>
                        <th class="border border-slate-300 px-4 py-2">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($record->jadwalRoadmaps()->orderBy('tanggal_mulai')->get() as $index => $row)
                    <tr>
                        {{-- <td class="border border-slate-300 px-4 py-2">{{ $index + 1 }}</td> --}}
                        <td class="border border-slate-300 px-4 py-2">{{ $row->gelombang->gel }}</td>
                        <td class="border border-slate-300 px-4 py-2">{{ $row->materi->nama_materi }}</td>
                        <td class="border border-slate-300 px-4 py-2">{{ \Carbon\Carbon::parse($row->tanggal_mulai)->format('d F Y') }}</td>
                        <td class="border border-slate-300 px-4 py-2">{{ $row->tanggal_ujian ? \Carbon\Carbon::parse($row->tanggal_ujian)->format('d F Y') : '-' }}</td>
                        <td class="border border-slate-300 px-4 py-2">{{ ($row->jadwal_belajar->count() > 0)  ? "Ready" : "-"}}</td>
                        <td class="border border-slate-300 px-4 py-2">
                            <button wire:click="hapusJadwal({{ $row->id }})" class="text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-red-600 dark:hover:bg-red-700 focus:outline-none dark:focus:ring-red-800 mt-3">Hapus</button>
                            <button wire:click="buatJadwalHarian({{ $row->id }})" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800 mt-3">Buat Jadwal Harian</button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @endif


    </div>




</x-filament::page>
