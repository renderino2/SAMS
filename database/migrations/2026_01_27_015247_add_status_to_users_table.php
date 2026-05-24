<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('status')->nullable()->after('active');
        });

        // Set default status for existing Student Assistants based on their active field
        DB::table('users')
            ->where('role', 'Student Assistant')
            ->whereNull('status')
            ->update([
                'status' => DB::raw('CASE WHEN active = 1 THEN "Active" ELSE "Inactive" END')
            ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};
