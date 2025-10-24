<?php

namespace app\repositories;

use yii\db\Query;
use app\domains\Report\ReportRepositoryInterface;

/**
 * Репозиторий для работы с отчетами
 * 
 * Реализация интерфейса ReportRepositoryInterface для получения аналитических данных.
 * Обеспечивает выполнение сложных SQL запросов для формирования отчетов и статистики.
 * Использует Query Builder для построения оптимизированных запросов к базе данных.
 * 
 * @package app\repositories
 * @author Zernov Oleg <oi.zernov@gmail.com>
 * @since 1.0
 */
class ReportRepository implements ReportRepositoryInterface
{
    /**
     * @inheritdoc
     * 
     * Выполняет сложный SQL запрос с соединением таблиц author, book_author и book.
     * Группирует результаты по авторам, подсчитывает количество книг и сортирует по убыванию.
     * Ограничивает результат топ-10 авторами.
     */
    public function getTopAuthorsByYear(int $year): array
    {
        $query = (new Query())
            ->select(['a.name', 'COUNT(b.id) AS total'])
            ->from(['a' => 'author'])
            ->innerJoin(['ba' => 'book_author'], 'ba.author_id = a.id')
            ->innerJoin(['b' => 'book'], 'b.id = ba.book_id')
            ->where(['b.year' => $year])
            ->groupBy(['a.id'])
            ->orderBy(['total' => SORT_DESC])
            ->limit(10);

        return $query->all();
    }
}
