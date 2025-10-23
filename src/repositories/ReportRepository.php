<?php

namespace app\repositories;

use yii\db\Query;
use app\domains\Report\ReportRepositoryInterface;

class ReportRepository implements ReportRepositoryInterface
{
     /**
     * Возвращает топ-10 авторов по количеству книг за указанный год.
    * @param int $year Год, по которому фильтруются книги.
    * @return array Массив авторов с соответствующим количеством книг.
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
