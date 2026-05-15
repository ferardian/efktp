<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

config(['database.connections.mysql.host' => '127.0.0.1']);
$columns = Illuminate\Support\Facades\DB::select("DESCRIBE petugas");
print_r($columns);
