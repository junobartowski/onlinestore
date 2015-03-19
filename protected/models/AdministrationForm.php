<?php

/*
 * Description: AdministrationForm Model
 * @author: JunJun S. Hernandez <hernandezjunjun28@gmail.com>
 * DateCreated: 2014-12-10
 * Country: Philippines
 */

class AdministrationForm extends CFormModel {

    public $_connection;

    public function __construct() {
        $this->_connection = Yii::app()->db;
    }

    public function getAccountActivationSwitch() {
        $accountActivationSwithID = CommonForm::ADMINISTRATION_ACCOUNT_ACTIVATION_SWITCH_ID;
        $connection = $this->_connection;
        $sql = "SELECT NumberValue FROM administration WHERE ID = :accountActivationSwithID";
        $command = $connection->createCommand($sql);
        $command->bindValues(array(":accountActivationSwithID" => $accountActivationSwithID));
        $result = $command->queryRow();
        if (isset($result['AccountActivationSwitch'])) {
            $result = (int) $result['AccountActivationSwitch'];
        } else {
            $result = 0;
        }

        return $result;
    }

}

?>