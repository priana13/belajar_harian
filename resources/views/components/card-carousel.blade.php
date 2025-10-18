<section class="mt-8">

  {{-- Post carousel --}}
    <div class="overflow-x-auto scrollbar-hide">
        <div class="flex space-x-4 px-4">
    
        @foreach($items as $card)

            <a href="{{ route('page.show' , $card->slug) }}" class="min-w-[150px] max-w-[150px] bg-white rounded-lg shadow-md overflow-hidden">
                <img class="w-full h-40 object-cover" src="{{ asset('storage/' . $card->image)  }}" alt="{{ $card->judul }}">
                <div class="p-4">
                    <h3 class="text-lg font-semibold mb-2">{{ $card->judul}}</h3>                
                </div>
            </a>
        @endforeach
    
        </div>

</section>