<div class="swiper mySwiper py-5">

    <style>
        .swiper {
        width: 100%;
        height: 100%;
        }
  
        .swiper-slide {
        text-align: center;
        font-size: 18px;
        background: #fff;
        display: flex;
        justify-content: center;
        align-items: center;
        }
  
        .swiper-slide img {
        display: block;
        width: 100%;
        /* height: 300px; */
        object-fit: cover;
        border-radius: 0.75rem;
        }
  
        .swiper-pagination-bullet {
            height: 10px;
            width: 10px;
            border-radius: 24px;
        }
  
        .swiper-pagination-bullet-active {
            height: 10px;
            width: 30px;
            border-radius: 24px;
            background-color: #41B02F;
        }
  
        /* radio */
  
        input[type="radio"]:checked + label span {
            background-color: #41B02F; 
            box-shadow: 0px 0px 0px 2px white inset;
        }
  
        input[type="radio"]:checked + label{
            color: #41B02F; 
        }
    </style>
   
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


        <!-- Swiper JS -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>

    <!-- Initialize Swiper -->
    <script>
    var swiper = new Swiper(".mySwiper", {
        spaceBetween: 20,
        speed: 900,
        loop: true,
        centeredSlides: false,
        autoplay: {
            delay: 2500,
            disableOnInteraction: false
        },
        pagination: {
                el: ".swiper-pagination",
                clickable: true,
        },
    });

    </script>

</div>