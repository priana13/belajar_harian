@extends('layouts.app')
@section('title', 'Member Premium — BISI Online')
@section('content')
<x-FrontTopNav />
<main class="pt-24 px-5 pb-6 space-y-5">
    <a href="{{ route('home') }}" class="text-sm text-green-700 font-semibold">← Kembali ke Beranda</a>
    @if(session('success')) <p role="status" class="p-3 rounded bg-green-100 text-green-800">{{ session('success') }}</p> @endif
    @if(session('error')) <p role="alert" class="p-3 rounded bg-amber-100 text-amber-800">{{ session('error') }}</p> @endif
    @if($errors->any())
        <ul role="alert" class="p-3 rounded bg-red-100 text-red-800 list-disc list-inside">
            @foreach($errors->all() as $error) <li>{{ $error }}</li> @endforeach
        </ul>
    @endif
    @yield('premium-content')
</main>
<x-FrontBottomNav />
@endsection
