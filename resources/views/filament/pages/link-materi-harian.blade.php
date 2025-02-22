<x-filament::page>

    
    <div class="bg-white rounded p-3">

        <?php $formatter = new IntlDateFormatter('id_ID', IntlDateFormatter::FULL, IntlDateFormatter::NONE); ?>

        <h2 class="text-xl mb-2">Jadwal Pembelajaran Hari Ini: <span class="font-bold">{{ $formatter->format(new DateTime()) }}</span> </h2> 

    </div>

    <div class="rounded py-3">
        {{-- list materi hari ini --}}
        <div class="flex justify-between">
            <h2 class="text-xl font-bold my-2">Materi Hari ini</h2>

            {{-- <button class="text-center text-blue-600 flex disable">

                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 7.5h1.5m-1.5 3h1.5m-7.5 3h7.5m-7.5 3h7.5m3-9h3.375c.621 0 1.125.504 1.125 1.125V18a2.25 2.25 0 0 1-2.25 2.25M16.5 7.5V18a2.25 2.25 0 0 0 2.25 2.25M16.5 7.5V4.875c0-.621-.504-1.125-1.125-1.125H4.125C3.504 3.75 3 4.254 3 4.875V18a2.25 2.25 0 0 0 2.25 2.25h13.5M6 7.5h3v3H6v-3Z" />
                </svg>  
                <span class="ms-1">Text</span>            
                
            </button> --}}

        </div>

        <div class="w-full">
            <table class="min-w-full bg-white shadow-md rounded-lg overflow-hidden">
                <thead class="bg-sky-100">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Group
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Pertemuan
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Judul Pertemuan
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Link
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">

                    @if(count($materi_hari_ini) > 0)

                    @foreach($materi_hari_ini as $row)

                        <tr class="hover:bg-gray-50 transition-colors duration-200">
                            <td class="px-6 py-4">
                                <span class="bg-gray-200 px-3 py-1 rounded-full text-sm font-medium text-gray-700">
                                    Group {{ $row->angkatan->gelombang->gel }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">
                                {{ $row->materi_detail->pertemuan }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">
                                {{ $row->materi_detail->judul }}
                            </td>
                            <td class="px-6 py-4 text-sm font-medium">
                                <a 
                                    href="{{ route('link_materi' , $row->code) }}"
                                    class="text-blue-600 hover:bg-blue-100 px-3 py-1 rounded-md transition-colors duration-200 inline-block"
                                    target="_blank"
                                >
                                {{ route('link_materi' , $row->code) }}
                                </a>
                                {{-- <a 
                                    href="{{ route('link_materi' , $row->code) }}"
                                    class="text-blue-600 hover:bg-blue-100 px-3 py-1 rounded-md transition-colors duration-200 inline-block"
                                    target="_blank"
                                >
                                    Copy
                                </a> --}}
                            </td>
                        </tr>
                    @endforeach

                    @else
                    <td class="px-6 py-4">
                        <span class="bg-gray-200 px-3 py-1 rounded-full text-sm font-medium text-gray-700">
                            Belum ada materi
                        </span>
                    </td>

                    @endif

                </tbody>
            </table>
        </div>

    </div>


    @if(count($ujian_pekanan) > 0)

    <div class="rounded py-3">
        {{-- list materi hari ini --}}
        <h2 class="text-xl font-bold my-2">Ujian Pekanan</h2>

        <div class="w-full">
            <table class="min-w-full bg-white shadow-md rounded-lg overflow-hidden">
                <thead class="bg-orange-100">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Group
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Pekan
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Materi
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    

                        @foreach ($ujian_pekanan as $row)                        
                    
                        <tr class="hover:bg-gray-50 transition-colors duration-200">
                            <td class="px-6 py-4">
                                <span class="bg-gray-200 px-3 py-1 rounded-full text-sm font-medium text-gray-700">
                                    Group {{ $row->angkatan->gelombang->gel }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">
                                {{ $row->urutan }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">
                                {{ $row->angkatan->materi->nama_materi }}
                            </td>
                            <td class="px-6 py-4 text-sm font-medium space-x-2">
                                <a 
                                    href="{{ route('link_materi' , 'sadfadsf') }}"
                                    class="text-blue-600 hover:bg-blue-100 px-3 py-1 rounded-md transition-colors duration-200 inline-block"
                                    target="_blank"
                                >
                                    Lihat
                                </a>
                            
                            </td>
                        </tr>

                        @endforeach                   

                </tbody>
            </table>
        </div>

    </div>

    @endif


    @if(count($ujian_akhir) > 0)

    <div class="rounded py-3">
        {{-- list materi hari ini --}}
        <h2 class="text-xl font-bold my-2">Ujian Akhir</h2>

        <div class="w-full">
            <table class="min-w-full bg-white shadow-md rounded-lg overflow-hidden">
                <thead class="bg-indigo-100">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Group
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Materi
                        </th>                       
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    
                        @foreach ($ujian_akhir as $row) 

                        <tr class="hover:bg-gray-50 transition-colors duration-200">
                            <td class="px-6 py-4">
                                <span class="bg-gray-200 px-3 py-1 rounded-full text-sm font-medium text-gray-700">
                                    Group {{ $row->angkatan->gelombang->gel }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">
                                {{ $row->angkatan->materi->nama_materi }}
                            </td>
                        
                            <td class="px-6 py-4 text-sm font-medium space-x-2">
                                <a 
                                    href="{{ route('link_materi' , 'sadfadsf') }}"
                                    class="text-blue-600 hover:bg-blue-100 px-3 py-1 rounded-md transition-colors duration-200 inline-block"
                                    target="_blank"
                                >
                                    Lihat
                                </a>
                            
                            </td>
                        </tr>

                        @endforeach                   

                </tbody>
            </table>
        </div>

    </div>

    @endif



</x-filament::page>
