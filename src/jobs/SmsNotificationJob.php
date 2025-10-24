<?php

namespace app\jobs;

use yii\base\BaseObject;
use yii\queue\JobInterface;
use GuzzleHttp\Client;
use Yii;

class SmsNotificationJob extends BaseObject implements JobInterface
{
    public string $phone;
    public string $message;

    public function execute($queue)
    {
        Yii::info("tETS", 'sms');
        $client = new Client([
            'base_uri' => $_ENV['SMS_BASE_URL'],
            'timeout'  => 5.0,
        ]);

        try {
            $response = $client->get('api.php', [
                'query' => [
                    'send' => $this->message,
                    'to' => $this->phone,
                    'apikey' => $_ENV['SMS_API_KEY'],
                    'format' => 'json',
                ]
            ]);

            $body = (string) $response->getBody();

            $data = json_decode($body, true);
            if (isset($data['error'])) {
                $msg = sprintf(
                    'SMS API error for %s: [%s] %s (%s)',
                    $this->phone,
                    $data['error']['code'] ?? '?',
                    $data['error']['description_ru'] ?? $data['error']['description'] ?? 'unknown',
                    $data['error']['ip'] ?? '-'
                );
                throw new \RuntimeException($msg);
            }
            Yii::info("SMS sent to {$this->phone}. Response: {$body}", 'sms');
        } catch (\Throwable $e) {
            Yii::error("SMS sending failed to {$this->phone}: {$e->getMessage()}", 'sms');
            throw $e;
        }
    }
}
