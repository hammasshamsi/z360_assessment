<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Landlord Dashboard - Tenant Management</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-50">
    <!-- NAV -->
    <nav class="bg-white shadow-sm border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <div class="flex-shrink-0 flex items-center">
                        <i class="fas fa-building text-2xl text-green-600 mr-3"></i>
                        <h1 class="text-xl font-bold text-gray-900">Landlord Dashboard</h1>
                        <span class="ml-3 px-2 py-1 bg-green-100 text-green-800 text-xs rounded-full font-medium">ADMIN</span>

                        <!-- DB context indicator -->
                        <div class="ml-4 flex items-center bg-blue-50 border border-blue-200 px-3 py-1 rounded-lg">
                            <i class="fas fa-database text-blue-600 mr-2"></i>
                            <div class="text-xs">
                                <div class="font-medium text-blue-800">Database Context</div>
                                <div class="text-blue-600">
                                    <p><strong>Active:</strong> {{ DB::connection()->getDatabaseName() }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="ml-2 flex items-center bg-purple-50 border border-purple-200 px-3 py-1 rounded-lg">
                        <i class="fas fa-layers text-purple-600 mr-2"></i>
                        <div class="text-xs">
                            <div class="font-medium text-purple-800">Spatie Context</div>
                            <div class="text-purple-600">
                                {{ \Spatie\Multitenancy\Models\Tenant::current() ? 'TENANT' : 'LANDLORD' }}
                            </div>
                        </div>
                    </div>

                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="{{ route('onboarding.step1') }}" 
                       class="text-gray-600 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium">
                        <i class="fas fa-plus mr-1"></i> New Tenant
                    </a>
                    <form method="POST" action="{{ route('landlord.logout') }}" class="inline">
                        @csrf
                        <input type="hidden" name="logout" value="1">
                        <button type="submit" 
                                class="text-red-600 hover:text-red-800 px-3 py-2 rounded-md text-sm font-medium">
                            <i class="fas fa-sign-out-alt mr-1"></i> Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <!-- success orr error messages -->
        @if(session('success'))
            <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg">
                <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
            </div>
        @endif
        
        @if(session('error'))
            <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
                <i class="fas fa-exclamation-triangle mr-2"></i>{{ session('error') }}
            </div>
        @endif

        <!-- stats -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6 mb-8">
            <div class="bg-white overflow-hidden shadow-sm rounded-lg border">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 bg-blue-500 rounded-lg flex items-center justify-center">
                                <i class="fas fa-building text-white"></i>
                            </div>
                        </div>
                        <div class="ml-4 flex-1">
                            <p class="text-sm font-medium text-gray-500">Total Tenants</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $stats['total_tenants'] }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm rounded-lg border">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 bg-green-500 rounded-lg flex items-center justify-center">
                                <i class="fas fa-check-circle text-white"></i>
                            </div>
                        </div>
                        <div class="ml-4 flex-1">
                            <p class="text-sm font-medium text-gray-500">Active</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $stats['active_tenants'] }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm rounded-lg border">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 bg-yellow-500 rounded-lg flex items-center justify-center">
                                <i class="fas fa-clock text-white"></i>
                            </div>
                        </div>
                        <div class="ml-4 flex-1">
                            <p class="text-sm font-medium text-gray-500">Pending</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $stats['pending_tenants'] + $stats['provisioning_tenants'] }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm rounded-lg border">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 bg-red-500 rounded-lg flex items-center justify-center">
                                <i class="fas fa-exclamation-triangle text-white"></i>
                            </div>
                        </div>
                        <div class="ml-4 flex-1">
                            <p class="text-sm font-medium text-gray-500">Failed</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $stats['failed_tenants'] }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm rounded-lg border">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 bg-purple-500 rounded-lg flex items-center justify-center">
                                <i class="fas fa-user-plus text-white"></i>
                            </div>
                        </div>
                        <div class="ml-4 flex-1">
                            <p class="text-sm font-medium text-gray-500">Sessions</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $stats['total_sessions'] }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- search and filter if user wants -->
        <div class="bg-white shadow-sm rounded-lg border mb-6">
            <div class="px-6 py-4">
                <form method="GET" class="flex gap-4 items-end">
                    <div class="flex-1">
                        <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Search Tenants</label>
                        <input type="text" 
                               name="search" 
                               id="search"
                               value="{{ request('search') }}"
                               placeholder="Search by name, domain, or database..."
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                        <select name="status" id="status" class="px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">All Statuses</option>
                            <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                            <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="provisioning" {{ request('status') === 'provisioning' ? 'selected' : '' }}>Provisioning</option>
                            <option value="failed" {{ request('status') === 'failed' ? 'selected' : '' }}>Failed</option>
                        </select>
                    </div>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                        <i class="fas fa-search mr-1"></i> Search
                    </button>
                    @if(request('search') || request('status'))
                        <a href="{{ route('landlord.dashboard') }}" class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600">
                            <i class="fas fa-times mr-1"></i> Clear
                        </a>
                    @endif
                </form>
            </div>
        </div>

        <!-- tenant list or main admin interfacee-->
        <div class="bg-white shadow-sm rounded-lg border">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-medium text-gray-900">
                    <i class="fas fa-list mr-2"></i>All Tenants 
                    <span class="text-sm font-normal text-gray-500">({{ $tenants->total() }} total)</span>
                </h2>
            </div>
            
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Tenant Info
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Database
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Created
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($tenants as $tenant)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">{{ $tenant->name }}</div>
                                        <div class="text-sm text-gray-500">{{ $tenant->domain }}</div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        @if($tenant->status === 'active') bg-green-100 text-green-800
                                        @elseif($tenant->status === 'pending') bg-yellow-100 text-yellow-800
                                        @elseif($tenant->status === 'provisioning') bg-blue-100 text-blue-800
                                        @else bg-red-100 text-red-800 @endif">
                                        @if($tenant->status === 'active')
                                            <i class="fas fa-check mr-1"></i>
                                        @elseif($tenant->status === 'pending')
                                            <i class="fas fa-clock mr-1"></i>
                                        @elseif($tenant->status === 'provisioning')
                                            <i class="fas fa-cog fa-spin mr-1"></i>
                                        @else
                                            <i class="fas fa-exclamation-triangle mr-1"></i>
                                        @endif
                                        {{ ucfirst($tenant->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $tenant->database }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $tenant->created_at->format('M d, Y H:i') }}
                                    <br>
                                    <span class="text-xs text-gray-400">{{ $tenant->created_at->diffForHumans() }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex space-x-2">
                                        <a href="{{ route('landlord.tenant.details', $tenant) }}" 
                                           class="text-blue-600 hover:text-blue-900">
                                            <i class="fas fa-eye"></i> View
                                        </a>
                                        
                                        @if($tenant->status === 'active')
                                            <a href="{{ route('tenant.login', $tenant->domain) }}" 
                                               class="text-green-600 hover:text-green-900" 
                                               target="_blank">
                                                <i class="fas fa-external-link-alt"></i> Access
                                            </a>
                                        @endif
                                        
                                        <!-- delete functionality -->
                                        <form method="POST" action="{{ route('landlord.tenant.delete', $tenant) }}" 
                                              class="inline"
                                              onsubmit="return confirm('Are you sure? This will permanently delete the tenant and all associated data.')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900">
                                                <i class="fas fa-trash"></i> Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center">
                                    <div class="text-gray-500">
                                        <i class="fas fa-building text-4xl mb-3"></i>
                                        <p class="text-lg font-medium">No tenants found</p>
                                        <p class="text-sm">Create your first tenant through the onboarding process.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- paginations if needed -->
            @if($tenants->hasPages())
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $tenants->links() }}
                </div>
            @endif
        </div>

        <!-- recent onboarding sessions -->
        <div class="mt-8 bg-white shadow-sm rounded-lg border">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">
                    <i class="fas fa-user-plus mr-2"></i>Recent Onboarding Sessions
                </h3>
            </div>
            <div class="divide-y divide-gray-200">
                @forelse($recentSessions as $session)
                    <div class="px-6 py-4">
                        <div class="flex items-center justify-between">
                            <div class="flex-1">
                                <p class="text-sm font-medium text-gray-900">{{ $session->full_name }}</p>
                                <p class="text-sm text-gray-500">{{ $session->email }}</p>
                                <p class="text-xs text-gray-400">{{ $session->created_at->diffForHumans() }}</p>
                            </div>
                            <div class="text-right">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    @if($session->is_complete) bg-green-100 text-green-800
                                    @else bg-yellow-100 text-yellow-800 @endif">
                                    @if($session->is_complete)
                                        <i class="fas fa-check mr-1"></i> Completed
                                    @else
                                        <i class="fas fa-clock mr-1"></i> In Progress
                                    @endif
                                </span>

                                <!-- copy button, OR we can use send reminder button to send reminder to user for incomplete sessions -->
                                @if(!$session->is_complete)
                                    <button type="button" 
                                        onclick="Copy(this)"
                                        data-session-token="{{ $session->token }}"
                                        data-resume-url="{{ URL::signedRoute('onboarding.resume', ['token' => $session->token]) }}"
                                        class="inline-flex items-center px-3 py-1.5 border border-blue-300 rounded-md text-xs font-medium text-blue-700 bg-blue-50 hover:bg-blue-100 hover:border-blue-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-1 transition-all duration-200">
                                        <i class="fas fa-copy mr-1"></i>
                                        Copy Resume Link
                                    </button>
                                @endif


                            </div>
                        </div>
                    </div>
                @empty
                    <div class="px-6 py-8 text-center text-gray-500">
                        <i class="fas fa-user-plus text-3xl mb-2"></i>
                        <p>No onboarding sessions yet</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

<script>
    function Copy(button) {
    const resumeUrl = button.getAttribute('data-resume-url');
    
    if (!resumeUrl) {
        console.error('Resume URL not found');
        return;
    }
    
    // Check if clipboard API is supported
    if (!navigator.clipboard) {
        // Fallback for older browsers
        const textarea = document.createElement('textarea');
        textarea.value = resumeUrl;
        textarea.style.position = 'fixed';
        textarea.style.opacity = '0';
        document.body.appendChild(textarea);
        textarea.select();
        
        try {
            document.execCommand('copy');
            updateButtonState(button, true);
        } catch (err) {
            console.error('Fallback: Failed to copy:', err);
            updateButtonState(button, false);
        }
        
        document.body.removeChild(textarea);
        return;
    }
    
    // Modern browsers with Clipboard API
    navigator.clipboard.writeText(resumeUrl)
        .then(() => updateButtonState(button, true))
        .catch(err => {
            console.error('Clipboard API: Failed to copy:', err);
            updateButtonState(button, false);
        });
}

function updateButtonState(button, success) {
    const originalText = button.innerHTML;
    
    if (success) {
        button.innerHTML = '<i class="fas fa-check mr-1"></i>Link Copied!';
        button.classList.remove('text-blue-700', 'bg-blue-50', 'border-blue-300');
        button.classList.add('text-green-700', 'bg-green-50', 'border-green-300');
    } else {
        button.innerHTML = '<i class="fas fa-exclamation-circle mr-1"></i>Failed to copy';
        button.classList.remove('text-blue-700', 'bg-blue-50', 'border-blue-300');
        button.classList.add('text-red-700', 'bg-red-50', 'border-red-300');
    }
    button.disabled = true;
    
    setTimeout(function() {
        button.innerHTML = originalText;
        button.classList.remove(
            'text-green-700', 'bg-green-50', 'border-green-300',
            'text-red-700', 'bg-red-50', 'border-red-300'
        );
        button.classList.add('text-blue-700', 'bg-blue-50', 'border-blue-300');
        button.disabled = false;
    }, 2000);
}
</script>   
</body>
</html>