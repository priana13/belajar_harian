@extends('premium.layout')
@section('premium-content')
<a href="{{ route('premium.index') }}" class="block text-sm text-green-700">← Member Premium</a>
<h1 class="text-2xl font-bold">Ebook Materi</h1>
<div class="space-y-3">
    @forelse($ebooks as $ebook)
        <article class="p-4 border rounded-lg bg-white space-y-2">
            <h2 class="font-bold">{{ $ebook->title }}</h2>
            <p class="text-sm text-gray-500">{{ $ebook->materi->nama_materi }}</p>
            <p class="text-sm whitespace-pre-line">{{ $ebook->description }}</p>
            <a href="{{ route('premium.download', $ebook) }}" class="inline-block text-green-700 font-semibold">Unduh PDF</a>
        </article>
    @empty <p class="text-gray-500">Ebook belum tersedia.</p> @endforelse
</div>
{{ $ebooks->links() }}
@endsection
