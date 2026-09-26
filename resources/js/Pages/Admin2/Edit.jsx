import React, { useState } from 'react';
import { Link, useForm } from '@inertiajs/react';
import Admin2Layout, { resourceUrl } from '../../Layouts/Admin2Layout';
import { Field, Reference } from './Fields';
import MaterialSection from './MaterialSection';

export default function Edit({ resourceKey, definition, record, selected, relations, parentContext, initialValues = {}, sections = [], activeSection = 'detail' }) {
    const base = resourceUrl(resourceKey, definition);
    const [relationLabels, setRelationLabels] = useState(relations);
    const initial = Object.fromEntries(definition.fields.map(f => {
        let value = f.type === 'file' || f.type === 'password' ? '' : record?.[f.name] ?? initialValues[f.name] ?? f.default ?? (f.type === 'checkbox' ? false : '');
        if (value && f.type === 'date') value = String(value).slice(0, 10);
        if (value && f.type === 'datetime-local') value = String(value).replace(' ', 'T').slice(0, 16);
        return [f.name, value];
    }));
    if (parentContext) initial._parent_id = parentContext.id;
    initial.relations = Object.fromEntries(Object.entries(relations).map(([name,items]) => [name, items.map(i => i.value)]));
    const form = useForm(initial);
    const title = `${record ? 'Edit' : 'Tambah'} ${definition.title}`;
    const backUrl = parentContext?.url || base;
    const section = sections.find(item => item.key === activeSection);
    const errors = Object.values(form.errors);
    return <Admin2Layout title={title} configuration={definition.group === 'Konfigurasi'} activeTab={resourceKey}>{parentContext && <nav className="material-breadcrumb" aria-label="Lokasi materi"><Link href="/admin2/materi">Materi</Link>{parentContext.ancestor && <><span>›</span><Link href={parentContext.ancestor.url}>{parentContext.ancestor.title}</Link></>}<span>›</span><Link href={parentContext.url}>{parentContext.title}</Link><span>›</span><span>{definition.title}</span></nav>}<div className="page-heading"><div className="page-header"><h2>{title}</h2><p>Lengkapi informasi berikut. Kolom bertanda * wajib diisi.</p></div><Link className="btn btn-outline" href={backUrl}>← Kembali</Link></div>
        {sections.length > 0 && <nav className="config-tabs" aria-label="Pengelolaan materi"><Link className={!section ? 'selected' : ''} href={`${base}/${record.id}/edit`} preserveState preserveScroll>Informasi {definition.title}</Link>{sections.map(item => <Link key={item.key} className={section?.key === item.key ? 'selected' : ''} href={`${base}/${record.id}/edit?tab=${item.key}`} preserveState preserveScroll>{item.title} <span className="badge badge-blue">{item.records.total}</span></Link>)}</nav>}
        {section && <MaterialSection key={`${record.id}-${section.key}`} section={section} parentUrl={`${base}/${record.id}/edit`}/>}
        <form hidden={!!section} className="card edit-card" onSubmit={e => { e.preventDefault(); form.post(record ? `${base}/${record.id}` : base, { forceFormData: true }); }}>
            {errors.length > 0 && <div className="notice error" role="alert"><strong>Data belum tersimpan.</strong><ul>{errors.map((error,i) => <li key={i}>{error}</li>)}</ul></div>}
            <div className="form-grid">{definition.fields.filter(field => field.name !== parentContext?.field).map(field => <Field key={field.name} field={{ ...field, required: field.type === 'password' ? !record : field.required }} value={form.data[field.name]} existing={record?.[field.name]} error={form.errors[field.name]} onChange={v => form.setData(field.name,v)} base={base} optionsQuery={resourceKey === 'soal' && form.data.materi_id ? `?materi_id=${form.data.materi_id}` : ''} selected={selected[field.name]}/>)}</div>
            {Object.keys(definition.relations || {}).map(name => <section className="relation-section" key={name}><h3>{name === 'gelombangs' ? 'Gelombang' : name === 'users' ? 'Anggota kelompok' : name === 'groups' ? 'Kelompok' : name === 'roadmaps' ? 'Roadmap' : name === 'soal' ? 'Soal ujian' : 'Materi roadmap'}</h3><Reference url={`${base}/options/${name}`} value="" onChange={(value,label) => { if (!value || form.data.relations[name].some(id => String(id) === value)) return; form.setData('relations', { ...form.data.relations, [name]: [...form.data.relations[name], value] }); setRelationLabels({ ...relationLabels, [name]: [...relationLabels[name], { value, label }] }); }}/><div className="relation-chips">{form.data.relations[name].map(id => <button type="button" key={id} className="relation-chip" onClick={() => form.setData('relations', { ...form.data.relations, [name]: form.data.relations[name].filter(v => v !== id) })}>{relationLabels[name].find(i => String(i.value) === String(id))?.label || id} ×</button>)}</div>{form.errors[`relations.${name}`] && <p className="field-error">{form.errors[`relations.${name}`]}</p>}</section>)}
            {form.progress && <p role="status">Mengunggah {form.progress.percentage}%</p>}<div className="form-footer"><Link className="btn btn-outline" href={backUrl}>Batal</Link><button className="btn btn-primary" disabled={form.processing}>{form.processing ? 'Menyimpan…' : 'Simpan perubahan'}</button></div>
        </form>{!record && ['materi', 'pertemuan'].includes(resourceKey) && <p className="material-save-hint">Simpan {definition.title.toLowerCase()} terlebih dahulu untuk mengelola {resourceKey === 'materi' ? 'pertemuan dan bank soal' : 'gambar pertemuan'}.</p>}</Admin2Layout>;
}
