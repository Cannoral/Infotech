<?php
use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Author $author */

$this->title = 'Добавить автора';
?>

<h1><?= Html::encode($this->title) ?></h1>

<?= $this->render('_form', [
    'author' => $author,
]) ?>
