<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

try {
    config(['database.connections.mysql.host' => '127.0.0.1']);
    config(['database.connections.mysql.port' => '3310']);
    
    $columns = DB::select('SHOW COLUMNS FROM petugas');
    echo json_encode($columns);
} catch (\Exception $e) {
    echo $e->getMessage();
}
