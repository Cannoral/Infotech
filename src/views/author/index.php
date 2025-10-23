<?php
/** @var app\models\Author[] $authors */
use yii\helpers\Html;

$this->title = 'Авторы';
?>
<h1><?= Html::encode($this->title) ?></h1>

<p>
    <?= Html::a('Добавить автора', ['author/create'], [
        'class' => 'btn btn-primary',
        'style' => 'margin-bottom:15px;'
    ]) ?>
</p>

<ul>
<?php foreach ($authors as $author): ?>
    <li>
        <?= Html::a(Html::encode($author->name), ['view', 'id' => $author->id]) ?>
    </li>
<?php endforeach; ?>
</ul>
