<?php

/*
 * Description: ItemsForm Model
 * @author: JunJun S. Hernandez <hernandezjunjun28@gmail.com>
 * DateCreated: 2014-12-10
 * Country: Philippines
 */

class ItemsForm extends CFormModel {

    public $_connection;

    public function __construct() {
        $this->_connection = Yii::app()->db;
    }

    public function registerItems($itemName, $categoryID, $itemCondition, $charityID, $price, $shippingFee, $donation, $itemDescription, $forType, $id) {
        $status = CommonForm::ITEM_STATUS_INACTIVE;
        $buyToken = CommonForm::generateBuyToken();
        $connection = $this->_connection;
        $beginTrans = $connection->beginTransaction();
        $query = "INSERT INTO items (Name, CategoryID, ItemCondition, CharityID, Price, ShippingFee, Donation, Description, ForType, Status, CreatedByID, BuyToken)
	    				  VALUES (:itemName, :categoryID, :itemCondition, :charityID, :price, :shippingFee, :donation, :description, :forType, :status, :createdByID, :buyToken)";
        $command = $connection->createCommand($query);
        $command->bindValues(array(":itemName" => $itemName, ":categoryID" => $categoryID, ":itemCondition" => $itemCondition, ":charityID" => $charityID, ":price" => $price, ":shippingFee" => $shippingFee, ":donation" => $donation, ":description" => $itemDescription, ":forType" => $forType, ":status" => $status, ":createdByID" => $id, ":buyToken" => $buyToken));
        $command->execute();
        try {
            $lastInserted = $this->_connection->getLastInsertID();
            $beginTrans->commit();
            return $lastInserted;
        } catch (PDOException $e) {
            $beginTrans->rollback();
            return false;
        }
    }

    public function updateItemProfilePhotoFilenameAndStatusByID($ID, $filename) {
        $status = CommonForm::ITEM_STATUS_ACTIVE;
        $connection = $this->_connection;
        $beginTrans = $connection->beginTransaction();
        $query = "UPDATE items SET ProfilePhotoFilename = :profilePhotoFilename, Status = :status, DateUpdated = NOW(6) WHERE ID = :ID";
        $command = $connection->createCommand($query);
        $command->bindValues(array(":profilePhotoFilename" => $filename, ":status" => $status, ":ID" => $ID));
        $command->execute();
        try {
            $beginTrans->commit();
            return CommonForm::ERROR_CODE_NO_ERROR;
        } catch (PDOException $e) {
            $beginTrans->rollback();
            return CommonForm::ERROR_CODE_UPDATE_ITEM_STATUS;
        }
    }

    public function getAllItems() {
        $status = CommonForm::ITEM_STATUS_ACTIVE;
        $connection = $this->_connection;
        $beginTrans = $connection->beginTransaction();
        $query = "SELECT * FROM items WHERE Status IN (1, 3) ORDER BY DateUpdated DESC";
        $command = $connection->createCommand($query);
        $result = $command->queryAll();

        if (isset($result)) {
            $items = $result;
        } else {
            $items = array();
        }

        return $items;
    }
    
    public function getAllItemsWithUserData() {
        $status = CommonForm::ITEM_STATUS_ACTIVE;
        $connection = $this->_connection;
        $beginTrans = $connection->beginTransaction();
        $query = "SELECT i.*,i.ProfilePhotoFilename ItemProfilePhoto,
                    ad.ProfilePhotoFilename AccountProfilePhoto,
                    ad.ID AccountID,
                    ad.FirstName,
                    ad.LastName,
                    ad.Sex,
                    c.Name CountryName
                  FROM items i
                  INNER JOIN AccountDetails ad ON ad.AccountID = i.CreatedByID
                  INNER JOIN ref_countries c ON ad.CountryCode = c.Code
                  WHERE i.Status IN (1, 3) ORDER BY DateUpdated DESC";
        $command = $connection->createCommand($query);
        $result = $command->queryAll();

        if (isset($result)) {
            $items = $result;
        } else {
            $items = array();
        }

        return $items;
    }
    
    public function getSingleItemWithUserData($ID) {
        $connection = $this->_connection;
        $query = "SELECT i.*,i.ProfilePhotoFilename ItemProfilePhoto,
                    ad.ProfilePhotoFilename AccountProfilePhoto,
                    ad.ID AccountID,
                    ad.FirstName,
                    ad.LastName,
                    ad.Sex,
                    c.Name CountryName
                  FROM items i
                  INNER JOIN AccountDetails ad ON ad.AccountID = i.CreatedByID
                  INNER JOIN ref_countries c ON ad.CountryCode = c.Code
                  WHERE i.ID = :ID";
        $command = $connection->createCommand($query);
        $command->bindParam(":ID", $ID);
        $result = $command->queryRow();

        if (isset($result)) {
            $items = $result;
        } else {
            $items = array();
        }

        return $items;
    }

    public function getItemByID($ID) {
        $connection = $this->_connection;
        $query = "SELECT * FROM items WHERE ID = :ID";
        $command = $connection->createCommand($query);
        $command->bindValues(array(":ID" => $ID));
        $result = $command->queryRow();

        if (isset($result)) {
            $item = $result;
        } else {
            $item = array();
        }

        return $item;
    }
    
    public function updateBuyToken($ID, $buyToken) {
        $connection = $this->_connection;
        $beginTrans = $connection->beginTransaction();
        $query = "UPDATE items SET BuyToken = :buyToken WHERE ID = :ID";
        $command = $connection->createCommand($query);
        $command->bindValues(array(":ID" => $ID, ":buyToken" => $buyToken));
        $result = $command->execute();

        try {
            if ($result == 1) {
                $beginTrans->commit();
                return CommonForm::ERROR_CODE_NO_ERROR;
            } else {
                $beginTrans->rollback();
                return CommonForm::ERROR_CODE_UPDATE_BUY_TOKEN;
            }
        } catch (PDOException $e) {
            $beginTrans->rollback();
            return CommonForm::ERROR_CODE_UPDATE_BUY_TOKEN;
        }
    }
    
    public function getBuyToken($ID) {
        $connection = $this->_connection;
        $query = "SELECT BuyToken FROM items WHERE ID = :ID";
        $command = $connection->createCommand($query);
        $command->bindValues(array(":ID" => $ID));
        $result = $command->queryRow();

        if (isset($result)) {
            $token = $result['BuyToken'];
        } else {
            $token = "";
        }

        return $token;
    }

    public function updateItemStatusAsSoldByID($ID) {
        $status = CommonForm::ITEM_STATUS_SOLD;
        $connection = $this->_connection;
        $beginTrans = $connection->beginTransaction();
        $query = "UPDATE items SET Status = :status, DateUpdated = NOW(6) WHERE ID = :ID";
        $command = $connection->createCommand($query);
        $command->bindValues(array(":ID" => $ID, ":status" => $status));
        $result = $command->execute();

        try {
            if ($result == 1) {
                $beginTrans->commit();
                return CommonForm::ERROR_CODE_NO_ERROR;
            } else {
                $beginTrans->rollback();
                return CommonForm::ERROR_CODE_UPDATE_ITEM_STATUS;
            }
        } catch (PDOException $e) {
            $beginTrans->rollback();
            return CommonForm::ERROR_CODE_UPDATE_ITEM_STATUS;
        }
    }
    
    public function getAllTransactionsByAccountID($accountID) {
        $connection = $this->_connection;
        $query = "SELECT * FROM items INNER JOIN receipts ON receipts.ItemID = items.ID WHERE items.CreatedByID = :accountID";
        $command = $connection->createCommand($query);
        $command->bindValues(array(":accountID" => $accountID));
        $result = $command->queryAll();

        if (isset($result)) {
            $transactions = $result;
        } else {
            $transactions = array();
        }

        return $transactions;
    }

}