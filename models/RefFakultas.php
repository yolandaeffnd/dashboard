<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "app_user_data".
 *
 * @property integer $idUser
 * @property integer $idUnit
 *
 * @property UnitKerja $idUnit0
 * @property AppUser $idUser0
 */
class RefFakultas extends \yii\db\ActiveRecord
{
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ref_fakultas';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['fakId', 'fakId'], 'required'],
            [['fakNama', 'fakId'], 'string']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'idFak' => 'Id Fakultas',
            'FakNama' => 'Fakultas Nama',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
   


    public function getFakNamaById($params)
    {
        $result = RefFakultas::findOne($params);
        if ($result !== null) {
            return $result->fakNama;
        } else {
            // Handle the case where no record is found
            return null; // Or any other value or action you need
        }
    }

    /**
     * @return \yii\db\ActiveQuery
     */
   
}
