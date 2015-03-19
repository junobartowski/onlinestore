<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/admin_charities_validation.js"></script>
<script type="text/ecmascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/jqGrid/js/jquery.jqGrid.js"></script>
<script type="text/ecmascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/jqGrid/js/i18n/grid.locale-en.js"></script>
<link href="<?php echo Yii::app()->request->baseUrl; ?>/css/ui.jqgrid.css" rel="stylesheet" />
<link href="<?php echo Yii::app()->request->baseUrl; ?>/css/layout-jqgrid.css" rel="stylesheet" />
<link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/css/font-awesome-4.2/css/font-awesome.css" />
<link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/css/font-awesome-4.2/css/bootstrap-social.css" />
<!-- TABLE INIT -->
<br>
<br>
<div class="jqGridTableFilter form-group">
    <div class="col-sm-12">
        <div id="mainSearchFilterDiv" class="col-sm-2">
            <select class="form-control" name='mainSearchFilter' id='mainSearchFilter'>
                <option value='0'><i>All Charities</i></option>
                <option value='1'><i>Charity ID</i></option>
                <option value='2'><i>Charity Name</i></option>
                <option value='3'><i>Username</i></option>
                <option value='4'><i>Email Address</i></option>
                <option value='5'><i>Status</i></option>
            </select>
        </div>
        <div id="buttonsDiv" class="col-sm-6">
            <button class="btn btn-warning" id="btnSearch" name="btnSearch" type="button" value="Search"><i class="glyphicon glyphicon-search"></i>&nbsp;Search</button>
            <button class="btn btn-warning" id="btnAdd" name="btnAdd" type="button" value="Add"><i class="glyphicon glyphicon-plus"></i>&nbsp;New Charity</button>
            <button class="btn btn-warning" id="btnExcel" name="btnExcel" type="button" value="Excel" style="display: none;"><i class="glyphicon glyphicon-download"></i>&nbsp;Excel</button>
            <button class="btn btn-warning" id="btnPDF" name="btnPDF" type="button" value="PDF" style="display: none;"><i class="glyphicon glyphicon-download"></i>&nbsp;PDF</button>
            <button class="btn btn-warning" id="btnPrint" name="btnPrint" type="button" value="Print" style="display: none;"><i class="glyphicon glyphicon-print"></i>&nbsp;Print</button>
        </div>
    </div>
</div>
<br>
<br>
<div id="errorMessage" style="margin: 0 auto; width: 40%; text-align: center;">
    <div class="alert alert-danger">
        <div id="message"></div>
    </div>
</div>
<div class="jqGridTable">
    <table id="jqgrid"></table>
    <div id="pager_jqgrid_left">

    </div>
    <div id="pager_jqgrid"></div>
</div>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/sell_validation.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/tinymce/js/tinymce/tinymce.min.js"></script>
<link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/css/font-awesome-4.2/css/font-awesome.css" />
<link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/css/font-awesome-4.2/css/bootstrap-social.css" />
<script>
    tinymce.init({
        selector: "textarea#elm1",
        theme: "modern",
        width: 400,
        height: 200,
        plugins: [
            "advlist autolink link lists charmap print preview hr anchor pagebreak spellchecker",
            "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime nonbreaking",
            "table contextmenu directionality emoticons template paste textcolor"
        ],
        content_css: "css/content.css",
        toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | l      ink image | print preview media fullpage | forecolor backcolor emoticons",
        style_formats: [
            {title: 'Bold text', inline: 'b'},
            {title: 'Red text', inline: 'span', styles: {color: '#ff0000'}},
            {title: 'Red header', block: 'h1', styles: {color: '#ff0000'}},
            {title: 'Example 1', inline: 'span', classes: 'example1'},
            {title: 'Example 2', inline: 'span', classes: 'example2'},
            {title: 'Table styles'},
            {title: 'Table row 1', selector: 'tr', classes: 'tablerow1'}
        ],
    });
</script>
<div id="addCharityModal" class="modal fade" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">&nbsp;</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="page-header">
                            <h2>New Charity</h2>
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
                        <form id="saveCharityForm" class="form-horizontal" method="POST" action="saveCharityForm">
                            <div class="form-group">
                                <div class="col-sm-10">
                                    <label class="col-sm-4 control-label">Charity Name</label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" name="charityName" placeholder="Charity Name" autocomplete="off" />
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-10">
                                    <label class="col-sm-4 control-label">Country</label>
                                    <div class="col-sm-8">
                                        <select class="form-control" name='country' id='country'>
                                            <option value=''><i>Charity Country</i></option>
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
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-10">
                                    <label class="col-sm-4 control-label">PostCode</label>
                                    <div class="col-sm-8">
                                        <select class="form-control" name='postCode' id='postCode'>
                                            <option value=''><i>Your Post Code</i></option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-10">
                                    <label class="col-sm-4 control-label">Landline Number</label>
                                    <div class="col-sm-8">
                                        <div class="input-group">
                                            <span class="input-group-addon"><div id="phonePrefix"><i class="fa fa-phone"></i></div></span>
                                            <input type="text" class="form-control" name="landline" id="landline" placeholder="Landline Number" value="" autocomplete="off" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-10">
                                    <label class="col-sm-4 control-label">Mobile Number</label>
                                    <div class="col-sm-8">
                                        <div class="input-group">
                                            <span class="input-group-addon"><div id="phonePrefix"><i class="fa fa-mobile-phone"></i></div></span>
                                            <input type="text" class="form-control" name="mobileNumber" id="mobileNumber" placeholder="Mobile Number" value="" autocomplete="off" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-10">
                                    <label class="col-sm-4 control-label">Website</label>
                                    <div class="col-sm-8">
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="fa fa-html5"></i></span></span>
                                            <input type="text" class="form-control" name="website" id="website" placeholder="https://www.charity.com" value="" autocomplete="off" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-10">
                                    <label class="col-sm-4 control-label">Facebook Page</label>
                                    <div class="col-sm-8">
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="fa fa-facebook"></i></span>
                                            <input type="text" class="form-control" name="facebookPage" id="facebookPage" placeholder="https://facebook.com/charitypage" value="" autocomplete="off" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-10">
                                    <label class="col-sm-4 control-label">Twitter Page</label>
                                    <div class="col-sm-8">
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="fa fa-twitter"></i></span>
                                            <input type="text" class="form-control" name="twitterPage" id="twitterPage" placeholder="https://twitter.com/charitypage" value="" autocomplete="off" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 col-sm-offset-1 control-label">Description</label>
                                <div class="col-sm-8">
                                    <textarea id="elm1" name="charityDescription"></textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-4 col-sm-offset-7">
                                    <button class="btn btn-default btn-warning" id="btnSave" name="btnSave" type="submit" value="Save"><i class="glyphicon glyphicon-save"></i>&nbsp;Save</button>
                                    <button type="button" class="btn btn-default btn-warning" data-dismiss="modal"><i class="glyphicon glyphicon-remove"></i>&nbsp;Cancel</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>