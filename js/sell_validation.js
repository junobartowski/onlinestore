$(document).ready(function() {
    $('#sellForm').formValidation({
        message: 'This value is not valid',
        icon: {
            valid: 'glyphicon glyphicon-ok',
            invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        },
        fields: {
            itemName: {
                validators: {
                    notEmpty: {
                        message: 'Please enter Item Name'
                    },
                    stringLength: {
                        min: 5,
                        max: 50,
                        message: 'Item name must be equal or more than 5 and less than or equal to 50 characters long'
                    },
                },
            },
            category: {
                validators: {
                    notEmpty: {
                        message: 'Please select a category'
                    }
                },
            },
            itemCondition: {
                validators: {
                    notEmpty: {
                        message: 'Please select a condition'
                    }
                },
            },
            price: {
                validators: {
                    notEmpty: {
                        message: 'Price is required'
                    },
                    numeric: {
                        message: 'This is not a money format'
                    },
                    callback: {
                        message: 'Price is invalid',
                        callback: function(value, validator, $field) {
                            return value <= 10000000;
                        }
                    }
                }
            },
            shippingFee: {
                validators: {
                    numeric: {
                        message: 'This is not a money format'
                    },
                    callback: {
                        message: 'Shipping Fee is invalid',
                        callback: function(value, validator, $field) {
                            return value <= 10000000;
                        }
                    }
                }
            },
            donation: {
                validators: {
                    numeric: {
                        message: 'Donation accepts numbers only'
                    },
                    notEmpty: {
                        message: 'Donation percentage is required'
                    },
                    callback: {
                        message: 'Donation must be equal or less than the item price',
                        callback: function(value, validator, $field) {
                            var val = $('#price').val();
                            return (+value) <= (+val);
                        }
                    }
                }
            },
            charity: {
                validators: {
                    notEmpty: {
                        message: 'Charity is required.'
                    },
                }
            },
            forType: {
                validators: {
                    notEmpty: {
                        message: 'Type is required.'
                    },
                }
            }
        }
    });

    $("#price").on('keyup', function(event) {
        var txtPrice = $("#price").val();
        var txtShippingFee = $("#shippingFee").val();
        var txtDonationPercentage = $("#donation").val();
        if (txtPrice !== "") {
            price = txtPrice;
        } else {
            price = 0;
        }
        if (txtShippingFee !== "") {
            shippingFee = txtShippingFee;
        } else {
            shippingFee = 0;
        }
        if (txtDonationPercentage !== "") {
            donation = txtDonationPercentage;
        } else {
            donation = 0;
        }
        totalAmountToBuyer = price + (+shippingFee);
        totalAmountToDonate = donation;
        totalAmountSellerWillEarn = price - totalAmountToDonate;
        if(totalAmountToBuyer !== 'NaN') {
            $("#totalAmountToBuyer").val(totalAmountToBuyer);
        } else {
            $("#totalAmountToBuyer").val('0.00');
        }
        if(donation !== 'NaN') {
            $("#totalAmountToDonate").val(donation);
        } else {
            $("#totalAmountToDonate").val('0.00');
        }
        if(totalAmountSellerWillEarn !== 'NaN') {
            if(donation <= price) {
                $("#totalAmountSellerWillEarn").val(totalAmountSellerWillEarn);
            } else {
                $("#totalAmountSellerWillEarn").val(price);
            }
        } else {
            $("#totalAmountToDonate").val('0.00');
        }
    });

    $("#price").on('keydown', function(event) {
        evt = (evt) ? evt : event;
        var charCode = (evt.charCode) ? evt.charCode : ((evt.keyCode) ? evt.keyCode : ((evt.which) ? evt.which : 0));
        if ((charCode >= 48) && (charCode <= 57)) {
            return true;
        } else {
            return false;
        }
    });
    
    $("#shippingFee").on('keyup', function(event) {
        var txtPrice = $("#price").val();
        var txtShippingFee = $("#shippingFee").val();
        var txtDonationPercentage = $("#donation").val();
        if (txtPrice !== "") {
            price = txtPrice;
        } else {
            price = 0;
        }
        if (txtShippingFee !== "") {
            shippingFee = txtShippingFee;
        } else {
            shippingFee = 0;
        }
        if (txtDonationPercentage !== "") {
            donation = txtDonationPercentage;
        } else {
            donation = 0;
        }
        totalAmountToBuyer = price + (+shippingFee);
        totalAmountToDonate = donation;
        totalAmountSellerWillEarn = price - totalAmountToDonate;
        if(totalAmountToBuyer !== 'NaN') {
            $("#totalAmountToBuyer").val(totalAmountToBuyer);
        } else {
            $("#totalAmountToBuyer").val('0.00');
        }
        if(donation !== 'NaN') {
            $("#totalAmountToDonate").val(donation);
        } else {
            $("#totalAmountToDonate").val('0.00');
        }
        if(totalAmountSellerWillEarn !== 'NaN') {
            if(donation <= price) {
                $("#totalAmountSellerWillEarn").val(totalAmountSellerWillEarn);
            } else {
                $("#totalAmountSellerWillEarn").val(price);
            }
        } else {
            $("#totalAmountToDonate").val('0.00');
        }
    });

    $("#shippingFee").on('keydown', function(event) {
        evt = (evt) ? evt : event;
        var charCode = (evt.charCode) ? evt.charCode : ((evt.keyCode) ? evt.keyCode : ((evt.which) ? evt.which : 0));
        if ((charCode >= 48) && (charCode <= 57)) {
            return true;
        } else {
            return false;
        }
    });
    
    $("#donation").on('keyup', function(event) {
        var txtPrice = $("#price").val();
        var txtShippingFee = $("#shippingFee").val();
        var txtDonationPercentage = $("#donation").val();
        if (txtPrice !== "") {
            price = txtPrice;
        } else {
            price = 0;
        }
        if (txtShippingFee !== "") {
            shippingFee = txtShippingFee;
        } else {
            shippingFee = 0;
        }
        if (txtDonationPercentage !== "") {
            donation = txtDonationPercentage;
        } else {
            donation = 0;
        }
        totalAmountToBuyer = price + (+shippingFee);
        totalAmountToDonate = donation;
        totalAmountSellerWillEarn = price - totalAmountToDonate;
        if(totalAmountToBuyer !== 'NaN') {
            $("#totalAmountToBuyer").val(totalAmountToBuyer);
        } else {
            $("#totalAmountToBuyer").val('0.00');
        }
        if(donation !== 'NaN') {
            $("#totalAmountToDonate").val(donation);
        } else {
            $("#totalAmountToDonate").val('0.00');
        }
        if(totalAmountSellerWillEarn !== 'NaN') {
            if(donation <= price) {
                $("#totalAmountSellerWillEarn").val(totalAmountSellerWillEarn);
            } else {
                $("#totalAmountSellerWillEarn").val(price);
            }
        } else {
            $("#totalAmountToDonate").val('0.00');
        }
    });

    $("#donation").on('keydown', function(event) {
        evt = (evt) ? evt : event;
        var charCode = (evt.charCode) ? evt.charCode : ((evt.keyCode) ? evt.keyCode : ((evt.which) ? evt.which : 0));
        if ((charCode >= 48) && (charCode <= 57)) {
            return true;
        } else {
            return false;
        }
    });
});