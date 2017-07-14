<?php
/**
 * Created by PhpStorm.
 * User: Default
 * Date: 12/29/2016
 * Time: 7:05 PM
 */

namespace frontend\controllers;


use common\models\Section;
use yii\web\Controller;
use yii\filters\AccessControl;


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
                    // всё остальное по умолчанию запрещено
                ],
            ],
        ];
    }

    public function actionView($id){
        $section = Section::findOne($id);

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