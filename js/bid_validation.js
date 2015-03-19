$(document).ready(function() {
    $('#ajaxMessageSuccessContainer').css('display', 'none');
    $('#ajaxMessageDangerContainer').css('display', 'none');
    
    $('#signinForm').on('submit', function(event) {
        event.preventDefault();
        var usernameOrEmailAddress = $("#usernameOrEmailAddress").val();
        var password = $("#password").val();
        if (usernameOrEmailAddress !== "") {
            if (password !== "") {
                $('#ajaxMessageDangerContainer').css('display', 'none');
                $('#ajaxMessageSuccessContainer').css('display', 'block');
                $('.ajaxMessage').html('<span>Signing in...</span>');
                $.ajax({
                    type: "POST",
                    url: "signinForm",
                    datatype: 'json',
                    data: {
                        usernameOrEmailAddress: function() {
                            return usernameOrEmailAddress;
                        },
                        password: function() {
                            return password;
                        }
                    },
                    success: function(data) {
                        var response = $.parseJSON(data);
                        
                        if (response.errorCode == 0) {
                            $('#ajaxMessageSuccessContainer').css('display', 'block');
                            $('#ajaxMessageDangerContainer').css('display', 'none');
                            location.reload(true);
                        } else {
                            $('#ajaxMessageSuccessContainer').css('display', 'none');
                            $('#ajaxMessageDangerContainer').css('display', 'block');
                            $('.ajaxMessage').html('<span>' + response.errorMessage + '</span>');
                        }
                    },
                    error: function(error)
                    {
                        $('.ajaxMessageSuccessContainer').css('display', 'none');
                        $('.ajaxMessageDangerContainer').css('display', 'block');
                        $('.ajaxMessage').html('<span>' + error + '</span>');
                    }
                });
            } else {
                return false;
            }
        } else {
            return false;
        }
    });
    
    $('#formBid').on('submit', function(event) {
        event.preventDefault();
        $('#btnCancelBid').css('display', 'none');
        $('#bidItemModal .close').css('display', 'none');
        var itemID = $("#itemID").val();
        if (itemID !== "") {
                $('#ajaxMessageDangerContainer').css('display', 'none');
                $('#ajaxMessageSuccessContainer').css('display', 'block');
                $('.ajaxMessage').html('<span>Connecting...</span>');
                $.ajax({
                    type: "POST",
                    url: "buyItemConfirmed",
                    datatype: 'json',
                    data: {
                        itemID: function() {
                            return itemID;
                        }
                    },
                    success: function(data) {
                        var response = $.parseJSON(data);
                        if (response.errorCode == 0) {
                            $('#ajaxMessageSuccessContainer').css('display', 'block');
                            $('#ajaxMessageDangerContainer').css('display', 'none');
                            $('.ajaxMessage').html('<span>Connected! Payment is now loading...</span>');
                            window.location = response.urlRedirect;
                        } else {
                            $('#ajaxMessageSuccessContainer').css('display', 'none');
                            $('#ajaxMessageDangerContainer').css('display', 'block');
                            $('.ajaxMessage').html('<span>' + response.errorMessage + '</span>');
                        }
                    },
                    error: function(error)
                    {
                        $('.ajaxMessageSuccessContainer').css('display', 'none');
                        $('.ajaxMessageDangerContainer').css('display', 'block');
                        $('.ajaxMessage').html('<span>' + error + '</span>');
                    }
                });
        } else {
            return false;
        }
    });
});