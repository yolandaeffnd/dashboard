<?php

namespace app\modules\aplikasi\models;

use Yii;

/**
 * This is the model class for table "app_action".
 *
 * @property integer $idAction
 * @property integer $idMenu
 * @property string $actionFn
 * @property string $actionDesk
 */
class AppAction extends \yii\db\ActiveRecord {

    public $labelMenu;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'app_action';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['idMenu', 'actionFn'], 'required'],
            [['idMenu'], 'integer'],
            [['actionFn'], 'string', 'max' => 100],
            [['actionDesk'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'idAction' => 'Id Action',
            'idMenu' => 'Menu',
            'actionFn' => 'Action Fn (Function)',
            'actionDesk' => 'Action Deskripsi',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdMenu0() {
        return $this->hasOne(AppMenu::className(), ['idMenu' => 'idMenu']);
    }

}
