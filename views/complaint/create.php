<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Создать жалобу';
?>

<h1><?= Html::encode($this->title) ?></h1>

<?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

<?= $form->field($model, 'address')->textInput(['maxlength' => true]) ?>

<?= $form->field($model, 'type')->dropDownList([
    'Разбитая дорога' => 'Разбитая дорога',
    'Ямы' => 'Ямы',
    'Неисправное освещение' => 'Неисправное освещение',
    'Другое' => 'Другое',
], ['prompt' => 'Выберите категорию']) ?>

<?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

<?= $form->field($model, 'imageFile')->fileInput() ?>

<?= $form->field($model, 'fio')->textInput(['maxlength' => true]) ?>

<?= $form->field($model, 'phone')->textInput(['maxlength' => true]) ?>

<?= $form->field($model, 'date')->input('date') ?>

<div class="form-group">
    <?= Html::submitButton('Отправить', ['class' => 'btn btn-primary']) ?>
</div>

<?php ActiveForm::end(); ?>
