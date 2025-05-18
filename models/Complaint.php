<?php
namespace app\models;

use yii\db\ActiveRecord;
use yii\web\UploadedFile;

class Complaint extends ActiveRecord
{
    /**
     * Для загрузки файла
     * @var UploadedFile
     */
    public $imageFile;

    const STATUS_NEW = 'Новая';
    const STATUS_IN_PROGRESS = 'В обработке';
    const STATUS_RESOLVED = 'Решена';
    const STATUS_REJECTED = 'Отклонена';

    public static function tableName()
    {
        return 'complaints';
    }

    public function rules()
    {
        return [
            [['address', 'type', 'description', 'fio', 'phone'], 'required'],
            [['description'], 'string', 'max' => 1000],
            [['address', 'fio', 'phone'], 'string', 'max' => 255],
            ['type', 'in', 'range' => ['Разбитая дорога', 'Ямы', 'Неисправное освещение', 'Другое']],
            [['date'], 'safe'],
            [['imageFile'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg, jpeg'],
            [['user_id'], 'integer'],
            [['status'], 'string', 'max' => 50],
            [['admin_comment'], 'string'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'address' => 'Адрес проблемы',
            'type' => 'Категория проблемы',
            'description' => 'Описание проблемы',
            'imageFile' => 'Фото (опционально)',
            'fio' => 'ФИО',
            'phone' => 'Телефон',
            'date' => 'Желаемая дата подачи жалобы',
            'status' => 'Статус',
            'admin_comment' => 'Комментарий администратора',
        ];
    }

    /**
     * Сохраняет загруженный файл и обновляет поле image
     */
    public function upload()
{
    if ($this->imageFile) {
        $uploadPath = \Yii::getAlias('@webroot') . '/uploads/';
        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0777, true);
        }
        $fileName = uniqid() . '.' . $this->imageFile->extension;
        $fullPath = $uploadPath . $fileName;

        if ($this->imageFile->saveAs($fullPath)) {
            $this->image = 'uploads/' . $fileName;
            return true;
        }
    }
    return false;
}



}
