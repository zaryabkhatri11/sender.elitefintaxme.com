<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

try {
    if (!Schema::hasColumn('customer_email_logs', 'attachment')) {
        Schema::table('customer_email_logs', function (Blueprint $table) {
            $table->string('attachment')->nullable()->after('message');
        });
        echo "Successfully added 'attachment' column to customer_email_logs.\n";
    } else {
        echo "Column 'attachment' already exists.\n";
    }
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
