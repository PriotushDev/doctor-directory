<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Prescription;
use App\Http\Resources\PrescriptionResource;

$rx = Prescription::latest()->first();
if ($rx) {
    echo "RX ID: " . $rx->id . "\n";
    echo "Appointment ID: " . ($rx->appointment_id ?: 'NULL') . "\n";
    echo "Doctor ID: " . ($rx->doctor_id ?: 'NULL') . "\n";
    
    $resource = new PrescriptionResource($rx);
    $data = $resource->toArray(request());
    echo "Chamber Name in Resource: " . ($data['chamber_name'] ?: 'NULL') . "\n";
} else {
    echo "No prescription found\n";
}
