<?php
use yii\helpers\Html;

$this->title = 'Мои жалобы';
?>

<h1><?= Html::encode($this->title) ?></h1>

<p><?= Html::a('Создать новую жалобу', ['create'], ['class' => 'btn btn-success']) ?></p>

<table class="table table-bordered">
    <thead>
        <tr>
            <th>Адрес</th>
            <th>Категория</th>
            <th>Описание</th>
            <th>Фото</th> <!-- Добавлена колонка Фото -->
            <th>Статус</th>
            <th>Дата подачи</th>
        </tr>
    </thead>
    <tbody>
       <?php foreach ($complaints as $complaint): ?>
    <tr>
        <td><?= Html::encode($complaint->address) ?></td>
        <td><?= Html::encode($complaint->type) ?></td>
        <td><?= Html::encode($complaint->description) ?></td>
        <td>
            <?php if ($complaint->image): ?>
                <?= Html::img('@web/' . $complaint->image, ['alt' => 'Фото жалобы', 'style' => 'max-width:150px; max-height:100px;']) ?>
            <?php else: ?>
                Нет фото
            <?php endif; ?>
        </td>
        <td><?= Html::encode($complaint->status) ?></td>

        <?php
        switch($complaint->status) {
            case 'Отклонена':
                $badgeClass = 'badge bg-danger';
                break;
            case 'В обработке':
                $badgeClass = 'badge bg-warning text-dark';
                break;
            case 'Решена':
                $badgeClass = 'badge bg-success';
                break;
            default:
                $badgeClass = 'badge bg-secondary';
                break;  
        }
        ?>

        <td>
            <span class="<?= $badgeClass ?>">
                <?= Html::encode($complaint->admin_comment ?: '-') ?>
            </span>
        </td>

        <td><?= Html::encode($complaint->created_at) ?></td>
    </tr>
<?php endforeach; ?>

    </tbody>
</table>
