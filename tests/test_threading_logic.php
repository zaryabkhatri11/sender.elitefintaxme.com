<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\CustomerEmailLog;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\EmailReplyController;
use Illuminate\Support\Facades\DB;

// Clear potential old test data
DB::table('customer_email_logs')->where('subject', 'LIKE', '%TEST THREADING%')->delete();

echo "--- Starting Threading Logic Test ---\n";

// 1. Simulate Outgoing Email to Atif
$msgId1 = 'msg-atif-123';
CustomerEmailLog::create([
    'direction' => 'outgoing',
    'from_email' => 'system@elitefintaxme.com',
    'to_email' => 'atif@example.com',
    'subject' => 'TEST THREADING: Hello Atif',
    'message' => 'Original message',
    'message_id' => $msgId1,
    'thread_id' => $msgId1,
    'status' => 'sent'
]);
echo "1. Sent original email to Atif. MessageID: $msgId1, ThreadID: $msgId1\n";

// 2. Simulate Reply from ATIF (Same person)
$msgId2 = 'reply-atif-456';
$requestAtif = new Request([
    'sender' => 'atif@example.com',
    'recipient' => 'system@elitefintaxme.com',
    'subject' => 'Re: TEST THREADING: Hello Atif',
    'body-html' => 'Atif replying',
    'Message-Id' => $msgId2,
    'In-Reply-To' => $msgId1
]);

$controller = new EmailReplyController();
$controller->handle($requestAtif);

$log2 = CustomerEmailLog::where('message_id', $msgId2)->first();
echo "2. Atif replied. Reply MessageID: $msgId2, Parent: {$log2->in_reply_to}, ThreadID: {$log2->thread_id}\n";
if ($log2->thread_id === $msgId1) {
    echo "✅ SUCCESS: Thread maintained for same sender.\n";
} else {
    echo "❌ FAILED: Thread changed for same sender.\n";
}

// 3. Simulate Reply from ZARYAB (Different person)
$msgId3 = 'reply-zaryab-789';
$requestZaryab = new Request([
    'sender' => 'zaryab@example.com',
    'recipient' => 'system@elitefintaxme.com',
    'subject' => 'Re: TEST THREADING: Hello Atif',
    'body-html' => 'Zaryab replying to Atif\'s thread',
    'Message-Id' => $msgId3,
    'In-Reply-To' => $msgId1
]);

$controller->handle($requestZaryab);

$log3 = CustomerEmailLog::where('message_id', $msgId3)->first();
echo "3. Zaryab replied to Atif's email. Reply MessageID: $msgId3, Parent: {$log3->in_reply_to}, ThreadID: {$log3->thread_id}\n";
if ($log3->thread_id === $msgId3 && $log3->thread_id !== $msgId1) {
    echo "✅ SUCCESS: New thread created for different sender.\n";
} else {
    echo "❌ FAILED: Thread maintained for different sender.\n";
}

echo "--- Test Complete ---\n";
