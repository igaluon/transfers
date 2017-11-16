<?php

namespace app\modules\transfer\controllers;

use Yii;
use yii\data\Pagination;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\modules\transfer\models\LoginForm;
use app\modules\transfer\models\BalanceForm;
use app\modules\transfer\models\TransferForm;
use app\modules\transfer\models\User;

/**
 * Class SiteController
 * @package app\controllers
 */
class TransferController extends Controller
{
    public $layout = 'transfer';
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        $query = User::find();

        $countQuery = clone $query;
        $pages = new Pagination(['totalCount' => $countQuery->count(), 'pageSize' => 5]);
        $model = $query
            ->offset($pages->offset)
            ->limit($pages->limit)
            ->all();

        return $this->render('index', ['model' => $model, 'pages' => $pages]);
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->redirect(['/transfer/transfer/personal-area'], 301);
        }
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->users->logout();

        return $this->redirect(['index']);
    }

    /**
     * Displays personal area for authorized user.
     * @param $id
     * @return string
     */
    public function actionPersonalArea()
    {
        $id = Yii::$app->users->identity->getId();

        $balanceform = new BalanceForm();

        if ($balanceform->load(Yii::$app->request->post()) && $balanceform->validate()) {
            $balanceform->updateBalance($id);
        }

        $transferform = new TransferForm();

        if ($transferform->load(Yii::$app->request->post()) && $transferform->validate()) {
            $transferform->saveTransfer($id);

        }

        $users = User::findOne($id);

        $query = User::find()->joinWith('transfer t')->where(['t.nickname_id' => $id]);

        $countQuery = clone $query;
        $pages = new Pagination(['totalCount' => $countQuery->count(), 'pageSize' => 3]);
        $model = $query->offset($pages->offset)
            ->limit($pages->limit)
            ->all();

        return $this->render('personalarea',
            [
                'model' => $model,
                'pages' => $pages,
                'users' => $users,
                'balanceform' => $balanceform,
                'transferform' => $transferform,
            ]);
    }

    /**
     * @param $id
     * @return mixed
     * @throws NotFoundHttpException
     */
    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
