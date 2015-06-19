<?php

use app\models\Author;
use mihaildev\elfinder\InputFile;
use yii\helpers\Html;
use yii\jui\DatePicker;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Book */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="book-form">

    <?php $form = ActiveForm::begin([
        'action' => \yii\helpers\Url::to($model->isNewRecord
            ? ['book/create']
            : ['book/update', 'id' => $model->id, 'return' => $return]
        )
    ]); ?>

    <?= $form->field($model, 'preview')->widget(InputFile::className(), [
        'language' => 'ru',
        'controller' => 'elfinder',
        'filter' => 'image',
        'template' => '<div class="input-group">{input}<span class="input-group-btn">{button}</span></div>',
        'options' => ['class' => 'form-control'],
        'buttonOptions' => ['class' => 'btn btn-default'],
        'multiple' => false,
        'buttonName' => 'Выбрать'
    ]); ?>

    <?= $form->field($model, 'date')->widget(DatePicker::className(), [
        'language' => 'ru',
        'dateFormat' => 'yyyy-MM-dd',
        'options' => ['class' => 'form-control']
    ]); ?>

    <?= $form->field($model, 'name')->textInput() ?>

    <?= $form->field($model, 'author_id')->dropDownList(Author::getVariants()); ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Добавить' : 'Сохранить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
