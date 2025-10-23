<?php
/** @var app\models\Book[] $books */
use yii\helpers\Html;

$this->title = 'Каталог книг';
?>
<h1><?= Html::encode($this->title) ?></h1>

<p>
    <?= Html::a('ТОП-10 авторов', ['report/top-authors'], [
        'class' => 'btn btn-primary',
        'style' => 'margin-bottom:15px;'
    ]) ?>
</p>

<ul>
<?php foreach ($books as $book): ?>
    <li>
        <?= Html::a(Html::encode($book->title), ['view', 'id' => $book->id]) ?>
        (<?= Html::encode($book->year) ?>)
    </li>
<?php endforeach; ?>
</ul>
