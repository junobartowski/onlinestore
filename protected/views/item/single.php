<link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/css/font-awesome-4.2/css/font-awesome.css"/>
<link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/css/font-awesome-4.2/css/bootstrap-social.css"/>
<?php if (isset($item)) { ?>
    <head>
        <meta charset="utf-8"/>
        <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
        <meta name="viewport" content="width=device-width, initial-scale=1"/>
        <!-- Open Graph Protocol -->
        <meta property="og:type" content="website"/>
        <meta property="og:title" content="<?php echo $item['Name']; ?>"/>
        <meta property="og:description" content="<?php if ($item['ForType'] == 1 || $item['ForType'] == '1') {
        echo "Buy Now!";
    } else {
        echo "Bid Now!";
    } ?>" />
        <meta property="og:url" content="<?php echo Yii::app()->params['defaultURLPrefix'] . 'item/single?itemID=' . $item['ID']; ?>"/>
        <meta property="og:image" content="<?php echo Yii::app()->request->baseUrl . '/images/uploads/items/thumbnail/' . $item['ProfilePhotoFilename']; ?>"/>
        <meta property="og:site_name" content="<?php echo Yii::app()->params['projectName']; ?>"/>
    </head>
    <div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
        <!-- Indicators -->
        <ol class="carousel-indicators">
            <li data-target="#carousel-example-generic" data-slide-to="0" class="active"></li>
            <li data-target="#carousel-example-generic" data-slide-to="1"></li>
            <li data-target="#carousel-example-generic" data-slide-to="2"></li>
        </ol>
        <!-- Wrapper for slides -->
        <div class="carousel-inner">
            <?php
            $itemID = $item['ID'];
            $folder = opendir($_SERVER['DOCUMENT_ROOT'] . Yii::app()->request->baseUrl . '/images/uploads/items/' . $itemID . '/');
            $pic_types = array("jpg", "jpeg", "gif", "png");
            $index = array();

            while ($file = readdir($folder)) {
                if (in_array(substr(strtolower($file), strrpos($file, ".") + 1), $pic_types)) {
                    ?>
                        <?php if ($file == $item['ProfilePhotoFilename']) { ?>
                        <div class="item active"></div>
            <?php } else { ?>
                        <div class="item">
                    <?php } ?>
                        <center>
                            <img src="<?php echo Yii::app()->request->baseUrl . '/images/uploads/items/' . $itemID . '/' . $file; ?>" alt="<?php echo $file; ?>" style="max-height: 300px;">
                        </center>
                    </div>
                    <?php
                }
            }
            closedir($folder);
            ?>

            <!-- Controls -->
            <a class="left carousel-control" href="#carousel-example-generic" role="button" data-slide="prev">
                <span class="glyphicon glyphicon-chevron-left"></span>
            </a>
            <a class="right carousel-control" href="#carousel-example-generic" role="button" data-slide="next">
                <span class="glyphicon glyphicon-chevron-right"></span>
            </a>
        </div> <!-- Carousel -->
    </div>
    <br>
    <center>
        <h3 class="cbp-vm-title">
    <?php echo $item['Name']; ?>
    <?php if ($item['Status'] == CommonForm::ITEM_STATUS_APPROVED_BY_CHARITY) { ?>
                <span style="color: #009900;"><i class="glyphicon glyphicon-ok"></i></span>
            <?php } ?>
        </h3>
        <div id="price_break"></div>
        <div class="cbp-vm-price">
            $
            <?php
            if ($item['ForType'] == 1 || $item['ForType'] == '1') {
                echo $item['Price'];
            } else {
                echo $item['LastPrice'];
            }
            ?>
        </div>
        <?php $urlForSocial = Yii::app()->params['defaultURLPrefix'] . 'item/single?itemID=' . $item['ID']; ?>
        <?php $facebookLink = "http://www.facebook.com/sharer.php?u=" . $urlForSocial; ?>
        <a onclick="window.open('<?php echo $facebookLink; ?>', 'newwindow', 'width=500, height=250');" class="btn btn-social btn-facebook">
            <i class="fa fa-share"></i>Share
        </a>
    <?php $twitterLink = "http://twitter.com/home?status=" . $urlForSocial; ?>
        <a onclick="window.open('<?php echo $twitterLink; ?>', 'newwindow', 'width=500, height=250');" class="btn btn-social btn-twitter">
            <i class="glyphicon glyphicon-retweet"></i>Tweet
        </a>
        <br>
        <br>
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
        <br>
        <?php
        if ($item['ForType'] == 1 || $item['ForType'] == '1') {
                echo "Donation : $" . $item['Donation'];
            }
        ?>
        <br>
        <br>
    <?php echo $item['Description']; ?>
    </center>
<?php } else { ?>
    <br>
    <br>
    <center>
        Failed to retrieve item information
    </center>
<?php } ?>