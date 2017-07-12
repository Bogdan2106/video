<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Video */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="video-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'description')->textInput(['maxlength' => true]) ?>

<!--    --><?//= $form->field($model, 'path')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'topic_id')->dropDownList(
        \yii\helpers\ArrayHelper::map(\common\models\Topic::getActiveTopicArray(),'id','name'),
        ['prompt'=>'Select topic']
    ) ?>
    <?= $form->field($model, 'imageFile')->fileInput(['accept' => 'image/*'])->label('Image') ?>

    <?= $form->field($model, 'videofile')->fileInput(['accept' => 'video/*'])->label('Video') ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update',
            ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
