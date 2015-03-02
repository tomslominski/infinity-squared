<?php

// Copy and rename your file to config.php in the /public/ directory to edit your settings
// 0, FALSE or '' to disable
// Pretty much everything else to enable, such as 1, TRUE or any string

// GENERAL SETTINGS
ISQ::$general = array(
	'name' => 'URL shortener', // The name of your URL shortener
	'qr' => TRUE, // Do you want to display a QR code?
	'clipboard' => 1 // Do you want to enable zeroClipboard? (uses flash)
);

// MENU LINKS
// Add more comma seperated arrays to add more links
ISQ::$links = array(
	array(
		'name' => ISQ::$general['name'],
		'link' => YOURLS_SITE
	),
	array(
		'name' => 'Tom Slominski',
		'link' => 'http://tomslominski.net/'
	),
	array(
		'name' => 'Get ∞²!',
		'link' => 'https://github.com/tomslominski/infinity-squared/'
	)
);

// SOCIAL SHARING
ISQ::$social = array(
	'facebook' => TRUE,
	'twitter' => TRUE,
	'plus' => TRUE,
	'linkedin' => TRUE,
	'tumblr' => TRUE
);

// reCAPTCHA API KEYS
// Get yourls from https://www.google.com/recaptcha/admin
ISQ::$recaptcha = array(
	'sitekey' => '',
	'secret' => ''
);

?>
