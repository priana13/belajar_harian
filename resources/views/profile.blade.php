@extends('layouts.app')

@section('content')
<x-FrontTopNav/>
    <div class="flex flex-col gap-2 items-center mt-6 justify-center">
        <p class="text-xl font-bold">Profile</p>
        <div class="bg-gray-500 flex items-center justify-center rounded-full text-white text-3xl  w-16 h-16">
            <i class="fa-solid fa-user"></i>
        </div>
    </div>
    <div class="px-3">
        <div class="py-1">
            <p class="text-lg py-2 font-semibold">Nama</p>
            <div class="relative text-gray-400 text-lg font-bold">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                    <i class="fa-solid fa-user"></i>                
                </span>
                <input type="text" name="paket_umroh" class="py-3  w-full text-gray-500 rounded-md pl-9 border-gray-300" placeholder="Nama Lengkap" autocomplete="off" />
            </div>
        </div>
        <div class="py-1">
            <p class="text-lg py-2 font-semibold">Nomor Whatsapp</p>
            <div class="relative text-gray-400 text-lg font-bold">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                    <i class="fa-brands fa-whatsapp"></i>
                </span>
                <input type="number" name="paket_umroh" class="py-3  w-full text-gray-500 rounded-md pl-9 border-gray-300" placeholder="08xxxx" autocomplete="off" />
            </div>
        </div>
        <div class="py-1">
            <p class="text-lg py-2 font-semibold">Email</p>
            <div class="relative text-gray-400 text-lg font-bold">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                    <i class="fa-solid fa-envelope"></i>
                </span>
                <input type="email" name="paket_umroh" class="py-3  w-full text-gray-500 rounded-md pl-9 border-gray-300" placeholder="@email" autocomplete="off" />
            </div>
        </div>
        <div class="py-1">
            <p class="text-lg py-2 font-semibold">Jenis Kelamin</p>
            <div class="relative text-gray-400 text-lg font-bold">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                    <i class="fa-solid fa-venus-mars"></i>                
                </span>
                <select type="search" name="paket_umroh" class="py-3  w-full text-gray-500 rounded-md pl-10 border-gray-300" placeholder="paket_umroh" autocomplete="off">
                    <option value="">Pilih</option>
                    <option value="L">Laki-Laki</option>
                    <option value="P">Perempuan</option>
                </select>
            </div>
        </div>
        <div class="py-1">
            <p class="text-lg py-2 font-semibold">Tanggal Lahir</p>
            <div class="relative text-gray-400 text-lg font-bold">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                    <i class="fa-solid fa-calendar"></i>
                </span>
                <input type="date" name="paket_umroh" class="py-3  w-full text-gray-500 rounded-md pl-9 border-gray-300" placeholder="@email" autocomplete="off" />
            </div>
        </div>
        
        <p class="mt-8">Ubah Password</p>
        
        <div class="py-1">
            <p class="text-lg py-2 font-semibold">Password</p>
            <div class="relative text-gray-400 text-lg font-bold">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                    <i class="fa-solid fa-key"></i>
                </span>
                <input type="password" name="paket_umroh" class="py-3  w-full text-gray-500 rounded-md pl-9 border-gray-300"  autocomplete="off" />
            </div>
        </div>
        <div class="py-1">
            <p class="text-lg py-2 font-semibold">Konfirmasi Password</p>
            <div class="relative text-gray-400 text-lg font-bold">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                    <i class="fa-solid fa-key"></i>
                </span>
                <input type="password" name="paket_umroh" class="py-3  w-full text-gray-500 rounded-md pl-9 border-gray-300"  autocomplete="off" />
            </div>
        </div>
        
        
    </div>
<x-FrontBottomNav />
@endsection