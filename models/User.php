<?php

namespace app\models;

use app\models\AppUser;
use app\models\AppGroupMenu;
use yii\httpclient\Client;
use yii\helpers\Json;

class User extends \yii\base\BaseObject implements \yii\web\IdentityInterface {

    const STATUS_AKTIF = '1';

    public $userId;
    public $userNama;
    public $userUsername;
    public $userPassword;
    public $userAuthKey;
    public $userGroupId;
    public $userTglEntri;
    public $userUnit;
    public $_status;

    /**
     * @inheritdoc
     */
    public static function findIdentity($id) {
        $users = AppUser::findOne($id);
        if (isset($users)) {
            return new static([
                'userId' => $users->idUser,
                'userNama' => $users->nama,
                'userUsername' => $users->usernameApp,
                'userPassword' => $users->passwordApp,
                'userGroupId' => $users->idGroup,
                'userTglEntri' => $users->tglEntri,
                'userUnit' => 'all'
            ]);
        } else {
            return null;
        }
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null) {
        //Belum digunakan maksimal
        $user = AppUser::find()->where(['usernameApp' => $token])->one();
        if (isset($user)) {
            return new static($user);
        }
        return null;
    }

    /**
     * Finds user by username
     *
     * @param  string      $username
     * @return static|null
     */
    public static function findByUsername($username) {
        $users = AppUser::find()->where('usernameApp=:username AND isAktif=:status', [
                    ':username' => $username,
                    ':status' => self::STATUS_AKTIF
                ])->one();
        if (isset($users)) {
            return new static([
                'userId' => $users->idUser,
                'userNama' => $users->nama,
                'userUsername' => $users->usernameApp,
                'userPassword' => $users->passwordApp,
                'userGroupId' => $users->idGroup,
                'userTglEntri' => $users->tglEntri,
                'userUnit' => 'all'
            ]);
        } else {
            return null;
        }
    }

    /**
     * @inheritdoc
     */
    public function getId() {
        return $this->userId;
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey() {
        return $this->userAuthKey;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey) {
        return $this->userAuthKey === $authKey;
    }

    /**
     * Validates password
     *
     * @param  string  $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password) {
        return $this->userPassword === $password;
    }

    public static function isUserLogin() {
        if (AppUser::findOne(['usernameApp' => \Yii::$app->user->identity->userUsername, 'idUser' => \Yii::$app->user->identity->userId, 'isAktif' => self::STATUS_AKTIF])) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public static function userAccessRoles($ctrlName = '') {
        $action = AppGroupMenu::find()
                ->join('JOIN', 'app_menu', 'app_menu.idMenu = app_group_menu.idMenu')
                ->where('controllerName = :ctrlName AND idGroup = :group')
                ->params([
                    ':ctrlName' => $ctrlName,
                    ':group' => isset(\Yii::$app->user->identity->userGroupId) ? \Yii::$app->user->identity->userGroupId : ''
                ])
                ->all();

        $data = [];
        foreach ($action as $val) {
            $data[] = [
                'allow' => true,
                'actions' => [$val->actionFn],
                'roles' => ["@"],
            ];
        }
        return $data;
    }

    public static function userAccessRoles2($ctrlName = '') {
        $action = AppGroupMenu::find()
                ->join('JOIN', 'app_menu', 'app_menu.idMenu = app_group_menu.idMenu')
                ->where('controllerName = :ctrlName AND idGroup = :group')
                ->params([
                    ':ctrlName' => $ctrlName,
                    ':group' => isset(\Yii::$app->user->identity->userGroupId) ? \Yii::$app->user->identity->userGroupId : ''
                ])
                ->all();

        $act = [];
        foreach ($action as $val) {
            $act[] = $val->actionFn;
        }
        if (empty($act)) {
            $data = [
                'allow' => false,
                'actions' => $act,
                'roles' => ["@"],
            ];
        } else {
            $data = [
                'allow' => true,
                'actions' => $act,
                'roles' => ["@"],
            ];
        }
        return $data;
    }

    public static function userAccessRoleAction($ctrlName = '', $actName = '') {
        $action = AppGroupMenu::find()
                ->join('JOIN', 'app_menu', 'app_menu.idMenu = app_group_menu.idMenu')
                ->where('controllerName = :ctrlName AND idGroup = :group AND actionFn = :actName')
                ->params([
                    ':ctrlName' => $ctrlName,
                    ':actName' => $actName,
                    ':group' => isset(\Yii::$app->user->identity->userGroupId) ? \Yii::$app->user->identity->userGroupId : ''
                ])
                ->one();

        if ($action->actionFn != '') {
            $data = true;
        } else {
            $data = false;
        }
        return $data;
    }

//    public static function userAccessRole($ctrlName = '') {
//        $action = AppGroupMenu::find()
//                ->join('JOIN', 'app_menu', 'app_menu.idMenu = app_group_menu.idMenu')
//                ->where('controllerName = :ctrlName AND idGroup = :group')
//                ->params([
//                    ':ctrlName' => $ctrlName,
//                    ':group' => isset(\Yii::$app->user->identity->idGroup) ? \Yii::$app->user->identity->idGroup : ''
//                ])
//                ->all();
//
//        $data = array();
//        $no = 0;
//        foreach ($action as $val) {
//            $data[$no] = $val->actionFn;
//            $no++;
//        }
//        return $data;
//    }
}
