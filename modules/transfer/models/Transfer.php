<?php

namespace app\modules\transfer\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "transfer".
 *
 * @property integer $id
 * @property integer $username_id
 * @property integer $nickname_id
 * @property double $transfer
 *
 * @property User $username
 */
class Transfer extends \yii\db\ActiveRecord
{
    public $username;

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'transfer';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username_id', 'nickname_id'], 'integer'],
            [['username', 'transfer'], 'string'],
            [['username_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['username_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username_id' => 'Username',
            'transfer' => 'Transfer',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'username_id']);
    }

}
