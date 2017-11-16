<?php

use yii\helpers\Html;
use yii\widgets\LinkPager;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $model app\models\User */
/* @var $balance app\models\BalanceForm */
/* @var $transfer app\models\TransferForm */
/* @var $users app\models\User */

$this->title = 'Personal area';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="operations-index">
    <div class="container-fluid">
        <div class="row">
            <div class="col-xs-5">
                <h1><?= Html::encode($this->title). ' '. '<span style="color: rgb(6, 18, 110)">' .Yii::$app->users->identity->username. '</span>' ?></h1>
            </div>
            <div class="col-xs-7"><br><br>
                <b>Balance </b>
                <span style="background: #000000;color: rgb(255, 255, 255);padding: 5px;"><?= $users->balance<0 ? '-' : '' ?>$<?= $users->balance ? abs($users->balance) : 0 ?></span>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-4">
                <h4>History transfers</h4>
            </div>
            <div class="col-xs-4">
                <h4>Change the balance $</h4>
            </div>
            <div class="col-xs-4">
                <h4>Money transfer $</h4>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-4 well">
                <div class="table-responsive">
                    <table class="table table-striped ">
                        <thead>
                        <tr>
                            <th>Transfer</th>
                            <th>Nickname</th>
                            <th>Date</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($model as $values):?>
                            <tr>
                            <?php $transfers = $values->transfer?>
                            <?php foreach ($transfers as $value):?>
                                <td>$<?= Html::encode($value->transfer) ? Html::encode($value->transfer) : 0 ?></td>
                                <td><?= Html::encode($values->username)?></td>
                                <td><?= Html::encode((Yii::$app->formatter->asDate($values->updated_at, 'd-M-Y')))?></td>
                                </tr>
                            <?php endforeach ?>
                        <?php endforeach ?>
                        </tbody>
                    </table>
                </div>
                <div style="align-content: center">
                    <?= LinkPager::widget([
                        'pagination' => $pages,
                    ]);
                    ?>
                </div>
            </div>
            <div class="col-xs-4 well">
                <div class="site-operations">

                    <?php $form = ActiveForm::begin(); ?>

                    <?= $form->field($balanceform, 'balance')->textInput(['placeholder' => 'ex. 100']) ?>

                    <div class="form-group">
                        <?= Html::submitButton('Submit', ['class' => 'btn btn-primary']) ?>
                    </div>
                    <?php ActiveForm::end(); ?>

                </div>
            </div>
            <div class="col-xs-4 well">
                <div class="site-operations">

                    <?php $form = ActiveForm::begin(); ?>

                    <?= $form->field($transferform, 'username') ?>

                    <?= $form->field($transferform, 'transfer')->textInput(['placeholder' => 'ex. 10 or 10.00']) ?>

                    <div class="form-group">
                        <?= Html::submitButton('Submit', ['class' => 'btn btn-primary']) ?>
                    </div>
                    <?php ActiveForm::end(); ?>

                </div>
            </div>
        </div>
    </div>


