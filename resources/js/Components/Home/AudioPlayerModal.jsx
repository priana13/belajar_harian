import { useEffect, useRef, useState } from 'react';

const BAR_COUNT = 20;

function formatTime(seconds) {
    if (!Number.isFinite(seconds)) {
        return '00:00';
    }
    const minutes = Math.floor(seconds / 60);
    const secs = Math.floor(seconds - minutes * 60);
    return `${String(minutes).padStart(2, '0')}:${String(secs).padStart(2, '0')}`;
}

export default function AudioPlayerModal({ isOpen, trackIndex, openKey, tracks, onClose, onPlayStateChange }) {
    const audioRef = useRef(null);
    const audioCtxRef = useRef(null);
    const analyserRef = useRef(null);
    const dataArrayRef = useRef(null);
    const animationIdRef = useRef(null);
    const barsRef = useRef([]);
    const seekSliderRef = useRef(null);

    const [activeIndex, setActiveIndex] = useState(trackIndex);
    const [isPlaying, setIsPlaying] = useState(false);
    const [currentTime, setCurrentTime] = useState(0);
    const [duration, setDuration] = useState(0);

    useEffect(() => {
        onPlayStateChange(isPlaying);
    }, [isPlaying, onPlayStateChange]);

    useEffect(() => {
        const audio = new Audio();
        audioRef.current = audio;

        const handleEnded = () => setIsPlaying(false);
        const handleTimeUpdate = () => setCurrentTime(audio.currentTime);
        const handleLoadedMetadata = () => setDuration(audio.duration);

        audio.addEventListener('ended', handleEnded);
        audio.addEventListener('timeupdate', handleTimeUpdate);
        audio.addEventListener('loadedmetadata', handleLoadedMetadata);

        return () => {
            audio.removeEventListener('ended', handleEnded);
            audio.removeEventListener('timeupdate', handleTimeUpdate);
            audio.removeEventListener('loadedmetadata', handleLoadedMetadata);
            audio.pause();
            if (animationIdRef.current) {
                cancelAnimationFrame(animationIdRef.current);
            }
        };
    }, []);

    const initAudioContext = () => {
        if (audioCtxRef.current) {
            return;
        }
        try {
            const AudioContextClass = window.AudioContext || window.webkitAudioContext;
            const audioCtx = new AudioContextClass();
            const analyser = audioCtx.createAnalyser();
            analyser.fftSize = 64;
            const source = audioCtx.createMediaElementSource(audioRef.current);
            source.connect(analyser);
            analyser.connect(audioCtx.destination);
            audioCtxRef.current = audioCtx;
            analyserRef.current = analyser;
            dataArrayRef.current = new Uint8Array(analyser.frequencyBinCount);
        } catch (e) {
            console.error('Error initializing audio context:', e);
        }
    };

    const visualize = () => {
        if (!analyserRef.current) {
            return;
        }
        animationIdRef.current = requestAnimationFrame(visualize);
        analyserRef.current.getByteFrequencyData(dataArrayRef.current);

        barsRef.current.forEach((bar, index) => {
            if (!bar) {
                return;
            }
            const dataIndex = Math.floor((index * dataArrayRef.current.length) / BAR_COUNT);
            const value = dataArrayRef.current[dataIndex];
            const height = Math.max(8, (value / 255) * 40);
            bar.style.height = `${height}px`;
        });
    };

    const play = () => {
        initAudioContext();
        if (audioCtxRef.current?.state === 'suspended') {
            audioCtxRef.current.resume();
        }
        audioRef.current.play().catch((error) => console.error('Error playing audio:', error));
        setIsPlaying(true);
        visualize();
    };

    const pause = () => {
        audioRef.current.pause();
        setIsPlaying(false);
        if (animationIdRef.current) {
            cancelAnimationFrame(animationIdRef.current);
        }
        barsRef.current.forEach((bar) => {
            if (bar) {
                bar.style.height = '8px';
            }
        });
    };

    const loadTrack = (index) => {
        const track = tracks[index];
        if (!track) {
            return;
        }
        if (animationIdRef.current) {
            cancelAnimationFrame(animationIdRef.current);
        }
        setCurrentTime(0);
        setDuration(0);
        audioRef.current.src = track.path;
        audioRef.current.load();
    };

    // Mirrors the original open_modal(id): (re)loads and plays every time the
    // "DENGARKAN MATERI" button is clicked, even for the same track.
    useEffect(() => {
        if (!isOpen || !tracks[trackIndex]) {
            return;
        }
        setActiveIndex(trackIndex);
        loadTrack(trackIndex);
        play();
        // eslint-disable-next-line react-hooks/exhaustive-deps
    }, [isOpen, openKey]);

    if (tracks.length === 0) {
        return null;
    }

    const track = tracks[activeIndex] ?? tracks[0];

    const playPause = () => (isPlaying ? pause() : play());

    const goToTrack = (index) => {
        setActiveIndex(index);
        loadTrack(index);
        play();
    };

    const nextTrack = () => goToTrack(activeIndex < tracks.length - 1 ? activeIndex + 1 : 0);
    const prevTrack = () => goToTrack(activeIndex > 0 ? activeIndex - 1 : tracks.length - 1);

    const seekTo = (value) => {
        if (audioRef.current.duration) {
            audioRef.current.currentTime = audioRef.current.duration * (value / 100);
        }
    };

    const seekPercent = duration ? (currentTime / duration) * 100 : 0;

    return (
        <div className={isOpen ? '' : 'hidden'}>
            <div
                className="fixed inset-x-0 bottom-0 h-full w-full bg-black opacity-70 z-10"
                onClick={onClose}
            ></div>
            <div className="fixed inset-x-3 bottom-[100px] z-10 bg-white shadow-top rounded-xl p-5 max-w-[30rem] mx-auto max-h-[calc(100vh-140px)] overflow-y-auto">
                <div className="modern-modal flex flex-col items-center justify-center overflow-auto">
                    <div className="details mb-4">
                        <div className="track-name font-semibold text-blue-900">{track?.name ?? 'Judul Materi'}</div>
                        <div className="track-artist text-blue-700">{track?.artist ?? 'Bab'}</div>
                    </div>
                    <div className="buttons flex gap-4 mb-4">
                        <button type="button" onClick={prevTrack}>
                            <i className="fa fa-step-backward fa-2x text-blue-700"></i>
                        </button>
                        <button type="button" onClick={playPause}>
                            <i className={`fa ${isPlaying ? 'fa-pause-circle' : 'fa-play-circle'} text-blue-700 fa-5x`}></i>
                        </button>
                        <button type="button" onClick={nextTrack}>
                            <i className="fa fa-step-forward fa-2x text-blue-700"></i>
                        </button>
                    </div>
                    <div className="slider_container flex items-center gap-2 mb-4">
                        <div className="current-time text-blue-700">{formatTime(currentTime)}</div>
                        <input
                            ref={seekSliderRef}
                            type="range"
                            min="1"
                            max="100"
                            value={seekPercent}
                            className="seek_slider modern-slider"
                            onChange={(e) => seekTo(e.target.value)}
                        />
                        <div className="total-duration text-blue-700">{formatTime(duration)}</div>
                    </div>

                    <div className="w-full border-t border-blue-200 pt-4 mb-4">
                        <div className="flex items-center justify-center gap-3">
                            <img
                                src="/img/pemateri1.png"
                                alt="Pemateri"
                                className="w-10 h-10 rounded-full object-cover shadow-md border-2 border-blue-500"
                            />
                            <div className="flex flex-col items-start">
                                <p className="text-sm font-semibold text-blue-900">Ustadz Ugun Gunansyah</p>
                                <div className={`sound-wave ${isPlaying ? '' : 'paused'}`}>
                                    {Array.from({ length: BAR_COUNT }).map((_, index) => (
                                        <span
                                            key={index}
                                            className="bar"
                                            ref={(el) => { barsRef.current[index] = el; }}
                                        ></span>
                                    ))}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    );
}
