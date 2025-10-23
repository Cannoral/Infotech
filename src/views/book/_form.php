<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\Author;

/** @var yii\web\View $this */
/** @var app\models\Book $book */
/** @var yii\widgets\ActiveForm $form */
?>

<?php if ($book->hasErrors()): ?>
    <div class="alert alert-danger">
        <?= Html::errorSummary($book) ?>
    </div>
<?php endif; ?>

<div class="book-form">

    <?php $form = ActiveForm::begin([
        'options' => ['enctype' => 'multipart/form-data'],
    ]); ?>

    <?php if ($book->cover): ?>
        <p>Текущая обложка:</p>
        <img src="<?= $book->cover ?>" width="150">
    <?php endif; ?>

    <?= $form->field($book, 'coverFile')->fileInput(['accept' => 'image/*']) ?>

    <?= $form->field($book, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($book, 'year')->textInput(['type' => 'number']) ?>

    <?= $form->field($book, 'isbn')->textInput(['maxlength' => true]) ?>

    <?= $form->field($book, 'description')->textarea(['rows' => 5]) ?>

    <?= $form->field($book, 'authorIds')->listBox(
        ArrayHelper::map(Author::find()->orderBy('name')->all(), 'id', 'name'),
        [
            'multiple' => true,
            'size' => 10,
        ]
    ) ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
