<?php

namespace tests\transfer;

use app\modules\transfer\models\LoginForm;
use app\modules\transfer\models\User;
use yii\codeception\DbTestCase;

class LoginFormTest extends DbTestCase
{
    public function testNicknameCanNotBeBlank()
    {
        $model = new LoginForm( [
            'username' => '',
        ]);

        $model->login();

        expect_that($model->getErrors('username'));
        expect($model->getFirstError('username'))
            ->equals('Nickname cannot be blank.');

    }

    public function testNicknameAtLeastMinTwoLetters()
    {
        $model = new LoginForm( [
            'username' => 'a',
        ]);

        $model->login();

        expect_that($model->getErrors('username'));
        expect($model->getFirstError('username'))
            ->equals('Nickname should contain at least 2 characters.');
    }

    public function testAutorizationNicknameAndHimBalanceIsNull()
    {
        $model = new LoginForm( [
            'username' => 'admin',
        ]);

        $model->login();
        expect(\yii::$app->users->identity->username)->equals('admin');

        $user = new User();
        expect($user->balance)->equals(null);
    }

    public function testNicknameIsUnique()
    {
        $this->tester->seeRecord('app\modules\transfer\models\User', array('username' => 'admin'));

        $model = new LoginForm( [
            'username' => 'admin',
        ]);

        $model->login();
        expect(\yii::$app->users->identity->username)->equals('admin');

        $count = User::find()->select(['username'])->where(['username' => 'admin'])->distinct()->count();
        expect($count)->equals(1);
    }

    public function testLogout()
    {
        $model = new LoginForm( [
            'username' => 'user8',
        ]);

        $model->login();

        expect(\Yii::$app->users->isGuest)->equals(false);
        expect(\yii::$app->users->identity->username)->equals('user8');
        expect(\Yii::$app->users->logout())->equals(true);
        expect(\Yii::$app->users->isGuest)->equals(true);    }

    public function testRememberMeBooleanTrue()
    {
        $model = new LoginForm( [
            'rememberMe' => true,
        ]);

        verify($model->rememberMe)->true();

    }

    public function testRememberMeBooleanFalse()
    {
        $model = new LoginForm( [
            'rememberMe' => false,
        ]);

        verify($model->rememberMe)->false();

        $this->DeleteDataWithDb();
    }

    public function DeleteDataWithDb()
    {
        User::deleteAll();
    }

}