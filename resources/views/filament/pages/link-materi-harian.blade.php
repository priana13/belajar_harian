<x-filament::page>

    
    <div class="bg-white rounded p-3">

        <?php 
            
            $formatter = new IntlDateFormatter('id_ID', IntlDateFormatter::FULL, IntlDateFormatter::NONE);

        ?>
        <h2 class="text-xl mb-2">Jadwal Hari Ini: <span class="font-bold">{{ $formatter->format(new DateTime()) }}</span> </h2> 


        <dl class="max-w-md text-gray-900 divide-y divide-gray-200 dark:text-white dark:divide-gray-700 ms-3">                     

           
            <div class="flex flex-col pt-3">
                <dt class="mb-1 text-gray-500 md:text-lg dark:text-gray-400">Gelombang/Group Wa</dt>
                <dd class="text-lg font-semibold"> Group -</dd>
            </div>
        </dl>



    </div>

    <div class="bg-white rounded py-3 px-12">
        {{-- list materi hari ini --}}
        <h2 class="text-xl font-bold my-2">Materi Hari ini</h2>

        <div class="w-full max-w-4xl">
            <table class="min-w-full bg-white shadow-md rounded-lg overflow-hidden">
                <thead class="bg-gray-50">
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
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <tr class="hover:bg-gray-50 transition-colors duration-200">
                        <td class="px-6 py-4">
                            <span class="bg-gray-200 px-3 py-1 rounded-full text-sm font-medium text-gray-700">
                                Group
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500">
                            Pertemuan
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500">
                            Judul pertemuan
                        </td>
                        <td class="px-6 py-4 text-sm font-medium space-x-2">
                            <a 
                                href="{{ route('link_materi' , 'sadfadsf') }}"
                                class="text-blue-600 hover:bg-blue-100 px-3 py-1 rounded-md transition-colors duration-200 inline-block"
                                target="_blank"
                            >
                                Lihat
                            </a>
                            <a 
                                href="{{ route('link_materi' , 'sadfadsf') }}"
                                class="text-blue-600 hover:bg-blue-100 px-3 py-1 rounded-md transition-colors duration-200 inline-block"
                                target="_blank"
                            >
                                Copy
                            </a>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

    </div>


    <div class="bg-white rounded py-3 px-12">
        {{-- list materi hari ini --}}
        <h2 class="text-xl font-bold my-2">Ujian Pekanan</h2>

        <div class="w-full max-w-4xl">
            <table class="min-w-full bg-white shadow-md rounded-lg overflow-hidden">
                <thead class="bg-gray-50">
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
                    <tr class="hover:bg-gray-50 transition-colors duration-200">
                        <td class="px-6 py-4">
                            <span class="bg-gray-200 px-3 py-1 rounded-full text-sm font-medium text-gray-700">
                                Group
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500">
                            1
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500">
                            Fiqih
                        </td>
                        <td class="px-6 py-4 text-sm font-medium space-x-2">
                            <a 
                                href="{{ route('link_materi' , 'sadfadsf') }}"
                                class="text-blue-600 hover:bg-blue-100 px-3 py-1 rounded-md transition-colors duration-200 inline-block"
                                target="_blank"
                            >
                                Lihat
                            </a>
                            <a 
                                href="{{ route('link_materi' , 'sadfadsf') }}"
                                class="text-blue-600 hover:bg-blue-100 px-3 py-1 rounded-md transition-colors duration-200 inline-block"
                                target="_blank"
                            >
                                Copy
                            </a>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

    </div>



    <div class="bg-white rounded py-3 px-12">
        {{-- list materi hari ini --}}
        <h2 class="text-xl font-bold my-2">Ujian Akhir</h2>

        <div class="w-full max-w-4xl">
            <table class="min-w-full bg-white shadow-md rounded-lg overflow-hidden">
                <thead class="bg-gray-50">
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
                    <tr class="hover:bg-gray-50 transition-colors duration-200">
                        <td class="px-6 py-4">
                            <span class="bg-gray-200 px-3 py-1 rounded-full text-sm font-medium text-gray-700">
                                Group
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500">
                            Akhlak
                        </td>
                      
                        <td class="px-6 py-4 text-sm font-medium space-x-2">
                            <a 
                                href="{{ route('link_materi' , 'sadfadsf') }}"
                                class="text-blue-600 hover:bg-blue-100 px-3 py-1 rounded-md transition-colors duration-200 inline-block"
                                target="_blank"
                            >
                                Lihat
                            </a>
                            <a 
                                href="{{ route('link_materi' , 'sadfadsf') }}"
                                class="text-blue-600 hover:bg-blue-100 px-3 py-1 rounded-md transition-colors duration-200 inline-block"
                                target="_blank"
                            >
                                Copy
                            </a>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

    </div>



</x-filament::page>
