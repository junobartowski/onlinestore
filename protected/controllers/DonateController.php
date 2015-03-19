<?php

/*
 * Description: DonateController Controller
 * @author: JunJun S. Hernandez <hernandezjunjun28@gmail.com>
 * DateCreated: 2014-12-10
 * Country: Philippines
 */

class DonateController extends Controller {

    public function actionIndex() {
        $_CharitiesForm = new CharitiesForm();
        $charities = $_CharitiesForm->getAllCharities();
        $this->render('directdonation', array('charities' => $charities));
    }

    public function actionDonate() {
        $_CharitiesForm = new CharitiesForm();
        $charities = $_CharitiesForm->getAllCharities();
        $this->render('directdonation', array('charities' => $charities));
    }
    
    public function actionDirectdonation() {
        $_CharitiesForm = new CharitiesForm();
        $charities = $_CharitiesForm->getAllCharities();
        $this->render('directdonation', array('charities' => $charities));
    }
    
    public function actionDirectDonationForm() {
        if (isset($_POST['donationAmount'])) {
            $amount = $_POST['donationAmount'];
            if (is_numeric($amount)) {
                if ($amount > 0) {
                    $maximumDonationAmount = Yii::app()->params['maximumDonationAmount'];
                    if ($amount <= $maximumDonationAmount) {
                        if(isset($_POST['charity'])) {
                            $charity = $_POST['charity'];
                            $charityID = $charity;
                            $_charitiesForm = new CharitiesForm();
                            $isCharityExisting = $_charitiesForm->isCharityExisting($charityID);
                            if ($isCharityExisting == true) {
                                $_charitiesForm = new CharitiesForm();
                                $charityName = $_charitiesForm->getCharityNameByID($charityID);
                                $itemID = $charityID;
                                $itemName = CommonForm::DIRECT_DONATION_ITEM_NAME . ' to ' . $charityName;
                                $itemQuantity = 1;
                                return $this->payPalRequest($itemID, $itemName, $itemQuantity, $amount);
                            } else {
                                $errorCode = CommonForm::ERROR_CODE_DIRECT_DONATION_NO_DETAILS;
                                $errorMessage = "Charity is invalid.";
                            }
                        } else {
                            $errorCode = CommonForm::ERROR_CODE_DIRECT_DONATION_NO_DETAILS;
                            $errorMessage = "Amount is invalid.";
                        }
                    } else {
                        $errorCode = CommonForm::ERROR_CODE_DEFAULT;
                        $errorMessage = "Amount is invalid.";
                    }
                } else {
                    $errorCode = CommonForm::ERROR_CODE_DEFAULT;
                    $errorMessage = "Amount is invalid.";
                }
            } else {
                $errorCode = CommonForm::ERROR_CODE_DEFAULT;
                $errorMessage = "Amount is invalid.";
            }
        } else {
            $errorCode = CommonForm::ERROR_CODE_DEFAULT;
            $errorMessage = "Amount is required.";
        }
        return $this->errorMessageHandler($errorCode, $errorMessage);
    }
    
    private function payPalCredentials() {
        session_start();
        define('CLIENT_ID', Yii::app()->params['payPalAppClientID']); //your PayPal client ID
        define('CLIENT_SECRET', Yii::app()->params['payPalAppSecret']); //PayPal Secret
        define('RETURN_URL', Yii::app()->params['defaultURLPrefix'] . Yii::app()->params['payPalDonationReturnURL']); //return URL where PayPal redirects user
        define('CANCEL_URL', Yii::app()->params['defaultURLPrefix'] . Yii::app()->params['payPalDonationCancelURL']); //cancel URL
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
                header("location: " . $result->links[1]->href); //after success redirect user to approval URL
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
            } else {
                $accountID = 0;
            }
            $_directDonationForm = new DirectDonationForm();
            $receiptID = $_directDonationForm->saveReceipt($transactionID, $firstName, $lastName, $currency, $amount, $method, $dateTime, $state, $payerEmailAddress, $payerID, $shippingRecipient, $shippingLine1, $shippingLine2, $shippingCity, $shippingState, $shippingPostalCode, $shippingCountryCode, $itemID, $accountID);
            if ($receiptID != false) {
                $receipt = $_directDonationForm->getReceiptByID($receiptID);
                if (!empty($receipt)) {
                    unset(Yii::app()->session['itemID']);
                    $this->render('/donate/receipt', array('receipt' => $receipt));
                } else {
                    return $this->errorMessageHandler($errorCode, 'Receipt was generated but could not retrieve at the moment. Please go to your receipts folder to view it.');
                }
            } else {
                return $this->errorMessageHandler($errorCode, 'Thank you. We received your donation but the system could not generate the receipt. Please contact our support team for assistance.');
            }
        } else {
            return $this->errorMessageHandler($errorCode, 'An unexpected error occured! Please try again.');
        }
    }

    public function errorMessageHandler($errorCode, $errorMessage) {
        if(isset(Yii::app()->session['itemID'])) {
            unset(Yii::app()->session['itemID']);
        }
        $_CharitiesForm = new CharitiesForm();
        $charities = $_CharitiesForm->getAllCharities();
        $submitFormResult = array('errorCode' => $errorCode, 'errorMessage' => $errorMessage);
        $this->render('directdonation', array('charities' => $charities, 'submitFormResult' => $submitFormResult));
    }

}