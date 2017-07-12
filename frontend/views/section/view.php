<?php
use yii\helpers\Html;
use common\models\User;

/* @var $this yii\web\View */

$this->title = 'My Yii Application';

/** @var User $user */
$user = Yii::$app->user->identity;

?>
<div class="site-index">
    <div class="body-content">

        <div class="row">
            <?php
            /** @var \common\models\Section $section */
            foreach ($topics as $topic){
                ?>

                <div class="col-lg-4">
                    <h2><?= $topic->name ?></h2>
                    <img src="/backend/web/<?= $section->image->path ?>" alt=" " style="height: 150px; width: 150px;">
                    <p><a class="btn btn-default" href="<?= \yii\helpers\Url::to(['/topic/' . $topic->id]) ?>" >View</a></p>
                </div>
                <?php
            }
            ?>
        </div>
    </div>
</div>
