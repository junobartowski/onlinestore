<?php if (isset(Yii::app()->session['accountID'])) { ?>
    <?php $accountID = Yii::app()->session['accountID']; ?>
    <script src="<?php echo Yii::app()->request->baseUrl; ?>/js/activate_account.js"></script>
    <div class="container">
        <div class="row">
            <div class="col-sm-4 col-sm-offset-4">
                <div class="page-header">
                    <h2>Activate Account</h2>
                    <?php if (isset($submitFormResult)) { ?>
                        <?php if ($submitFormResult['errorCode'] == CommonForm::ERROR_CODE_NO_ERROR) { ?>
                            <div class="alert alert-success">
                                
                                <strong>Success!</strong>&nbsp;
                                <?php echo $submitFormResult['errorMessage']; ?>
                            </div>
                        <?php } else { ?>
                            <div class="alert alert-danger">
                                
                                <strong>Error!</strong>&nbsp;
                                <?php echo $submitFormResult['errorMessage']; ?>
                            </div>
                        <?php } ?>
                    <?php } ?>
                </div>
                <form id="activateAccountPostForm" class="form-horizontal col-sm-offset-2" role="form" method="POST" action="activateAccountPostForm">
                    <div class="form-group">
                        <label for="activationCode" class="sr-only">Activation Code</label>
                        <div class="col-sm-10">
                            <input type="text" id="activationCode" name="activationCode" class="form-control" placeholder="Activation Code" autocomplete="off" />
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-10">
                            <button class="btn btn-warning btn-block" id="btnActivateAccount" name="btnActivateAccount" type="submit" value="Activate Now!">Activate Now!</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php } else { ?>
    <script src="<?php echo Yii::app()->request->baseUrl; ?>/js/activate_account.js"></script>
    <div class="container">
        <div class="row">
            <div class="col-sm-4 col-sm-offset-4">
                <div class="page-header">
                    <h2>Sign in</h2>
                    <?php if (isset($submitFormResult)) { ?>
                        <?php if ($submitFormResult['errorCode'] == CommonForm::ERROR_CODE_NO_ERROR) { ?>
                            <div class="alert alert-success">
                                
                                <strong>Success!</strong>&nbsp;
                                <?php echo $submitFormResult['errorMessage']; ?>
                            </div>
                        <?php } else { ?>
                            <div class="alert alert-danger">
                                
                                <strong>Error!</strong>&nbsp;
                                <?php echo $submitFormResult['errorMessage']; ?>
                            </div>
                        <?php } ?>
                    <?php } ?>
                </div>
                <form id="signinForm" class="form-horizontal col-sm-offset-2" role="form" method="POST" action="signinForm">
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
                            <div class="checkbox">
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