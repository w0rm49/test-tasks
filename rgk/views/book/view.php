<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Book */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Книги', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="book-view">

<h1><?= Html::encode($this->title) ?></h1>
<h3><?= $model->author->firstname; ?> <?= $model->author->lastname; ?></h3>
<?php
if ($model->preview) {
    echo Html::img($model->preview, ['height' => 300]);
}
?>
<br/>
<?= DetailView::widget([
    'model' => $model,
    'attributes' => [
        'id',
        'date_create',
        'date_update',
        'preview',
        'date',
    ],
]);