<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\User;

/* @var $this yii\web\View */
/* @var $model common\models\Topic */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="topic-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]) ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'slug')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'description')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'status')->dropDownList([
        \common\models\Topic::STATUS_ACTIVE => 'Active',
        \common\models\Topic::STATUS_INV => 'Invisible',
        \common\models\Topic::STATUS_DELETED => 'Deleted',
    ])
        ?>

    <?= $form->field($model, 'section_id')->dropDownList(
        \yii\helpers\ArrayHelper::map(\common\models\Topic::getActiveSectionArray(),'id','name'),
        ['prompt'=>'Select section']
    ) ?>

<!--    --><?//= $form->field($model, 'videoFile')->fileInput(['multiple' => true, 'accept' => 'video/*'.$model->slug])->label('Video') ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
