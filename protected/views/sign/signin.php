<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/signin_validation.js"></script>
<link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/css/font-awesome-4.2/css/font-awesome.css" />
<link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/css/font-awesome-4.2/css/bootstrap-social.css" />
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