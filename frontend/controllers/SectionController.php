<?php
/**
 * Created by PhpStorm.
 * User: Default
 * Date: 12/29/2016
 * Time: 7:05 PM
 */

namespace frontend\controllers;


use common\models\Section;

use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\BadRequestHttpException;

class SectionController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['view'],
                'rules' => [
                    // разрешаем аутентифицированным пользователям
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],

                ],
            ],
        ];
    }

    public function actionView($id){
        $section = Section::findOne($id);

        if (!$section) {
            throw new BadRequestHttpException('no $section'); // change
        }

        if (!\Yii::$app->user->identity->hasAccessFor($section)) {
            throw new BadRequestHttpException('You don`t have access to this $section');
        }

        $topics = $section->getTopics()->all();

        return $this->render('view', [
            'topics' => $topics,
            'section' => $section
        ]);
    }

    public function actionTopic($id){
        return $this->redirect(["/topic/" . $id]);
    }
}