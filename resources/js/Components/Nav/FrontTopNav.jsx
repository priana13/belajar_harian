import { useState, useRef, useEffect } from 'react';
import { Link, usePage, router } from '@inertiajs/react';

export default function FrontTopNav() {
    const { auth, can_logout } = usePage().props;
    const user = auth?.user;
    const [open, setOpen] = useState(false);
    const ref = useRef(null);

    useEffect(() => {
        function onClickAway(e) {
            if (ref.current && !ref.current.contains(e.target)) {
                setOpen(false);
            }
        }
        document.addEventListener('click', onClickAway);
        return () => document.removeEventListener('click', onClickAway);
    }, []);

    return (
        <nav className="bg-primary-700 py-3 z-40 px-5 grid grid-cols-2 fixed inset-x-0 max-w-lg mx-auto shadow-lg">
            <div className="flex items-center">
                <Link href={route('home')}>
                    <img
                        className="h-8 w-auto drop-shadow-[0_0_2px_rgba(255,255,255,0.8)]"
                        src="/img/logo-trf.png"
                        alt=""
                    />
                </Link>
            </div>

            {user && (
                <div ref={ref} className="flex items-center justify-end">
                    <button onClick={() => setOpen(!open)} className="flex items-center justify-center">
                        <div className="text-3xl text-gray-400">
                            {user.foto_profil ? (
                                <img
                                    className="h-6 w-6 rounded-full"
                                    src={`/storage/${user.foto_profil}`}
                                    alt=""
                                    onError={(e) => { e.target.onerror = null; e.target.src = '/img/user.jpeg'; }}
                                />
                            ) : (
                                <i className="fa-solid fa-circle-user"></i>
                            )}
                        </div>
                        <span className="text-sm ml-1 text-white capitalize">{user.name}</span>
                        <i className="fa-solid fa-caret-down text-gray-400 ml-0.5"></i>
                    </button>

                    {open && (
                        <div className="z-40 mt-48 bg-white border rounded-md shadow-md absolute">
                            <ul className="py-2 text-primary">
                                <li className="px-4 py-2 hover:bg-green-100">
                                    <Link href={route('materi_saya')} className="block">
                                        <i className="fa-solid fa-book-open mr-1"></i> Materi Saya
                                    </Link>
                                </li>
                                <li className="px-4 py-2 hover:bg-green-100">
                                    <Link href={route('profile')} className="block">
                                        <i className="fa-solid fa-user mr-1"></i> Akun
                                    </Link>
                                </li>
                                <li className="px-4 py-2 hover:bg-green-100">
                                    <a
                                        href="https://drive.google.com/file/d/1XxrvPeybGL4kjt-M10doS9Zw1AD_fPMM/view?usp=sharing"
                                        className="block"
                                        target="_blank"
                                        rel="noreferrer"
                                    >
                                        <i className="fa-solid fa-download mr-1"></i> Download APK
                                    </a>
                                </li>
                                <li className={`px-4 py-2 hover:bg-green-100 ${can_logout ? '' : 'hidden'}`}>
                                    <button
                                        type="button"
                                        className="block w-full text-left"
                                        onClick={() => router.post(route('logout'))}
                                    >
                                        <i className="fa-solid fa-right-from-bracket mr-1"></i> Log Out
                                    </button>
                                </li>
                            </ul>
                        </div>
                    )}
                </div>
            )}
        </nav>
    );
}
