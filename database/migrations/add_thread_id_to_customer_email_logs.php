<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

if (!Schema::hasColumn('customer_email_logs', 'thread_id')) {
    Schema::table('customer_email_logs', function (Blueprint $table) {
        $table->string('thread_id')->nullable()->after('in_reply_to');
    });
    echo "Added thread_id column to customer_email_logs table.\n";
} else {
    echo "thread_id column already exists.\n";
}
