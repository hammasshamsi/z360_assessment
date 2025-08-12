<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Workspace Not Active - {{ $tenant->name }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center p-4">
    <div class="max-w-md w-full bg-white rounded-lg shadow-lg p-8">
        <div class="text-center mb-6">
            <div class="mx-auto flex items-center justify-center h-20 w-20 rounded-full 
                @if($tenant->status === 'pending') bg-yellow-100
                @elseif($tenant->status === 'provisioning') bg-blue-100
                @elseif($tenant->status === 'failed') bg-red-100
                @else bg-gray-100 @endif mb-4">
                
                @if($tenant->status === 'pending')
                    <i class="fas fa-clock text-3xl text-yellow-600"></i>
                @elseif($tenant->status === 'provisioning')
                    <i class="fas fa-cog fa-spin text-3xl text-blue-600"></i>
                @elseif($tenant->status === 'failed')
                    <i class="fas fa-exclamation-triangle text-3xl text-red-600"></i>
                @else
                    <i class="fas fa-lock text-3xl text-gray-600"></i>
                @endif
            </div>
            
            <h1 class="text-2xl font-bold text-gray-900 mb-2">
                @if($tenant->status === 'pending')
                    <i class="fas fa-tools mr-2 text-yellow-600"></i>Workspace Setup in Progress
                @elseif($tenant->status === 'provisioning')
                    <i class="fas fa-rocket mr-2 text-blue-600"></i>Almost Ready!
                @elseif($tenant->status === 'failed')
                    <i class="fas fa-wrench mr-2 text-red-600"></i>Setup Issue
                @else
                    <i class="fas fa-shield-alt mr-2 text-gray-600"></i>Access Restricted
                @endif
            </h1>
            
            <div class="inline-flex items-center px-3 py-1 rounded-full bg-gray-100 text-gray-700 text-sm">
                <i class="fas fa-building mr-2"></i>
                {{ $tenant->name }}
            </div>
        </div>

        <div class="
            @if($tenant->status === 'pending') bg-yellow-50 border-yellow-200 
            @elseif($tenant->status === 'provisioning') bg-blue-50 border-blue-200
            @elseif($tenant->status === 'failed') bg-red-50 border-red-200
            @else bg-gray-50 border-gray-200 @endif
            border rounded-lg p-4 mb-6">
            
            <div class="text-center">
                <h3 class="text-sm font-semibold 
                    @if($tenant->status === 'pending') text-yellow-800
                    @elseif($tenant->status === 'provisioning') text-blue-800
                    @elseif($tenant->status === 'failed') text-red-800
                    @else text-gray-800 @endif mb-2">
                    Current Status
                </h3>
                
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                    @if($tenant->status === 'pending') bg-yellow-100 text-yellow-800
                    @elseif($tenant->status === 'provisioning') bg-blue-100 text-blue-800
                    @elseif($tenant->status === 'failed') bg-red-100 text-red-800
                    @else bg-gray-100 text-gray-800 @endif">
                    
                    @if($tenant->status === 'pending')
                        <i class="fas fa-hourglass-half mr-2"></i> Pending Setup
                    @elseif($tenant->status === 'provisioning')
                        <i class="fas fa-cog fa-spin mr-2"></i> Being Provisioned
                    @elseif($tenant->status === 'failed')
                        <i class="fas fa-exclamation-triangle mr-2"></i> Setup Failed
                    @else
                        <i class="fas fa-question-circle mr-2"></i> {{ ucfirst($tenant->status) }}
                    @endif
                </span>
            </div>
        </div>

        <!-- satsus specific messages -->
        <div class="text-center mb-6">
            @if($tenant->status === 'pending')
                <h3 class="text-lg font-medium text-gray-900 mb-3">
                    <i class="fas fa-magic mr-2 text-yellow-600"></i>
                    Setting Up Your Workspace
                </h3>
                <p class="text-gray-600 mb-4 leading-relaxed">
                    We're preparing your personalized environment. This usually takes a few minutes.
                </p>
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <div class="text-sm text-blue-800 space-y-2">
                        <div class="flex items-center justify-center">
                            <i class="fas fa-database mr-2"></i>
                            Setting up database
                        </div>
                        <div class="flex items-center justify-center">
                            <i class="fas fa-shield-alt mr-2"></i>
                            Installing security features
                        </div>
                        <div class="flex items-center justify-center">
                            <i class="fas fa-users mr-2"></i>
                            Configuring user management
                        </div>
                    </div>
                </div>

            @elseif($tenant->status === 'provisioning')
                <h3 class="text-lg font-medium text-gray-900 mb-3">
                    <i class="fas fa-check-circle mr-2 text-blue-600"></i>
                    Final Steps Completing
                </h3>
                <p class="text-gray-600 mb-4 leading-relaxed">
                    Your workspace is almost ready! We're completing the final configuration steps.
                </p>
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <p class="text-sm text-blue-800">
                        <i class="fas fa-sync fa-spin mr-2"></i>
                        Finalizing setup and running security checks...
                    </p>
                </div>

            @elseif($tenant->status === 'failed')
                <h3 class="text-lg font-medium text-gray-900 mb-3">
                    <i class="fas fa-tools mr-2 text-red-600"></i>
                    Setup Needs Attention
                </h3>
                <p class="text-gray-600 mb-4 leading-relaxed">
                    We encountered an issue during workspace setup. Don't worry - this can be resolved easily.
                </p>
                <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                    <p class="text-sm text-red-800">
                        <i class="fas fa-headset mr-2"></i>
                        Our support team has been notified and will resolve this shortly.
                    </p>
                </div>

            @else
                <h3 class="text-lg font-medium text-gray-900 mb-3">
                    <i class="fas fa-lock mr-2 text-gray-600"></i>
                    Workspace Not Available
                </h3>
                <p class="text-gray-600 mb-4 leading-relaxed">
                    This workspace is not currently available for access.
                </p>
            @endif
        </div>

        <!-- actions -->
        <div class="space-y-3">
            @if($tenant->status === 'provisioning' || $tenant->status === 'pending')
                <button onclick="window.location.reload()" 
                        class="w-full flex items-center justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                    <i class="fas fa-sync mr-2"></i>
                    Check Status Again
                </button>
                
                <!-- auto referesh indicator -->
                <div class="text-center">
                    <p class="text-xs text-gray-500">
                        <i class="fas fa-clock mr-1"></i>
                        Auto-refreshing in 30 seconds
                    </p>
                </div>
            @endif
            
            @if($tenant->status === 'failed')
                <a href="{{ route('onboarding.step1') }}" 
                   class="w-full flex items-center justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-orange-600 hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 transition-colors">
                    <i class="fas fa-redo mr-2"></i>
                    Restart Setup Process
                </a>
            @endif
            
            <a href="{{ route('onboarding.step1') }}" 
               class="w-full flex items-center justify-center py-3 px-4 border border-gray-300 rounded-lg shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>
                Back to Onboarding
            </a>
        </div>

        <!-- support information -->
        <div class="mt-6 pt-4 border-t border-gray-200 text-center">
            <p class="text-xs text-gray-500 flex items-center justify-center">
                <i class="fas fa-info-circle mr-1"></i>
                Need help? Contact support or check your email for updates
            </p>
        </div>

        <!-- auto refresh for pending or provisioning states -->
        @if($tenant->status === 'provisioning' || $tenant->status === 'pending')
            <script>
                // auto refresh after every 30 seconds for pending or provisioning tenants
                setTimeout(function() {
                    window.location.reload();
                }, 30000);
            </script>
        @endif
    </div>
</body>