<?php
use app\models\Complaint; 
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\ActiveForm;

$this->title = 'Панель администратора';
?>

<h1><?= Html::encode($this->title) ?></h1>

<div>
<form method="get" action="<?= \yii\helpers\Url::toRoute(['admin/index'], true) ?>" class="form-inline mb-3">
        <input type="text" name="type" placeholder="Категория" value="<?= Html::encode($type) ?>" class="form-control mr-2" />
        <input type="date" name="date" value="<?= Html::encode($date) ?>" class="form-control mr-2" />
        <select name="status" class="form-control mr-2">
            <option value="">Все статусы</option>
            <option value="<?= Complaint::STATUS_NEW ?>" <?= $status == Complaint::STATUS_NEW ? 'selected' : '' ?>>Новая</option>
            <option value="<?= Complaint::STATUS_IN_PROGRESS ?>" <?= $status == Complaint::STATUS_IN_PROGRESS ? 'selected' : '' ?>>В обработке</option>
            <option value="<?= Complaint::STATUS_RESOLVED ?>" <?= $status == Complaint::STATUS_RESOLVED ? 'selected' : '' ?>>Решена</option>
            <option value="<?= Complaint::STATUS_REJECTED ?>" <?= $status == Complaint::STATUS_REJECTED ? 'selected' : '' ?>>Отклонена</option>
        </select>
        <button type="submit" class="btn btn-primary">Фильтровать</button>
    </form>
</div>


<div class="table-responsive">
<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'columns' => [
        'address',
        'type',
        'description:ntext',
        [
            'attribute' => 'image',
            'format' => 'html',
            'value' => function($model) {
                if ($model->image) {
                    return Html::img(Yii::getAlias('@web') . '/' . $model->image, ['style' => 'max-width:100px; max-height:100px;']);
                }
                return '(нет изображения)';
            },
            'label' => 'Фото',
        ],
        [
            'attribute' => 'status',
            'format' => 'html',
            'value' => function($model) {
                switch ($model->status) {
                    case Complaint::STATUS_REJECTED:
                        $class = 'badge bg-danger';
                        break;
                    case Complaint::STATUS_IN_PROGRESS:
                        $class = 'badge bg-warning text-dark';
                        break;
                    case Complaint::STATUS_RESOLVED:
                        $class = 'badge bg-success';
                        break;
                    case Complaint::STATUS_NEW:
                    default:
                        $class = 'badge bg-secondary';
                        break;
                }
                return Html::tag('span', Html::encode($model->status), ['class' => $class]);
            },
            'label' => 'Статус',
        ],
        'admin_comment:ntext',
        'created_at',
        [
            'class' => 'yii\grid\ActionColumn',
            'template' => '{update}',
            'buttons' => [
                'update-status' => function ($url, $model) {
                    return Html::a('Изменить статус', ['admin/update-status', 'id' => $model->id], [
                        'class' => 'btn btn-sm btn-warning',
                        'data-method' => 'post',
                        'data-params' => ['status' => Complaint::STATUS_IN_PROGRESS],
                    ]);
                },
            ],
        ],        
    ],
]); ?>

</div>


<style>
    @media (max-width: 576px) {
    .grid-view table thead th:nth-child(3),
    .grid-view table tbody td:nth-child(3),
    .grid-view table thead th:nth-child(4),
    .grid-view table tbody td:nth-child(4),
    .grid-view table thead th:nth-child(6),
    .grid-view table tbody td:nth-child(6) {
        display: none;
    }
}

</style>