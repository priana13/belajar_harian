export default function JadwalUjianList({ items }) {
    if (items.length === 0) {
        return null;
    }

    return (
        <>
            <div className="modern-title mt-6 mb-2">Ujian Hari ini</div>
            {items.map((row) => (
                <div key={row.id} className="modern-card">
                    <div className="flex flex-col md:flex-row md:items-center md:justify-between gap-2">
                        <div>
                            <div className="modern-label">
                                Ujian {row.type} {row.type === 'Pekanan' ? row.urutan : ''}
                            </div>
                            <div className="modern-title">{row.materi.nama_materi}</div>
                        </div>
                        <a
                            href={route('kuis', { materi_id: row.materi_id, jadwal_id: row.id })}
                            className="modern-btn mt-2 md:mt-0 bg-white text-white border border-blue-200 hover:bg-blue-50"
                        >
                            Kerjakan Soal
                        </a>
                    </div>
                </div>
            ))}
        </>
    );
}
