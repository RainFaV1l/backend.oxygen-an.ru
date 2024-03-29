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
            $table->string('fio')->after('total')->nullable()->change();
            $table->string('tel')->after('fio')->nullable()->change();
            $table->string('email')->after('tel')->nullable()->change();
            $table->string('height')->after('email')->nullable()->change();
            $table->string('city')->after('height')->nullable()->change();
            $table->string('promotional_code')->nullable()->after('city')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('carts', function (Blueprint $table) {
            $table->string('fio')->after('total')->change();
            $table->string('tel')->after('fio')->change();
            $table->string('email')->after('tel')->change();
            $table->string('height')->after('email')->change();
            $table->string('city')->after('height')->change();
            $table->string('promotional_code')->nullable()->after('city')->change();
        });
    }
};
