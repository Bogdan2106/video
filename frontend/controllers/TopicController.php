<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 29.06.2017
 * Time: 14:20
 */

namespace frontend\controllers;


use common\models\Topic;
use common\models\Video;
use yii\web\Controller;
use yii\web\BadRequestHttpException;


class TopicController extends Controller
{
    public function actionView($id)
    {
        $topic = Topic::findOne($id);

        if (!$topic) {
            throw new BadRequestHttpException('no topic'); // change
        }

        if (!\Yii::$app->user->identity->hasAccessFor($topic->section)) {
            throw new BadRequestHttpException('You don`t have access to this video');
        }

        $video = Video::findAll(['topic_id' => $topic->id,]);
        return $this->render('view', ['topic' => $topic, 'video' => $video]);
    }
}