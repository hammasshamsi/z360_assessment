import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head, Link, router, usePage } from '@inertiajs/react';
import { PageProps } from '@/types';
import { FormEvent, useState } from 'react';

// --- ts interfacres for props ---
interface Stats {
    total_tenants: number; active_tenants: number; pending_tenants: number;
    provisioning_tenants: number; failed_tenants: number; total_sessions: number;
}
interface RecentSession {
    id: number; full_name: string; email: string; is_complete: boolean;
    created_at: string; token: string;
    resume_url?: string; 
}
interface Tenant {
    id: number; name: string; domain: string; status: string;
    database: string; created_at: string;
}
interface PaginatedTenants {
    data: Tenant[];
    links: { url: string | null; label: string; active: boolean }[];
    total: number;
}
interface DashboardProps extends PageProps {
    stats: Stats; tenants: PaginatedTenants; recentSessions: RecentSession[];
    filters: { search: string; status: string };
    db_context: string; spatie_context: string;
}

const StatCard = ({ title, value, icon, color }: { title: string, value: string | number, icon: JSX.Element, color: string }) => (
    <div className="bg-white overflow-hidden shadow-sm rounded-lg border">
        <div className="p-5 flex items-center">
            <div className={`flex-shrink-0 w-12 h-12 rounded-lg flex items-center justify-center ${color}`}>
                {icon}
            </div>
            <div className="ml-5 flex-1">
                <p className="text-sm font-medium text-gray-500 truncate">{title}</p>
                <p className="text-3xl font-bold text-gray-900">{value}</p>
            </div>
        </div>
    </div>
);
const Pagination = ({ links }: { links: PaginatedTenants['links'] }) => (
    <div className="px-6 py-4 border-t border-gray-200">
        <div className="flex flex-wrap -mb-1">
            {links.map((link, key) => (
                link.url ? (
                    <Link key={key} href={link.url} className={`mr-1 mb-1 px-4 py-3 text-sm leading-4 border rounded ${link.active ? 'bg-blue-700 text-white' : 'bg-white'}`} dangerouslySetInnerHTML={{ __html: link.label }} />
                ) : (
                    <div key={key} className="mr-1 mb-1 px-4 py-3 text-sm leading-4 text-gray-400 border rounded" dangerouslySetInnerHTML={{ __html: link.label }} />
                )
            ))}
        </div>
    </div>
);

/*
|---------------------------------------------------------------------------
| Browser is disabling navigator.clipboard API for security reasons most probably 
| because HTTPS is required so we are tring to handle it garcefully
|---------------------------------------------------------------------------
*/
const CopyResumeLinkButton = ({ session }: { session: RecentSession }) => {
    const [buttonText, setButtonText] = useState('Copy Resume Link');
    const [isSuccess, setIsSuccess] = useState(false);

    const copyLink = () => {
        if (!session.resume_url) return;

        const showSuccess = () => {
            setButtonText('Link Copied!');
            setIsSuccess(true);
            setTimeout(() => {
                setButtonText('Copy Resume Link');
                setIsSuccess(false);
            }, 2000);
        };

        // in secure contexts https
        if (navigator.clipboard) {
            navigator.clipboard.writeText(session.resume_url).then(showSuccess);
        } else {
            // fallback for insecure contexts http
            const textArea = document.createElement("textarea");
            textArea.value = session.resume_url;
            textArea.style.position = "fixed";
            textArea.style.left = "-9999px";
            document.body.appendChild(textArea);
            textArea.focus();
            textArea.select();
            try {
                document.execCommand('copy');
                showSuccess();
            } catch (err) {
                console.error('Fallback: unable to copy', err);
            }
            document.body.removeChild(textArea);
        }
    };

    return (
        <button onClick={copyLink} className={`ml-2 inline-flex items-center px-3 py-1.5 border rounded-md text-xs font-medium transition-all duration-200 ${isSuccess ? 'text-green-700 bg-green-50 border-green-300' : 'text-blue-700 bg-blue-50 hover:bg-blue-100 border-blue-300'}`}>
            {buttonText}
        </button>
    );
};

// --- main dashboard component ---
export default function Dashboard({ auth, stats, tenants, recentSessions, filters, db_context, spatie_context }: DashboardProps) {
    const { flash = {} } = usePage().props;
    const [search, setSearch] = useState(filters.search || '');
    const [status, setStatus] = useState(filters.status || '');

    const handleSearch = (e: FormEvent) => {
        e.preventDefault();
        router.get(route('landlord.dashboard'), { search, status }, { preserveState: true, replace: true });
    };

    const clearFilters = () => {
        setSearch('');
        setStatus('');
        router.get(route('landlord.dashboard'));
    };

    const deleteTenant = (tenant: Tenant) => {
        if (confirm('Are you sure? This will permanently delete the tenant and all associated data.')) {
            router.delete(route('landlord.tenant.delete', tenant.id));
        }
    };

    const getStatusClass = (status: string) => {
        switch (status) {
            case 'active': return 'bg-green-100 text-green-800';
            case 'pending': return 'bg-yellow-100 text-yellow-800';
            case 'provisioning': return 'bg-blue-100 text-blue-800';
            default: return 'bg-red-100 text-red-800';
        }
    };

    return (
        <AuthenticatedLayout user={auth.user} header={<h2 className="font-semibold text-xl text-gray-800 leading-tight">Landlord Dashboard</h2>}>
            <Head title="Landlord Dashboard" />

            <div className="py-12">
                <div className="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                    {/* context info Section */}
                    <div className="px-4 sm:px-0">
                        <div className="flex flex-wrap items-center gap-4 text-sm">
                            <span className="flex items-center px-3 py-1.5 bg-white border border-gray-200 rounded-lg shadow-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" className="h-5 w-5 mr-2 text-gray-500" viewBox="0 0 20 20" fill="currentColor"><path fillRule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clipRule="evenodd" /></svg>
                                <strong className="font-semibold mr-1 text-gray-600">User:</strong>
                                <span className="text-gray-800">{auth.user.email}</span>
                            </span>
                            <span className="flex items-center px-3 py-1.5 bg-white border border-gray-200 rounded-lg shadow-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" className="h-5 w-5 mr-2 text-blue-500" viewBox="0 0 20 20" fill="currentColor"><path d="M3 12v3c0 1.657 3.134 3 7 3s7-1.343 7-3v-3c0 1.657-3.134 3-7 3s-7-1.343-7-3z" /><path d="M3 7v3c0 1.657 3.134 3 7 3s7-1.343 7-3V7c0 1.657-3.134 3-7 3S3 8.657 3 7z" /><path d="M17 5c0 1.657-3.134 3-7 3S3 6.657 3 5s3.134-3 7-3 7 1.343 7 3z" /></svg>
                                <strong className="font-semibold mr-1 text-gray-600">DB:</strong>
                                <span className="text-blue-800 font-medium">{db_context}</span>
                            </span>
                            <span className="flex items-center px-3 py-1.5 bg-white border border-gray-200 rounded-lg shadow-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" className="h-5 w-5 mr-2 text-purple-500" viewBox="0 0 20 20" fill="currentColor"><path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z" /></svg>
                                <strong className="font-semibold mr-1 text-gray-600">Context:</strong>
                                <span className="text-purple-800 font-medium">{spatie_context}</span>
                            </span>
                        </div>
                    </div>

                    {flash.success && <div className="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg">{flash.success}</div>}
                    {flash.error && <div className="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">{flash.error}</div>}

                    <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6">
                        <StatCard title="Total Tenants" value={stats.total_tenants} color="bg-blue-100 text-blue-600" icon={<svg xmlns="http://www.w3.org/2000/svg" className="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" /></svg>} />
                        <StatCard title="Active" value={stats.active_tenants} color="bg-green-100 text-green-600" icon={<svg xmlns="http://www.w3.org/2000/svg" className="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>} />
                        <StatCard title="Pending" value={stats.pending_tenants + stats.provisioning_tenants} color="bg-yellow-100 text-yellow-600" icon={<svg xmlns="http://www.w3.org/2000/svg" className="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>} />
                        <StatCard title="Failed" value={stats.failed_tenants} color="bg-red-100 text-red-600" icon={<svg xmlns="http://www.w3.org/2000/svg" className="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>} />
                        <StatCard title="Sessions" value={stats.total_sessions} color="bg-purple-100 text-purple-600" icon={<svg xmlns="http://www.w3.org/2000/svg" className="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M11 4a2 2 0 114 0v1a1 1 0 001 1h3a1 1 0 011 1v3a1 1 0 01-1 1h-1a2 2 0 100 4h1a1 1 0 011 1v3a1 1 0 01-1 1h-3a1 1 0 01-1-1v-1a2 2 0 10-4 0v1a1 1 0 01-1 1H7a1 1 0 01-1-1v-3a1 1 0 00-1-1H4a2 2 0 110-4h1a1 1 0 001-1V7a1 1 0 011-1h3a1 1 0 001-1V4z" /></svg>} />
                    </div>

                    <div className="bg-white shadow-sm rounded-lg border">
                        <form onSubmit={handleSearch} className="px-6 py-4 flex gap-4 items-end">
                            <div className="flex-1">
                                <label htmlFor="search" className="block text-sm font-medium text-gray-700 mb-1">Search Tenants</label>
                                <input type="text" value={search} onChange={e => setSearch(e.target.value)} placeholder="Search by name, domain..." className="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" />
                            </div>
                            <div>
                                <label htmlFor="status" className="block text-sm font-medium text-gray-700 mb-1">Status</label>
                                <select value={status} onChange={e => setStatus(e.target.value)} className="px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <option value="">All Statuses</option>
                                    <option value="active">Active</option><option value="pending">Pending</option>
                                    <option value="provisioning">Provisioning</option><option value="failed">Failed</option>
                                </select>
                            </div>
                            <button type="submit" className="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Search</button>
                            {(filters.search || filters.status) && <button type="button" onClick={clearFilters} className="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600">Clear</button>}
                        </form>
                    </div>

                    <div className="bg-white shadow-sm rounded-lg border">
                        <div className="px-6 py-4 border-b border-gray-200"><h2 className="text-lg font-medium text-gray-900">All Tenants ({tenants.total} total)</h2></div>
                        <div className="overflow-x-auto">
                            <table className="min-w-full divide-y divide-gray-200">
                                <thead className="bg-gray-50">
                                    <tr>
                                        <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tenant Info</th>
                                        <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                        <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Database</th>
                                        <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Created</th>
                                        <th className="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                                    </tr>
                                </thead>
                                <tbody className="bg-white divide-y divide-gray-200">
                                    {tenants.data.map((tenant) => (
                                        <tr key={tenant.id} className="hover:bg-gray-50">
                                            <td className="px-6 py-4"><div className="text-sm font-medium text-gray-900">{tenant.name}</div><div className="text-sm text-gray-500">{tenant.domain}</div></td>
                                            <td className="px-6 py-4"><span className={`inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${getStatusClass(tenant.status)}`}>{tenant.status}</span></td>
                                            <td className="px-6 py-4 text-sm text-gray-500">{tenant.database}</td>
                                            <td className="px-6 py-4 text-sm text-gray-500">{new Date(tenant.created_at).toLocaleString()}</td>
                                            <td className="px-6 py-4 text-sm font-medium">
                                                <div className="flex space-x-2">
                                                    <Link
                                                        href={route('landlord.tenant.details', tenant.id)}
                                                        className="inline-block px-3 py-1 text-xs font-semibold rounded-md shadow-sm transition-colors duration-150 bg-blue-100 text-blue-800 hover:bg-blue-200"
                                                    >
                                                        View
                                                    </Link>
                                                    {tenant.status === 'active' && (
                                                        <a
                                                            href={`http://${tenant.domain}.myapp.test`}
                                                            target="_blank"
                                                            className="inline-block px-3 py-1 text-xs font-semibold rounded-md shadow-sm transition-colors duration-150 bg-green-100 text-green-800 hover:bg-green-200"
                                                        >
                                                            Access
                                                        </a>
                                                    )}
                                                    <button
                                                        onClick={() => deleteTenant(tenant)}
                                                        className="inline-block px-3 py-1 text-xs font-semibold rounded-md shadow-sm transition-colors duration-150 bg-red-100 text-red-800 hover:bg-red-200"
                                                    >
                                                        Delete
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    ))}
                                    {tenants.data.length === 0 && <tr><td colSpan={5} className="px-6 py-12 text-center text-gray-500">No tenants found.</td></tr>}
                                </tbody>
                            </table>
                        </div>
                        {tenants.data.length > 0 && <Pagination links={tenants.links} />}
                    </div>

                    <div className="bg-white shadow-sm rounded-lg border">
                        <div className="px-6 py-4 border-b"><h3 className="text-lg font-medium text-gray-900">Recent Onboarding Sessions</h3></div>
                        <div className="divide-y divide-gray-200">
                            {recentSessions.map((session) => (
                                <div key={session.id} className="px-6 py-4 flex items-center justify-between">
                                    <div>
                                        <p className="text-sm font-medium text-gray-900">{session.full_name}</p>
                                        <p className="text-sm text-gray-500">{session.email}</p>
                                    </div>
                                    <div className="text-right">
                                        <span className={`inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${session.is_complete ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800'}`}>{session.is_complete ? 'Completed' : 'In Progress'}</span>
                                        {!session.is_complete && <CopyResumeLinkButton session={session} />}
                                    </div>
                                </div>
                            ))}
                            {recentSessions.length === 0 && <div className="px-6 py-8 text-center text-gray-500">No onboarding sessions yet.</div>}
                        </div>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}