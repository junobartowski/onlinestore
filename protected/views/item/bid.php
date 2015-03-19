<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/bid_validation.js"></script>
<?php if (isset(Yii::app()->session['accountTypeID'])) { ?>
    <?php $accountType = Yii::app()->session['accountTypeID']; ?>
    <?php if ($accountType == CommonForm::ACCOUNT_TYPE_USER) { ?>
        <?php if (isset($item)) { ?>
            <div class="alert alert-success" id="ajaxMessageSuccessContainer">
                <div class="ajaxMessage"></div>
            </div>
            <div class="alert alert-danger" id="ajaxMessageDangerContainer">
                <strong>Error!</strong>&nbsp;
                <div class="ajaxMessage"></div>
            </div>
            <form id="formBid" target="_top" name="_xclick" method="post" action="bidItemConfirmed">
                <img class="cbp-vm-image" src="<?php echo Yii::app()->request->baseUrl; ?>/images/uploads/items/<?php echo $item['ID']; ?>/thumbnail/<?php echo $item['ProfilePhotoFilename']; ?>">
                <br>
                <center>
                    <h3><?php echo $item['Name']; ?>
                        <?php if ($item['Status'] == CommonForm::ITEM_STATUS_APPROVED_BY_CHARITY) { ?>
                            <span style="color: #009900;"><i class="glyphicon glyphicon-ok"></i></span>
                        <?php } ?>
                    </h3>
                    <h4>Last Bid: $<?php echo $item['LastPrice']; ?></h4>
                    <div class="cbp-vm-details">
                        <?php $urlForSocial = Yii::app()->params['defaultURLPrefix'] . 'item/single?itemID=' . $item['ID']; ?>
                        <?php $facebookLink = "http://www.facebook.com/sharer.php?u=" . $urlForSocial; ?>
                        <a onclick="window.open('<?php echo $facebookLink; ?>', 'newwindow', 'width=500, height=250');" class="btn btn-social btn-facebook">
                            <i class="fa fa-share"></i>Share
                        </a>
                        <?php $twitterLink = "http://twitter.com/home?status=" . $urlForSocial; ?>
                        <a onclick="window.open('<?php echo $twitterLink; ?>', 'newwindow', 'width=500, height=250');" class="btn btn-social btn-twitter">
                            <i class="fa fa-retweet"></i>Tweet
                        </a>
                        <br>
                        <br>
                        <?php
                        if ($item['ItemCondition'] == 1) {
                            $condition = "Brand New";
                        } else if ($item['ItemCondition'] == 2) {
                            $condition = "Used";
                        } else {
                            $condition = "N/A";
                        }
                        echo "Condition : " . $condition;
                        ?>
                        <br>
                        <?php
                        echo "Beneficiary : ";
                        ?>
                        <br>
                        <?php
                        echo "Donation : $" . $item['Donation'];
                        ?>
                        <br>
                        <br>
                        <div class="col-sm-4 col-sm-offset-4">
                            <div class="input-group">
                                <span class="input-group-addon"><span class="glyphicon glyphicon-usd"></span></span>
                                <input type="text" class="form-control" name="price" id="price" placeholder="0.00" value="" autocomplete="off" />
                            </div>
                        </div>
                    </div>
                </center>
                <br>
                <br>
                <input type="hidden" id="itemID" name="itemID" value="<?php echo $item['ID']; ?>">
                <center>
                    <button type="submit" class="btn btn-default btn-warning"><i class="glyphicon glyphicon-usd"></i>&nbsp;Place Bid</button>
                    <button type="button" id="btnCancelBid" class="btn btn-default btn-warning" data-dismiss="modal"><i class="glyphicon glyphicon-remove"></i>&nbsp;Cancel</button>
                </center>
            </form>
            <br>
        <?php } else { ?>
            Failed to retrieve item information
        <?php } ?>
    <?php } else { ?>
        <script src="<?php echo Yii::app()->request->baseUrl; ?>/js/signin_validation.js"></script>
        <link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/css/font-awesome-4.2/css/font-awesome.css" />
        <link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/css/font-awesome-4.2/css/bootstrap-social.css" />
        <div class="container">
            <div class="row">
                <div class="col-sm-4 col-sm-offset-4">
                    <div class="page-header">
                        <h2>Sign in</h2>
                        <div class="alert alert-success" id="ajaxMessageSuccessContainer">
                            <div class="ajaxMessage"></div>
                        </div>
                        <div class="alert alert-danger" id="ajaxMessageDangerContainer">
                            <strong>Error!</strong>&nbsp;
                            <div class="ajaxMessage"></div>
                        </div>
                    </div>
                    <form id="signinForm" name="signinForm" class="form-horizontal col-sm-offset-2" role="form">
                        <div class="form-group">
                            <label for="usernameOrEmailAddress" class="sr-only">Username or Email Address</label>
                            <div class="col-sm-10">
                                <input type="text" id="usernameOrEmailAddress" name="usernameOrEmailAddress" class="form-control" placeholder="Username or Email Address" autocomplete="off" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="password" class="sr-only">Password</label>
                            <div class="col-sm-10">
                                <input type="password" id="password" name="password" class="form-control" placeholder="Password" />
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-10">
                                <div class="checkbox" id="rememberCheckbox">
                                    <label>
                                        <input type="checkbox" value="remember-me"> Remember me
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-10">
                                <button class="btn btn-warning btn-block" id="btnSignin" name="btnSignin" type="submit" value="Sign in">Sign in</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    <?php } ?>
<?php } else { ?>
    <script src="<?php echo Yii::app()->request->baseUrl; ?>/js/signin_validation.js"></script>
    <link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/css/font-awesome-4.2/css/font-awesome.css" />
    <link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/css/font-awesome-4.2/css/bootstrap-social.css" />
    <div class="container">
        <div class="row">
            <div class="col-sm-4 col-sm-offset-1">
                <div class="page-header">
                    <h2>Sign in</h2>
                    <div class="alert alert-success" id="ajaxMessageSuccessContainer">
                        <div class="ajaxMessage"></div>
                    </div>
                    <div class="alert alert-danger" id="ajaxMessageDangerContainer">

                        <strong>Error!</strong>&nbsp;
                        <div class="ajaxMessage"></div>
                    </div>
                </div>
                <form id="signinForm" class="form-horizontal col-sm-offset-2" role="form">
                    <div class="form-group">
                        <label for="usernameOrEmailAddress" class="sr-only">Username or Email Address</label>
                        <div class="col-sm-10">
                            <input type="text" id="usernameOrEmailAddress" name="usernameOrEmailAddress" class="form-control" placeholder="Username or Email Address" autocomplete="off" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="password" class="sr-only">Password</label>
                        <div class="col-sm-10">
                            <input type="password" id="password" name="password" class="form-control" placeholder="Password" />
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-10">
                            <div class="checkbox" id="rememberCheckbox">
                                <label>
                                    <input type="checkbox" value="remember-me"> Remember me
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-10">
                            <button class="btn btn-warning btn-block" id="btnSignin" name="btnSignin" type="submit" value="Sign in">Sign in</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php } ?>
<br>