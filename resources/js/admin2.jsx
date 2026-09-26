import './bootstrap';
import '../css/admin2.css';
import { createInertiaApp } from '@inertiajs/react';
import { createRoot } from 'react-dom/client';

createInertiaApp({
    title: title => `${title} — Bisi Online`,
    resolve: name => {
        const pages = import.meta.glob('./Pages/Admin2/**/*.jsx', { eager: true });
        return pages[`./Pages/${name}.jsx`];
    },
    setup({ el, App, props }) { createRoot(el).render(<App {...props} />); },
});
