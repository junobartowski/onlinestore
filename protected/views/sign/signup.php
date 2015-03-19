<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/signup_validation.js"></script>
<link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/css/font-awesome-4.2/css/font-awesome.css" />
<link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/css/font-awesome-4.2/css/bootstrap-social.css" />
<?php
Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl . '/js/msdropdown/jquery.dd.min.js');
?>
<div class="container">
    <div class="row">
        <div class="col-sm-8 col-sm-offset-2">
            <div class="page-header">
                <h2>
                    Sign up
                </h2>
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
            <form id="signupForm" class="form-horizontal" method="POST" action="signupForm">
                <div class="form-group">
                    <label class="col-sm-3 control-label">Full name</label>
                    <div class="col-sm-4">
                        <input type="text" class="form-control" name="firstName" placeholder="First name" />
                    </div>
                    <div class="col-sm-4">
                        <input type="text" class="form-control" name="lastName" placeholder="Last name" />
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-3 control-label">Sex</label>
                    <div class="col-sm-4">
                        <div class="radio">
                            <label>
                                <input type="radio" name="sex" value="1" /> Male
                            </label>
                            <label>
                                <input type="radio" name="sex" value="2" /> Female
                            </label>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <img class="preloader_small" style="float: right;" src="<?php echo Yii::app()->request->baseUrl . '/images/preloader_small.GIF'; ?>" alt="Loading..." />
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-3 control-label">Location</label>
                    <div class="col-sm-4">
                        <select class="form-control" name='country' id='country'>
                            <option value=''><i>Your Country</i></option>
                            <?php $option = ''; ?>
                            <?php foreach ($countries as $country) { ?>
                                <?php
                                $countryCode = $country['Code'];
                                $countryName = $country['Name'];
                                ?>
                                <?php
                                $option .= "<option value='" . $countryCode . "' data-image='" . Yii::app()->request->baseUrl . "/images/msdropdown/icons/blank.gif' data-imagecss='flag " . $countryCode . "' data-title='" . $countryName . "'>" . $countryName . "</option>";
                                ?>
                            <?php } ?>
                            <?php echo $option; ?>
                        </select>
                    </div>
                    <div class="col-sm-4">
                        <select class="form-control" name='postCode' id='postCode'>
                            <option value=''><i>Your Post Code</i></option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">Contact Details</label>
                    <div class="col-sm-4">
                        <div class="input-group">
                            <span class="input-group-addon"><span class="glyphicon glyphicon-envelope"></span></span>
                            <input type="email" class="form-control" name="emailAddress" placeholder="Email Address" />
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="input-group">
                            <span class="input-group-addon"></i><div id="phonePrefix"><i class="fa fa-mobile-phone"></i></div></span>
                            <input type="text" class="form-control" name="mobileNumber" placeholder="Mobile Number" />
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-3 control-label">Username</label>
                    <div class="col-sm-4">
                        <div class="input-group">
                            <span class="input-group-addon"><span class="glyphicon glyphicon-user"></span></span>
                            <input type="text" class="form-control" name="username" placeholder="Username"  autocomplete="off" />
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-3 control-label">Password</label>
                    <div class="col-sm-4">
                        <div class="input-group">
                            <span class="input-group-addon"><span class="glyphicon glyphicon-lock"></span></span>
                            <input type="password" class="form-control" name="password" placeholder="(Case-sensitive)" />
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="input-group">
                            <span class="input-group-addon"><span class="glyphicon glyphicon-lock"></span></span>
                            <input type="password" class="form-control" name="confirmPassword" placeholder="Confirm Password" />
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-3 control-label" id="captchaOperation"></label>
                    <div class="col-sm-3">
                        <input type="text" class="form-control" name="captcha" placeholder="Answer" autocomplete="off" />
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-sm-6 col-sm-offset-3">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="agree" value="agree" /> I agree with the <a id="termsAndConditionsLink" href="#">Terms and Conditions</a>.
                            </label>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-sm-9 col-sm-offset-3">
                        <button type="submit" class="btn btn-warning" id="btnSignup" name="btnSignup" value="Sign up">Sign up</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="termsAndConditionsModal" class="modal fade" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Terms and Conditions</h4>
            </div>
            <div class="modal-body">
                <p><?php echo Yii::app()->params["projectName"]; ?> Terms</p>
                <p class="text-warning"><small>Lorem ipsum...</small></p>
                <p><?php echo Yii::app()->params["projectName"]; ?> Conditions</p>
                <p class="text-warning"><small>Lorem ipsum...</small></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>