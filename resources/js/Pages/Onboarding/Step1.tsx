import { Head, useForm } from '@inertiajs/react';
import OnboardingLayout from '@/Layouts/OnboardingLayout';

interface Step1Props {
    sessionData: {
        full_name?: string;
        email?: string;
    };
}

export default function Step1({ sessionData }: Step1Props) {
    // intilaize for with existing data if available
    const { data, setData, post, processing, errors } = useForm({
        full_name: sessionData.full_name || '',
        email: sessionData.email || '',
    });

    function submit(e: React.FormEvent) {
        e.preventDefault();
        post(route('onboarding.step1'));
    }

    return (
        <OnboardingLayout>
            <Head title="Step 1: Personal Details" />
            <form onSubmit={submit}>
                <h2 className="text-2xl font-bold text-center mb-6">Your Information</h2>
                <div>
                    <label htmlFor="full_name">Full Name</label>
                    <input
                        id="full_name"
                        type="text"
                        value={data.full_name}
                        onChange={(e) => setData('full_name', e.target.value)}
                        className="mt-1 block w-full"
                        required
                    />
                    {/* display validation errors from backend */}
                    {errors.full_name && <div className="text-red-600 mt-1">{errors.full_name}</div>}
                </div>
                <div className="mt-4">
                    <label htmlFor="email">Email Address</label>
                    <input
                        id="email"
                        type="email"
                        value={data.email}
                        onChange={(e) => setData('email', e.target.value)}
                        className="mt-1 block w-full"
                        required
                    />
                    {errors.email && <div className="text-red-600 mt-1">{errors.email}</div>}
                </div>

                <div className="flex items-center justify-end mt-4">
                    <button
                        type="submit"
                        className="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 disabled:opacity-25"
                        disabled={processing}
                    >
                        Next Step
                    </button>
                </div>
            </form>
        </OnboardingLayout>
    );
}