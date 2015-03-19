<?php

/*
 * Description: UserController Controller
 * @author: JunJun S. Hernandez <hernandezjunjun28@gmail.com>
 * DateCreated: 2014-12-10
 * Country: Philippines
 */

class UserController extends Controller {

    public function actionIndex() {
        $this->render('home');
    }

    public function actionItem() {
        $this->render('item');
    }

    public function actionTransactions() {
        $_Ref_TransactionTypesForm = new Ref_TransactionTypesForm();
        $transactionTypes = $_Ref_TransactionTypesForm->getAllActiveTransactionTypes();
        $_ReceiptsForm = new ReceiptsForm();
        $this->render('transactions', array('transactionTypes' => $transactionTypes, 'model' => $_ReceiptsForm));
    }

    public function actionSell() {
        $_Ref_CategoriesForm = new Ref_CategoriesForm();
        $categories = $_Ref_CategoriesForm->getAllCategories();
        $_CharitiesForm = new CharitiesForm();
        $charities = $_CharitiesForm->getAllCharities();
        $this->render('sell', array('categories' => $categories, 'charities' => $charities));
    }

    public function actionSaveAndNextForm() {
        $_itemsForm = new ItemsForm();
        $_accountsForm = new AccountsForm();
        $_Ref_CategoriesForm = new Ref_CategoriesForm();
        $categories = $_Ref_CategoriesForm->getAllCategories();
        $_charitiesForm = new CharitiesForm();
        $charities = $_charitiesForm->getAllCharities();
        if (isset(Yii::app()->session['accountID'])) {
            $accountID = Yii::app()->session['accountID'];
        } else {
            $accountID = "";
        }
        if (isset($_POST['itemName'])) {
            $itemName = $_POST['itemName'];
        } else {
            $itemName = "";
        }
        if (isset($_POST['price'])) {
            $price = $_POST['price'];
        } else {
            $price = 0;
        }
        if (isset($_POST['shippingFee'])) {
            $shippingFee = $_POST['shippingFee'];
        } else {
            $shippingFee = 0;
        }
        if (isset($_POST['itemDescription'])) {
            $itemDescription = $_POST['itemDescription'];
        } else {
            $itemDescription = 0;
        }
        if (isset($_POST['category'])) {
            $category = $_POST['category'];
        } else {
            $category = 0;
        }
        if (isset($_POST['itemCondition'])) {
            $itemCondition = $_POST['itemCondition'];
        } else {
            $itemCondition = 0;
        }
        if (isset($_POST['charity'])) {
            $charity = $_POST['charity'];
        } else {
            $charity = 0;
        }
        if (isset($_POST['forType'])) {
            $forType = $_POST['forType'];
        } else {
            $forType = 0;
        }
        if (isset($_POST['donation'])) {
            $donation = $_POST['donation'];
        } else {
            $donation = 0;
        }

        if ($accountID != "") {
            if ($accountID != 0) {
                $accountTypeID = $_accountsForm->getAccountTypeIDByIDWithActiveStatus($accountID);
                if ($accountTypeID != 0) {
                    if ($accountTypeID == CommonForm::ACCOUNT_TYPE_USER) {
                        if (strlen($itemName) >= Yii::app()->params['minimumItemNameLength']) {
                            if (strlen($itemName) <= Yii::app()->params['maximumItemNameLength']) {
                                if (strlen($itemDescription) >= Yii::app()->params['minimumItemDescriptionLength']) {
                                    if (strlen($itemDescription) <= Yii::app()->params['maximumItemDescriptionLength']) {
                                        if ($price > 0) {
                                            if ($price <= Yii::app()->params['maximumItemPrice']) {
                                                if ($category > 0) {
                                                    $categoryID = $category;
                                                    $isCategoryExisting = $_Ref_CategoriesForm->isCategoryExisting($categoryID);
                                                    if ($isCategoryExisting == true) {
                                                        if ($itemCondition == 1 || $itemCondition == 2 || $itemCondition == 3) {
                                                            if ($charity > 0) {
                                                                $charityID = $charity;
                                                                $isCharityExisting = $_charitiesForm->isCharityExisting($charityID);
                                                                if ($isCharityExisting == true) {
                                                                    if(is_numeric($forType)) {
                                                                        if($forType == 1 || $forType == '1' || $forType == 2 || $forType == '2') {
                                                                            if ($donation > 0) {
                                                                                if ($donation <= $price) {
                                                                                    $id = Yii::app()->session['accountID'];
                                                                                    $registerItems = $_itemsForm->registerItems($itemName, $categoryID, $itemCondition, $charityID, $price, $shippingFee, $donation, $itemDescription, $forType, $accountID);
                                                                                    if ($registerItems != false) {
                                                                                        $hasError = CommonForm::ERROR_CODE_NO_ERROR;
                                                                                        $message = 'Item successfully saved.';
                                                                                    } else {
                                                                                        $hasError = CommonForm::ERROR_CODE_DEFAULT;
                                                                                        $message = 'Unexpected error occured. Please resubmit the form.';
                                                                                    }
                                                                                } else {
                                                                                    $hasError = CommonForm::ERROR_CODE_DEFAULT;
                                                                                    $message = 'Donation must not be greater than the item price.';
                                                                                }
                                                                            } else {
                                                                                $hasError = CommonForm::ERROR_CODE_DEFAULT;
                                                                                $message = 'Donation is required for your chosen Charity.';
                                                                            }
                                                                        } else {
                                                                            $hasError = CommonForm::ERROR_CODE_DEFAULT;
                                                                            $message = 'Type is not supported.';
                                                                        }
                                                                    } else {
                                                                        $hasError = CommonForm::ERROR_CODE_DEFAULT;
                                                                        $message = 'Type is required.';
                                                                    }
                                                                } else {
                                                                    $hasError = CommonForm::ERROR_CODE_DEFAULT;
                                                                    $message = 'Charity is not supported.';
                                                                }
                                                            } else {
                                                                $registerItems = $_itemsForm->registerItems($itemName, $categoryID, $itemCondition, $charityID, $price, $shippingFee, $donation, $description, $id);
                                                                if ($registerItems != false) {
                                                                    $hasError = CommonForm::ERROR_CODE_NO_ERROR;
                                                                    $message = 'Item successfully saved.';
                                                                } else {
                                                                    $hasError = CommonForm::ERROR_CODE_DEFAULT;
                                                                    $message = 'Unexpected error occured. Please resubmit the form.';
                                                                }
                                                            }
                                                        } else {
                                                            $hasError = CommonForm::ERROR_CODE_DEFAULT;
                                                            $message = 'Please select a condition.';
                                                        }
                                                    } else {
                                                        $hasError = CommonForm::ERROR_CODE_DEFAULT;
                                                        $message = 'Category not supported.';
                                                    }
                                                } else {
                                                    $hasError = CommonForm::ERROR_CODE_DEFAULT;
                                                    $message = 'Category is required.';
                                                }
                                            } else {
                                                $hasError = CommonForm::ERROR_CODE_DEFAULT;
                                                $message = 'Price is too large. It is not allowed at the moment.';
                                            }
                                        } else {
                                            $hasError = CommonForm::ERROR_CODE_DEFAULT;
                                            $message = 'Price is required.';
                                        }
                                    } else {
                                        $hasError = 1;
                                        $message = 'Description must be equal or more than ' . Yii::app()->params['minimumItemNameLength'] . ' and less than or equal to ' . Yii::app()->params['maximumItemNameLength'] . ' characters long.';
                                    }
                                } else {
                                    $hasError = CommonForm::ERROR_CODE_DEFAULT;
                                    $message = 'Description must be equal or more than ' . Yii::app()->params['minimumItemNameLength'] . ' and less than or equal to ' . Yii::app()->params['maximumItemNameLength'] . ' characters long.';
                                }
                            } else {
                                $hasError = CommonForm::ERROR_CODE_DEFAULT;
                                $message = 'Item name must be equal or more than ' . Yii::app()->params['minimumItemNameLength'] . ' and less than or equal to ' . Yii::app()->params['maximumItemNameLength'] . ' characters long.';
                            }
                        } else {
                            $hasError = CommonForm::ERROR_CODE_DEFAULT;
                            $message = 'Item name must be equal or more than ' . Yii::app()->params['minimumItemNameLength'] . ' and less than or equal to ' . Yii::app()->params['maximumItemNameLength'] . ' characters long.';
                        }
                    } else {
                        $hasError = CommonForm::ERROR_CODE_DEFAULT;
                        $message = 'You are not authorized for this action.';
                    }
                } else {
                    $hasError = CommonForm::ERROR_CODE_DEFAULT;
                    $message = 'You are not authorized for this action.';
                }
            } else {
                $hasError = CommonForm::ERROR_CODE_DEFAULT;
                $message = 'Session not found.';
            }
        } else {
            $hasError = CommonForm::ERROR_CODE_DEFAULT;
            $message = 'Session not found.';
        }
        $submitFormResult = array('errorCode' => CommonForm::ERROR_CODE_DEFAULT, 'errorMessage' => $message);
        $postedData = array('itemName' => $itemName, 'price' => $price, 'shippingFee' => $shippingFee, 'itemDescription' => $itemDescription, 'category' => $category, 'condition' => $itemCondition, 'charity' => $charity, 'donation' => $donation);
        if ($hasError == CommonForm::ERROR_CODE_NO_ERROR) {

            $directory = getcwd() . '/images/uploads/items/' . $registerItems;
            $superUserMode = Yii::app()->params['superUserMode'];
            $groupMode = Yii::app()->params['groupMode'];
            if (CommonForm::makeDirectoryForItems($directory, $superUserMode, $groupMode) == true) {
                Yii::app()->session['itemsFolderName'] = $registerItems;
                $this->render('saveandnext', array('postedData' => $postedData));
            } else {
                $message = 'Unexpected error occured. Please try submitting the form again.';
                $submitFormResult = array('errorCode' => 1, 'errorMessage' => $message);
                $this->render('sell', array('categories' => $categories, 'charities' => $charities, 'postedData' => $postedData, 'submitFormResult' => $submitFormResult));
            }
        } else {
            $this->render('sell', array('categories' => $categories, 'charities' => $charities, 'postedData' => $postedData, 'submitFormResult' => $submitFormResult));
        }
    }

    public function actionServer() {
        $_uploadHandler = new UploadHandler();
        if ($_uploadHandler) {
            
        } else {
            echo "Unexpected error encountered";
        }
    }

    public function actionSellItemFinish() {
        $itemID = Yii::app()->session['itemsFolderName'];
        $_itemsForm = new ItemsForm();
        if (isset($_POST['itemProfilePhoto'])) {
            $filename = $_POST['itemProfilePhoto'];
        } else {
            $filename = "";
        }

        if ($filename != "") {
            $updateItemProfilePhotoFilenameAndStatusByID = $_itemsForm->updateItemProfilePhotoFilenameAndStatusByID($itemID, $filename);
            if ($updateItemProfilePhotoFilenameAndStatusByID == CommonForm::ERROR_CODE_NO_ERROR) {
                unset(Yii::app()->session['itemsFolderName']);
                $_itemsForm = new ItemsForm();
                $items = $_itemsForm->getAllItems();
                $message = 'Success. Your new item is now listed.';
                $submitFormResult = array('errorCode' => CommonForm::ERROR_CODE_NO_ERROR, 'errorMessage' => $message);
                $this->redirect(Yii::app()->createUrl('/item/multiple', array('submitFormResult' => $submitFormResult, "items" => $items, 'itemID' => $itemID)));
            } else {
                $message = 'Unexpected error occured. Please try submitting the form again.';
                $submitFormResult = array('errorCode' => CommonForm::ERROR_CODE_SELL_ITEM_FINISH, 'errorMessage' => $message);
                $this->render('error', array('submitFormResult' => $submitFormResult, 'itemID' => $itemID));
            }
        } else {
            $message = 'Please select a Profile Photo for your new item.';
            $submitFormResult = array('errorCode' => CommonForm::ERROR_CODE_SELL_ITEM_FINISH, 'errorMessage' => $message);
            $this->render('saveandnext', array('submitFormResult' => $submitFormResult, 'itemID' => $itemID));
        }
    }

    public function actionSearchAllTransactions() {
        if (Yii::app()->request->isAjaxRequest) {
            $accountID = Yii::app()->session['accountID'];
            $_receiptsForm = new ReceiptsForm();
            $_directDonationsForm = new DirectDonationForm();
            $page = Yii::app()->request->getParam('page');
            $limit = Yii::app()->request->getParam('rows');
            $sidx = Yii::app()->request->getParam('sidx');
            $rows = array();
            $searchResultReceipts = $_receiptsForm->getAllTransactionsByAccountID($accountID);
            $searchResultDirectDonations = $_directDonationsForm->getAllTransactionsByAccountID($accountID);
            if (!$sidx) {
                $sidx = 1;
            }
            $searchResultReceiptsCount = count($searchResultReceipts);
            $searchResultDirectDonationsCount = count($searchResultDirectDonations);
            $count = $searchResultReceiptsCount + $searchResultDirectDonationsCount;

            if ($count > 0) {
                $total_pages = ceil($count / $limit);
            } else {
                $total_pages = 1;
            }
            if ($page > $total_pages) {
                $page = $total_pages;
            }

            if (!empty($searchResultReceipts) && $searchResultReceiptsCount > 0) {
                foreach ($searchResultReceipts as $value) {
                    $ID = $value["ID"];
                    $transactionID = $value["TransactionID"];
                    $itemName = $value["ItemID"];
                    $amt = $value["Amount"];
                    $amount = number_format($amt, 2, ".", ",");
                    $method = $value["Method"];
                    $dateTime = $value["DateTime"];
                    $itemID = $value["ItemID"];
                    $transactionType = 'Bought';
                    $rowReceipts = array('TransactionID' => $transactionID,
                        'ItemID' => "<center><a href='#' id='checkLink' itemID=" . $itemID . " itemName=" . $itemName . "'><div id='linkButton'>" . $itemName . "</div></a></center>",
                        'Amount' => $amount,
                        'TransactionType' => $transactionType,
                        'Method' => $method,
                        'Datetime' => $dateTime);
                    array_push($rows, $rowReceipts);
                }
            }
            if (!empty($searchResultDirectDonations) && $searchResultDirectDonationsCount > 0) {
                foreach ($searchResultDirectDonations as $value) {
                    $ID = $value["ID"];
                    $transactionID = $value["TransactionID"];
                    $itemName = 'Direct Donation to ' . $value["CharityID"];
                    $amt = $value["Amount"];
                    $amount = number_format($amt, 2, ".", ",");
                    $method = $value["Method"];
                    $dateTime = $value["DateTime"];
                    $itemID = $value["CharityID"];
                    $transactionType = 'Direct Donation';
                    $rowDirectDonations = array('TransactionID' => $transactionID,
                        'ItemID' => "<center><a href='#' id='checkLink' itemID=" . $value['CharityID'] . " itemName=" . $itemName . "'><div id='linkButton'>" . $itemName . "</div></a></center>",
                        'Amount' => $amount,
                        'TransactionType' => $transactionType,
                        'Method' => $method,
                        'Datetime' => $dateTime);
                    array_push($rows, $rowDirectDonations);
                }
            }
            echo CJSON::encode($rows);
        }
    }

    public function actionError() {
        $this->render('../error');
    }

}