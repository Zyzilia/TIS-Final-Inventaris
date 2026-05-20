<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('user_name')->nullable();
            $table->string('action'); // e.g. 'Paid', 'Refund', 'Restock', 'Delete', 'Update', 'Create'
            $table->string('description'); // e.g. 'Budi Santoso purchased RTX 4090' or 'Admin updated Ryzen 9'
            $table->string('item_type')->nullable(); // e.g. 'gpu', 'cpu', 'ram', 'ssd', 'mb', 'psu', 'case', 'cooling'
            $table->string('amount')->nullable(); // e.g. 'Rp 28.000.000' or '+15 Units'
            $table->string('order_id')->nullable(); // e.g. '#513 003'
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};
