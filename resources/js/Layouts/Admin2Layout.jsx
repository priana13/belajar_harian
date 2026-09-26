import React, { useState } from 'react';
import { Head, Link, usePage } from '@inertiajs/react';

export function Icon({ name = 'grid', size = 19 }) {
    const paths = {
        grid: 'M3 3h7v7H3z M14 3h7v7h-7z M3 14h7v7H3z M14 14h7v7h-7z',
        users: 'M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2 M9 11a4 4 0 1 0 0-8 4 4 0 0 0 0 8 M20 21v-2a4 4 0 0 0-3-3.87 M16 3.13a4 4 0 0 1 0 7.75',
        book: 'M4 3h7v18H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2z M11 3h9a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-9 M6 7h2 M15 7h3 M15 11h3',
        map: 'm3 6 6-3 6 3 6-3v15l-6 3-6-3-6 3z M9 3v15 M15 6v15',
        calendar: 'M4 5h16v16H4z M4 10h16 M8 2v6 M16 2v6 M8 14h2 M14 14h2',
        settings: 'M12 8a4 4 0 1 0 0 8 4 4 0 0 0 0-8 M12 2v3 M12 19v3 M2 12h3 M19 12h3 M5 5l2 2 M17 17l2 2 M5 19l2-2 M17 7l2-2',
        menu: 'M3 6h18 M3 12h18 M3 18h18',
        arrow: 'M5 12h14 M13 6l6 6-6 6',
    };
    return <svg width={size} height={size} viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="1.7" strokeLinecap="round" strokeLinejoin="round" aria-hidden="true"><path d={paths[name] || paths.book}/></svg>;
}
export function ConfigTabs({ active = 'general' }) {
    const { navigation } = usePage().props;
    return <nav className="config-tabs" aria-label="Submenu konfigurasi"><Link className={active === 'general' ? 'selected' : ''} href="/admin2/konfigurasi">Umum & Pengumuman</Link>{navigation.filter(n => n.group === 'Konfigurasi').map(n => <Link key={n.key} href={n.url} className={active === n.key ? 'selected' : ''}>{n.title}</Link>)}</nav>;
}
export const resourceUrl = (key, definition) => definition.group === 'Konfigurasi' ? `/admin2/konfigurasi/${key}` : `/admin2/${key}`;
export default function Admin2Layout({ title, children, configuration = false, activeTab }) {
    const { auth, navigation = [], flash = {}, settings } = usePage().props;
    const { url } = usePage();
    const [open, setOpen] = useState(false);
    const groups = [...new Set(navigation.filter(n => n.group !== 'Konfigurasi').map(n => n.group))];
    const icons = { peserta: 'users', materi: 'book', roadmap: 'map', 'jadwal-belajar': 'calendar', 'jadwal-ujian': 'calendar' };
    const materialChild = /^\/admin2\/(pertemuan|soal|gambar-materi)(\/|\?|$)/.test(url);
    const active = path => (path === '/admin2/materi' && materialChild) || (path === '/admin2' ? url.split('?')[0] === path : url === path || url.startsWith(path + '/') || url.startsWith(path + '?'));
    return <><Head title={title}/><button className={`overlay ${open ? 'show' : ''}`} aria-label="Tutup menu" onClick={() => setOpen(false)}/>
        <aside className={`sidebar ${open ? 'open' : ''}`}><Link href="/admin2" className="sidebar-logo"><div className="logo-icon">{settings?.logo_url ? <img src={settings.logo_url} alt=""/> : 'Bi'}</div><div className="logo-text"><span className="logo-name">Bisi Online</span><span className="logo-sub">Admin Dashboard</span></div></Link>
            <nav className="sidebar-nav" aria-label="Menu admin"><Link href="/admin2" className={`nav-item ${active('/admin2') ? 'active' : ''}`} onClick={() => setOpen(false)}><Icon/>Dashboard</Link>
                {groups.map(group => <React.Fragment key={group}><div className="nav-section-label">{group}</div>{navigation.filter(n => n.group === group).map(n => <Link key={n.key} href={n.url} className={`nav-item ${active(n.url) ? 'active' : ''}`} onClick={() => setOpen(false)}><Icon name={icons[n.key]}/>{n.title}</Link>)}</React.Fragment>)}
                <div className="nav-section-label">Laporan</div><Link href="/admin2/keaktifan-peserta" className={`nav-item ${active('/admin2/keaktifan-peserta') ? 'active' : ''}`}><Icon name="users"/>Keaktifan Peserta</Link><Link href="/admin2/link-materi-harian" className={`nav-item ${active('/admin2/link-materi-harian') ? 'active' : ''}`}><Icon name="calendar"/>Link Materi Harian</Link><div className="nav-section-label">Sistem</div><Link href="/admin2/konfigurasi" className={`nav-item ${configuration ? 'active' : ''}`} onClick={() => setOpen(false)}><Icon name="settings"/>Konfigurasi</Link>
                <a href="/admin" className="nav-item"><Icon name="arrow"/>Admin lama</a></nav>
            <div className="sidebar-footer"><div className="user-card"><div className="user-avatar">{auth.user.name.slice(0, 2).toUpperCase()}</div><div className="user-info"><div className="user-name">{auth.user.name}</div><div className="user-role">Administrator</div></div><Link as="button" method="post" href="/admin2/logout" className="logout-btn" aria-label="Keluar akun">↪</Link></div></div>
        </aside><main className="main-wrap"><header><button className="icon-btn mobile-menu" aria-label="Buka menu" aria-expanded={open} onClick={() => setOpen(!open)}><Icon name="menu"/></button><div className="header-title">{configuration ? 'Konfigurasi' : title} <span>Bisi Online</span></div><a className="btn btn-outline btn-sm" href="/">Lihat aplikasi <Icon name="arrow" size={14}/></a></header>
            <div className="content">{flash.success && <div className="notice success" role="status">{flash.success}</div>}{flash.error && <div className="notice error" role="alert">{flash.error}</div>}{configuration && <><div className="page-header"><h2>Konfigurasi Sistem</h2><p>Kelola pengaturan dan data pendukung aplikasi dalam satu tempat.</p></div><ConfigTabs active={activeTab}/></>}{children}</div><footer className="admin-footer">Bisi Online · Ruang belajar, tumbuh bersama.</footer>
        </main></>;
}
