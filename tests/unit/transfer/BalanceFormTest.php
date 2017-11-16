<?php

namespace tests\transfer;

use app\modules\transfer\models\BalanceForm;
use tests\unit\fixtures\BalanceFixture;
use yii\codeception\DbTestCase;
use app\modules\transfer\models\User;

class BalanceFormTest extends DbTestCase
{
    /**
     * @var \UnitTester
     */
    protected $tester;



    public function fixtures()
    {
        return [
            BalanceFixture::className(),
        ];
    }

    public function testTheFieldBalanceCanNotBeEmpty()
    {
        $balance = new BalanceForm( [
            'balance' => '',
        ]);

        $balance->validate();

        expect_that($balance->getErrors('balance'));
        expect($balance->getFirstError('balance'))
            ->equals('Balance cannot be blank.');
    }

    public function testBalanceIsNotCorrect()
    {

        $balance = new BalanceForm( [
            'balance' => '1er5yu',
        ]);

        $balance->validate();

        expect_that($balance->getErrors('balance'));
        expect($balance->getFirstError('balance'))
            ->equals('Balance must be an integer.');
    }

    public function testFieldBalanceCanNotBeNegative()
    {

        $balance = new BalanceForm( [
            'balance' => '-200',
        ]);

        $balance->validate();

        expect_that($balance->getErrors('balance'));
        expect($balance->getFirstError('balance'))
            ->equals('Balance can not be negative.');
    }

    public function testBalanceIsCorrect()
    {
        $user = User::findOne(['username' => 'admin']);
        expect($user->balance)->equals(200);

        $balance = new BalanceForm( [
            'balance' => '500',
        ]);

        $balance->updateBalance($user->id);

        $user = User::findOne(['username' => 'admin']);
        expect($user->balance)->equals(700);

    }

    public function testBalanceIsCorrectIfNegative()
    {
        $user = User::findOne(['username' => 'user']);
        expect($user->balance)->equals(-200);

    }
}