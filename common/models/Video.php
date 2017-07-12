<?php

namespace common\models;

use Yii;
use yii\web\UploadedFile;
use yii\helpers\FileHelper;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "video".
 *
 * @property integer $id
 * @property string $name
 * @property string $path
 * @property string $description
 * @property integer $topic_id
 * @property integer $image_id
 * @property integer $created_at
 * @property integer $created_by
 * @property integer $updated_at
 * @property integer $updated_by
 *
 * @property Like[] $likes
 * @property User[] $users
 * @property Image $image
 * @property Video $videofile
 * @property Topic $topic
 */
class Video extends \yii\db\ActiveRecord
{
    public $videofile;
    public $imageFile;

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at']
                ],
            ],
            'blameable' => [
                'class' => BlameableBehavior::className(),
                'createdByAttribute' => 'created_by',
                'updatedByAttribute' => 'updated_by'
            ],
        ];
    }


    public static function tableName()
    {
        return 'video';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'path', 'description', 'topic_id', 'image_id'], 'required'],
            [['topic_id', 'image_id', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'integer'],
            [['name', 'path', 'description'], 'string', 'max' => 255],
            [['image_id'], 'exist', 'skipOnError' => true, 'targetClass' => Image::className(), 'targetAttribute' => ['image_id' => 'id']],
            [['topic_id'], 'exist', 'skipOnError' => true, 'targetClass' => Topic::className(), 'targetAttribute' => ['topic_id' => 'id']],
            [['videofile'],'file', 'skipOnEmpty' => false, 'extensions' => 'mp4'],
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
            'description' => 'Description',
            'topic_id' => 'Topic ID',
            'image_id' => 'Image ID',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLikes()
    {
        return $this->hasMany(Like::className(), ['video_id' => 'id']);
    }

    public function hasLiked()
    {
        return (bool) Like::findOne([
            'user_id' => Yii::$app->user->id,
            'video_id' => $this->id,
        ]);
    }

    public function like()
    {
        if ($this->hasLiked())
            return true;

        $like = new Like;
        $like->user_id = Yii::$app->user->id;
        $like->video_id = $this->id;

        return $like->save();
    }

    public function dislike()
    {
        if (!$this->hasLiked())
            return true;

        $like = Like::findOne([
            'user_id' => Yii::$app->user->id,
            'video_id' => $this->id,
        ]);

        return $like->delete();
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsers()
    {
        return $this->hasMany(User::className(), ['id' => 'user_id'])->viaTable('like', ['video_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getImage()
    {
        return $this->hasOne(Image::className(), ['id' => 'image_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTopic()
    {
        return $this->hasOne(Topic::className(), ['id' => 'topic_id']);
    }

    public function upload($file, $image)
    {
        $uploadedImage = Image::upload($image,"images/section/" . Section::findOne(Topic::findOne($this->topic_id)->section_id)->slug);
        $this->videofile = $file;
        if (!$this->videofile) return null;
        $folder = $this->topic->slug;
        $path = self::getVideoParentFolderPath();

        $directory = $path . '/' . $folder;
        FileHelper::createDirectory($directory, 0777);
        //echo "$directory/$videoName." . $this->videofile->extension . "<br>";
        $this->videofile->saveAs("$directory/$file->basename." . $this->videofile->extension, false);

        if (!$this->isNewRecord) {
            try{
                unlink($path ."/$this->path");
            }catch (\Exception $exception){

            }
        }

        $this->path = "video/" . $folder . "/$file->basename." . $this->videofile->extension;
        $this->image_id = $uploadedImage->id;
        //die($this->path);


        if ($this->save()) {
            //var_dump($this->id);
            return $this;
        }
        return null;
    }
    public function getCreatedBy($attribute)
    {
        /** @var User $user */
        $user = User::findOne($this->created_by);

        return $user->hasAttribute($attribute) ? $user->{$attribute} : $user->email;
    }

    public static function getVideoParentFolderPath(){
        return Yii::getAlias('@backend/web/video/');
    }

    public static function getVideoParentFolderLink(){
        return Yii::$app->request->hostInfo.'/backend/web/video/';
    }

    public function getUpdatedBy($attribute)
    {
        /** @var User $user */
        $user = User::findOne($this->updated);

        return $user->hasAttribute($attribute) ? $user->{$attribute} : $user->email;
    }

    public function getDate($date)
    {
        return Yii::$app->formatter->asDate($date, 'medium');
    }

}