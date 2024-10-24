<?php

namespace app\models;

use Yii;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of DaoQuery
 *
 * @author LPTIK
 */
class DAO {
    

    //INI SAMA DENGAN DI ATAS BEDA NAMA FUNGSINYA SAJA

    public function QueryAll($query, $params) {
        $conn = Yii::$app->db;
        $command = $conn->createCommand($query);
        foreach ($params as $key => $value) {
            $command->bindValue($key, $value);
        }
        $rowData = $command->queryAll();
        return $rowData;
    }

    public function QueryRow($query, $params) {
        $conn = Yii::$app->db;
        $command = $conn->createCommand($query);
        foreach ($params as $key => $value) {
            $command->bindValue($key, $value);
        }
        $rowData = $command->queryOne();
        return $rowData;
    }

    public function Execute($query, $params) {
        $conn = Yii::$app->db;
        $command = $conn->createCommand($query);
        foreach ($params as $key => $value) {
            $command->bindValue($key, $value);
        }
        $result = $command->execute();
        return $result;
    }

    public function BatchInsert($tabel, $attributes, $params) {
        $result = Yii::$app->db->createCommand()
                ->batchInsert($tabel, $attributes, $params)
                ->execute();
        return $result;
    }

    public function beginTransaction() {
        $conn = Yii::$app->db;
        return $conn->beginTransaction();
    }

    /**
     * DATABASE SIREG
     */
    public function dbSiregQueryAll($query, $params) {
        $conn = Yii::$app->dbSireg;
        $command = $conn->createCommand($query);
        foreach ($params as $key => $value) {
            $command->bindValue($key, $value);
        }
        $rowData = $command->queryAll();
        return $rowData;
    }

    public function dbSiregQueryRow($query, $params) {
        $conn = Yii::$app->dbSireg;
        $command = $conn->createCommand($query);
        foreach ($params as $key => $value) {
            $command->bindValue($key, $value);
        }
        $rowData = $command->queryOne();
        return $rowData;
    }

    public function dbSiregExecute($query, $params) {
        $conn = Yii::$app->dbSireg;
        $command = $conn->createCommand($query);
        foreach ($params as $key => $value) {
            $command->bindValue($key, $value);
        }
        $result = $command->execute();
        return $result;
    }

    public function dbSiregBatchInsert($tabel, $attributes, $params) {
        $result = Yii::$app->dbSireg->createCommand()
                ->batchInsert($tabel, $attributes, $params)
                ->execute();
        return $result;
    }

    public function dbSiregBeginTransaction() {
        $conn = Yii::$app->dbSireg;
        return $conn->beginTransaction();
    }

    /**
     * BEGIN
     * Connection All dbAllConn
     */
    public function dbAllConn($host, $port, $dbname, $dbusername, $dbpassword) {
        $conn = new Connection();
        $conn->dsn = 'mysql:host=' . $host . ';port=' . $port . ';dbname=' . $dbname . ';';
        $conn->username = $dbusername;
        $conn->password = $dbpassword;
        $conn->charset = 'utf8';
        return $conn;
    }

    public function dbAllQueryAll($dbname, $query, $params) {
        $conn = Yii::$app->$dbname;
        $command = $conn->createCommand($query);
        foreach ($params as $key => $value) {
            $command->bindValue($key, $value);
        }
        $rowData = $command->queryAll();
        return $rowData;
    }

    public function dbAllQueryRow($dbname, $query, $params) {
        $conn = Yii::$app->$dbname;
        $command = $conn->createCommand($query);
        foreach ($params as $key => $value) {
            $command->bindValue($key, $value);
        }
        $rowData = $command->queryOne();
        return $rowData;
    }

    public function dbAllExecute($dbname, $query, $params) {
        $conn = Yii::$app->$dbname;
        $command = $conn->createCommand($query);
        foreach ($params as $key => $value) {
            $command->bindValue($key, $value);
        }
        $result = $command->execute();
        return $result;
    }

    public function dbAllBatchInsert($dbname, $tabel, $attributes, $params) {
        $conn = Yii::$app->$dbname;
        $result = $conn->createCommand()
                ->batchInsert($tabel, $attributes, $params)
                ->execute();
        return $result;
    }

    public function dbAllBeginTransaction($conn) {
        return $conn->beginTransaction();
    }
    
    public function dbMultiQueryAll($dbname,$query){
        $conn = Yii::$app->$dbname;
        $result = $conn->createCommand($query)
                ->queryAll();
        return $result;
    }

    /**
     * End Connection All
     */
}
