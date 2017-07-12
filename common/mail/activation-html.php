<?php
use yii\helpers\Html;
//use common\models\User;

/* @var $this yii\web\View */
/* @var $user common\models\User */

$verifyLink = Yii::$app->urlManager->createAbsoluteUrl(['site/verify?key=' . $user->secret_key]);
?>
<div class="activation">
    <p>Hello <?= Html::encode($user->username) ?>,</p>

    <p>Please activate your account by clicking on the link below:</p>

    <p><?= Html::a(Html::encode($verifyLink), $verifyLink) ?></p>

    <p>If you did not register on this resource, then ignore this email.</p>
</div>