<?php

namespace app\jobs;

use yii\base\BaseObject;
use yii\queue\JobInterface;
use GuzzleHttp\Client;
use Yii;

/**
 * Фоновая задача для отправки SMS уведомлений
 * 
 * Выполняет асинхронную отправку SMS сообщений через внешний API.
 * Реализует интерфейс JobInterface для интеграции с системой очередей Yii2.
 * Включает обработку ошибок и логирование результатов операций.
 * 
 * @package app\jobs
 * @author Zernov Oleg <oi.zernov@gmail.com>
 * @since 1.0
 */
class SmsNotificationJob extends BaseObject implements JobInterface
{
    /**
     * @var string Номер телефона получателя SMS
     */
    public string $phone;
    
    /**
     * @var string Текст SMS сообщения
     */
    public string $message;

    /**
     * Выполнение задачи отправки SMS
     * 
     * Отправляет SMS сообщение через внешний API с использованием HTTP клиента.
     * Обрабатывает ответ API, логирует результат и выбрасывает исключения при ошибках.
     * 
     * @param mixed $queue Объект очереди (не используется в данной реализации)
     * @return void
     * @throws \RuntimeException При ошибке SMS API
     * @throws \Throwable При других ошибках HTTP запроса
     */
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
