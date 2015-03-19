<?php /* @var $this Controller */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en" unselectable='on' onselectstart='return false;'>
    <head>
        <title><?php echo CHtml::encode($this->pageTitle); ?></title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="language" content="en" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1">
            <link rel="canonical" href="http://auction/" />
            <link rel="shortcut icon" href="<?php echo Yii::app()->request->baseUrl; ?>/images/favicon.ico" type="image/x-icon" />
            <link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery-ui-1.11.2/jquery-ui.min.css">
                <script src="<?php echo Yii::app()->request->baseUrl; ?>/js/jquery-ui-1.11.2/external/jquery/jquery.js"></script>
                <link href="<?php echo Yii::app()->request->baseUrl; ?>/css/main.css" rel="stylesheet" />
                <link href="<?php echo Yii::app()->request->baseUrl; ?>/css/Bootstrap/dist/css/bootstrap.min.css" rel="stylesheet" />
                <script src="<?php echo Yii::app()->request->baseUrl; ?>/css/Bootstrap/dist/js/bootstrap.min.js"></script>
                <link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/css/Bootstrap/validation/dist/css/formValidation.css" />
                <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/css/Bootstrap/validation/dist/js/formValidation.js"></script>
                <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/css/Bootstrap/validation/dist/js/framework/bootstrap.js"></script>
                <?php
                Yii::app()->clientScript->registerCoreScript('jquery');
                Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl . '/js/jquery-ui-1.11.2/jquery-ui.js');
                ?>

                <script>
                    $(document).ready(function() {
                    });
                </script>
                </head>

                <body>
                    <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
                        <div class="container">
                            <div class="navbar-header">
                                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                                    <span class="sr-only">Toggle navigation</span>
                                    <span class="icon-bar"></span>
                                    <span class="icon-bar"></span>
                                    <span class="icon-bar"></span>
                                </button>
                                <a class="navbar-brand" href="#"><img src="<?php echo Yii::app()->request->baseUrl; ?>/images/auction_logo_small.png" alt="Sell Buy Donate" /></a>
                            </div>
                            <div id="navbar" class="collapse navbar-collapse">
                                <?php if (isset(Yii::app()->session['accountTypeID'])) { ?>
                                    <?php $accountType = Yii::app()->session['accountTypeID']; ?>
                                    <?php if ($accountType == CommonForm::ACCOUNT_TYPE_USER) { ?>
                                        <ul class="nav navbar-nav">
                                            <li><a href="<?php echo Yii::app()->params['defaultURLPrefix']; ?>user/sell"><i class="glyphicon glyphicon-usd"></i>&nbsp;Sell</a></li>
                                            <li><a href="<?php echo Yii::app()->params['defaultURLPrefix']; ?>item/multiple"><i class="glyphicon glyphicon-list-alt"></i>&nbsp;Items</a></li>
                                            <li><a href="<?php echo Yii::app()->params['defaultURLPrefix']; ?>user/transactions"><i class="glyphicon glyphicon-barcode"></i>&nbsp;Receipts</a></li>
                                            <li><a href="<?php echo Yii::app()->params['defaultURLPrefix']; ?>donate/directdonation"><i class="glyphicon glyphicon-heart-empty"></i>&nbsp;Donate Now</a></li>
                                        </ul>
                                        <ul class="nav navbar-nav navbar-right">
                                            <li><a href="<?php echo Yii::app()->params['defaultURLPrefix']; ?>user/profile"><i class="glyphicon glyphicon-user"></i>&nbsp;
                                                    <?php
                                                    if (isset(Yii::app()->session['firstName'])) {
                                                        $firstName = Yii::app()->session['firstName'];

                                                        if ($firstName != "" || !is_null($firstName)) {
                                                            echo $firstName;
                                                        } else {
                                                            echo "Profile";
                                                        }
                                                    } else {
                                                        echo "Profile";
                                                    }
                                                    ?>
                                                </a></li>
                                            <li><a href="<?php echo Yii::app()->params['defaultURLPrefix']; ?>user/settings"></i>&nbsp;Settings</a></li>
                                            <li><a href="<?php echo Yii::app()->params['defaultURLPrefix']; ?>sign/signout"><i class="glyphicon glyphicon-log-out"></i>&nbsp;Signout</a></li>
                                        </ul>
                                    <?php } else if ($accountType == CommonForm::ACCOUNT_TYPE_CHARITY) { ?>
                                        <ul class="nav navbar-nav">
                                            <li><a href="<?php echo Yii::app()->params['defaultURLPrefix']; ?>item/multiple"><i class="glyphicon glyphicon-list-alt"></i>&nbsp;Items</a></li>
                                            <li><a href="<?php echo Yii::app()->params['defaultURLPrefix']; ?>charity/transactions"><i class="glyphicon glyphicon-barcode"></i>&nbsp;Transactions</a></li>
                                        </ul>
                                        <ul class="nav navbar-nav navbar-right">
                                            <li><a href="<?php echo Yii::app()->params['defaultURLPrefix']; ?>charity/profile"><i class="glyphicon glyphicon-user">Charity Profile</a></li>
                                            <li><a href="<?php echo Yii::app()->params['defaultURLPrefix']; ?>charity/settings"><i class="glyphicon glyphicon-cog">Settings</a></li>
                                            <li><a href="<?php echo Yii::app()->params['defaultURLPrefix']; ?>sign/signout"><i class="glyphicon glyphicon-log-out"></i>&nbsp;Signout</a></li>
                                        </ul>
                                    <?php } else if ($accountType == CommonForm::ACCOUNT_TYPE_ADMINISTRATOR) { ?>
                                        <ul class="nav navbar-nav">
                                            <li><a href="<?php echo Yii::app()->params['defaultURLPrefix']; ?>administration/statistics"><i class="glyphicon glyphicon-stats"></i>&nbsp;Statistics</a></li>
                                            <li><a href="<?php echo Yii::app()->params['defaultURLPrefix']; ?>administration/incidents"><i class="glyphicon glyphicon-warning-sign"></i>&nbsp;Incidents</a></li>
                                            <li><a href="<?php echo Yii::app()->params['defaultURLPrefix']; ?>administration/logmonitoring"><i class="glyphicon glyphicon-list"></i>&nbsp;Log Monitoring</a></li>
                                            <li><a href="<?php echo Yii::app()->params['defaultURLPrefix']; ?>administration/users"><i class="glyphicon glyphicon-list-alt"></i>&nbsp;Users</a></li>
                                            <li><a href="<?php echo Yii::app()->params['defaultURLPrefix']; ?>administration/charities"><i class="glyphicon glyphicon-heart"></i>&nbsp;Charities</a></li>
                                        </ul>
                                        <ul class="nav navbar-nav navbar-right">
                                            <li><a href="<?php echo Yii::app()->params['defaultURLPrefix']; ?>administration/profile"><i class="glyphicon glyphicon-user"></i>&nbsp;Admin</a></li>
                                            <li><a href="<?php echo Yii::app()->params['defaultURLPrefix']; ?>administration/settings"><i class="glyphicon glyphicon-cog"></i>&nbsp;Settings</a></li>
                                            <li><a href="<?php echo Yii::app()->params['defaultURLPrefix']; ?>sign/signout"><i class="glyphicon glyphicon-log-out"></i>&nbsp;Signout</a></li>
                                        </ul>
                                    <?php } else { ?>
                                        <ul class="nav navbar-nav">
                                            <li><a href="<?php echo Yii::app()->params['defaultURLPrefix']; ?>item/multiple"><i class="glyphicon glyphicon-list-alt"></i>&nbsp;Items</a></li>
                                            <li><a href="<?php echo Yii::app()->params['defaultURLPrefix']; ?>donate/directdonation"><i class="glyphicon glyphicon-heart-empty"></i>&nbsp;Donate Now</a></li>
                                        </ul>
                                        <ul class="nav navbar-nav navbar-right">
                                            <li><a href="<?php echo Yii::app()->params['defaultURLPrefix']; ?>sign/signin"><i class="glyphicon glyphicon-home"></i>&nbsp;Sign in</a></li>
                                            <li><a href="<?php echo Yii::app()->params['defaultURLPrefix']; ?>sign/signup"><i class="glyphicon glyphicon-pencil"></i>&nbsp;Sign up</a></li>
                                        </ul>
                                    <?php } ?>
                                <?php } else { ?>
                                    <ul class="nav navbar-nav">
                                        <li><a href="<?php echo Yii::app()->params['defaultURLPrefix']; ?>item/multiple"><i class="glyphicon glyphicon-list-alt"></i>&nbsp;Items</a></li>
                                        <li><a href="<?php echo Yii::app()->params['defaultURLPrefix']; ?>donate/directdonation"><i class="glyphicon glyphicon-heart-empty"></i>&nbsp;Donate Now</a></li>
                                    </ul>
                                    <ul class="nav navbar-nav navbar-right">
                                        <li><a href="<?php echo Yii::app()->params['defaultURLPrefix']; ?>sign/signin"><i class="glyphicon glyphicon-home"></i>&nbsp;Sign in</a></li>
                                        <li><a href="<?php echo Yii::app()->params['defaultURLPrefix']; ?>sign/signup"><i class="glyphicon glyphicon-pencil"></i>Sign up</a></li>
                                    </ul>
                                <?php } ?>
                            </div><!--/.nav-collapse -->
                        </div>
                    </nav>
                    <?php echo $content; ?>
                </body>
                <div id="footer" class="nav navbar-inverse"><i class="glyphicon glyphicon-copyright-mark"></i>&nbsp;Global Sports League Ltd. UK 2015</div>
                </html>
