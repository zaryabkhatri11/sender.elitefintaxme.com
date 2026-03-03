<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Customer;
use App\Models\CustomerEmailLog;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\EmailReplyController;
use Illuminate\Support\Facades\DB;

/**
 * THREADING TEST SCRIPT
 * This script simulates the scenario where you email one person (Atif)
 * and someone else (Zaryab) replies using the same email chain.
 */

// 1. Find or create a test customer
$customer = Customer::first();
if (!$customer) {
    die("Error: Please create at least one customer in the dashboard first.\n");
}

echo "Testing for Customer: {$customer->owner_name} ({$customer->email})\n";
echo "--------------------------------------------------------\n";

// 2. Simulate OUTGOING email to Atif
$msgId1 = 'original-email-' . time();
CustomerEmailLog::create([
    'customer_id' => $customer->id,
    'direction' => 'outgoing',
    'from_email' => 'admin@elitefintaxme.com',
    'to_email' => 'atif@example.com',
    'subject' => 'Verify your Trademark',
    'message' => 'Hello Atif, please verify.',
    'message_id' => $msgId1,
    'thread_id' => $msgId1, // Initialize thread
    'status' => 'sent'
]);
echo "1. [OUTGOING] Sent email to atif@example.com. (Thread ID: $msgId1)\n";

// 3. Simulate INCOMING reply from SAME person (Atif)
$msgId2 = 'reply-atif-' . time();
$requestAtif = new Request([
    'sender' => 'atif@example.com',
    'recipient' => 'admin@elitefintaxme.com',
    'subject' => 'Re: Verify your Trademark',
    'body-html' => 'Hi, this is Atif replying.',
    'Message-Id' => $msgId2,
    'In-Reply-To' => $msgId1
]);

$controller = new EmailReplyController();
$controller->handle($requestAtif);

$log2 = CustomerEmailLog::where('message_id', $msgId2)->first();
echo "2. [INCOMING] Atif replied. \n";
echo "   - Maintained Thread: " . ($log2->thread_id === $msgId1 ? "✅ YES" : "❌ NO") . "\n";

// 4. Simulate INCOMING reply from DIFFERENT person (Zaryab)
$msgId3 = 'reply-zaryab-' . time();
$requestZaryab = new Request([
    'sender' => 'zaryab@example.com',
    'recipient' => 'admin@elitefintaxme.com',
    'subject' => 'Re: Verify your Trademark',
    'body-html' => 'Hey, I am Zaryab replying to Atif\'s thread.',
    'Message-Id' => $msgId3,
    'In-Reply-To' => $msgId1
]);

$controller->handle($requestZaryab);

$log3 = CustomerEmailLog::where('message_id', $msgId3)->first();
echo "3. [INCOMING] Zaryab replied to Atif's email chain.\n";
echo "   - Created New Thread: " . ($log3->thread_id === $msgId3 ? "✅ YES (Zaryab is separate)" : "❌ NO") . "\n";

echo "--------------------------------------------------------\n";
echo "TEST COMPLETE. Now refresh your dashboard and click the email icon for this customer.\n";
echo "You should see TWO separate conversations in the list.\n";
