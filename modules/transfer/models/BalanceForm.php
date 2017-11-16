<?php

namespace app\modules\transfer\models;

use Yii;
use yii\base\Model;

/**
 * This is the model class for table "user".
 *
 * @property integer $balance
 */
class BalanceForm extends Model
{
    public $balance;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['balance'], 'integer', 'message' => 'Balance must be an integer.'],
            [['balance'], 'match', 'pattern' => '#^\\d+$#', 'message' => 'Balance can not be negative.'],
            [['balance'], 'required'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [

            'balance' => 'Balance',

        ];
    }

    /**
     * @param $id
     * @return bool
     */
    public function updateBalance($id)
    {
        if ($this->validate()) {
            $balance = User::findOne($id);
            $balance->balance = (float)$this->balance + $balance->balance;
            $balance->update(false);
            return true;
        }
        return false;
    }
}
