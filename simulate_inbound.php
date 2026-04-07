<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\MessagesLog;
use App\Models\Customer;

// 1. Find a customer or create a dummy one
$customer = Customer::first();
$toNum = '+1234567890';
$fromNum = '+0987654321';

if ($customer) {
    $fromNum = $customer->phone ?: $fromNum;
    echo "Using Customer: {$customer->owner_name} ($fromNum)\n";
} else {
    echo "No customer found, using dummy number.\n";
}

// 2. Create an INBOUND message (Unread)
$msg = MessagesLog::create([
    'customer_id' => $customer ? $customer->id : null,
    'from_num' => $fromNum,
    'to_num' => '+1555000111',
    'body' => 'Simulated WhatsApp message ' . time(),
    'direction' => 'inbound',
    'status' => 'received',
    'is_read' => false,
    'message_sid' => 'SIM_' . time()
]);

echo "Created INBOUND message ID: {$msg->id}. It should appear at the top with an unread badge.\n";
