<?php

namespace app\modules\maping\models;

use Yii;

/**
 * This is the model class for table "ref_jalur".
 *
 * @property integer $idJalur
 * @property string $namaJalur
 *
 * @property RefJalurMap[] $refJalurMaps
 * @property SiregJalur[] $mapIdJalurs
 */
class Jalur extends \yii\db\ActiveRecord {

    public $jalurMap;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'ref_jalur';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['idJalur', 'namaJalur'], 'required'],
                [['idJalur'], 'integer'],
                [['jalurMap'], 'safe'],
                [['namaJalur'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'idJalur' => 'Id',
            'namaJalur' => 'Nama Jalur',
            'jalurMap' => 'Jalur Map Sireg'
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRefJalurMaps() {
        return $this->hasMany(RefJalurMap::className(), ['idJalur' => 'idJalur']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMapIdJalurs() {
        return $this->hasMany(SiregJalur::className(), ['idJalur' => 'mapIdJalur'])->viaTable('ref_jalur_map', ['idJalur' => 'idJalur']);
    }

}
