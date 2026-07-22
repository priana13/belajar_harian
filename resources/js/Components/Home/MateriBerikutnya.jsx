function formatTanggalPanjang(tanggal) {
    return new Date(tanggal).toLocaleDateString('id-ID', {
        weekday: 'long',
        day: '2-digit',
        month: 'long',
        year: 'numeric',
    });
}

function TimelineItem({ badge, dateLabel, children }) {
    return (
        <li>
            <div className="flex-start md:flex">
                <div className="-ms-[13px] flex h-[25px] w-[25px] items-center justify-center rounded-full bg-info-100 text-info-700">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" className="h-4 w-4">
                        <path
                            fillRule="evenodd"
                            d="M6.75 2.25A.75.75 0 017.5 3v1.5h9V3A.75.75 0 0118 3v1.5h.75a3 3 0 013 3v11.25a3 3 0 01-3 3H5.25a3 3 0 01-3-3V7.5a3 3 0 013-3H6V3a.75.75 0 01.75-.75zm13.5 9a1.5 1.5 0 00-1.5-1.5H5.25a1.5 1.5 0 00-1.5 1.5v7.5a1.5 1.5 0 001.5 1.5h13.5a1.5 1.5 0 001.5-1.5v-7.5z"
                            clipRule="evenodd"
                        />
                    </svg>
                </div>
                <div className="mb-10 ms-6 block w-full rounded-lg bg-neutral-50 p-6 shadow-md shadow-black/5 dark:bg-neutral-700 dark:shadow-black/10">
                    <div className="mb-4 relative pt-4">
                        <p className="text-sm text-info">{children}</p>
                        <span className="text-sm text-info absolute -left-6 -top-6 bg-gray-200 rounded px-2 py-1">
                            {dateLabel}
                        </span>
                    </div>
                </div>
            </div>
        </li>
    );
}

export default function MateriBerikutnya({ jadwalBerikutnya, jadwalUjianBerikutnya }) {
    if (jadwalBerikutnya.length === 0 && jadwalUjianBerikutnya.length === 0) {
        return null;
    }

    return (
        <div className="mt-5">
            <br />
            <br />
            {jadwalBerikutnya.length > 0 && (
                <h3 className="mb-6 text-xl font-bold text-neutral-700 dark:text-neutral-300 border-b py-2">
                    Materi Berikutnya
                </h3>
            )}

            <ol className="border-s-2 border-info-100">
                {jadwalBerikutnya.map((row, index) => (
                    <TimelineItem key={`belajar-${index}`} dateLabel={formatTanggalPanjang(row.tanggal)}>
                        Materi Audio: {row.materi_detail.judul}
                    </TimelineItem>
                ))}

                {jadwalUjianBerikutnya.map((row, index) => (
                    <TimelineItem key={`ujian-${index}`} dateLabel={formatTanggalPanjang(row.tanggal)}>
                        Evaluasi {row.type} - Materi {row.materi.nama_materi}
                    </TimelineItem>
                ))}
            </ol>
        </div>
    );
}
