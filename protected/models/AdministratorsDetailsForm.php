<?php

/*
 * Description: AccountDetailsForm Model
 * @author: JunJun S. Hernandez <hernandezjunjun28@gmail.com>
 * DateCreated: 2014-12-10
 * Country: Philippines
 */

class AdministratorsDetailsForm extends CFormModel {

    public $_connection;

    public function __construct() {
        $this->_connection = Yii::app()->db;
    }

//Start of ajax live checking
    public function getEmailAddressByEmailAddress($emailAddress) {
        $connection = $this->_connection;
        $sql = "SELECT EmailAddress FROM administratordetails WHERE EmailAddress = :emailAddress";
        $command = $connection->createCommand($sql);
        $command->bindValues(array(":emailAddress" => $emailAddress));
        $result = $command->queryRow();

        if (isset($result)) {
            $email = $result['EmailAddress'];
        } else {
            $email = '';
        }

        return $email;
    }

//End of ajax live checking
    public function isEmailAddressExisting($emailAddress) {
        $connection = $this->_connection;
        $sql = "SELECT COUNT(ID) ctrID FROM administratordetails WHERE EmailAddress = :emailAddress";
        $command = $connection->createCommand($sql);
        $command->bindValues(array(":emailAddress" => $emailAddress));
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

    public function isMobileNumberExisting($mobileNumber) {
        $connection = $this->_connection;
        $sql = "SELECT COUNT(ID) ctrID FROM administratordetails WHERE MobileNumber = :mobileNumber";
        $command = $connection->createCommand($sql);
        $command->bindValues(array(":mobileNumber" => $mobileNumber));
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

    public function isMobileNumberAndCountryCodeMatched($mobileNumber, $countryCode) {
        $connection = $this->_connection;
        $sql = "SELECT COUNT(ID) ctrID FROM administratordetails WHERE MobileNumber = :mobileNumber AND CountryCode = :countryCode";
        $command = $connection->createCommand($sql);
        $command->bindValues(array(":mobileNumber" => $mobileNumber, ":countryCode" => $countryCode));
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

    public function getAdministratorEmailAddress() {
        $accountTypeID = CommonForm::ACCOUNT_TYPE_ADMINISTRATOR;
        $administratorEmailAddress = array();
        $connection = $this->_connection;
        $sql = "SELECT ad.EmailAddress FROM administratordetails ad
        		INNER JOIN accounts a ON a.ID = ad.AccountID WHERE a.AccountTypeID = :accountTypeID";
        $command = $connection->createCommand($sql);
        $command->bindValues(array(":accountTypeID" => $accountTypeID));
        $result = $command->queryRow();

        if (isset($result)) {
            $administratorEmailAddress = $result['EmailAddress'];
        }

        return $administratorEmailAddress;
    }

    public function getFirstNameByAccountID($accountID) {
        $connection = $this->_connection;
        $sql = "SELECT FirstName FROM administratordetails WHERE AccountID = :accountID";
        $command = $connection->createCommand($sql);
        $command->bindValues(array(":accountID" => $accountID));
        $result = $command->queryRow();

        if (isset($result)) {
            $firstName = $result['FirstName'];
        } else {
            $firstName = '';
        }

        return $firstName;
    }
    
}