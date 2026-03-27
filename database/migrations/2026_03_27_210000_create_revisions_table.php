<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('revisions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('listing_id')->constrained()->cascadeOnDelete();
            $table->string('field');
            $table->text('old_value')->nullable();
            $table->text('new_value')->nullable();
            $table->timestamp('created_at')->useCurrent();
        });

        DB::statement(
            'CREATE INDEX revisions_listing_id_created_at_desc_index ON revisions (listing_id, created_at DESC)'
        );
    }

    public function down(): void
    {
        Schema::dropIfExists('revisions');
    }
};

