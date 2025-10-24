<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Subscription $subscription */
/** @var app\models\Author $author */

$this->title = 'Подписка на автора: ' . $author->name;
?>

<h1><?= Html::encode($this->title) ?></h1>

<?php $form = ActiveForm::begin([
    'action' => ['author/subscribe', 'id' => $author->id],
    'method' => 'post',
]); ?>

<?= $form->field($subscription, 'phone')->textInput(['placeholder' => '+79991234567']) ?>

<div class="form-group">
    <?= Html::submitButton('Подписаться', ['class' => 'btn btn-success']) ?>
</div>

<?php ActiveForm::end(); ?>
<p><?= Html::a('Назад к автору', ['author/view', 'id' => $author->id], ['class' => 'btn btn-default']) ?></p>