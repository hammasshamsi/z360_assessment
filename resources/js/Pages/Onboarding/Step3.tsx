import { Head, useForm } from '@inertiajs/react';
import OnboardingLayout from '@/Layouts/OnboardingLayout';

interface Step3Props {
    sessionData: {
        company_name?: string;
        subdomain?: string;
    };
}

export default function Step3({ sessionData }: Step3Props) {
    const { data, setData, post, processing, errors } = useForm({
        company_name: sessionData.company_name || '',
        subdomain: sessionData.subdomain || '',
    });

    function submit(e: React.FormEvent) {
        e.preventDefault();
        post(route('onboarding.step3'));
    }

    return (
        <OnboardingLayout>
            <Head title="Step 3: Company Details" />
            <form onSubmit={submit}>
                <h2 className="text-2xl font-bold text-center mb-6">Company Information</h2>
                <div>
                    <label htmlFor="company_name">Company Name</label>
                    <input id="company_name" type="text" value={data.company_name} onChange={e => setData('company_name', e.target.value)} className="mt-1 block w-full" required />
                    {errors.company_name && <div className="text-red-600 mt-1">{errors.company_name}</div>}
                </div>
                <div className="mt-4">
                    <label htmlFor="subdomain">Subdomain (lowercase only)</label>
                    <input id="subdomain" type="text" value={data.subdomain} onChange={e => setData('subdomain', e.target.value.toLowerCase())} className="mt-1 block w-full" required />
                    {errors.subdomain && <div className="text-red-600 mt-1">{errors.subdomain}</div>}
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