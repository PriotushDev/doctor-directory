<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('prescriptions', function (Blueprint $table) {
            $table->string('cc')->nullable()->after('patient_id'); // Chief Complaint
            $table->string('oe')->nullable()->after('cc'); // On Examination
            $table->string('oh')->nullable()->after('oe'); // Occupational/Other History
            $table->string('mh')->nullable()->after('oh'); // Medical/Menstrual History
            $table->text('investigation')->nullable()->after('mh'); // Investigation
            
            // Patient details at time of prescription
            $table->string('age')->nullable()->after('investigation');
            $table->string('sex')->nullable()->after('age');
            $table->string('weight')->nullable()->after('sex');
            $table->string('registration_no')->nullable()->after('weight');
        });
    }

    public function down(): void
    {
        Schema::table('prescriptions', function (Blueprint $table) {
            $table->dropColumn([
                'cc', 'oe', 'oh', 'mh', 'investigation',
                'age', 'sex', 'weight', 'registration_no'
            ]);
        });
    }
};
