<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $tableNames = config('permission.table_names');
        $tableName = $tableNames['permissions'];
        
        // Drop the existing unique index if it exists
        DB::statement("SET FOREIGN_KEY_CHECKS=0;");
        
        // Drop the index if it exists
        $indexExists = DB::select("SHOW INDEX FROM `{$tableName}` WHERE Key_name = 'permissions_name_guard_name_unique'");
        if (count($indexExists) > 0) {
            DB::statement("ALTER TABLE `{$tableName}` DROP INDEX `permissions_name_guard_name_unique`");
        }
        
        // Modify the columns to use a smaller length
        DB::statement("ALTER TABLE `{$tableName}` MODIFY `name` VARCHAR(125) NOT NULL");
        DB::statement("ALTER TABLE `{$tableName}` MODIFY `guard_name` VARCHAR(125) NOT NULL");
        
        // Recreate the unique index with the new column lengths
        DB::statement("ALTER TABLE `{$tableName}` ADD UNIQUE `permissions_name_guard_name_unique` (`name`, `guard_name`)");
        
        DB::statement("SET FOREIGN_KEY_CHECKS=1;");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // This migration is fixing an issue, so we don't need to implement the down method
        // as we don't want to revert these changes
    }
};
