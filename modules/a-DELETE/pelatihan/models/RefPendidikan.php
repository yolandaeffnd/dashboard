<?php

namespace app\modules\pelatihan\models;

use Yii;

/**
 * This is the model class for table "ref_pendidikan".
 *
 * @property integer $pddId
 * @property string $pddNama
 * @property string $pddCreate
 * @property string $pddUpdate
 *
 * @property Member[] $members
 */
class RefPendidikan extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ref_pendidikan';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['pddNama', 'pddCreate'], 'required'],
            [['pddCreate', 'pddUpdate'], 'safe'],
            [['pddNama'], 'string', 'max' => 200],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'pddId' => 'Pdd ID',
            'pddNama' => 'Pdd Nama',
            'pddCreate' => 'Pdd Create',
            'pddUpdate' => 'Pdd Update',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMembers()
    {
        return $this->hasMany(Member::className(), ['memberPddId' => 'pddId']);
    }
}
