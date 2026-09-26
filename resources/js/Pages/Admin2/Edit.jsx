import React, { useState } from 'react';
import { Link, useForm } from '@inertiajs/react';
import Admin2Layout, { resourceUrl } from '../../Layouts/Admin2Layout';
import { Field, Reference } from './Fields';

export default function Edit({ resourceKey, definition, record, selected, relations }) {
    const base = resourceUrl(resourceKey, definition);
    const [relationLabels, setRelationLabels] = useState(relations);
    const initial = Object.fromEntries(definition.fields.map(f => {
        let value = f.type === 'file' || f.type === 'password' ? '' : record?.[f.name] ?? f.default ?? (f.type === 'checkbox' ? false : '');
        if (value && f.type === 'date') value = String(value).slice(0, 10);
        if (value && f.type === 'datetime-local') value = String(value).replace(' ', 'T').slice(0, 16);
        return [f.name, value];
    }));
    initial.relations = Object.fromEntries(Object.entries(relations).map(([name,items]) => [name, items.map(i => i.value)]));
    const form = useForm(initial);
    const title = `${record ? 'Edit' : 'Tambah'} ${definition.title}`;
    const errors = Object.values(form.errors);
    return <Admin2Layout title={title} configuration={definition.group === 'Konfigurasi'} activeTab={resourceKey}><div className="page-heading"><div className="page-header"><h2>{title}</h2><p>Lengkapi informasi berikut. Kolom bertanda * wajib diisi.</p></div><Link className="btn btn-outline" href={base}>← Kembali</Link></div>
        <form className="card edit-card" onSubmit={e => { e.preventDefault(); form.post(record ? `${base}/${record.id}` : base, { forceFormData: true }); }}>
            {errors.length > 0 && <div className="notice error" role="alert"><strong>Data belum tersimpan.</strong><ul>{errors.map((error,i) => <li key={i}>{error}</li>)}</ul></div>}
            <div className="form-grid">{definition.fields.map(field => <Field key={field.name} field={{ ...field, required: field.type === 'password' ? !record : field.required }} value={form.data[field.name]} existing={record?.[field.name]} error={form.errors[field.name]} onChange={v => form.setData(field.name,v)} base={base} selected={selected[field.name]}/>)}</div>
            {Object.keys(definition.relations || {}).map(name => <section className="relation-section" key={name}><h3>{name === 'gelombangs' ? 'Gelombang' : name === 'users' ? 'Anggota kelompok' : name === 'groups' ? 'Kelompok' : name === 'roadmaps' ? 'Roadmap' : name === 'soal' ? 'Soal ujian' : 'Materi roadmap'}</h3><Reference url={`${base}/options/${name}`} value="" onChange={(value,label) => { if (!value || form.data.relations[name].some(id => String(id) === value)) return; form.setData('relations', { ...form.data.relations, [name]: [...form.data.relations[name], value] }); setRelationLabels({ ...relationLabels, [name]: [...relationLabels[name], { value, label }] }); }}/><div className="relation-chips">{form.data.relations[name].map(id => <button type="button" key={id} className="relation-chip" onClick={() => form.setData('relations', { ...form.data.relations, [name]: form.data.relations[name].filter(v => v !== id) })}>{relationLabels[name].find(i => String(i.value) === String(id))?.label || id} ×</button>)}</div>{form.errors[`relations.${name}`] && <p className="field-error">{form.errors[`relations.${name}`]}</p>}</section>)}
            {form.progress && <p role="status">Mengunggah {form.progress.percentage}%</p>}<div className="form-footer"><Link className="btn btn-outline" href={base}>Batal</Link><button className="btn btn-primary" disabled={form.processing}>{form.processing ? 'Menyimpan…' : 'Simpan perubahan'}</button></div>
        </form></Admin2Layout>;
}
