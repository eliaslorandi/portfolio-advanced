<?php

namespace common\models;

use Yii;
use yii\imagine\Image;
use yii\web\UploadedFile;

/**
 * This is the model class for table "testimonial".
 *
 * @property int $id
 * @property int $project_id
 * @property int $customer_image_id
 * @property string $title
 * @property string $customer_name
 * @property string $review
 * @property int $rating
 *
 * @property File $customerImage
 * @property Project $project
 */
class Testimonial extends \yii\db\ActiveRecord
{


    /**
     * @var UploadedFile
     */
    public $imageFile;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'testimonial';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['project_id', 'customer_image_id', 'title', 'customer_name', 'review', 'rating'], 'required'],
            [['project_id', 'customer_image_id', 'rating'], 'integer'],
            [['review'], 'string'],
            [['title', 'customer_name'], 'string', 'max' => 255],
            [['project_id'], 'exist', 'skipOnError' => true, 'targetClass' => Project::class, 'targetAttribute' => ['project_id' => 'id']],
            [['imageFile'], 'file', 'skipOnEmpty' => false, 'extensions' => 'png, jpg, jpeg',]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'project_id' => Yii::t('app', 'Project ID'),
            'customer_image_id' => Yii::t('app', 'Customer Image ID'),
            'title' => Yii::t('app', 'Title'),
            'customer_name' => Yii::t('app', 'Customer Name'),
            'review' => Yii::t('app', 'Review'),
            'rating' => Yii::t('app', 'Rating'),
            'imageFile' => Yii::t('app', 'Image'),
        ];
    }

    /**
     * Gets query for [[CustomerImage]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCustomerImage()
    {
        return $this->hasOne(File::class, ['id' => 'customer_image_id']);
    }

    /**
     * Gets query for [[Project]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProject()
    {
        return $this->hasOne(Project::class, ['id' => 'project_id']);
    }

    public function loadUploadedImageFile()
    {
        $this->imageFile = UploadedFile::getInstance($this, 'imageFile');
    }

    public function saveImage()
    {
        $db = Yii::$app->db;
        $transaction = $db->beginTransaction();
        try {
            $file = new File();
            $file->name = uniqid(true) . '.' . $this->imageFile->extension;
            $file->path_url = Yii::$app->params['uploads']['testimonials'];
            $file->base_url = Yii::$app->urlManager->createAbsoluteUrl($file->path_url);
            $file->mime_type = mime_content_type($this->imageFile->tempName);
            $file->save();

            $this->customer_image_id = $file->id;

            $thumbnail = Image::thumbnail($this->imageFile->tempName, null, 1080);
            $didSave = $thumbnail->save($file->path_url . '/' . $file->name);
            if (!$didSave) {
                $this->addError('imageFile', Yii::t('app', 'Failed to save image.'));
                return false;
            }
            $transaction->commit();
        } catch (\Throwable $e) {
            $transaction->rollBack();
            $this->addError('imageFile', Yii::t('app', 'Failed to save image.') . '(' . $e->getMessage() . ')');
            return false;
        }
        return true;
    }

    public function imageAbsoluteUrl()
    {
        return $this->customerImage ? $this->customerImage->absoluteUrl() : [];
    }

    public function imageConfig()
    {
        return $this->customerImage ? [['key' => $this->customerImage->id]] : [];
    }
}
