
<div>


    <link href="https://fonts.cdnfonts.com/css/certificate" rel="stylesheet">

    <div id="capture-me" class="mx-auto flex justify-center items-center ">
            <img src="/img/serifikat2.jpg" alt="" class=" shadow-lg"> 
            <div class="absolute z-30 mt-10 text-center max-w-3xl font-serif ">
                <div class="flex justify-end">
                    {{-- <img class="h-16 w-auto self-end" src="{{ url('storage/') }}/icon/logo.jpg" alt=""> --}}
                </div>              

                {{-- <h2 style="font-size:70px" >𝕊𝕖𝕣𝕥𝕚𝕗𝕚𝕜𝕒𝕥</h2>

                <h2 style="font-size:60px" >Pernghargaan</h2> --}}

                <h2 class="mt-40" style="font-size:30px" >Penghargaan ini diberikan kepada:</h2>
               
                <p style="font-size:60px" class="capitalize border-b-2 border-biru mb-2 text-biru">{{$user->name}}</p>

                <p class="text-2xl">Telah berhasil menyelesaikan Bimbingan Instensif Studi Islam Online</a></p>
                
                <p class="text-2xl" >Paket: <span class="font-extrabold">"{{ $ujian->materi->nama_materi }}"</span> </p>

                <p class="text-2xl" >Dengan Nilai: <span class="text-3xl font-serif font-extrabold my-2 uppercase">"{{ $ujian->predikat }}"</span> </p>
                
                <img src="data:image/png;base64,{{ $barcodeData }}" class="mx-auto mb-3 mt-6" alt="Barcode" />
                
                <P class=" text-2xl">Semoga Ilmunya Bermanfaat Dan Menjadi Amal Soleh</P>
                <p class="text-xl mt-5">Bogor, {{ $ujian->created_at->format('d M Y') }}</p>

                <img src="{{ asset('img/ttd2.png') }}" alt="" class="mx-auto">
                
                <p class="text-2xl font-extrabold border-t-2 border-biru w-[70%] mx-auto pt-2">Irfan Bahar Nurdin, S.Th.I, M.M.,</p>
                <p class="text-xl">Manager</p>
            </div>
    </div> 
    <div>

    </div>
    <button class="bg-blue-400 text-white p-4 lg:text-xl text-4xl fixed top-5 ml-3 font-bold rounded-lg" id="download-button"><i class="fa-solid fa-download"></i> Unduh Srtifikat</button>

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
                    a.download = 'Sertifikat.png'; // Change the filename and extension as needed
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


