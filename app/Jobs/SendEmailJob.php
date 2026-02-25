<?php
namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Mail;
use App\Models\EmailLog;
use App\Models\CustomerEmailLog;

class SendEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $data;

    public function __construct($data)
    {
        \Log::info('JOB DISPATCHED', [
            'email' => $data['email'] ?? null,
            'time' => now()->toDateTimeString(),
        ]);

        $this->data = $data;
    }

    public function handle()
    {
        $rawEmails = $this->data['email'] ?? '';
        $serial = $this->data['serial'] ?? 'default';

        if (!$rawEmails)
            return;

        $emails = preg_split('/\s*,\s*/', trim($rawEmails, ','));
        $payload = array_merge([
            'email' => '',
            'name' => 'Applicant',
            'entity' => '',
            'address' => '',
            'wordmark' => '',
            'serial' => '',
            'date' => date('l, F d, Y', strtotime('+1 day')),
        ], (array) $this->data);

        foreach ($emails as $singleEmail) {
            // Skip invalid emails
            if (!filter_var($singleEmail, FILTER_VALIDATE_EMAIL)) {
                continue;
            }

            // Skip already sent emails (Commented out as per user request to allow duplicates)
            /*
            if (
                EmailLog::where('email', $singleEmail)
                    ->where('serial', $serial)
                    ->where('status', 'success')
                    ->exists()
            ) {
                continue;
            }
            */

            // Optional lock per email to prevent duplicates (commented out for testing/allowing duplicates)
            /*
            $lockFile = storage_path('framework/email-lock-' . md5($singleEmail . '-' . $serial));
            if (file_exists($lockFile)) {
                \Log::warning('Duplicate prevented', ['email' => $singleEmail, 'serial' => $serial]);
                continue;
            }
            file_put_contents($lockFile, time());
            */

            $messageId = null;
            try {
                Mail::send('email.trademark-notice', $payload, function ($mail) use ($payload, $singleEmail, &$messageId) {
                    $mail->to($singleEmail, $payload['name'])
                        ->subject('U.S Trademark Application Verification - ' . ($payload['wordmark'] ?: 'Trademark'));
                    $messageId = $mail->getId();
                });

                EmailLog::create([
                    'email' => $singleEmail,
                    'serial' => $serial,
                    'status' => 'success'
                ]);

                // ✅ NEW: Log to CustomerEmailLog
                CustomerEmailLog::create([
                    'customer_id' => $this->data['customer_id'] ?? null,
                    'direction' => 'outgoing',
                    'from_email' => config('mail.from.address'),
                    'to_email' => $singleEmail,
                    'subject' => 'U.S Trademark Application Verification - ' . ($payload['wordmark'] ?: 'Trademark'),
                    'message' => view('email.trademark-notice', $payload)->render(),
                    'message_id' => $messageId,
                    'status' => 'sent'
                ]);

                // Delay between emails to prevent SMTP errors (e.g. MailTrap rate limits)
                sleep(3);

            } catch (\Exception $e) {
                \Log::error("Failed to send email to {$singleEmail}: " . $e->getMessage());

                EmailLog::create([
                    'email' => $singleEmail,
                    'serial' => $serial,
                    'status' => 'failed',
                    'error' => $e->getMessage()
                ]);

                //  NEW: Log Failure to CustomerEmailLog
                CustomerEmailLog::create([
                    'customer_id' => $this->data['customer_id'] ?? null,
                    'direction' => 'outgoing',
                    'from_email' => config('mail.from.address'),
                    'to_email' => $singleEmail,
                    'subject' => 'U.S Trademark Application Verification - ' . ($payload['wordmark'] ?: 'Trademark'),
                    'status' => 'failed',
                    'error' => $e->getMessage()
                ]);
            } finally {
                // @unlink($lockFile);
            }
        }
    }
}
