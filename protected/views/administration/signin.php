<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/signin_validation.js"></script>
<div class="container">
    <div class="row">
        <div class="col-sm-4 col-sm-offset-4">
            <div class="page-header">
                <h2>Sign in</h2>
                <?php if (isset($submitFormResult)) { ?>
                <div class="alert alert-danger">
                    <strong>Error!</strong>&nbsp;
                    <?php echo $submitFormResult['errorMessage']; ?>
                </div>
                <?php } else { ?>
                <?php if (isset($_GET['submitFormResult'])) { ?>
                    <?php $submitFormResult = $_GET['submitFormResult'] ?>
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
                        <button class="btn btn-warning btn-block" id="btnSignin" name="btnSignin" type="submit" value="Sign in">Sign in</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>