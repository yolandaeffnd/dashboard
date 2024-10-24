<?php

namespace app\modules\maping\models;

use Yii;

/**
 * This is the model class for table "sireg_prodi".
 *
 * @property integer $idProgramStudi
 * @property string $namaProgramStudi
 * @property integer $idFak
 * @property integer $idJenjang
 *
 * @property RefProdiMap[] $refProdiMaps
 * @property RefProdiNasional[] $prodiKodes
 * @property RefFakultas $idFak0
 */
class SiregProdi extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'sireg_prodi';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['idFak', 'idJenjang'], 'integer'],
            [['namaProgramStudi'], 'string', 'max' => 255],
            [['idFak'], 'exist', 'skipOnError' => true, 'targetClass' => RefFakultas::className(), 'targetAttribute' => ['idFak' => 'fakId']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'idProgramStudi' => 'Id Program Studi',
            'namaProgramStudi' => 'Nama Program Studi',
            'idFak' => 'Id Fak',
            'idJenjang' => 'Id Jenjang',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRefProdiMaps()
    {
        return $this->hasMany(RefProdiMap::className(), ['idProgramStudi' => 'idProgramStudi']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProdiKodes()
    {
        return $this->hasMany(RefProdiNasional::className(), ['prodiKode' => 'prodiKode'])->viaTable('ref_prodi_map', ['idProgramStudi' => 'idProgramStudi']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdFak0()
    {
        return $this->hasOne(RefFakultas::className(), ['fakId' => 'idFak']);
    }
}
