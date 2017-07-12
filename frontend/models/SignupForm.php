<?php
namespace frontend\models;

use yii\base\Model;
use common\models\User;
use Yii;
use yii\helpers\Html;

/**
 * Signup form
 */
class SignupForm extends Model
{
    public $username;
    public $email;
    public $password;
    public $status;
    public $secret_key;


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['username', 'trim'],
            ['username', 'required'],
            ['username', 'unique', 'targetClass' => '\common\models\User', 'message' => 'This username has already been taken.'],
            ['username', 'string', 'min' => 2, 'max' => 255],

            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => '\common\models\User', 'message' => 'This email address has already been taken. Эта почта уже занята.'],

          ['status', 'default', 'value' => User::STATUS_NOT_ACTIVE, 'on' => 'emailActivation'],

            ['password', 'required'],
            ['password', 'string', 'min' => 6],
        ];
    }

    /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function signup()
    {
        if (!$this->validate()) {
            return null;
        }

        /**
         * SQL: SELECT * FROM 'user' ;
         * Вытаскиваем всех пользователей с базы
         */
        $userExists = User::find()->all();

        
        $user = new User();
        $user->username = $this->username;
        $user->email = $this->email;

        /** если в базе есть пользователи - присваиваем роль обычного пользователя, если нет - админа  */
        $user->role = $userExists ? User::ROLE_USER : User::ROLE_ADMIN;

        //$user->status = $this->status;
        $user->setPassword($this->password);
        $user->generateAuthKey();
        //    if ($this->scenario === 'emailActivation')
        $user->generateSecretKey(); // генерация секретного ключа
        $user->status = User::STATUS_NOT_ACTIVE;

        $isSaved = $user->save() ? $user : null;

        if ($isSaved != null){
            $this->sendEmail($isSaved);
        }
        return $isSaved;
    }

    public function sendEmail($user)
    {
        Yii::$app
            ->mailer
            ->compose(
                ['html' => 'activation-html'],//, 'text' => 'passwordResetToken-text'
                ['user' => $user]
            )
            ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name . ' robot'])
            ->setTo($this->email)
            ->setSubject('Account verification ' . Yii::$app->name)
            ->send();
        Yii::$app->session->setFlash('success', 'Check your email for further instructions.');
        return true;
    }
}
