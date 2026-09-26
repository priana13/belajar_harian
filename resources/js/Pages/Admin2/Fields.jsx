import React, { useEffect, useId, useState } from 'react';
import axios from 'axios';

export function Reference({ url, value, label, onChange, id, required = false }) {
    const [search, setSearch] = useState('');
    const [options, setOptions] = useState([]);
    const [error, setError] = useState(false);
    useEffect(() => {
        const controller = new AbortController();
        const timer = setTimeout(() => axios.get(url, { params: { search }, signal: controller.signal }).then(r => { setOptions(r.data); setError(false); }).catch(e => { if (e.code !== 'ERR_CANCELED') setError(true); }), 250);
        return () => { clearTimeout(timer); controller.abort(); };
    }, [url, search]);
    return <div className="reference-field"><input type="search" value={search} aria-label="Cari pilihan" placeholder="Ketik untuk mencari pilihan…" onChange={e => setSearch(e.target.value)}/><select id={id} required={required} value={value ?? ''} onChange={e => onChange(e.target.value, options.find(o => String(o.value) === e.target.value)?.label)}><option value="">Pilih…</option>{value && !options.some(o => String(o.value) === String(value)) && <option value={value}>{label || `#${value}`}</option>}{options.map(o => <option key={o.value} value={o.value}>{o.label}</option>)}</select>{error && <small className="field-error">Pilihan gagal dimuat. Coba pencarian kembali.</small>}</div>;
}
export function Field({ field, value, error, onChange, base, selected, existing, optionsQuery = '' }) {
    const id = useId();
    const props = { id, value: value ?? '', onChange: e => onChange(e.target.value), required: field.required, 'aria-invalid': !!error, 'aria-describedby': error ? `${id}-error` : undefined };
    return <div className={`form-field ${field.type === 'textarea' ? 'span-full' : ''}`}><label htmlFor={id}>{field.label}{field.required && <span className="required"> *</span>}</label>
        {field.type === 'reference' ? <Reference id={id} url={`${base}/options/${field.name}${optionsQuery}`} value={value} label={selected} onChange={onChange} required={field.required}/>
            : field.type === 'select' ? <select {...props}><option value="">Pilih…</option>{field.options.map(o => <option key={o.value} value={o.value}>{o.label}</option>)}</select>
            : field.type === 'textarea' ? <><textarea {...props} rows={6}/><small>Konten yang sudah berformat HTML dapat diedit di sini.</small></>
            : field.type === 'checkbox' ? <label className="toggle-label"><input id={id} type="checkbox" checked={!!Number(value)} onChange={e => onChange(e.target.checked)} aria-invalid={!!error}/> Aktif</label>
            : field.type === 'file' ? <><input id={id} type="file" accept={field.accept} required={field.required && !existing} onChange={e => onChange(e.target.files[0] || null)}/>{existing && <small>Berkas tersimpan: {existing.split('/').pop()}. Unggah untuk mengganti.</small>}</>
            : <input {...props} type={field.type} step={field.type === 'number' ? 'any' : undefined} min={field.min} max={field.type === 'number' ? field.max : undefined} maxLength={field.type === 'number' ? undefined : field.max} autoComplete={field.type === 'password' ? 'new-password' : undefined}/>}
        {error && <p className="field-error" id={`${id}-error`}>{error}</p>}
    </div>;
}
