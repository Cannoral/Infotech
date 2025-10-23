<?php
/** @var array $authors */
/** @var int $year */
use yii\helpers\Html;

$this->title = "ТОП-10 авторов за {$year}";
?>
<h1><?= Html::encode($this->title) ?></h1>

<form method="get">
    <label>Год:
        <input type="number" name="year" value="<?= Html::encode($year) ?>" min="1500" max="<?= date('Y') ?>">
    </label>
    <button type="submit">Показать</button>
</form>

<?php if (empty($authors)): ?>
    <p>Нет данных за выбранный год.</p>
<?php else: ?>
    <table border="1" cellpadding="5">
        <tr><th>#</th><th>Автор</th><th>Количество книг</th></tr>
        <?php foreach ($authors as $i => $a): ?>
            <tr>
                <td><?= $i + 1 ?></td>
                <td><?= Html::encode($a['name']) ?></td>
                <td><?= Html::encode($a['total']) ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php endif; ?>
