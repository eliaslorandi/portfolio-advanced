<?php

namespace common\models;

use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\web\UploadedFile;
use yii\helpers\FileHelper;

/**
 * This is the model class for table "project".
 *
 * @property int $id
 * @property string $name
 * @property string $tech_stack
 * @property string $description
 * @property string|null $start_date
 * @property string|null $end_date
 *
 * @property ProjectImage[] $projectImages
 * @property Testimonial[] $testimonials
 */
class Project extends ActiveRecord
{
    /**
     * @var UploadedFile
     */
    public $imageFiles;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'project';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'tech_stack', 'description'], 'required'],
            [['tech_stack', 'description'], 'string'],
            [['start_date', 'end_date'], 'safe'],
            [['name'], 'string', 'max' => 255],
            [['imageFiles'], 'file', 'skipOnEmpty' => false, 'extensions' => 'png, jpg, jpeg', 'maxFiles' => 10], //0 = limitado diretiva php = 20
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Name'),
            'tech_stack' => Yii::t('app', 'Tech Stack'),
            'description' => Yii::t('app', 'Description'),
            'start_date' => Yii::t('app', 'Start Date'),
            'end_date' => Yii::t('app', 'End Date'),
        ];
    }

    /**
     * Gets query for [[ProjectImages]].
     *
     * @return ActiveQuery|ActiveQuery
     */
    public function getProjectImages()
    {
        return $this->hasMany(ProjectImage::class, ['project_id' => 'id']);
    }

    /**
     * Gets query for [[Testimonials]].
     *
     * @return ActiveQuery|ActiveQuery
     */
    public function getTestimonials()
    {
        return $this->hasMany(Testimonial::class, ['project_id' => 'id']);
    }

    /**
     * {@inheritdoc}
     * @return ProjectQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ProjectQuery(get_called_class());
    }

    public function saveImages()
    {
        Yii::$app->db->transaction(function ($db) {
            /**
             * @var $db \yii\db\Connection
             */
            foreach ($this->imageFiles as $imageFile) {
                /**
                 * @var $imageFile UploadedFile
                 */
                //primeira etapa: criar registro na tabela file
                $file = new File();
                $file->name = uniqid(true) . '.' . $imageFile->extension;
                $file->path_url = Yii::$app->params['uploads']['projects'];
                $file->base_url = Yii::$app->urlManager->createAbsoluteUrl($file->path_url);
                //$file->mime_type = FileHelper::getMimeType($imageFile->tempName);
                $file->mime_type = mime_content_type($imageFile->tempName);
                $file->save();

                //segunda etapa: criar registro na tabela project_image
                $projectImage = new ProjectImage();
                $projectImage->project_id = $this->id;
                $projectImage->file_id = $file->id;
                $projectImage->save();

                //terceira etapa: salvar a imagem no diretório da web
                if (!$imageFile->saveAs($file->path_url . '/' . $file->name)) {
                    $db->transaction->rollBack();
                }
            }
        });
    }

    public function hasImages() //verifica se o projeto possui imagem
    {
        return count($this->projectImages) > 0;
    }

    public function imageAbsoluteUrls()
    {
        $urls = [];
        foreach ($this->projectImages as $image) {
            $urls[] = $image->file->absoluteUrl();
        }
        return $urls;
    }

    public function imageConfigs()
    {
        $configs = [];
        foreach ($this->projectImages as $image) {
            $configs[] = [
                'key' => $image->id,
            ];
        }
        return $configs;
    }

    public function loadUploadedImageFiles()
    {
        $this->imageFiles = UploadedFile::getInstances($this, 'imageFiles');
    }
}
