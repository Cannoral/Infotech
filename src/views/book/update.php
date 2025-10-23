<?php
use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\Book $book */

$this->title = 'Редактировать: ' . $book->title;
?>

<h1><?= Html::encode($this->title) ?></h1>

<?= $this->render('_form', [
    'book' => $book,
]) ?>
