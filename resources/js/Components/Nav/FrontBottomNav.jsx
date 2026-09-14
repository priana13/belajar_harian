import { Link, usePage } from '@inertiajs/react';

export default function FrontBottomNav() {
    const { url } = usePage();
    const [path, query] = url.split('?');
    const segment = path.split('/').filter(Boolean)[0] ?? '';
    const trial = new URLSearchParams(query).get('trial') ?? '';
    const suffix = trial ? `?trial=${trial}` : '';

    const tabs = [
        { name: 'home', label: 'Home', icon: 'fa-house', match: '' },
        { name: 'materi_saya', label: 'Materi Saya', icon: 'fa-book-open', match: 'materiku' },
        { name: 'history_belajar', label: 'History Belajar', icon: 'fa-book-bookmark', match: 'history-belajar' },
        { name: 'profile', label: 'Akun', icon: 'fa-user-gear', match: 'profile' },
    ];

    return (
        <div>
            <div className="mt-16"></div>
            <section
                id="bottom-navigation"
                className="fixed inset-x-0 -bottom-[1px] z-10 bg-white shadow-top rounded-md py-1.5 pr-4 max-w-lg mx-auto text-gray-400"
            >
                <div id="tabs" className="flex justify-between">
                    {tabs.map((tab) => (
                        <Link
                            key={tab.name}
                            href={`${route(tab.name)}${suffix}`}
                            className={`w-full focus:text-teal-500 hover:text-[#1169a8] justify-center inline-block text-center pt-2 pb-1 border-[#E0E0E0] ${
                                segment === tab.match ? 'text-secondary' : ''
                            }`}
                        >
                            <i className={`fa-solid ${tab.icon} text-xl`}></i>
                            <span className="tab block text-xs">{tab.label}</span>
                        </Link>
                    ))}
                </div>
            </section>
        </div>
    );
}
