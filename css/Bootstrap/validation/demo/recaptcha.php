<?php
// Register the public and private keys at https://www.google.com/recaptcha/admin
define('PUBLIC_KEY',  '6LfiX_0SAAAAAFGN6mRslurxcH4V1zXAfXIp3nv_');
define('PRIVATE_KEY', '6LfiX_0SAAAAAH8Zu-YE4y3psgc7cZ84qQ7nZQic');

// https://developers.google.com/recaptcha/docs/php
require_once('recaptchalib.php');

// Verify the captcha
// https://developers.google.com/recaptcha/docs/verify
$resp = recaptcha_check_answer(PRIVATE_KEY,
                                $_SERVER['REMOTE_ADDR'],
                                $_POST['recaptcha_challenge_field'],
                                $_POST['recaptcha_response_field']
                            );

echo json_encode(array(
    'valid'   => $resp->is_valid,
    'message' => $resp->is_valid ? null : 'Hey, the captcha is wrong!',       // $resp->error,
));

