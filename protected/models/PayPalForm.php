<?php

use PayPal\Rest\ApiContext;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Api\Payment;
use PayPal\Api\Payer;
use PayPal\Api\Amount;
use PayPal\Api\Transaction;
use PayPal\Api\ItemList;
use PayPal\Api\RedirectUrls;
use PayPal\Api\PaymentExecution;
use PayPal\Api\CreditCard;
use PayPal\Api\CreditCardToken;
use PayPal\Api\FundingInstrument;

class PayPalForm {

//function gets access token from PayPal
    public function apiContext() {
        $apiContext = new ApiContext(new OAuthTokenCredential(CLIENT_ID, CLIENT_SECRET));
        return $apiContext;
    }

//create PayPal payment method
    public function create_paypal_payment($total, $currency, $desc, $my_items, $redirect_url, $cancel_url) {
        $redirectUrls = new RedirectUrls();
        $redirectUrls->setReturnUrl($redirect_url);
        $redirectUrls->setCancelUrl($cancel_url);

        $payer = new Payer();
        $payer->setPaymentMethod("paypal");

        $amount = new Amount();
        $amount->setCurrency($currency);
        $amount->setTotal($total);

        $items = new ItemList();
        $items->setItems($my_items);

        $transaction = new Transaction();
        $transaction->setAmount($amount);
        $transaction->setDescription($desc);
        $transaction->setItemList($items);

        $payment = new Payment();
        $payment->setRedirectUrls($redirectUrls);
        $payment->setIntent("sale");
        $payment->setPayer($payer);
        $payment->setTransactions(array($transaction));

        $payment->create($this->apiContext());

        return $payment;
    }

//executes PayPal payment
    public function execute_payment($payment_id, $payer_id) {
        $payment = Payment::get($payment_id, $this->apiContext());
        $payment_execution = new PaymentExecution();
        $payment_execution->setPayerId($payer_id);
        $payment = $payment->execute($payment_execution, $this->apiContext());
        return $payment;
    }

//pay with credit card
    public function pay_direct_with_credit_card($credit_card_params, $currency, $amount_total, $my_items, $payment_desc) {

        $card = new CreditCard();
        $card->setType($credit_card_params['type']);
        $card->setNumber($credit_card_params['number']);
        $card->setExpireMonth($credit_card_params['expire_month']);
        $card->setExpireYear($credit_card_params['expire_year']);
        $card->setCvv2($credit_card_params['cvv2']);
        $card->setFirstName($credit_card_params['first_name']);
        $card->setLastName($credit_card_params['last_name']);

        $funding_instrument = new FundingInstrument();
        $funding_instrument->setCreditCard($card);

        $payer = new Payer();
        $payer->setPayment_method("credit_card");
        $payer->setFundingInstruments(array($funding_instrument));

        $amount = new Amount();
        $amount->setCurrency($currency);
        $amount->setTotal($amount_total);

        $transaction = new Transaction();
        $transaction->setAmount($amount);
        $transaction->setDescription("creating a direct payment with credit card");

        $payment = new Payment();
        $payment->setIntent("sale");
        $payment->setPayer($payer);
        $payment->setTransactions(array($transaction));

        $payment->create(apiContext());

        return $payment;
    }

}
