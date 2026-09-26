import React, { useState } from 'react';
import { Link, router } from '@inertiajs/react';
import Admin2Layout, { resourceUrl } from '../../Layouts/Admin2Layout';
import { Reference } from './Fields';

export default function Records({ resourceKey, definition, records, labels, filters }) {
    const base = resourceUrl(resourceKey, definition);
    const [search, setSearch] = useState(filters.search || '');
    const [deleting, setDeleting] = useState(null);
    const [busy, setBusy] = useState(false);
    const fields = Object.fromEntries(definition.fields.map(f => [f.name, f]));
    const filterFields = definition.fields.filter(f => ['select', 'reference', 'checkbox'].includes(f.type));
    function visit(values) { router.get(base, { ...filters, ...values }, { preserveState: true, preserveScroll: true, replace: true }); }
    function cell(row, key) {
        const value = row[key];
        if (value === null || value === undefined || value === '') return <span className="muted">—</span>;
        const field = fields[key];
        if (field.type === 'reference') return labels[key]?.[value] || `#${value}`;
        if (field.type === 'checkbox') return <span className={`badge ${Number(value) ? 'badge-green' : 'badge-gray'}`}>{Number(value) ? 'Aktif' : 'Tidak aktif'}</span>;
        if (field.type === 'select') return <span className={`badge ${['Aktif', 'Selesai', 'hadir'].includes(value) ? 'badge-green' : 'badge-blue'}`}>{value}</span>;
        return String(value);
    }
    const exportQuery = new URLSearchParams(Object.entries(filters).filter(([,v]) => v !== null && v !== '')).toString();
    return <Admin2Layout title={definition.title} configuration={definition.group === 'Konfigurasi'} activeTab={resourceKey}>
        <div className="page-heading"><div className="page-header"><h2>{definition.title}</h2><p>Kelola {definition.title.toLowerCase()} · {records.total.toLocaleString('id-ID')} data</p></div><div className="heading-actions"><a className="btn btn-outline" href={`${base}/export?${exportQuery}`}>↓ Ekspor CSV</a><Link className="btn btn-primary" href={`${base}/create`}>+ Tambah {definition.title}</Link></div></div>
        <section className="card data-card"><form className="table-tools" onSubmit={e => { e.preventDefault(); visit({ search }); }}><input aria-label={`Cari ${definition.title}`} type="search" placeholder={`Cari ${definition.title.toLowerCase()}…`} value={search} onChange={e => setSearch(e.target.value)}/><button className="btn btn-primary">Cari</button><button type="button" className="btn btn-outline" onClick={() => { setSearch(''); router.get(base); }}>Reset</button></form>
            {filterFields.length > 0 && <details className="filter-panel"><summary>Filter data{Object.keys(filters).some(k => k.startsWith('filter_') && filters[k]) ? ' · Aktif' : ''}</summary><div className="filter-grid">{filterFields.map(f => <label key={f.name}>{f.label}{f.type === 'reference' ? <Reference url={`${base}/options/${f.name}`} value={filters[`filter_${f.name}`]} onChange={value => visit({ [`filter_${f.name}`]: value })}/> : <select value={filters[`filter_${f.name}`] ?? ''} onChange={e => visit({ [`filter_${f.name}`]: e.target.value })}><option value="">Semua</option>{(f.options || [{ value: '1', label: 'Aktif' }, { value: '0', label: 'Tidak aktif' }]).map(o => <option key={o.value} value={o.value}>{o.label}</option>)}</select>}</label>)}</div></details>}
            <div className="table-scroll"><table><thead><tr><th>No.</th>{definition.columns.map(key => <th key={key} aria-sort={filters.sort === key ? filters.direction === 'asc' ? 'ascending' : 'descending' : 'none'}><button className="sort-button" onClick={() => visit({ sort: key, direction: filters.sort === key && filters.direction === 'asc' ? 'desc' : 'asc' })}>{fields[key]?.label || key} <span>{filters.sort === key ? filters.direction === 'asc' ? '↑' : '↓' : '↕'}</span></button></th>)}<th>Aksi</th></tr></thead><tbody>{records.data.map((row, i) => <tr key={row.id}><td className="muted">{records.from + i}</td>{definition.columns.map((key, j) => <td key={key}>{j === 0 && resourceKey === 'peserta' ? <div className="cell-user"><div className="avatar-sm">{String(row[key]).slice(0,2).toUpperCase()}</div><span className="cell-name">{cell(row,key)}</span></div> : cell(row,key)}</td>)}<td><div className="actions-cell"><Link className="btn btn-outline btn-sm" href={`${base}/${row.id}/edit`}>Edit</Link><button className="btn btn-danger btn-sm" onClick={() => setDeleting(row)}>Hapus</button></div></td></tr>)}</tbody></table></div>
            {!records.data.length && <div className="empty-state"><strong>Belum ada data yang ditemukan</strong><p>Ubah pencarian atau tambahkan data baru.</p></div>}
            <div className="table-bottom"><span>Menampilkan {records.from || 0}–{records.to || 0} dari {records.total} data</span><nav className="pagination" aria-label="Halaman tabel">{records.links.map((link,i) => link.url ? <Link key={i} className={`pag-btn ${link.active ? 'active' : ''}`} href={link.url} preserveScroll aria-current={link.active ? 'page' : undefined}>{i === 0 ? '‹' : i === records.links.length-1 ? '›' : link.label}</Link> : <span key={i} className="pag-btn disabled">{i === 0 ? '‹' : i === records.links.length-1 ? '›' : '…'}</span>)}</nav></div>
        </section>
        {deleting && <div className="modal-backdrop" onKeyDown={e => { if (e.key === 'Escape' && !busy) setDeleting(null); }}><section className="confirm-dialog" role="alertdialog" aria-modal="true" aria-labelledby="delete-title"><h3 id="delete-title">Hapus data ini?</h3><p>{deleting[definition.columns[0]] || `Data #${deleting.id}`} akan dihapus. Data terkait dapat ikut terhapus sesuai relasi aplikasi.</p><div className="heading-actions"><button autoFocus className="btn btn-outline" disabled={busy} onClick={() => setDeleting(null)}>Batal</button><button className="btn btn-danger" disabled={busy} onClick={() => { setBusy(true); router.delete(`${base}/${deleting.id}`, { onFinish: () => { setBusy(false); setDeleting(null); } }); }}>{busy ? 'Menghapus…' : 'Ya, hapus'}</button></div></section></div>}
    </Admin2Layout>;
}
