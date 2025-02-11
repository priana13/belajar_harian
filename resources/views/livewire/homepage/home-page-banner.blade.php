<div class="swiper mySwiper py-5">
   
    <div class="swiper-wrapper">

        @forelse($banners as $banner)        
        
        {{-- {{ dd( asset( "storage/" . $banner->image ) ) }}       --}}

        <a href="{{ $banner->url }}" class="swiper-slide" target="_blank">
            <img src="{{ asset('storage/' . $banner->image) }}">
        </a>

        @empty

        {{-- jika tidak ada banner yang aktif --}}

        @endforelse

    </div>
    <div class="swiper-pagination -mb-3"></div>
</div>