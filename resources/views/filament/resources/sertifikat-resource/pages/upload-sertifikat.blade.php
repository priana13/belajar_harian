<x-filament-panels::page>

    <style>
        .cert-upload-wrapper {
            font-family: 'Figtree', sans-serif;
        }
        .cert-section-title {
            font-size: 0.7rem;
            font-weight: 700;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            color: var(--gray-400, #9ca3af);
            margin-bottom: 1rem;
            padding-bottom: 0.5rem;
            border-bottom: 1px solid var(--gray-200, #e5e7eb);
        }
        .dark .cert-section-title {
            color: var(--gray-500, #6b7280);
            border-color: var(--gray-700, #374151);
        }
        .ttd-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.5rem;
        }
        @media (max-width: 640px) {
            .ttd-grid { grid-template-columns: 1fr; }
        }
        .ttd-card {
            border: 1px solid var(--gray-200, #e5e7eb);
            border-radius: 0.75rem;
            padding: 1.25rem;
            background: var(--gray-50, #f9fafb);
        }
        .dark .ttd-card {
            border-color: var(--gray-700, #374151);
            background: var(--gray-800, #1f2937);
        }
        .ttd-card-label {
            font-size: 0.75rem;
            font-weight: 600;
            color: var(--primary-600, #4f46e5);
            margin-bottom: 0.75rem;
            display: flex;
            align-items: center;
            gap: 0.4rem;
        }
        .img-preview {
            width: 80px;
            height: 80px;
            object-fit: contain;
            border-radius: 0.5rem;
            border: 1px dashed var(--gray-300, #d1d5db);
            display: none;
            margin-top: 0.5rem;
        }
    </style>

    <div class="cert-upload-wrapper">
        <form action="{{ route('sertifikat.upload') }}" method="POST" enctype="multipart/form-data" id="cert-form">
            @csrf

            {{-- ─── INFORMASI SERTIFIKAT ─── --}}
            <div class="fi-section rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10 p-6 mb-6">
                <p class="cert-section-title">Informasi Sertifikat</p>

                <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">

                    {{-- Nama --}}
                    <div class="sm:col-span-2">
                        <x-filament::input.wrapper>
                            <x-slot name="label">
                                <label class="fi-fo-field-wrp-label inline-flex items-center gap-x-3" for="nama">
                                    <span class="text-sm font-medium leading-6 text-gray-950 dark:text-white">
                                        Nama Penerima <span class="text-danger-600">*</span>
                                    </span>
                                </label>
                            </x-slot>
                            <x-filament::input
                                type="text"
                                id="nama"
                                name="nama"
                                placeholder="Masukkan nama penerima sertifikat"
                                required
                                value="{{ old('nama') }}"
                            />
                        </x-filament::input.wrapper>
                        @error('nama')
                            <p class="mt-1 text-sm text-danger-600">{{ $message }}</p>
                        @enderror
                    </div>
                   

                    {{-- Background --}}
                    <div class="sm:col-span-2">
                        <label class="fi-fo-field-wrp-label inline-flex items-center gap-x-3 mb-1" for="bg">
                            <span class="text-sm font-medium leading-6 text-gray-950 dark:text-white">
                                Background Sertifikat
                            </span>
                        </label>
                        <div class="flex items-center justify-center w-full">
                            <label for="bg"
                                class="flex flex-col items-center justify-center w-full h-32 border-2 border-dashed rounded-xl cursor-pointer
                                       border-gray-300 bg-gray-50 hover:bg-gray-100 dark:bg-gray-800 dark:border-gray-600 dark:hover:bg-gray-700
                                       transition-colors duration-200">
                                <div class="flex flex-col items-center justify-center pt-5 pb-6" id="bg-label-content">
                                    <x-filament::icon icon="heroicon-o-photo" class="w-8 h-8 mb-2 text-gray-400" />
                                    <p class="text-sm text-gray-500 dark:text-gray-400">
                                        <span class="font-semibold">Upload background</span> (opsional)
                                    </p>
                                    <p class="text-xs text-gray-400 mt-1">PNG, JPG (Maks. 5MB)</p>
                                </div>
                                <input type="file" id="bg" name="bg" class="hidden"
                                       accept=".png,.jpg,.jpeg"
                                       onchange="updateFileLabel(this, 'bg-label-content')" />
                            </label>
                        </div>
                        @error('bg')
                            <p class="mt-1 text-sm text-danger-600">{{ $message }}</p>
                        @enderror
                    </div>

                </div>
            </div>

            {{-- ─── TANDA TANGAN ─── --}}
            <div class="fi-section rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10 p-6 mb-6">
                <p class="cert-section-title">Tanda Tangan</p>

                <div class="ttd-grid">

                    {{-- TTD 1 --}}
                    <div class="ttd-card">
                        <p class="ttd-card-label">
                            <x-filament::icon icon="heroicon-o-pencil-square" class="w-4 h-4" />
                            Penanda Tangan 1
                        </p>

                        <div class="space-y-4">
                            <div>
                                <x-filament::input.wrapper>
                                    <x-slot name="label">
                                        <label class="fi-fo-field-wrp-label inline-flex items-center gap-x-3" for="ttd_nama">
                                            <span class="text-sm font-medium leading-6 text-gray-950 dark:text-white">
                                                Nama <span class="text-danger-600">*</span>
                                            </span>
                                        </label>
                                    </x-slot>
                                    <x-filament::input
                                        type="text"
                                        id="ttd_nama"
                                        name="ttd_nama"
                                        placeholder="Nama penanda tangan"
                                        required
                                        value="{{ old('ttd_nama') }}"
                                    />
                                </x-filament::input.wrapper>
                                @error('ttd_nama')
                                    <p class="mt-1 text-sm text-danger-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <x-filament::input.wrapper>
                                    <x-slot name="label">
                                        <label class="fi-fo-field-wrp-label inline-flex items-center gap-x-3" for="ttd_jabatan">
                                            <span class="text-sm font-medium leading-6 text-gray-950 dark:text-white">
                                                Jabatan <span class="text-danger-600">*</span>
                                            </span>
                                        </label>
                                    </x-slot>
                                    <x-filament::input
                                        type="text"
                                        id="ttd_jabatan"
                                        name="ttd_jabatan"
                                        placeholder="Jabatan penanda tangan"
                                        required
                                        value="{{ old('ttd_jabatan') }}"
                                    />
                                </x-filament::input.wrapper>
                                @error('ttd_jabatan')
                                    <p class="mt-1 text-sm text-danger-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="fi-fo-field-wrp-label inline-flex items-center gap-x-3 mb-1" for="ttd_image">
                                    <span class="text-sm font-medium leading-6 text-gray-950 dark:text-white">
                                        Gambar Tanda Tangan
                                    </span>
                                </label>
                                <input type="file" id="ttd_image" name="ttd_image"
                                       accept=".png,.jpg,.jpeg"
                                       class="block w-full text-sm text-gray-500 dark:text-gray-400
                                              file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0
                                              file:text-sm file:font-medium
                                              file:bg-primary-50 file:text-primary-700
                                              dark:file:bg-primary-900/20 dark:file:text-primary-400
                                              hover:file:bg-primary-100 dark:hover:file:bg-primary-900/30
                                              cursor-pointer transition"
                                       onchange="previewImage(this, 'preview-ttd1')" />
                                <img id="preview-ttd1" class="img-preview" src="#" alt="Preview TTD 1" />
                                @error('ttd_image')
                                    <p class="mt-1 text-sm text-danger-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- TTD 2 --}}
                    <div class="ttd-card">
                        <p class="ttd-card-label">
                            <x-filament::icon icon="heroicon-o-pencil-square" class="w-4 h-4" />
                            Penanda Tangan 2
                        </p>

                        <div class="space-y-4">
                            <div>
                                <x-filament::input.wrapper>
                                    <x-slot name="label">
                                        <label class="fi-fo-field-wrp-label inline-flex items-center gap-x-3" for="ttd_nama2">
                                            <span class="text-sm font-medium leading-6 text-gray-950 dark:text-white">
                                                Nama
                                            </span>
                                        </label>
                                    </x-slot>
                                    <x-filament::input
                                        type="text"
                                        id="ttd_nama2"
                                        name="ttd_nama2"
                                        placeholder="Nama penanda tangan kedua"
                                        value="{{ old('ttd_nama2') }}"
                                    />
                                </x-filament::input.wrapper>
                                @error('ttd_nama2')
                                    <p class="mt-1 text-sm text-danger-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <x-filament::input.wrapper>
                                    <x-slot name="label">
                                        <label class="fi-fo-field-wrp-label inline-flex items-center gap-x-3" for="ttd_jabatan2">
                                            <span class="text-sm font-medium leading-6 text-gray-950 dark:text-white">
                                                Jabatan
                                            </span>
                                        </label>
                                    </x-slot>
                                    <x-filament::input
                                        type="text"
                                        id="ttd_jabatan2"
                                        name="ttd_jabatan2"
                                        placeholder="Jabatan penanda tangan kedua"
                                        value="{{ old('ttd_jabatan2') }}"
                                    />
                                </x-filament::input.wrapper>
                                @error('ttd_jabatan2')
                                    <p class="mt-1 text-sm text-danger-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="fi-fo-field-wrp-label inline-flex items-center gap-x-3 mb-1" for="ttd_image2">
                                    <span class="text-sm font-medium leading-6 text-gray-950 dark:text-white">
                                        Gambar Tanda Tangan
                                    </span>
                                </label>
                                <input type="file" id="ttd_image2" name="ttd_image2"
                                       accept=".png,.jpg,.jpeg"
                                       class="block w-full text-sm text-gray-500 dark:text-gray-400
                                              file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0
                                              file:text-sm file:font-medium
                                              file:bg-primary-50 file:text-primary-700
                                              dark:file:bg-primary-900/20 dark:file:text-primary-400
                                              hover:file:bg-primary-100 dark:hover:file:bg-primary-900/30
                                              cursor-pointer transition"
                                       onchange="previewImage(this, 'preview-ttd2')" />
                                <img id="preview-ttd2" class="img-preview" src="#" alt="Preview TTD 2" />
                                @error('ttd_image2')
                                    <p class="mt-1 text-sm text-danger-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            {{-- ─── ACTIONS ─── --}}
            <div class="flex items-center justify-end gap-3">
                <x-filament::button
                    type="button"
                    color="gray"
                    onclick="window.history.back()"
                >
                    Batal
                </x-filament::button>

                <x-filament::button
                    type="submit"
                    icon="heroicon-o-arrow-up-tray"
                >
                    Upload Sertifikat
                </x-filament::button>
            </div>

        </form>
    </div>

    <script>
        function updateFileLabel(input, contentId = 'file-label-content') {
            const content = document.getElementById(contentId);
            if (input.files && input.files[0]) {
                const fileName = input.files[0].name;
                const fileSize = (input.files[0].size / 1024 / 1024).toFixed(2);
                content.innerHTML = `
                    <svg class="w-8 h-8 mb-2 text-primary-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                    <p class="text-sm font-semibold text-primary-600 dark:text-primary-400">${fileName}</p>
                    <p class="text-xs text-gray-400 mt-1">${fileSize} MB</p>
                `;
            }
        }

        function previewImage(input, previewId) {
            const preview = document.getElementById(previewId);
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                };
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>

</x-filament-panels::page>