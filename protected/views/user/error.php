<div class="container">
    <div class="row">
        <div class="col-sm-10 col-sm-offset-1">
            <div class="page-header">
                <h2>Sell an Item</h2>
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
    </div>
</div>