<?php

namespace common\models;

use Yii;
use yii\helpers\FileHelper;
use yii\helpers\Url;
use yii\web\UploadedFile;

/**
 * This is the model class for table "image".
 *
 * @property integer $id
 * @property string $name
 * @property string $path
 * @property integer $status
 * @property integer $created_at
 * @property integer $created_by
 * @property integer $updated_at
 * @property integer $updated_by
 *
 * @property Section[] $sections
 * @property Video[] $videos
 */
class Image extends \yii\db\ActiveRecord
{

    const STATUS_ACTIVE = 10;
    const STATUS_INV = 5;
    const STATUS_DELETED = 0;

    public $status;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'image';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'path'], 'required'],
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            [['created_at', 'created_by', 'updated_at', 'updated_by'], 'integer'],
            [['name'], 'string', 'max' => 100],
            [['path'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'path' => 'Path',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSections()
    {
        return $this->hasMany(Section::className(), ['image_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVideos()
    {
        return $this->hasMany(Video::className(), ['image_id' => 'id']);
    }

    /**
     * @param $file UploadedFile Сюда мы передадим обьект файла
     * @param $folder string Имя папки, в которую мы загрузим файл
     * @param null $id int Если нам нужно обновить картинку, а не загрузить новую, мі указіваем ИД картинки в БД. По умолчанию null
     * @return Image|null
     */
    public static function upload($file, $folder, $id = null)
    {

        $imageName = time() . uniqid('', false);

        //берем полный путь папки в которую юудем загружать картинки
        $path = self::getImageParentFolderPath();

        /** сохраняем картинку */

        $directory = $path . $folder;
        FileHelper::createDirectory($directory, 0777);
        $file->saveAs("$directory/$imageName." . $file->extension);

        if (!$id) {
            $modelImage = new Image();
        } else {
            $modelImage = Image::findOne($id);
            try{
                unlink($path ."/$modelImage->path");
            }catch (\Exception $exception){
                //log
            }
        }

        /** делаем необходинмые манипуляции с обьектом картинки  */
        $modelImage->name = $file->baseName;
        $modelImage->path = $folder . "/$imageName." . $file->extension;
        if($modelImage->save()){
            return $modelImage;
        }

        return null;
    }

    public static function getImageParentFolderPath(){
        return Yii::getAlias('@backend/web/');
    }

    public static function getImagesParentFolderLink(){
        return Yii::$app->request->hostInfo.'/backend/web/';
    }


    public function delete(){
        /**
         * Delete image from file system
         */
        try{
            unlink(self::getImageParentFolderPath().$this->path);
        }catch (\Exception $exception){
            //log
        }

        parent::delete();
    }
}
