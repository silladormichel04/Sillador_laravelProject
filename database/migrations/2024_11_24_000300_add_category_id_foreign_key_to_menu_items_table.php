<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (! Schema::hasTable('menu_items')) {
            return;
        }

        Schema::table('menu_items', function (Blueprint $table) {
            if (! Schema::hasColumn('menu_items', 'category_id')) {
                $table->unsignedBigInteger('category_id')->nullable()->after('description');
            }
        });

        if ($this->foreignKeyExists()) {
            return;
        }

        Schema::table('menu_items', function (Blueprint $table) {
            $table->foreign('category_id')
                ->references('id')
                ->on('categories')
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (! Schema::hasTable('menu_items') || ! Schema::hasColumn('menu_items', 'category_id')) {
            return;
        }

        Schema::table('menu_items', function (Blueprint $table) {
            $table->dropForeign(['category_id']);
        });
    }

    protected function foreignKeyExists(): bool
    {
        $connection = Schema::getConnection();
        $driver = $connection->getDriverName();

        if ($driver !== 'mysql') {
            return false;
        }

        $database = $connection->getDatabaseName();

        return DB::table('information_schema.KEY_COLUMN_USAGE')
            ->where('TABLE_SCHEMA', $database)
            ->where('TABLE_NAME', 'menu_items')
            ->where('COLUMN_NAME', 'category_id')
            ->whereNotNull('REFERENCED_TABLE_NAME')
            ->exists();
    }
};

