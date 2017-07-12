<?php

/* @var $this yii\web\View */

$this->title = 'Test';

/** @var  $user */
$user = Yii::$app->user->identity;

$sections = \common\models\Topic::getActiveSectionArray();

?>
<div class="site-index">
    <div class="body-content">

        <div class="row">
            <h1 align="center">List sections</h1>

            <?php
            /** @var \common\models\Section $section */
            foreach ($sections as $section){
                ?>

            <div class="col-lg-4">

            <img src="/backend/web/<?= $section->image->path ?>" alt=" " style="height: 150px; width: 150px;">

                <h2><?= $section->name ?></h2>

                <p><a class="btn btn-default" href="<?= \yii\helpers\Url::to(["/section/$section->id"]) ?>" >View</a></p>
            </div>

            <?php
            }
            ?>

        </div>

    </div>
</div>
