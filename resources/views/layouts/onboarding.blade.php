<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name') }} - Onboarding</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        input[type="text"],
        input[type="email"],
        input[type="password"]{
            height: 35px;
            padding-left: 10px;
        }
        .error{
            color: red;
            font-size: 0.875rem;
            margin-top: 0.25rem;
        }
    </style>
</head>
<body class="bg-gradient-to-br from-gray-50 to-gray-100">
    <div class="min-h-screen flex flex-col">
        <!-- NAV with stepss -->
        <nav class="bg-white border-b border-gray-100 shadow-sm fixed w-full top-0 z-10">
            <div class="max-w-4xl mx-auto px-4">
                <div class="flex justify-between items-center h-16">
                    <div class="text-xl font-semibold text-gray-800">
                        {{ config('app.name') }}
                    </div>
                    @if(!request()->routeIs('getstarted'))
                    <div class="flex items-center space-x-4">
                        @php
                            $currentStep = intval(substr(request()->path(), -1));
                        @endphp
                        @foreach(range(1,5) as $step)
                            <div class="flex items-center">
                                <div class="h-8 w-8 rounded-full flex items-center justify-center transition-all duration-200
                                    {{ $step === $currentStep 
                                        ? 'bg-indigo-600 text-white ring-2 ring-indigo-100 scale-110' 
                                        : ($step < $currentStep 
                                            ? 'bg-green-500 text-white'
                                            : 'bg-gray-100 text-gray-400') }}">
                                    @if($step < $currentStep)
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                        </svg>
                                    @else
                                        {{ $step }}
                                    @endif
                                </div>
                                @if($step < 5)
                                    <div class="w-6 h-0.5 {{ $step < $currentStep ? 'bg-green-500' : 'bg-gray-200' }} ml-2"></div>
                                @endif
                            </div>
                        @endforeach   
                    </div>
                    @endif
                </div>
            </div>
        </nav>

        <main class="flex-grow pt-28">
            <div class="max-w-xl mx-auto px-4 pb-12">
                <div class="bg-white shadow-sm ring-1 ring-gray-100 rounded-lg">
                    <div class="px-6 py-8">
                        @hasSection('heading')
                            <div class="mb-6">
                                <h1 class="text-2xl font-semibold text-gray-900">@yield('heading')</h1>
                                <p class="mt-2 text-sm text-gray-600">Please fill in the information below to continue.</p>
                            </div>
                        @endif
                        @yield('content')
                    </div>
                </div>
            </div>
        </main>

        <!-- foter -->
        <footer class="bg-white border-t border-gray-100">
            <div class="max-w-4xl mx-auto px-4 py-6">
                <div class="text-center">
                    <p class="text-sm text-gray-600">
                        &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
                    </p>
                </div>
            </div>
        </footer>
    </div>
</body>
</html>