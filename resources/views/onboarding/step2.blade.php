@extends('layouts.onboarding')
@section('heading', 'Step 2: Create Password')

@section('content')
    <div class="container mx-auto p-4">
    <form action="{{ route('onboarding.step2') }}" method="POST">
        @csrf
        <div class="mb-4">
            <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
            <input type="password" id="password" name="password" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            @error('password') <p class="error">{{ $message }}</p> @enderror
        </div>
        <div class="mb-4">
            <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirm Password</label>
            <input type="password" id="password_confirmation" name="password_confirmation" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
            @error('password_confirmation') <p class="error">{{ $message }}</p>@enderror
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

