<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

if (!Schema::hasColumn('sms_logs', 'is_read')) {
    Schema::table('sms_logs', function (Blueprint $table) {
        $table->boolean('is_read')->default(true)->after('status');
    });
    echo "Added is_read column to sms_logs table.\n";
} else {
    echo "is_read column already exists.\n";
}
