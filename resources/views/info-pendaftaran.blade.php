@extends('layouts.app')

@section('content')
<x-FrontTopNav />
<div class="p-3">
    {{-- <a href="{{ route('home') }}">
        <img class="mx-auto mt-9 w-40" src="{{ asset('storage/logo.png') }}" alt="thumbnail">
    </a>     --}}

    {{-- <h2 class="text-2xl font-bold text-center text-gray-700 mt-6">Ups, Pendaftaran Belum dibuka atau sudah ditutup</h2> --}}


    <div class="flex items-center p-4 mb-4 text-sm text-blue-800 rounded-lg bg-blue-50 dark:bg-gray-800 dark:text-blue-400" role="alert">
        <svg class="flex-shrink-0 inline w-4 h-4 me-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
          <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
        </svg>
        <span class="sr-only">Mohon</span>
        <div>
          <span class="font-medium">Ups, Spertinya Pendaftaran Belum dibuka atau sudah ditutup.</span>

          <p>Silahkan tunggu jadwal berikutnya, terimakasih.</p>

        </div>


    </div>


</div>
@endsection