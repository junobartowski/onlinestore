$(document).ready(function() {
    $('#directDonationForm').formValidation({
        message: 'This value is not valid',
        icon: {
            valid: 'glyphicon glyphicon-ok',
            invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        },
        fields: {
            donationAmount: {
                validators: {
                    notEmpty: {
                        message: 'Amount is required'
                    },
                    numeric: {
                        message: 'This is not a money format'
                    },
                    callback: {
                        message: 'Amount is invalid',
                        callback: function(value, validator, $field) {
                            return value <= 10000000;
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
            }
        }
    });
});