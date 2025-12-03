<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;
use App\Models\User;
use Exception;

class InstallationController extends Controller
{
    public function welcome()
    {
        return view('install.welcome');
    }

    public function requirements()
    {
        $requirements = [
            'php' => [
                'name' => 'PHP Version',
                'required' => '8.0.2',
                'current' => PHP_VERSION,
                'status' => version_compare(PHP_VERSION, '8.0.2', '>=')
            ],
            'pdo' => [
                'name' => 'PDO Extension',
                'required' => 'Enabled',
                'current' => extension_loaded('pdo') ? 'Enabled' : 'Disabled',
                'status' => extension_loaded('pdo')
            ],
            'pdo_mysql' => [
                'name' => 'PDO MySQL Extension',
                'required' => 'Enabled',
                'current' => extension_loaded('pdo_mysql') ? 'Enabled' : 'Disabled',
                'status' => extension_loaded('pdo_mysql')
            ],
            'mbstring' => [
                'name' => 'Mbstring Extension',
                'required' => 'Enabled',
                'current' => extension_loaded('mbstring') ? 'Enabled' : 'Disabled',
                'status' => extension_loaded('mbstring')
            ],
            'openssl' => [
                'name' => 'OpenSSL Extension',
                'required' => 'Enabled',
                'current' => extension_loaded('openssl') ? 'Enabled' : 'Disabled',
                'status' => extension_loaded('openssl')
            ],
            'tokenizer' => [
                'name' => 'Tokenizer Extension',
                'required' => 'Enabled',
                'current' => extension_loaded('tokenizer') ? 'Enabled' : 'Disabled',
                'status' => extension_loaded('tokenizer')
            ],
            'xml' => [
                'name' => 'XML Extension',
                'required' => 'Enabled',
                'current' => extension_loaded('xml') ? 'Enabled' : 'Disabled',
                'status' => extension_loaded('xml')
            ],
            'ctype' => [
                'name' => 'Ctype Extension',
                'required' => 'Enabled',
                'current' => extension_loaded('ctype') ? 'Enabled' : 'Disabled',
                'status' => extension_loaded('ctype')
            ],
            'json' => [
                'name' => 'JSON Extension',
                'required' => 'Enabled',
                'current' => extension_loaded('json') ? 'Enabled' : 'Disabled',
                'status' => extension_loaded('json')
            ],
            'storage_writable' => [
                'name' => 'Storage Directory Writable',
                'required' => 'Writable',
                'current' => is_writable(storage_path()) ? 'Writable' : 'Not Writable',
                'status' => is_writable(storage_path())
            ],
            'env_writable' => [
                'name' => '.env File Writable',
                'required' => 'Writable',
                'current' => is_writable(base_path('.env')) ? 'Writable' : 'Not Writable',
                'status' => is_writable(base_path('.env'))
            ]
        ];

        $allRequirementsMet = collect($requirements)->every(function ($requirement) {
            return $requirement['status'];
        });

        return view('install.requirements', compact('requirements', 'allRequirementsMet'));
    }

    public function database()
    {
        return view('install.database');
    }

    public function storeDatabase(Request $request)
    {
        $request->validate([
            'db_host' => 'required|string',
            'db_port' => 'required|integer|min:1|max:65535',
            'db_database' => 'required|string|max:255',
            'db_username' => 'required|string|max:255',
            'db_password' => 'nullable|string|max:255',
        ]);

        try {
            // Test database connection with provided credentials
            $connection = $this->testDatabaseConnection(
                $request->db_host,
                $request->db_port,
                $request->db_database,
                $request->db_username,
                $request->db_password
            );

            if (!$connection['success']) {
                return back()->withErrors(['database' => $connection['message']])->withInput();
            }

            // Store database config in session for next steps
            session([
                'install_db_host' => $request->db_host,
                'install_db_port' => $request->db_port,
                'install_db_database' => $request->db_database,
                'install_db_username' => $request->db_username,
                'install_db_password' => $request->db_password,
            ]);

            return redirect()->route('install.migrate');

        } catch (Exception $e) {
            return back()->withErrors(['database' => 'Database connection failed: ' . $e->getMessage()])->withInput();
        }
    }

    public function migrate()
    {
        // Check if database config is in session
        if (!session()->has('install_db_host')) {
            return redirect()->route('install.database');
        }

        return view('install.migrate');
    }

    public function runMigrations(Request $request)
    {
        if (!session()->has('install_db_host')) {
            return redirect()->route('install.database');
        }

        try {
            // Set database config dynamically
            Config::set('database.connections.mysql.host', session('install_db_host'));
            Config::set('database.connections.mysql.port', session('install_db_port'));
            Config::set('database.connections.mysql.database', session('install_db_database'));
            Config::set('database.connections.mysql.username', session('install_db_username'));
            Config::set('database.connections.mysql.password', session('install_db_password'));

            // Clear any existing connection
            DB::purge('mysql');
            DB::reconnect('mysql');

            // Run migrations
            Artisan::call('migrate', ['--force' => true]);

            return redirect()->route('install.admin');

        } catch (Exception $e) {
            return back()->withErrors(['migration' => 'Migration failed: ' . $e->getMessage()]);
        }
    }

    public function admin()
    {
        if (!session()->has('install_db_host')) {
            return redirect()->route('install.database');
        }

        return view('install.admin');
    }

    public function storeAdmin(Request $request)
    {
        if (!session()->has('install_db_host')) {
            return redirect()->route('install.database');
        }

        // Set database config dynamically before validation
        Config::set('database.connections.mysql.host', session('install_db_host'));
        Config::set('database.connections.mysql.port', session('install_db_port'));
        Config::set('database.connections.mysql.database', session('install_db_database'));
        Config::set('database.connections.mysql.username', session('install_db_username'));
        Config::set('database.connections.mysql.password', session('install_db_password'));

        DB::purge('mysql');
        DB::reconnect('mysql');

        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]/',
            'employee_code' => 'required|string|unique:users,employee_code',
        ], [
            'password.regex' => 'Password must contain at least one uppercase letter, one lowercase letter, one number, and one special character.',
        ]);

        try {

            // Create super admin user
            $admin = User::create([
                'employee_code' => $request->employee_code,
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'executive',
                'access_scope' => 'global',
                'is_active' => true,
                'email_verified_at' => now(),
            ]);

            // Update .env file with database credentials
            $this->updateEnvFile();

            // Clear installation session data
            session()->forget(['install_db_host', 'install_db_port', 'install_db_database', 'install_db_username', 'install_db_password']);

            return redirect()->route('login');

        } catch (Exception $e) {
            return back()->withErrors(['admin' => 'Failed to create admin user: ' . $e->getMessage()])->withInput();
        }
    }

    public function complete()
    {
        return view('install.complete');
    }

    private function testDatabaseConnection($host, $port, $database, $username, $password)
    {
        try {
            $dsn = "mysql:host={$host};port={$port};dbname={$database};charset=utf8mb4";
            $pdo = new \PDO($dsn, $username, $password, [
                \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
                \PDO::ATTR_EMULATE_PREPARES => false,
            ]);

            return ['success' => true, 'message' => 'Database connection successful'];
        } catch (\PDOException $e) {
            $errorCode = $e->getCode();
            $errorMessage = $e->getMessage();

            // Provide helpful error messages based on common issues
            if ($errorCode == 1049) {
                // Unknown database
                $helpfulMessage = "Database '{$database}' does not exist. Please create the database first using your MySQL administration tool (phpMyAdmin, MySQL Workbench, or command line) before proceeding with the installation.";
            } elseif ($errorCode == 1045) {
                // Access denied
                $helpfulMessage = "Access denied for user '{$username}'. Please check your database username and password.";
            } elseif ($errorCode == 2002) {
                // Connection refused
                $helpfulMessage = "Cannot connect to MySQL server at {$host}:{$port}. Please check that MySQL is running and the host/port are correct.";
            } else {
                $helpfulMessage = "Database connection failed: {$errorMessage}";
            }

            return ['success' => false, 'message' => $helpfulMessage];
        }
    }

    private function updateOrAddEnvLine($envContent, $key, $value)
    {
        $pattern = "/^{$key}=.*$/m";
        $replacement = "{$key}={$value}";

        if (preg_match($pattern, $envContent)) {
            // Replace existing line
            $envContent = preg_replace($pattern, $replacement, $envContent);
        } else {
            // Add new line
            $envContent .= "\n{$replacement}";
        }

        return $envContent;
    }

    private function updateEnvFile()
    {
        $envPath = base_path('.env');

        if (!File::exists($envPath)) {
            File::copy(base_path('.env.example'), $envPath);
        }

        $envContent = File::get($envPath);

        // Update database configuration
        $envContent = $this->updateOrAddEnvLine($envContent, 'DB_CONNECTION', 'mysql');
        $envContent = $this->updateOrAddEnvLine($envContent, 'DB_HOST', session('install_db_host'));
        $envContent = $this->updateOrAddEnvLine($envContent, 'DB_PORT', session('install_db_port'));
        $envContent = $this->updateOrAddEnvLine($envContent, 'DB_DATABASE', session('install_db_database'));
        $envContent = $this->updateOrAddEnvLine($envContent, 'DB_USERNAME', session('install_db_username'));
        $envContent = $this->updateOrAddEnvLine($envContent, 'DB_PASSWORD', '"' . session('install_db_password') . '"');

        // Set timezone
        $envContent = $this->updateOrAddEnvLine($envContent, 'APP_TIMEZONE', 'Africa/Nairobi');

        // Add APP_INSTALLED flag
        $envContent = $this->updateOrAddEnvLine($envContent, 'APP_INSTALLED', 'true');

        File::put($envPath, $envContent);
    }
}