@extends('layouts.onboarding')

@section('title', 'Success - Tenant Created')
@section('heading', '🎉 Success! Your Workspace is Being Set Up')

@section('content')
    <div class="container mx-auto p-4 text-center">
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
            <strong>Congratulations!</strong> Your tenant "{{ $tenant->name }}" is being provisioned.
        </div>

        <div class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
            <h3 class="text-lg font-semibold mb-4">Your Workspace Details:</h3>
            
            <div class="mb-4">
                <p><strong>Company:</strong> {{ $tenant->name }}</p>
            </div>
            <div class="mb-4">
                <p><strong>Subdomain:</strong> {{ $tenant->domain }}</p>
            </div>
            <div class="mb-4">
                <p><strong>Status:</strong> 
                    <span class="px-2 py-1 rounded text-sm
                        @if($tenant->status === 'active') bg-green-200 text-green-800
                        @elseif($tenant->status === 'provisioning') bg-yellow-200 text-yellow-800
                        @elseif($tenant->status === 'pending') bg-blue-200 text-blue-800
                        @else bg-red-200 text-red-800
                        @endif">
                        {{ ucfirst($tenant->status) }}
                    </span>
                </p>
            </div>

            @if($tenant->status === 'active')
                <div class="mt-6">
                    <a href="https://{{ $tenant->domain }}.yourapp.com/login" 
                       class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Access Your Workspace
                    </a>
                </div>
            @else
                <div class="mt-6">
                    <p class="text-gray-600 mb-4">Your workspace is being set up. This usually takes a few minutes.</p>
                    <button onclick="location.reload()" 
                            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Refresh Status
                    </button>
                </div>
            @endif
        </div>

        <div class="mt-8 text-sm text-gray-600">
            <p>You'll receive an email confirmation once your workspace is ready.</p>
            <p>If you have any issues, please contact support.</p>
        </div>
    </div>

    @if($tenant->status !== 'active')
        <script>
            // auto refresh every 5 seconds if not active
            setTimeout(function() {
                location.reload();
            }, 5000);
        </script>
    @endif
@endsection
