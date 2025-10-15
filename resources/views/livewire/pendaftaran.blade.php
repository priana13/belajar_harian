<div>

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

<div class="min-h-screen bg-gray-50 py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-2xl mx-auto">
        
        <!-- Header -->
        <div class="text-center mb-8">
            <h2 class="text-3xl font-bold text-gray-900">Formulir Pendaftaran</h2>
            <p class="mt-2 text-sm text-gray-600">Lengkapi data diri Anda dengan benar</p>
        </div>

        <!-- Form Container -->
        <div class="bg-white rounded-2xl shadow-lg p-6 sm:p-8">
            <form action="{{ route('register.store') }}" method="post" class="space-y-6">
                @csrf
                
                <input type="hidden" name="kode_angkatan" value="{{ (isset($kode_daftar)) ? $kode_daftar : request()->kodeangkatan }}">
                
                <!-- Data Pribadi Section -->
                <div class="space-y-5">
                    <h3 class="text-lg font-semibold text-gray-900 border-b border-gray-200 pb-2">Data Pribadi</h3>
                    
                    <!-- Nama -->
                    <div>
                        <label for="nama" class="block text-sm font-medium text-gray-700 mb-2">
                            Nama Lengkap <span class="text-red-500">*</span>
                        </label>
                        <input 
                            id="nama"
                            name="nama" 
                            type="text" 
                            placeholder="Masukkan nama lengkap" 
                            value="{{ old('nama') }}"
                            class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors @error('nama') border-red-500 @enderror"
                        >
                        @error('nama')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Umur -->
                    <div>
                        <label for="umur" class="block text-sm font-medium text-gray-700 mb-2">
                            Umur <span class="text-red-500">*</span>
                        </label>
                        <input 
                            id="umur"
                            name="umur" 
                            type="number" 
                            min="1"
                            max="100"
                            placeholder="Berapa tahun umur Anda saat ini" 
                            value="{{ old('umur') }}"
                            class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors @error('umur') border-red-500 @enderror"
                        >
                        @error('umur')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Jenis Kelamin -->
                    <div>
                        <label for="jenis_kelamin" class="block text-sm font-medium text-gray-700 mb-2">
                            Jenis Kelamin <span class="text-red-500">*</span>
                        </label>
                        <select 
                            id="jenis_kelamin"
                            name="jenis_kelamin" 
                            class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors @error('jenis_kelamin') border-red-500 @enderror"
                        >
                            <option value="">Pilih jenis kelamin</option>
                            <option value="L" {{ old('jenis_kelamin') == 'L' ? 'selected' : '' }}>Laki-Laki</option>
                            <option value="P" {{ old('jenis_kelamin') == 'P' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                        @error('jenis_kelamin')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Provinsi & Kota -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="provinsi" class="block text-sm font-medium text-gray-700 mb-2">
                                Provinsi <span class="text-red-500">*</span>
                            </label>
                            <select 
                                id="provinsi"
                                wire:model="provinsi_id"
                                name="provinsi" 
                                class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors @error('provinsi') border-red-500 @enderror"
                            >
                                <option value="">Pilih provinsi</option>
                                @foreach($provinsi as $prov)
                                    <option value="{{ $prov->id }}" {{ old('provinsi') == $prov->id ? 'selected' : '' }}>
                                        {{ $prov->nama_provinsi }}
                                    </option>
                                @endforeach
                            </select>
                            @error('provinsi')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="kota" class="block text-sm font-medium text-gray-700 mb-2">
                                Kota/Kabupaten <span class="text-red-500">*</span>
                            </label>
                            <select 
                                id="kota"
                                name="kota" 
                                class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors @error('kota') border-red-500 @enderror"
                            >
                                <option value="">Pilih kota</option>
                                @foreach($kota as $kot)
                                    <option value="{{ $kot->id }}" {{ old('kota') == $kot->id ? 'selected' : '' }}>
                                        {{ $kot->nama_kota }}
                                    </option>
                                @endforeach
                            </select>
                            @error('kota')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Nomor WhatsApp -->
                    <div>
                        <label for="no_hp" class="block text-sm font-medium text-gray-700 mb-2">
                            Nomor WhatsApp <span class="text-red-500">*</span>
                        </label>
                        <input 
                            id="no_hp"
                            name="no_hp" 
                            type="tel" 
                            placeholder="Contoh: 081234567890" 
                            value="{{ old('no_hp') }}"
                            class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors @error('no_hp') border-red-500 @enderror"
                        >
                        @error('no_hp')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Pekerjaan -->
                    <div>
                        <label for="pekerjaan" class="block text-sm font-medium text-gray-700 mb-2">
                            Pekerjaan <span class="text-red-500">*</span>
                        </label>
                        <input 
                            id="pekerjaan"
                            name="pekerjaan" 
                            type="text" 
                            placeholder="Masukkan pekerjaan" 
                            value="{{ old('pekerjaan') }}"
                            class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors @error('pekerjaan') border-red-500 @enderror"
                        >
                        @error('pekerjaan')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Data Login Section -->
                <div class="space-y-5 pt-6">
                    <h3 class="text-lg font-semibold text-gray-900 border-b border-gray-200 pb-2">Data Login</h3>
                    
                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                            Email <span class="text-red-500">*</span>
                        </label>
                        <input 
                            id="email"
                            name="email" 
                            type="email" 
                            placeholder="contoh@email.com" 
                            value="{{ old('email') }}"
                            class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors @error('email') border-red-500 @enderror"
                        >
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                            Password <span class="text-red-500">*</span>
                        </label>
                        <input 
                            id="password"
                            name="password" 
                            type="password" 
                            placeholder="Minimal 8 karakter" 
                            class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors @error('password') border-red-500 @enderror"
                        >
                        @error('password')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="pt-4">
                    <button 
                        type="submit" 
                        class="w-full bg-primary-600 hover:bg-primary-700 text-white font-semibold py-3.5 px-6 rounded-lg transition-colors duration-200 shadow-md hover:shadow-lg focus:ring-4 focus:ring-primary-300"
                    >
                        Daftar Sekarang
                    </button>
                </div>
            </form>
        </div>

        <!-- Login Link -->
        <p class="text-center text-gray-600 mt-6">
            Sudah punya akun? 
            <a href="{{ route('login') }}" class="font-medium text-primary-600 hover:text-primary-700 transition-colors">
                Login Sekarang
            </a>
        </p>

    </div>
</div>

</div>