<?php

/*
 * Description: Ref_TransactionTypesForm Model
 * @author: JunJun S. Hernandez <hernandezjunjun28@gmail.com>
 * DateCreated: 2014-12-10
 * Country: Philippines
 */

class Ref_TransactionTypesForm extends CFormModel {

    public $_connection;

    public function __construct() {
        $this->_connection = Yii::app()->db;
    }

    public function getAllActiveTransactionTypes() {
        $connection = $this->_connection;
        $sql = "SELECT DISTINCT ID, Name FROM ref_transactiontypes ORDER BY Name ASC";
        $command = $connection->createCommand($sql);
        $result = $command->queryAll();

        if (isset($result)) {
            $transactionTypes = $result;
        } else {
            $transactionTypes = array();
        }

        return $transactionTypes;
    }

}