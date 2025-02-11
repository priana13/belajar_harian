@extends('layouts.app')

@section('content')
<x-FrontTopNav />
<div class="p-3">
    {{-- <a href="{{ route('home') }}">
        <img class="mx-auto mt-9 w-40" src="{{ asset('storage/logo.png') }}" alt="thumbnail">
    </a>     --}}

    <h2 class="text-2xl font-bold text-center text-gray-700 mt-6">Lupa Password</h2>


    <form action="{{ route('password.email') }}" method="post" class="px-8">
        @csrf

        <input name="email" placeholder="Masukan Email" class="bg-gray-100 w-full border border-gray-400 rounded-xl mt-5" type="text">
        @error('email')
            <span class="text-danger">{{ $message }}</span>  
        @enderror
     

        <button type="submit" class="bg-primary-600 w-full rounded-xl p-3  text-white mt-5">Reset Password</button>
     

        <p class="text-center text-gray-500 my-4">Belum punya akun? <a href="{{ route('register') }}" class="text-blue-400 hover:text-blue-600">Daftar Sekarang</a> </p>

    </form>

</div>
@endsection