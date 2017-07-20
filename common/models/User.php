<?php
namespace common\models;

use Symfony\Component\EventDispatcher\Event;
use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use yii\helpers\Html;

//use common\components\email;


/**
 * User model
 *
 * @property integer $id
 * @property string $username
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property string $auth_key
 * @property integer $status

 * @property integer $role
 * @property string $secret_key
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $password write-only password
 */
class User extends ActiveRecord implements IdentityInterface
{
    const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 10;
    const STATUS_NOT_ACTIVE = 2;

    const ROLE_ADMIN = 1;
    const ROLE_USER = 0;
   // const ROLE_STUDENT = 55;
    //const ROLE_UNIVERSITY = 66;

    public $password;
    public $section;


    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user}}';
    }


    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID'
        ];
    }


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['username', 'trim'],
            ['username', 'string', 'max' => 50],
            ['username', 'unique','targetClass' => '\common\models\User', 'message' => 'This name is taken.'],

            ['email', 'trim'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => '\common\models\User', 'message' => 'This email address is taken.'],

            ['status', 'default', 'value' => self::STATUS_ACTIVE],

            ['section', 'required'],

            ['role', 'default', 'value' => self::ROLE_USER],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_NOT_ACTIVE, self::STATUS_DELETED]],
        ];
    }


    public function getStatusText()
    {
        switch ($this->status) {
            case self::STATUS_DELETED:
                return 'Deleted';
            case self::STATUS_ACTIVE:
                return 'Active';
            case self::STATUS_NOT_ACTIVE:
                return 'Not active';
        }
    }
    /**
     * @inheritdoc
     */

    public function getRoleText()
    {
        switch ($this->role) {
            case self::ROLE_ADMIN:
                return 'Admin';
            case self::ROLE_USER:
                return 'User';
//            case self::ROLE_STUDENT:
//                return 'Student';
        }
    }


    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username, 'status' => self::STATUS_ACTIVE]);
    }

    public static function findByUsernameAndEmail($value)
    {
        return static::find()->where(
            ['or',
                ['username' => $value],
                ['email' => $value]
            ])
            ->andWhere(['status' => self::STATUS_ACTIVE])
            ->one();
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
            'status' => self::STATUS_ACTIVE,
        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return bool
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int)substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        return $timestamp + $expire >= time();
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    public static function findBySecretKey($key)
    {
        if (!static::isSecretKeyExpire($key)) {
            Yii::$app->session->setFlash('error', 'Key expired');
            return null;
        }
        return static::findOne([
            'secret_key' => $key,
            'status' => self::STATUS_NOT_ACTIVE,
        ]);
    }

    /* Хелперы */
    public function generateSecretKey()
    {
        $this->secret_key = Yii::$app->security->generateRandomString() . '_' . time();
    }

    public function removeSecretKey()
    {
        $this->secret_key = null;
    }

    public static function isSecretKeyExpire($key)
    {
        if (empty($key)) {
            return false;
        }
        $expire = Yii::$app->params['secretKeyExpire'];
        $parts = explode('_', $key);
        $timestamp = (int)end($parts);
        return $timestamp + $expire >= time();
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }

    public function addSection(Section $section)
    {
        $subscriptionExists = Subscription::findOne([
            'user_id' => $this->id,
            'section_id' => $section->id
        ]);

        if (!$subscriptionExists) {
            $subscription = new Subscription();

            $subscription->load(['Subscription' => [
                'user_id' => $this->id,
                'section_id' => $section->id
            ]]);

            return $subscription->save();
        }

        return true;
    }

    public function deleteSection(Section $section)
    {
        $subscription = Subscription::findOne([
            'user_id' => $this->id,
            'section_id' => $section->id
        ]);

        if ($subscription) {
            return $subscription->delete();
        }

        return true;
    }

    public function hasAccessFor(Section $section)
    {

        $subscription = Subscription::findOne([
            'user_id' => $this->id,
            'section_id' => $section->id
        ]);

        return $subscription ? true : false;
    }

    public function getAvailableSections()
    {

        return Section::find()
            ->joinWith('users')
            ->where([User::tableName() . '.id' => $this->id])
            ->all();
        /*$myid = $this->id;
        $myid = $myid;
        $jjj = Subscription::findAll(['user_id' => $myid]);
        $jjj = $jjj;
        return $jjj;*/
    }

    public static function getActiveTopicArray()
    {
        return Topic::findAll(['status' => Topic::STATUS_ACTIVE]);
    }

}
