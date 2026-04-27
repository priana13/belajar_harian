@extends('layouts.app')

@section('content')

<div class="p-4 text-center">
    <h1 class="text-2xl font-bold mb-4">Daftar Kelas MMI</h1>
    <div class="space-y-4">

        <div class="p-4 bg-white rounded shadow">
            <h2 class="text-xl font-semibold mb-2">{{ $group->nama_group }}</h2>
            <p>Kode Group: {{ $group->kode_group }}</p>
            <!-- Tambahkan informasi lain yang relevan tentang kelas khusus di sini -->

            {{-- list kelas, $group->materi --}}

            {{-- <ul class="list-disc list-inside">
                @foreach($group->materi as $materi)
                    <li>{{ $materi->nama_materi }}</li>
                @endforeach
            </ul> --}}



            <form action="{{ route('daftar-kelas-khusus.post') }}" method="POST">
                @csrf
                <input type="hidden" name="group_id" value="{{ $group->id }}">

                <button type="submit" class="mt-4 px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">Ikuti Kelas</button>
            </form>

        </div>

        {{-- tampilkan error --}}
        @if ($errors->any())

        {{ dd('test') }}
            <div class="p-4 bg-red-100 text-red-700 rounded shadow">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>



@endsection