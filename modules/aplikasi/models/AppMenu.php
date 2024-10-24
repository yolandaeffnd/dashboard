<?php

namespace app\modules\aplikasi\models;

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
 * @property integer $iconMenu
 * @property integer $isHeader
 */
class AppMenu extends \yii\db\ActiveRecord {

    public $kode;
    public $idAction;
    public $actionDesk;
    public $actionFn;
    public $idGroup;
    public $arrGroupMenu;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return 'app_menu';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['parentId', 'noUrut'], 'integer'],
            [['labelMenu', 'isAktif', 'noUrut','isHeader'], 'required'],
            [['isAktif', 'isSubAction'], 'string'],
            [['labelMenu', 'controllerName', 'iconMenu'], 'string', 'max' => 100],
            [['urlModule'], 'string', 'max' => 150]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'idMenu' => 'Kode',
            'parentId' => 'Sub Menu Dari',
            'labelMenu' => 'Label Menu',
            'urlModule' => 'Url Module',
            'controllerName' => 'Controller Name',
            'isAktif' => 'Aktif',
            'isSubAction' => 'Is Sub Action',
            'noUrut' => 'No Urut',
            'iconMenu' => 'Class Icon Admin LTE',
            'isHeader'=>'Is Header Menu'
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAppActions() {
        return $this->hasMany(AppAction::className(), ['idMenu' => 'idMenu']);
    }

    public function getMenuAkses($id){
        $queryMenuGroup = $this->find();
        $queryMenuGroup->select(['app_group_menu.idMenu AS kode', 'app_group_menu.actionFn AS actionFn']);
        $queryMenuGroup->join = [
            ['JOIN', 'app_group_menu', 'app_menu.idMenu=app_group_menu.idMenu'],
//            ['LEFT JOIN', 'app_action', 'app_action.idMenu=app_menu.idMenu']
        ];
        $queryMenuGroup->where('idGroup=:group', [':group' => $id]);
        $queryMenuGroup->groupBy(['app_menu.idMenu', 'app_group_menu.actionFn']);
        $arrGroupMenu = [];
        foreach ($queryMenuGroup->each() as $val) {
            if ($val['actionFn'] == "") {
                $arrGroupMenu[] = $val['kode'];
            } else {
                $arrGroupMenu[] = $val['kode'] . '.' . $val['actionFn'];
            }
        }
        return $arrGroupMenu;
    }
}
