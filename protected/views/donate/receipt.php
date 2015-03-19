<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/receipt.js"></script>
<br>
<br>
<br>
<?php
if (isset($receipt)) {
    if (!empty($receipt)) {
        $transactionID = $receipt['TransactionID'];
        $dateTime = $receipt['DateTime'];
        $currency = $receipt['Currency'];
        $amount = $receipt['Amount'];
        $Method = $receipt['Method'];
        $firstName = $receipt['FirstName'];
        $lastName = $receipt['LastName'];
        $payerEmailAddress = $receipt['PayerEmailAddress'];
        $payerID = $receipt['PayerID'];
        ?>
        <center>
            <div id="receiptDocument">
                <u>TRANSACTION DETAILS</u><br>
                Transaction ID : <?php echo $transactionID; ?><br>
                Transaction Date and Time : <?php echo $dateTime; ?><br>
                Amount : <?php echo $amount; ?>&nbsp;<?php echo $currency; ?><br>
                Transaction Method : <?php echo $Method; ?>
                <br><br>
                <u>PAYER DETAILS</u><br>
                Name: <?php echo $firstName . ' ' . $lastName; ?><br>
                Paypal Payer Email : <?php echo $payerEmailAddress; ?><br>
                Paypal Payer ID : <?php echo $payerID; ?><br>
                <br><br>
                THANK YOU FOR YOUR DONATION
                <br><br>
            </div>
            <button class="btn btn-warning" id="btnExportToPDF" name="btnExportToPDF" type="button" ><i class="glyphicon glyphicon-download"></i>&nbsp;Download PDF</button>
            <button class="btn btn-warning" id="btnPrint" name="btnPrint" type="button" onclick="return printDiv('receiptDocument');" ><i class="glyphicon glyphicon-print"></i>&nbsp;Print</button>
        </center>
        <?php
    } else {
        echo "Failed to retrieve transaction details";
    }
} else {
    echo "Failed to retrieve transaction details.";
}
?>