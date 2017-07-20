<?php

namespace common\models;

use common\models\Image;
use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\UploadedFile;

/**
 * This is the model class for table "section".
 *
 * @property integer $id
 * @property string $name
 * @property string $slug
 * @property integer $status
 * @property integer $image_id
 * @property integer $created_at
 * @property integer $created_by
 * @property integer $updated_at
 * @property integer $updated_by
 *
 * @property Image $image
 * @property Subscription[] $subscriptions
 * @property User[] $users
 * @property Topic[] $topics
 */
class Section extends \yii\db\ActiveRecord
{

    const STATUS_ACTIVE = 10;
    const STATUS_INV = 5;
    const STATUS_DELETED = 0;

    public $imageFile;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'section';
    }

    public function __toString()
    {
        return (string)$this->name;
    }

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

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'slug'], 'required'],
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            [['status', 'image_id', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'integer'],
//            [['imageFile'], 'file', 'skipOnEmpty' => false, 'extensions' => 'png, jpg'],
            [['name', 'slug'], 'string'],
            [['name', 'slug'], 'unique'],
            [['image_id'], 'exist', 'skipOnError' => false, 'targetClass' => Image::className(), 'targetAttribute' => ['image_id' => 'id']],
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
            'slug' => 'Slug',
            'status' => 'Status',
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
    public function getImage()
    {
        return $this->hasOne(Image::className(), ['id' => 'image_id']);
    }


    public function getStatusText()
    {
        switch ($this->status) {
            case self::STATUS_DELETED:
                return 'Deleted';
            case self::STATUS_INV:
                return 'Invisible';
            case self::STATUS_ACTIVE:
                return 'Active';
        }
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSubscriptions()
    {
        return $this->hasMany(Subscription::className(), ['section_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsers()
    {
        return $this->hasMany(User::className(), ['id' => 'user_id'])->viaTable('subscription', ['section_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTopics()
    {
        return $this->hasMany(Topic::className(), ['section_id' => 'id']);
    }

    public function getCreatedBy($attribute)
    {
        /** @var User $user */
        $user = User::findOne($this->created_by);

        return $user->hasAttribute($attribute) ? $user->{$attribute} : $user->email;
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

    public function addUser(User $user)
    {

        $subscription = new Subscription();

        $subscription->load(['Subscription' => [
            'user_id' => $user->id,
            'section_id' => $this->id
        ]]);

        return $subscription->save();
    }

    public function uploadImage()
    {
        /** Берем файл из модели */
        $imageFile = UploadedFile::getInstance($this, 'imageFile');
        if ($imageFile == null)
            return false;
        /** Пользуемся своей функцией для аплоада картинки, получаем обьект картинки */
        if ($image = Image::upload($imageFile, "images/section/$this->slug", $this->image ? $this->image->id : null)) {
            $this->image_id = $image->id;
            return true;
        }

        return false;
    }

//    /** Safe delete */
    public function delete()
    {
        /**
         * Section delete
         */
        $this->status = self::STATUS_DELETED;

        /**
         * Image delete
         */
        return $this->save();
    }

}
