$(document).ready(function() {
    // Generate a simple captcha
    function randomNumber(min, max) {
        return Math.floor(Math.random() * (max - min + 1) + min);
    }
    ;
    $('#captchaOperation').html([randomNumber(1, 50), '+', randomNumber(1, 70), '='].join(' '));

    $('#signinForm').formValidation({
        message: 'This value is not valid',
        icon: {
            valid: 'glyphicon glyphicon-ok',
            invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        },
        fields: {
            usernameOrEmailAddress: {
                validators: {
                    notEmpty: {
                        message: 'Please enter Username or Email Address'
                    },
                    stringLength: {
                        min: 1,
                        max: 255,
                        message: ''
                    },
                },
            },
            password: {
                validators: {
                    notEmpty: {
                        message: 'Password is required'
                    },
                    stringLength: {
                        min: 1,
                        max: 30,
                        message: ''
                    }
                },
            }
        }
    });
});