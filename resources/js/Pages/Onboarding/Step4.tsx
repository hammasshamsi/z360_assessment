import { Head, useForm } from '@inertiajs/react';
import OnboardingLayout from '@/Layouts/OnboardingLayout';

interface Step4Props {
    sessionData: {
        billing_name?: string;
        billing_address?: string;
        country?: string;
        phone?: string;
    };
    allowedCountries: string[]; //countries allowed from backend
}

export default function Step4({ sessionData, allowedCountries }: Step4Props) {
    const { data, setData, post, processing, errors } = useForm({
        billing_name: sessionData.billing_name || '',
        billing_address: sessionData.billing_address || '',
        country: sessionData.country || '',
        phone: sessionData.phone || '',
    });

    function submit(e: React.FormEvent) {
        e.preventDefault();
        post(route('onboarding.step4'));
    }

    return (
        <OnboardingLayout>
            <Head title="Step 4: Billing Details" />
            <form onSubmit={submit}>
                <h2 className="text-2xl font-bold text-center mb-6">Billing Information</h2>
                <div>
                    <label htmlFor="billing_name">Billing Name</label>
                    <input
                        id="billing_name"
                        type="text"
                        value={data.billing_name}
                        onChange={(e) => setData('billing_name', e.target.value)}
                        className="mt-1 block w-full"
                        required
                    />
                    {errors.billing_name && <div className="text-red-600 mt-1">{errors.billing_name}</div>}
                </div>
                <div className="mt-4">
                    <label htmlFor="billing_address">Billing Address</label>
                    <input
                        id="billing_address"
                        type="text"
                        value={data.billing_address}
                        onChange={(e) => setData('billing_address', e.target.value)}
                        className="mt-1 block w-full"
                        required
                    />
                    {errors.billing_address && <div className="text-red-600 mt-1">{errors.billing_address}</div>}
                </div>
                <div className="mt-4">
                    <label htmlFor="country">Country</label>
                    <input
                        id="country"
                        type="text"
                        value={data.country}
                        onChange={(e) => setData('country', e.target.value)}
                        className="mt-1 block w-full"
                        required
                    />
                    <p className="text-sm text-gray-700 mt-1">
                        Accepted countries: {allowedCountries.join(', ')}
                    </p>
                    {errors.country && <div className="text-red-600 mt-1">{errors.country}</div>}
                </div>
                <div className="mt-4">
                    <label htmlFor="phone">Phone Number</label>
                    <input
                        id="phone"
                        type="tel"
                        value={data.phone}
                        onChange={(e) => setData('phone', e.target.value)}
                        className="mt-1 block w-full"
                        required
                    />
                    {errors.phone && <div className="text-red-600 mt-1">{errors.phone}</div>}
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