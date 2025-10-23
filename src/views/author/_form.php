<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\Author;

/** @var yii\web\View $this */
/** @var app\models\Author $author */
/** @var yii\widgets\ActiveForm $form */
?>

<?php if ($author->hasErrors()): ?>
    <div class="alert alert-danger">
        <?= Html::errorSummary($author) ?>
    </div>
<?php endif; ?>

<div class="author-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($author, 'name')->textInput(['maxlength' => true]) ?>
    
    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
