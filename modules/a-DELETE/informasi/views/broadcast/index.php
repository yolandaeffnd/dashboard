<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Url;
use app\models\IndonesiaDate;
use app\modules\informasi\models\Member;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\informasi\models\BroadcastSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Broadcasts';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="broadcast-index">

    <?php echo $this->render('_search', ['model' => $searchModel]); ?>

    <?php
    Pjax::begin([
        'id' => 'pjax-berita',
        'enablePushState' => false,
        'enableReplaceState' => false
    ]);

    echo GridView::widget([
        'dataProvider' => $dataProvider,
        'responsive' => true,
        'hover' => true,
        'toolbar' => [
            '{toggleData}',
        ],
        'columns' => [
                ['class' => 'kartik\grid\SerialColumn'],
                [
                'attribute' => 'bcCreate',
                'group' => true,
                'value' => function ($data) {
                    $inDate = new IndonesiaDate();
                    return $inDate->setDateTime($data->bcCreate);
                }
            ],
                [
                'attribute' => 'bcTo',
                'value' => function ($data) {
                    return $data->bcTo.' ['.Member::findOne(['memberEmail'=>$data->bcTo])->memberNama.']';
                }
            ],
                [
                'attribute' => 'bcJudul',
                'group' => true,
                'value' => function ($data) {
                    return $data->bcJudul;
                }
            ],
                [
                'class' => 'kartik\grid\ActionColumn',
                'template' => '{view} {delete}',
            ],
        ],
        'panel' => [
            'type' => 'success',
            'heading' => 'Broadcast',
            'after' => false,
            'before' => false
        ],
    ]);
    ?>
</div>
