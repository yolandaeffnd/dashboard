<?php

namespace app\models;

use yii\base\BaseObject;
use yii;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Menu
 *
 * @author Ade Priyanto
 */
class Menu extends BaseObject {

    public function getMenu() {
        $cnd = "0";
        $data = array();
        //$data[] = ['label' => 'MENU UTAMA', 'options' => ['class' => 'header']];
        $data[] = ['label' => 'Home', 'icon' => 'fa fa-home', 'url' => ['/site/index']];
        $result = AppMenu::find()
                ->join('JOIN', 'app_group_menu', 'app_group_menu.idMenu=app_menu.idMenu')
                ->where([
                    'isAktif' => '1',
                    'isSubAction' => '0',
                    'parentId' => '0',
                    'idGroup' => \Yii::$app->user->identity->userGroupId
                ])
                ->groupBy(['app_menu.idMenu'])
                ->orderBy([
                    'noUrut' => SORT_ASC
                ])
                ->each();
        foreach ($result as $val) {
            $row = array();
            if ($val->isHeader == 1) {
                $row['label'] = $val->labelMenu;
                $row['visible'] = !Yii::$app->user->isGuest;
                $row['options'] = ['class' => 'header'];
            } else {
                $row['label'] = $val->labelMenu;
                if ($val->urlModule != '') {
                    $row['url'] = [$val->urlModule];
                } else {
                    $row['url'] = '#';
                }
                $row['icon'] = $val->iconMenu;
                $row['visible'] = !Yii::$app->user->isGuest;
                $row['options'] = ['class' => 'treeview'];
                if (count($this->getSubMenu($val->idMenu)) > 0) {
                    $row['items'] = $this->getSubMenu($val->idMenu);
                }
            }
            $data[] = $row;
        }
        return $data;
    }

    public function getSubMenu($cnd = '') {
        $data = array();
        $result = AppMenu::find()
                ->join('JOIN', 'app_group_menu', 'app_group_menu.idMenu=app_menu.idMenu')
                ->where([
                    'isAktif' => '1',
                    'isSubAction' => '0',
                    'parentId' => $cnd,
                    'idGroup' => \Yii::$app->user->identity->userGroupId
                ])
                ->groupBy(['app_menu.idMenu'])
                ->orderBy([
                    'noUrut' => SORT_ASC
                ])
                ->each();
        foreach ($result as $val) {
            $row = array();
            $row['label'] = $val->labelMenu;
            if ($val->urlModule != '') {
                $row['url'] = [$val->urlModule];
            } else {
                $row['url'] = '#';
            }
            $row['icon'] = $val->iconMenu;
            $row['visible'] = !Yii::$app->user->isGuest;
            $row['options'] = ['class' => 'treeview'];
            if (count($this->getSubMenu($val->idMenu)) > 0) {
                $row['items'] = $this->getSubMenu($val->idMenu);
            }
            $data[] = $row;
        }
        return $data;
    }

}
