@section('title', 'Kuis')
<div >
@push('head')

@endpush
    <x-FrontTopNav />
    <br>
    <br>
   
    <div class="p-5">
        <p class="font-semibold my-2 uppercase"> HASIL EVALUASI {{ $ujian->jenis_ujian->nama }}</p>
  
        <p>Materi: {{ $ujian->materi->nama_materi }}</p>
        <div class="flex justify-between items-center pb-2 border-b">
            <div class="mt-3">
                <p class="">Nilai &emsp; : {{$ujian->nilai}}</p>
                <p class="">Benar&emsp;: {{ $ujian->soal_ujian()->benar()->count() }} </p>
                <p class="">Salah &emsp;: {{ $ujian->soal_ujian()->salah()->count() }}</p>
            </div>
            <div class="">
                <p class="">Tanggal&emsp;:{{date('d M Y', strtotime($ujian->created_at))}} </p>
                <p class="">Durasi &emsp; :  {{$ujian->jenis_ujian->waktu}} Menit</p>
            </div>
        </div>

          
        <?php $nomor=1; ?>
        @foreach($ujian->soal_ujian as $row)

        {{-- 
        "soal_id" => 8
        "ujian_id" => 9
        "user_id" => 2
        "jawaban" => "a"
        "istrue" => 0 
        --}}

    
        <div class="border-b px-4 py-3  leading-5 mt-1">
            <ol class="list-decimal">
                <li value="{{$nomor}}">
                    <p class="text-justify ">{!! $row->soal->judul !!}</p>
                    <div class=" mt-3">
                        <div class="grid grid-cols-[19px_auto] mt-2">
                            <div>
                                <input class=""   type="radio" name="soal{{$row->soal->nomor}}" {{ ($row->jawaban == 'a')? 'checked':'' }} disabled id="a{{$row->id}}">
                            </div>
                            <div>
                                <label class="" for="a{{$row->id}}">
                                   A. {{$row->soal->a}}
                                </label> 

                                @if($row->jawaban == 'a')

                                    @if($row->istrue)
                                        <a class="bg-accent text-primary px-2 rounded-md text-sm">
                                            <i class="fa-solid fa-check"></i> 
                                            Benar
                                        </a>
                                    @else 

                                        <a class="bg-red-200 text-red-500 px-2 rounded-md text-sm">
                                            <i class="fa-solid fa-x"></i>
                                            Salah
                                        </a>

                                    @endif

                                @endif
                              
                            </div>
                        </div>
                        <div class="grid grid-cols-[19px_auto] mt-2">
                            <div>
                                <input class=""  type="radio" name="soal{{$row->nomor}}" id="b{{$row->id}}" {{ ($row->jawaban == 'b')?'checked':'' }} checked disabled >
                            </div>
                            <div>
                                <label class="" for="b{{$row->id}}">
                                   B. {{$row->soal->b}}
                                </label>  
                               

                                @if($row->jawaban == 'b')

                                @if($row->istrue == 1)
                                    <a class="bg-accent text-primary px-2 rounded-md text-sm">
                                        <i class="fa-solid fa-check"></i> 
                                        Benar
                                    </a>
                                @else 

                                    <a class="bg-red-200 text-red-500 px-2 rounded-md text-sm">
                                        <i class="fa-solid fa-x"></i>
                                        Salah
                                    </a>
                                    
                                @endif

                            @endif

                            </div>
                        </div>
                        {{-- @if($jenis_ujian[$row['jenis_ujian_id']] != 'Harian') --}}

                            <div class="grid grid-cols-[19px_auto] mt-2">
                                <div>
                                    <input class="" value="c{{$row->nomor}}" type="radio" name="soal{{$row->nomor}}" {{ ($row->jawaban == 'c')? 'checked':'' }} id="c{{$row['id']}}" disabled>
                                </div>
                                <div>
                                    <label class="" for="c{{$row->id}}">
                                        C. {{$row->soal->c}}
                                    </label>
                                    
                                    @if($row->jawaban == 'c')

                                        @if($row->istrue)
                                            <a class="bg-accent text-primary px-2 rounded-md text-sm">
                                                <i class="fa-solid fa-check"></i> 
                                                Benar
                                            </a>
                                        @else 

                                            <a class="bg-red-200 text-red-500 px-2 rounded-md text-sm">
                                                <i class="fa-solid fa-x"></i>
                                                Salah
                                            </a>
                                            
                                        @endif

                                @endif

                                </div>
                            </div>
                        {{-- @endif --}}
                        <!-- <div class="grid grid-cols-[19px_auto] mt-2">
                            <div>
                                <input class="" value="d" type="radio" name="soal{{$row->nomor}}" id="d{{$row['id']}}">
                            </div>
                            <div>
                                <label class=""  for="d{{$row->id}}">
                                    D. {{$row->d}}
                                </label>  
                            </div>
                        </div> -->
                    </div>
                </li>
            </ol>  
            <div class="flex justify-end text-gray-500 ">
                <p>Jawaban Benar: {{$row->soal->kunci}}</p>
            </div>
        </div> 

        <?php $nomor++; ?>

        @endforeach   
        <div class="py-10">
            <a href="{{route('home')}}" class="bg-primary-500 text-center px-8 py-2 mx-auto block text-white font-bold mt-4 rounded-lg">KEMBALI KE HALAMAN UTAMA</a>
        </div>
    </div>
    <x-FrontBottomNav /> 
  
</div>

