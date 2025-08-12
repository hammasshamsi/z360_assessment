@extends('layouts.onboarding')
@section('heading', 'Step 4: Complete')

@section('content')
    <div class="container mx-auto p-4">
    <form action="{{ route('onboarding.step4') }}" method="POST">
        @csrf
        <div class="mb-4">
            <label for="billing_name" class="block text-sm font-medium text-gray-700">Billing Name</label>
            <input type="text" id="billing_name" value="{{ old('billing_name', $session->billing_name ?? '') }}" name="billing_name" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            @error('billing_name') <p class="error">{{ $message }}</p> @enderror
        </div>
        <div class="mb-4">
            <label for="billing_address" class="block text-sm font-medium text-gray-700">Billing Address</label>
            <input type="text" id="billing_address" value="{{ old('billing_address', $session->billing_address ?? '') }}" name="billing_address" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            @error('billing_address') <p class="error">{{ $message }}</p>@enderror
        </div>
        <div class="mb-4">
            <label for="country" class="block text-sm font-medium text-gray-700">Country</label>
            @if(config('countries.allowed'))
                <p style="font-style:italic; color:green">Supported: {{ implode(', ', config('countries.allowed')) }}</p>
            @endif
            <input type="text" id="country" value="{{ old('country', $session->country ?? '') }}" name="country" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            @error('country') <p class="error">{{ $message }}</p>@enderror
        </div>
        <div class="mb-4">
            <label for="phone" class="block text-sm font-medium text-gray-700">Phone (E.164 format)</label>
            <input type="text" id="phone" value="{{ old('phone', $session->phone ?? '') }}" name="phone" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            @error('phone') <p class="error">{{ $message }}</p>@enderror
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