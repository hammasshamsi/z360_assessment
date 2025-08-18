import { Link, Head } from '@inertiajs/react';

export default function Welcome() {
    return (
        <>
            <Head title="Welcome" />
            <div className="min-h-screen bg-gray-100 flex flex-col justify-center items-center">
                <div className="text-center p-8 bg-white shadow-md rounded-lg">
                    <h1 className="text-4xl font-bold mb-6">Welcome to Z360 App</h1>
                    <div className="space-y-4">
                        {/* onboarding */}
                        <Link
                            href={route('getstarted')}
                            className="block w-full px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg shadow-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150"
                        >
                            Start Onboarding
                        </Link>

                        <a
                            href={route('login')}
                            className="block w-full px-6 py-3 bg-gray-800 text-white font-semibold rounded-lg shadow-md hover:bg-gray-700"
                        >
                            Landlord / Admin Dashboard
                        </a>
                        
                    </div>
                </div>
            </div>
        </>
    );
}