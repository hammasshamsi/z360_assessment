// import React, { useEffect } from 'react';
// import { Head, router } from '@inertiajs/react';

// interface Tenant {
//     id: string;
//     name: string;
//     domain: string;
//     status: 'active' | 'provisioning' | 'pending' | 'failed';
// }

// interface SuccessProps {
//     tenant: Tenant;
// }

// const Success: React.FC<SuccessProps> = ({ tenant }) => {

//     useEffect(() => {
//         // If the tenant becomes active, redirect to their login page.
//         if (tenant.status === 'active') {
//             window.location.href = `http://${tenant.domain}.myapp.test/login`;
//             return; // Stop the effect.
//         }
        
//         // If the status is still pending or provisioning, set up a timer to check again.
//         if (tenant.status === 'provisioning' || tenant.status === 'pending') {
//             const timer = setTimeout(() => {
//                 // Use Inertia's reload to re-fetch props from the server without a full page refresh.
//                 router.reload({ only: ['tenant'] });
//             }, 5000); // Check every 5 seconds.

//             // Clean up the timer if the component unmounts to prevent memory leaks.
//             return () => clearTimeout(timer);
//         }
//     }, [tenant]); // The effect depends on the tenant prop. It will re-run if the tenant object changes.

//     const getStatusClass = (status: Tenant['status']) => {
//         switch (status) {
//             case 'active':
//                 return 'bg-green-200 text-green-800';
//             case 'provisioning':
//                 return 'bg-yellow-200 text-yellow-800';
//             case 'pending':
//                 return 'bg-blue-200 text-blue-800';
//             default:
//                 return 'bg-red-200 text-red-800';
//         }
//     };

//     const handleRefresh = () => {
//         // The manual refresh button also uses Inertia's reload.
//         router.reload({ only: ['tenant'] });
//     };

//     return (
//         <>
//             <Head title="Success - Workspace Setup" />
//             <div className="min-h-screen bg-gray-100 flex flex-col justify-center items-center p-4">
//                 <div className="max-w-2xl w-full text-center">
//                     <h1 className="text-3xl font-bold text-gray-800 mb-4">🎉 Success! Your Workspace is Being Set Up</h1>

//                     <div className="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
//                         <strong>Congratulations!</strong> Your tenant "{tenant.name}" is being provisioned.
//                     </div>

//                     <div className="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
//                         <h3 className="text-lg font-semibold mb-4">Your Workspace Details:</h3>
                        
//                         <div className="mb-4 text-left">
//                             <p><strong>Company:</strong> {tenant.name}</p>
//                         </div>
//                         <div className="mb-4 text-left">
//                             <p><strong>Subdomain:</strong> {tenant.domain}</p>
//                         </div>
//                         <div className="mb-4 text-left">
//                             <p><strong>Status:</strong> 
//                                 <span className={`px-2 py-1 rounded text-sm ml-2 ${getStatusClass(tenant.status)}`}>
//                                     {tenant.status.charAt(0).toUpperCase() + tenant.status.slice(1)}
//                                 </span>
//                             </p>
//                         </div>

//                         {tenant.status === 'active' ? (
//                             <div className="mt-6">
//                                 <a href={`http://${tenant.domain}.myapp.test/login`} 
//                                    className="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded transition duration-300">
//                                     Access Your Workspace
//                                 </a>
//                             </div>
//                         ) : tenant.status === 'failed' ? (
//                             <div className="mt-6">
//                                 <p className="text-red-600 font-semibold">Workspace provisioning failed.</p>
//                                 <p className="text-gray-600">We're sorry, but something went wrong during the setup of your workspace. Please contact support for assistance.</p>
//                             </div>
//                         ) : (
//                             <div className="mt-6">
//                                 <p className="text-gray-600">Your workspace is being set up. This page will refresh automatically.</p>
//                                 <div className="mt-4 flex justify-center items-center">
//                                     <svg className="animate-spin -ml-1 mr-3 h-5 w-5 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
//                                         <circle className="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" strokeWidth="4"></circle>
//                                         <path className="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
//                                     </svg>
//                                     <span>Provisioning in progress...</span>
//                                 </div>
//                                 <button onClick={handleRefresh} className="mt-4 bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
//                                     Refresh Now
//                                 </button>
//                             </div>
//                         )}
//                     </div>

//                     <div className="mt-8 text-sm text-gray-600">
//                         <p>You'll receive an email confirmation once your workspace is ready.</p>
//                         <p>If you have any issues, please contact support.</p>
//                     </div>
//                 </div>
//             </div>
//         </>
//     );
// };

// export default Success;