<?php
namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use yii\data\ActiveDataProvider;
use app\models\Complaint;
use yii\web\Response;
use yii\widgets\ActiveForm;

class AdminController extends Controller
{
    private $adminLogin = 'adminka';
    private $adminPassword = 'password';

    public function behaviors()
{
    return [
        'access' => [
            'class' => \yii\filters\AccessControl::class,
            'only' => ['index', 'update-status', 'update', 'logout'],
            'rules' => [
                [
                    'allow' => true,
                    'roles' => ['@'],
                    'matchCallback' => function () {
                        return Yii::$app->user->identity->isAdmin;
                    }
                ],
            ],
        ],
    ];
}


    /**
     * Вход в админку по фиксированному логину и паролю
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest && Yii::$app->user->identity->username === $this->adminLogin) {
            return $this->redirect(['index']);
        }

        $model = new \yii\base\DynamicModel(['username', 'password']);
        $model->addRule(['username', 'password'], 'required');
        $model->addRule('password', function ($attribute, $params) use ($model) {
            if (!($model->username === $this->adminLogin && $model->password === $this->adminPassword)) {
                $this->addError($attribute, 'Неверный логин или пароль.');
            }
        });

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            // Логиним администратора вручную (без БД)
            $identity = new \app\models\User();
            $identity->username = $this->adminLogin;
            Yii::$app->user->login($identity);
            return $this->redirect(['index']);
        }

        return $this->render('login', ['model' => $model]);
    }

    /**
     * Выход из админки
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();
        return $this->redirect(['login']);
    }

    /**
     * Список жалоб с фильтрами
     */
    public function actionIndex()
{
    $query = Complaint::find();

    $status = Yii::$app->request->get('status');
    $type = Yii::$app->request->get('type');
    $date = Yii::$app->request->get('date');

    if ($status) {
        $query->andWhere(['status' => $status]);
    }
    if ($type) {
        $query->andWhere(['type' => $type]);
    }
    if ($date) {
        $query->andWhere(['DATE(created_at)' => $date]);
    }

    $dataProvider = new ActiveDataProvider([
        'query' => $query->orderBy(['created_at' => SORT_DESC]),
        'pagination' => ['pageSize' => 20],
    ]);

    return $this->render('index', [
        'dataProvider' => $dataProvider,
        'status' => $status,
        'type' => $type,
        'date' => $date,
    ]);
}

public function actionUpdate($id)
{
    $model = Complaint::findOne($id);
    if (!$model) {
        throw new \yii\web\NotFoundHttpException('Жалоба не найдена.');
    }

    if ($model->load(Yii::$app->request->post()) && $model->save()) {
        Yii::$app->session->setFlash('success', 'Жалоба успешно обновлена.');
        return $this->redirect(['index']);
    }

    return $this->render('update', [
        'model' => $model,
    ]);
}



    /**
     * Обновление статуса жалобы (через POST)
     */
    public function actionUpdateStatus($id)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $complaint = Complaint::findOne($id);
        if (!$complaint) {
            return ['success' => false, 'message' => 'Жалоба не найдена'];
        }

        $status = Yii::$app->request->post('status');
        $comment = Yii::$app->request->post('comment');

        if (!in_array($status, [Complaint::STATUS_IN_PROGRESS, Complaint::STATUS_RESOLVED, Complaint::STATUS_REJECTED])) {
            return ['success' => false, 'message' => 'Неверный статус'];
        }

        $complaint->status = $status;
        if ($status === Complaint::STATUS_REJECTED) {
            if (empty($comment)) {
                return ['success' => false, 'message' => 'Причина отклонения обязательна'];
            }
            $complaint->admin_comment = $comment;
        } else {
            $complaint->admin_comment = $comment; // можно оставить комментарий
        }

        if ($complaint->save()) {
            return ['success' => true];
        }

        return ['success' => false, 'message' => 'Ошибка сохранения'];
    }

    public function beforeAction($action)
{
    if (!parent::beforeAction($action)) {
        return false;
    }

    if (!Yii::$app->user->identity->isAdmin) {
        Yii::$app->session->setFlash('error', 'Доступ запрещён.');
        return $this->redirect(['/site/index'])->send();
    }

    return true;
}

}
