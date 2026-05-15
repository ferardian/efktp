<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    $tables = Illuminate\Support\Facades\DB::select('SHOW TABLES');
    foreach ($tables as $table) {
        foreach ($table as $key => $value) {
            if (strpos($value, 'triase') !== false) {
                echo $value . PHP_EOL;
            }
        }
    }
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . PHP_EOL;
}
