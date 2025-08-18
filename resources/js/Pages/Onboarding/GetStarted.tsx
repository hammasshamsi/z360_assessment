import { Head, Link } from '@inertiajs/react';
import OnboardingLayout from '@/Layouts/OnboardingLayout'; 

export default function GetStarted() {
    return (
        <OnboardingLayout>
            <Head title="Start Onboarding" />

            <div className="text-center">
                <h2 className="text-2xl font-bold mb-6">Let's Get Started</h2>
                <p className="text-gray-600 mb-8">
                    Are you starting a new organization setup or resuming a previous one?
                </p>

                <div className="space-y-4">
                    <Link
                        href={route('onboarding.new')}
                        className="block w-full px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg shadow-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150"
                    >
                        Sign Up New Organization
                    </Link>
                    <Link
                        href={route('onboarding.resume')}
                        className="block w-full px-6 py-3 bg-gray-600 text-white font-semibold rounded-lg shadow-md hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150"
                    >
                        Resume Onboarding
                    </Link>
                </div>
            </div>
        </OnboardingLayout>
    );
}