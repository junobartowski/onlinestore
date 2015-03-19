<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/transaction_validation_user.js"></script>
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
                <option value='0'><i>All Transactions</i></option>
                <option value='1'><i>Transaction ID</i></option>
                <option value='2'><i>Item Name</i></option>
                <option value='3'><i>Amount</i></option>
                <option value='4'><i>Transaction Type</i></option>
                <option value='5'><i>Method</i></option>
                <option value='6'><i>Date Range</i></option>
            </select>
        </div>
        <div id="numberFilterDiv" class="col-sm-2" style="display: none;">
            <select class="form-control" name='numberFilter' id='numberFilter'>
                <option value='0'><i>Equal</i></option>
                <option value='1'><i>Greater Than</i></option>
                <option value='2'><i>Less Than</i></option>
                <option value='3'><i>Greater Than or Equal</i></option>
                <option value='4'><i>Less Than or Equal</i></option>
            </select>
        </div>
        <div id="alphanumericFilterDiv" class="col-sm-2" style="display: none;">
            <select class="form-control" name='alphanumericFilter' id='alphanumericFilter'>
                <option value='0'><i>Like</i></option>
                <option value='1'><i>Equal</i></option>
                <option value='2'><i>Not Equal</i></option>
            </select>
        </div>
        <div id="transactionIDDiv" class="col-sm-2">
            <input type="text" class="form-control" id="transactionID" name="transactionID" placeholder="Transaction ID" autocomplete="off" />
        </div>
        <div id="itemNameDiv" class="col-sm-2">
            <input type="text" class="form-control" id="itemName" name="itemName" placeholder="Item Name" autocomplete="off" />
        </div>
        <div id="amountDiv" class="col-sm-2">
            <input type="text" class="form-control" id="amount" name="amount" placeholder="Amount" autocomplete="off" />
        </div>
        <div id="transactionTypeDiv" class="col-sm-2" style="display: none;">
            <select class="form-control" name='transactionType' id='transactionType'>
                <option value='0'><i>- Select Type -</i></option>
                <?php $optionTransactionType = ''; ?>
                <?php foreach ($transactionTypes as $transactionType) { ?>
                    <?php
                    $transactionTypeID = $transactionType['ID'];
                    $transactionTypeName = $transactionType['Name'];
                    ?>
                    <?php
                    if($transactionTypeName != 'Sold') {
                        $optionTransactionType .= "<option value='" . $transactionTypeID . "' >" . $transactionTypeName . "</option>";
                    }
                    ?>
                <?php } ?>
                <?php echo $optionTransactionType; ?>
            </select>
        </div>
        <div id="methodDiv" class="col-sm-2" style="display: none;">
            <select class="form-control" name='method' id='method'>
                <option value='0'><i>- Select Method -</i></option>
                <option value='1'><i>PayPal</i></option>
                <option value='2'><i>Credit Card</i></option>
            </select>
        </div>
        <div id="datePickersDiv" class="col-sm-4" style="display: none;">
            <?php
            $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                'model' => $model,
                'attribute' => 'startDate',
                'htmlOptions' => array(
                    'size' => '14', // textField size
                    'maxlength' => '10', // textField maxlength
                    'readonly' => true,
                ),
                'options' => array(
                    'showOn' => 'button',
                    'buttonImageOnly' => true,
                    'changeMonth' => true,
                    'changeYear' => true,
                    'buttonText' => 'Select End Date',
                    'buttonImage' => Yii::app()->request->baseUrl . '/images/calendar.png',
                    'dateFormat' => 'yy-mm-dd',
                    'maxDate' => '0'
                )
            ));
            ?>
            <?php
            $this->widget('zii.widgets.jui.CJuiDatePicker', array(
                'model' => $model,
                'attribute' => 'endDate',
                'htmlOptions' => array(
                    'size' => '14', // textField size
                    'maxlength' => '10', // textField maxlength
                    'readonly' => true,
                ),
                'options' => array(
                    'showOn' => 'button',
                    'buttonImageOnly' => true,
                    'changeMonth' => true,
                    'changeYear' => true,
                    'buttonText' => 'Select End Date',
                    'buttonImage' => Yii::app()->request->baseUrl . '/images/calendar.png',
                    'dateFormat' => 'yy-mm-dd',
                    'maxDate' => '0'
                )
            ));
            ?>
        </div>
        <div id="buttonsDiv" class="col-sm-6">
            <button class="btn btn-warning" id="btnSearch" name="btnSearch" type="button" value="Search">Search</button>
            <button class="btn btn-warning" id="btnExcel" name="btnExcel" type="button" value="Excel" style="display: none;">Excel</button>
            <button class="btn btn-warning" id="btnPDF" name="btnPDF" type="button" value="PDF" style="display: none;">PDF</button>
            <button class="btn btn-warning" id="btnPrint" name="btnPrint" type="button" value="Print" style="display: none;">Print</button>
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