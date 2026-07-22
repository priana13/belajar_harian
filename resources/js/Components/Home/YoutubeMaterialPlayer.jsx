import { useEffect, useRef } from 'react';

function getYouTubeVideoId(url) {
    const regex = /(?:https?:\/\/)?(?:www\.)?(?:youtube\.com\/(?:[^/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?/\s]{11})/;
    const match = url?.match(regex);
    return match ? match[1] : null;
}

function loadYoutubeApi() {
    if (window.YT && window.YT.Player) {
        return Promise.resolve();
    }
    if (!window.__youtubeApiPromise) {
        window.__youtubeApiPromise = new Promise((resolve) => {
            const previousCallback = window.onYouTubeIframeAPIReady;
            window.onYouTubeIframeAPIReady = () => {
                previousCallback?.();
                resolve();
            };
            const script = document.createElement('script');
            script.src = 'https://www.youtube.com/iframe_api';
            document.head.appendChild(script);
        });
    }
    return window.__youtubeApiPromise;
}

export default function YoutubeMaterialPlayer({ videoUrl, domId }) {
    const playerRef = useRef(null);

    useEffect(() => {
        let cancelled = false;

        loadYoutubeApi().then(() => {
            if (cancelled) {
                return;
            }
            playerRef.current = new window.YT.Player(domId, {
                height: '360',
                width: '100%',
                videoId: getYouTubeVideoId(videoUrl),
                disablekb: 1,
                controls: 0,
            });
        });

        return () => {
            cancelled = true;
            playerRef.current?.destroy?.();
        };
    }, [videoUrl, domId]);

    return (
        <div className="my-3">
            <div id={domId}></div>
        </div>
    );
}
