<?php

namespace app\modules\maping\models;

use Yii;

/**
 * This is the model class for table "ref_prodi_map".
 *
 * @property string $prodiKode
 * @property integer $idProgramStudi
 */
class ProdiMap extends \yii\db\ActiveRecord {

    public $prodiNama;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'ref_prodi_map';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['prodiKode', 'idProgramStudi'], 'required'],
                [['idProgramStudi'], 'integer'],
                [['prodiKode'], 'string', 'max' => 10],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'prodiKode' => 'Prodi Kode',
            'idProgramStudi' => 'Id Program Studi',
        ];
    }

}
