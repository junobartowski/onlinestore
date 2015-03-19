<?php

/*
 * Description: AccountsForm Model
 * @author: JunJun S. Hernandez <hernandezjunjun28@gmail.com>
 * DateCreated: 2014-12-10
 * Country: Philippines
 */

class AccountsForm extends CFormModel {

    public $_connection;

    public function __construct() {
        $this->_connection = Yii::app()->db;
    }

    public function signUp($firstName, $lastName, $sex, $countryCode, $postCode, $emailAddress, $mobileNumber, $username, $encryptedPassword, $activationCode) {
        $connection = $this->_connection;
        $beginTrans = $connection->beginTransaction();
        $accountTypeID = CommonForm::ACCOUNT_TYPE_USER;
        $status = CommonForm::STATUS_INACTIVE;
        $createdByAccountID = CommonForm::SIGNUP_SELF_CREATED;
        $queryAccounts = "INSERT INTO accounts (Username, Password, AccountTypeID, Status, CreatedByAccountID, ActivationCode)
	    				  VALUES (:username, :password, :accountTypeID, :status, :createdByAccountID, :activationCode)";
        $accountsCommand = $connection->createCommand($queryAccounts);
        $accountsCommand->bindValues(array(":username" => $username, ":password" => $encryptedPassword, ":accountTypeID" => $accountTypeID, ":status" => $status, ":createdByAccountID" => $createdByAccountID, ":activationCode" => $activationCode));
        $accountsCommand->execute();
        try {
            $accountID = $this->_connection->getLastInsertID();
            $queryAccountDetails = "INSERT INTO accountdetails(AccountID, FirstName, LastName, Sex, EmailAddress, MobileNumber, CountryCode, PostCode)
	    				  	  		VALUES (:accountID, :firstName, :lastName, :sex, :emailAddress, :mobileNumber, :countryCode, :postCode)";
            $accountDetailsCommand = $connection->createCommand($queryAccountDetails);
            $accountDetailsCommand->bindValues(array(":accountID" => $accountID, ":firstName" => $firstName, ":lastName" => $lastName, ":sex" => $sex, ":emailAddress" => $emailAddress, ":mobileNumber" => $mobileNumber, ":countryCode" => $countryCode, ":postCode" => $postCode));
            $accountDetailsCommand->execute();
            try {
                $_administrationForm = new AdministrationForm();
                $accountActivationSwitch = $_administrationForm->getAccountActivationSwitch();
                if ($accountActivationSwitch == CommonForm::ACCOUNT_ACTIVATIONSWITCH_ON) {
                    $subject = CommonForm::SIGNUP_EMAIL_SUBJECT_WITH_ACTIVATION;
                    $emailMessageContentCode = CommonForm::ERROR_CODE_FUNCTION_EMAIL_SIGNUP_WITH_ACTIVATION;
                    $message = CommonForm::sendMail($emailAddress, $subject, $emailMessageContentCode, $firstName, "", $lastName, $activationCode);
                    if ($message == CommonForm::ERROR_CODE_NO_ERROR) {
                        $beginTrans->commit();
                        return CommonForm::SUCCESS_SAVING_ACCOUNT_WITH_ACTIVATION();
                    } else {
                        $beginTrans->rollback();
                        return CommonForm::FAILED_SIGNUP_SEND_EMAIL_NO_ACTIVATION($message);
                    }
                } else {
                    $subject = CommonForm::SIGNUP_EMAIL_SUBJECT_NO_ACTIVATION;
                    $emailMessageContentCode = CommonForm::ERROR_CODE_FUNCTION_EMAIL_SIGNUP_NO_ACTIVATION;
                    $message = CommonForm::sendMail($emailAddress, $subject, $emailMessageContentCode, $firstName, "", $lastName);
                    if ($message == CommonForm::ERROR_CODE_NO_ERROR) {
                        $beginTrans->commit();
                        return CommonForm::SUCCESS_SAVING_ACCOUNT_NO_ACTIVATION();
                    } else {
                        $beginTrans->rollback();
                        return CommonForm::FAILED_SIGNUP_SEND_EMAIL_NO_ACTIVATION($message);
                    }
                }
            } catch (PDOException $e) {
                $beginTrans->rollback();
                return CommonForm::FAILED_SAVING_ACCOUNT_DETAILS();
            }
        } catch (PDOException $e) {
            $beginTrans->rollback();
            return CommonForm::FAILED_SAVING_ACCOUNT();
        }
    }

//Start of ajax live checking
    public function getUsernameByUsername($username) {
        $connection = $this->_connection;
        $sql = "SELECT Username FROM accounts WHERE Username = :username";
        $command = $connection->createCommand($sql);
        $command->bindValues(array(":username" => $username));
        $result = $command->queryRow();

        if (isset($result['Username'])) {
            $user = $result['Username'];
        } else {
            $user = '';
        }

        return $user;
    }

//End of ajax live checking
    public function isUsernameExisting($username) {
        $connection = $this->_connection;
        $sql = "SELECT COUNT(ID) ctrID FROM accounts WHERE Username = :username";
        $command = $connection->createCommand($sql);
        $command->bindValues(array(":username" => $username));
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

    public function getAdministratorPasswordByEmailAddress($emailAddress) {
        $accountTypeID = CommonForm::ACCOUNT_TYPE_ADMINISTRATOR;
        $administratorEmailAddress = array();
        $connection = $this->_connection;
        $sql = "SELECT a.Password FROM accounts a
        		INNER JOIN accountdetails ad ON ad.AccountID = a.ID WHERE ad.EmailAddress = :emailAddress AND a.AccountTypeID = :accountTypeID";
        $command = $connection->createCommand($sql);
        $command->bindValues(array(":emailAddress" => $emailAddress, ":accountTypeID" => $accountTypeID));
        $result = $command->queryRow();

        if (isset($result)) {
            $administratorPassword = $result['Password'];
        }

        return $administratorPassword;
    }

    public function getAccountIDByEmailAddressAndPassword($emailAddress, $encryptedPassword) {
        $connection = $this->_connection;
        $sql = "SELECT a.ID FROM accounts a
        		INNER JOIN accountdetails ad ON ad.AccountID = a.ID WHERE ad.EmailAddress = :emailAddress AND a.Password = :encryptedPassword";
        $command = $connection->createCommand($sql);
        $command->bindValues(array(":emailAddress" => $emailAddress, ":encryptedPassword" => $encryptedPassword));
        $result = $command->queryRow();

        if (isset($result['ID'])) {
            $accountID = $result['ID'];
            if ($accountID > 0) {
                return $accountID;
            } else {
                return 0;
            }
        } else {
            return 0;
        }
    }

    public function getAccountIDANDAccountTypeIDAndStatusByEmailAddressAndPassword($emailAddress, $encryptedPassword) {
        $connection = $this->_connection;
        $sql = "SELECT a.ID, a.AccountTypeID, a.Status FROM accounts a
        		INNER JOIN accountdetails ad ON ad.AccountID = a.ID WHERE ad.EmailAddress = :emailAddress AND a.Password = :encryptedPassword";
        $command = $connection->createCommand($sql);
        $command->bindValues(array(":emailAddress" => $emailAddress, ":encryptedPassword" => $encryptedPassword));
        $result = $command->queryRow();

        if (isset($result)) {
            if (!empty($result)) {
                return $result;
            } else {
                return array();
            }
        } else {
            return array();
        }
    }

    public function getAccountIDByUsernameAndPassword($username, $encryptedPassword) {
        $connection = $this->_connection;
        $sql = "SELECT ID FROM accounts WHERE Username = :username AND Password = :encryptedPassword";
        $command = $connection->createCommand($sql);
        $command->bindValues(array(":username" => $username, ":encryptedPassword" => $encryptedPassword));
        $result = $command->queryRow();

        if (isset($result['ID'])) {
            $accountID = $result['ID'];
            if ($accountID > 0) {
                return $accountID;
            } else {
                return 0;
            }
        } else {
            return 0;
        }
    }

    public function getAccountTypeIDByID($accountID) {
        $connection = $this->_connection;
        $sql = "SELECT AccountTypeID FROM accounts WHERE ID = :accountID";
        $command = $connection->createCommand($sql);
        $command->bindValues(array(":accountID" => $accountID));
        $result = $command->queryRow();

        if (isset($result['AccountTypeID'])) {
            $accountTypeID = $result['AccountTypeID'];
            return $accountTypeID;
        } else {
            return 0;
        }
    }

    public function getAccountTypeIDByIDWithActiveStatus($accountID) {
        $connection = $this->_connection;
        $sql = "SELECT AccountTypeID FROM accounts WHERE ID = :accountID AND Status = 1";
        $command = $connection->createCommand($sql);
        $command->bindValues(array(":accountID" => $accountID));
        $result = $command->queryRow();

        if (isset($result['AccountTypeID'])) {
            $accountTypeID = $result['AccountTypeID'];
            return $accountTypeID;
        } else {
            return 0;
        }
    }

    public function getAccountIDAndAccountTypeIDAndStatusByUsernameAndPassword($username, $encryptedPassword) {
        $connection = $this->_connection;
        $sql = "SELECT ID, AccountTypeID, Status FROM accounts WHERE Username = :username AND Password = :encryptedPassword";
        $command = $connection->createCommand($sql);
        $command->bindValues(array(":username" => $username, ":encryptedPassword" => $encryptedPassword));
        $result = $command->queryRow();

        if (isset($result)) {
            if (!empty($result)) {
                return $result;
            } else {
                return array();
            }
        } else {
            return array();
        }
    }

    public function activateAccount($accountID, $activatedByID) {
        $activationAttempts = 0;
        $status = CommonForm::STATUS_ACTIVE;
        $connection = $this->_connection;
        $beginTrans = $connection->beginTransaction();
        $sql = "UPDATE accounts SET Status = :status, DateActivated = NOW(), ActivatedByID = :activatedByID, ActivationAttempts = :activationAttempts WHERE ID = :accountID";
        $command = $connection->createCommand($sql);
        $command->bindValues(array(":accountID" => $accountID, ":status" => $status, ":activatedByID" => $activatedByID, ":activationAttempts" => $activationAttempts));
        $command->execute();
        try {
            $beginTrans->commit();
            return CommonForm::ERROR_CODE_NO_ERROR;
        } catch (PDOException $e) {
            $beginTrans->rollback();
            return CommonForm::ERROR_CODE_ACTIVATE_ACCOUNT;
        }
    }

    public function getActivationAttemptsByAccountID($accountID) {
        $connection = $this->_connection;
        $sql = "SELECT ActivationAttempts FROM accounts WHERE ID = :accountID";
        $command = $connection->createCommand($sql);
        $command->bindValues(array(":accountID" => $accountID));
        $result = $command->queryRow();

        if (isset($result['ActivationAttempts'])) {
            $result = (int) $result['ActivationAttempts'];
        } else {
            $result = 0;
        }

        return $result;
    }

    public function getActivationCodeByAccountID($accountID) {
        $connection = $this->_connection;
        $sql = "SELECT ActivationCode FROM accounts WHERE ID = :accountID";
        $command = $connection->createCommand($sql);
        $command->bindValues(array(":accountID" => $accountID));
        $result = $command->queryRow();

        if (isset($result)) {
            $result = $result['ActivationCode'];
        } else {
            $result = 0;
        }

        return $result;
    }

    public function updateActivationAttempts($accountID) {
        $connection = $this->_connection;
        $beginTrans = $connection->beginTransaction();
        $attempts = $this->getActivationAttemptsByAccountID($accountID);
        $attemptsPlusOne = $attempts + 1;
        $sql = "UPDATE accounts SET ActivationAttempts = :activationAttempts WHERE ID = :accountID";
        $command = $connection->createCommand($sql);
        $command->bindValues(array(":accountID" => $accountID, ":activationAttempts" => $attemptsPlusOne));
        $command->execute();
        try {
            $beginTrans->commit();
            return CommonForm::ERROR_CODE_NO_ERROR;
        } catch (PDOException $e) {
            $beginTrans->rollback();
            return CommonForm::ERROR_CODE_UPDATE_ACTIVATION;
        }
    }

    public function lockAccountByAccount($accountID, $accountTypeID) {
        $accountTypeID = CommonForm::ACCOUNT_TYPE_USER;
        if ($accountTypeID == CommonForm::ACCOUNT_TYPE_USER) {
            $status = CommonForm::STATUS_LOCKED_BY_SELF;
        } else {
            $status = CommonForm::STATUS_LOCKED_BY_THE_ADMINISTRATOR;
        }
        $connection = $this->_connection;
        $beginTrans = $connection->beginTransaction();
        $sql = "UPDATE accounts SET Status = :status WHERE ID = :accountID";
        $command = $connection->createCommand($sql);
        $command->bindValues(array(":accountID" => $accountID, ":status" => $status));
        $command->execute();
        try {
            $beginTrans->commit();
            return CommonForm::ERROR_CODE_NO_ERROR;
        } catch (PDOException $e) {
            $beginTrans->rollback();
            return CommonForm::ERROR_CODE_LOCK_ACCOUNT;
        }
    }

}