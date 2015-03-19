<?php if (isset($item)) { ?>
    <form id="form_buy" target="_top" name="_xclick" method="post" action="buyItemConfirmed">
        <img class="cbp-vm-image" src="<?php echo Yii::app()->request->baseUrl; ?>/images/uploads/items/<?php echo $item['ID']; ?>/thumbnail/<?php echo $item['ProfilePhotoFilename']; ?>">
        <br>
        <h3 class="cbp-vm-title"><?php echo $item['Name']; ?></h3>
        <div id="price_break"></div>
        <div class="cbp-vm-price">$<?php echo $item['Price']; ?></div>
        <div class="cbp-vm-details">
            <?php
            if ($item['ItemCondition'] == 1) {
                $condition = "Brand New";
            } else if ($item['ItemCondition'] == 2) {
                $condition = "Used";
            } else {
                $condition = "N/A";
            }
            echo "Condition : " . $condition;
            ?>
            <br>
            <?php
            echo "Beneficiary : ";
            ?>
            <i class="glyphicon glyphicon-info-sign"></i>
            <br>
            <?php
            echo "Donation : $" . $item['Donation'];
            ?>
            <i class="glyphicon glyphicon-info-sign"></i>
            <br>
        </div>
        <br>
        <?php echo $item['Description']; ?>
        <input type="hidden" name="itemID" value="<?php echo $item['ID']; ?>">
        <button type="submit" class="btn btn-default btn-warning"><i class="glyphicon glyphicon-shopping-cart"></i>&nbsp;Confirm Buy</button>
        <button type="button" class="btn btn-default btn-warning" data-dismiss="modal"><i class="glyphicon glyphicon-remove"></i>&nbsp;Cancel</button>
    </form>
    <br>
<?php } else { ?>
    Failed to retrieve item information
<?php } ?>