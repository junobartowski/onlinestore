<?php

/*
 * Description: AdministrationController Controller
 * @author: JunJun S. Hernandez <hernandezjunjun28@gmail.com>
 * DateCreated: 2014-12-10
 * Country: Philippines
 */

class AdministrationController extends Controller {

    public function actionIndex() {
        $_administratorsForm = new AdministratorsForm();
        if (isset(Yii::app()->session['administratorID'])) {
            $accountID = Yii::app()->session['administratorID'];
            if ($accountID != "" || $accountID != 0) {
                if (empty(Yii::app()->request->urlReferrer) || Yii::app()->request->urlReferrer == "") {
                    $this->redirect(Yii::app()->createUrl('administration/statistics'));
                } else {
                    $this->redirect(Yii::app()->request->urlReferrer);
                }
            } else {
                $this->redirect(Yii::app()->createUrl('administration/signin'));
            }
        } else {
            $this->redirect(Yii::app()->createUrl('administration/signin'));
        }
    }

    public function actionAdministration() {
        $_administratorsForm = new AdministratorsForm();
        if (isset(Yii::app()->session['administratorID'])) {
            $accountID = Yii::app()->session['administratorID'];
            if ($accountID != "" || $accountID != 0) {
                if (empty(Yii::app()->request->urlReferrer) || Yii::app()->request->urlReferrer == "") {
                    $this->redirect(Yii::app()->createUrl('administration/statistics'));
                } else {
                    $this->redirect(Yii::app()->request->urlReferrer);
                }
            } else {
                $this->redirect(Yii::app()->createUrl('administration/signin'));
            }
        } else {
            $this->redirect(Yii::app()->createUrl('administration/signin'));
        }
    }

    public function actionSignin() {
        $_administratorsForm = new AdministratorsForm();
        if (isset(Yii::app()->session['administratorID'])) {
            $accountID = Yii::app()->session['administratorID'];
            if ($accountID != "" || $accountID != 0) {
                if (empty(Yii::app()->request->urlReferrer) || Yii::app()->request->urlReferrer == "") {
                    $this->redirect(Yii::app()->createUrl('administration/statistics'));
                } else {
                    $this->redirect(Yii::app()->request->urlReferrer);
                }
            } else {
                $this->render('signin', array('administratorsForm' => $_administratorsForm));
            }
        } else {
            $this->render('signin', array('administratorsForm' => $_administratorsForm));
        }
    }

    public function actionError() {
        $this->render('../error');
    }

    public function actionSigninForm() {
        $_administratorsForm = new AdministratorsForm();
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
                        if ($isEmailAddressValid == true) {
                            $emailAddress = $usernameOrEmailAddress;
                            $result = $_administratorsForm->getAccountIDANDAccountTypeIDAndStatusByEmailAddressAndPassword($emailAddress, $encryptedPassword);
                            if (!empty($result)) {
                                if (isset($result['ID'])) {
                                    if (isset($result['AccountTypeID'])) {
                                        if (isset($result['Status'])) {
                                            $accountID = $result['ID'];
                                            $accountTypeID = $result['AccountTypeID'];
                                            $status = $result['Status'];
                                            $_administratorDetailsForm = new AdministratorsForm();
                                            $firstName = $_administratorDetailsForm->getFirstNameByAccountID($accountID);
                                            return $this->processSignin($accountID, (int) $accountTypeID, (int) $status, $firstName);
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
                            $result = $_administratorsForm->getAccountIDAndAccountTypeIDAndStatusByUsernameAndPassword($username, $encryptedPassword);
                            if (!empty($result)) {
                                if (isset($result['ID'])) {
                                    if (isset($result['AccountTypeID'])) {
                                        if (isset($result['Status'])) {
                                            $accountID = $result['ID'];
                                            $accountTypeID = $result['AccountTypeID'];
                                            $status = $result['Status'];
                                            $_administratorDetailsForm = new AccountDetailsForm();
                                            $firstName = $_administratorDetailsForm->getFirstNameByAccountID($accountID);
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
        $this->render('signin', array('administratorsForm' => $_administratorsForm, 'submitFormResult' => $submitFormResult));
    }

    private function processSignin($accountID, $accountTypeID, $status, $firstName) {
        Yii::app()->session['accountID'] = $accountID;
        $_administratorsForm = new AdministratorsForm();
        $errorCodeDefault = CommonForm::ERROR_CODE_DEFAULT;
        if ($status == CommonForm::STATUS_INACTIVE) {
            $_administrationForm = new AdministrationForm();
            $accountActivationSwitch = $_administrationForm->getAccountActivationSwitch();
            if ($accountActivationSwitch == CommonForm::ACCOUNT_ACTIVATIONSWITCH_ON) {
                $this->render('activateaccount', array('administratorsForm' => $_administratorsForm));
            } else {
                $activatedByID = $accountID;
                $activateAccount = $_administratorsForm->activateAccount($accountID, $activatedByID);
                if ($activateAccount == CommonForm::ERROR_CODE_NO_ERROR) {
                    Yii::app()->session['administratorID'] = $accountID;
                    Yii::app()->session['accountTypeID'] = $accountTypeID;
                    Yii::app()->session['firstName'] = $firstName;
                    $this->redirect(Yii::app()->createUrl('administration/statistics'));
                } else {
                    $submitFormResult = array('errorCode' => $errorCodeDefault, 'errorMessage' => 'Unexpected error occured while attempting to activate your account. Please try again.');
                    $this->render('signin', array('administratorsForm' => $_administratorsForm, 'submitFormResult' => $submitFormResult, 'accountID' => $accountID));
                }
            }
        } else {
            if ($status == CommonForm::STATUS_ACTIVE) {
                if ($accountTypeID == CommonForm::ACCOUNT_TYPE_ADMINISTRATOR) {
                    $submitFormResult = array('errorCode' => CommonForm::ERROR_CODE_NO_ERROR, 'errorMessage' => 'User Success.');
                    Yii::app()->session['administratorID'] = $accountID;
                    Yii::app()->session['accountTypeID'] = $accountTypeID;
                    Yii::app()->session['firstName'] = $firstName;
                    $this->redirect(Yii::app()->createUrl('administration/statistics'));
                } else {
                    if (isset(Yii::app()->session['administratorID'])) {
                        unset(Yii::app()->session['administratorID']);
                    }
                    if (isset(Yii::app()->session['accountTypeID'])) {
                        unset(Yii::app()->session['accountTypeID']);
                    }
                    if (isset(Yii::app()->session['firstName'])) {
                        unset(Yii::app()->session['firstName']);
                    }
                    $submitFormResult = array('errorCode' => $errorCodeDefault, 'errorMessage' => 'You are not authorized to perform this action.');
                }
                $this->render('signin', array('administratorsForm' => $_administratorsForm, 'submitFormResult' => $submitFormResult));
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
                $this->render('signin', array('administratorsForm' => $_administratorsForm, 'submitFormResult' => $submitFormResult));
            }
        }
    }

    public function actionStatistics() {
        $errorCodeDefault = CommonForm::ERROR_CODE_DEFAULT;
        if (isset(Yii::app()->session['accountTypeID'])) {
            $accountType = Yii::app()->session['accountTypeID'];
            if ($accountType == CommonForm::ACCOUNT_TYPE_ADMINISTRATOR) {
                if (isset(Yii::app()->session['administratorID'])) {
                    $accountID = Yii::app()->session['administratorID'];
                    if ($accountID != "" || $accountID != 0) {
                        $this->render('statistics');
                    } else {
                        $message = 'Cannot retieve Administrator ID.';
                        $submitFormResult = array('erroCode' => $errorCodeDefault, 'errorMessage' => $message);
                        $this->redirect(Yii::app()->createUrl('administration/signin', array('submitFormResult' => $submitFormResult)));
                    }
                } else {
                    $message = 'No Session for Administrator ID found.';
                    $submitFormResult = array('erroCode' => $errorCodeDefault, 'errorMessage' => $message);
                    $this->redirect(Yii::app()->createUrl('administration/signin', array('submitFormResult' => $submitFormResult)));
                }
            } else {
                $message = 'Logged out! You are not authorized to perform that action.';
                $submitFormResult = array('erroCode' => $errorCodeDefault, 'errorMessage' => $message);
                $this->redirect(Yii::app()->createUrl('administration/signin', array('submitFormResult' => $submitFormResult)));
            }
        } else {
            $message = 'No session for Administrator found.';
            $submitFormResult = array('erroCode' => $errorCodeDefault, 'errorMessage' => $message);
            $this->redirect(Yii::app()->createUrl('administration/signin', array('submitFormResult' => $submitFormResult)));
        }
    }

    public function actionUsers() {
        $errorCodeDefault = CommonForm::ERROR_CODE_DEFAULT;
        if (isset(Yii::app()->session['accountTypeID'])) {
            $accountType = Yii::app()->session['accountTypeID'];
            if ($accountType == CommonForm::ACCOUNT_TYPE_ADMINISTRATOR) {
                if (isset(Yii::app()->session['administratorID'])) {
                    $accountID = Yii::app()->session['administratorID'];
                    if ($accountID != "" || $accountID != 0) {
                        $this->render('users');
                    } else {
                        $message = 'Cannot retieve Administrator ID.';
                        $submitFormResult = array('erroCode' => $errorCodeDefault, 'errorMessage' => $message);
                        $this->redirect(Yii::app()->createUrl('administration/signin', array('submitFormResult' => $submitFormResult)));
                    }
                } else {
                    $message = 'No Session for Administrator ID found.';
                    $submitFormResult = array('erroCode' => $errorCodeDefault, 'errorMessage' => $message);
                    $this->redirect(Yii::app()->createUrl('administration/signin', array('submitFormResult' => $submitFormResult)));
                }
            } else {
                $message = 'Logged out! You are not authorized to perform that action.';
                $submitFormResult = array('erroCode' => $errorCodeDefault, 'errorMessage' => $message);
                $this->redirect(Yii::app()->createUrl('administration/signin', array('submitFormResult' => $submitFormResult)));
            }
        } else {
            $message = 'No session for Administrator found.';
            $submitFormResult = array('erroCode' => $errorCodeDefault, 'errorMessage' => $message);
            $this->redirect(Yii::app()->createUrl('administration/signin', array('submitFormResult' => $submitFormResult)));
        }
    }

    public function actionIncidents() {
        $errorCodeDefault = CommonForm::ERROR_CODE_DEFAULT;
        if (isset(Yii::app()->session['accountTypeID'])) {
            $accountType = Yii::app()->session['accountTypeID'];
            if ($accountType == CommonForm::ACCOUNT_TYPE_ADMINISTRATOR) {
                if (isset(Yii::app()->session['administratorID'])) {
                    $accountID = Yii::app()->session['administratorID'];
                    if ($accountID != "" || $accountID != 0) {
                        $this->render('statistics');
                    } else {
                        $message = 'Cannot retieve Administrator ID.';
                        $submitFormResult = array('erroCode' => $errorCodeDefault, 'errorMessage' => $message);
                        $this->redirect(Yii::app()->createUrl('administration/signin', array('submitFormResult' => $submitFormResult)));
                    }
                } else {
                    $message = 'No Session for Administrator ID found.';
                    $submitFormResult = array('erroCode' => $errorCodeDefault, 'errorMessage' => $message);
                    $this->redirect(Yii::app()->createUrl('administration/signin', array('submitFormResult' => $submitFormResult)));
                }
            } else {
                $message = 'Logged out! You are not authorized to perform that action.';
                $submitFormResult = array('erroCode' => $errorCodeDefault, 'errorMessage' => $message);
                $this->redirect(Yii::app()->createUrl('administration/signin', array('submitFormResult' => $submitFormResult)));
            }
        } else {
            $message = 'No session for Administrator found.';
            $submitFormResult = array('erroCode' => $errorCodeDefault, 'errorMessage' => $message);
            $this->redirect(Yii::app()->createUrl('administration/signin', array('submitFormResult' => $submitFormResult)));
        }
    }

    public function actionLogmonitoring() {
        $errorCodeDefault = CommonForm::ERROR_CODE_DEFAULT;
        if (isset(Yii::app()->session['accountTypeID'])) {
            $accountType = Yii::app()->session['accountTypeID'];
            if ($accountType == CommonForm::ACCOUNT_TYPE_ADMINISTRATOR) {
                if (isset(Yii::app()->session['administratorID'])) {
                    $accountID = Yii::app()->session['administratorID'];
                    if ($accountID != "" || $accountID != 0) {
                        $this->render('logmonitoring');
                    } else {
                        $message = 'Cannot retieve Administrator ID.';
                        $submitFormResult = array('erroCode' => $errorCodeDefault, 'errorMessage' => $message);
                        $this->redirect(Yii::app()->createUrl('administration/signin', array('submitFormResult' => $submitFormResult)));
                    }
                } else {
                    $message = 'No Session for Administrator ID found.';
                    $submitFormResult = array('erroCode' => $errorCodeDefault, 'errorMessage' => $message);
                    $this->redirect(Yii::app()->createUrl('administration/signin', array('submitFormResult' => $submitFormResult)));
                }
            } else {
                $message = 'Logged out! You are not authorized to perform that action.';
                $submitFormResult = array('erroCode' => $errorCodeDefault, 'errorMessage' => $message);
                $this->redirect(Yii::app()->createUrl('administration/signin', array('submitFormResult' => $submitFormResult)));
            }
        } else {
            $message = 'No session for Administrator found.';
            $submitFormResult = array('erroCode' => $errorCodeDefault, 'errorMessage' => $message);
            $this->redirect(Yii::app()->createUrl('administration/signin', array('submitFormResult' => $submitFormResult)));
        }
    }

    public function actionProfile() {
        $errorCodeDefault = CommonForm::ERROR_CODE_DEFAULT;
        if (isset(Yii::app()->session['accountTypeID'])) {
            $accountType = Yii::app()->session['accountTypeID'];
            if ($accountType == CommonForm::ACCOUNT_TYPE_ADMINISTRATOR) {
                if (isset(Yii::app()->session['administratorID'])) {
                    $accountID = Yii::app()->session['administratorID'];
                    if ($accountID != "" || $accountID != 0) {
                        $this->render('profile');
                    } else {
                        $message = 'Cannot retieve Administrator ID.';
                        $submitFormResult = array('erroCode' => $errorCodeDefault, 'errorMessage' => $message);
                        $this->redirect(Yii::app()->createUrl('administration/signin', array('submitFormResult' => $submitFormResult)));
                    }
                } else {
                    $message = 'No Session for Administrator ID found.';
                    $submitFormResult = array('erroCode' => $errorCodeDefault, 'errorMessage' => $message);
                    $this->redirect(Yii::app()->createUrl('administration/signin', array('submitFormResult' => $submitFormResult)));
                }
            } else {
                $message = 'Logged out! You are not authorized to perform that action.';
                $submitFormResult = array('erroCode' => $errorCodeDefault, 'errorMessage' => $message);
                $this->redirect(Yii::app()->createUrl('administration/signin', array('submitFormResult' => $submitFormResult)));
            }
        } else {
            $message = 'No session for Administrator found.';
            $submitFormResult = array('erroCode' => $errorCodeDefault, 'errorMessage' => $message);
            $this->redirect(Yii::app()->createUrl('administration/signin', array('submitFormResult' => $submitFormResult)));
        }
    }

    public function actionSettings() {
        $errorCodeDefault = CommonForm::ERROR_CODE_DEFAULT;
        if (isset(Yii::app()->session['accountTypeID'])) {
            $accountType = Yii::app()->session['accountTypeID'];
            if ($accountType == CommonForm::ACCOUNT_TYPE_ADMINISTRATOR) {
                if (isset(Yii::app()->session['administratorID'])) {
                    $accountID = Yii::app()->session['administratorID'];
                    if ($accountID != "" || $accountID != 0) {
                        $this->render('settings');
                    } else {
                        $message = 'Cannot retieve Administrator ID.';
                        $submitFormResult = array('erroCode' => $errorCodeDefault, 'errorMessage' => $message);
                        $this->redirect(Yii::app()->createUrl('administration/signin', array('submitFormResult' => $submitFormResult)));
                    }
                } else {
                    $message = 'No Session for Administrator ID found.';
                    $submitFormResult = array('erroCode' => $errorCodeDefault, 'errorMessage' => $message);
                    $this->redirect(Yii::app()->createUrl('administration/signin', array('submitFormResult' => $submitFormResult)));
                }
            } else {
                $message = 'Logged out! You are not authorized to perform that action.';
                $submitFormResult = array('erroCode' => $errorCodeDefault, 'errorMessage' => $message);
                $this->redirect(Yii::app()->createUrl('administration/signin', array('submitFormResult' => $submitFormResult)));
            }
        } else {
            $message = 'No session for Administrator found.';
            $submitFormResult = array('erroCode' => $errorCodeDefault, 'errorMessage' => $message);
            $this->redirect(Yii::app()->createUrl('administration/signin', array('submitFormResult' => $submitFormResult)));
        }
    }

    public function actionCharities() {
        $errorCodeDefault = CommonForm::ERROR_CODE_DEFAULT;
        if (isset(Yii::app()->session['accountTypeID'])) {
            $accountType = Yii::app()->session['accountTypeID'];
            if ($accountType == CommonForm::ACCOUNT_TYPE_ADMINISTRATOR) {
                if (isset(Yii::app()->session['administratorID'])) {
                    $accountID = Yii::app()->session['administratorID'];
                    if ($accountID != "" || $accountID != 0) {
                        $countries = CommonForm::getAllCountries();
                        $this->render('charities', array('countries' => $countries));
                    } else {
                        $message = 'Cannot retieve Administrator ID.';
                        $submitFormResult = array('erroCode' => $errorCodeDefault, 'errorMessage' => $message);
                        $this->redirect(Yii::app()->createUrl('administration/signin', array('submitFormResult' => $submitFormResult)));
                    }
                } else {
                    $message = 'No Session for Administrator ID found.';
                    $submitFormResult = array('erroCode' => $errorCodeDefault, 'errorMessage' => $message);
                    $this->redirect(Yii::app()->createUrl('administration/signin', array('submitFormResult' => $submitFormResult)));
                }
            } else {
                $message = 'Signed out! You are not authorized to perform that action.';
                $submitFormResult = array('erroCode' => $errorCodeDefault, 'errorMessage' => $message);
                $this->redirect(Yii::app()->createUrl('administration/signin', array('submitFormResult' => $submitFormResult)));
            }
        } else {
            $message = 'No session for Administrator found.';
            $submitFormResult = array('erroCode' => $errorCodeDefault, 'errorMessage' => $message);
            $this->redirect(Yii::app()->createUrl('administration/signin', array('submitFormResult' => $submitFormResult)));
        }
    }

    public function actionSearchAllCharities() {
        if (Yii::app()->request->isAjaxRequest) {
            if (isset(Yii::app()->session['administratorID'])) {
                $_charitiesForm = new CharitiesForm();
                $page = Yii::app()->request->getParam('page');
                $limit = Yii::app()->request->getParam('rows');
                $sidx = Yii::app()->request->getParam('sidx');
                $sord = Yii::app()->request->getParam('sord');
                $rows = array();
                $searchResultCharities = $_charitiesForm->getAllCharities();
                if (!$sidx) {
                    $sidx = 1;
                }
                $searchResultCharitiesCount = count($searchResultCharities);
                $count = $searchResultCharitiesCount;

                if ($count > 0) {
                    $total_pages = ceil($count / $limit);
                } else {
                    $total_pages = 1;
                }
                if ($page > $total_pages) {
                    $page = $total_pages;
                }
                $start = $limit * $page - $limit;

                $i = 0;
                if (!empty($searchResultCharities) && $searchResultCharitiesCount > 0) {
                    foreach ($searchResultCharities as $value) {
                        $ID = $value["ID"];
                        $name = $value["Name"];
                        $website = $value["Website"];
                        $facebookPage = $value["FacebookPage"];
                        $twitterPage = $value["TwitterPage"];
                        $status = $value["Status"];
                        $action = 'Action';
                        $rowCharities = array(
                            'ID' => $ID,
                            'Name' => $name,
                            'Website' => $website,
                            'FacebookPage' => $facebookPage,
                            'TwitterPage' => $twitterPage,
                            'Status' => $status,
                            'Action' => $action);
                        array_push($rows, $rowCharities);
                    }
                }
                echo CJSON::encode($rows);
            } else {
                $errorCodeDefault = CommonForm::ERROR_CODE_DEFAULT;
                $message = 'Signed out! You are not authorized to perform that action.';
                $submitFormResult = array('erroCode' => $errorCodeDefault, 'errorMessage' => $message);
                $this->redirect(Yii::app()->createUrl('sign/signout', array('submitFormResult' => $submitFormResult)));
            }
        }
    }

    public function actionSaveAndNextCharityForm() {
        if (Yii::app()->request->isAjaxRequest) {
            $charityName = Yii::app()->request->getParam('charityName');
            $countryCode = Yii::app()->request->getParam('countryCode');
            $postCode = Yii::app()->request->getParam('postCode');
            $landline = Yii::app()->request->getParam('landline');
            $mobileNumber = Yii::app()->request->getParam('mobileNumber');
            $website = Yii::app()->request->getParam('website');
            $facebookPage = Yii::app()->request->getParam('facebookPage');
            $twitterPage = Yii::app()->request->getParam('twitterPage');
            $description = Yii::app()->request->getParam('description');
            $data = array('charityName' => $charityName,
                'country' => $countryCode,
                'postCode' => $postCode,
                'landline' => $landline,
                'mobileNumber' => $mobileNumber,
                'website' => $website,
                'facebookPage' => $facebookPage,
                'twitterPage' => $twitterPage,
                'description' => $description);
            if ($charityName != "") {
                if ($countryCode != "" || $countryCode != 0 || $countryCode != "0") {
                    $_ref_CountriesForm = new Ref_CountriesForm();
                    $isCountryCodeExisting = $_ref_CountriesForm->isCountryCodeExisting($countryCode);
                    if ($isCountryCodeExisting == true) {
                        if ($landline != "") {
                            if (is_numeric($landline)) {
                                if ($mobileNumber != "") {
                                    if (is_numeric($mobileNumber)) {
                                        return $this->saveCharityData($data);
                                    } else {
                                        $errorCode = CommonForm::ERROR_CODE_DEFAULT;
                                        $errorMessage = 'Mobile Number must numbers';
                                        return $this->errorMessageHandler($errorCode, $errorMessage);
                                    }
                                } else {
                                    return $this->saveCharityData($data);
                                }
                            } else {
                                $errorCode = CommonForm::ERROR_CODE_DEFAULT;
                                $errorMessage = 'Landline must be numbers';
                                return $this->errorMessageHandler($errorCode, $errorMessage);
                            }
                        } else {
                            return $this->saveCharityData($data);
                        }
                    } else {
                        $errorCode = CommonForm::ERROR_CODE_DEFAULT;
                        $errorMessage = 'Country does not exists';
                        return $this->errorMessageHandler($errorCode, $errorMessage);
                    }
                } else {
                    $errorCode = CommonForm::ERROR_CODE_DEFAULT;
                    $errorMessage = 'Country is required';
                    return $this->errorMessageHandler($errorCode, $errorMessage);
                }
            } else {
                $errorCode = CommonForm::ERROR_CODE_DEFAULT;
                $errorMessage = 'Charity Name is required';
                return $this->errorMessageHandler($errorCode, $errorMessage);
            }
        } else {
            $errorCode = CommonForm::ERROR_CODE_DEFAULT;
            $errorMessage = 'No data received';
            return $this->errorMessageHandler($errorCode, $errorMessage);
        }
    }

    public function errorMessageHandler($errorCode, $errorMessage) {
        $submitFormResult = array('errorCode' => $errorCode, 'errorMessage' => $errorMessage);
        echo CJSON::encode($submitFormResult);
    }

    public function saveCharityData($data) {
        $_charitiesForm = new CharitiesForm();
        $saveCharityData = $_charitiesForm->saveCharity($data);
        if ($saveCharityData != CommonForm::ERROR_CODE_SAVING_CHARITY) {
            $charityID = $saveCharityData;
            $directory = getcwd() . '/images/uploads/charities/' . $charityID;
            $superUserMode = Yii::app()->params['superUserMode'];
            $groupMode = Yii::app()->params['groupMode'];
            if (CommonForm::makeDirectoryForItems($directory, $superUserMode, $groupMode) == true) {
                Yii::app()->session['itemsFolderName'] = $charityID;
                $this->render('saveandnext', array('postedData' => $postedData));
            } else {
                $message = 'Unexpected error occured. Please try submitting the form again.';
                $submitFormResult = array('errorCode' => CommonForm::ERROR_CODE_DEFAULT, 'errorMessage' => $message);
                $this->render('sell', array('categories' => $categories, 'charities' => $charities, 'postedData' => $postedData, 'submitFormResult' => $submitFormResult));
            }
        } else {
            return $saveCharityData;
        }
    }

}