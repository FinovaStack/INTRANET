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
        <div class="max-w-4xl mx-auto px-6">
            <div class="text-center mb-12">
                <h1 class="text-4xl font-bold text-gray-900 mb-4">Database Configuration</h1>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                    Set up your database connection details to proceed with the installation.
                </p>
            </div>

            <div class="bg-white rounded-lg shadow-lg p-8 mb-8">
                <div class="px-6 py-12">
                    <!-- Info Box -->
                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-6 mb-8">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <svg class="w-6 h-6 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-semibold text-gray-900 mb-3">Database Setup Instructions</h3>
                                <div class="text-gray-700 space-y-3">
                                    <div>
                                        <h4 class="font-medium text-gray-900 mb-2">Before you begin:</h4>
                                        <ul class="text-sm space-y-2 ml-4">
                                            <li class="flex items-start">
                                                <span class="text-blue-500 mr-2 mt-1">•</span>
                                                <span>Create an empty database in your MySQL server</span>
                                            </li>
                                            <li class="flex items-start">
                                                <span class="text-blue-500 mr-2 mt-1">•</span>
                                                <span>Note down the database name, host, port, username, and password</span>
                                            </li>
                                            <li class="flex items-start">
                                                <span class="text-blue-500 mr-2 mt-1">•</span>
                                                <span>Ensure the database user has full privileges on the database</span>
                                            </li>
                                        </ul>
                                    </div>
                                    <div>
                                        <h4 class="font-medium text-gray-900 mb-2">Example using phpMyAdmin or MySQL command line:</h4>
                                        <code class="block bg-gray-100 p-3 rounded text-sm font-mono border">
                                            CREATE DATABASE Intranet CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
                                        </code>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('install.database.store') }}" class="space-y-6">
                        @csrf

                        <!-- Connection Details -->
                        <div class="bg-gray-50 rounded-lg p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path>
                                </svg>
                                Database Connection
                            </h3>

                            <div class="space-y-6">
                                <!-- Database Host and Port -->
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <div class="md:col-span-2">
                                        <label for="db_host" class="block text-sm font-medium text-gray-900 mb-2">
                                            Database Host
                                        </label>
                                        <input id="db_host" type="text" name="db_host"
                                               value="{{ old('db_host', '127.0.0.1') }}"
                                               placeholder="e.g., 127.0.0.1 or localhost"
                                               class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm bg-white text-gray-900 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 @error('db_host') border-red-300 @enderror"
                                               required autofocus autocomplete="off" />
                                        @error('db_host')
                                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="db_port" class="block text-sm font-medium text-gray-900 mb-2">
                                            Database Port
                                        </label>
                                        <input id="db_port" type="number" name="db_port"
                                               value="{{ old('db_port', '3306') }}"
                                               placeholder="e.g., 3306"
                                               class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm bg-white text-gray-900 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 @error('db_port') border-red-300 @enderror"
                                               required autocomplete="off" min="1" max="65535" />
                                        @error('db_port')
                                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Database Name -->
                                <div>
                                    <label for="db_database" class="block text-sm font-medium text-gray-900 mb-2">
                                        Database Name
                                    </label>
                                    <input id="db_database" type="text" name="db_database"
                                           value="{{ old('db_database', 'laravel') }}"
                                           placeholder="e.g., Intranet"
                                           class="max-w-md px-4 py-3 border border-gray-300 rounded-lg shadow-sm bg-white text-gray-900 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 @error('db_database') border-red-300 @enderror"
                                           required autocomplete="off" />
                                    @error('db_database')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                    <p class="mt-2 text-sm text-gray-600">⚠️ Database must be created before installation</p>
                                </div>

                                <!-- Database Username and Password -->
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label for="db_username" class="block text-sm font-medium text-gray-900 mb-2">
                                            Database Username
                                        </label>
                                        <input id="db_username" type="text" name="db_username"
                                               value="{{ old('db_username', 'root') }}"
                                               placeholder="e.g., root or db_user"
                                               class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm bg-white text-gray-900 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 @error('db_username') border-red-300 @enderror"
                                               required autocomplete="off" />
                                        @error('db_username')
                                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="db_password" class="block text-sm font-medium text-gray-900 mb-2">
                                            Database Password
                                        </label>
                                        <input id="db_password" type="password" name="db_password"
                                               value="{{ old('db_password') }}"
                                               placeholder="Enter database password (optional)"
                                               class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm bg-white text-gray-900 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200 @error('db_password') border-red-300 @enderror"
                                               autocomplete="off" />
                                        @error('db_password')
                                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                        <p class="mt-2 text-sm text-gray-600">Leave empty if your database doesn't require a password</p>
                                    </div>
                                </div>
                        </div>

                        @if($errors->has('database'))
                            <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        <svg class="w-5 h-5 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm text-red-800">{{ $errors->first('database') }}</p>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Navigation -->
                        <div class="flex items-center justify-between pt-8 pb-12 border-t border-gray-200">
                            <a href="{{ route('install.requirements') }}" class="inline-flex items-center px-6 py-3 text-base font-medium text-gray-700 bg-gray-100 border border-transparent rounded-lg hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors duration-200 shadow-md">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                </svg>
                                Back
                            </a>

                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Test & Continue
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>