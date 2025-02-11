@extends('layouts.app')

@section('content')

<x-FrontTopNav />

<div class="py-4 px-8">

    {{-- title --}}
    <div class="border-b-2 border-gray-300 py-2">
        <h2 class="text-2xl">{{ $page->judul }}</h2>
    </div>

    <div class="my-8">

        {!! $page->konten !!}

    </div>   


</div>



@endsection