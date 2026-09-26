import React, { useState } from 'react';
import { Link, router } from '@inertiajs/react';

export default function MaterialSection({ section, parentUrl }) {
    const [search, setSearch] = useState(section.search);
    const [deleting, setDeleting] = useState(null);
    const [busy, setBusy] = useState(false);
    const fields = Object.fromEntries(section.fields.map(field => [field.name, field]));
    const images = section.key === 'gambar-materi';
    const value = (row, column) => section.labels[column]?.[row[column]] ?? row[column] ?? '—';
    const actions = row => <div className="actions-cell"><Link className="btn btn-outline btn-sm" href={`/admin2/${section.key}/${row.id}/edit`}>{section.key === 'pertemuan' ? 'Edit & Gambar' : 'Edit'}</Link><button type="button" className="btn btn-danger btn-sm" onClick={() => setDeleting(row)}>Hapus</button></div>;
    return <section className="card data-card" aria-label={section.title}>
        <div className="section-heading"><div><h3>{section.title}</h3><p>{section.records.total} data untuk {images ? 'pertemuan' : 'materi'} ini.</p></div><Link className="btn btn-primary" href={section.createUrl}>+ Tambah {images ? 'Gambar' : section.title}</Link></div>
        {!images && <form className="table-tools" onSubmit={event => { event.preventDefault(); router.get(parentUrl, { tab: section.key, [`${section.key}_search`]: search }, { preserveState: true, preserveScroll: true }); }}><input aria-label={`Cari ${section.title}`} type="search" placeholder="Cari judul…" value={search} onChange={event => setSearch(event.target.value)}/><button className="btn btn-outline">Cari</button></form>}
        {images ? <div className="meeting-images">{section.records.data.map(row => <article key={row.id}><a href={row.image_url} target="_blank" rel="noreferrer"><img src={row.image_url} alt={`Gambar pertemuan ${row.id}`} loading="lazy"/></a>{actions(row)}</article>)}</div> : <div className="table-scroll"><table><thead><tr>{section.columns.map(column => <th key={column}>{fields[column]?.label || column}</th>)}<th>Aksi</th></tr></thead><tbody>{section.records.data.map(row => <tr key={row.id}>{section.columns.map(column => <td key={column}>{String(value(row, column))}</td>)}<td>{actions(row)}</td></tr>)}</tbody></table></div>}
        {!section.records.data.length && <div className="empty-state">Belum ada {section.title.toLowerCase()}{section.search ? ' yang sesuai pencarian' : ''}.</div>}
        <div className="table-bottom"><span>{section.records.from || 0}–{section.records.to || 0} dari {section.records.total}</span><nav className="pagination" aria-label={`Halaman ${section.title}`}>{section.records.prev_page_url && <Link className="btn btn-outline btn-sm" href={section.records.prev_page_url} preserveState preserveScroll>Sebelumnya</Link>}{section.records.next_page_url && <Link className="btn btn-outline btn-sm" href={section.records.next_page_url} preserveState preserveScroll>Berikutnya</Link>}</nav></div>
        {deleting && <dialog className="confirm-dialog" aria-labelledby="delete-child-title" ref={node => { if (node && !node.open) node.showModal(); }} onCancel={event => { if (busy) event.preventDefault(); else setDeleting(null); }}><h3 id="delete-child-title">Hapus {section.title}?</h3><p>{deleting.judul || 'Gambar ini'} akan dihapus. Data terkait dapat ikut terhapus sesuai relasi aplikasi.</p><div className="heading-actions"><button autoFocus className="btn btn-outline" disabled={busy} onClick={() => setDeleting(null)}>Batal</button><button className="btn btn-danger" disabled={busy} onClick={() => { setBusy(true); router.delete(`/admin2/${section.key}/${deleting.id}`, { preserveScroll: true, onFinish: () => { setBusy(false); setDeleting(null); } }); }}>{busy ? 'Menghapus…' : 'Ya, hapus'}</button></div></dialog>}
    </section>;
}
