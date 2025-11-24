<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasTable('menu_items')) {
            Schema::table('menu_items', function (Blueprint $table) {
                if (! Schema::hasColumn('menu_items', 'price')) {
                    $table->decimal('price', 10, 2)->after('name');
                }

                if (! Schema::hasColumn('menu_items', 'status')) {
                    $table->string('status')->after('price');
                }

                if (! Schema::hasColumn('menu_items', 'description')) {
                    $table->text('description')->nullable()->after('status');
                }

                if (! Schema::hasColumn('menu_items', 'category_id')) {
                    $table->unsignedBigInteger('category_id')->nullable()->after('description');
                }
            });

            return;
        }

        Schema::create('menu_items', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->decimal('price', 10, 2);
            $table->string('status');
            $table->text('description')->nullable();
            $table->unsignedBigInteger('category_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menu_items');
    }
};

