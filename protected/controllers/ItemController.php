<?php

/*
 * Description: ItemController Controller
 * @author: JunJun S. Hernandez <hernandezjunjun28@gmail.com>
 * DateCreated: 2014-12-10
 * Country: Philippines
 */

class ItemController extends Controller {

    public function actionIndex() {
        $_itemsForm = new ItemsForm();
        $items = $_itemsForm->getAllItems();
        $this->redirect(Yii::app()->createUrl('/item/multiple'));
    }

    public function actionMultiple() {
        $_itemsForm = new ItemsForm();
        $items = $_itemsForm->getAllItemsWithUserData();
        $this->render('multiple', array("items" => $items));
    }

    public function actionSingle() {
        if(isset($_GET['itemID'])) {
            $ID = $_GET['itemID'];
            $_itemsForm = new ItemsForm();
            $item = $_itemsForm->getSingleItemWithUserData($ID);
            $this->render('single', array('item' => $item));
        } else {
            $this->render('single');
        }
    }
    
    public function actionBuy() {
        $this->render('buy');
    }
    
    public function actionBuyerror() {
        $_itemsForm = new ItemsForm();
        $items = $_itemsForm->getAllItems();
        $this->render('buyerror', array("items" => $items));
    }

    public function actionViewItem() {
        if (Yii::app()->request->isAjaxRequest) {
            if (isset($_POST['itemID'])) {
                $ID = $_POST['itemID'];
                $_itemsForm = new ItemsForm();
                $item = $_itemsForm->getItemByID($ID);
                return $this->renderPartial('single', array('item' => $item));
            } else {
                return $this->renderPartial('single');
            }
        } else {
            return $this->renderPartial('single');
        }
    }

    public function actionBuyItem() {
        if (Yii::app()->request->isAjaxRequest) {
            if (isset($_POST['itemID'])) {
                $ID = $_POST['itemID'];
                $_itemsForm = new ItemsForm();
                $item = $_itemsForm->getItemByID($ID);
                return $this->renderPartial('buy', array('item' => $item));
            } else {
                return $this->renderPartial('buy');
            }
        } else {
            return $this->renderPartial('buy');
        }
    }

    public function actionBuyItemConfirmed() {
        if (isset($_POST['itemID'])) {
            $itemID = $_POST['itemID'];
            if ($itemID != "") {
                $_itemsForm = new ItemsForm();
                $item = $_itemsForm->getItemByID($itemID);
                if (!empty($item)) {
                    $status = $item['Status'];
                    if ($status == CommonForm::ITEM_STATUS_ACTIVE || $status == CommonForm::ITEM_STATUS_APPROVED_BY_CHARITY) {
                        $itemName = $item['Name'];
                        $itemQuantity = 1;
                        $amount = $item['Price'];
                        if ($status == CommonForm::ITEM_STATUS_ACTIVE) {
                            return $this->payPalRequest($itemID, $itemName, $itemQuantity, $amount);
                        } else {
                            $amount = $amount;
                            return $this->payPalRequest($itemID, $itemName, $itemQuantity, $amount);
                        }
                    } else {
                        $errorCode = CommonForm::ERROR_CODE_BUY_ITEM_NO_DETAILS;
                        $errorMessage = "Someone has already bought this item.";
                    }
                } else {
                    $errorCode = CommonForm::ERROR_CODE_BUY_ITEM_NO_DETAILS;
                    $errorMessage = "Could not retrieve item details.";
                }
            } else {
                $errorCode = CommonForm::ERROR_CODE_BUY_ITEM_NO_DETAILS;
                $errorMessage = "Could not retrieve item details.";
            }
        } else {
            $errorCode = CommonForm::ERROR_CODE_BUY_ITEM_NO_DETAILS;
            $errorMessage = "Could not retrieve item details";
        }
        return $this->errorMessageHandler($errorCode, $errorMessage);
    }
    
    public function actionBidItem() {
        if (Yii::app()->request->isAjaxRequest) {
            if (isset($_POST['itemID'])) {
                $ID = $_POST['itemID'];
                $_itemsForm = new ItemsForm();
                $item = $_itemsForm->getItemByID($ID);
                return $this->renderPartial('bid', array('item' => $item));
            } else {
                return $this->renderPartial('bid');
            }
        } else {
            return $this->renderPartial('bid');
        }
    }

    private function payPalCredentials() {
        session_start();
        define('CLIENT_ID', Yii::app()->params['payPalAppClientID']); //your PayPal client ID
        define('CLIENT_SECRET', Yii::app()->params['payPalAppSecret']); //PayPal Secret
        define('RETURN_URL', Yii::app()->params['defaultURLPrefix'] . Yii::app()->params['payPalReturnURL']); //return URL where PayPal redirects user
        define('CANCEL_URL', Yii::app()->params['defaultURLPrefix'] . Yii::app()->params['payPalCancelURL']); //cancel URL
        define('PP_CURRENCY', 'USD'); //Currency code
        define('PP_CONFIG_PATH', dirname(__FILE__)); //PayPal config path (sdk_config.ini)

        $PayPal = new PayPalForm();
        return $PayPal;
    }
    
    public function payPalRequest($itemID, $itemName, $itemQuantity, $amount) {
        $PayPal = $this->payPalCredentials();
        $errorCode = CommonForm::ERROR_CODE_DEFAULT;
        /*
          Note: DO NOT rely on item_price you get from products page, in production mode get only "item code"
          from the products page and then fetch its actual price from Database.
          Example :
          $results = $mysqli->query("SELECT item_name, item_price FROM products WHERE item_code= '$item_code'");
          while($row = $results->fetch_object()) {
          $item_name = $row->item_name;
          $item_price = item_price ;
          }
         */

        //set array of items you are selling, single or multiple
        $items = array(
            array('name' => $itemName, 'quantity' => (int) $itemQuantity, 'price' => (int) $amount, 'sku' => $itemID, 'currency' => PP_CURRENCY)
        );
        //calculate total amount of all quantity. 
        $totalAmount = ($itemQuantity * $amount);

        try { // try a payment request
            //if payment method is paypal
            $result = $PayPal->create_paypal_payment($totalAmount, PP_CURRENCY, '', $items, RETURN_URL, CANCEL_URL);

            //if payment method was PayPal, we need to redirect user to PayPal approval URL
            if ($result->state == "created" && $result->payer->payment_method == "paypal") {
                Yii::app()->session['itemID'] = $itemID;
                $_SESSION["payment_id"] = $result->id; //set payment id for later use, we need this to execute payment
                //header("location: " . $result->links[1]->href); //after success redirect user to approval URL 
                $responseData = array('errorCode' => 0, 'errorMessage' => 'Connected', 'urlRedirect' => $result->links[1]->href);
                echo CJSON::encode($responseData);
            }
        } catch (PPConnectionException $ex) {
            return $this->errorMessageHandler($errorCode, $ex->getMessage());
        } catch (Exception $ex) {
            return $this->errorMessageHandler($errorCode, $ex->getMessage());
        }
    }

    public function actionPaypalPayment() {
        $PayPal = $this->payPalCredentials();
        $errorCode = 1;
        ### After PayPal payment method confirmation, user is redirected back to this page with token and Payer ID ###
        if (isset($_GET["token"]) && isset($_GET["PayerID"]) && isset($_GET["paymentId"])) {
            $PayPal = new PayPalForm();
            try {
                $result = $PayPal->execute_payment($_GET["paymentId"], $_GET["PayerID"]);  //call execute payment function.

                if ($result->state == "approved") { //if state = approved continue..
                    //SUCCESS
                    //get transaction details
                    $transaction_id = $result->transactions[0]->related_resources[0]->sale->id;
                    $transaction_time = $result->transactions[0]->related_resources[0]->sale->create_time;
                    $transaction_currency = $result->transactions[0]->related_resources[0]->sale->amount->currency;
                    $transaction_amount = $result->transactions[0]->related_resources[0]->sale->amount->total;
                    $transaction_method = $result->payer->payment_method;
                    $transaction_state = $result->transactions[0]->related_resources[0]->sale->state;

                    //get payer details
                    $payer_first_name = $result->payer->payer_info->first_name;
                    $payer_last_name = $result->payer->payer_info->last_name;
                    $payer_email = $result->payer->payer_info->email;
                    $payer_id = $result->payer->payer_info->payer_id;

                    //get shipping details 
                    $shipping_recipient = $result->transactions[0]->item_list->shipping_address->recipient_name;
                    $shipping_line1 = $result->transactions[0]->item_list->shipping_address->line1;
                    $shipping_line2 = $result->transactions[0]->item_list->shipping_address->line2;
                    $shipping_city = $result->transactions[0]->item_list->shipping_address->city;
                    $shipping_state = $result->transactions[0]->item_list->shipping_address->state;
                    $shipping_postal_code = $result->transactions[0]->item_list->shipping_address->postal_code;
                    $shipping_country_code = $result->transactions[0]->item_list->shipping_address->country_code;

                    //Set session for later use, print_r($result); to see what is returned
                    $_SESSION["results"] = array(
                        'transaction_id' => $transaction_id,
                        'transaction_time' => $transaction_time,
                        'transaction_currency' => $transaction_currency,
                        'transaction_amount' => $transaction_amount,
                        'transaction_method' => $transaction_method,
                        'transaction_state' => $transaction_state,
                        'payer_first_name' => $payer_first_name,
                        'payer_last_name' => $payer_last_name,
                        'payer_email' => $payer_email,
                        'payer_id' => $payer_id,
                        'shipping_recipient' => $shipping_recipient,
                        'shipping_line1' => $shipping_line1,
                        'shipping_line2' => $shipping_line2,
                        'shipping_city' => $shipping_city,
                        'shipping_state' => $shipping_state,
                        'shipping_postal_code' => $shipping_postal_code,
                        'shipping_country_code' => $shipping_country_code
                    );

                    header("location: " . RETURN_URL); //$_SESSION["results"] is set, redirect back to order_process.php
                    exit();
                } else {
                    return $this->errorMessageHandler($errorCode, 'Payment was not approved! Please try again.');
                }
            } catch (PPConnectionException $ex) {
                return $this->errorMessageHandler($errorCode, $ex->getMessage());
            } catch (Exception $ex) {
                return $this->errorMessageHandler($errorCode, $ex->getMessage());
            }
        }

        if (isset($_SESSION["results"])) {
            $transactionDetails = $_SESSION["results"];
            $transactionID = $transactionDetails['transaction_id'];
            $dateTime = $transactionDetails['transaction_time'];
            $currency = $transactionDetails['transaction_currency'];
            $amount = $transactionDetails['transaction_amount'];
            $method = $transactionDetails['transaction_method'];
            $state = $transactionDetails['transaction_state'];
            $firstName = $transactionDetails['payer_first_name'];
            $lastName = $transactionDetails['payer_last_name'];
            $payerEmailAddress = $transactionDetails['payer_email'];
            $payerID = $transactionDetails['payer_id'];
            $shippingRecipient = $transactionDetails['shipping_recipient'];
            $shippingLine1 = $transactionDetails['shipping_line1'];
            $shippingLine2 = $transactionDetails['shipping_line2'];
            $shippingCity = $transactionDetails['shipping_city'];
            $shippingState = $transactionDetails['shipping_state'];
            $shippingPostalCode = $transactionDetails['shipping_postal_code'];
            $shippingCountryCode = $transactionDetails['shipping_country_code'];
            $itemID = Yii::app()->session['itemID'];
            unset($_SESSION["results"]);
            if(Yii::app()->session['accountID']) {
                $accountID = Yii::app()->session['accountID'];
                $_receiptsForm = new ReceiptsForm();
                $receiptID = $_receiptsForm->saveReceipt($transactionID, $firstName, $lastName, $currency, $amount, $method, $dateTime, $state, $payerEmailAddress, $payerID, $shippingRecipient, $shippingLine1, $shippingLine2, $shippingCity, $shippingState, $shippingPostalCode, $shippingCountryCode, $itemID, $accountID);
                if ($receiptID != false) {
                    $receipt = $_receiptsForm->getReceiptByID($receiptID);
                    if (!empty($receipt)) {
                        unset(Yii::app()->session['itemID']);
                        $this->render('/item/receipt', array('receipt' => $receipt));
                    } else {
                        return $this->errorMessageHandler($errorCode, 'Receipt was generated but could not retrieve at the moment. Please go to your receipts folder to view it.');
                    }
                } else {
                    return $this->errorMessageHandler($errorCode, 'Thank you. We received your payment but the system failed to generate the receipt. Please contact our support team for assistance.');
                }
            } else {
                return $this->errorMessageHandler($errorCode, 'Your session has timed-out. We received your payment but the system failed to generate the receipt. Please contact our support team for assistance. Thank you');
            }
        } else {
            return $this->errorMessageHandler($errorCode, 'An unexpected error occured! Please try again.');
        }
    }

    public function errorMessageHandler($errorCode, $errorMessage) {
        $itemID = Yii::app()->session['itemID'];
        unset(Yii::app()->session['itemID']);
        $submitFormResult = array('errorCode' => $errorCode, 'errorMessage' => $errorMessage);
        echo CJSON::encode($submitFormResult);
        //return $submitFormResult;
        //$_itemsForm = new ItemsForm();
        //$items = $_itemsForm->getAllItems();
        //$this->render('buyerror', array('items' => $items, 'submitFormResult' => $submitFormResult));
    }

    public function actionSigninForm() {
        $_accountsForm = new AccountsForm();
        $errorCodeDefault = CommonForm::ERROR_CODE_DEFAULT;
        if (Yii::app()->request->isAjaxRequest) {
            $submitFormResult = array('errorCode' => $errorCodeDefault, 'errorMessage' => 'An unexpected error occured. Please try again!');
            $usernameOrEmailAddress = Yii::app()->request->getParam('usernameOrEmailAddress');
            $password = Yii::app()->request->getParam('password');
            if (strlen($usernameOrEmailAddress) > 0) {
                if (strlen($password) > 0) {
                    $encryptedPassword = CommonForm::encryptPassword($password);
                    $isEmailAddressValid = CommonForm::checkEmailAddress($usernameOrEmailAddress);
                    $_accountsForm = new AccountsForm();
                    if ($isEmailAddressValid == true) {
                        $emailAddress = $usernameOrEmailAddress;
                        $result = $_accountsForm->getAccountIDANDAccountTypeIDAndStatusByEmailAddressAndPassword($emailAddress, $encryptedPassword);
                        if (!empty($result)) {
                            if (isset($result['ID'])) {
                                if (isset($result['AccountTypeID'])) {
                                    if (isset($result['Status'])) {
                                        $accountID = $result['ID'];
                                        $accountTypeID = $result['AccountTypeID'];
                                        $status = $result['Status'];
                                        $_accountDetailsForm = new AccountDetailsForm();
                                        $firstName = $_accountDetailsForm->getFirstNameByAccountID($accountID);
                                        return $this->processSignin($accountID, (int)$accountTypeID, (int)$status, $firstName);
                                    } else {
                                        $submitFormResult = array('errorCode' => $errorCodeDefault, 'errorMessage' => 'Unexpected error occured while fetching account status.');
                                    }
                                } else {
                                    $submitFormResult = array('errorCode' => $errorCodeDefault, 'errorMessage' => 'Unexpected error occured while fetching account type.');
                                }
                            } else {
                                $submitFormResult = array('errorCode' => $errorCodeDefault, 'errorMessage' => 'Unexpected error occured while fetching account ID.');
                            }
                        } else {
                            $submitFormResult = array('errorCode' => $errorCodeDefault, 'errorMessage' => 'Account is invalid.');
                        }
                    } else {
                        $username = $usernameOrEmailAddress;
                        $result = $_accountsForm->getAccountIDAndAccountTypeIDAndStatusByUsernameAndPassword($username, $encryptedPassword);
                        if (!empty($result)) {
                            if (isset($result['ID'])) {
                                if (isset($result['AccountTypeID'])) {
                                    if (isset($result['Status'])) {
                                        $accountID = $result['ID'];
                                        $accountTypeID = $result['AccountTypeID'];
                                        $status = $result['Status'];
                                        $_accountDetailsForm = new AccountDetailsForm();
                                        $firstName = $_accountDetailsForm->getFirstNameByAccountID($accountID);
                                        return $this->processSignin($accountID, (int)$accountTypeID, (int)$status, $firstName);
                                    } else {
                                        $submitFormResult = array('errorCode' => $errorCodeDefault, 'errorMessage' => 'Unexpected error occured while fetching account status.');
                                    }
                                } else {
                                    $submitFormResult = array('errorCode' => $errorCodeDefault, 'errorMessage' => 'Unexpected error occured while fetching account type.');
                                }
                            } else {
                                $submitFormResult = array('errorCode' => $errorCodeDefault, 'errorMessage' => 'Unexpected error occured while fetching account ID.');
                            }
                        } else {
                            $submitFormResult = array('errorCode' => $errorCodeDefault, 'errorMessage' => 'Account is invalid.');
                        }
                    }
                } else {
                    $submitFormResult = array('errorCode' => $errorCodeDefault, 'errorMessage' => 'Please enter your password.');
                }
            } else {
                $submitFormResult = array('errorCode' => $errorCodeDefault, 'errorMessage' => 'Please enter you username or email address.');
            }
        } else {
            $submitFormResult = array('errorCode' => $errorCodeDefault, 'errorMessage' => 'Please fill in the required fields.');
        }
        echo CJSON::encode($submitFormResult);
    }

    private function processSignin($accountID, $accountTypeID, $status, $firstName) {
        Yii::app()->session['accountID'] = $accountID;
        $_accountsForm = new AccountsForm();
        $errorCodeDefault = CommonForm::ERROR_CODE_DEFAULT;
        if ($status == CommonForm::STATUS_INACTIVE) {
            $_administrationForm = new AdministrationForm();
            $accountActivationSwitch = $_administrationForm->getAccountActivationSwitch();
            if ($accountActivationSwitch == CommonForm::ACCOUNT_ACTIVATIONSWITCH_ON) {
                $this->redirect(Yii::app()->createUrl('/user/activateaccount'));
            } else {
                $activatedByID = $accountID;
                $activateAccount = $_accountsForm->activateAccount($accountID, $activatedByID);
                if ($activateAccount == CommonForm::ERROR_CODE_NO_ERROR) {
                    Yii::app()->session['accountTypeID'] = $accountTypeID;
                    Yii::app()->session['firstName'] = $firstName;
                    $this->redirect(Yii::app()->createUrl('/item/multiple'));
                } else {
                    unset(Yii::app()->session['accountID']);
                    $submitFormResult = array('errorCode' => CommonForm::ERROR_CODE_DEFAULT, 'errorMessage' => 'Unexpected error occured while attempting to activate your account. Please try again.');
                    echo CJSON::encode($submitFormResult);
                }
            }
        } else {
            if ($status == CommonForm::STATUS_ACTIVE) {
                if ($accountTypeID == CommonForm::ACCOUNT_TYPE_USER) {
                    $submitFormResult = array('errorCode' => CommonForm::ERROR_CODE_NO_ERROR, 'errorMessage' => 'User Success.');
                    Yii::app()->session['accountTypeID'] = $accountTypeID;
                    Yii::app()->session['firstName'] = $firstName;
                } else if ($accountTypeID == CommonForm::ACCOUNT_TYPE_CHARITY) {
                    $submitFormResult = array('errorCode' => CommonForm::ERROR_CODE_NO_ERROR, 'errorMessage' => 'Charity Success.');
                } else if ($accountTypeID == CommonForm::ACCOUNT_TYPE_ADMINISTRATOR) {
                    $submitFormResult = array('errorCode' => CommonForm::ERROR_CODE_NO_ERROR, 'errorMessage' => 'Account Type Success.');
                } else {
                    unset(Yii::app()->session['accountID']);
                    $submitFormResult = array('errorCode' => $errorCodeDefault, 'errorMessage' => 'Error');
                }
                echo CJSON::encode($submitFormResult);
            } else {
                if ($status == CommonForm::STATUS_DEACTIVATED) {
                    $submitFormResult = array('errorCode' => $errorCodeDefault, 'errorMessage' => 'Account has been deactivated. Please contact our support team for assistance.');
                } else if ($status == CommonForm::STATUS_LOCKED_BY_SELF) {
                    $submitFormResult = array('errorCode' => $errorCodeDefault, 'errorMessage' => 'Account has been locked. Click the "Problem Signing in" below or you may contact our support team for assistance.');
                } else if ($status == CommonForm::STATUS_LOCKED_BY_THE_ADMINISTRATOR) {
                    $submitFormResult = array('errorCode' => $errorCodeDefault, 'errorMessage' => 'Account has been locked by the administrator. Please contact our support team for assistance.');
                } else if ($status == CommonForm::STATUS_DELETED) {
                    $submitFormResult = array('errorCode' => $errorCodeDefault, 'errorMessage' => 'Account has been deleted.');
                } else {
                    $submitFormResult = array('errorCode' => $errorCodeDefault, 'errorMessage' => 'Please enter you username or email address.');
                }
                unset(Yii::app()->session['accountID']);
                echo CJSON::encode($submitFormResult);
            }
        }
    }
}