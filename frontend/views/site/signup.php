<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\SignupForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Signup';
$this->params['breadcrumbs'][] = $this->title;
function activationEmail($user)
{
    echo 'Привет ' . Html::encode($user->username) . '.';

    echo Html::a('Для активации аккаунта перейдите по этой ссылке.',
        Yii::$app->urlManager->createAbsoluteUrl(
            [
                '/main/activate-account',
                'key' => $user->secret_key
            ]
        ));
}

?>
<div class="site-signup">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>Please fill out the following fields to signup:</p>

    <div class="row">
        <div class="col-lg-5">
            <?php $form = ActiveForm::begin(['id' => 'form-signup']); ?>

                <?= $form->field($model, 'username')->textInput(['autofocus' => true]) ?>

                <?= $form->field($model, 'email') ?>

                <?= $form->field($model, 'password')->passwordInput() ?>

                <div class="form-group">
                    <?= Html::submitButton('Signup', ['class' => 'btn btn-primary', 'name' => 'signup-button']) ?>

                </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
