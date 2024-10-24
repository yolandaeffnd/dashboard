<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "app_menu".
 *
 * @property integer $idMenu
 * @property integer $parentId
 * @property string $labelMenu
 * @property string $urlModule
 * @property string $controllerName
 * @property string $isAktif
 * @property string $isSubAction
 * @property integer $noUrut
 * @property string $iconMenu
 *
 * @property AppAction[] $appActions
 * @property AppGroupMenu[] $appGroupMenus
 */
class AppMenu extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'app_menu';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['parentId', 'noUrut'], 'integer'],
            [['labelMenu', 'isAktif', 'noUrut'], 'required'],
            [['isAktif', 'isSubAction'], 'string'],
            [['labelMenu', 'controllerName', 'iconMenu'], 'string', 'max' => 100],
            [['urlModule'], 'string', 'max' => 150]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'idMenu' => 'Id Menu',
            'parentId' => 'Parent ID',
            'labelMenu' => 'Label Menu',
            'urlModule' => 'Url Module',
            'controllerName' => 'Controller Name',
            'isAktif' => 'Is Aktif',
            'isSubAction' => 'Is Sub Action',
            'noUrut' => 'No Urut',
            'iconMenu' => 'class icon AdminLTE',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAppGroupMenus()
    {
        return $this->hasMany(AppGroupMenu::className(), ['idMenu' => 'idMenu']);
    }
    
    
}
