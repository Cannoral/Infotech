<?php
/** @var app\models\Book $book */
use yii\helpers\Html;

$this->title = $book->title;
?>
<h1><?= Html::encode($book->title) ?></h1>

<p><strong>Год выпуска:</strong> <?= Html::encode($book->year) ?></p>
<p><strong>Описание:</strong> <?= Html::encode($book->description) ?></p>
<p><strong>ISBN:</strong> <?= Html::encode($book->isbn) ?></p>

<h3>Авторы:</h3>
<ul>
<?php foreach ($book->authors as $author): ?>
    <li><?= Html::encode($author->name) ?></li>
<?php endforeach; ?>
</ul>

<p><?= Html::a('Назад к каталогу', ['index'], ['class' => 'btn btn-default']) ?></p>
