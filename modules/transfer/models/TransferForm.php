<?php

namespace app\modules\transfer\models;

use Yii;
use yii\base\Model;

/**
 * This is the model class for table "user".
 *
 * @property double $transfer
 */
class TransferForm extends Model
{
    public $username;
    public $transfer;
    public $username_id;
    public $nickname_id;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username'], 'required', 'message' => 'Nickname cannot be blank.'],
//            [['username'],'match', 'pattern' => '#^[\w_-]+$#is', 'message' => 'Nickname is not valid.'],
            ['username', 'string', 'min' => 2,'max' => 32],
            [['username'], 'notself'],
            [['transfer'], 'required'],
            [['transfer'], 'match', 'pattern' => '#^\\d+(\\.\\d+)?$#', 'message' => 'Transfer must be integer.'],
            [['username_id', 'nickname_id'], 'integer'],
        ];
    }

    /**
     * @return bool|void
     */
    public function notself()
    {
        if (\yii::$app->users->identity->username !== $this->username){
            return true;
        }else{
            return  $this->addError('username', 'You can not transfer money to yourself.');
        }
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'transfer' => 'Transfer',
            'username' => 'Nickname',

        ];
    }

    /**
     * @param $id
     * @return bool
     */
    public function saveTransfer($id)
    {
        if ($this->validate()) {
            $nickname = new User();
            if (!$this->username instanceof User) {
                $nickname->username = $this->username;
                $nickname->auth_key = Yii::$app->security->generateRandomString();
                $nickname->save();
            }
            $users = User::findOne(['username' => $this->username]);
            $users->balance = $users->balance + $this->transfer;
            $users->update(false);
            $id_user = $users->id;
            $transfer = new Transfer();
            $transfer->transfer = $this->transfer;
            $transfer->username_id = $id_user;
            $transfer->nickname_id = Yii::$app->users->identity->id;
            $transfer->save();
            $user =  User::findOne($id);
            $user->balance = $user->balance - $this->transfer;
            $user->save(false);
            return true;
        }
        return false;
    }
}
