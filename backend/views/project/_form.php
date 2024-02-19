<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\editors\Summernote;

/** @var yii\web\View $this */
/** @var common\models\Project $model */
/** @var yii\widgets\ActiveForm $form */

$this->registerJsFile(
    '@web/js/projectForm.js',
    ['depends' => [\yii\web\JqueryAsset::class]]
);

?>

<div class="project-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'tech_stack')->widget(Summernote::class, [
        'useKrajeePresets' => true,
        // other widget settings
    ]);
    ?>

    <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'start_date')->widget(\yii\jui\DatePicker::class, [
        //'language' => 'pt-BR',
    ]) ?>

    <?= $form->field($model, 'end_date')->widget(\yii\jui\DatePicker::class, [
        //'language' => 'pt-BR' 
    ]) ?>

    <?php foreach ($model->projectImages as $image) : ?>

        <div id="project-form__image-container-<?= $image->id ?>" class="project-form__image-container">
            <?= Html::img($image->file->absoluteUrl(), [
                'alt' => 'Image demonstration',
                'height' => 200,
                'class' => 'project-form__image'
            ]) ?>

            <?= Html::button(Yii::t('app', 'Delete'), [
                'class' => 'btn btn-danger btn-delete-project-image',
                'data-project-image-id' => $image->id
            ]) ?>

            <div id="project-form__image-error-message-<?= $image->id ?>" class="text-danger"></div>
        </div>
    <?php endforeach; ?>

    <?= $form->field($model, 'imageFile')->fileInput() ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>