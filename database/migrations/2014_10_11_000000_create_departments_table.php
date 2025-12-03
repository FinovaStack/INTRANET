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
        Schema::create('departments', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->string('branch');
            $table->string('sub_branch')->nullable();
            $table->string('sector')->nullable();
            $table->foreignId('parent_department_id')
                ->nullable()
                ->constrained('departments')
                ->nullOnDelete();
            $table->unsignedBigInteger('hod_user_id')->nullable();
            $table->unsignedBigInteger('team_lead_user_id')->nullable();
            $table->unsignedBigInteger('manager_user_id')->nullable();
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->json('metadata')->nullable();
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
        Schema::dropIfExists('departments');
    }
};
