<?php

namespace app\modules\aplikasi\models;

use Yii;

/**
 * This is the model class for table "app_group".
 *
 * @property integer $idGroup
 * @property string $namaGroup
 * @property string $ketGroup
 * @property string $isMemberGroup
 */
class AppGroup extends \yii\db\ActiveRecord {

    public $arrGroupView;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'app_group';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['namaGroup','isMemberGroup'], 'required'],
            [['ketGroup'],'safe'],
            [['namaGroup'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'idGroup' => 'Id Group',
            'namaGroup' => 'Nama Group',
            'ketGroup'=>'Keterangan',
            'isMemberGroup'=>'Member Group?'
        ];
    }

}
