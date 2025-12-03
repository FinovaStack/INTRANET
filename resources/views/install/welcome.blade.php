<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <link rel="icon" href="{{ asset('images/demulla-tablogo.jpeg') }}" type="image/jpeg">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
</head>
<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-50 py-12">
        <div class="max-w-6xl mx-auto px-6">
            <div class="text-center mb-12">
                <h1 class="text-4xl font-bold text-gray-900 mb-4">Welcome to INTRANET Setup</h1>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                    Get started with your comprehensive intranet solution. This wizard will guide you through the installation process.
                </p>
            </div>

            <div class="bg-white rounded-lg shadow-lg p-8 mb-8">
                <div class="px-6 py-12">
                    <!-- Features Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
                        <div class="bg-white border border-gray-200 rounded-lg p-6 text-center shadow-sm">
                            <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mx-auto mb-3">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4"></path>
                                </svg>
                            </div>
                            <h3 class="text-base font-semibold text-gray-900 mb-2">Database Setup</h3>
                            <p class="text-sm text-gray-600">Configure database and run migrations.</p>
                        </div>

                        <div class="bg-white border border-gray-200 rounded-lg p-6 text-center shadow-sm">
                            <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center mx-auto mb-3">
                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                </svg>
                            </div>
                            <h3 class="text-base font-semibold text-gray-900 mb-2">Security First</h3>
                            <p class="text-sm text-gray-600">Built-in security and access controls.</p>
                        </div>

                        <div class="bg-white border border-gray-200 rounded-lg p-6 text-center shadow-sm">
                            <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center mx-auto mb-3">
                                <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                            </div>
                            <h3 class="text-base font-semibold text-gray-900 mb-2">User Management</h3>
                            <p class="text-sm text-gray-600">Complete user and organizational management.</p>
                        </div>
                    </div>

                    <!-- Installation Steps -->
                    <div class="bg-gray-50 rounded-lg p-6 mb-8">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">Quick Setup Process</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="flex items-start">
                                <div class="flex-shrink-0 w-6 h-6 bg-blue-600 text-white rounded-full flex items-center justify-center text-xs font-medium mt-0.5">
                                    1
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-gray-900">Check Requirements</p>
                                    <p class="text-xs text-gray-500">Verify server compatibility</p>
                                </div>
                            </div>
                            <div class="flex items-start">
                                <div class="flex-shrink-0 w-6 h-6 bg-gray-300 text-gray-600 rounded-full flex items-center justify-center text-xs font-medium mt-0.5">
                                    2
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-gray-900">Database Setup</p>
                                    <p class="text-xs text-gray-500">Configure connection</p>
                                </div>
                            </div>
                            <div class="flex items-start">
                                <div class="flex-shrink-0 w-6 h-6 bg-gray-300 text-gray-600 rounded-full flex items-center justify-center text-xs font-medium mt-0.5">
                                    3
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-gray-900">Run Migrations</p>
                                    <p class="text-xs text-gray-500">Create database tables</p>
                                </div>
                            </div>
                            <div class="flex items-start">
                                <div class="flex-shrink-0 w-6 h-6 bg-gray-300 text-gray-600 rounded-full flex items-center justify-center text-xs font-medium mt-0.5">
                                    4
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-gray-900">Create Admin</p>
                                    <p class="text-xs text-gray-500">Set up administrator account</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Start Button -->
                    <div class="text-center pb-12">
                        <a href="{{ route('install.requirements') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                            Start Installation
                        </a>
                        <p class="mt-4 text-sm text-gray-600">
                            This process will take approximately 5-10 minutes
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>