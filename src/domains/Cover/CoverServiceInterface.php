<?php

namespace app\domains\Cover;

use yii\web\UploadedFile;

interface CoverServiceInterface
{
    public function upload(UploadedFile $file): string;
}