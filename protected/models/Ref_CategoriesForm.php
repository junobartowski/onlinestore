<?php

/*
 * Description: Ref_CategoriesForm Model
 * @author: JunJun S. Hernandez <hernandezjunjun28@gmail.com>
 * DateCreated: 2014-12-10
 * Country: Philippines
 */

class Ref_CategoriesForm extends CFormModel {

    public $_connection;

    public function __construct() {
        $this->_connection = Yii::app()->db;
    }

    public function getAllCategories() {
        $connection = $this->_connection;
        $sql = "SELECT DISTINCT ID, Name, HasCondition FROM ref_categories ORDER BY Name ASC";
        $command = $connection->createCommand($sql);
        $result = $command->queryAll();

        if (isset($result)) {
            $categories = $result;
        } else {
            $categories = array();
        }

        return $categories;
    }

    public function isCategoryExisting($categoryID) {
        $connection = $this->_connection;
        $sql = "SELECT COUNT(ID) ctrID FROM ref_categories WHERE ID = :categoryID";
        $command = $connection->createCommand($sql);
        $command->bindValues(array(":categoryID" => $categoryID));
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