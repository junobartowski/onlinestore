<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/signup_validation.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/tinymce/js/tinymce/tinymce.min.js"></script>
<!-- Generic page styles -->
<link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/js/fileuploader/css/style.css">
<!-- CSS to style the file input field as button and adjust the Bootstrap progress bars -->
<link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/js/fileuploader/css/jquery.fileupload.css">
<link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/js/fileuploader/css/jquery.fileupload-ui.css">
<!-- CSS adjustments for browsers with JavaScript disabled -->
<noscript><link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/js/fileuploader/css/jquery.fileupload-noscript.css"></noscript>
<noscript><link rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/js/fileuploader/css/jquery.fileupload-ui-noscript.css"></noscript>
<!-- The template to display files available for upload -->
<script id="template-upload" type="text/x-tmpl">
    {% for (var i=0, file; file=o.files[i]; i++) { %}
    <tr class="template-upload fade">
    <td>
    <span class="preview"></span>
    </td>
    <td style="max-width: 220px;">
    <p class="name" style="word-wrap: break-word;">{%=file.name%}</p>
    <strong class="error text-danger"></strong>
    </td>
    <td>
    <p class="size">Processing...</p>
    <div class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0"><div class="progress-bar progress-bar-success" style="width:0%;"></div></div>
    </td>
    <td>
    {% if (!i && !o.options.autoUpload) { %}
    <button class="btn btn-primary start" disabled>
    <i class="glyphicon glyphicon-upload"></i>
    <span>Start</span>
    </button>
    {% } %}
    {% if (!i) { %}
    <button class="btn btn-warning cancel">
    <i class="glyphicon glyphicon-ban-circle"></i>
    <span>Cancel</span>
    </button>
    {% } %}
    </td>
    </tr>
    {% } %}
</script>
<!-- The template to display files available for download -->
<script id="template-download" type="text/x-tmpl">
    {% for (var i=0, file; file=o.files[i]; i++) { %}
    <tr class="template-download fade">
    <td>
    <span class="preview">
    {% if (file.thumbnailUrl) { %}
    <a href="{%=file.url%}" title="{%=file.name%}" download="{%=file.name%}" data-gallery><img src="{%=file.thumbnailUrl%}" style="max-width: 128px;"></a>
    {% } %}
    </span>
    </td>
    <td style="max-width: 220px;">
    <p class="name" style="word-wrap: break-word;">
    {% if (file.url) { %}
    <a href="{%=file.url%}" title="{%=file.name%}" download="{%=file.name%}" {%=file.thumbnailUrl?'data-gallery':''%}>{%=file.name%}</a>
    {% } else { %}
    <span>{%=file.name%}</span>
    {% } %}
    </p>
    {% if (file.error) { %}
    <div><span class="label label-danger">Error</span> {%=file.error%}</div>
    {% } %}
    </td>
    <td>
    <span class="size">{%=o.formatFileSize(file.size)%}</span>
    </td>
    <td>
    {% if (file.deleteUrl) { %}
    <div class="radio">
    <label>
    <input type="radio" name="itemProfilePhoto" value="{%=file.name%}" /> Make Profile
    </label>
    </div>
    {% } else { %}
    <button class="btn btn-warning cancel">
    <i class="glyphicon glyphicon-ban-circle"></i>
    <span>Cancel</span>
    </button>
    {% } %}
    </td>
    </tr>
    {% } %}
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
                <div class="col-sm-12">
                    <br>
                    <br>                      
                    <form id="fileupload" class="form-horizontal" action="sellItemFinish" method="POST" enctype="multipart/form-data">
                        <!-- Redirect browsers with JavaScript disabled to the origin page -->
                        <noscript><input type="hidden" name="redirect" value="<?php echo Yii::app()->params['defaultURLPrefix']; ?>user/saveAndNextForm"></noscript>
                        <div class="col-sm-12 col-sm-offset-2">
                            <!-- The fileupload-buttonbar contains buttons to add/delete files and start/cancel the upload -->
                            <div class="row fileupload-buttonbar">
                                <div class="col-lg-10">
                                    <!-- The fileinput-button span is used to style the file input field as button -->
                                    <span class="btn btn-success fileinput-button">
                                        <i class="glyphicon glyphicon-plus"></i>
                                        <span>Add files...</span>
                                        <input type="file" name="files[]" multiple>
                                    </span>
                                    <button type="submit" class="btn btn-primary start">
                                        <i class="glyphicon glyphicon-upload"></i>
                                        <span>Start upload</span>
                                    </button>
                                    <button type="reset" class="btn btn-warning cancel">
                                        <i class="glyphicon glyphicon-ban-circle"></i>
                                        <span>Cancel upload</span>
                                    </button>
                                    <input type="checkbox" class="toggle">
                                    <button type="submit" class="btn btn-warning" id="btnSellFinish" name="btnSellFinish" type="submit" value="Finish">
                                        <i class="glyphicon glyphicon-ok"></i>
                                        <span>Finish</span>
                                    </button>
                                    <!-- The global file processing state -->
                                    <span class="fileupload-process"></span>
                                </div>
                                <!-- The global progress state -->
                                <div class="col-lg-4 fileupload-progress fade">
                                    <!-- The global progress bar -->
                                    <div class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100">
                                        <div class="progress-bar progress-bar-success" style="width:0%;"></div>
                                    </div>
                                    <!-- The extended global progress state -->
                                    <div class="progress-extended">&nbsp;</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-8 col-sm-offset-2">
                            <!-- The table listing the files available for upload/download -->
                            <table role="presentation" class="table table-striped">
                                <tbody class="files">
                                <div>
                                    Select files or drop image here
                                </div>
                                </tbody>
                            </table>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- The jQuery UI widget factory, can be omitted if jQuery UI is already included -->
<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/fileuploader/js/vendor/jquery.ui.widget.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/fileuploader/js/canvas-to-blob.min.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/fileuploader/js/load-image.all.min.js"></script>
<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/fileuploader/js/tmpl.min.js"></script>
<!-- The Iframe Transport is required for browsers without support for XHR file uploads -->
<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/fileuploader/js/jquery.iframe-transport.js"></script>
<!-- The basic File Upload plugin -->
<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/fileuploader/js/jquery.fileupload.js"></script>
<!-- The File Upload processing plugin -->
<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/fileuploader/js/jquery.fileupload-process.js"></script>
<!-- The File Upload image preview & resize plugin -->
<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/fileuploader/js/jquery.fileupload-image.js"></script>
<!-- The File Upload audio preview plugin -->
<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/fileuploader/js/jquery.fileupload-audio.js"></script>
<!-- The File Upload video preview plugin -->
<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/fileuploader/js/jquery.fileupload-video.js"></script>
<!-- The File Upload validation plugin -->
<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/fileuploader/js/jquery.fileupload-validate.js"></script>
<!-- The File Upload user interface plugin -->
<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/fileuploader/js/jquery.fileupload-ui.js"></script>
<!-- The main application script -->
<script src="<?php echo Yii::app()->request->baseUrl; ?>/js/fileuploader/js/main.js"></script>
<!-- The XDomainRequest Transport is included for cross-domain file deletion for IE 8 and IE 9 -->
<!--[if (gte IE 8)&(lt IE 10)]>
<script src="js/cors/jquery.xdr-transport.js"></script>
<![endif]-->