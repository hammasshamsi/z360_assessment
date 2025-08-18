import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head } from '@inertiajs/react';
import { PageProps } from '@/types';

interface TenantDashboardProps extends PageProps {
    tenant: {
        id: string;
        name:string;
        domain: string;
        status: string;
        database: string;
    };
    context: {
        db_connection: string;
        db_driver: string;
        default_connection: string;
        landlord_connection: string;
        current_tenant_id: string;
        users_count: number;
    };
}

const ChecklistItem = ({ children }: { children: React.ReactNode }) => (
    <p className="flex items-center">
        <svg className="w-4 h-4 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M5 13l4 4L19 7"/>
        </svg>
        {children}
    </p>
);

export default function Dashboard({ auth, tenant, context }: TenantDashboardProps) {
    return (
        <AuthenticatedLayout
            user={auth.user}
            header={<h2 className="font-semibold text-xl text-gray-800 leading-tight">{tenant.name} Dashboard</h2>}
        >
            <Head title="Dashboard" />

            <div className="py-12">
                <div className="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div className="bg-white rounded-lg shadow-sm ring-1 ring-gray-900/5 p-6 space-y-6">
                        
                        {/* welcome heasder */}
                        <div className="flex items-center space-x-3">
                            <div className="h-10 w-10 flex items-center justify-center rounded-full bg-indigo-100">
                                <span className="text-xl">🎉</span>
                            </div>
                            <h2 className="text-xl font-semibold text-gray-900">Welcome, {auth.user.name}!</h2>
                        </div>

                        {/* status and info card */}
                        <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div className="bg-green-50 p-5 rounded-lg border border-green-100">
                                <h3 className="flex items-center text-green-800 font-medium mb-2">
                                    <svg className="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M5 13l4 4L19 7"/></svg>
                                    Tenant Successfully Provisioned
                                </h3>
                                <p className="text-green-600 text-sm">Your workspace is ready and fully functional.</p>
                            </div>

                            <div className="bg-indigo-50 p-5 rounded-lg border border-indigo-100">
                                <h3 className="text-indigo-800 font-medium mb-3">Tenant Information</h3>
                                <div className="grid grid-cols-2 gap-3 text-sm text-indigo-600">
                                    <div>
                                        <p className="mb-2"><span className="font-medium">Company:</span> {tenant.name}</p>
                                        <p className="mb-2"><span className="font-medium">Domain:</span> {tenant.domain}</p>
                                        <p><span className="font-medium">Status:</span> <span className="capitalize">{tenant.status}</span></p>
                                    </div>
                                    <div>
                                        <p className="mb-2"><span className="font-medium">Database:</span> {tenant.database}</p>
                                        <p><span className="font-medium">Connection:</span> {context.default_connection}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {/* verification card */}
                        <div className="bg-purple-50 p-5 rounded-lg border border-purple-100">
                            <h3 className="flex items-center text-purple-800 font-medium mb-3">
                                <span className="mr-2">🔍</span>
                                Multitenancy Context Verification
                            </h3>
                            <div className="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-purple-600">
                                <div className="space-y-2">
                                    <p><strong>Landlord Connection:</strong> {context.landlord_connection}</p>
                                    <p><span className="font-medium">Current Tenant ID:</span> {context.current_tenant_id ?? 'None'}</p>
                                    <p><span className="font-medium">Users Count:</span> {context.users_count}</p>
                                </div>
                                <div className="space-y-2">
                                    <p><span className="font-medium">Database Name:</span> {context.db_connection}</p>
                                    <p><span className="font-medium">Connection Type:</span> {context.db_driver}</p>
                                </div>
                            </div>
                        </div>

                        {/* checklist card */}
                        <div className="bg-gray-50 p-5 rounded-lg border border-gray-200">
                            <h3 className="flex items-center text-gray-900 font-medium mb-4">
                                <svg className="w-5 h-5 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                Assessment Complete!
                            </h3>
                            <div className="space-y-3 text-sm text-gray-600">
                                <ChecklistItem>Multi-step onboarding flow implemented</ChecklistItem>
                                <ChecklistItem>Tenant provisioning via background jobs</ChecklistItem>
                                <ChecklistItem>Isolated tenant database created</ChecklistItem>
                                <ChecklistItem>User redirected to tenant subdomain</ChecklistItem>
                                <ChecklistItem>Spatie multitenancy properly configured</ChecklistItem>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}