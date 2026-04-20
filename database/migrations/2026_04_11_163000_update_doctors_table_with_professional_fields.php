<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('doctors', function (Blueprint $table) {
            $table->text('long_bio')->nullable()->after('bio');
            $table->string('degree1')->nullable()->after('degree');
            $table->string('degree2')->nullable()->after('degree1');
            $table->string('degree3')->nullable()->after('degree2');
            $table->string('degree4')->nullable()->after('degree3');
            $table->string('workplace')->nullable()->after('degree4');
            $table->string('bmdc')->unique()->nullable()->after('workplace');
        });
    }

    public function down(): void
    {
        Schema::table('doctors', function (Blueprint $table) {
            $table->dropColumn([
                'long_bio',
                'degree1',
                'degree2',
                'degree3',
                'degree4',
                'workplace',
                'bmdc',
            ]);
        });
    }
};
