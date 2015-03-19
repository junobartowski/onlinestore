<?php

/*
 * Description: CharitiesForm Model
 * @author: JunJun S. Hernandez <hernandezjunjun28@gmail.com>
 * DateCreated: 2014-12-10
 * Country: Philippines
 */

class CharitiesForm extends CFormModel {

    public $_connection;

    public function __construct() {
        $this->_connection = Yii::app()->db;
    }

    public function getAllCharities() {
        $connection = $this->_connection;
        $sql = "SELECT DISTINCT ID, Name, Description, Website, FacebookPage, TwitterPage, Status FROM charities ORDER BY Name ASC";
        $command = $connection->createCommand($sql);
        $result = $command->queryAll();

        if (isset($result)) {
            $harities = $result;
        } else {
            $harities = array();
        }

        return $harities;
    }

    public function isCharityExisting($charityID) {
        $connection = $this->_connection;
        $sql = "SELECT COUNT(ID) ctrID FROM charities WHERE ID = :ID";
        $command = $connection->createCommand($sql);
        $command->bindValues(array(":ID" => $charityID));
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

    public function getCharityNameByID($charityID) {
        $connection = $this->_connection;
        $sql = "SELECT Name FROM charities WHERE ID = :ID";
        $command = $connection->createCommand($sql);
        $command->bindValues(array(":ID" => $charityID));
        $result = $command->queryRow();

        if (isset($result['Name'])) {
            return $result['Name'];
        } else {
            return "";
        }
    }

    public function saveCharity($data) {
        $connection = $this->_connection;
        $beginTrans = $connection->beginTransaction();
        $status = CommonForm::CHARITY_STATUS_INACTIVE;
        $createdByAccountID = Yii::app()->session['administratorID'];
        $query = "INSERT INTO accounts (Name,
            CountyID,
            PostCodeID,
            TelephoneNumber,
            MobileNumber,
            Description,
            Website,
            FacebookPage,
            TwitterPage,
            CreatedByAdministratorID,
            Status)
	    VALUES (:charityName, :countyID, :postCodeID, :telephoneNumber, :mobileNumber, :description, :website, :facebookPage, :twitterPage, :createdByAdministratorID, :status)";
        $command = $connection->createCommand($query);
        $command->bindValues(array(
            ":charityName" => $data['charityName'],
            ":countyID" => $data['countyID'],
            ":postCodeID" => $data['postCodeID'],
            ":telephoneNumber" => $data['telephoneNumber'],
            ":mobileNumber" => $data['mobileNumber'],
            ":description" => $data['description'],
            ":website" => $data['website'],
            ":facebookPage" => $data['facebookPage'],
            ":twitterPage" => $data['twitterPage'],
            ":createdByAdministratorID" => $createdByAccountID,
            ":status" => $status,));
        $command->execute();
        try {
            $lastInserted = $this->_connection->getLastInsertID();
            $beginTrans->commit();
            return $lastInserted;
        } catch (PDOException $e) {
            $beginTrans->rollback();
            return CommonForm::FAILED_SAVING_CHARITY();
        }
    }

}