<div>
<x-FrontTopNav/>
@if(session()->has('message'))
    <div class="alert alert-success">
        {{ session('message') }}
    </div>
@endif
<form wire:submit.prevent="submit" enctype="multipart/form-data">

    <div class="flex flex-col gap-2 items-center mt-6 justify-center">
        <p class="text-xl font-bold">Profile</p>
        @if($foto_profil)
            <img class="h-14 bg-cover w-14 rounded-full" src="{{ asset('storage/' .$foto_profil) }}" alt="">
        @else
            <div class="bg-gray-500 flex items-center justify-center rounded-full text-white text-3xl  w-16 h-16">
                <i class="fa-solid fa-user"></i>
            </div>
        @endif
        <input wire:model="foto_profil" type="file" class="form-control-file " style="display:none" id="foto_profil">
        <button onclick="document.getElementById('foto_profil').click()" class="">Upload</button>

        @error('foto_profil') <span class="text-danger">{{ $message }}</span> @enderror

    </div>
    <div class="px-3">
        <div class="py-1">
            <p class="text-lg py-2 font-semibold">Nama</p>
            <div class="relative text-gray-400 text-lg font-bold">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                    <i class="fa-solid fa-user"></i>                
                </span>
                <input type="text" wire:model="name" class="py-3  w-full text-gray-500 rounded-md pl-9 border-gray-300"  placeholder="Nama Lengkap" autocomplete="off" />
            </div>
        </div>
        @error('name') <span class="error text-red-500">{{ $message }}</span> @enderror

        <div class="py-1">
            <p class="text-lg py-2 font-semibold">Nomor Whatsapp</p>
            <div class="relative text-gray-400 text-lg font-bold">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                    <i class="fa-brands fa-whatsapp"></i>
                </span>
                <input type="number" wire:model="no_hp"  class="py-3  w-full text-gray-500 rounded-md pl-9 border-gray-300" placeholder="08xxxx" autocomplete="off" />
            </div>
        </div>
        @error('no_hp') <span class="error text-red-500">{{ $message }}</span> @enderror

        <div class="py-1">
            <p class="text-lg py-2 font-semibold">Email</p>
            <div class="relative text-gray-400 text-lg font-bold">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                    <i class="fa-solid fa-envelope"></i>
                </span>
                <input type="email" wire:model="email"  class="py-3  w-full text-gray-500 rounded-md pl-9 border-gray-300" placeholder="@email" autocomplete="off" />
            </div>
        </div>
        @error('email') <span class="error text-red-500">{{ $message }}</span> @enderror

        <div class="grid grid-cols-2 gap-3">
            <div class="py-1">
                <p class="text-lg py-2 font-semibold">Jenis Kelamin</p>
                <div class="relative text-gray-400 text-lg font-bold">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                        <i class="fa-solid fa-venus-mars"></i>                
                    </span>
                    <select type="" wire:model="jenis_kelamin" class="py-3  w-full text-gray-500 rounded-md pl-10 border-gray-300" placeholder="" autocomplete="off">
                        <option value="">Pilih</option>
                        <option value="L">Laki-Laki</option>
                        <option value="P">Perempuan</option>
                    </select>
                </div>
            </div>

            <div class="py-1">
                <p class="text-lg py-2 font-semibold">Umur</p>
                <div class="relative text-gray-400 text-lg font-bold">
                    {{-- <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                        <i class="fa-solid fa-envelope"></i>
                    </span> --}}
                    <input type="number" wire:model="umur"  class="py-3  w-full text-gray-500 rounded-md border-gray-300" autocomplete="off" />
                </div>
            </div>

            {{-- <div class="py-1 hidden">
                <p class="text-lg py-2 font-semibold">Status Perkawinan</p>
                <div class="relative text-gray-400 text-lg font-bold">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                        <i class="fa-solid fa-ring"></i>
                    </span>
                    <select type="" wire:model="status" class="py-3  w-full text-gray-500 rounded-md pl-10 border-gray-300" placeholder="" autocomplete="off">
                        <option value="">Pilih</option> 
                        <option value="1">Menikah</option> 
                        <option value="0">Belum</option> 
                    </select>
                </div>
            </div> --}}
        </div>
        <div class="grid grid-cols-[40%_57%] gap-3 hidden">
            {{-- <div class="py-1">
                <p class="text-lg py-2 font-semibold">Tempat Lahir</p>
                <div class="relative text-gray-400 text-lg font-bold">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                        <i class="fa-solid fa-city"></i>
                    </span>
                    <input type="text" wire:model="temp_lahir"  class="py-3  w-full text-gray-500 rounded-md pl-9 border-gray-300" placeholder="Kota" autocomplete="off" />
                </div>
            </div> --}}
            {{-- <div class="py-1">
                <p class="text-lg py-2 font-semibold">Tanggal Lahir</p>
                <div class="relative text-gray-400 text-lg font-bold">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                        <i class="fa-solid fa-calendar"></i>
                    </span>
                    <input type="date" wire:model="tgl_lahir" class="py-3  w-full text-gray-500 rounded-md pl-9 border-gray-300" placeholder="@email" autocomplete="off" />
                </div>
            </div> --}}
        </div>
        
        
        <p class="mt-8">Ubah Password</p>
        
        <div class="py-1">
            <p class="text-lg py-2 font-semibold">Password</p>
            <div class="relative text-gray-400 text-lg font-bold">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                    <i class="fa-solid fa-key"></i>
                </span>
                <input type="password" id="asd" wire:model="password" class="py-3  w-full text-gray-500 rounded-md pl-9 border-gray-300"  autocomplete="new-password" />
            </div>
        </div>
        @error('password') <span class="error text-red-500">{{ $message }}</span> @enderror

        <div class="py-1">
            <p class="text-lg py-2 font-semibold">Konfirmasi Password</p>
            <div class="relative text-gray-400 text-lg font-bold">
                <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                    <i class="fa-solid fa-key"></i>
                </span>
                <input type="password"  wire:model="password_confirmation" class="py-3  w-full text-gray-500 rounded-md pl-9 border-gray-300"  autocomplete="off" />
            </div>
        </div>
        @error('password') <span class="error text-red-500">{{ $message }}</span> @enderror

        
        <button type="submit" class="bg-primary-500 w-full rounded-xl p-3 mb-5  text-white mt-5">Simpan</button>    
    </div>

</form>

<x-FrontBottomNav />
</div>
