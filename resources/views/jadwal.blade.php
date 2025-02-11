@extends('layouts.app')

@section('content')

<div class="p-3">
    <h2 class="text-xl font-bold mb-2 border-b-2 border-gray-200">Jadwal Ujian</h2>


    @foreach($list_angkatan as $i => $angkatan)

    <?php
        $tanggal =  \Carbon\Carbon::parse( $angkatan->tanggal_ujian )
    
    ?>

    <div class="my-2 p-2">

        <h3 class=""> {{ $i+1 }}. {{ $angkatan->kode_angkatan }}</h3>
        <p class="ms-4">
           Ujian Akhir: {{$tanggal->format("d M Y") }} : 
           <strong>{{ $tanggal->diffForHumans() }}</strong> 
        </p>
      
    </div>

    @endforeach

</div>



@endsection