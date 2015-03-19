$(document).ready(function() {
    jQuery("#jqgrid").GridUnload();
    $(".jqGridTable").css('display', 'none');
    $('#errorMessage').css('display', 'none');
    $("#mainSearchFilter").val('0');
    $("#btnExcel").css('display', 'none');
    $("#btnPDF").css('display', 'none');
    $("#btnPrint").css('display', 'none');
    $("#numberFilterDiv").css('display', 'none');
    $("#alphanumericFilterDiv").css('display', 'none');
    $("#transactionIDDiv").css('display', 'none');
    $("#itemNameDiv").css('display', 'none');
    $("#amountDiv").css('display', 'none');
    $("#transactionTypeDiv").css('display', 'none');
    $("#methodDiv").css('display', 'none');
    $("#datePickersDiv").css('display', 'none');
    $("#mainSearchFilter").on('change', function() {
        var mainSearchFilter = $("#mainSearchFilter").val();
        jQuery("#jqgrid").GridUnload();
        $(".jqGridTable").css('display', 'none');
        $('#errorMessage').css('display', 'none');
        $("#numberFilter").val('0');
        $("#alphanumericFilter").val('0');
        $("#transactionID").val('');
        $("#itemName").val('');
        $("#amount").val('');
        $("#transactionType").val('0');
        $("#method").val('0');
        $("#startDate").val('');
        $("#endDate").val('');
        $("#btnExcel").css('display', 'none');
        $("#btnPDF").css('display', 'none');
        $("#btnPrint").css('display', 'none');
        $("#jqGridTable").css('display', 'none');
        $("#numberFilterDiv").css('display', 'none');
        $("#alphanumericFilterDiv").css('display', 'none');
        $("#transactionIDDiv").css('display', 'none');
        $("#itemNameDiv").css('display', 'none');
        $("#amountDiv").css('display', 'none');
        $("#transactionTypeDiv").css('display', 'none');
        $("#methodDiv").css('display', 'none');
        $("#datePickersDiv").css('display', 'none');
        if (mainSearchFilter == 1 || mainSearchFilter == '1') {
            $("#alphanumeric").val('');
            $("#alphanumericFilterDiv").css('display', 'block');
            $("#transactionID").val('');
            $("#transactionIDDiv").css('display', 'block');
        } else if (mainSearchFilter == 2 || mainSearchFilter == '2') {
            $("#alphanumeric").val('');
            $("#alphanumericFilterDiv").css('display', 'block');
            $("#itemName").val('');
            $("#itemNameDiv").css('display', 'block');
        } else if (mainSearchFilter == 3 || mainSearchFilter == '3') {
            $("#numberFilter").val('0');
            $("#numberFilterDiv").css('display', 'block');
            $("#amount").val('');
            $("#amountDiv").css('display', 'block');
        } else if (mainSearchFilter == 4 || mainSearchFilter == '4') {
            $("#transactionType").val('0');
            $("#transactionTypeDiv").css('display', 'block');
        } else if (mainSearchFilter == 5 || mainSearchFilter == '5') {
            $("#method").val('0');
            $("#methodDiv").css('display', 'block');
        } else if (mainSearchFilter == 6 || mainSearchFilter == '6') {
            $("#startDate").val('');
            $("#endDate").val('');
            $("#datePickersDiv").css('display', 'block');
        }
    });

    $("#btnSearch").on('click', function() {
        $('#errorMessage').css('display', 'none');
        var mainSearchFilter = $("#mainSearchFilter").val();
        if (mainSearchFilter == 0 || mainSearchFilter == '0') {
            searchFunction = 'all';
            url = 'searchAllTransactions';
        } else if (mainSearchFilter == 1 || mainSearchFilter == '1') {
            searchFunction = 'transactionID';
            url = 'searchTransactionsByTransactionID';
        } else if (mainSearchFilter == 2 || mainSearchFilter == '2') {
            searchFunction = 'itemName';
            url = 'searchTransactionsByItemName';
        } else if (mainSearchFilter == 3 || mainSearchFilter == '3') {
            searchFunction = 'amount';
            url = 'searchTransactionsByAmount';
        } else if (mainSearchFilter == 4 || mainSearchFilter == '4') {
            searchFunction = 'transactionTpe';
            url = 'searchTransactionsByTransactionType';
        } else if (mainSearchFilter == 5 || mainSearchFilter == '5') {
            searchFunction = 'method';
            url = 'searchTransactionsByMethod';
        } else if (mainSearchFilter == 6 || mainSearchFilter == '6') {
            searchFunction = 'dateRange';
            url = 'searchTransactionsByDateRange';
        }

        return searchTransactions(searchFunction, url);
    });

    $('#signupForm').formValidation({
        message: 'This value is not valid',
        icon: {
            valid: 'glyphicon glyphicon-ok',
            invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        },
        fields: {
            firstName: {
                validators: {
                    notEmpty: {
                        message: 'First name is required'
                    },
                    stringLength: {
                        min: 1,
                        max: 30,
                        message: 'First Name must be less than or equal to 30 characters long'
                    },
                    regexp: {
                        regexp: /^[a-zA-Z '-]+$/,
                        message: 'First name accepts letters, space, apostrophe and dash only'
                    }
                },
            },
            lastName: {
                validators: {
                    notEmpty: {
                        message: 'Last name is required'
                    },
                    stringLength: {
                        min: 1,
                        max: 30,
                        message: 'Last Name must be less than or equal to 30 characters long'
                    },
                    regexp: {
                        regexp: /^[a-zA-Z '-]+$/,
                        message: 'Last name accepts letters, space, apostrophe and dash only'
                    }
                },
            },
            sex: {
                validators: {
                    notEmpty: {
                        message: 'Sex is required'
                    }
                }
            },
            country: {
                validators: {
                    notEmpty: {
                        message: 'Country is required'
                    },
                }
            },
            postCode: {
                validators: {
                    notEmpty: {
                        message: 'Post Code is required'
                    },
                }
            },
            username: {
                message: 'The username is not valid',
                validators: {
                    notEmpty: {
                        message: 'Username is required'
                    },
                    stringLength: {
                        min: 8,
                        max: 20,
                        message: 'Username must be equal or more than 8 and less than or equal to 20 characters long'
                    },
                    regexp: {
                        regexp: /^[a-zA-Z0-9_\.]+$/,
                        message: 'Username accepts letters, numbers, period and underscore only'
                    }
                }
            },
            emailAddress: {
                validators: {
                    notEmpty: {
                        message: 'Email address is required'
                    },
                    emailAddress: {
                        message: 'This is not a valid email address'
                    }
                }
            },
            mobileNumber: {
                validators: {
                    notEmpty: {
                        message: 'Mobile number is required'
                    },
                    numeric: {
                        message: 'Mobile number accepts numbers only'
                    },
                    stringLength: {
                        min: 5,
                        max: 20,
                        message: 'This is not a valid mobile number'
                    }
                }
            },
            password: {
                validators: {
                    notEmpty: {
                        message: 'The password is required'
                    },
                    stringLength: {
                        min: 8,
                        max: 20,
                        message: 'Password must be equal or more than 8 and less than or equal to 20 characters long'
                    },
                    different: {
                        field: 'username',
                        message: 'Password cannot be the same as username'
                    }
                }
            },
            confirmPassword: {
                validators: {
                    notEmpty: {
                        message: 'Password must be confirmed'
                    },
                    identical: {
                        field: 'password',
                        message: 'Passwords did not match'
                    },
                    stringLength: {
                        min: 8,
                        max: 20,
                        message: 'Password must be equal or more than 8 and less than or equal to 20 characters long'
                    }
                }
            },
            captcha: {
                validators: {
                    callback: {
                        message: 'Answer has incorrect value',
                        callback: function(value, validator, $field) {
                            var items = $('#captchaOperation').html().split(' '), sum = parseInt(items[0]) + parseInt(items[2]);
                            return value == sum;
                        }
                    },
                    numeric: {
                        message: 'Answer accepts numbers only'
                    }
                }
            },
            agree: {
                validators: {
                    notEmpty: {
                        message: 'You must agree with the Terms and Conditions'
                    }
                }
            }
        }
    });
});

function searchTransactions(searchFunction, url) {

    if (searchFunction == 'all') {
        $(".jqGridTable").css('display', 'block');
        url = 'searchAllTransactions';
        loadGrid(url);
    } else if (searchFunction == 'transactionID') {
        $.ajax({
            url: 'getPhonePrefixByCountryCode',
            type: 'post',
            data: {countryCode: function() {
                    return countryCode;
                }},
            success: function(phoneData) {
                $('.preloader_small').css('display', 'none');
                $('#postCode').html('<select class="form-control" name="postCode" id="postCode">' + countryData + '</select>');
                $('#phonePrefix').html(phoneData);
            },
            error: function(e) {
                alert(e);
            }
        });
    } else if (searchFunction == 'itemName') {
        $.ajax({
            url: 'getPhonePrefixByCountryCode',
            type: 'post',
            data: {countryCode: function() {
                    return countryCode;
                }},
            success: function(phoneData) {
                $('.preloader_small').css('display', 'none');
                $('#postCode').html('<select class="form-control" name="postCode" id="postCode">' + countryData + '</select>');
                $('#phonePrefix').html(phoneData);
            },
            error: function(e) {
                alert(e);
            }
        });
    } else if (searchFunction == 'amount') {
        $.ajax({
            url: 'getPhonePrefixByCountryCode',
            type: 'post',
            data: {countryCode: function() {
                    return countryCode;
                }},
            success: function(phoneData) {
                $('.preloader_small').css('display', 'none');
                $('#postCode').html('<select class="form-control" name="postCode" id="postCode">' + countryData + '</select>');
                $('#phonePrefix').html(phoneData);
            },
            error: function(e) {
                alert(e);
            }
        });
    } else if (searchFunction == 'transactionType') {
        $.ajax({
            url: 'getPhonePrefixByCountryCode',
            type: 'post',
            data: {countryCode: function() {
                    return countryCode;
                }},
            success: function(phoneData) {
                $('.preloader_small').css('display', 'none');
                $('#postCode').html('<select class="form-control" name="postCode" id="postCode">' + countryData + '</select>');
                $('#phonePrefix').html(phoneData);
            },
            error: function(e) {
                alert(e);
            }
        });
    } else if (searchFunction == 'method') {
        $.ajax({
            url: 'getPhonePrefixByCountryCode',
            type: 'post',
            data: {countryCode: function() {
                    return countryCode;
                }},
            success: function(phoneData) {
                $('.preloader_small').css('display', 'none');
                $('#postCode').html('<select class="form-control" name="postCode" id="postCode">' + countryData + '</select>');
                $('#phonePrefix').html(phoneData);
            },
            error: function(e) {
                alert(e);
            }
        });
    } else if (searchFunction == 'dateRange') {
        $.ajax({
            url: 'getPhonePrefixByCountryCode',
            type: 'post',
            data: {countryCode: function() {
                    return countryCode;
                }},
            success: function(phoneData) {
                $('.preloader_small').css('display', 'none');
                $('#postCode').html('<select class="form-control" name="postCode" id="postCode">' + countryData + '</select>');
                $('#phonePrefix').html(phoneData);
            },
            error: function(e) {
                alert(e);
            }
        });
    }
}

function loadGrid(url) {
    jQuery("#jqgrid").jqGrid({
        url: url,
        datatype: "json",
        height: '250',
        colNames: ['ID', 'Item', 'Amount ($)', 'Type', 'Method', 'Datetime'],
        colModel: [
            {name: 'TransactionID', index: 'TransactionID', sortable: true},
            {name: 'ItemName', index: 'ItemName', sortable: true},
            {name: 'Amount', index: 'Amount', align: "right", sortable: true},
            {name: 'TransactionType', index: 'TransactionType', sortable: true},
            {name: 'Method', index: 'Method', sortable: true},
            {name: 'Datetime', index: 'Datetime', sorttype: "date"}],
        rowNum: 10,
        rowList: [10, 25, 50, 100, 500, 1000],
        pager: '#pager_jqgrid',
        sortname: 'id',
        toolbarfilter: false,
        viewrecords: true,
        loadonce: true,
        sortorder: "asc",
        caption: "Transactions",
        multiselect: false,
        autowidth: true,
    });
    jqGridExtended();
}

function jqGridExtended() {

    jQuery("#jqgrid").jqGrid('navGrid', "#pager_jqgrid", {
        edit: false,
        add: false,
        del: true
    });

    jQuery("#jqgrid").jqGrid('inlineNav', "#pager_jqgrid");

    /* Add tooltips */
    jQuery('.navtable .ui-pg-button').tooltip({
        container: 'body'
    });

    // Get Selected ID's
    jQuery("a.get_selected_ids").bind("click", function() {
        s = jQuery("#jqgrid").jqGrid('getGridParam', 'selarrrow');
        alert(s);
    });

    // Select/Unselect specific Row by id
    jQuery("a.select_unselect_row").bind("click", function() {
        jQuery("#jqgrid").jqGrid('setSelection', "13");
    });

    // Select/Unselect specific Row by id
    jQuery("a.delete_row").bind("click", function() {
        var su = jQuery("#jqgrid").jqGrid('delRowData', 1);
        if (su)
            alert("Succes. Write custom code to delete row from server");
        else
            alert("Already deleted or not in list");
    });


    // On Resize
    jQuery(window).resize(function() {

        if (window.afterResize) {
            clearTimeout(window.afterResize);
        }

        window.afterResize = setTimeout(function() {

            /**
             After Resize Code
             .................
             **/

            jQuery("#jqgrid").jqGrid('setGridWidth', jQuery(".ui-jqgrid").parent().width());

        }, 500);

    });

    // ----------------------------------------------------------------------------------------------------

    /**
     @STYLING
     **/
    jQuery(".ui-jqgrid").removeClass("ui-widget ui-widget-content");
    jQuery(".ui-jqgrid-view").children().removeClass("ui-widget-header ui-state-default");
    jQuery(".ui-jqgrid-labels, .ui-search-toolbar").children().removeClass("ui-state-default ui-th-column ui-th-ltr");
    jQuery(".ui-jqgrid-pager").removeClass("ui-state-default");
    jQuery(".ui-jqgrid").removeClass("ui-widget-content");

    jQuery(".ui-jqgrid-htable").addClass("table table-bordered table-hover");
    jQuery(".ui-pg-div").removeClass().addClass("btn btn-sm btn-primary");
    jQuery(".ui-icon.ui-icon-plus").removeClass().addClass("fa fa-plus");
    jQuery(".ui-icon.ui-icon-pencil").removeClass().addClass("fa fa-pencil");
    jQuery(".ui-icon.ui-icon-trash").removeClass().addClass("fa fa-trash-o");
    jQuery(".ui-icon.ui-icon-search").removeClass().addClass("fa fa-search");
    jQuery(".ui-icon.ui-icon-refresh").removeClass().addClass("fa fa-refresh");
    jQuery(".ui-icon.ui-icon-disk").removeClass().addClass("fa fa-save").parent(".btn-primary").removeClass("btn-primary").addClass("btn-success");
    jQuery(".ui-icon.ui-icon-cancel").removeClass().addClass("fa fa-times").parent(".btn-primary").removeClass("btn-primary").addClass("btn-danger");

    jQuery(".ui-icon.ui-icon-seek-prev").wrap("");
    jQuery(".ui-icon.ui-icon-seek-prev").removeClass().addClass("fa fa-backward");

    jQuery(".ui-icon.ui-icon-seek-first").wrap("");
    jQuery(".ui-icon.ui-icon-seek-first").removeClass().addClass("fa fa-fast-backward");

    jQuery(".ui-icon.ui-icon-seek-next").wrap("");
    jQuery(".ui-icon.ui-icon-seek-next").removeClass().addClass("fa fa-forward");

    jQuery(".ui-icon.ui-icon-seek-end").wrap("");
    jQuery(".ui-icon.ui-icon-seek-end").removeClass().addClass("fa fa-fast-forward");
}

//enable datepicker
function pickDate(cellvalue, options, cell) {
    setTimeout(function() {
        jQuery(cell).find('input[type=text]')
                .datepicker({format: 'yyyy-mm-dd', autoclose: true});
    }, 0);
}