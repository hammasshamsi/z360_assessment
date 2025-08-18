import { Head, Link } from '@inertiajs/react';
import React, { useEffect } from 'react';

interface InactiveProps {
    tenant: {
        name: string;
        status: string;
    };
}

// --- svg icons ---
const PendingIcon = () => <svg xmlns="http://www.w3.org/2000/svg" className="h-10 w-10 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>;
const ProvisioningIcon = () => <svg xmlns="http://www.w3.org/2000/svg" className="h-10 w-10 text-blue-600 animate-spin" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" /><path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>;
const FailedIcon = () => <svg xmlns="http://www.w3.org/2000/svg" className="h-10 w-10 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>;
const DefaultIcon = () => <svg xmlns="http://www.w3.org/2000/svg" className="h-10 w-10 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M12 15v2m-6.364-8.364l-.707.707M6.343 6.343l-.707-.707m12.728 0l.707-.707M17.657 17.657l.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" /></svg>;

const StatusDetails = ({ status }: { status: string }) => {
    switch (status) {
        case 'pending':
            return {
                icon: <PendingIcon />,
                title: "Workspace Setup in Progress",
                message: "We're preparing your personalized environment. This usually takes just a few minutes.",
                theme: 'yellow',
            };
        case 'provisioning':
            return {
                icon: <ProvisioningIcon />,
                title: "Almost Ready!",
                message: "Your workspace is almost ready! We're completing the final configuration steps.",
                theme: 'blue',
            };
        case 'failed':
            return {
                icon: <FailedIcon />,
                title: "Setup Needs Attention",
                message: "We encountered an issue during workspace setup. Our support team has been notified and will resolve this shortly.",
                theme: 'red',
            };
        default:
            return {
                icon: <DefaultIcon />,
                title: "Access Restricted",
                message: "This workspace is not currently available for access. Please contact support if you believe this is an error.",
                theme: 'gray',
            };
    }
};

export default function Inactive({ tenant }: InactiveProps) {
    const details = StatusDetails({ status: tenant.status });

    const themeClasses = {
        yellow: { bg: 'bg-yellow-100', text: 'text-yellow-800', border: 'border-yellow-400', iconBg: 'bg-yellow-100' },
        blue: { bg: 'bg-blue-100', text: 'text-blue-800', border: 'border-blue-400', iconBg: 'bg-blue-100' },
        red: { bg: 'bg-red-100', text: 'text-red-800', border: 'border-red-400', iconBg: 'bg-red-100' },
        gray: { bg: 'bg-gray-100', text: 'text-gray-800', border: 'border-gray-400', iconBg: 'bg-gray-100' },
    };

    const theme = themeClasses[details.theme as keyof typeof themeClasses];

    useEffect(() => {
        if (tenant.status === 'pending' || tenant.status === 'provisioning') {
            const timer = setTimeout(() => {
                window.location.reload();
            }, 30000); //refreshing after 30 sec
            return () => clearTimeout(timer);
        }
    }, [tenant.status]);

    return (
        <>
            <Head title={`Workspace Status: ${details.title}`} />
            <div className="min-h-screen bg-gray-50 flex flex-col justify-center items-center p-4">
                <div className="max-w-lg w-full bg-white p-8 rounded-xl shadow-lg border border-gray-200">
                    <div className="text-center mb-6">
                        <div className={`mx-auto flex items-center justify-center h-20 w-20 rounded-full ${theme.iconBg} mb-4`}>
                            {details.icon}
                        </div>
                        <h1 className="text-2xl font-bold text-gray-900 mb-2">{details.title}</h1>
                        <p className="text-gray-600 leading-relaxed">{details.message}</p>
                    </div>

                    <div className={`border-l-4 ${theme.border} ${theme.bg} p-4 rounded-r-lg mb-6`}>
                        <div className="flex">
                            <div className="py-1">
                                <svg className={`h-6 w-6 ${theme.text} mr-4`} xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path fillRule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clipRule="evenodd" /></svg>
                            </div>
                            <div>
                                <p className={`font-bold ${theme.text}`}>Current Status: {tenant.status.toUpperCase()}</p>
                                <p className={`text-sm ${theme.text}`}>Workspace: <b className="uppercase">{tenant.name}</b></p>
                            </div>
                        </div>
                    </div>

                    <div className="space-y-3">
                        {(tenant.status === 'pending' || tenant.status === 'provisioning') && (
                            <>
                                <button
                                    onClick={() => window.location.reload()}
                                    className="w-full flex items-center justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors"
                                >
                                    Check Status Again
                                </button>
                                <div className="text-center text-xs text-gray-500">
                                    Auto-refreshing in 30 seconds...
                                </div>
                            </>
                        )}

                        {tenant.status === 'failed' && (
                            <Link
                                href={route('onboarding.new')}
                                className="w-full flex items-center justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-orange-600 hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 transition-colors"
                            >
                                Restart Setup Process
                            </Link>
                        )}

                        <Link
                            href={route('getstarted')}
                            className="w-full flex items-center justify-center py-3 px-4 border border-gray-300 rounded-lg shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors"
                        >
                            Back to Main Page
                        </Link>
                    </div>

                    <div className="mt-6 pt-4 border-t border-gray-200 text-center">
                        <p className="text-xs text-gray-500">
                            Need help? Contact support or check your email for updates.
                        </p>
                    </div>
                </div>
            </div>
        </>
    );
}
