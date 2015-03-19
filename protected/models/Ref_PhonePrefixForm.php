<?php

/*
 * Description: Ref_PhonePrefixForm Model
 * @author: JunJun S. Hernandez <hernandezjunjun28@gmail.com>
 * DateCreated: 2014-12-10
 * Country: Philippines
 */

class Ref_PhonePrefixForm extends CFormModel {

    public $_connection;

    public function __construct() {
        $this->_connection = Yii::app()->db;
    }

    public function getPhonePrefixByCountryCode($countryCode) {
        $connection = $this->_connection;
        $sql = "SELECT PhonePrefix FROM ref_phoneprefix WHERE CountryCode = :countryCode";
        $command = $connection->createCommand($sql);
        $command->bindValues(array(":countryCode" => $countryCode));
        $result = $command->queryRow();

        if (isset($result['PhonePrefix'])) {
            $result_PhonePrefix = $result['PhonePrefix'];
        } else {
            $result_PhonePrefix = '+';
        }

        return $result_PhonePrefix;
    }

}