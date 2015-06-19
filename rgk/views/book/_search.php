<?php

use app\models\Author;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\SearchBook */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="book-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'author_id')->dropDownList(array_merge(
        [null => '- любой -'],
        Author::getVariants()))->label('Автор'); ?>

    <?= $form->field($model, 'name') ?>

    <div class="form-group field-searchbook-date">

        <label class="control-label">Дата выхода книги</label>
<!--        --><?//= Html::activeInput('text', $model, 'date_from'); ?>

<!--        --><?//= Html::activeInput('text', $model, 'date_to'); ?>
        <div id="range_w1" class="input-daterange input-group">
            <?= \yii\jui\DatePicker::widget([
                'model' => $model,
                'attribute' => 'date_from',
                'language' => 'ru',
                'dateFormat' => 'yyyy-MM-dd',
                'options' => ['class' => 'form-control']
            ]); ?>
            <span class="input-group-addon">до</span>
            <?= \yii\jui\DatePicker::widget([
                'model' => $model,
                'attribute' => 'date_to',
                'language' => 'ru',
                'dateFormat' => 'yyyy-MM-dd',
                'options' => ['class' => 'form-control']

            ]); ?>
        </div>






    </div>

    <!--
    <?= $form->field($model, 'date_from') ?>

    <?= $form->field($model, 'date_to') ?>
    -->


    <div class="form-group">
        <?= Html::submitButton('Искать', ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
