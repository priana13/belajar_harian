@extends('layouts.app')

@section('content')


@push('head')

@if($angkatan)

   <!-- Meta Pixel Code -->
   <script>
      !function(f,b,e,v,n,t,s)
      {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
      n.callMethod.apply(n,arguments):n.queue.push(arguments)};
      if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
      n.queue=[];t=b.createElement(e);t.async=!0;
      t.src=v;s=b.getElementsByTagName(e)[0];
      s.parentNode.insertBefore(t,s)}(window, document,'script',
      'https://connect.facebook.net/en_US/fbevents.js');
      fbq('init', '{{$angkatan->fb_pixel_id}}');
      fbq('track', 'PageView');
   </script>

   <noscript><img height="1" width="1" style="display:none"
   src="https://www.facebook.com/tr?id={{ $angkatan->fb_pixel_id }}&ev=PageView&noscript=1"
   /></noscript>
   <!-- End Meta Pixel Code -->

@endif

@endpush


<x-FrontTopNav />
<div class="p-3">
    
   {{-- <a href="{{ route('home') }}">
      <img class="mx-auto mt-9 w-40" src="{{ asset('storage/logo.png') }}" alt="thumbnail">
   </a>     --}}

   <h2 class="text-2xl font-bold text-center text-gray-700 mt-6">Formulir Pendaftaran</h2>

  
    <form action="{{ route('register.store') }}" method="post" class="px-3">
        @csrf
      
        <input type="hidden" name="kode_angkatan" value="{{ (isset($kode_daftar) ) ? $kode_daftar : request()->kodeangkatan }}">
        
        <p class="my-2">Nama</p>
        <input name="nama"  class="bg-gray-100 w-full border border-gray-400 rounded-xl" type="text" placeholder="Nama" value="{{ old('nama') }}">
        @error('nama')
           <span class="text-red-400 text-sm">{{ $message }}</span>  
        @enderror
        
        <div class="grid grid-cols-[30%_67%] gap-3">
            <div>
               <p class="my-2">Tempat Lahir</p>

               <input name="temp_lahir" class="bg-gray-100 w-full border border-gray-400 rounded-xl " type="text" placeholder="Kota Kelahiran" value="{{ old('temp_lahir') }}">
               @error('temp_lahir')
                  <span class="text-red-400 text-sm">{{ $message }}</span>  
               @enderror
            </div>
            <div>
               <p class="my-2">Tanggal Lahir</p>
               <input name="tgl_lahir" class="bg-gray-100 w-full border border-gray-400 rounded-xl " type="date" placeholder="" value="{{ old('tgl_lahir') }}">
               @error('tgl_lahir')
                  <span class="text-red-400 text-sm">{{ $message }}</span>  
               @enderror
            </div>
        </div>

        <p class="my-2">Provinsi</p>
        <select name="provinsi" class="bg-gray-100 w-full border border-gray-400 rounded-xl " type="text" placeholder="provinsi" value="{{ old('provinsi') }}">
            <option value="">Pilih</option> 
            
            @foreach($provinsi as $prov)
            
            <option value="{{ $prov->id }}" {{ (old('provinsi') == $prov->id)?"selected":'' }}>{{ $prov->nama_provinsi }}</option> 
            
            @endforeach

         </select>

        @error('provinsi')
           <span class="text-red-400 text-sm">{{ $message }}</span>  
        @enderror

        <p class="my-2">Kota</p>
        <select name="kota" class="bg-gray-100 w-full border border-gray-400 rounded-xl " type="text" placeholder="kota" value="{{ old('kota') }}">
            <option value="">Pilih</option> 
            
            @foreach($kota as $kot)
            
            <option value="{{ $kot->id }}" {{ (old('kota') == $kot->id)?"selected":'' }}>{{ $kot->nama_kota }}</option> 
            
            @endforeach

         </select>
         
        @error('kota')
           <span class="text-red-400 text-sm">{{ $message }}</span>  
        @enderror

        
        <p class="my-2">Nomor Whatsapp</p>
        <input name="no_hp" class="bg-gray-100 w-full border border-gray-400 rounded-xl " type="number" placeholder="Nomor Whatsapp" value="{{ old('no_hp') }}">
        @error('no_hp')
           <span class="text-red-400 text-sm">{{ $message }}</span>  
        @enderror
        
        <p class="my-2">Pekerjaan</p>
        <input name="pekerjaan" class="bg-gray-100 w-full border border-gray-400 rounded-xl " type="text" placeholder="Pekerjaan" value="{{ old('pekerjaan') }}">
        @error('pekerjaan')
           <span class="text-red-400 text-sm">{{ $message }}</span>  
        @enderror       

        
        <p class="my-2">Status</p>
        <select name="status" class="bg-gray-100 w-full border border-gray-400 rounded-xl " type="text" placeholder="Status Pernikahan" value="{{ old('status') }}">
            <option value="">Pilih</option> 
            <option value="1" {{ (old('status') == '1')?"selected":'' }}>Menikah</option> 
            <option value="0" {{ (old('status') == '0')?"selected":'' }}>Belum</option> 
        </select>
        @error('status')
           <span class="text-red-400 text-sm">{{ $message }}</span>  
        @enderror
       
        <p class="my-2">Jenis Kelamin</p>
        <select name="jenis_kelamin" class="bg-gray-100 w-full border border-gray-400 rounded-xl " type="text" placeholder="Jenis Kelamin" value="{{ old('gender') }}">
           <option value="">Pilih</option> 
            <option value="L" {{ (old('jenis_kelamin') == 'L')?"selected":'' }}>Laki-Laki</option> 
            <option value="P" {{ (old('jenis_kelamin') == 'P')?"selected":'' }}>Perempuan</option> 
        </select>
        @error('jenis_kelamin')
           <span class="text-red-400 text-sm">{{ $message }}</span>  
        @enderror

        <h2 class="font-bold mb-2 mt-6 text-gray-700">Data Login:</h2>

        <p class="my-2">Email </p>
        <input name="email" class="bg-gray-100 w-full border border-gray-400 rounded-xl " type="text" placeholder="Email" value="{{ old('email') }}">
        @error('email')
           <span class="text-red-400 text-sm">{{ $message }}</span>  
        @enderror        

        <p class="my-2">Password</p>
        <input name="password" class="bg-gray-100 w-full border border-gray-400 rounded-xl " type="password" placeholder="Buat Password Untuk Login">
        @error('password')
           <span class="text-red-400 text-sm">{{ $message }}</span>  
        @enderror
        {{-- <p class="my-2">Konfirmasi Password</p>
        <input name="password_confirmation" class="bg-gray-100 w-full border border-gray-400 rounded-xl " type="password" placeholder="Ketik Ulang Password Anda"> --}}
        <button class="bg-primary-600 w-full rounded-xl p-3  text-white mt-5">Daftar</button>
    </form>

    <p class="text-center text-gray-500 my-4">Sudah punya akun? <a href="{{ route('login') }}" class="text-blue-400 hover:text-blue-600">Login Sekarang</a> </p>



</div>
@endsection