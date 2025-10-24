<?php

namespace app\domains\Cover;

use yii\web\UploadedFile;

/**
 * Интерфейс сервиса для работы с обложками книг
 * 
 * Определяет контракт для операций загрузки и обработки обложек книг.
 * Реализует паттерн Service для инкапсуляции логики работы с файлами изображений,
 * включая валидацию, обработку и сохранение обложек.
 * 
 * @package app\domains\Cover
 * @author Zernov Oleg <oi.zernov@gmail.com>
 * @since 1.0
 */
interface CoverServiceInterface
{
    /**
     * Загрузка обложки книги
     * 
     * Обрабатывает загрузку файла обложки с валидацией формата и размера,
     * оптимизацией изображения и сохранением в файловой системе.
     * 
     * @param UploadedFile $file Загружаемый файл обложки
     * @return string Путь к сохраненному файлу обложки
     * @throws \Exception В случае ошибки загрузки или валидации файла
     */
    public function upload(UploadedFile $file): string;
}