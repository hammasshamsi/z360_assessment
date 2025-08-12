<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - {{ $tenant->name }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-gray-50">
    <div class="min-h-screen flex flex-col">
        <!-- navigations -->
        <nav class="bg-white border-b border-gray-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center">
                        <h1 class="text-xl font-semibold text-gray-900">{{ $tenant->name }}</h1>
                    </div>
                    <div class="flex items-center space-x-4">
                        <span class="px-3 py-1 text-sm text-indigo-600 bg-indigo-50 rounded-full">
                            {{ $tenant->domain }}.myapp.test
                        </span>
                    </div>
                </div>
            </div>
        </nav>

        <main class="flex-1 py-8">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="bg-white rounded-lg shadow-sm ring-1 ring-gray-900/5">
                    <div class="p-6">
                        <div class="flex items-center space-x-3 mb-6">
                            <div class="h-10 w-10 flex items-center justify-center rounded-full bg-indigo-100">
                                <span class="text-xl">🎉</span>
                            </div>
                            <h2 class="text-xl font-semibold text-gray-900">Welcome to Your Workspace!</h2>
                        </div>
                        
                        <!-- status -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div class="bg-green-50 p-5 rounded-lg border border-green-100">
                                <h3 class="flex items-center text-green-800 font-medium mb-2">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    Tenant Successfully Provisioned
                                </h3>
                                <p class="text-green-600 text-sm">Your workspace is ready and fully functional.</p>
                            </div>

                            <!-- tenant infor card -->
                            <div class="bg-indigo-50 p-5 rounded-lg border border-indigo-100">
                                <h3 class="text-indigo-800 font-medium mb-3">Tenant Information</h3>
                                <div class="grid grid-cols-2 gap-3 text-sm text-indigo-600">
                                    <div>
                                        <p class="mb-2"><span class="font-medium">Company:</span> {{ $tenant->name }}</p>
                                        <p class="mb-2"><span class="font-medium">Domain:</span> {{ $tenant->domain }}</p>
                                        <p><span class="font-medium">Status:</span> {{ ucfirst($tenant->status) }}</p>
                                    </div>
                                    <div>
                                        <p class="mb-2"><span class="font-medium">Database:</span> {{ $tenant->database }}</p>
                                        <p><span class="font-medium">Connection:</span> {{ config('database.default') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- verification card -->
                        <div class="bg-purple-50 p-5 rounded-lg border border-purple-100 mb-6">
                            <h3 class="flex items-center text-purple-800 font-medium mb-3">
                                <span class="mr-2">🔍</span>
                                Multitenancy Context Verification
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-purple-600">
                                <div class="space-y-2">
                                    <p><strong>Landlord Connection:</strong> {{ config('multitenancy.landlord_database_connection_name') }}</p>
                                    <p><span class="font-medium">Current Tenant ID:</span> {{ app('currentTenant')?->id ?? 'None' }}</p>
                                    <p><span class="font-medium">Users Count:</span> {{ DB::connection('tenant')->table('users')->count() }}</p>
                                </div>
                                <div class="space-y-2">
                                    <p><span class="font-medium">Database Name:</span> {{ DB::connection('tenant')->getDatabaseName() }}</p>
                                    <p><span class="font-medium">Connection Type:</span> {{ config('database.connections.tenant.driver') }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- checklist -->
                        <div class="bg-gray-50 p-5 rounded-lg border border-gray-200">
                            <h3 class="flex items-center text-gray-900 font-medium mb-4">
                                <svg class="w-5 h-5 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Assessment Complete!
                            </h3>
                            <div class="space-y-3 text-sm text-gray-600">
                                <p class="flex items-center">
                                    <svg class="w-4 h-4 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    Multi-step onboarding flow implemented
                                </p>
                                <p class="flex items-center">
                                    <svg class="w-4 h-4 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    Tenant provisioning via background jobs
                                </p>
                                <p class="flex items-center">
                                    <svg class="w-4 h-4 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    Isolated tenant database created
                                </p>
                                <p class="flex items-center">
                                    <svg class="w-4 h-4 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    User redirected to tenant subdomain
                                </p>
                                <p class="flex items-center">
                                    <svg class="w-4 h-4 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    Spatie multitenancy properly configured
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>
</html>