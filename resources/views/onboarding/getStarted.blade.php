@extends('layouts.onboarding')
@section('content')
    
    <!-- Header -->
    <div class="text-center mb-8">
        <h1 class="text-2xl font-semibold text-gray-900 mb-2">
            Welcome to {{ config('app.name') }}
        </h1>
        <p class="text-sm text-gray-600">
            Choose how you'd like to begin your journey
        </p>
    </div>

    <!-- buttons -->
    <div class="space-y-4">
        <a href="{{ route('onboarding.new') }}" class="block w-full">
            <button class="w-full bg-indigo-600 text-white px-4 py-3 rounded-md hover:bg-indigo-700 
                transition-colors duration-200 text-sm font-medium focus:outline-none focus:ring-2 
                focus:ring-offset-2 focus:ring-indigo-500">
                New Organization Sign-Up
            </button>
        </a>

        <a href="{{ route('onboarding.resume') }}" class="block w-full">
            <button class="w-full bg-white text-gray-700 px-4 py-3 rounded-md border border-gray-300 
                hover:bg-gray-50 transition-colors duration-200 text-sm font-medium focus:outline-none 
                focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                Resume Onboarding
            </button>
        </a>
    </div>
            
@endsection