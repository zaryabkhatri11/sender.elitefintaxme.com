<?php

namespace App\Services;

use Twilio\Rest\Client;
use Twilio\Jwt\AccessToken;
use Twilio\Jwt\Grants\VoiceGrant;

class TwilioService
{
    protected $client;
    protected $from;
    protected $messagingServiceSid;

    public function __construct()
    {
        $sid = config('services.twilio.sid');
        $token = config('services.twilio.auth_token');
        $apiKey = config('services.twilio.api_key');
        $apiSecret = config('services.twilio.api_secret');
        $this->from = config('services.twilio.from');
        $this->messagingServiceSid = config('services.twilio.messaging_service_sid');

        if ($sid) {
            \Log::info("TwilioService initializing: SID=" . substr($sid, 0, 8) . "...");
            if ($apiKey && $apiSecret) {
                \Log::info("TwilioService using API Key: SID=$apiKey");
                // Use API Key + Secret for better security/reliability
                $this->client = new Client($apiKey, $apiSecret, $sid);
            }
            elseif ($token) {
                \Log::info("TwilioService using Auth Token: SID=$sid");
                // Fallback to Account SID + Auth Token
                $this->client = new Client($sid, $token);
            } else {
                \Log::warning("TwilioService: No Auth Token or API Key found.");
            }
        } else {
            \Log::error("TwilioService: TWILIO_SID is missing.");
        }
    }

    /**
     * Format number to E.164 (ensure + and country code).
     */
    protected function formatNumber($number)
    {
        $number = trim($number);
        $number = str_replace([' ', '-', '(', ')'], '', $number);

        if (substr($number, 0, 1) === '+') {
            return $number;
        }

        // Handle Pakistani and other numbers starting with 0
        if (substr($number, 0, 1) === '0') {
            $number = substr($number, 1);

            // If it's now 10 digits, it's likely a PK or US local number.
            // For now, we prepend +92 if it was a 03xx... number (PK) 
            // or +1 if it is a 10 digit number and we are in US context.
            // Given the user logs, 0333... is very likely +92333...
            if (strlen($number) === 10) {
                // If it starts with 3 (like 333...), it's PK
                if (substr($number, 0, 1) === '3') {
                    return '+92' . $number;
                }
                return '+1' . $number;
            }
        }

        if (strlen($number) === 10 && is_numeric($number)) {
            return '+1' . $number;
        }

        if (is_numeric($number) && substr($number, 0, 1) !== '+') {
            return '+' . $number;
        }

        return $number;
    }

    /**
     * Generate a Twilio Access Token for the browser Voice JS SDK.
     *
     * @param string $identity A unique name for this user/browser session.
     * @return string|null JWT token string, or null if credentials missing.
     */
    public function getAccessToken($identity = 'admin')
    {
        $sid = config('services.twilio.sid');
        $apiKey = config('services.twilio.api_key');
        $apiSecret = config('services.twilio.api_secret');
        $appSid = config('services.twilio.twiml_app_sid');

        if (!$sid || !$apiKey || !$apiSecret || !$appSid) {
            return null;
        }

        // Correct: accountSid, signingKeySid (API Key SID), secret (API Key Secret)
        $accessToken = new AccessToken($sid, $apiKey, $apiSecret, 3600, $identity);

        $voiceGrant = new VoiceGrant();
        $voiceGrant->setOutgoingApplicationSid($appSid);
        $voiceGrant->setIncomingAllow(true);

        $accessToken->addGrant($voiceGrant);

        return $accessToken->toJWT();
    }

    /**
     * Send an SMS message.
     */
    public function sendSms($to, $message, $callbackUrl = null)
    {
        if (!$this->client) {
            return ['success' => false, 'message' => 'Twilio credentials not configured.'];
        }

        $recipients = is_array($to) ? $to : [$to];
        $results = [];

        foreach ($recipients as $recipient) {
            try {
                $recipient = $this->formatNumber($recipient);
                // Priority: Messaging Service SID (for Global Geomatch) > Static From Number
                if ($this->messagingServiceSid) {
                    $params['messagingServiceSid'] = $this->messagingServiceSid;
                } else {
                    $params['from'] = $this->from;
                }

                if ($callbackUrl) {
                    $params['statusCallback'] = $callbackUrl;
                }

                $params['body'] = $message;

                $msg = $this->client->messages->create($recipient, $params);

                $results[] = ['to' => $recipient, 'success' => true, 'sid' => $msg->sid, 'status' => $msg->status];
            }
            catch (\Exception $e) {
                $errorMsg = $e->getMessage();
                $statusCode = method_exists($e, 'getStatusCode') ? $e->getStatusCode() : 'N/A';
                \Log::error("Twilio SMS FAILED to $recipient | Status: $statusCode | Error: $errorMsg");
                $results[] = ['to' => $recipient, 'success' => false, 'error' => $errorMsg];
            }
        }

        return $results;
    }

    /**
     * Initiate a server-side outbound voice call.
     */
    public function makeCall($to, $twimlUrl, $callbackUrl = null)
    {
        if (!$this->client) {
            return ['success' => false, 'message' => 'Twilio credentials not configured.'];
        }

        try {
            $to = $this->formatNumber($to);
            $params = ['url' => $twimlUrl];

            if ($callbackUrl) {
                $params['statusCallback'] = $callbackUrl;
                $params['statusCallbackEvent'] = ['initiated', 'ringing', 'answered', 'completed'];
            }

            $call = $this->client->calls->create($to, $this->from, $params);

            return ['success' => true, 'sid' => $call->sid, 'status' => $call->status];
        }
        catch (\Exception $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * Bridge a call between two numbers (Click-to-Call).
     */
    public function bridgeCall($fromAdmin, $toCustomer, $bridgeUrl, $callbackUrl = null)
    {
        if (!$this->client) {
            return ['success' => false, 'message' => 'Twilio credentials not configured.'];
        }

        try {
            $fromAdmin = $this->formatNumber($fromAdmin);
            $params = ['url' => $bridgeUrl];

            if ($callbackUrl) {
                $params['statusCallback'] = $callbackUrl;
            }

            $call = $this->client->calls->create($fromAdmin, $this->from, $params);

            return ['success' => true, 'sid' => $call->sid, 'status' => $call->status];
        }
        catch (\Exception $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
}
