<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

$tableName = 'customer_email_logs';

if (!Schema::hasTable($tableName)) {
    die("Table $tableName does not exist.\n");
}

$columns = Schema::getColumnListing($tableName);
echo "Current columns: " . implode(', ', $columns) . "\n";

Schema::table($tableName, function (Blueprint $table) use ($columns) {
    if (!in_array('message_id', $columns)) {
        $table->string('message_id')->nullable();
        echo "Added message_id column.\n";
    }
    if (!in_array('in_reply_to', $columns)) {
        $table->string('in_reply_to')->nullable();
        echo "Added in_reply_to column.\n";
    }
    if (!in_array('thread_id', $columns)) {
        $table->string('thread_id')->nullable();
        echo "Added thread_id column.\n";
    }
});
