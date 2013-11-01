<!DOCTYPE html>
<?php
// Start YOURLS engine
require_once( dirname(__FILE__).'/includes/load-yourls.php' );

// Ask for Infinity Squared settings
include( dirname(__FILE__).'/public/config.php' );
class ISQ { public static $general = array(), $links = array(), $social = array(); }

// Translations stuff
function isq_load_textdomain() {
    yourls_load_custom_textdomain( 'isq_translation', $site . '/public/languages' );
    $site = YOURLS_SITE;
}
isq_load_textdomain();

// Settings definitions
$ISQtitle = ISQ::$general['name'];
$ISQname_1= ISQ::$links['name_1'];
$ISQurl_1= ISQ::$links['url_1'];
$ISQname_2= ISQ::$links['name_2'];
$ISQurl_2= ISQ::$links['url_2'];
$ISQname_3= ISQ::$links['name_3'];
$ISQurl_3= ISQ::$links['url_3'];
$ISQname_4= ISQ::$links['name_4'];
$ISQurl_4= ISQ::$links['url_4'];
$ISQname_5= ISQ::$links['name_5'];
$ISQurl_5= ISQ::$links['url_5'];
$ISQname_6= ISQ::$links['name_6'];
$ISQurl_6= ISQ::$links['url_6'];
$ISQname_7= ISQ::$links['name_7'];
$ISQurl_7= ISQ::$links['url_7'];
$ISQname_8= ISQ::$links['name_8'];
$ISQurl_8= ISQ::$links['url_8'];
$ISQname_9= ISQ::$links['name_9'];
$ISQurl_9= ISQ::$links['url_9'];
$ISQname_10= ISQ::$links['name_10'];
$ISQurl_10= ISQ::$links['url_10'];

// Error definitions

$genericerror = '<h2 class="error">' . yourls__( 'An error has occured :(', 'isq_translation') . '</h2>';

if ( $message = $url. yourls__( 'added to database', 'isq_translation') ) {
		$error = '<h2>' . yourls__( 'URL shortened successfully', 'isq_translation') . '</h2><p>' . yourls__( 'View the details of your short URL below.', 'isq_translation') . '</p>';
	} elseif ( $message = $url. yourls__( 'already exists in database', 'isq_translation') ) {
		$error = $genericerror . '<p class="error">' . yourls__( 'This URL already exists in this database. This website does not allow a single URL to have multiple short links.', 'isq_translation') . '</p>';
	} elseif ( $message = yourls__( 'Short URL', 'isq_translation') . $url . yourls__( 'already exists in database or is reserved', 'isq_translation') ) {
		$error = $genericerror . '<p class="error">' . yourls__( 'This short URL already exists in this database or is reserved. This website does not allow a single URL to have multiple short links. It could\'ve also been reserved by the admin.', 'isq_translation') . '</p>';
	} elseif ( $message = yourls__( 'Missing URL input', 'isq_translation') ) {
		$error = $genericerror . '<p class="error">' . yourls__( 'You did not enter the URL you want to shorten or the server lost it. Please try again.', 'isq_translation') . '</p>';
	} elseif ( $message = yourls__( 'This URL is a short URL', 'isq_translation') ) {
		$error = $genericerror . '<p class="error">' . yourls__( 'You cannot shorten a short URL!', 'isq_translation') . '</p>';
	}
?>

<html>
<head>
<title><?php echo $ISQtitle; ?></title> <!-- Site title defined in theme settings -->
<meta http-equiv="Content-Type" content="text/html;charset=utf-8" >
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1"/>
<?php if (!empty(ISQ::$general['clipboard'])) { ?>
<style type="text/css">
	.output label {
		width: 20%;
	}

	.output input {
		width: 60%
	}

	.output button {
		float: right;
	}

	.output button.active {
		color:#013F6D;
	}
</style>
<?php } ?>
<link rel="stylesheet" href="<?php echo YOURLS_SITE; ?>/public/formalize.css" /> <!-- Formalize CSS -->
<link rel="stylesheet" href="http://cdnjs.cloudflare.com/ajax/libs/qtip2/2.1.1/basic/jquery.qtip.min.css" /><!-- qTip CSS -->
<link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Ubuntu:regular,italic,bold,bolditalic"><!-- Ubuntu from Google Web Fonts -->
<link rel="stylesheet" href="<?php echo YOURLS_SITE; ?>/public/style.css" /><!-- Theme CSS -->
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script> <!-- jQuery -->
<script src="<?php echo YOURLS_SITE; ?>/public/js/jquery.formalize.min.js"></script><!-- Formalize JS -->
<script src="http://cdnjs.cloudflare.com/ajax/libs/qtip2/2.1.1/basic/jquery.qtip.min.js"></script><!-- qTip JS -->
<?php if (!empty(ISQ::$social['plus'])) { ?>
<script type="text/javascript" src="https://apis.google.com/js/plusone.js">
  {lang: "en-GB"}
</script>
<?php } ?>
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
<style type="text/css">
	.output label {
		width: 20%;
	}

	.output input {
		width: 60%
	}

	.output button {
		float: right;
	}

	.output button.active {
		color:#013F6D;
	}
</style>
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
</head>

<body>

<div id="container">
<header>
<h1><a href="<?php echo YOURLS_SITE; ?>"><?php echo $ISQtitle; ?></a></h1>
<ul class="menu">
	<li><a href="<?php echo $ISQurl_1; ?>"><?php echo $ISQname_1; ?></a></li>
	<li><a href="<?php echo $ISQurl_2; ?>"><?php echo $ISQname_2; ?></a></li>
	<li><a href="<?php echo $ISQurl_3; ?>"><?php echo $ISQname_3; ?></a></li>
	<li><a href="<?php echo $ISQurl_4; ?>"><?php echo $ISQname_4; ?></a></li>
	<li><a href="<?php echo $ISQurl_5; ?>"><?php echo $ISQname_5; ?></a></li>
	<li><a href="<?php echo $ISQurl_6; ?>"><?php echo $ISQname_6; ?></a></li>
	<li><a href="<?php echo $ISQurl_7; ?>"><?php echo $ISQname_7; ?></a></li>
	<li><a href="<?php echo $ISQurl_8; ?>"><?php echo $ISQname_8; ?></a></li>
	<li><a href="<?php echo $ISQurl_9; ?>"><?php echo $ISQname_9; ?></a></li>
	<li><a href="<?php echo $ISQurl_10; ?>"><?php echo $ISQname_10; ?></a></li>
</ul>
</header>
	<div class="paragraph">
