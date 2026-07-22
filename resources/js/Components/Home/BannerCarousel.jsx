import { useEffect, useRef } from 'react';

export default function BannerCarousel({ banners }) {
    const containerRef = useRef(null);
    const swiperRef = useRef(null);

    useEffect(() => {
        if (!containerRef.current || banners.length === 0) {
            return undefined;
        }

        let cancelled = false;

        const initSwiper = () => {
            if (cancelled || !window.Swiper) {
                return;
            }
            swiperRef.current = new window.Swiper(containerRef.current, {
                spaceBetween: 20,
                speed: 900,
                loop: true,
                centeredSlides: false,
                autoplay: {
                    delay: 2500,
                    disableOnInteraction: false,
                },
                pagination: {
                    el: containerRef.current.querySelector('.swiper-pagination'),
                    clickable: true,
                },
            });
        };

        if (window.Swiper) {
            initSwiper();
        } else {
            const interval = setInterval(() => {
                if (window.Swiper) {
                    clearInterval(interval);
                    initSwiper();
                }
            }, 100);
            return () => {
                cancelled = true;
                clearInterval(interval);
                swiperRef.current?.destroy(true, true);
            };
        }

        return () => {
            cancelled = true;
            swiperRef.current?.destroy(true, true);
        };
    }, [banners]);

    if (banners.length === 0) {
        return null;
    }

    return (
        <div ref={containerRef} className="swiper mySwiper py-5">
            <div className="swiper-wrapper">
                {banners.map((banner) => (
                    <a
                        key={banner.id}
                        href={banner.url}
                        className="swiper-slide"
                        target="_blank"
                        rel="noreferrer"
                    >
                        <img
                            src={banner.image_url}
                            alt=""
                            onError={(e) => { e.target.style.display = 'none'; }}
                        />
                    </a>
                ))}
            </div>
            <div className="swiper-pagination -mb-3"></div>
        </div>
    );
}
