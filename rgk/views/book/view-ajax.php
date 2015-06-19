<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Book */

?>
<h1><?= $model->name; ?>
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