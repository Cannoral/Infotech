<?php

namespace app\services;

use yii\web\UploadedFile;
use app\domains\Cover\CoverServiceInterface;
use Yii;

/**
 * Сервис для обработки обложек книг
 * 
 * Реализация интерфейса CoverServiceInterface для загрузки и обработки файлов обложек.
 * Обеспечивает сохранение загружаемых изображений в файловую систему с генерацией уникальных имен.
 * Автоматически создает необходимые директории и обрабатывает ошибки загрузки.
 * 
 * @package app\services
 * @author Zernov Oleg <oi.zernov@gmail.com>
 * @since 1.0
 */
class CoverService implements CoverServiceInterface
{
    /**
     * @inheritdoc
     * 
     * Сохраняет загруженный файл в директорию uploads с уникальным именем.
     * Автоматически создает директорию uploads при её отсутствии.
     * Генерирует уникальное имя файла на основе uniqid() с сохранением расширения.
     * Возвращает веб-путь к сохраненному файлу для использования в HTML.
     */
    public function upload(UploadedFile $file): string
    {
        $dir = Yii::getAlias('@webroot/uploads');
        if (!is_dir($dir)) {
            mkdir($dir, 0775, true);
        }

        $filename = uniqid('cover_', true) . '.' . $file->extension;
        if (!$file->saveAs("$dir/$filename", false)) {
            throw new \RuntimeException('Ошибка при сохранении файла');
        }

        return '/uploads/' . $filename;
    }
}