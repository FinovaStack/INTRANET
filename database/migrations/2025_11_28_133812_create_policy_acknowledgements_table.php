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
        Schema::create('policy_acknowledgements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('document_id')->constrained('documents')->cascadeOnDelete();
            $table->foreignId('document_version_id')->nullable()->constrained('document_versions')->nullOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->enum('status', ['pending', 'acknowledged', 'declined', 'overdue'])->default('pending');
            $table->timestamp('acknowledged_at')->nullable();
            $table->timestamp('due_at')->nullable();
            $table->timestamp('last_reminder_sent_at')->nullable();
            $table->text('note')->nullable();
            $table->timestamps();

            $table->unique(['document_id', 'user_id', 'document_version_id'], 'unique_policy_ack');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('policy_acknowledgements');
    }
};
