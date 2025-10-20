<div class="mt-5"> 
    <br>
    <br>
    @if(count($jadwal_berikutnya) > 0)

    <h3 class="mb-6 text-xl font-bold text-neutral-700 dark:text-neutral-300 border-b py-2">
        Materi Berikutnya
    </h3>

    @endif

    <ol class="border-s-2 border-info-100">
    <!--First item-->

    @foreach( $jadwal_berikutnya as $row)
    <li>
        <div class="flex-start md:flex">
        <div
            class="-ms-[13px] flex h-[25px] w-[25px] items-center justify-center rounded-full bg-info-100 text-info-700">
            <svg
            xmlns="http://www.w3.org/2000/svg"
            viewBox="0 0 24 24"
            fill="currentColor"
            class="h-4 w-4">
            <path
                fill-rule="evenodd"
                d="M6.75 2.25A.75.75 0 017.5 3v1.5h9V3A.75.75 0 0118 3v1.5h.75a3 3 0 013 3v11.25a3 3 0 01-3 3H5.25a3 3 0 01-3-3V7.5a3 3 0 013-3H6V3a.75.75 0 01.75-.75zm13.5 9a1.5 1.5 0 00-1.5-1.5H5.25a1.5 1.5 0 00-1.5 1.5v7.5a1.5 1.5 0 001.5 1.5h13.5a1.5 1.5 0 001.5-1.5v-7.5z"
                clip-rule="evenodd" />
            </svg>
        </div>
        <div
            class="mb-10 ms-6 block w-full rounded-lg bg-neutral-50 p-6 shadow-md shadow-black/5 dark:bg-neutral-700 dark:shadow-black/10">
            <div class="mb-4 relative pt-4">
            <p               
                class="text-sm text-info transition duration-150 ease-in-out hover:text-info-600 focus:text-info-600 active:text-info-700"
                >Materi Audio: {{ $row->materi_detail->judul }}</p
            >
            <a
                href="#!"
                class="text-sm text-info transition duration-150 ease-in-out hover:text-info-600 focus:text-info-600 active:text-info-700 absolute -left-6 -top-6 bg-gray-200 rounded px-2 py-1"
                >{{ \Carbon\Carbon::parse($row->tanggal)->translatedFormat('l, d F Y') }}</a
            >
            </div>
         
            {{--        
            <button
            type="button"
            class="inline-block rounded border-2 border-info px-4 pb-[3px] pt-[4px] text-xs font-medium uppercase leading-normal text-info transition duration-150 ease-in-out hover:border-info-600 hover:bg-neutral-500 hover:bg-opacity-10 hover:text-info-600 focus:border-info-600 focus:text-info-600 focus:outline-none focus:ring-0 active:border-info-700 active:text-info-700 dark:hover:bg-neutral-100 dark:hover:bg-opacity-10"
            data-twe-ripple-init>
            See demo
            </button> --}}
        </div>
        </div>
    </li>

    @endforeach


 @foreach( $jadwal_ujian as $row2)
    <li>
        <div class="flex-start md:flex">
        <div
            class="-ms-[13px] flex h-[25px] w-[25px] items-center justify-center rounded-full bg-info-100 text-info-700">
            <svg
            xmlns="http://www.w3.org/2000/svg"
            viewBox="0 0 24 24"
            fill="currentColor"
            class="h-4 w-4">
            <path
                fill-rule="evenodd"
                d="M6.75 2.25A.75.75 0 017.5 3v1.5h9V3A.75.75 0 0118 3v1.5h.75a3 3 0 013 3v11.25a3 3 0 01-3 3H5.25a3 3 0 01-3-3V7.5a3 3 0 013-3H6V3a.75.75 0 01.75-.75zm13.5 9a1.5 1.5 0 00-1.5-1.5H5.25a1.5 1.5 0 00-1.5 1.5v7.5a1.5 1.5 0 001.5 1.5h13.5a1.5 1.5 0 001.5-1.5v-7.5z"
                clip-rule="evenodd" />
            </svg>
        </div>
        <div
            class="mb-10 ms-6 block w-full rounded-lg bg-neutral-50 p-6 shadow-md shadow-black/5 dark:bg-neutral-700 dark:shadow-black/10">
            <div class="mb-4 relative pt-4">
            <p  class="text-sm text-info transition duration-150 ease-in-out hover:text-info-600 focus:text-info-600 active:text-info-700">
                Evaluasi {{ $row2->type }} - Materi {{ $row2->materi->nama_materi }}
            </p>


            <a
                href="#!"
                class="text-sm text-info transition duration-150 ease-in-out hover:text-info-600 focus:text-info-600 active:text-info-700 absolute -left-6 -top-6 bg-gray-200 rounded px-2 py-1"
                >{{ date("d F Y" , strtotime($row2->tanggal)) }}</a
            >
            </div>
         
          
        </div>
        </div>
    </li>

    @endforeach



 

    </ol>

</div>