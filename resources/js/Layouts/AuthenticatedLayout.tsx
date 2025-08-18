import { useState, PropsWithChildren, ReactNode } from 'react';
// import ApplicationLogo from '@/Components/ApplicationLogo';
// import Dropdown from '@/Components/Dropdown';
// import NavLink from '@/Components/NavLink';
// import ResponsiveNavLink from '@/Components/ResponsiveNavLink';
import { Link } from '@inertiajs/react';
import { User } from '@/types';

export default function Authenticated({ user, header, children }: PropsWithChildren<{ user: User, header?: ReactNode }>) {
    return (
        <div className="min-h-screen bg-gray-100">
            {/* simple nav bar */}
            <nav className="bg-white border-b border-gray-100 shadow-sm">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="flex justify-between h-16">
                        {/* page ttile from header */}
                        <div className="flex items-center">
                            {header}
                        </div>

                        {/* logout button */}
                        <div className="flex items-center">
                            <Link
                                href="/logout"
                                method="post"
                                as="button"
                                className="inline-flex items-center justify-center p-2 rounded-md text-red-400 hover:text-red-500 hover:bg-red-100 focus:outline-none focus:bg-red-100 focus:text-red-500 transition duration-150 ease-in-out"
                                title="Log Out"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" className="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" strokeWidth={2}>
                                    <path strokeLinecap="round" strokeLinejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                </svg>
                            </Link>
                        </div>
                    </div>
                </div>
            </nav>

            {/* page content */}
            <main>{children}</main>
        </div>
    );
}