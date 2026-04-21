<?php
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$tables = DB::select('SHOW TABLES');
$dbName = config('database.connections.mysql.database');
$columnToFind = 'no_rkm_medis';
$found = [];

foreach ($tables as $table) {
    $tableName = $table->{'Tables_in_' . $dbName};
    $columns = Schema::getColumnListing($tableName);
    if (in_array($columnToFind, $columns)) {
        $found[] = $tableName;
    }
}

echo json_encode($found, JSON_PRETTY_PRINT);
