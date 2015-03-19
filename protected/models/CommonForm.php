<?php

/*
 * Description: CommonForm Model
 * @author: JunJun S. Hernandez <hernandezjunjun28@gmail.com>
 * DateCreated: 2014-12-10
 * Country: Philippines
 */

class CommonForm {

    CONST STATUS_INACTIVE = 0;
    CONST STATUS_ACTIVE = 1;
    CONST STATUS_DEACTIVATED = 2;
    CONST STATUS_LOCKED_BY_SELF = 3;
    CONST STATUS_LOCKED_BY_THE_ADMINISTRATOR = 4;
    CONST STATUS_DELETED = 5;
    CONST CHARITY_STATUS_INACTIVE = 0;
    CONST CHARITY_STATUS_ACTIVE = 1;
    CONST CHARITY_STATUS_DEACTIVATED = 2;
    CONST CHARITY_STATUS_DELETED = 3;
    CONST ITEM_STATUS_INACTIVE = 0;
    CONST ITEM_STATUS_ACTIVE = 1;
    CONST ITEM_STATUS_SOLD = 2;
    CONST ITEM_STATUS_REJECTED_BY_CHARITY = 3;
    CONST ITEM_STATUS_APPROVED_BY_CHARITY = 4;
    CONST ITEM_STATUS_REPORTED_BY_ANOTHER_USER = 5;
    CONST ITEM_STATUS_REPORTED_BY_CHARITY = 6;
    CONST ITEM_STATUS_DELETED = 7;
    CONST DIRECT_DONATION_ITEM_ID = 0;
    CONST DIRECT_DONATION_ITEM_NAME = 'Direct Donation';
    CONST ACCOUNT_TYPE_SUPERUSER = 1;
    CONST ACCOUNT_TYPE_ADMINISTRATOR = 2;
    CONST ACCOUNT_TYPE_USER = 3;
    CONST ACCOUNT_TYPE_CHARITY = 4;
    CONST ACCOUNT_ACTIVATIONSWITCH_OFF = 0;
    CONST ACCOUNT_ACTIVATIONSWITCH_ON = 1;
    CONST ADMINISTRATION_ACCOUNT_ACTIVATION_SWITCH_ID = 2;
    CONST SIGNUP_SELF_CREATED = 0;
    CONST FUNCTION_EMAIL_SIGNUP = 1;
    CONST SIGNUP_EMAIL_SUBJECT = 'Activation Code - Sell Buy Donate Account Registration';
    CONST SIGNUP_EMAIL_SUBJECT_WITH_ACTIVATION = 'Activation Code - Sell Buy Donate Account Registration';
    CONST SIGNUP_EMAIL_SUBJECT_NO_ACTIVATION = 'Sell Buy Donate Account Registration';
    CONST ERROR_MESSAGE_DEFAULT = 'An unexpected error occured. Please try again!';
    CONST ERROR_CODE_DEFAULT = 1;
    CONST ERROR_CODE_NO_ERROR = 0;
    CONST ERROR_CODE_SAVING_ACCOUNT = 1;
    CONST ERROR_CODE_SAVING_ACCOUNT_DETAILS = 2;
    CONST ERROR_CODE_FUNCTION_EMAIL_SIGNUP = 3;
    CONST ERROR_CODE_FUNCTION_EMAIL_SIGNUP_WITH_ACTIVATION = 4;
    CONST ERROR_CODE_FUNCTION_EMAIL_SIGNUP_NO_ACTIVATION = 5;
    CONST ERROR_CODE_SIGNUP_SEND_EMAIL = 6;
    CONST ERROR_CODE_SIGNUP_SEND_EMAIL_WITH_ACTIVATION = 7;
    CONST ERROR_CODE_SIGNUP_SEND_EMAIL_NO_ACTIVATION = 8;
    CONST ERROR_CODE_MAILER_EXCEPTION = 9;
    CONST ERROR_CODE_EXCEPTION = 10;
    CONST ERROR_CODE_GET_ADMIN_CREDENTIALS = 11;
    CONST ERROR_CODE_ACTIVATE_ACCOUNT = 12;
    CONST ERROR_CODE_UPDATE_ACTIVATION = 13;
    CONST ERROR_CODE_LOCK_ACCOUNT = 14;
    CONST ERROR_CODE_UPDATE_ITEM_STATUS = 15;
    CONST ERROR_CODE_SELL_ITEM_FINISH = 16;
    CONST ERROR_CODE_BUY_ITEM_NOW = 17;
    CONST ERROR_CODE_BUY_ITEM_NO_DETAILS = 18;
    CONST ERROR_CODE_UPDATE_BUY_TOKEN = 19;
    CONST ERROR_CODE_DIRECT_DONATION_NO_DETAILS = 20;
    CONST ERROR_CODE_SAVING_CHARITY = 21;

    public static function checkEmailAddress($emailAddress) {
        // First, we check that there's one @ symbol, and that the lengths are right
        if (!preg_match("/^[^@]{1,64}@[^@]{1,255}$/", $emailAddress)) {
            // Email invalid because wrong number of characters in one section, or wrong number of @ symbols.
            return false;
        }
        // Split it into sections to make life easier
        $emailAddress_array = explode("@", $emailAddress);
        $local_array = explode(".", $emailAddress_array[0]);
        for ($i = 0; $i < sizeof($local_array); $i++) {
            if (!preg_match("/^(([A-Za-z0-9_-][A-Za-z0-9_.-]{0,63})|(\"[^(\\|\")]{0,62}\"))$/", $local_array[$i])) {
                return false;
            }
        }
        if (!preg_match("/^\[?[0-9\.]+\]?$/", $emailAddress_array[1])) { // Check if domain is IP. If not, it should be valid domain name
            $domain_array = explode(".", $emailAddress_array[1]);
            if (sizeof($domain_array) < 2) {
                return false; // Not enough parts to domain
            }
            for ($i = 0; $i < sizeof($domain_array); $i++) {
                if (!preg_match("/^(([A-Za-z0-9][A-Za-z0-9-]{0,61}[A-Za-z0-9])|([A-Za-z0-9]+))$/", $domain_array[$i])) {
                    return false;
                }
            }
        }
        return true;
    }

    public static function sendMail($emailAddress, $subject, $emailMessageContentCode, $firstName = "", $middleName = "", $lastName = "", $activationCode = "") {
        $_AccountsForm = new AccountsForm();
        $administratorName = Yii::app()->params['administratorName'];
        $_accountDetailsForm = new AccountDetailsForm();
        $administratorEmailAddress = $_accountDetailsForm->getAdministratorEmailAddress();
        $_accountsForm = new AccountsForm();
        $administratorPassword = $_accountsForm->getAdministratorPasswordByEmailAddress($administratorEmailAddress);
        if ($administratorEmailAddress != "" && $administratorPassword != "") {
            Yii::import('application.extensions.phpmailer.JPhpMailer');
            $mail = new JPhpMailer;
            try {
                $mail->IsSMTP();
                $mail->Host = 'smtp.googlemail.com:465';
                $mail->SMTPSecure = "ssl";
                $mail->SMTPAuth = true;
                $mail->Username = $administratorEmailAddress;
                $mail->Password = $administratorPassword;
                $mail->SetFrom($administratorEmailAddress, $administratorName);
                $mail->Subject = $subject;
                $mail->AltBody = SELF::EMAIL_MESSAGE_CONTENT($emailMessageContentCode, $firstName, $lastName, $activationCode);
                $mail->MsgHTML(SELF::EMAIL_MESSAGE_CONTENT($emailMessageContentCode, $firstName, $lastName, $activationCode));
                $mail->AddAddress($emailAddress, $firstName . ' ' . $middleName . ' ' . $lastName);
                if ($mail->Send()) {
                    return SELF::ERROR_CODE_NO_ERROR;
                } else {
                    return SELF::ERROR_CODE_SIGNUP_SEND_EMAIL;
                }
            } catch (phpmailerException $e) {
                return SELF::FAILED_MAILER_EXCEPTION($e);
            } catch (Exception $e) {
                return SELF::FAILED_EXCEPTION($e);
            }
        } else {
            return SELF::ERROR_CODE_GET_ADMIN_CREDENTIALS;
        }
    }

    public static function generateActivationCode() {
        $key = '';
        $activationCodeLength = Yii::app()->params['activationCodeLength'];
        $keys = array_merge(range(0, 9), range('a', 'z'));
        for ($i = 0; $i < $activationCodeLength; $i++) {
            $key .= $keys[array_rand($keys)];
        }
        return $key;
        //return random_string(50);
    }
    
    public static function generateBuyToken() {
        $key = '';
        $buyTokenLength = Yii::app()->params['buyTokenLength'];
        $keys = array_merge(range(0, 9), range('a', 'z'));
        for ($i = 0; $i < $buyTokenLength; $i++) {
            $key .= $keys[array_rand($keys)];
        }
        $dateNow = date("Y-m-d hh:mm:ss");
        return $key . "+" . $dateNow;
        //return random_string(50);
    }

    public static function SUCCESS_SAVING_ACCOUNT() {
        return array('errorCode' => SELF::ERROR_CODE_NO_ERROR, 'errorMessage' => 'Signing up successful! Please check your email for the activation code.');
    }

    public static function FAILED_SAVING_ACCOUNT() {
        return array('errorCode' => SELF::ERROR_CODE_SAVING_ACCOUNT, 'errorMessage' => 'Unexpected error occured while saving account! Please try signing up again.');
    }

    public static function SUCCESS_SAVING_ACCOUNT_WITH_ACTIVATION() {
        return array('errorCode' => SELF::ERROR_CODE_NO_ERROR_SIGN_UP_WITH_ACTIVATION, 'errorMessage' => 'Signing up successful! Please check your email for the activation code.');
    }

    public static function FAILED_SAVING_ACCOUNT_WITH_ACTIVATION() {
        return array('errorCode' => SELF::ERROR_CODE_NO_ERROR_SIGN_UP_WITH_ACTIVATION, 'errorMessage' => 'Unexpected error occured while saving account! Please try signing up again.');
    }

    public static function SUCCESS_SAVING_ACCOUNT_NO_ACTIVATION() {
        return array('errorCode' => SELF::ERROR_CODE_NO_ERROR, 'errorMessage' => 'Signing up successful! Please login to continue.');
    }

    public static function FAILED_SAVING_ACCOUNT_NO_ACTIVATION() {
        return array('errorCode' => SELF::ERROR_CODE_NO_ERROR, 'errorMessage' => 'Unexpected error occured while saving account! Please try signing up again.');
    }

    public static function FAILED_SAVING_ACCOUNT_DETAILS() {
        return array('errorCode' => SELF::ERROR_CODE_SAVING_ACCOUNT_DETAILS, 'errorMessage' => 'Unexpected error occured while saving account details! Please try signing up again.');
    }

    public static function SUCCESS_SAVING_CHARITY() {
        return array('errorCode' => SELF::ERROR_CODE_NO_ERROR, 'errorMessage' => 'Saving Charity successful! Please add Charity Logo.');
    }

    public static function FAILED_SAVING_CHARITY() {
        return array('errorCode' => SELF::ERROR_CODE_SAVING_CHARITY, 'errorMessage' => 'Unexpected error occured while saving charity! Please try again.');
    }
    
    public static function FAILED_SIGNUP_SEND_EMAIL_WITH_ACTIVATION($errorMessage) {
        return array('errorCode' => SELF::ERROR_CODE_SIGNUP_SEND_EMAIL_WITH_ACTIVATION, 'errorMessage' => $errorMessage);
    }
    
    public static function FAILED_SIGNUP_SEND_EMAIL_NO_ACTIVATION($errorMessage) {
        return array('errorCode' => SELF::ERROR_CODE_SIGNUP_SEND_EMAIL_NO_ACTIVATION, 'errorMessage' => $errorMessage);
    }

    public static function FAILED_MAILER_EXCEPTION($e) {
        return array('errorCode' => SELF::ERROR_CODE_MAILER_EXCEPTION, 'errorMessage' => $e);
    }

    public static function FAILED_EXCEPTION($e) {
        return array('errorCode' => SELF::ERROR_CODE_EXCEPTION, 'errorMessage' => $e);
    }

    public static function FAILED_GET_ADMIN_CREDENTIALS() {
        return array('errorCode' => SELF::ERROR_CODE_GET_ADMIN_CREDENTIALS, 'errorMessage' => 'Unexpected error occured while fetching email credentials.');
    }

    public static function SUCCESS_ACTIVATE_ACCOUNT() {
        return array('errorCode' => SELF::ERROR_CODE_NO_ERROR, 'errorMessage' => 'Account activation successful.');
    }

    public static function FAILED_ACTIVATE_ACCOUNT($activationAttempt) {
        if (Yii::app()->params['maximumActivationAttempt'] > 0) {
            if ($activationAttempt < Yii::app()->params['maximumActivationAttempt']) {
                return array('errorCode' => SELF::ERROR_CODE_ACTIVATE_ACCOUNT, 'errorMessage' => 'Account has been locked due to multiple activation attempts. Please contact our support team for assistance.');
            } else {
                return array('errorCode' => SELF::ERROR_CODE_ACTIVATE_ACCOUNT, 'errorMessage' => 'Unexpected error occured while activating your account. Please try again.');
            }
        } else {
            return array('errorCode' => SELF::ERROR_CODE_ACTIVATE_ACCOUNT, 'errorMessage' => 'Unexpected error occured while activating your account. Please try again.');
        }
    }

    public static function EMAIL_MESSAGE_HEADER($firstName = "", $lastName = "") {
        $messageHeader = "Hi " . $firstName . " " . $lastName . ",<br>";
        return $messageHeader;
    }

    public static function EMAIL_MESSAGE_FOOTER() {
        $messageFooter = "<br><br>Regards,<br>The " . Yii::app()->params["projectName"] . " Team";
        return $messageFooter;
    }

    public static function EMAIL_MESSAGE_CONTENT($emailMessageContentCode, $firstName = "", $lastName = "", $activationCode = "") {
        $messageContent = SELF::EMAIL_MESSAGE_HEADER($firstName, $lastName);
        if ($emailMessageContentCode == SELF::ERROR_CODE_FUNCTION_EMAIL_SIGNUP_WITH_ACTIVATION) {
            $messageContent .= "Thank you for signing up with " . Yii::app()->params["projectName"] . ".<br>Your activation code is: <b>" . $activationCode . "</b>";
        } else if ($emailMessageContentCode == SELF::ERROR_CODE_FUNCTION_EMAIL_SIGNUP_NO_ACTIVATION) {
            $messageContent .= "Thank you for signing up with " . Yii::app()->params["projectName"] . ".<br>Kindly click this link to login: <b>Link</b>";
        } else {
            $messageContent .= "";
        }
        $messageContent .= SELF::EMAIL_MESSAGE_FOOTER();
        return $messageContent;
    }

    public static function EMAIL_MESSAGE_COMPLETE($emailMessageContentCode, $firstName, $lastName, $activationCode = '') {
        $messageHTML .="Hi " . $firstName . " " . $lastName . ",<br>";
        if ($emailMessageContentCode == SELF::EMAIL_MESSAGE_CONTENT_CODE_SIGNUP) {
            $messageHTML .= "Thank you for signing up with " . Yii::app()->params["projectName"] . ".<br>Your activation code is: <b>" . $activationCode . "</b>";
        } else {
            $messageHTML .= "";
        }
        $messageHTML .= "<br><br>Regards,<br>The " . Yii::app()->params["projectName"] . " Team";
        return $messageHTML;
    }

    public static function ACCOUNT_ACTIVATION_COMPLETE($emailMessageContentCode, $firstName, $lastName, $activationCode = '') {
        $messageHTML .="Hi " . $firstName . " " . $lastName . ",<br>";
        if ($emailMessageContentCode == SELF::EMAIL_MESSAGE_CONTENT_CODE_SIGNUP) {
            $messageHTML .= "Thank you for signing up with " . Yii::app()->params["projectName"] . ".<br>Your activation code is: <b>" . $activationCode . "</b>";
        } else {
            $messageHTML .= "";
        }
        $messageHTML .= "<br><br>Regards,<br>The " . Yii::app()->params["projectName"] . " Team";
        return $messageHTML;
    }

    public static function getAllCountries() {
        $_Ref_CountriesForm = new Ref_CountriesForm();
        $countries = $_Ref_CountriesForm->getCountries();

        return $countries;
    }

    public static function encryptPassword($password) {
        $encryptedPassword = md5($password);

        return $encryptedPassword;
    }

    public static function makeDirectoryForItems($directory, $groupMode, $superUserMode) {
        if (is_dir($directory)) {
            chmod($directory, $groupMode);
            return true;
        } else {
            if (mkdir($directory, $groupMode, true)) {
                chmod($directory, $groupMode);
                return true;
            } else {
                return false;
            }
        }
    }

}