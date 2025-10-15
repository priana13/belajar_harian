<div>
    
    <x-FrontTopNav/>
    
    @if(session()->has('message'))
        <div class="alert alert-success">
            {{ session('message') }}
        </div>
    @endif
    
    <form wire:submit.prevent="submit" enctype="multipart/form-data">
        <!-- Profile Picture Section -->
        <div class="flex flex-col gap-3 items-center justify-center">
            <br><br>
            <p class="text-xl font-bold mt-5">Profile</p>
            
            @if($foto_profil)
                <img class="h-16 w-16 rounded-full object-cover" src="{{ asset('storage/' .$foto_profil) }}" alt="Profile Picture">
            @else
                <div class="bg-gray-500 flex items-center justify-center rounded-full text-white text-3xl w-16 h-16">
                    <i class="fa-solid fa-user"></i>
                </div>
            @endif
            
            <input wire:model="foto_profil" type="file" class="hidden" id="foto_profil" accept="image/*">
            <button type="button" onclick="document.getElementById('foto_profil').click()" class="text-primary-500 hover:text-primary-600 font-semibold">
                Upload Foto
            </button>
            
            @error('foto_profil') 
                <span class="text-red-500 text-sm">{{ $message }}</span> 
            @enderror
        </div>
        
        <!-- Form Fields -->
        <div class="px-3 mt-6">
            <!-- Nama -->
            <div class="mb-4">
                <label class="text-lg font-semibold mb-2 block">Nama</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                        <i class="fa-solid fa-user"></i>                
                    </span>
                    <input type="text" 
                           wire:model="name" 
                           class="py-3 w-full text-gray-700 rounded-md pl-10 border border-gray-300 focus:border-primary-500 focus:ring-1 focus:ring-primary-500"  
                           placeholder="Nama Lengkap" 
                           autocomplete="off" />
                </div>
                @error('name') 
                    <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> 
                @enderror
            </div>
            
            <!-- Nomor Whatsapp -->
            <div class="mb-4">
                <label class="text-lg font-semibold mb-2 block">Nomor Whatsapp</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                        <i class="fa-brands fa-whatsapp"></i>
                    </span>
                    <input type="tel" 
                           wire:model="no_hp"  
                           class="py-3 w-full text-gray-700 rounded-md pl-10 border border-gray-300 focus:border-primary-500 focus:ring-1 focus:ring-primary-500" 
                           placeholder="08xxxx" 
                           autocomplete="off" />
                </div>
                @error('no_hp') 
                    <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> 
                @enderror
            </div>
            
            <!-- Email -->
            <div class="mb-4">
                <label class="text-lg font-semibold mb-2 block">Email</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                        <i class="fa-solid fa-envelope"></i>
                    </span>
                    <input type="email" 
                           wire:model="email"  
                           class="py-3 w-full text-gray-700 rounded-md pl-10 border border-gray-300 focus:border-primary-500 focus:ring-1 focus:ring-primary-500" 
                           placeholder="email@example.com" 
                           autocomplete="off" />
                </div>
                @error('email') 
                    <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> 
                @enderror
            </div>
            
            <!-- Jenis Kelamin & Umur -->
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="text-lg font-semibold mb-2 block">Jenis Kelamin</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                            <i class="fa-solid fa-venus-mars"></i>                
                        </span>
                        <select wire:model="jenis_kelamin" 
                                class="py-3 w-full text-gray-700 rounded-md pl-10 border border-gray-300 focus:border-primary-500 focus:ring-1 focus:ring-primary-500" 
                                autocomplete="off">
                            <option value="">Pilih</option>
                            <option value="L">Laki-Laki</option>
                            <option value="P">Perempuan</option>
                        </select>
                    </div>
                    @error('jenis_kelamin') 
                        <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> 
                    @enderror
                </div>
                
                <div>
                    <label class="text-lg font-semibold mb-2 block">Umur</label>
                    <input type="number" 
                           wire:model="umur"  
                           class="py-3 w-full text-gray-700 rounded-md border border-gray-300 focus:border-primary-500 focus:ring-1 focus:ring-primary-500 px-3" 
                           placeholder="Umur"
                           autocomplete="off" />
                    @error('umur') 
                        <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> 
                    @enderror
                </div>
            </div>
            
            <!-- Password Section -->
            <div class="mt-8 mb-4">
                <h3 class="text-lg font-bold mb-4">Ubah Password</h3>
                
                <!-- Password -->
                <div class="mb-4">
                    <label class="text-lg font-semibold mb-2 block">Password Baru</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                            <i class="fa-solid fa-key"></i>
                        </span>
                        <input type="password" 
                               wire:model="password" 
                               class="py-3 w-full text-gray-700 rounded-md pl-10 border border-gray-300 focus:border-primary-500 focus:ring-1 focus:ring-primary-500"  
                               placeholder="Kosongkan jika tidak ingin mengubah"
                               autocomplete="new-password" />
                    </div>
                    @error('password') 
                        <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> 
                    @enderror
                </div>
                
                <!-- Konfirmasi Password -->
                <div class="mb-4">
                    <label class="text-lg font-semibold mb-2 block">Konfirmasi Password</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                            <i class="fa-solid fa-key"></i>
                        </span>
                        <input type="password"  
                               wire:model="password_confirmation" 
                               class="py-3 w-full text-gray-700 rounded-md pl-10 border border-gray-300 focus:border-primary-500 focus:ring-1 focus:ring-primary-500"  
                               placeholder="Konfirmasi password baru"
                               autocomplete="off" />
                    </div>
                </div>
            </div>
            
            <!-- Submit Button -->
            <button type="submit" 
                    class="bg-primary-500 hover:bg-primary-600 w-full rounded-xl p-3 mb-5 text-white text-lg font-semibold transition-colors">
                Simpan
            </button>    
        </div>
    </form>
    
    <x-FrontBottomNav />
</div>