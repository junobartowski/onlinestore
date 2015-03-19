<?php
if (isset(Yii::app()->session['accountID'])) {
    $accountID = Yii::app()->session['accountID'];
    if ($accountID != "" || $accountID != 0) {
        ?>

        <script src="<?php echo Yii::app()->request->baseUrl; ?>/js/sell_validation.js"></script>
        <script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/tinymce/js/tinymce/tinymce.min.js"></script>
        <script>
            tinymce.init({
                selector: "textarea#elm1",
                theme: "modern",
                width: 780,
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
                    <form id="sellForm" class="form-horizontal" method="POST" action="saveAndNextForm">
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Item Name</label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control" name="itemName" placeholder="Item Name" autocomplete="off" />
                            </div>
                            <div class="col-sm-4">
                                <i>eg. Necklace with Diamond Pendant</i>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Category</label>
                            <div class="col-sm-4">
                                <select class="form-control" name='category' id='category'>
                                    <option value=''><i>Choose a Category</i></option>
                                    <?php $optionCategory = ''; ?>
                                    <?php foreach ($categories as $category) { ?>
                                        <?php
                                        $categoryID = $category['ID'];
                                        $categoryName = $category['Name'];
                                        ?>
                                        <?php
                                        $optionCategory .= "<option value='" . $categoryID . "' >" . $categoryName . "</option>";
                                        ?>
                                    <?php } ?>
                                    <?php echo $optionCategory; ?>
                                </select>
                            </div>
                            <label class="col-sm-2 control-label">Condition</label>
                            <div class="col-sm-4">
                                <div class="radio">
                                    <label>
                                        <input type="radio" name="itemCondition" value="1" /> Brand New
                                    </label>
                                    <label>
                                        <input type="radio" name="itemCondition" value="2" /> Used
                                    </label>
                                    <label>
                                        <input type="radio" name="itemCondition" value="2" /> Other
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Charity</label>
                            <div class="col-sm-4">
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
                            <label class="col-sm-2 control-label">Type</label>
                            <div class="col-sm-3">
                                <select class="form-control" name='forType' id='forType'>
                                    <option value=''><i>Sell Type</i></option>
                                    <option value='1'><i>Simply Sell</i></option>
                                    <option value='2'><i>For Bidding</i></option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Price</label>
                            <div class="col-sm-3">
                                <div class="input-group">
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-usd"></span></span>
                                    <input type="text" class="form-control" name="price" id="price" placeholder="0.00" value="" autocomplete="off" />
                                </div>
                            </div>
                            <label class="col-sm-2 control-label col-sm-offset-1">Shipping Fee</label>
                            <div class="col-sm-3">
                                <div class="input-group">
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-usd"></span></span>
                                    <input type="text" class="form-control" name="shippingFee" id="shippingFee" placeholder="0.00" value="" autocomplete="off" />
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-8 col-sm-offset-3">
                                <div class="col-sm-10">
                                    <label class="col-sm-6 control-label">Donation</label>
                                    <div class="col-sm-4">
                                        <div class="input-group">
                                            <span class="input-group-addon"><span class="glyphicon glyphicon-usd"></span></span>
                                            <input type="text" class="form-control" name="donation" id="donation" placeholder="0.00" value="" autocomplete="off" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-8 col-sm-offset-3">
                                <div class="col-sm-10">
                                    <label class="col-sm-6 control-label">Total Amount To Buyer</label>
                                    <div class="col-sm-4">
                                        <div class="input-group">
                                            <span class="input-group-addon"><span class="glyphicon glyphicon-usd"></span></span>
                                            <input type="text" class="form-control" name="totalAmountToBuyer" id="totalAmountToBuyer" placeholder="0.00" disabled="true" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-8 col-sm-offset-3">
                                <div class="col-sm-10">
                                    <label class="col-sm-6 control-label">Total Amount To Donate</label>
                                    <div class="col-sm-4">
                                        <div class="input-group">
                                            <span class="input-group-addon"><span class="glyphicon glyphicon-usd"></span></span>
                                            <input type="text" class="form-control" name="totalAmountToDonate" id="totalAmountToDonate" placeholder="0.00" disabled="true" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-8 col-sm-offset-3">
                                <div class="col-sm-10">
                                    <label class="col-sm-6 control-label">Total Amount You will Earn</label>
                                    <div class="col-sm-4">
                                        <div class="input-group">
                                            <span class="input-group-addon"><span class="glyphicon glyphicon-usd"></span></span>
                                            <input type="text" class="form-control" name="totalAmountSellerWillEarn" id="totalAmountSellerWillEarn" placeholder="0.00" disabled="true" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Description</label>
                            <div class="col-sm-10">
                                <textarea id="elm1" name="itemDescription"></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-2 col-sm-offset-10">
                                <button class="btn btn-warning btn-block" id="btnSaveAndNext" name="btnSaveAndNext" type="submit" value="Save and Next">Save and Next</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <?php
    } else {
        ?>
        <br><br>The page does not exists
        <?php
    }
} else {
    ?>
    <br><br>The page does not exists
    <?php
}
?>