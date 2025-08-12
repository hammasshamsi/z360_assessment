<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tenant Details - {{ $tenant->name }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-50">
    <nav class="bg-white shadow-sm border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <a href="{{ route('landlord.dashboard') }}" class="text-blue-600 hover:text-blue-800 mr-4">
                        <i class="fas fa-arrow-left"></i> Back to Dashboard
                    </a>
                    <h1 class="text-xl font-bold text-gray-900">Tenant Details</h1>
                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <div class="bg-white shadow-sm rounded-lg border mb-6">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-medium text-gray-900">{{ $tenant->name }}</h2>
                <p class="text-sm text-gray-500">{{ $tenant->domain }}</p>
            </div>
            <div class="px-6 py-4">
                <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Status</dt>
                        <dd class="mt-1">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                @if($tenant->status === 'active') bg-green-100 text-green-800
                                @elseif($tenant->status === 'pending') bg-yellow-100 text-yellow-800
                                @elseif($tenant->status === 'provisioning') bg-blue-100 text-blue-800
                                @else bg-red-100 text-red-800 @endif">
                                {{ ucfirst($tenant->status) }}
                            </span>
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Database</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $tenant->database }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Created</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $tenant->created_at->format('M d, Y H:i:s') }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Last Updated</dt>
                        <dd class="mt-1 text-sm text-gray-900">{{ $tenant->updated_at->format('M d, Y H:i:s') }}</dd>
                    </div>
                </dl>
            </div>
        </div>

        <!-- oboarding session details -->
        @if($session)
            <div class="bg-white shadow-sm rounded-lg border mb-6">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Onboarding Session</h3>
                </div>
                <div class="px-6 py-4">
                    <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Full Name</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $session->full_name }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Email</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $session->email }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Company Name</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $session->company_name }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Sub-domain</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $session->subdomain }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Completion Status</dt>
                            <dd class="mt-1">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    @if($session->is_complete) bg-green-100 text-green-800
                                    @else bg-yellow-100 text-yellow-800 @endif">
                                    @if($session->is_complete)
                                        <i class="fas fa-check mr-1"></i> Completed
                                    @else
                                        <i class="fas fa-clock mr-1"></i> In Progress
                                    @endif
                                </span>
                            </dd>
                        </div>
                        @if($session->completed_at)
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Completed At</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $session->completed_at->format('M d, Y H:i:s') }}</dd>
                            </div>
                        @endif
                    </dl>
                </div>
            </div>
        @endif

        <!-- datrabase stats -->
        @if($tenantStats && $tenant->status === 'active')
            <div class="bg-white shadow-sm rounded-lg border">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Database Statistics</h3>
                </div>
                <div class="px-6 py-4">
                    @if(isset($tenantStats['error']))
                        <p class="text-red-600">{{ $tenantStats['error'] }}</p>
                    @else
                        <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-3">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Users Count</dt>
                                <dd class="mt-1 text-2xl font-bold text-blue-600">{{ $tenantStats['users_count'] }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Database Size</dt>
                                <dd class="mt-1 text-2xl font-bold text-green-600">{{ $tenantStats['database_size'] }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Tables Count</dt>
                                <dd class="mt-1 text-2xl font-bold text-purple-600">{{ $tenantStats['tables_count'] }}</dd>
                            </div>
                        </dl>
                    @endif
                </div>
            </div>
        @endif
    </div>
</body>
</html>