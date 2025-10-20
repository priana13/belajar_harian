<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>

    @vite('resources/css/app.css')
    
</head>
<body>


    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Kirim Email') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form action="{{ route('email.kirim') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <!-- Pilihan Penerima -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Pilih Penerima
                            </label>
                            <div class="space-y-3">
                                <label class="flex items-center">
                                    <input type="radio" name="recipient_type" value="all" 
                                           class="w-4 h-4 text-blue-600 focus:ring-blue-500 border-gray-300"
                                           onchange="toggleRecipientSelect()"
                                           checked>
                                    <span class="ml-2 text-gray-700">Kirim ke Semua Contact</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="radio" name="recipient_type" value="specific" 
                                           class="w-4 h-4 text-blue-600 focus:ring-blue-500 border-gray-300"
                                           onchange="toggleRecipientSelect()">
                                    <span class="ml-2 text-gray-700">Pilih Contact Tertentu</span>
                                </label>
                            </div>
                            @error('recipient_type')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Select Contact (Hidden by default) -->
                        <div id="contactSelectWrapper" class="mb-6 hidden">
                            <label for="recipients" class="block text-sm font-medium text-gray-700 mb-2">
                                Pilih Contact <span class="text-red-500">*</span>
                            </label>
                            <select name="recipients[]" id="recipients" multiple
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                    size="8">
                                @foreach(\App\Models\User::all() as $contact)
                                    <option value="1">
                                        {{ $contact->name }} ({{ $contact->email }})
                                    </option>
                                @endforeach
                            </select>
                            <p class="mt-1 text-sm text-gray-500">
                                Tekan Ctrl/Cmd untuk memilih lebih dari satu contact
                            </p>
                            @error('recipients')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Subject -->
                        <div class="mb-6">
                            <label for="subject" class="block text-sm font-medium text-gray-700 mb-2">
                                Subject <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="subject" id="subject" 
                                   value="{{ old('subject') }}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                   placeholder="Masukkan subject email"
                                   required>
                            @error('subject')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Message Body -->
                        <div class="mb-6">
                            <label for="message" class="block text-sm font-medium text-gray-700 mb-2">
                                Pesan <span class="text-red-500">*</span>
                            </label>
                            <textarea name="message" id="message" rows="10"
                                      class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                      placeholder="Tulis pesan email Anda di sini..."
                                      required>{{ old('message') }}</textarea>
                            @error('message')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Attachment -->
                        <div class="mb-6">
                            <label for="attachment" class="block text-sm font-medium text-gray-700 mb-2">
                                Lampiran (Opsional)
                            </label>
                            <input type="file" name="attachment" id="attachment"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                            <p class="mt-1 text-sm text-gray-500">
                                Maksimal ukuran file: 10MB
                            </p>
                            @error('attachment')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Buttons -->
                        <div class="flex items-center justify-end space-x-3">
                            <a href="/" 
                               class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-500 transition">
                                Batal
                            </a>
                            <button type="submit"
                                    class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 transition">
                                <span class="flex items-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                    </svg>
                                    Kirim Email
                                </span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Info Box -->
            <div class="mt-6 bg-blue-50 border-l-4 border-blue-400 p-4 rounded">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-blue-700">
                            <strong>Tips:</strong> Pastikan semua data contact sudah lengkap sebelum mengirim email massal.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleRecipientSelect() {
            const recipientType = document.querySelector('input[name="recipient_type"]:checked').value;
            const contactSelectWrapper = document.getElementById('contactSelectWrapper');
            const recipientsSelect = document.getElementById('recipients');
            
            if (recipientType === 'specific') {
                contactSelectWrapper.classList.remove('hidden');
                recipientsSelect.required = true;
            } else {
                contactSelectWrapper.classList.add('hidden');
                recipientsSelect.required = false;
            }
        }

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            toggleRecipientSelect();
        });
    </script>

    
</body>
</html>