<x-filament::page>

    <h2 class="text-xl mb-0 py-0">#. Pendaftaran Peserta</h2>
       <p>
         Peserta Tanpa Kelas: <strong>{{ $peserta_tanpa_kelas }} </strong>  

       </p>

       <button wire:click.prevent="ambilPeserta" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">Daftarakan Ke Angkatan Ini</button>

    <h2 class="text-xl mb-0 py-0">1. Kalkulasi Kelulusan Peserta</h2>
    <p class="mt-0 py-0"></p>

    <div>
        <span>Lulus: <strong>{{ $this->record->angkatan_user()->lulus()->count() }}</strong></span>
        <span>Tidak Lulus: <strong>{{ $this->record->angkatan_user()->tidakLulus()->count() }}</strong></span>
        <span>Total: <strong>{{ $this->record->peserta->count()  }}</strong></span>
    </div>

    <button wire:click="kalkulasiKelulusan" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">Kalkulasi Nilai</button> <br>
    <span class="text-sm">Proses ini hanya boleh dilakukan setelah ujian akhir selesai</span>


    <h2 class="text-xl">2. Daftarkan Peserta Ke Angkatan Berikutnya</h2>

    <div class="sm:w-1/4">

        <label for="" class="block mb-2 text-normal font-medium text-gray-900 dark:text-white">Pilih Peserta di angkatan ini</label>
        <select wire:model.live="jenis_peserta" name="" id="" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
            <option value="semua">Semua</option>
            <option value="lulus">Yang Lulus Saja</option> 
            <option value="tidak_lulus">Yang Tidak Lulus</option>
        </select>

        @error('jenis_peserta')
        <p>{{ $message }}</p>
        @enderror

        <span>Peserta terpilih: {{ $peserta_terpilih }}</span>

    </div>

    <div class="sm:w-1/4">

        <label for="" class="block mb-2 text-normal font-medium text-gray-900 dark:text-white">Pilih Angkatan Berikutnya</label>
        <select wire:model="angkatan_selected" name="" id="" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
            <option value="">Pilih Angkatan</option>
            @foreach($angkatan as $row)
            <option value="{{ $row->id }}">{{ $row->kode_angkatan }}</option>
            @endforeach
        </select>

        @error('angkatan_selected')
        <p>{{ $message }}</p>
        @enderror


    </div>


    <button wire:click="daftarkanPeserta" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">Daftarkan Peserta</button>



    <h2 class="text-xl">3. Perbaiki Soal</h2>

    <button wire:click="getSoal" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">Get Soal</button>

    <h2 class="text-xl">4. Perbaiki Jadwal Ujian</h2>

    <button wire:click="getJadwal" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">Execute</button>

    <h2 class="text-xl">5. Generate Jadwal Belajar</h2>

    <button wire:click="buatJadwalBelajar" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">Buat Jadwal</button>


</x-filament::page>
