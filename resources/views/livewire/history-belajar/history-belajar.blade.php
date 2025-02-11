<div>
    @push('head')
    
    @endpush
        <x-FrontTopNav />

        <h2 class="p-5 text-xl" >Materi yang sudah Diikuti:</h2>

        <div class="p-5">
            @foreach($angkatan_user as $row)             
             
                <div class="grid grid-cols-[50%_25%_25%] gap-3 items-center mt-1 p-2 border-b-2">
                    <p class="font-semibold">{{ $no }}. {{$row->angkatan->materi->nama_materi}} <br>
                       <span class="ms-4 text-sm text-gray-500">   {{ date('d M Y' , strtotime( $row->angkatan->tanggal_mulai )) }}</span> 
                    </p>
                    
                    @if( isset( $ujian_akhir[$row->angkatan_id] ) )
                        <p class="text-center">
                            <a href="{{ route('daftar_nilai' , $ujian_akhir[$row->angkatan_id] ) }}" class="hover:text-blue-400 flex">
                                <svg class="w-6 me-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512"><!--!Font Awesome Free 6.5.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M0 64C0 28.7 28.7 0 64 0H224V128c0 17.7 14.3 32 32 32H384v38.6C310.1 219.5 256 287.4 256 368c0 59.1 29.1 111.3 73.7 143.3c-3.2 .5-6.4 .7-9.7 .7H64c-35.3 0-64-28.7-64-64V64zm384 64H256V0L384 128zM288 368a144 144 0 1 1 288 0 144 144 0 1 1 -288 0zm211.3-43.3c-6.2-6.2-16.4-6.2-22.6 0L416 385.4l-28.7-28.7c-6.2-6.2-16.4-6.2-22.6 0s-6.2 16.4 0 22.6l40 40c6.2 6.2 16.4 6.2 22.6 0l72-72c6.2-6.2 6.2-16.4 0-22.6z"/></svg>
                                Daftar Nilai
                            </a>
                        </p>


                        @if($row->predikat != 'Kurang')

                        <p class="flex">                        
                            <a href="{{ route('sertifikat' , $ujian_akhir[$row->angkatan_id]) }}" target="_blank" class="flex items-center">

                                <svg xmlns="http://www.w3.org/2000/svg" height="18" width="18" viewBox="0 0 512 512"><!--!Font Awesome Free 6.5.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2023 Fonticons, Inc.--><path d="M211 7.3C205 1 196-1.4 187.6 .8s-14.9 8.9-17.1 17.3L154.7 80.6l-62-17.5c-8.4-2.4-17.4 0-23.5 6.1s-8.5 15.1-6.1 23.5l17.5 62L18.1 170.6c-8.4 2.1-15 8.7-17.3 17.1S1 205 7.3 211l46.2 45L7.3 301C1 307-1.4 316 .8 324.4s8.9 14.9 17.3 17.1l62.5 15.8-17.5 62c-2.4 8.4 0 17.4 6.1 23.5s15.1 8.5 23.5 6.1l62-17.5 15.8 62.5c2.1 8.4 8.7 15 17.1 17.3s17.3-.2 23.4-6.4l45-46.2 45 46.2c6.1 6.2 15 8.7 23.4 6.4s14.9-8.9 17.1-17.3l15.8-62.5 62 17.5c8.4 2.4 17.4 0 23.5-6.1s8.5-15.1 6.1-23.5l-17.5-62 62.5-15.8c8.4-2.1 15-8.7 17.3-17.1s-.2-17.4-6.4-23.4l-46.2-45 46.2-45c6.2-6.1 8.7-15 6.4-23.4s-8.9-14.9-17.3-17.1l-62.5-15.8 17.5-62c2.4-8.4 0-17.4-6.1-23.5s-15.1-8.5-23.5-6.1l-62 17.5L341.4 18.1c-2.1-8.4-8.7-15-17.1-17.3S307 1 301 7.3L256 53.5 211 7.3z"/></svg>

                                <span class="ms-1 hover:text-blue-400">Sertifikat</span>
                                
                            </a>   
                        </p>

                        @endif



                    @endif




                </div>

                <?php $no++; ?>

            @endforeach
        </div>
    <x-FrontBottomNav />
</div>
