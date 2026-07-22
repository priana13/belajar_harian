import YoutubeMaterialPlayer from './YoutubeMaterialPlayer';

export default function MateriCard({ jadwal, canKerjakanSoal, kerjakanSoalHref, trackIndex, isPlaying, onListen }) {
    const { materi_detail: materiDetail, materi } = jadwal;
    const isVideo = materiDetail.jenis_kontent === 'Video';

    return (
        <div className="bg-white p-4 rounded-lg shadow-md mb-4 hover:shadow-lg transition-shadow duration-300 cursor-pointer">
            <div className="flex flex-col gap-2">
                <div className="flex justify-between items-center">
                    <span className="modern-badge">{materi.kategori.nama_kategori}</span>
                </div>
                <div className="modern-title">{materiDetail.judul}</div>
                <div className="text-xs font-semibold text-blue-900">{materi.nama_materi}</div>
                <div className="flex gap-2 text-xs mt-2">
                    <div className="bg-blue-100 text-blue-700 py-1 px-2 rounded-md font-semibold flex items-center justify-center">
                        {materiDetail.pertemuan}
                    </div>
                    <div className="bg-blue-100 text-blue-700 py-1 px-2 rounded-md font-semibold">
                        {new Date(jadwal.tanggal).toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric' })}
                    </div>
                </div>

                {isVideo ? (
                    <YoutubeMaterialPlayer videoUrl={materiDetail.video_url} domId={`materi_video_${trackIndex}`} />
                ) : (
                    <>
                        <button
                            type="button"
                            onClick={onListen}
                            className={`modern-btn w-full mt-4 ${isPlaying ? 'is-audio-playing' : ''}`}
                        >
                            {isPlaying ? 'TAMPILKAN AUDIO' : 'DENGARKAN MATERI'}
                        </button>
                        {isPlaying && (
                            <div className="audio-playing-notice mt-3" role="status" aria-live="polite">
                                <span className="pulse-dot" aria-hidden="true"></span>
                                <span>Ada audio yang sedang diputar. Klik "Tampilkan Audio" untuk membuka pemutar.</span>
                            </div>
                        )}
                        {canKerjakanSoal && (
                            <a
                                href={kerjakanSoalHref}
                                className="modern-btn w-full mt-3 bg-white text-white text-center border border-blue-200 hover:bg-blue-50"
                            >
                                Kerjakan Soal
                            </a>
                        )}
                    </>
                )}
            </div>
        </div>
    );
}
