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
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-50 py-12">
        <div class="max-w-4xl mx-auto px-6">
            <div class="text-center mb-12">
                <h1 class="text-4xl font-bold text-gray-900 mb-4">Create Administrator</h1>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                    Set up the main administrator account to complete the installation.
                </p>
            </div>

            <div class="bg-white rounded-lg shadow-lg p-8 mb-8">
                <div class="px-6 py-12">
                    <!-- Admin Info -->
                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-6 mb-8">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-semibold text-gray-900 mb-2">Administrator Account</h3>
                                <div class="text-gray-700 space-y-2">
                                    <p class="text-sm">This account will have full access to all system features and settings.</p>
                                    <ul class="text-sm space-y-1">
                                        <li class="flex items-start">
                                            <span class="text-green-500 mr-2 mt-1">•</span>
                                            <span>Complete access to user management</span>
                                        </li>
                                        <li class="flex items-start">
                                            <span class="text-green-500 mr-2 mt-1">•</span>
                                            <span>System configuration and settings</span>
                                        </li>
                                        <li class="flex items-start">
                                            <span class="text-green-500 mr-2 mt-1">•</span>
                                            <span>Department and organizational management</span>
                                        </li>
                                        <li class="flex items-start">
                                            <span class="text-green-500 mr-2 mt-1">•</span>
                                            <span>Security and access control</span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('install.admin.store') }}" class="space-y-6">
                        @csrf

                        <!-- Personal Information -->
                        <div class="bg-gray-50 rounded-lg p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                Personal Information
                            </h3>

                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                                <!-- Employee Code -->
                                <div class="mb-6">
                                    <label for="employee_code" class="block text-sm font-medium text-gray-900 mb-3">
                                        Employee Code *
                                    </label>
                                    <input id="employee_code" type="text" name="employee_code"
                                           value="{{ old('employee_code') }}"
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm bg-white text-gray-900 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors duration-200 @error('employee_code') border-red-300 @enderror"
                                           required autofocus autocomplete="off" placeholder="e.g., EMP001" />
                                    @error('employee_code')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- First Name -->
                                <div class="mb-6">
                                    <label for="first_name" class="block text-sm font-medium text-gray-900 mb-3">
                                        First Name *
                                    </label>
                                    <input id="first_name" type="text" name="first_name"
                                           value="{{ old('first_name') }}"
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm bg-white text-gray-900 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors duration-200 @error('first_name') border-red-300 @enderror"
                                           required autocomplete="given-name" placeholder="Enter first name" />
                                    @error('first_name')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Last Name -->
                                <div class="mb-6">
                                    <label for="last_name" class="block text-sm font-medium text-gray-900 mb-3">
                                        Last Name *
                                    </label>
                                    <input id="last_name" type="text" name="last_name"
                                           value="{{ old('last_name') }}"
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm bg-white text-gray-900 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors duration-200 @error('last_name') border-red-300 @enderror"
                                           required autocomplete="family-name" placeholder="Enter last name" />
                                    @error('last_name')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Email -->
                                <div class="mb-6">
                                    <label for="email" class="block text-sm font-medium text-gray-900 mb-3">
                                        Email Address *
                                    </label>
                                    <input id="email" type="email" name="email"
                                           value="{{ old('email') }}"
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm bg-white text-gray-900 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors duration-200 @error('email') border-red-300 @enderror"
                                           required autocomplete="username" placeholder="admin@example.com" />
                                    @error('email')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Security Information -->
                        <div class="bg-gray-50 rounded-lg p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                </svg>
                                Security Information
                            </h3>

                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                                <!-- Password -->
                                <div class="mb-6">
                                    <label for="password" class="block text-sm font-medium text-gray-900 mb-3">
                                        Password *
                                    </label>
                                    <input id="password" type="password" name="password"
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm bg-white text-gray-900 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors duration-200 @error('password') border-red-300 @enderror"
                                           required autocomplete="new-password" placeholder="Enter a secure password" />
                                    @error('password')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                    <p class="mt-2 text-sm text-gray-600">Minimum 8 characters</p>
                                </div>

                                <!-- Confirm Password -->
                                <div class="mb-6">
                                    <label for="password_confirmation" class="block text-sm font-medium text-gray-900 mb-3">
                                        Confirm Password *
                                    </label>
                                    <input id="password_confirmation" type="password" name="password_confirmation"
                                           class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm bg-white text-gray-900 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors duration-200 @error('password_confirmation') border-red-300 @enderror"
                                           required autocomplete="new-password" placeholder="Re-enter the password" />
                                    @error('password_confirmation')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        @if($errors->has('admin'))
                            <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        <svg class="w-5 h-5 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm text-red-800">{{ $errors->first('admin') }}</p>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Navigation -->
                        <div class="flex items-center justify-between pt-8 pb-12 border-t border-gray-200">
                            <a href="{{ route('install.migrate') }}" class="inline-flex items-center px-6 py-3 text-base font-medium text-gray-700 bg-gray-100 border border-transparent rounded-lg hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors duration-200 shadow-md">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                </svg>
                                Back
                            </a>

                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Create Admin
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>