<?php

namespace app\modules\transfer\models;

use yii\db\ActiveRecord;
use yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "{{%user}}".
 *
 * @property integer $id
 * @property string $username
 * @property double $balance
 * @property string $auth_key
 * @property integer $created_at
 * @property integer $updated_at
 */
class User extends ActiveRecord implements \yii\web\IdentityInterface
{

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
        return '{{%users}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['username', 'unique'],
            ['username','match', 'pattern' => '#^[\w_-]+$#is'],
            ['balance', 'string'],
//            ['username','match', 'pattern' => '#^[\w_-]+$#is'],
            ['username', 'string', 'min' => 2,'max' => 32],
//            ['username', 'string', 'min' => 2, 'max' =>32],
            ['auth_key', 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'username' => 'Nickname',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTransfer()
    {
        return $this->hasMany(Transfer::className(), ['username_id' => 'id']);

    }
        /**
     * @param int|string $id
     * @return yii\web\IdentityInterface|static
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id]);
    }

    /**
     * @param mixed $token
     * @param null $type
     * @return void|yii\web\IdentityInterface
     * @throws \yii\base\NotSupportedException
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new yii\base\NotSupportedException('findIdentityByAccessToken is not implemented.');
    }

    /**
     * @return string
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @param string $authKey
     * @return bool
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * @param $nickname
     * @return User|array|null|ActiveRecord
     */
    public static function findByNickName($nickname)
    {
        $user = static::find()->where(['username' => $nickname])->one();
        if($user instanceof User){
            return $user;
        }else {
            $user = new User();
            $user->username = $nickname;
            $user->generateAuthKey();
            if($user->save())
            {
                return $user;
            }
            return null;
        }
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }
    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->id;
    }

}
