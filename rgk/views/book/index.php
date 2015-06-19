<?php

use yii\bootstrap\Modal;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\SearchBook */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Книги';
$this->params['breadcrumbs'][] = $this->title;

$dateFormat = function ($date) {
    $months = [
        'января', 'февраля', 'марта', 'апреля', 'мая', 'июня', 'июля',
        'августа', 'сентября', 'октября', 'ноября', 'декабря'
    ];
    $timestamp = strtotime($date);
    if ($timestamp === -1) {
        return null;
    }
    if ($timestamp >= strtotime('today')) {
        return 'Сегодня';
    }
    if ($timestamp >= strtotime('yesterday')) {
        return 'Вчера';
    }
    return sprintf("%s %s %s", date('j', $timestamp), $months[date('n', $timestamp) - 1], date('Y', $timestamp));
};

$this->registerJs(<<<'JS'
    var modal = $('#modal');
    var loading = modal.find('.loading');
    var content = modal.find('.content');
    var error = modal.find('.error');
    var viewButtons = $('.ajaxView');

    viewButtons.on('click', function(event){
        event.preventDefault();
        var $this = $(this);

        modal.modal('show');
        content.hide().html('');
        error.hide();
        loading.show();

        console.log($this.data('model-id'));

        $.ajax({
            url: '/book/view-ajax/?id=' + $this.data('model-id'),
            method: 'GET',
            dataType: 'html',
            success: function(answer){
                loading.hide();
                content.html(answer).show();
            },
            error: function() {
                loading.hide();
                error.show();
            }
        });
    });
JS
);

?>

<?= newerton\fancybox\FancyBox::widget([
    'target' => '.fancybox',
    'helpers' => true,
    'mouse' => true,
    'config' => [
        'maxWidth' => '90%',
        'maxHeight' => '90%',
        'playSpeed' => 7000,
        'padding' => 0,
        'fitToView' => false,
        'width' => '70%',
        'height' => '70%',
        'autoSize' => false,
        'closeClick' => false,
        'openEffect' => 'elastic',
        'closeEffect' => 'elastic',
        'prevEffect' => 'elastic',
        'nextEffect' => 'elastic',
        'closeBtn' => false,
        'openOpacity' => true,
        'helpers' => [
            'title' => ['type' => 'float'],
            'buttons' => [],
            'thumbs' => ['width' => 68, 'height' => 50],
            'overlay' => [
                'css' => [
                    'background' => 'rgba(0, 0, 0, 0.8)'
                ]
            ]
        ],
    ]
]);
?>

<div class="book-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?php /* Html::a('Create Book', ['create'], ['class' => 'btn btn-success']) */ ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => null,
        'columns' => [
            'id',
            'name',
            [
                'attribute' => 'preview',
                'format' => 'html',
                'value' => function($data) {
                    return $data->preview
                        ? Html::a(
                            Html::img($data->preview, ['height' => 100]),
                            $data->preview,
                            ['class' => 'fancybox']
                        )
                        : null;
                }
            ],
            [
                'attribute' => 'author_id',
                'value' => function($data) {
                    return $data->author->firstname . ' ' . $data->author->lastname;
                }
            ],
            [
                'attribute' => 'date',
                'value' => function($data) use($dateFormat) {
                    return $dateFormat($data->date);
                }
            ],
            [
                'attribute' => 'date_create',
                'value' => function($data) use($dateFormat) {
                    return $dateFormat($data->date_create);
                }
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'options' => ['width' => 80],
                'template' => '{update} {viewAjax} {delete}',
                'buttons' => [
                    'viewAjax' => function ($url, $model, $key) {
                        return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url, [
                            'data-model-id' => $model->id,
                            'class' => 'ajaxView'
                        ]);
                    },
                    'update' => function ($url, $model, $key) {
                        $options = [
                            'title' => Yii::t('yii', 'Update'),
                            'aria-label' => Yii::t('yii', 'Update'),
                            'data-pjax' => '0',
                        ];
                        $url = Url::to(['book/update', 'id' => $model->id, 'return' => base64_encode(Url::current())]);
                        return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, $options);
                    }
                ]
            ],
        ],
    ]); ?>

</div>

<?php Modal::begin(['id' => 'modal', 'header' => null]);?>
    <div class="alert alert-info loading">Загрузка...</div>
    <div class="alert alert-danger error">Ошибка, попробуйте позже</div>
    <div class="content"></div>
<?php Modal::end(); ?>