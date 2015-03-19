<?php
if (isset(Yii::app()->session['accountID'])) {
    $accountID = Yii::app()->session['accountID'];
} else {
    $accountID = 0;
}
?>
<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/js/gridswitcher/css/default.css" />
<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/js/gridswitcher/css/component.css" />
<link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/css/font-awesome-4.2/css/font-awesome.css" />
<link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/css/font-awesome-4.2/css/bootstrap-social.css" />
<br>
<br>
<br>
<script>
    function viewItem(itemID) {
        $.ajax({
            url: 'viewItem',
            type: 'post',
            data: {itemID: function() {
                    return itemID;
                }},
            success: function(response) {
                $('#renderPartialSingleItem').html(response);
                $("#viewItemModal").modal('show');
            },
            error: function(e) {
                $('#renderPartialSingleItem').html(e);
                $("#viewItemModal").modal('show');
            }
        });
    }

    function buyItem(itemID) {
        $.ajax({
            url: 'buyItem',
            type: 'post',
            data: {itemID: function() {
                    return itemID;
                }},
            success: function(response) {
                $('#renderPartialBuyItem').html(response);
                $("#buyItemModal").modal('show');
            },
            error: function(e) {
                $('#renderPartialBuyItem').html(e);
                $("#buyItemModal").modal('show');
            }
        });
    }
    
    function bidItem(itemID) {
        $.ajax({
            url: 'bidItem',
            type: 'post',
            data: {itemID: function() {
                    return itemID;
                }},
            success: function(response) {
                $('#renderPartialBidItem').html(response);
                $("#bidItemModal").modal('show');
            },
            error: function(e) {
                $('#renderPartialBuyItem').html(e);
                $("#bidItemModal").modal('show');
            }
        });
    }
</script>
<div class="switcherContainer">
    <div class="switcherMain">
        <div id="cbp-vm" class="cbp-vm-switcher cbp-vm-view-grid">
            <div class="cbp-vm-options">
                <span style="float: left; font-size: 2em;" data-view="cbp-vm-view-grid">Items</span>
                <a href="#" class="cbp-vm-icon cbp-vm-grid cbp-vm-selected" data-view="cbp-vm-view-grid">Grid View</a>
                <a href="#" class="cbp-vm-icon cbp-vm-list" data-view="cbp-vm-view-list">List View</a>
            </div>
            <ul>
                <?php
                foreach ($items as $item) {
                    ?>
                    <li>
                        <span class="thumbnailAccountProfileForItem">
                            <?php if ((!is_null($item['AccountProfilePhoto']) && $item['AccountProfilePhoto']) != "") { ?>
                                <img class="userProfilePhotoThumbnail" src="<?php echo Yii::app()->request->baseUrl; ?>/images/uploads/users/<?php echo $item['AccountProfilePhoto']; ?>">
                            <?php } else { ?>
                                <?php if ($item['Sex'] == 1 || $item['Sex'] == '1') { ?>
                                    <img class="userProfilePhotoThumbnail" src="<?php echo Yii::app()->request->baseUrl; ?>/images/uploads/users/ProfilePhotoDefaultMale.png">
                                <?php } else { ?>
                                    <img class="userProfilePhotoThumbnail" src="<?php echo Yii::app()->request->baseUrl; ?>/images/uploads/users/ProfilePhotoDefaultFemale.png">
                                <?php } ?>
                            <?php } ?>
                            <?php echo $item['FirstName'] . ' ' . $item['LastName']; ?>, <?php echo $item['CountryName'] ?>
                        </span>
                        <br>
                        <img class="cbp-vm-image" src="<?php echo Yii::app()->request->baseUrl; ?>/images/uploads/items/<?php echo $item['ID']; ?>/thumbnail/<?php echo $item['ItemProfilePhoto']; ?>">
                        <h3 class="cbp-vm-title"><?php echo $item['Name']; ?></h3>
                        <div id="price_break"></div>
                        <div class="cbp-vm-price">
                            <?php
                            if ($item['ForType'] == 1 || $item['ForType'] == '1') {
                                echo "$".$item['Price']."<br><div style='font-size: 10px;'>&nbsp;</div>";
                            } else {
                                echo "$".$item['LastPrice']."<br><div style='font-size: 10px;'>Last Bid</div>";
                            }
                            ?>
                        </div>
                        <div class="cbp-vm-details">
                            <?php if ($item['Status'] == CommonForm::ITEM_STATUS_APPROVED_BY_CHARITY) { ?>
                                <span style="color: #66ff66;">Approved <i class="glyphicon glyphicon-ok"></i></span>
                                <br>
                            <?php } ?>
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
                        </div>
                        <?php $createdByID = (int) $item['CreatedByID']; ?>
                        <?php if ($accountID == $createdByID) { ?>
                            <?php if ($item['Status'] == CommonForm::ITEM_STATUS_ACTIVE) { ?>
                                <button class="btn btn-warning" id="btnUpdateItem" name="btnUpdateItem" type="button" onclick="return viewItem($(this).val());" value="<?php echo $item['ID']; ?>"><i class="glyphicon glyphicon-pencil"></i>&nbsp;Update</button>
                            <?php } ?>
                            <button class="btn btn-warning" id="btnDeleteItem" name="btnDeleteItem" type="button" onclick="return buyItem($(this).val());" value="<?php echo $item['ID']; ?>"><i class="glyphicon glyphicon-trash"></i>&nbsp;Remove</button>
                        <?php } else { ?>
                            <button class="btn btn-warning" id="btnViewItem" name="btnViewItem" type="button" onclick="return viewItem($(this).val());" value="<?php echo $item['ID']; ?>">&nbsp;<i class="glyphicon glyphicon-eye-open"></i>&nbsp;View&nbsp;</button>
                            <?php if ($item['ForType'] == 1 || $item['ForType'] == '1') { ?>
                                <button class="btn btn-warning" id="btnBuyItem" name="btnBuyItem" type="button" onclick="return buyItem($(this).val());" value="<?php echo $item['ID']; ?>"><i class="glyphicon glyphicon-shopping-cart"></i>&nbsp;Buy Now</button>
                            <?php } else if ($item['ForType'] == 2 || $item['ForType'] == '2') { ?>
                                <button class="btn btn-warning" id="btnBidItem" name="btnBidItem" type="button" onclick="return bidItem($(this).val());" value="<?php echo $item['ID']; ?>"><i class="glyphicon glyphicon-usd"></i>&nbsp;Bid</button>
                            <?php } else { ?>

                            <?php } ?>
                        <?php } ?>
                        <div class="socialIcons">
                            <?php $urlForSocial = Yii::app()->params['defaultURLPrefix'] . 'item/single?itemID=' . $item['ID']; ?>
                            <?php $facebookLink = "http://www.facebook.com/sharer.php?u=" . $urlForSocial; ?>
                            <a onclick="window.open('<?php echo $facebookLink; ?>', 'newwindow', 'width=500, height=250');" class="btn btn-social btn-facebook">
                                <i class="fa fa-facebook"></i>&nbsp;<i class="fa fa-share"></i>
                            </a>
                            <?php $twitterLink = "http://twitter.com/home?status=" . $urlForSocial; ?>
                            <a onclick="window.open('<?php echo $twitterLink; ?>', 'newwindow', 'width=500, height=250');"  class="btn btn-social btn-twitter">
                                <i class="fa fa-twitter"></i>&nbsp;<i class="glyphicon glyphicon-retweet"></i>
                            </a>
                        </div>
                    </li>
                    <?php
                }
                ?>
            </ul>
        </div>
    </div><!-- /main -->
</div><!-- /container -->
<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/gridswitcher/js/classie.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/gridswitcher/js/cbpViewModeSwitch.js"></script>
<div id="viewItemModal" class="modal fade" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">&nbsp;</h4>
            </div>
            <div class="modal-body">
                <div id="renderPartialSingleItem"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default btn-warning" data-dismiss="modal"><i class="glyphicon glyphicon-remove"></i>&nbsp;Close</button>
            </div>
        </div>
    </div>
</div>
<div id="buyItemModal" class="modal fade" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">&nbsp;</h4>
            </div>
            <div class="modal-body">
                <div id="renderPartialBuyItem"></div>
            </div>
        </div>
    </div>
</div>
<div id="bidItemModal" class="modal fade" data-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">&nbsp;</h4>
            </div>
            <div class="modal-body">
                <div id="renderPartialBidItem"></div>
            </div>
        </div>
    </div>
</div>