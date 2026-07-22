import { useState } from 'react';
import { Head, usePage } from '@inertiajs/react';
import FrontTopNav from '../Components/Nav/FrontTopNav';
import FrontBottomNav from '../Components/Nav/FrontBottomNav';
import BannerCarousel from '../Components/Home/BannerCarousel';
import PengumumanAlert from '../Components/Home/PengumumanAlert';
import MateriCard from '../Components/Home/MateriCard';
import JadwalUjianList from '../Components/Home/JadwalUjianList';
import MateriBerikutnya from '../Components/Home/MateriBerikutnya';
import AudioPlayerModal from '../Components/Home/AudioPlayerModal';

export default function Home({
    banners,
    angkatanCount,
    pengumuman,
    trial,
    jadwal,
    jadwalKhusus,
    ujianHarian,
    soalHarian,
    ujianHarianKhusus,
    mulaiBelajar,
    userGroups,
    jadwalUjian,
    jadwalUjianKhusus,
    jadwalBerikutnya,
    jadwalUjianBerikutnya,
}) {
    const { auth } = usePage().props;
    const user = auth?.user;

    const [audioModal, setAudioModal] = useState({ isOpen: false, trackIndex: 0, openKey: 0 });
    const [isPlaying, setIsPlaying] = useState(false);

    const tracks = [];
    if (jadwal && jadwal.materi_detail.jenis_kontent !== 'Video' && jadwal.materi_detail.multimedia_url) {
        tracks.push({
            name: jadwal.materi_detail.judul,
            artist: jadwal.materi.nama_materi,
            path: `storage/${jadwal.materi_detail.multimedia_url}`,
        });
    }
    if (jadwalKhusus && jadwalKhusus.materi_detail.jenis_kontent !== 'Video' && jadwalKhusus.materi_detail.multimedia_url) {
        tracks.push({
            name: jadwalKhusus.materi_detail.judul,
            artist: jadwalKhusus.materi.nama_materi,
            path: `storage/${jadwalKhusus.materi_detail.multimedia_url}`,
        });
    }

    const openAudioModal = (trackIndex) => setAudioModal((prev) => ({ isOpen: true, trackIndex, openKey: prev.openKey + 1 }));
    const closeAudioModal = () => setAudioModal((prev) => ({ ...prev, isOpen: false }));

    const jadwalKhususTrackIndex = jadwal ? 1 : 0;
    const isGroupEnrolled = userGroups.length > 0 && mulaiBelajar?.tanggal_mulai && mulaiBelajar.tanggal_mulai > new Date().toISOString().slice(0, 10);

    const allJadwalUjian = [...jadwalUjian, ...jadwalUjianKhusus];

    return (
        <>
            <Head>
                <title>BISI Online</title>
                <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css" />
                <link rel="stylesheet" href="/css/home.css" />
                <script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>
            </Head>

            {user && <FrontTopNav />}
            {user && <p className="py-6 bg-white-700"></p>}

            <div
                className="min-h-screen py-6 px-0 md:px-0"
                style={{ background: 'linear-gradient(135deg, #9fcaf1ff 0%, #2f56d4ff 100%)' }}
            >
                <div className="max-w-2xl mx-auto">
                    <div className="text-center mb-6">
                        <p className="text-2xl font-bold text-white">Ahlan wa Sahlan</p>
                        <p className="text-white">Semoga hari ini mendapatkan tambahan ilmu yang bermanfaat</p>
                    </div>

                    {!user ? (
                        <div className="modern-section">
                            <BannerCarousel banners={banners} />
                            <a href={route('login')} className="modern-btn w-full mt-5 block text-center">Masuk</a>
                            <a
                                href={route('register')}
                                className="modern-btn w-full mt-3 bg-white text-white border border-blue-200 hover:bg-blue-50 hover:text-blue-900 block text-center"
                            >
                                Daftar
                            </a>
                        </div>
                    ) : (
                        <div className="modern-section">
                            <BannerCarousel banners={banners} />

                            {angkatanCount === 0 && (
                                <div className="mb-2 text-center">Seperti nya belum ada materi yang tersedia</div>
                            )}

                            <PengumumanAlert pengumuman={pengumuman} />

                            {jadwal && (
                                <MateriCard
                                    jadwal={jadwal}
                                    canKerjakanSoal={Boolean(ujianHarian && soalHarian > 0)}
                                    kerjakanSoalHref={route('kuis', { materi_id: jadwal.materi.id, jadwal_id: ujianHarian?.id }) + `?trial=${trial ?? ''}`}
                                    trackIndex={0}
                                    isPlaying={isPlaying}
                                    onListen={() => openAudioModal(0)}
                                />
                            )}

                            {jadwalKhusus ? (
                                <MateriCard
                                    jadwal={jadwalKhusus}
                                    canKerjakanSoal={Boolean(ujianHarianKhusus)}
                                    kerjakanSoalHref={route('kuis', { materi_id: jadwalKhusus.materi.id, jadwal_id: ujianHarianKhusus?.id }) + `?trial=${trial ?? ''}`}
                                    trackIndex={jadwalKhususTrackIndex}
                                    isPlaying={isPlaying}
                                    onListen={() => openAudioModal(jadwalKhususTrackIndex)}
                                />
                            ) : (
                                isGroupEnrolled && (
                                    <div className="mt-5 text-center text-gray-700 bg-white p-4 rounded-lg shadow-md">
                                        Anda terdaftar di Group: {userGroups.map((g) => g.nama_group).join(', ')}
                                        <br />
                                        Mulai Pembelajaran: <strong>{mulaiBelajar.tanggal_mulai}</strong>
                                        <br />
                                    </div>
                                )
                            )}

                            <JadwalUjianList items={allJadwalUjian} />

                            <MateriBerikutnya
                                jadwalBerikutnya={jadwalBerikutnya}
                                jadwalUjianBerikutnya={jadwalUjianBerikutnya}
                            />
                        </div>
                    )}
                </div>
            </div>

            <FrontBottomNav />

            <AudioPlayerModal
                isOpen={audioModal.isOpen}
                trackIndex={audioModal.trackIndex}
                openKey={audioModal.openKey}
                tracks={tracks}
                onClose={closeAudioModal}
                onPlayStateChange={setIsPlaying}
            />
        </>
    );
}
