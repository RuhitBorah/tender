<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    echo "Checking User count...\n";
    echo "Users: " . \App\Models\User::count() . "\n";

    echo "Checking Tender count...\n";
    if (class_exists('\App\Models\Tender')) {
        echo "Tenders: " . \App\Models\Tender::count() . "\n";
    }
    else {
        echo "Tender model not found!\n";
    }

    echo "Checking District count...\n";
    if (class_exists('\App\Models\District')) {
        echo "Districts: " . \App\Models\District::count() . "\n";
    }
    else {
        echo "District model not found!\n";
    }

    echo "Checking Recent Tenders relationship...\n";
    $tenders = \App\Models\Tender::with(['district', 'subdistrict'])->take(1)->get();
    foreach ($tenders as $tender) {
        echo "Tender: " . $tender->title . "\n";
        echo "District: " . ($tender->district ? $tender->district->name : 'null') . "\n";
    }

    echo "Success! Dashboard data is queryable.\n";

}
catch (\Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n";
    echo "Trace:\n" . $e->getTraceAsString() . "\n";
}
