@extends('layouts.onboarding')
@section('heading', 'Step 3: Company Details')

@section('content')
    <div class="container mx-auto p-4">
    <form action="{{ route('onboarding.step3') }}" method="POST">
        @csrf
        <div class="mb-4">
            <label for="company_name" class="block text-sm font-medium text-gray-700">Company Name</label>
            <input type="text" id="company_name" value="{{ old('company_name', $session->company_name ?? '') }}" name="company_name" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            @error('company_name') <p class="error">{{ $message }}</p> @enderror
        </div>
        <div class="mb-4">
            <label for="subdomain" class="block text-sm font-medium text-gray-700">Subdomain (Must be in lowercase)</label>
            <input type="text" id="subdomain" value="{{ old('subdomain', $session->subdomain ?? '') }}" name="subdomain" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            @error('subdomain') <p class="error">{{ $message }}</p>@enderror
        </div>
        <div class="flex justify-end">
                <button type="submit" 
                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Continue to Next Step
                </button>
        </div>
    </form>
</div>
@endsection

