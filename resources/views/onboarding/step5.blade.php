@extends('layouts.onboarding')
@section('heading', 'Step 5: Confirmation & Review')

@section('content')
    <div class="space-y-8">
        <!-- personal details section -->
        <div class="bg-gray-50 rounded-lg p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-sm font-medium text-gray-500">Personal Details</h3>
                <a href="{{ URL::signedRoute('onboarding.step1', ['token' => $session->token]) }}" 
                   class="text-xs text-indigo-600 hover:text-indigo-800 flex items-center">
                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    Edit
                </a>
            </div>
            <div class="grid grid-cols-2 gap-6">
                <div>
                    <label class="text-xs text-gray-500">Full Name</label>
                    <p class="text-sm font-medium text-gray-900 mt-1">{{$session->full_name}}</p>
                </div>
                <div>
                    <label class="text-xs text-gray-500">Email Address</label>
                    <p class="text-sm font-medium text-gray-900 mt-1">{{$session->email}}</p>
                </div>
            </div>
        </div>

        <!-- company details section -->
        <div class="bg-gray-50 rounded-lg p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-sm font-medium text-gray-500">Company Information</h3>
                <a href="{{ URL::signedRoute('onboarding.step3', ['token' => $session->token]) }}" 
                   class="text-xs text-indigo-600 hover:text-indigo-800 flex items-center">
                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    Edit
                </a>
            </div>
            <div class="grid grid-cols-2 gap-6">
                <div>
                    <label class="text-xs text-gray-500">Company Name</label>
                    <p class="text-sm font-medium text-gray-900 mt-1">{{$session->company_name}}</p>
                </div>
                <div>
                    <label class="text-xs text-gray-500">Subdomain</label>
                    <p class="text-sm font-medium text-gray-900 mt-1">{{$session->subdomain}}</p>
                </div>
            </div>
        </div>

        <!-- contact detailss section -->
        <div class="bg-gray-50 rounded-lg p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-sm font-medium text-gray-500">Billing Information</h3>
                <a href="{{ URL::signedRoute('onboarding.step4', ['token' => $session->token]) }}" 
                   class="text-xs text-indigo-600 hover:text-indigo-800 flex items-center">
                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    Edit
                </a>
            </div>
            <div class="grid grid-cols-2 gap-6">
                <div>
                    <label class="text-xs text-gray-500">Billing Address</label>
                    <p class="text-sm font-medium text-gray-900 mt-1">{{$session->billing_name}}</p>
                </div>
                <div>
                    <label class="text-xs text-gray-500">Country</label>
                    <p class="text-sm font-medium text-gray-900 mt-1">{{$session->country}}</p>
                </div>
                <div>
                    <label class="text-xs text-gray-500">Phone Number</label>
                    <p class="text-sm font-medium text-gray-900 mt-1">{{$session->phone}}</p>
                </div>
            </div>
        </div>
        <form method="POST" action="{{ route('onboarding.step5') }}">
            @csrf
            <div class="flex justify-end">
                <button type="submit" 
                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Submit and Complete Onboarding
                </button>
            </div>
        </form>
</div>
@endsection