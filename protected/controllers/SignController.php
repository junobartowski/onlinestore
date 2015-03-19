<?php

/*
 * Description: SignController Controller
 * @author: JunJun S. Hernandez <hernandezjunjun28@gmail.com>
 * DateCreated: 2014-12-10
 * Country: Philippines
 */

class SignController extends Controller {

    public function actionIndex() {
        $this->redirect(Yii::app()->createUrl('sign/signin'));
    }

    public function actionSignin() {
        $_accountsForm = new AccountsForm();
        if (isset(Yii::app()->session['accountID'])) {
            $accountID = Yii::app()->session['accountID'];
            if ($accountID != "" || $accountID != 0) {
                if (empty(Yii::app()->request->urlReferrer) || Yii::app()->request->urlReferrer == "") {
                    $this->redirect(Yii::app()->createUrl('/item/multiple'));
                } else {
                    $this->redirect(Yii::app()->request->urlReferrer);
                }
            } else {
                $this->render('signin', array('accountsForm' => $_accountsForm));
            }
        } else {
            $this->render('signin', array('accountsForm' => $_accountsForm));
        }
    }

    public function actionSignup() {
        $_accountsForm = new AccountsForm();
        $countries = CommonForm::getAllCountries();
        if (isset(Yii::app()->session['accountID'])) {
            $accountID = Yii::app()->session['accountID'];
            if ($accountID != "" || $accountID != 0) {
                if (empty(Yii::app()->request->urlReferrer) || Yii::app()->request->urlReferrer == "") {
                    $this->redirect(Yii::app()->createUrl('/item/multiple'));
                } else {
                    $this->redirect(Yii::app()->request->urlReferrer);
                }
            } else {
                $this->render('signup', array('accountsForm' => $_accountsForm, 'countries' => $countries));
            }
        } else {
            $this->render('signup', array('accountsForm' => $_accountsForm, 'countries' => $countries));
        }
    }

    public function actionSignout() {
        Yii::app()->session->clear();
        $this->redirect(Yii::app()->createUrl('/item/multiple'));
    }

    public function actionSignupForm() {
        $countries = CommonForm::getAllCountries();
        $_accountsForm = new AccountsForm();
        $errorCodeDefault = CommonForm::ERROR_CODE_DEFAULT;
        $submitFormResult = array('errorCode' => $errorCodeDefault, 'errorMessage' => 'An unexpected error occured. Please try again!');
        if (isset($_POST['firstName'])) {
            if (isset($_POST['lastName'])) {
                if (isset($_POST['sex'])) {
                    if (isset($_POST['emailAddress'])) {
                        if (isset($_POST['mobileNumber'])) {
                            if (isset($_POST['username'])) {
                                if (isset($_POST['password'])) {
                                    if (isset($_POST['confirmPassword'])) {
                                        if (isset($_POST['captcha'])) {
                                            $firstName = $_POST['firstName'];
                                            $lastName = $_POST['lastName'];
                                            $sex = $_POST['sex'];
                                            $countryCode = $_POST['country'];
                                            $postCode = $_POST['postCode'];
                                            $emailAddress = $_POST['emailAddress'];
                                            $mobileNumber = $_POST['mobileNumber'];
                                            $username = $_POST['username'];
                                            $password = $_POST['password'];
                                            $confirmPassword = $_POST['confirmPassword'];
                                            $captcha = $_POST['captcha'];
                                            $maximumFirstNameLength = Yii::app()->params['maximumFirstNameLength'];
                                            if (strlen($firstName) > 0 && strlen($firstName) <= $maximumFirstNameLength) {
                                                if (ctype_alpha(str_replace(array(' ', "'", '-'), '', $firstName))) {
                                                    $maximumLastNameLength = Yii::app()->params['maximumLastNameLength'];
                                                    if (strlen($lastName) > 0 && strlen($lastName) <= $maximumLastNameLength) {
                                                        if (ctype_alpha(str_replace(array(' ', "'", '.', '-'), '', $lastName))) {
                                                            $sex = (int) $sex;
                                                            if ($sex != 0) {
                                                                if ($countryCode != '') {
                                                                    $_ref_CountriesForm = new Ref_CountriesForm();
                                                                    $isCountryCodeExisting = $_ref_CountriesForm->isCountryCodeExisting($countryCode);
                                                                    if ($isCountryCodeExisting == true) {
                                                                        if ($postCode != '') {
                                                                            $_ref_PostCodesForm = new Ref_PostCodesForm();
                                                                            $isPostCodeExisting = $_ref_PostCodesForm->isPostCodeExisting($postCode);
                                                                            if ($isPostCodeExisting == true) {
                                                                                $isPostCodeMatchedWithCountryCode = $_ref_PostCodesForm->isPostCodeMatchedWithCountryCode($postCode, $countryCode);
                                                                                if ($isPostCodeMatchedWithCountryCode == true) {
                                                                                    if (strlen($emailAddress) > 0) {
                                                                                        $isEmailAddressValid = CommonForm::checkEmailAddress($emailAddress);
                                                                                        if ($isEmailAddressValid == true) {
                                                                                            $_accountDetailsForm = new AccountDetailsForm();
                                                                                            $isEmailAddressExisting = $_accountDetailsForm->isEmailAddressExisting($emailAddress);
                                                                                            if ($isEmailAddressExisting != true) {
                                                                                                if (strlen($mobileNumber) > 0) {
                                                                                                    $mobileNumber = $mobileNumber;
                                                                                                    $isMobileNumberAndCountryCodeMatched = $_accountDetailsForm->isMobileNumberAndCountryCodeMatched($mobileNumber, $countryCode);
                                                                                                    if ($isMobileNumberAndCountryCodeMatched == false) {
                                                                                                        if ((strlen($username) >= 8) && (strlen($username) <= 20)) {
                                                                                                            $isValidUsername = preg_match("/^[a-zA-Z0-9_\.]+$/", $username);
                                                                                                            if ($isValidUsername == true) {
                                                                                                                if ((strlen($password) >= 8) && (strlen($password) <= 20)) {
                                                                                                                    if ($username != $password) {
                                                                                                                        if ($password == $confirmPassword) {
                                                                                                                            $isUsernameExisting = $_accountsForm->isUsernameExisting($username);
                                                                                                                            if ($isUsernameExisting != true) {
                                                                                                                                if ($password == $confirmPassword) {
                                                                                                                                    if (strlen($captcha) >= 0) {
                                                                                                                                        $activationCode = CommonForm::generateActivationCode();
                                                                                                                                        if ($activationCode != '') {
                                                                                                                                            $encryptPassword = CommonForm::encryptPassword($password);
                                                                                                                                            $submitFormResult = $_accountsForm->signUp($firstName, $lastName, $sex, $countryCode, $postCode, $emailAddress, $mobileNumber, $username, $encryptPassword, $activationCode);
                                                                                                                                            $this->render('signin', array('accountsForm' => $_accountsForm, 'submitFormResult' => $submitFormResult));
                                                                                                                                        } else {
                                                                                                                                            $submitFormResult = array('errorCode' => $errorCodeDefault, 'Signing up failed. Unexpected error occured while generating your verification code. Please try again.');
                                                                                                                                            $this->render('signup', array('accountsForm' => $_accountsForm, 'countries' => $countries, 'submitFormResult' => $submitFormResult));
                                                                                                                                        }
                                                                                                                                    } else {
                                                                                                                                        $submitFormResult = array('errorCode' => $errorCodeDefault, 'Captcha is required.');
                                                                                                                                        $this->render('signup', array('accountsForm' => $_accountsForm, 'countries' => $countries, 'submitFormResult' => $submitFormResult));
                                                                                                                                    }
                                                                                                                                } else {
                                                                                                                                    $submitFormResult = array('errorCode' => $errorCodeDefault, 'errorMessage' => 'Passwords did not match.');
                                                                                                                                    $this->render('signup', array('accountsForm' => $_accountsForm, 'countries' => $countries, 'submitFormResult' => $submitFormResult));
                                                                                                                                }
                                                                                                                            } else {
                                                                                                                                $submitFormResult = array('errorCode' => $errorCodeDefault, 'errorMessage' => 'Username is already existing.');
                                                                                                                                $this->render('signup', array('accountsForm' => $_accountsForm, 'countries' => $countries, 'submitFormResult' => $submitFormResult));
                                                                                                                            }
                                                                                                                        } else {
                                                                                                                            $submitFormResult = array('errorCode' => $errorCodeDefault, 'errorMessage' => 'Passwords did not match.');
                                                                                                                            $this->render('signup', array('accountsForm' => $_accountsForm, 'countries' => $countries, 'submitFormResult' => $submitFormResult));
                                                                                                                        }
                                                                                                                    } else {
                                                                                                                        $submitFormResult = array('errorCode' => $errorCodeDefault, 'errorMessage' => 'Username and Password must not be the same.');
                                                                                                                        $this->render('signup', array('accountsForm' => $_accountsForm, 'countries' => $countries, 'submitFormResult' => $submitFormResult));
                                                                                                                    }
                                                                                                                } else {
                                                                                                                    $submitFormResult = array('errorCode' => $errorCodeDefault, 'errorMessage' => 'Password must be equal or more than ' . Yii::app()->params["minimumPasswordLength"] . ' and less than or equal to ' . Yii::app()->params["maximumPasswordLength"] . ' characters long.');
                                                                                                                    $this->render('signup', array('accountsForm' => $_accountsForm, 'countries' => $countries, 'submitFormResult' => $submitFormResult));
                                                                                                                }
                                                                                                            } else {
                                                                                                                $submitFormResult = array('errorCode' => $errorCodeDefault, 'errorMessage' => 'Username is invalid.');
                                                                                                                $this->render('signup', array('accountsForm' => $_accountsForm, 'countries' => $countries, 'submitFormResult' => $submitFormResult));
                                                                                                            }
                                                                                                        } else {
                                                                                                            $submitFormResult = array('errorCode' => $errorCodeDefault, 'errorMessage' => 'Username must be equal or more than ' . Yii::app()->params["minimumUsernameLength"] . ' and less than or equal to ' . Yii::app()->params["maximumUsernameLength"] . ' characters long.');
                                                                                                            $this->render('signup', array('accountsForm' => $_accountsForm, 'countries' => $countries, 'submitFormResult' => $submitFormResult));
                                                                                                        }
                                                                                                    } else {
                                                                                                        $submitFormResult = array('errorCode' => $errorCodeDefault, 'errorMessage' => 'Mobile Number is already existing.');
                                                                                                        $this->render('signup', array('accountsForm' => $_accountsForm, 'countries' => $countries, 'submitFormResult' => $submitFormResult));
                                                                                                    }
                                                                                                } else {
                                                                                                    $submitFormResult = array('errorCode' => $errorCodeDefault, 'errorMessage' => 'Mobile number is required.');
                                                                                                    $this->render('signup', array('accountsForm' => $_accountsForm, 'countries' => $countries, 'submitFormResult' => $submitFormResult));
                                                                                                }
                                                                                            } else {
                                                                                                $submitFormResult = array('errorCode' => $errorCodeDefault, 'errorMessage' => 'Email is already existing.');
                                                                                                $this->render('signup', array('accountsForm' => $_accountsForm, 'countries' => $countries, 'submitFormResult' => $submitFormResult));
                                                                                            }
                                                                                        } else {
                                                                                            $submitFormResult = array('errorCode' => $errorCodeDefault, 'errorMessage' => 'Email Address is invalid.');
                                                                                            $this->render('signup', array('accountsForm' => $_accountsForm, 'countries' => $countries, 'submitFormResult' => $submitFormResult));
                                                                                        }
                                                                                    } else {
                                                                                        $submitFormResult = array('errorCode' => $errorCodeDefault, 'errorMessage' => 'Email Address is required.');
                                                                                        $this->render('signup', array('accountsForm' => $_accountsForm, 'countries' => $countries, 'submitFormResult' => $submitFormResult));
                                                                                    }
                                                                                } else {
                                                                                    $submitFormResult = array('errorCode' => $errorCodeDefault, 'errorMessage' => 'Post Code does not match Country Code.');
                                                                                    $this->render('signup', array('accountsForm' => $_accountsForm, 'countries' => $countries, 'submitFormResult' => $submitFormResult));
                                                                                }
                                                                            } else {
                                                                                $submitFormResult = array('errorCode' => $errorCodeDefault, 'errorMessage' => 'Post Code is not supported.');
                                                                                $this->render('signup', array('accountsForm' => $_accountsForm, 'countries' => $countries, 'submitFormResult' => $submitFormResult));
                                                                            }
                                                                        } else {
                                                                            $submitFormResult = array('errorCode' => $errorCodeDefault, 'errorMessage' => 'Post Code is required.');
                                                                            $this->render('signup', array('accountsForm' => $_accountsForm, 'countries' => $countries, 'submitFormResult' => $submitFormResult));
                                                                        }
                                                                    } else {
                                                                        $submitFormResult = array('errorCode' => $errorCodeDefault, 'errorMessage' => 'Country is not supported.');
                                                                        $this->render('signup', array('accountsForm' => $_accountsForm, 'countries' => $countries, 'submitFormResult' => $submitFormResult));
                                                                    }
                                                                } else {
                                                                    $submitFormResult = array('errorCode' => $errorCodeDefault, 'errorMessage' => 'Country is required.');
                                                                    $this->render('signup', array('accountsForm' => $_accountsForm, 'countries' => $countries, 'submitFormResult' => $submitFormResult));
                                                                }
                                                            } else {
                                                                $submitFormResult = array('errorCode' => $errorCodeDefault, 'errorMessage' => 'Sex is required.');
                                                                $this->render('signup', array('accountsForm' => $_accountsForm, 'countries' => $countries, 'submitFormResult' => $submitFormResult));
                                                            }
                                                        } else {
                                                            $submitFormResult = array('errorCode' => $errorCodeDefault, 'errorMessage' => 'Last name is invalid.');
                                                            $this->render('signup', array('accountsForm' => $_accountsForm, 'countries' => $countries, 'submitFormResult' => $submitFormResult));
                                                        }
                                                    } else {
                                                        $submitFormResult = array('errorCode' => $errorCodeDefault, 'errorMessage' => 'Last name is required.');
                                                        $this->render('signup', array('accountsForm' => $_accountsForm, 'countries' => $countries, 'submitFormResult' => $submitFormResult));
                                                    }
                                                } else {
                                                    $submitFormResult = array('errorCode' => $errorCodeDefault, 'errorMessage' => 'First name is invalid.');
                                                    $this->render('signup', array('accountsForm' => $_accountsForm, 'countries' => $countries, 'submitFormResult' => $submitFormResult));
                                                }
                                            } else {
                                                $submitFormResult = array('errorCode' => $errorCodeDefault, 'errorMessage' => 'First name is required.');
                                                $this->render('signup', array('accountsForm' => $_accountsForm, 'countries' => $countries, 'submitFormResult' => $submitFormResult));
                                            }
                                        } else {
                                            $submitFormResult = array('errorCode' => $errorCodeDefault, 'errorMessage' => 'Captcha must have a correct answer.');
                                            $this->render('signup', array('accountsForm' => $_accountsForm, 'countries' => $countries, 'submitFormResult' => $submitFormResult));
                                        }
                                    } else {
                                        $submitFormResult = array('errorCode' => $errorCodeDefault, 'errorMessage' => 'Passwords did not match.');
                                        $this->render('signup', array('accountsForm' => $_accountsForm, 'countries' => $countries, 'submitFormResult' => $submitFormResult));
                                    }
                                } else {
                                    $submitFormResult = array('errorCode' => $errorCodeDefault, 'errorMessage' => 'Password is required.');
                                    $this->render('signup', array('accountsForm' => $_accountsForm, 'countries' => $countries, 'submitFormResult' => $submitFormResult));
                                }
                            } else {
                                $submitFormResult = array('errorCode' => $errorCodeDefault, 'errorMessage' => 'Username is required.');
                                $this->render('signup', array('accountsForm' => $_accountsForm, 'countries' => $countries, 'submitFormResult' => $submitFormResult));
                            }
                        } else {
                            $submitFormResult = array('errorCode' => $errorCodeDefault, 'errorMessage' => 'Mobile number is required.');
                            $this->render('signup', array('accountsForm' => $_accountsForm, 'countries' => $countries, 'submitFormResult' => $submitFormResult));
                        }
                    } else {
                        $submitFormResult = array('errorCode' => $errorCodeDefault, 'errorMessage' => 'Email address is required.');
                        $this->render('signup', array('accountsForm' => $_accountsForm, 'countries' => $countries, 'submitFormResult' => $submitFormResult));
                    }
                } else {
                    $submitFormResult = array('errorCode' => $errorCodeDefault, 'errorMessage' => 'Sex is required.');
                    $this->render('signup', array('accountsForm' => $_accountsForm, 'countries' => $countries, 'submitFormResult' => $submitFormResult));
                }
            } else {
                $submitFormResult = array('errorCode' => $errorCodeDefault, 'errorMessage' => 'Last name is required.');
                $this->render('signup', array('accountsForm' => $_accountsForm, 'countries' => $countries, 'submitFormResult' => $submitFormResult));
            }
        } else {
            $submitFormResult = array('errorCode' => $errorCodeDefault, 'errorMessage' => 'First name is required.');
            $this->render('signup', array('accountsForm' => $_accountsForm, 'countries' => $countries, 'submitFormResult' => $submitFormResult));
        }
    }

    public function actionSigninForm() {
        $_accountsForm = new AccountsForm();
        $errorCodeDefault = CommonForm::ERROR_CODE_DEFAULT;
        $submitFormResult = array('errorCode' => $errorCodeDefault, 'errorMessage' => 'An unexpected error occured. Please try again!');
        if (isset($_POST['usernameOrEmailAddress'])) {
            if (isset($_POST['password'])) {
                $usernameOrEmailAddress = $_POST['usernameOrEmailAddress'];
                $password = $_POST['password'];
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
                                            return $this->processSignin($accountID, $accountTypeID, $status, $firstName);
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
                $submitFormResult = array('errorCode' => $errorCodeDefault, 'errorMessage' => 'Please enter your password.');
            }
        } else {
            $submitFormResult = array('errorCode' => $errorCodeDefault, 'errorMessage' => 'Please enter you username or email address.');
        }
        $this->render('signin', array('accountsForm' => $_accountsForm, 'submitFormResult' => $submitFormResult));
    }

    public function actionGetPostCodeByCountryCode() {
        if (Yii::app()->request->isAjaxRequest) {
            $countryCode = trim(Yii::app()->request->getParam('countryCode'));
            $_Ref_PostCodesForm = new Ref_PostCodesForm();
            $postCodes = $_Ref_PostCodesForm->getPostCodeByCountryCode($countryCode);
            $optionPostCodes = '<option value="">Your Post Code</option>';

            foreach ($postCodes as $postCode) {
                $optionPostCodes .= CHtml::tag('option', array('value' => $postCode['ID']), $postCode['PostCode']);
            }
        } else {
            $postCodes = CHtml::tag('option', array('value' => '', ''));
            $optionPostCodes = CHtml::listData($postCodes);
        }
        echo $optionPostCodes;
    }

    public function actionGetPhonePrefixByCountryCode() {
        if (Yii::app()->request->isAjaxRequest) {
            $countryCode = trim(Yii::app()->request->getParam('countryCode'));
            $_Ref_PhonePrefixForm = new Ref_PhonePrefixForm();
            $phonePrefixes = $_Ref_PhonePrefixForm->getPhonePrefixByCountryCode($countryCode);
        } else {
            $phonePrefixes = '+';
        }
        echo $phonePrefixes;
    }

//Start of ajax live checking
    public function actionGetUsernameByUsername() {
        if (Yii::app()->request->isAjaxRequest) {
            $username = trim(Yii::app()->request->getParam('user'));
            $_AccountsForm = new AccountsForm();
            $user = $_AccountsForm->getUsernameByUsername($username);
        } else {
            $user = '';
        }
        echo $user;
    }

    public function actionGetEmailAddressByEmailAddress() {
        if (Yii::app()->request->isAjaxRequest) {
            $emailAddress = trim(Yii::app()->request->getParam('email'));
            $_AccountDetailsForm = new AccountDetailsForm();
            $email = $_AccountDetailsForm->getEmailAddressByEmailAddress($emailAddress);
        } else {
            $email = '';
        }
        echo $email;
    }

//End of ajax live checking
    private function processSignin($accountID, $accountTypeID, $status, $firstName) {
        Yii::app()->session['accountID'] = $accountID;
        $_accountsForm = new AccountsForm();
        $errorCodeDefault = CommonForm::ERROR_CODE_DEFAULT;
        if ($status == CommonForm::STATUS_INACTIVE) {
            $_administrationForm = new AdministrationForm();
            $accountActivationSwitch = $_administrationForm->getAccountActivationSwitch();
            if ($accountActivationSwitch == CommonForm::ACCOUNT_ACTIVATIONSWITCH_ON) {
                $this->render('activateaccount', array('accountsForm' => $_accountsForm));
            } else {
                $activatedByID = $accountID;
                $activateAccount = $_accountsForm->activateAccount($accountID, $activatedByID);
                if ($activateAccount == CommonForm::ERROR_CODE_NO_ERROR) {
                    Yii::app()->session['accountTypeID'] = $accountTypeID;
                    Yii::app()->session['firstName'] = $firstName;
                    $this->redirect(Yii::app()->createUrl('/item/multiple'));
                } else {
                    unset(Yii::app()->session['accountID']);
                    $submitFormResult = array('errorCode' => $errorCodeDefault, 'errorMessage' => 'Unexpected error occured while attempting to activate your account. Please try again.');
                    $this->render('signin', array('accountsForm' => $_accountsForm, 'submitFormResult' => $submitFormResult, 'accountID' => $accountID));
                }
            }
        } else {
            if ($status == CommonForm::STATUS_ACTIVE) {
                if ($accountTypeID == CommonForm::ACCOUNT_TYPE_USER) {
                    $submitFormResult = array('errorCode' => CommonForm::ERROR_CODE_NO_ERROR, 'errorMessage' => 'User Success.');
                    Yii::app()->session['accountTypeID'] = $accountTypeID;
                    Yii::app()->session['firstName'] = $firstName;
                    $this->redirect(Yii::app()->createUrl('/item/multiple'));
                } else if ($accountTypeID == CommonForm::ACCOUNT_TYPE_CHARITY) {
                    $submitFormResult = array('errorCode' => CommonForm::ERROR_CODE_NO_ERROR, 'errorMessage' => 'Charity Success.');
                } else if ($accountTypeID == CommonForm::ACCOUNT_TYPE_ADMINISTRATOR) {
                    $submitFormResult = array('errorCode' => CommonForm::ERROR_CODE_NO_ERROR, 'errorMessage' => 'Account Type Success.');
                } else {
                    unset(Yii::app()->session['accountID']);
                    $submitFormResult = array('errorCode' => $errorCodeDefault, 'errorMessage' => 'Error');
                }
                $this->render('signin', array('accountsForm' => $_accountsForm, 'submitFormResult' => $submitFormResult));
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
                $this->render('signin', array('accountsForm' => $_accountsForm, 'submitFormResult' => $submitFormResult));
            }
        }
    }

    public function actionActivateAccountPostForm() {
        $_accountsForm = new AccountsForm();
        $errorCodeDefault = CommonForm::ERROR_CODE_DEFAULT;
        if (isset(Yii::app()->session['accountID'])) {
            $accountID = Yii::app()->session['accountID'];
            if ($accountID != "") {
                if ($accountID != 0 || $accountID != "0") {
                    if (isset($_POST['activationCode'])) {
                        $activationCode = $_POST['activationCode'];
                        if ($activationCode != "") {
                            $activationAttempts = $_accountsForm->getActivationAttemptsByAccountID($accountID);
                            $maximumActivationAttempts = Yii::app()->params['maximumActivationAttempt'];
                            if ($maximumActivationAttempts > 0) {
                                if ($activationAttempts < Yii::app()->params['maximumActivationAttempt']) {
                                    $dataActivationCode = $_accountsForm->getActivationCodeByAccountID($accountID);
                                    if ($dataActivationCode == $activationCode) {
                                        $activatedByID = $accountID;
                                        $activateAccount = $_accountsForm->activateAccount($accountID, $activatedByID);
                                        if ($activateAccount == CommonForm::ERROR_CODE_NO_ERROR) {
                                            $submitFormResult = array('errorCode' => CommonForm::ERROR_CODE_NO_ERROR, 'errorMessage' => 'Account activation successful.');
                                            $this->render('signin', array('accountsForm' => $_accountsForm, 'submitFormResult' => $submitFormResult, 'accountID' => $accountID));
                                        } else {
                                            $submitFormResult = array('errorCode' => $errorCodeDefault, 'errorMessage' => 'Unexpected error occured while attempting to activate your account. Please try again.');
                                            $this->render('activateAccount', array('accountsForm' => $_accountsForm, 'submitFormResult' => $submitFormResult, 'accountID' => $accountID));
                                        }
                                    } else {
                                        $updateActivationAttempts = $_accountsForm->updateActivationAttempts($accountID);
                                        if ($updateActivationAttempts == CommonForm::ERROR_CODE_NO_ERROR) {
                                            $newActivationAttempts = $_accountsForm->getActivationAttemptsByAccountID($accountID);
                                            $remainingAttempts = Yii::app()->params['maximumActivationAttempt'] - $newActivationAttempts;
                                            if ($remainingAttempts == 0) {
                                                $accountTypeID = CommonForm::ACCOUNT_TYPE_USER;
                                                $lockAccount = $_accountsForm->lockAccountByAccount($accountID, $accountTypeID);
                                                if ($lockAccount == CommonForm::ERROR_CODE_NO_ERROR) {
                                                    unset(Yii::app()->session['accountID']);
                                                    $submitFormResult = array('errorCode' => $errorCodeDefault, 'errorMessage' => 'You have exceeded the maximum attempts. Activation code is invalid. Your account is locked. Please contact our support team for assistance.');
                                                    $this->render('signin', array('accountsForm' => $_accountsForm, 'submitFormResult' => $submitFormResult));
                                                } else {
                                                    $submitFormResult = array('errorCode' => $errorCodeDefault, 'errorMessage' => 'Account has almost been locked due to multiple activation attempts. Please contact our support team for assistance.');
                                                    $this->render('activateAccount', array('accountsForm' => $_accountsForm, 'submitFormResult' => $submitFormResult));
                                                }
                                            } else {
                                                $submitFormResult = array('errorCode' => $errorCodeDefault, 'errorMessage' => 'Activation code is invalid. Maximum attempt is ' . $maximumActivationAttempts . '. You only have ' . $remainingAttempts . ' remaining.');
                                                $this->render('activateAccount', array('accountsForm' => $_accountsForm, 'submitFormResult' => $submitFormResult));
                                            }
                                        } else {
                                            if ($remainingAttempts == 0) {
                                                $accountTypeID = CommonForm::ACCOUNT_TYPE_USER;
                                                $lockAccount = $_accountsForm->lockAccountByAccount($accountID, $accountTypeID);
                                                if ($lockAccount == CommonForm::ERROR_CODE_NO_ERROR) {
                                                    unset(Yii::app()->session['accountID']);
                                                    $submitFormResult = array('errorCode' => $errorCodeDefault, 'errorMessage' => 'You have exceeded the maximum attempts. Activation code is invalid. Your account is locked. Please contact our support team for assistance.');
                                                    $this->render('signin', array('accountsForm' => $_accountsForm, 'submitFormResult' => $submitFormResult));
                                                } else {
                                                    $submitFormResult = array('errorCode' => $errorCodeDefault, 'errorMessage' => 'Account has almost been locked due to multiple activation attempts. Please contact our support team for assistance.');
                                                    $this->render('signin', array('accountsForm' => $_accountsForm, 'submitFormResult' => $submitFormResult));
                                                }
                                            } else {
                                                $submitFormResult = array('errorCode' => $errorCodeDefault, 'errorMessage' => 'Activation code is invalid. Maximum attempt is ' . $maximumActivationAttempts . '. You only have ' . $remainingAttempts . ' remaining.');
                                                $this->render('activateAccount', array('accountsForm' => $_accountsForm, 'submitFormResult' => $submitFormResult));
                                            }
                                        }
                                    }
                                } else {
                                    $accountTypeID = CommonForm::ACCOUNT_TYPE_USER;
                                    $lockAccount = $_accountsForm->lockAccountByAccount($accountID, $accountTypeID);
                                    if ($lockAccount == CommonForm::ERROR_CODE_NO_ERROR) {
                                        unset(Yii::app()->session['accountID']);
                                        $submitFormResult = array('errorCode' => $errorCodeDefault, 'errorMessage' => 'Account has been locked due to multiple activation attempts. Please contact our support team for assistance.');
                                        $this->render('signin', array('accountsForm' => $_accountsForm, 'submitFormResult' => $submitFormResult));
                                    } else {
                                        $submitFormResult = array('errorCode' => $errorCodeDefault, 'errorMessage' => 'Account has almost been locked due to multiple activation attempts. Please contact our support team for assistance.');
                                        $this->render('signin', array('accountsForm' => $_accountsForm, 'submitFormResult' => $submitFormResult));
                                    }
                                }
                            } else {
                                $dataActivationCode = $_accountsForm->getActivationCodeByAccountID($accountID);
                                if ($dataActivationCode == $activationCode) {
                                    $activatedByID = $accountID;
                                    $activateAccount = $_accountsForm->activateAccount($accountID, $activatedByID);
                                    if ($activateAccount == CommonForm::ERROR_CODE_NO_ERROR) {
                                        $submitFormResult = array('errorCode' => CommonForm::ERROR_CODE_NO_ERROR, 'errorMessage' => 'Account activation successful.');
                                    } else {
                                        $submitFormResult = array('errorCode' => $errorCodeDefault, 'errorMessage' => 'Unexpected error occured while attempting to activate your account. Please try again.');
                                        $this->render('activateAccount', array('accountsForm' => $_accountsForm, 'submitFormResult' => $submitFormResult, 'accountID' => $accountID));
                                    }
                                } else {
                                    $submitFormResult = array('errorCode' => $errorCodeDefault, 'errorMessage' => 'Activation code is invalid. Please try again.');
                                    $this->render('activateAccount', array('accountsForm' => $_accountsForm, 'submitFormResult' => $submitFormResult, 'accountID' => $accountID));
                                }
                            }
                        } else {
                            $submitFormResult = array('errorCode' => $errorCodeDefault, 'errorMessage' => 'Please enter your activation code.');
                            $this->render('activateAccount', array('accountsForm' => $_accountsForm, 'submitFormResult' => $submitFormResult, 'accountID' => $accountID));
                        }
                    } else {
                        $submitFormResult = array('errorCode' => $errorCodeDefault, 'errorMessage' => 'Please enter your activation code.');
                        $this->render('activateAccount', array('accountsForm' => $_accountsForm, 'submitFormResult' => $submitFormResult, 'accountID' => $accountID));
                    }
                } else {
                    $submitFormResult = array('errorCode' => $errorCodeDefault, 'errorMessage' => 'Unexpected error occured while trying to fetch your account ID. Please try again.');
                    $this->render('signin', array('accountsForm' => $_accountsForm, 'submitFormResult' => $submitFormResult));
                }
            } else {
                $submitFormResult = array('errorCode' => $errorCodeDefault, 'errorMessage' => 'Unexpected error occured while trying to fetch your account ID. Please try again.');
                $this->render('signin', array('accountsForm' => $_accountsForm, 'submitFormResult' => $submitFormResult));
            }
        } else {
            $submitFormResult = array('errorCode' => $errorCodeDefault, 'errorMessage' => 'Unexpected error occured while trying to fetch your account ID. Please try again.');
            $this->render('signin', array('accountsForm' => $_accountsForm, 'submitFormResult' => $submitFormResult));
        }
    }

    public function actionActivateAccountGetForm() {
        $_accountsForm = new AccountsForm();
        $errorCodeDefault = CommonForm::ERROR_CODE_DEFAULT;
        if (isset($_GET['accountID'])) {
            $accountID = $_GET['accountID'];
            if ($accountID != "") {
                if ($accountID != 0 || $accountID != "0") {
                    if (isset($_GET['activationCode'])) {
                        $activationCode = $_GET['activationCode'];
                        if ($activationCode != "") {
                            $activationAttempt = $_accountsForm->getActivationAttemptsByAccountID($accountID);
                            $maximumActivationAttempts = Yii::app()->params['maximumActivationAttempt'];
                            if ($maximumActivationAttempts > 0) {
                                if ($activationAttempt < Yii::app()->params['maximumActivationAttempt']) {
                                    $dataActivationCode = $_accountsForm->getActivationCodeByAccountID($accountID);
                                    if ($dataActivationCode == $activationCode) {
                                        $activatedByID = $accountID;
                                        $activateAccount = $_accountsForm->activateAccount($accountID, $activatedByID);
                                        if ($activateAccount == CommonForm::ERROR_CODE_NO_ERROR) {
                                            $submitFormResult = array('errorCode' => CommonForm::ERROR_CODE_NO_ERROR, 'errorMessage' => 'Congratulations! Account activation successful. Please sign in to continue.');
                                            $this->render('signin', array('accountsForm' => $_accountsForm, 'submitFormResult' => $submitFormResult));
                                        } else {
                                            $submitFormResult = array('errorCode' => $errorCodeDefault, 'errorMessage' => 'Unexpected error occured while attempting to activate your account. Please try again.');
                                        }
                                    } else {
                                        $updateActivationAttempts = $_accountsForm->updateActivationAttempts($accountID);
                                        if ($updateActivationAttempts == CommonForm::ERROR_CODE_NO_ERROR) {
                                            $newActivationAttempt = $_accountsForm->getActivationAttemptsByAccountID($accountID);
                                            $remainingAttempts = Yii::app()->params['maximumActivationAttempt'] - $newActivationAttempt;
                                            $submitFormResult = array('errorCode' => $errorCodeDefault, 'errorMessage' => 'Activation code is invalid. Maximum attempt is ' . $maximumActivationAttempts . '. You only have ' . $remainingAttempts . ' remaining.');
                                        } else {
                                            $newActivationAttempt = $_accountsForm->getActivationAttemptsByAccountID($accountID);
                                            $remainingAttempts = Yii::app()->params['maximumActivationAttempt'] - $newActivationAttempt;
                                            $submitFormResult = array('errorCode' => $errorCodeDefault, 'errorMessage' => 'Activation code is invalid. Maximum attempt is ' . $maximumActivationAttempts . '. You only have ' . $remainingAttempts . ' remaining.');
                                        }
                                    }
                                } else {
                                    $accountTypeID = CommonForm::ACCOUNT_TYPE_USER;
                                    $lockAccount = $_accountsForm->lockAccountByAccount($accountID, $accountTypeID);
                                    if ($lockAccount == CommonForm::ERROR_CODE_NO_ERROR) {
                                        $submitFormResult = array('errorCode' => $errorCodeDefault, 'errorMessage' => 'Account has been locked due to multiple activation attempts. Please contact our support team for assistance.');
                                    } else {
                                        $submitFormResult = array('errorCode' => $errorCodeDefault, 'errorMessage' => 'Account has almost been locked due to multiple activation attempts. Please contact our support team for assistance.');
                                    }
                                }
                            } else {
                                $dataActivationCode = $_accountsForm->getActivationCodeByAccountID($accountID);
                                if ($dataActivationCode == $activationCode) {
                                    $activatedByID = $accountID;
                                    $activateAccount = $_accountsForm->activateAccount($accountID, $activatedByID);
                                    if ($activateAccount == CommonForm::ERROR_CODE_NO_ERROR) {
                                        $submitFormResult = array('errorCode' => CommonForm::ERROR_CODE_NO_ERROR, 'errorMessage' => 'Congratulations! Account activation successful. Please sign in to continue.');
                                    } else {
                                        $submitFormResult = array('errorCode' => $errorCodeDefault, 'errorMessage' => 'Unexpected error occured while attempting to activate your account. Please try again.');
                                    }
                                } else {
                                    $submitFormResult = array('errorCode' => $errorCodeDefault, 'errorMessage' => 'Activation code is invalid. Please try again.');
                                }
                            }
                        } else {
                            $submitFormResult = array('errorCode' => $errorCodeDefault, 'errorMessage' => 'Please enter your activation code.');
                        }
                    } else {
                        $submitFormResult = array('errorCode' => $errorCodeDefault, 'errorMessage' => 'Please enter your activation code.');
                    }
                } else {
                    $submitFormResult = array('errorCode' => $errorCodeDefault, 'errorMessage' => 'Unexpected error occured while fetching your account ID. Please try again.');
                }
            } else {
                $submitFormResult = array('errorCode' => $errorCodeDefault, 'errorMessage' => 'Unexpected error occured while fetching your account ID. Please try again.');
            }
        } else {
            $submitFormResult = array('errorCode' => $errorCodeDefault, 'errorMessage' => 'Unexpected error occured while fetching your account ID. Please try again.');
        }
        $this->render('signin', array('accountsForm' => $_accountsForm, 'submitFormResult' => $submitFormResult));
    }
    
    public function actionAuthenticateFacebook() {
        $this->redirect('http://localhost/Facebook');
    }

}