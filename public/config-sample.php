<?php

// Copy and rename your file to config.php in this directory to edit your settings

// General settings
// 1 to enable, 0 to disable
ISQ::$general = array(
	'name' => 'URL shortener', // The name of your URL shortener
	'qr' => 1, // Do you want to display a QR code?
	'clipboard' => 1 // Do you want to enable zeroClipboard? (uses flash)
);

// Menu links
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

// Social sharing
// 1 to enable, 0 to disable
ISQ::$social = array(
	'facebook' => 1,
	'twitter' => 1,
	'plus' => 1,
	'linkedin' => 1,
	'tumblr' => 1
);

// reCAPTCHA API keys
// Get yourls from https://www.google.com/recaptcha/admin
ISQ::$recaptcha = array(
	'sitekey' => '',
	'secret' => ''
);
?>
