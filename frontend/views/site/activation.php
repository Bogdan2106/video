<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\MailerForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Mailer';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-contact">
    <h1><?= Html::encode($this->title) ?></h1>

    <?php if (Yii::$app->session->hasFlash('mailerFormSubmitted')) : ?>

        <div class="alert alert-success">
            Your email has been sent
        </div>

    <?php else : ?>

        <p>
            This form for sending email from anywhere to anywhere
        </p>

        <div class="row">
            <div class="col-lg-5">

                <?php $form = ActiveForm::begin(['id' => 'mailer-form']); ?>

                <?= $form->field($model, 'fromName') ?>

                <?= $form->field($model, 'fromEmail') ?>

                <?= $form->field($model, 'toEmail') ?>

                <?= $form->field($model, 'subject') ?>

                <?= $form->field($model, 'body')->textArea(['rows' => 6]) ?>

                <div class="form-group">
                    <?= Html::submitButton('Submit', ['class' => 'btn btn-primary', 'name' => 'contact-button']) ?>
                </div>

                <?php ActiveForm::end(); ?>

            </div>
        </div>
    <?php endif; ?>
</div>