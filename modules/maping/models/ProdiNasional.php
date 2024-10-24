<?php

namespace app\modules\maping\models;

use Yii;

/**
 * This is the model class for table "ref_prodi_nasional".
 *
 * @property string $prodiKode
 * @property string $prodiNama
 * @property string $prodiJenjang
 * @property integer $prodiFakId
 * @property string $prodiStatus
 *
 * @property RefFakultas $prodiFak
 */
class ProdiNasional extends \yii\db\ActiveRecord {

    public $prodiMap;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'ref_prodi_nasional';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['prodiKode', 'prodiNama', 'prodiJenjang', 'prodiFakId', 'prodiStatus'], 'safe'],
                [['prodiFakId'], 'integer'],
                [['prodiStatus'], 'string'],
                [['prodiMap'], 'safe'],
                [['prodiKode'], 'string', 'max' => 10],
                [['prodiNama'], 'string', 'max' => 200],
                [['prodiJenjang'], 'string', 'max' => 15],
                [['prodiFakId'], 'exist', 'skipOnError' => true, 'targetClass' => Fakultas::className(), 'targetAttribute' => ['prodiFakId' => 'fakId']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'prodiKode' => 'Kode',
            'prodiNama' => 'Nama Prodi',
            'prodiJenjang' => 'Jenjang',
            'prodiFakId' => 'Fakultas',
            'prodiStatus' => 'Status',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProdiFak() {
        return $this->hasOne(Fakultas::className(), ['fakId' => 'prodiFakId']);
    }

}
