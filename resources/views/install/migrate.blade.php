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
                <h1 class="text-4xl font-bold text-gray-900 mb-4">Run Database Migrations</h1>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                    Prepare your database by creating all necessary tables and relationships.
                </p>
            </div>

            <div class="bg-white rounded-lg shadow-lg p-8 mb-8">
                <div class="px-6 py-12">
                    <!-- Migration Info -->
                    <div class="mb-8">
                        <h3 class="text-lg font-semibold text-gray-900 mb-6 text-center">Database Tables to Create</h3>
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-8">
                            <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 shadow-sm">
                                <ul class="text-sm text-blue-800 space-y-2 text-center">
                                    <li>• users</li>
                                    <li>• departments</li>
                                    <li>• branches</li>
                                    <li>• positions</li>
                                    <li>• password_resets</li>
                                </ul>
                            </div>

                            <div class="bg-green-50 border border-green-200 rounded-lg p-6 shadow-sm">
                                <ul class="text-sm text-green-800 space-y-2 text-center">
                                    <li>• password_histories</li>
                                    <li>• security_questions</li>
                                    <li>• login_logs</li>
                                    <li>• mfa_tokens</li>
                                    <li>• audit_logs</li>
                                </ul>
                            </div>

                            <div class="bg-purple-50 border border-purple-200 rounded-lg p-6 shadow-sm">
                                <ul class="text-sm text-purple-800 space-y-2 text-center">
                                    <li>• announcements</li>
                                    <li>• documents</li>
                                    <li>• events</li>
                                    <li>• notifications</li>
                                    <li>• issues & tickets</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Migration Process -->
                    <div class="bg-gray-50 rounded-lg p-6 mb-8">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Migration Process</h3>
                        <div class="space-y-6">
                            <div class="flex flex-col sm:flex-row sm:items-center">
                                <div class="flex-shrink-0 w-10 h-10 bg-green-100 rounded-full flex items-center justify-center mb-3 sm:mb-0">
                                    <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                                <div class="sm:ml-4">
                                    <p class="text-base font-semibold text-gray-900">Database connection verified</p>
                                    <p class="text-sm text-gray-600">Connection to database established successfully.Please proceed to <b>Run migrations</b></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('install.migrate.run') }}">
                        @csrf

                        @if($errors->has('migration'))
                            <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        <svg class="w-5 h-5 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm text-red-800">{{ $errors->first('migration') }}</p>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Navigation -->
                        <div class="flex items-center justify-between pt-8 pb-12 border-t border-gray-200">
                            <a href="{{ route('install.database') }}" class="inline-flex items-center px-6 py-3 text-base font-medium text-gray-700 bg-gray-100 border border-transparent rounded-lg hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors duration-200 shadow-md">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                </svg>
                                Back
                            </a>

                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Run Migrations
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>