@extends('layouts.app')

@section('content')

<x-FrontTopNav />

<div class="py-4 px-8">

    {{-- title --}}
    <div class="border-b-2 border-gray-300 py-2 mb-4">
        <h2 class="text-2xl">{{ $page->judul }}</h2>
    </div>

    <div class="my-t">

        {!! $page->konten !!}

    </div>   
    

    @if($page->action_url)

    <div class="flex justify-end">       

         <a href="{{ $page->action_url }}" target="_blank" class="flex items-center justify-center px-4 py-2 text-white bg-green-500 rounded-lg hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-400 focus:ring-opacity-75 shadow-lg">
            <i class="fab fa-whatsapp mr-2"></i> {{ $page->action_label }}
        </a>

    </div>    

    @endif

</div>



@endsection