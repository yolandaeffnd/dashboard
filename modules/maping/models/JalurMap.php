<?php

namespace app\modules\maping\models;

use Yii;

/**
 * This is the model class for table "ref_jalur_map".
 *
 * @property integer $idJalur
 * @property integer $mapIdJalur
 *
 * @property RefJalur $idJalur0
 * @property SiregJalur $mapIdJalur0
 */
class JalurMap extends \yii\db\ActiveRecord {

    public $jalurSireg;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'ref_jalur_map';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['idJalur', 'mapIdJalur'], 'required'],
                [['idJalur', 'mapIdJalur'], 'integer'],
                [['jalurSireg'], 'safe'],
                [['idJalur'], 'exist', 'skipOnError' => true, 'targetClass' => RefJalur::className(), 'targetAttribute' => ['idJalur' => 'idJalur']],
                [['mapIdJalur'], 'exist', 'skipOnError' => true, 'targetClass' => SiregJalur::className(), 'targetAttribute' => ['mapIdJalur' => 'idJalur']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'idJalur' => 'Id Jalur',
            'mapIdJalur' => 'Map Id Jalur',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdJalur0() {
        return $this->hasOne(RefJalur::className(), ['idJalur' => 'idJalur']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMapIdJalur0() {
        return $this->hasOne(SiregJalur::className(), ['idJalur' => 'mapIdJalur']);
    }

}
