<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use App\Models\Office;
use App\Models\User;

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
            // Add office_id column (nullable initially for data migration)
            $table->unsignedBigInteger('office_id')->nullable()->after('office');
        });

        // Migrate existing office names to office_ids
        $users = User::whereNotNull('office')->get();
        foreach ($users as $user) {
            $office = Office::where('name', $user->office)->first();
            if ($office) {
                DB::table('users')
                    ->where('id', $user->id)
                    ->update(['office_id' => $office->id]);
            }
        }

        Schema::table('users', function (Blueprint $table) {
            // Add foreign key constraint
            $table->foreign('office_id')
                  ->references('id')
                  ->on('offices')
                  ->onDelete('set null')
                  ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            // Drop foreign key first
            $table->dropForeign(['office_id']);
            // Drop the column
            $table->dropColumn('office_id');
        });
    }
};
