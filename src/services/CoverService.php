<?php

namespace app\services;

use yii\web\UploadedFile;
use app\domains\Cover\CoverServiceInterface;
use Yii;

class CoverService implements CoverServiceInterface
{
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