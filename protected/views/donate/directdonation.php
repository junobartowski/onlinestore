<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/donate_validation.js"></script>
<br>
<br>
<form id="directDonationForm" class="form-horizontal" method="POST" action="directDonationForm">
    <div class="container">
        <div class="row">
            <div class="col-sm-8 col-sm-offset-2">
                <div class="page-header">
                    <h2>Direct Donation</h2>
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
            </div>
                <div class="col-sm-10 col-sm-offset-3">
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Charity</label>
                        <div class="col-sm-3">
                            <select class="form-control" name='charity' id='charity'>
                                <option value=''><i>Choose a Charity</i></option>
                                <?php $optionCharity = ''; ?>
                                <?php foreach ($charities as $charity) { ?>
                                    <?php
                                    $charityID = $charity['ID'];
                                    $charityName = $charity['Name'];
                                    ?>
                                    <?php
                                    $optionCharity .= "<option value='" . $charityID . "' >" . $charityName . "</option>";
                                    ?>
                                <?php } ?>
                                <?php echo $optionCharity; ?>
                            </select>
                        </div>
                        <div class="col-sm-2">
                            <button class="btn btn-warning btn-block" id="btnDirectDonation" name="btnDirectDonation" type="submit" value="Donate Now"><i class="glyphicon glyphicon-heart-empty"></i>&nbsp;Donate Now</button>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Amount</label>
                        <div class="col-sm-3">
                            <div class="input-group">
                                <span class="input-group-addon"><span class="glyphicon glyphicon-usd"></span></span>
                                <input type="text" class="form-control" name="donationAmount" id="donation" placeholder="0.00" autocomplete="off" />
                            </div>
                        </div>
                    </div>
                </div>
        </div>
    </div>
</form>