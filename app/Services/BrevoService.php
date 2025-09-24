<?php
namespace App\Services;

use Brevo\Client\Configuration;
use Brevo\Client\Api\SendEmailApi;
use Brevo\Client\Model\SendSmtpEmail;

class BrevoService
{
    protected $apiInstance;
    protected $apiKey;

    public function __construct()
    {
        $this->apiKey = env('BREVO_API_KEY');
        if (empty($this->apiKey)) {
            throw new \Exception('Brevo API key not set in .env');
        }

        $config = Configuration::getDefaultConfiguration()->setApiKey('api-key', $this->apiKey);
        $this->apiInstance = new SendEmailApi(new \GuzzleHttp\Client(), $config);
    }

    public function sendEmail(string $to, string $subject, string $htmlContent, string $fromEmail = 'noreply@skybirdhotel.com', string $fromName = 'SkyBird Hotel'): array
    {
        $sendSmtpEmail = new SendSmtpEmail([
            'subject' => $subject,
            'htmlContent' => $htmlContent,
            'sender' => ['name' => $fromName, 'email' => $fromEmail],
            'to' => [['email' => $to]]
        ]);

        try {
            $result = $this->apiInstance->sendTransacEmail($sendSmtpEmail);
            return ['success' => true, 'messageId' => $result->getMessageId()];
        } catch (\Exception $e) {
            log_message('error', 'Brevo API error: ' . $e->getMessage());
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
}