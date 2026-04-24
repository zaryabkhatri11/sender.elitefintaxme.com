<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Laracasts\Flash\Flash;

class CampaignController extends AppBaseController
{
    public function index()
    {
        \App\Helper\BreadcrumbsRegister::Register('campaigns', 'Campaigns');
        return view('admin.campaign.index');
    }

    public function create()
    {
        \App\Helper\BreadcrumbsRegister::Register('campaigns', 'Campaigns');
        return view('admin.campaign.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt',
            'message' => 'required',
        ]);

        $file = $request->file('csv_file');
        $handle = fopen($file->getRealPath(), 'r');

        // Skip header
        $header = fgetcsv($handle);

        $successCount = 0;
        $errorCount = 0;
        $errors = [];

        $twilio = new \App\Services\TwilioService();
        $adminId = \Auth::id();
        $twilioFrom = config('services.twilio.from');

        // Form fields for placeholders
        $formDirectPhone = $request->input('direct_phone');
        $formApptDate = $request->input('appointment_date');
        $formApptTime = $request->input('appointment_time');
        $formApptNo = $request->input('appointment_number');
        $baseMessage = $request->input('message');

        while (($row = fgetcsv($handle, 1000, ',')) !== FALSE) {
            if (count($row) < 3)
                continue; // Basic validation

            $email = trim($row[0]);
            $phone = trim($row[1]);
            $ownerName = trim($row[2]);
            $entity = trim($row[3]);
            $ownerAddress = trim($row[4]);
            $subjectMark = trim($row[5]);
            $caseNumber = trim($row[6]);

            if (empty($phone))
                continue;

            // Prepare the personalized message
            $personalizedMessage = $baseMessage;
            $personalizedMessage = str_replace('{owner_name}', $ownerName, $personalizedMessage);
            $personalizedMessage = str_replace('{direct_phone}', $formDirectPhone, $personalizedMessage);
            $personalizedMessage = str_replace('{appointments_date}', $formApptDate, $personalizedMessage);
            $personalizedMessage = str_replace('{appointment_time}', $formApptTime, $personalizedMessage);
            $personalizedMessage = str_replace('{appointment_no}', $formApptNo, $personalizedMessage);

            // Send SMS
            $sendResults = $twilio->sendSms($phone, $personalizedMessage);
            $sendResult = $sendResults[0] ?? ['success' => false, 'error' => 'No response from Twilio'];

            if ($sendResult['success']) {
                try {
                    \App\Models\MessagesLog::create([
                        'customer_id' => null,
                        'from_num' => $twilioFrom,
                        'to_num' => $phone,
                        'body' => $personalizedMessage,
                        'status' => $sendResult['status'],
                        'message_sid' => $sendResult['sid'],
                        'direction' => 'outbound',
                        'user_id' => $adminId,
                    ]);
                    $successCount++;
                } catch (\Exception $e) {
                    \Log::error("Log creation failed for $phone: " . $e->getMessage());
                    $errorCount++;
                    $errors[] = "Log failed for $phone";
                }
            } else {
                $errorCount++;
                $errors[] = "Twilio failed for $phone: " . ($sendResult['error'] ?? 'Unknown error');
            }

        }
        fclose($handle);

        if ($errorCount > 0) {
            Flash::error("Processed Campaign. $successCount sent, $errorCount failed.");
        } else {
            Flash::success("Campaign processed successfully. $successCount messages sent.");
        }

        return redirect(route('admin.messages-logs.index'));
    }



}
