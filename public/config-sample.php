<?php

// Copy and rename your file to config.php in this directory to edit your settings

// General settings
// 1 to enable, 0 to disable
ISQ::$general = array(
	'name' => 'URL shortener', // The name of your URL shortener
	'qr' => 1, // Do you want to display a QR code?
	'clipboard' => 1 // Do you want to enable zeroClipboard? (uses flash)
);

// Menu- The width of ∞² menu allows you to have about 10 links
// Leave empty to disable
ISQ::$links = array(
	'name_1' => 'Tom Slominski',
	'url_1' => 'http://tomslominski.net/',
	'name_2' => 'Get ∞²!',
	'url_2' => 'https://github.com/tomslominski/infinity-squared/',
	'name_3' => '',
	'url_3' => '',
	'name_4' => '',
	'url_4' => '',
	'name_5' => '',
	'url_5' => '',
	'name_6' => '',
	'url_6' => '',
	'name_7' => '',
	'url_7' => '',
	'name_8' => '',
	'url_8' => '',
	'name_9' => '',
	'url_9' => '',
	'name_10' => '',
	'url_10' => ''
);

// Social sharing
// 1 to enable, 0 to disable
ISQ::$social = array(
	'facebook' => 1,
	'twitter' => 1,
	'plus' => 1,
	'linkedin' => 1
);

// reCAPTCHA API keys
// Get yourls from https://www.google.com/recaptcha/admin
ISQ::$recaptcha = array(
	'public' => '',
	'private' => ''
);
?>
