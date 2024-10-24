<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\pembayaran\models\RefTarifSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Tarif Pelatihan';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ref-tarif-index">
<?php echo $this->render('_search', ['model' => $searchModel]); ?>
    <?php echo $this->render('_index', ['dataProvider' => $dataProvider]); ?>
</div>
