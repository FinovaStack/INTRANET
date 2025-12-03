<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('employee_code')->unique();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('other_names')->nullable();
            $table->string('email')->unique();
            $table->string('phone_number')->nullable();
            $table->string('job_title')->nullable();
            $table->enum('role', [
                'executive',
                'head_of_department',
                'team_lead',
                'manager',
                'staff',
            ])->default('staff');
            $table->enum('access_scope', ['global', 'branch', 'department'])->default('department');
            $table->foreignId('department_id')->nullable()->constrained('departments')->nullOnDelete();
            $table->string('branch')->nullable();
            $table->string('sub_branch')->nullable();
            $table->string('sector')->nullable();
            $table->foreignId('reports_to_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->boolean('is_active')->default(true);
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
};
