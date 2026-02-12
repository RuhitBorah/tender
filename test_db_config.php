<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "Environment: " . env('APP_ENV') . "\n";
echo "DB_CONNECTION from env: " . env('DB_CONNECTION') . "\n";
echo "Config database.default: " . config('database.default') . "\n";
echo "Config database.connections.mysql.host: " . config('database.connections.mysql.host') . "\n";

try {
    $pdo = \Illuminate\Support\Facades\DB::connection()->getPdo();
    echo "Connected to database: " . \Illuminate\Support\Facades\DB::connection()->getDatabaseName() . "\n";
    echo "Driver: " . \Illuminate\Support\Facades\DB::connection()->getDriverName() . "\n";
}
catch (\Exception $e) {
    echo "Connection failed: " . $e->getMessage() . "\n";
}
