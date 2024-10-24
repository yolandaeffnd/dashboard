<?php

namespace app\modules\aplikasi\models;

use Yii;

/**
 * This is the model class for table "ref_fakultas".
 *
 * @property integer $fakId
 * @property string $fakNama
 * @property string $fakCreate
 * @property string $fakUpdate
 *
 * @property AppUserData[] $appUserDatas
 * @property AppUser[] $idUsers
 */
class RefFakultas extends \yii\db\ActiveRecord {

    public $arrUserData;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'ref_fakultas';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
                [['fakNama', 'fakCreate'], 'required'],
                [['fakCreate', 'fakUpdate'], 'safe'],
                [['fakNama'], 'string', 'max' => 250],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'fakId' => 'Fak ID',
            'fakNama' => 'Fakultas',
            'fakCreate' => 'Fak Create',
            'fakUpdate' => 'Fak Update',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAppUserDatas() {
        return $this->hasMany(AppUserData::className(), ['unitId' => 'fakId']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdUsers() {
        return $this->hasMany(AppUser::className(), ['idUser' => 'idUser'])->viaTable('app_user_data', ['unitId' => 'fakId']);
    }

}
