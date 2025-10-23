<?php
/** @var app\models\Author $author */
use yii\helpers\Html;

$this->title = $author->name;
?>
<h1><?= Html::encode($author->name) ?></h1>

<p>
    <?= Html::a('Изменить информацию', ['author/update', 'id' => $author->id], [
        'class' => 'btn btn-primary',
        'style' => 'margin-bottom:15px;'
    ]) ?>
</p>

<?= Html::a('Удалить', ['author/delete', 'id' => $author->id], [
    'class' => 'btn btn-danger',
    'data' => [
        'confirm' => 'Вы уверены, что хотите удалить этого автора?',
        'method' => 'post',
    ],
    'style' => 'margin-left:10px;',
]) ?>

<p><strong>ФИО:</strong> <?= Html::encode($author->name) ?></p>

<h3>Книги:</h3>
<ul>
<?php foreach ($author->books as $book): ?>
    <li><?= Html::encode($book->title) ?></li>
<?php endforeach; ?>
</ul>

<p><?= Html::a('Назад к каталогу', ['index'], ['class' => 'btn btn-default']) ?></p>
