@extends('layouts.onboarding')
@section('heading', 'Step 1: Account Information')

@section('content')
<div class="container mx-auto p-4">
    <form action="{{ route('onboarding.step1') }}" method="POST">
        @csrf
        <div class="mb-4">
            <label for="full_name" class="block text-sm font-medium text-gray-700">Full Name</label>
            <input type="text" id="full_name" value="{{ old('full_name', $session->full_name ?? '') }}" name="full_name" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            @error('full_name') <p class="error">{{ $message }}</p> @enderror
        </div>
        <div class="mb-4">
            <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
            <input type="email" id="email" value="{{ old('email', $session->email ?? '') }}" name="email" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            @error('email') <p class="error">{{ $message }}</p>@enderror
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