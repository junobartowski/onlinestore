<?php

/*
 * Description: ReceiptsForm Model
 * @author: JunJun S. Hernandez <hernandezjunjun28@gmail.com>
 * DateCreated: 2014-12-10
 * Country: Philippines
 */

class ReceiptsForm extends CFormModel {

    public $_connection;
    public $startDate;
    public $endDate;

    public function __construct() {
        $this->_connection = Yii::app()->db;
    }

    public function saveReceipt($transactionID, $firstName, $lastName, $currency, $amount, $method, $dateTime, $state, $payerEmailAddress, $payerID, $shippingRecipient, $shippingLine1, $shippingLine2, $shippingCity, $shippingState, $shippingPostalCode, $shippingCountryCode, $itemID, $accountID) {
        if (!is_null($shippingRecipient)) {
            $shippingRecipient = $shippingRecipient;
        } else {
            $shippingRecipient = "";
        }
        if (!is_null($shippingLine1)) {
            $shippingLine1 = $shippingLine1;
        } else {
            $shippingLine1 = "";
        }
        if (!is_null($shippingLine2)) {
            $shippingLine2 = $shippingLine2;
        } else {
            $shippingLine2 = "";
        }
        if (!is_null($shippingCity)) {
            $shippingCity = $shippingCity;
        } else {
            $shippingCity = "";
        }
        if (!is_null($shippingState)) {
            $shippingState = $shippingState;
        } else {
            $shippingState = "";
        }
        if (!is_null($shippingPostalCode)) {
            $shippingPostalCode = $shippingPostalCode;
        } else {
            $shippingPostalCode = "";
        }
        if (!is_null($shippingCountryCode)) {
            $shippingCountryCode = $shippingCountryCode;
        } else {
            $shippingCountryCode = "";
        }
        $connection = $this->_connection;
        $beginTrans = $connection->beginTransaction();
        $query1 = "INSERT INTO receipts (TransactionID, FirstName, LastName, Currency, Amount, Method, DateTime, State, PayerEmailAddress, PayerID, ShippingRecipient, ShippingLine1, ShippingLine2, ShippingCity, ShippingState, ShippingPostalCode, ShippingCountryCode, ItemID, BoughtByID)
                          VALUES (:transactionID, :firstName, :lastName, :currency, :amount, :method, :dateTime, :state, :payerEmailAddress, :payerID, :shippingRecipient, :shippingLine1, :shippingLine2, :shippingCity, :shippingState, :shippingPostalCode, :shippingCountryCode, :itemID, :accountID)";
        $command1 = $connection->createCommand($query1);
        $command1->bindValues(array(":transactionID" => $transactionID, ":firstName" => $firstName, ":lastName" => $lastName, ":currency" => $currency, ":amount" => $amount, ":method" => $method, ":dateTime" => $dateTime, ":state" => $state, ":payerEmailAddress" => $payerEmailAddress, ":payerID" => $payerID, ":shippingRecipient" => $shippingRecipient, ":shippingLine1" => $shippingLine1, ":shippingLine2" => $shippingLine2, ":shippingCity" => $shippingCity, ":shippingState" => $shippingState, ":shippingPostalCode" => $shippingPostalCode, ":shippingCountryCode" => $shippingCountryCode, ":itemID" => $itemID, ":accountID" => $accountID));
        $command1->execute();
        try {
            $lastInsertedID = $this->_connection->getLastInsertID();

            $status = CommonForm::ITEM_STATUS_SOLD;
            $query2 = "UPDATE items SET Status = :status, DateUpdated = NOW(6) WHERE ID = :itemID";
            $command2 = $connection->createCommand($query2);
            $command2->bindValues(array(":itemID" => $itemID, ":status" => $status));
            $result = $command2->execute();

            if ($result == 1) {
                $beginTrans->commit();
                return $lastInsertedID;
            } else {
                $beginTrans->rollback();
                return false;
            }
        } catch (PDOException $e) {
            $beginTrans->rollback();
            return false;
        }
    }

    public function getReceiptByID($ID) {
        $connection = $this->_connection;
        $sql = "SELECT * FROM receipts INNER JOIN items ON items.ID = receipts.itemID WHERE receipts.ID = :ID";
        $command = $connection->createCommand($sql);
        $command->bindValues(array(":ID" => $ID));
        $result = $command->queryRow();

        if (isset($result)) {
            $receipt = $result;
        } else {
            $receipt = '';
        }

        return $receipt;
    }
    
    public function getAllTransactionsByAccountID($accountID) {
        $connection = $this->_connection;
        $query = "SELECT * FROM receipts WHERE BoughtByID = :accountID";
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