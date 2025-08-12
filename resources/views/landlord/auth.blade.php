<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Landlord Access - Authentication Required</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">
    <div class="max-w-md w-full bg-white rounded-lg shadow-md p-6">
        <div class="text-center mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Admin - Landlord Access</h1>
            <p class="text-gray-600 mt-2">Administrative Interface</p>
            <p class="text-sm text-gray-500 mt-1">Restricted to <b>Authorized personnel</b> only</p>
        </div>
        
        <form method="GET" action="{{ request()->url() }}">
            <div class="mb-4">
                <label for="admin_password" class="block text-sm font-medium text-gray-700 mb-2">
                    Admin Password
                </label>
                <input type="password" 
                       id="admin_password" 
                       name="admin_password" 
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                       placeholder="Enter admin password"
                       required>
            </div>
            
            <button type="submit" 
                    class="w-full bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                Proceed
            </button>
        </form>
        
        <div class="mt-4 p-3 bg-blue-50 border border-blue-200 rounded-md">
            <p class="text-xs text-blue-800">
                <strong>For Assessment:</strong> Use password <b>"admin123"</b> to access the dashboard.
            </p>
        </div>
        
        <div class="mt-4 text-center">
            <a href="{{ route('onboarding.step1') }}" class="text-sm text-gray-600 hover:text-blue-600">
                ← Back to Tenant Onboarding
            </a>
        </div>
    </div>
</body>
</html>