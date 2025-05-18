<?php
namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use yii\web\UploadedFile;
use app\models\Complaint;

class ComplaintController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['index', 'create'],
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'], // только авторизованные
                    ],
                ],
            ],
        ];
    }

    /**
     * Личный кабинет - список жалоб пользователя
     */
    public function actionIndex()
    {
        $userId = Yii::$app->user->id;
        $complaints = Complaint::find()->where(['user_id' => $userId])->orderBy(['created_at' => SORT_DESC])->all();

        return $this->render('index', [
            'complaints' => $complaints,
        ]);
    }

    /**
     * Создание новой жалобы
     */
    public function actionCreate()
    {
        $model = new Complaint();
        $model->user_id = Yii::$app->user->id;

        // Заполняем контактные данные из профиля пользователя
        $user = Yii::$app->user->identity;
        $model->fio = $user->fio;
        $model->phone = $user->phone;

        if ($model->load(Yii::$app->request->post())) {
            $model->imageFile = UploadedFile::getInstance($model, 'imageFile');
        
            if ($model->validate()) {  // валидируем модель включая файл
                if ($model->imageFile) {
                    $model->upload();  // сохраняем файл, обновляем поле image
                }
                $model->status = Complaint::STATUS_NEW;
                $model->created_at = date('Y-m-d H:i:s');
        
                if ($model->save(false)) {  // сохраняем без повторной валидации
                    Yii::$app->session->setFlash('success', 'Жалоба успешно создана.');
                    return $this->redirect(['index']);
                }
            }
        }
        
        

        return $this->render('create', [
            'model' => $model,
        ]);
    }
}
