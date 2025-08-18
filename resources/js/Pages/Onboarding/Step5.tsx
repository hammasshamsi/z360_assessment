import { Head, Link, useForm } from '@inertiajs/react';
import OnboardingLayout from '@/Layouts/OnboardingLayout';
import axios from 'axios';
import { useState } from 'react';

interface Step5Props {
    summary: any;
    editUrls: {
        step1: string;
        step3: string;
        step4: string;
    };
}

const EditLink = ({ href }: { href: string }) => (
    <Link href={href} className="text-xs text-indigo-600 hover:text-indigo-800">Edit</Link>
);

const SummaryRow = ({ label, value }: { label: string, value: string }) => (
    <div>
        <label className="text-xs text-gray-500">{label}</label>
        <p className="text-sm font-medium text-gray-900 mt-1">{value}</p>
    </div>
);

export default function Step5({ summary, editUrls }: Step5Props) {
    const [processing, setProcessing] = useState(false);
    const [showSuccessModal, setShowSuccessModal] = useState(false);
    const [redirectUrl, setRedirectUrl] = useState('');
    async function submit(e: React.FormEvent) {
        e.preventDefault();
        setProcessing(true);

        try {
            //axios for json response
            const response = await axios.post(route('onboarding.step5.store'));

            // onsucess, get subdomain
            const subdomain = response.data.subdomain;

            if (subdomain) {
                setRedirectUrl(`http://${subdomain}.myapp.test/login`);
                setShowSuccessModal(true);
            } else {
                throw new Error("Response was successful but did not include a subdomain.");
            }

        } catch (error) {
            console.error("Failed to finalize onboarding:", error);
            alert("an error occurred while setting up your workspace. please try again.");
            setProcessing(false);
        }
    }
    const handleRedirect = () => {
        if (redirectUrl) {
            window.location.href = redirectUrl;
        }
    };

    return (
        <OnboardingLayout>
            <Head title="Step 5: Confirmation & Review" />
            {/*success moodal*/}
            {showSuccessModal && (
                <div className="fixed inset-0 bg-gray-600 bg-opacity-75 transition-opacity z-50 flex items-center justify-center" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                    <div className="bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:max-w-sm sm:w-full sm:p-6">
                        <div>
                            <div className="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-green-100">
                                <svg className="h-6 w-6 text-green-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M5 13l4 4L19 7" />
                                </svg>
                            </div>
                            <div className="mt-3 text-center sm:mt-5">
                                <h3 className="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                    Provisioning Started!
                                </h3>
                                <div className="mt-2">
                                    <p className="text-sm text-gray-500">
                                        Your workspace is being created. You will now be redirected to your login page to monitor its status.
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div className="mt-5 sm:mt-6">
                            <button
                                type="button"
                                className="inline-flex justify-center w-full rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:text-sm"
                                onClick={handleRedirect}
                            >
                                Go to Login Page
                            </button>
                        </div>
                    </div>
                </div>
            )}
            
            <form onSubmit={submit}>
                <h2 className="text-2xl font-bold text-center mb-6">Review Your Information</h2>
                
                <div className="space-y-6">
                    <div className="bg-gray-50 rounded-lg p-4">
                        <div className="flex justify-between items-center mb-2">
                            <h3 className="font-semibold">Personal Details</h3>
                            <EditLink href={editUrls.step1} />
                        </div>
                        <SummaryRow label="Full Name" value={summary.full_name} />
                        <SummaryRow label="Email Address" value={summary.email} />
                    </div>
                    <div className="bg-gray-50 rounded-lg p-4">
                        <div className="flex justify-between items-center mb-2">
                            <h3 className="font-semibold">Company Information</h3>
                            <EditLink href={editUrls.step3} />
                        </div>
                        <SummaryRow label="Company Name" value={summary.company_name} />
                        <SummaryRow label="Subdomain" value={summary.subdomain} />
                    </div>
                    <div className="bg-gray-50 rounded-lg p-4">
                        <div className="flex justify-between items-center mb-2">
                            <h3 className="font-semibold">Billing Information</h3>
                            <EditLink href={editUrls.step4} />
                        </div>
                        <SummaryRow label="Billing Name" value={summary.billing_name} />
                        <SummaryRow label="Country" value={summary.country} />
                        <SummaryRow label="Phone" value={summary.phone} />
                    </div>
                </div>

                <div className="mt-6">
                    <button type="submit" className="w-full text-center px-6 py-3 bg-green-600 text-white font-semibold rounded-lg" disabled={processing}>
                        Provision Workspace
                    </button>
                </div>
            </form>
        </OnboardingLayout>
    );
}