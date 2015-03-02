<!DOCTYPE html>
<?php
// Start YOURLS engine
require_once( dirname(__FILE__).'/includes/load-yourls.php' );

// Ask for Infinity Squared settings
include( dirname(__FILE__).'/public/config.php' );

if( ( @include dirname(__FILE__).'/public/config.php' ) === false) {
	include( dirname(__FILE__).'/public/config-sample.php' );
}

class ISQ { public static $general = array(), $links = array(), $social = array(), $recaptcha = array(); }

// Load translations
function isq_load_textdomain() {
	yourls_load_custom_textdomain( 'isq_translation', '/public/languages' );
}
isq_load_textdomain();
?>

<html>
<head>
<title><?php echo ISQ::$general['name']; ?></title> <!-- Site title defined in theme settings -->
<meta http-equiv="Content-Type" content="text/html;charset=utf-8" >
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1"/>
<link rel="shortcut icon" href="<?php echo YOURLS_SITE; ?>/images/favicon.gif" /> <!-- Default favicon -->
<link rel="stylesheet" href="<?php echo YOURLS_SITE; ?>/public/formalize.css" /> <!-- Formalize CSS -->
<link rel="stylesheet" href="http://cdnjs.cloudflare.com/ajax/libs/qtip2/2.1.1/basic/jquery.qtip.min.css" /><!-- qTip CSS -->
<link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Ubuntu:regular,italic,bold,bolditalic"><!-- Ubuntu from Google Web Fonts -->
<link rel="stylesheet" href="<?php echo YOURLS_SITE; ?>/public/style.css" /><!-- Theme CSS -->
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script> <!-- jQuery -->
<script src="<?php echo YOURLS_SITE; ?>/public/js/jquery.formalize.min.js"></script><!-- Formalize JS -->
<script src="http://cdnjs.cloudflare.com/ajax/libs/qtip2/2.1.1/basic/jquery.qtip.min.js"></script><!-- qTip JS -->
<?php if ( ISQ::$social['plus'] ) { ?>
	<script type="text/javascript" src="https://apis.google.com/js/plusone.js">
		{lang: "en-GB"}
	</script>
<?php } ?>
<script src='https://www.google.com/recaptcha/api.js'></script><!-- reCAPTCHA -->
<?php if (!empty(ISQ::$general['clipboard'])) { ?>
	<script type="text/javascript" src="js/jquery.zclip.min.js"></script>
	<script>
	$(document).ready(function(){
		$('button#long-copy').zclip({
			path:'js/ZeroClipboard.swf',
			copy:function(){return $('input#long-copy').val();},
			afterCopy: function() {
				$("button#long-copy").html('<?php yourls_e( 'Copied!', 'isq_translation'); ?>');
			}
		});
		$('button#short-copy').zclip({
			path:'js/ZeroClipboard.swf',
			copy:function(){return $('input#short-copy').val();},
			afterCopy: function() {
				$("button#short-copy").html('<?php yourls_e( 'Copied!', 'isq_translation'); ?>');
			}
		});
		$('button#stats-copy').zclip({
			path:'js/ZeroClipboard.swf',
			copy:function(){return $('input#stats-copy').val();},
			afterCopy: function() {
				$("button#stats-copy").html('<?php yourls_e( 'Copied!', 'isq_translation'); ?>');
			}
		});
	});
	</script>
<?php } ?>
<script>
$(document).ready(function()
{
	$('a.bookmarklet').qtip({
	});
	$('label').qtip({
	});
});
</script>

<!-- App icons
	Generated using http://realfavicongenerator.net -->
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

<body>

<div id="container">
<header>
	<h1><a href="<?php echo YOURLS_SITE; ?>"><?php echo ISQ::$general['name']; ?></a></h1>
	<ul class="menu">
		<?php
			foreach( ISQ::$links as $menuItem ) {
				echo '<li><a href="' . $menuItem['link'] . '">' . $menuItem['name'] . '</a></li>';
			}
		?>
	</ul>
</header>

<div class="paragraph">
