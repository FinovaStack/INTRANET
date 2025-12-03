<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Config;

class InstallationCheck
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // Check if app is installed
        $isInstalled = $this->isAppInstalled();

        // If accessing install routes
        if ($request->is('install*')) {
            // If app is already installed, redirect to login
            if ($isInstalled) {
                return redirect()->route('login');
            }
            // If not installed, allow access to install routes
            return $next($request);
        }

        // For non-install routes, redirect to install if not installed
        if (!$isInstalled) {
            return redirect()->route('install.welcome');
        }

        return $next($request);
    }

    /**
     * Check if the application is installed
     *
     * @return bool
     */
    private function isAppInstalled()
    {
        // Check if .env exists and has APP_INSTALLED=true
        if (!file_exists(base_path('.env'))) {
            return false;
        }

        // If APP_INSTALLED is set to true in .env, set database config and consider app installed
        $envContent = file_get_contents(base_path('.env'));
        if (str_contains($envContent, 'APP_INSTALLED=true')) {
            // Set database config from .env to ensure it's loaded
            Config::set('database.connections.mysql.host', env('DB_HOST', '127.0.0.1'));
            Config::set('database.connections.mysql.port', env('DB_PORT', '3306'));
            Config::set('database.connections.mysql.database', env('DB_DATABASE', 'forge'));
            Config::set('database.connections.mysql.username', env('DB_USERNAME', 'forge'));
            Config::set('database.connections.mysql.password', env('DB_PASSWORD', ''));
            DB::purge('mysql');
            DB::reconnect('mysql');
            return true;
        }

        // Check if we can connect to database and migrations table exists
        try {
            // Try to connect to database
            DB::connection()->getPdo();

            // Check if migrations table exists and has records
            if (Schema::hasTable('migrations')) {
                $migrationCount = DB::table('migrations')->count();
                if ($migrationCount > 0) {
                    // Check if users table exists and has at least one user
                    if (Schema::hasTable('users')) {
                        $userCount = DB::table('users')->count();
                        return $userCount > 0;
                    }
                }
            }
        } catch (\Exception $e) {
            // Database not configured or connection failed
            return false;
        }

        return false;
    }
}