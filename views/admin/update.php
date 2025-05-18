<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Редактирование жалобы #' . $model->id;
?>

<h1><?= Html::encode($this->title) ?></h1>

<div class="complaint-update">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'address')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'type')->dropDownList([
        'Разбитая дорога' => 'Разбитая дорога',
        'Ямы' => 'Ямы',
        'Неисправное освещение' => 'Неисправное освещение',
        'Другое' => 'Другое',
    ], ['prompt' => 'Выберите категорию']) ?>

    <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

    <?php if ($model->image): ?>
        <p>Текущее фото:</p>
        <?= Html::img(Yii::getAlias('@web') . '/' . $model->image, ['style' => 'max-width:200px; max-height:150px;']) ?>
    <?php endif; ?>

    <?= $form->field($model, 'fio')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'phone')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'date')->input('date') ?>

    <?= $form->field($model, 'status')->dropDownList([
        \app\models\Complaint::STATUS_NEW => \app\models\Complaint::STATUS_NEW,
        \app\models\Complaint::STATUS_IN_PROGRESS => \app\models\Complaint::STATUS_IN_PROGRESS,
        \app\models\Complaint::STATUS_RESOLVED => \app\models\Complaint::STATUS_RESOLVED,
        \app\models\Complaint::STATUS_REJECTED => \app\models\Complaint::STATUS_REJECTED,
    ]) ?>

    <?= $form->field($model, 'admin_comment')->textarea(['rows' => 4]) ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
        <?= Html::a('Отмена', ['index'], ['class' => 'btn btn-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
