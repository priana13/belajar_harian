@extends('premium.layout')
@section('premium-content')
<a href="{{ route('premium.index') }}" class="block text-sm text-green-700">← Member Premium</a>
<h1 class="text-2xl font-bold">Materi Video</h1>
<p class="text-sm text-gray-600">Modul video tersedia sesuai jadwal belajar Anda.</p>
<div class="space-y-3">
    @forelse($materials as $material)
        <a href="{{ route('halaman-materi-video', $material->kode_materi) }}" class="block p-4 border rounded-lg bg-white font-semibold text-green-800">▶ {{ $material->nama_materi }}</a>
    @empty <p class="text-gray-500">Materi video belum tersedia.</p> @endforelse
</div>
{{ $materials->links() }}
@endsection
