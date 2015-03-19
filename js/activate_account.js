$(document).ready(function() {
    $('#activateAccountPostForm').formValidation({
        message: 'This value is not valid',
        icon: {
            valid: 'glyphicon glyphicon-ok',
            invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        },
        fields: {
            activationCode: {
                validators: {
                    notEmpty: {
                        message: 'Please enter your activation code.'
                    }
                },
            }
        }
    });
});