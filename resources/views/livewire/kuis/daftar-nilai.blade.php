
<div class="p-6 bg-blue-100">

    <style>
        .gambar-latar {

            background-image: url('{{ asset("/img/daftar-nilai.jpg") }}');
            background-repeat: no-repeat;
            background-size: auto;
            background-size: contain, cover;
        }

    </style>


    {{-- <link href="https://fonts.cdnfonts.com/css/certificate" rel="stylesheet"> --}}
    
    <div id="capture-me" class="rounded gambar-latar pt-60 w-[800px] mx-auto p-16 realtive"
   
    >   
        <img src="/img/logo.jpg" alt="" class="absolute opacity-5 -rotate-6 " style="height: 200px;">

        <div class="font-serif px-4 -mt-16 relative z-30">               

            <!-- data perserta -->
            <div class="text-left flex">

                <div>

                    <h2 class="" style="" >Nama: <strong>{{ $user->name }}</strong></h2>
                    <h2 class="" style="" >NIP: {{ $user->nip }}</h2>    

                </div>

                <div class="ms-6">

                    <h2 class="" style="" >Materi: {{ $ujian->materi->nama_materi }}</h2>
                    <h2 class="" style="" >Angkatan: {{ $angkatan->kode_angkatan }}</h2>
                   

                </div>                    

            </div>              

            <!-- daftar nilai -->  
                     

            <div class="mt-6 mb-3">
                <table class="text-sm text-left rtl:text-right text-gray-500 ms-12">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 bg-opacity-90">
                        <tr>
                            <th scope="col" class="px-6 py-2">
                                Ujian
                            </th>
                            <th scope="col" class="px-6 py-2">
                                Nilai
                            </th>
                            <th scope="col" class="px-6 py-2">
                                Grade
                            </th>
                            
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="bg-white border-b bg-opacity-60">
                            <th scope="row" class="px-6 py-1 font-medium text-gray-900 whitespace-nowrap">
                                Harian
                            </th>
                            <td class="px-6 py-0">
                                {{ $nilai_ujian["harian"] }}
                            </td>
                            <td class="px-6 py-0">
                                {{ $this->getPredikat($nilai_ujian["harian"] ) }}
                            </td>
                            
                        </tr>
                        <tr class="bg-white border-b bg-opacity-60">
                            <th scope="row" class="px-6 py-1 font-medium text-gray-900 whitespace-nowrap">
                                Pekanan
                            </th>
                            <td class="px-6 py-0">
                                {{ $nilai_ujian["pekanan"] }}
                            </td>
                            <td class="px-6 py-0">
                                {{ $this->getPredikat($nilai_ujian["pekanan"] ) }}
                            </td>
                            
                        </tr>
                        <tr class="bg-white bg-opacity-60">
                            <th scope="row" class="px-6 py-1 font-medium text-gray-900 whitespace-nowrap">
                                Ujian Akhir
                            </th>
                            <td class="px-6 py-0">
                                {{ $nilai_ujian["akhir"] }}
                            </td>
                            <td class="px-6 py-0">
                                {{ $this->getPredikat($nilai_ujian["akhir"] ) }}
                            </td>
                            
                        </tr>
                        <tr class="bg-white bg-opacity-60">
                            <th colspan="3" class="px-6 py-1 font-medium text-gray-900 whitespace-nowrap"> 
                               Nilai Akhir: <span><strong>{{ $nilai_ujian["total"] }}</strong></span> <br> IPK: <span class="font-bold">{{ $nilai_ujian["ipk"] }}</span>
                            </th>
                        </tr>
                    </tbody>
                </table>
            </div>


            <div class="text-center w-[60%]">  

                <p class="" >Grade Nilai: <span class="font-serif  capitalize">"{{ $ujian->predikat }}"</span> </p>
                
                <img src="data:image/png;base64,{{ $barcodeData }}" class="mx-auto mb-3 w-16 mt-2" alt="Barcode" />
                
                <P class=" ">Semoga Ilmunya Bermanfaat Dan Menjadi Amal Soleh</P>
                <p class="">Bogor, {{ $ujian->created_at->format('d M Y') }}</p>

                <img src="{{ asset('img/ttd2.png') }}" alt="" class="mx-auto -mb-4">
                
                <p class=" border-t-2 border-biru w-[70%] mx-auto pt-2 font-bold">Irfan Bahar Nurdin, S.Th.I., M.M.,</p>
                <p class="">Manager</p>


            </div>



        </div>
    </div> 

    <div class="w-[800px] mt-3 mx-auto">
        <button class="bg-blue-400 text-white p-2 mx-auto rounded-lg" id="download-button"><i class="fa-solid fa-download"></i> Unduh</button>
    </div>
    

    <script>
            document.getElementById('download-button').addEventListener('click', function () {
                // Get the HTML element to capture
                const elementToCapture = document.getElementById('capture-me');

                // Use html2canvas to capture the element
                html2canvas(elementToCapture).then(function(canvas) {
                    // Convert the canvas to a data URL
                    
                    const dataURL = canvas.toDataURL();

                    // Create a temporary link element to initiate the download
                    const a = document.createElement('a');
                    a.href = dataURL;
                    a.download = 'daftar-nilai-{{ $angkatan->kode_daftar }}.png'; // Change the filename and extension as needed
                    a.style.display = 'none';

                    // Append the link element to the document and trigger a click event
                    document.body.appendChild(a);
                    a.click();

                    // Clean up
                    document.body.removeChild(a);
                });
            });
        </script>
</div>


