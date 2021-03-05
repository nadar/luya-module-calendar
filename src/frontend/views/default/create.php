<?php
use luya\helpers\Url;
use luya\bootstrap4\ActiveForm;
use luya\helpers\Html;
use nadar\calendar\models\Person;

?>
<div class="container content pt-5">
    <h1>Eintrag erstellen</h1>
    <div class="text-center mb-5">
        <a href="<?= Url::toRoute(['/calendarfrontend/default/index']); ?>" class="btn btn-primary btn-arrow-left">Zur Übersicht</a>
    </div>
    <?php $form = ActiveForm::begin(['options' => ['class' => 'mb-5']]); ?>
        <?= $form->field($model, 'person_id')->dropDownList(Person::find()->select(['name'])->indexBy('id')->column(), ['prompt' => '-']) ?>
        <?= $form->field($model, 'title'); ?>
        <div class="row">
            <div class="col">
                <?= $form->field($model, 'start_date')->textInput(['type' => 'date']); ?>
            </div>
            <div class="col">
                <?= $form->field($model, 'end_date')->textInput(['type' => 'date']); ?>
            </div>
        </div>
        
        
        <?= $form->field($model, 'is_fix')->checkbox(); ?>
        <p class="lead">Kontakt</p>
        <div class="row">
            <div class="col">
                <?= $form->field($model, 'comment')->textarea(); ?>
            </div>
            <div class="col">
                <?= $form->field($model, 'email'); ?>
                <?= $form->field($model, 'phone'); ?>
            </div>
        </div>
        <?= $form->errorSummary($model); ?>
        <?= Html::submitButton('Speichern', ['class' => 'btn btn-success btn-arrow-right']); ?>
    <?php $form::end(); ?>
</div>