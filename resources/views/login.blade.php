@extends('layouts.app')

@section('content')
{{-- <x-FrontTopNav /> --}}

<div class="px-3 py-12">
    {{-- <a href="{{ route('home') }}">
        <img class="mx-auto mt-9 w-40" src="{{ asset('storage/logo.png') }}" alt="thumbnail">
    </a>     --}}

    <h2 class="text-2xl font-bold text-center text-gray-700 mt-6">MASUK</h2>


    <form action="{{ route('login.store') }}" method="post" class="px-8">
        @csrf

         <div class="py-1">
            <p class="text-lg py-2 font-semibold">Email</p>
            <div class="relative text-gray-400 text-lg font-bold">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                    <i class="fa-solid fa-envelope"></i>
                </span>
                <input type="email" name="email" class="py-3  w-full text-gray-500 rounded-md pl-9 bg-gray-100 w-full border border-gray-400" placeholder="Alamat Email" autocomplete="off" />
               
               @error('email')
                    <span class="text-danger">{{ $message }}</span>  
                @enderror
            
            </div>
        </div>     

        <div class="py-1">
            <p class="text-lg py-2 font-semibold">Password</p>
            <div class="relative text-gray-400 text-lg font-bold">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                    <i class="fa-solid fa-key"></i>
                </span>
                <input type="password" name="password" class="py-3  w-full text-gray-500 rounded-md pl-9 bg-gray-100 border border-gray-400" placeholder="Password" autocomplete="off" />
               
               @error('password')
                <span class="text-danger">{{ $message }}</span>  
               @enderror
            
            </div>
        </div>



        <button type="submit" class="bg-primary-600 w-full rounded-xl p-3  text-white mt-5">Masuk</button>
        <a href="/auth/redirect" class="bg-danger-600 w-full rounded-xl p-3  text-white mt-5 flex items-center justify-center">
            <svg xmlns="http://www.w3.org/2000/svg" height="16" width="15.25" viewBox="0 0 488 512"><!--!Font Awesome Free 6.5.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2023 Fonticons, Inc.--><path fill="#e7eaee" d="M488 261.8C488 403.3 391.1 504 248 504 110.8 504 0 393.2 0 256S110.8 8 248 8c66.8 0 123 24.5 166.3 64.9l-67.5 64.9C258.5 52.6 94.3 116.6 94.3 256c0 86.5 69.1 156.6 153.7 156.6 98.2 0 135-70.4 140.8-106.9H248v-85.3h236.1c2.3 12.7 3.9 24.9 3.9 41.4z"/></svg>
            <span class="mx-2">Masuk dengan Google</span>
        </a>

        <p class="text-center text-gray-500 my-4">Belum punya akun? <a href="{{ route('register') }}" class="text-blue-400 hover:text-blue-600">Daftar Sekarang</a> </p>
        <p class="text-center text-gray-500 my-4">atau <a href="{{ route('password.request') }}" class="text-blue-400 hover:text-blue-600">Lupa Password</a> ? </p>

    </form>

</div>
@endsection