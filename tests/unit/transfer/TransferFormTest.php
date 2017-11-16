<?php

namespace tests\transfer;

use app\modules\transfer\models\TransferForm;
use app\modules\transfer\models\User;
use tests\unit\fixtures\TransferFixture;
use yii\codeception\DbTestCase;

class TransferFormTest extends DbTestCase
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    public function fixtures()
    {
        return [
            'user' => TransferFixture::className(),
        ];
    }

    public function testFieldNicknameIsNotRequired()
    {
        $model = new TransferForm( [
            'username' => '',
        ]);

        $model->validate();

        expect_that($model->getErrors('username'));
        expect($model->getFirstError('username'))
            ->equals('Nickname cannot be blank.');
    }

    public function testFieldTransferCanNotBeRequired()

    {
        $this->tester->seeRecord('app\modules\transfer\models\User', array('username' => 'user'));

        $model = new TransferForm( [
            'transfer' => '',
        ]);

        $model->validate();

        expect_that($model->getErrors('transfer'));
        expect($model->getFirstError('transfer'))
            ->equals('Transfer cannot be blank.');
    }

    public function testTransferedMoneyToRegisteredUserIsCorrect()
    {
        $this->tester->seeRecord('app\modules\transfer\models\User', array('username' => 'admin'));
        $this->tester->seeRecord('app\modules\transfer\models\User', array('username' => 'user'));

        $identity = User::findOne(['username' => 'admin']);
        \Yii::$app->user->login($identity);

        $model = new TransferForm( [
            'username' => 'user',
            'transfer' => '10.00',
        ]);

        expect($model->savetransfer($identity->id))->equals(true);

        $admin = User::findOne(['username' => 'admin']);
        $user = User::findOne(['username' => 'user']);

        expect($admin->balance)->equals(190);
        expect($user->balance)->equals(10);
    }

    public function testTransferIsAddedToBalanceOfRegisteredUser()
    {
        $this->tester->seeRecord('app\modules\transfer\models\User', array('username' => 'admin'));
        $this->tester->seeRecord('app\modules\transfer\models\User', array('username' => 'user'));
        $user = User::findOne(['username' => 'user']);
        expect($user->balance)->equals(null);

        $identity = User::findOne(['username' => 'admin']);
        \Yii::$app->user->login($identity);

        $model = new TransferForm( [
            'username' => 'user',
            'transfer' => '10.00',
        ]);

        $model->savetransfer($identity->id);

        $user = User::findOne(['username' => 'user']);

        expect($user->balance)->equals(10);
    }

    public function testTransferFromBalanceOfAuthorizedUser()
    {
        $this->tester->seeRecord('app\modules\transfer\models\User', array('username' => 'admin'));
        $this->tester->seeRecord('app\modules\transfer\models\User', array('username' => 'user'));
        $admin = User::findOne(['username' => 'admin']);
        expect($admin->balance)->equals(200);

        $model = new TransferForm( [
            'username' => 'user',
            'transfer' => '10.00',
        ]);

        $identity = User::findOne(['username' => 'admin']);
        \Yii::$app->users->login($identity);

        $model->savetransfer($identity->id);

        $admin = User::findOne(['username' => 'admin']);

        expect($admin->balance)->equals(190);
    }

    public function testRegisterationOfNewUser()
    {
        $this->tester->seeRecord('app\modules\transfer\models\User', array('username' => 'admin'));
        $this->tester->cantSeeRecord('app\modules\transfer\models\User', array('username' => 'user1'));

        $model = new TransferForm( [
            'username' => 'user1',
            'transfer' => '10.00',
        ]);

        $identity = User::findOne(['username' => 'admin']);
        \Yii::$app->users->login($identity);

        $model->savetransfer($identity->id);

        $this->tester->seeRecord('app\modules\transfer\models\User', array('username' => 'user1'));
    }


    public function testTransferIsAddedToBalanceOfNewUser()
    {
        $this->tester->seeRecord('app\modules\transfer\models\User', array('username' => 'admin'));

        $identity = User::findOne(['username' => 'admin']);
        \Yii::$app->users->login($identity);

        $model = new TransferForm( [
            'username' => 'user1',
            'transfer' => '10.00',
        ]);

        $model->savetransfer($identity->id);

        $user = User::findOne(['username' => 'user1']);

        expect($user->balance)->equals(10);
    }

    public function testTransferToNewUserIsKeptFromBalanceOfAuthorizedUser()
    {
        $this->tester->seeRecord('app\modules\transfer\models\User', array('username' => 'admin'));
        $admin = User::findOne(['username' => 'admin']);
        expect($admin->balance)->equals(200);

        $model = new TransferForm( [
            'username' => 'user',
            'transfer' => '10.00',
        ]);

        $identity = User::findOne(['username' => 'admin']);
        \Yii::$app->users->login($identity);

        $model->savetransfer($identity->id);

        $admin = User::findOne(['username' => 'admin']);

        expect($admin->balance)->equals(190);
    }

    public function testBalanceOfAuthorizedUserCanBeNegative()
    {
        $identity = User::findOne(['username' => 'admin']);
        \Yii::$app->users->login($identity);

        $admin = User::findOne(['username' => 'admin']);
        expect($admin->balance)->equals(200);

        $model = new TransferForm( [
            'username' => 'user',
            'transfer' => '300',
        ]);

        $model->savetransfer($identity->id);

        $admin = User::findOne(['username' => 'admin']);

        expect($admin->balance)->equals(-100);
    }

    public function testCanNotTransferMoneyToYourself()
    {
        $identity = User::findOne(['username' => 'admin']);
        \Yii::$app->users->login($identity);

        $model = new TransferForm( [
            'username' => 'admin',
            'transfer' => '100',
        ]);

        $model->validate();

        expect_that($model->getErrors('username'));
        expect($model->getFirstError('username'))
            ->equals('You can not transfer money to yourself.');
    }
}
