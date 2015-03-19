$(document).ready(function() {
    $("#country").on('change', function(event) {
        $('.preloader_small').css('display', 'block');
        $('#postCode').val('');
        $('#phonePrefix').html('');
        var countryCode = $('#country').val();
        $.ajax({
            url: 'getPostCodeByCountryCode',
            type: 'post',
            data: {countryCode: function() {
                    return countryCode;
                }},
            success: function(countryData) {
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
            },
            error: function(e) {
                $('.preloader_small').css('display', 'none');
                alert(e);
            }
        });
    });

    $("#termsAndConditionsLink").on('click', function(event) {
        $("#termsAndConditionsModal").modal('show');
    });

    // Generate a simple captcha
    function randomNumber(min, max) {
        return Math.floor(Math.random() * (max - min + 1) + min);
    }
    ;
    $('#captchaOperation').html([randomNumber(1, 10), '+', randomNumber(1, 5), '='].join(' '));

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