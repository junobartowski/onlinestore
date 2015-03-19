<?php

/*
 * Description: Ref_PostCodesForm Model
 * @author: JunJun S. Hernandez <hernandezjunjun28@gmail.com>
 * DateCreated: 2014-12-10
 * Country: Philippines
 */

class Ref_PostCodesForm extends CFormModel {

    public $_connection;

    public function __construct() {
        $this->_connection = Yii::app()->db;
    }

    public function getPostCodeByCountryCode($countryCode) {
        $connection = $this->_connection;
        $sql = "SELECT ID, PostCode FROM ref_postcodes WHERE CountryCode = :countryCode ORDER BY PostCode ASC";
        $command = $connection->createCommand($sql);
        $command->bindValues(array(":countryCode" => $countryCode));
        $result = $command->queryAll();

        if (isset($result)) {
            return $result;
        } else {
            return array();
        }
    }

    public function isPostCodeExisting($postCodeID) {
        $connection = $this->_connection;
        $sql = "SELECT COUNT(ID) ctrID FROM ref_postcodes WHERE PostCodeID = :postCodeID";
        $command = $connection->createCommand($sql);
        $command->bindValues(array(":postCodeID" => $postCode));
        $result = $command->queryRow();

        if (isset($result['ctrID'])) {
            $countResult = $result['ctrID'];
            if ($countResult > 0) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function isPostCodeMatchedWithCountryCode($postCodeID, $countryCode) {
        $connection = $this->_connection;
        $sql = "SELECT COUNT(ID) ctrID FROM ref_postcodes WHERE ID = :postCodeID AND CountryCode = :countryCode";
        $command = $connection->createCommand($sql);
        $command->bindValues(array(":postCodeID" => $postCodeID, ":countryCode" => $countryCode));
        $result = $command->queryRow();

        if (isset($result['ctrID'])) {
            $countResult = $result['ctrID'];
            if ($countResult > 0) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

}