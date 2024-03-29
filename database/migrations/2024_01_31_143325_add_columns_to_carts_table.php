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
        Schema::table('carts', function (Blueprint $table) {
            $table->string('fio')->after('total');
            $table->string('tel')->after('fio');
            $table->string('email')->after('tel');
            $table->string('height')->after('email');
            $table->string('city')->after('height');
            $table->string('promotional_code')->nullable()->after('city');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('carts', function (Blueprint $table) {
            $table->dropColumn('fio');
            $table->dropColumn('tel');
            $table->dropColumn('email');
            $table->dropColumn('height');
            $table->dropColumn('city');
            $table->dropColumn('promotional_code');
        });
    }
};
