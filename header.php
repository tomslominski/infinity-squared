<!DOCTYPE html>
<?php
// Start YOURLS engine
require_once( dirname(__FILE__) . '/includes/load-yourls.php' );

// Ask for Infinity Squared settings
if( @include dirname(__FILE__) . '/public/config.php' ) {
	include( dirname(__FILE__) . '/public/config.php' );
} else {
	include( dirname(__FILE__) . '/public/config-sample.php' );
}

class ISQ { public static $general = array(), $links = array(), $social = array(), $recaptcha = array(); }

// Load translations
yourls_load_custom_textdomain( 'isq_translation', 'public/languages' );
?>

<html>
	<head>
		<title><?php echo ISQ::$general['name']; ?></title> <!-- Site title defined in theme settings -->
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link href="//fonts.googleapis.com/css?family=Open+Sans:400,700" rel="stylesheet" type="text/css">
		<link rel="stylesheet" href="<?php echo YOURLS_SITE; ?>/public/style.css" /><!-- Theme CSS -->
		<?php if ( ISQ::$general['customstyle'] ) { ?>
			<link rel="stylesheet" href="<?php echo YOURLS_SITE; ?>/public/custom.css" /><!-- Custom CSS -->
		<?php } ?>

		<!-- App icons generated using http://realfavicongenerator.net -->
		<link rel="apple-touch-icon" sizes="57x57" href="public/images/app-icons/apple-touch-icon-57x57.png">
		<link rel="apple-touch-icon" sizes="60x60" href="public/images/app-icons/apple-touch-icon-60x60.png">
		<link rel="apple-touch-icon" sizes="72x72" href="public/images/app-icons/apple-touch-icon-72x72.png">
		<link rel="apple-touch-icon" sizes="76x76" href="public/images/app-icons/apple-touch-icon-76x76.png">
		<link rel="apple-touch-icon" sizes="114x114" href="public/images/app-icons/apple-touch-icon-114x114.png">
		<link rel="apple-touch-icon" sizes="120x120" href="public/images/app-icons/apple-touch-icon-120x120.png">
		<link rel="apple-touch-icon" sizes="144x144" href="public/images/app-icons/apple-touch-icon-144x144.png">
		<link rel="apple-touch-icon" sizes="152x152" href="public/images/app-icons/apple-touch-icon-152x152.png">
		<link rel="apple-touch-icon" sizes="180x180" href="public/images/app-icons/apple-touch-icon-180x180.png">
		<link rel="icon" type="image/png" href="public/images/app-icons/favicon-32x32.png" sizes="32x32">
		<link rel="icon" type="image/png" href="public/images/app-icons/android-chrome-192x192.png" sizes="192x192">
		<link rel="icon" type="image/png" href="public/images/app-icons/favicon-96x96.png" sizes="96x96">
		<link rel="icon" type="image/png" href="public/images/app-icons/favicon-16x16.png" sizes="16x16">
		<link rel="manifest" href="public/images/app-icons/manifest.json">
		<link rel="shortcut icon" href="public/images/app-icons/favicon.ico">
		<meta name="msapplication-TileColor" content="#2b5797">
		<meta name="msapplication-TileImage" content="public/images/app-icons/mstile-144x144.png">
		<meta name="msapplication-config" content="public/images/app-icons/browserconfig.xml">
		<meta name="theme-color" content="#013f6d">
	</head>

	<body class="load">

		<div class="wrapper">

			<header class="content site-header">
				<a href="<?php echo YOURLS_SITE; ?>" class="site-title">
					<h1><?php echo ISQ::$general['name']; ?></h1>
				</a>
				<nav class="menu">
					<?php
						foreach( ISQ::$links as $menuItem ) {
							echo '<a href="' . $menuItem['link'] . '">' . $menuItem['name'] . '</a>';
						};
					?>
				</nav>
			</header>
