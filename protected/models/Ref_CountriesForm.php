<?php

/*
 * Description: Ref_CountriesForm Model
 * @author: JunJun S. Hernandez <hernandezjunjun28@gmail.com>
 * DateCreated: 2014-12-10
 * Country: Philippines
 */

class Ref_CountriesForm extends CFormModel {

    public $_connection;

    public function __construct() {
        $this->_connection = Yii::app()->db;
    }

    public function getCountries() {
        $connection = $this->_connection;
        $sql = "SELECT DISTINCT Code, Name FROM ref_countries ORDER BY Name ASC";
        $command = $connection->createCommand($sql);
        $result = $command->queryAll();

        if (isset($result)) {
            $countries = $result;
        } else {
            $countries = array();
        }

        return $countries;
    }

    public function isCountryCodeExisting($countryCode) {
        $connection = $this->_connection;
        $sql = "SELECT COUNT(Code) ctrCode FROM ref_countries WHERE Code = :countryCode";
        $command = $connection->createCommand($sql);
        $command->bindValues(array(":countryCode" => $countryCode));
        $result = $command->queryRow();

        if (isset($result['ctrCode'])) {
            $countResult = $result['ctrCode'];
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