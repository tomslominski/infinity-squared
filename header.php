<!DOCTYPE html>
<?php
// Start YOURLS engine
require_once( dirname(__FILE__).'/includes/load-yourls.php' );

// Ask for Infinity Squared settings
include( dirname(__FILE__).'/public/config.php' );
class ISQ { public static $general = array(), $links = array(), $social = array(), $recaptcha = array(); }

// Load translations
function isq_load_textdomain() {
    yourls_load_custom_textdomain( 'isq_translation', $site . '/public/languages' );
    $site = YOURLS_SITE;
}
isq_load_textdomain();

// Load reCAPTCHA
require_once('public/recaptchalib.php');
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
<?php if (!empty(ISQ::$social['plus'])) { ?>
<script type="text/javascript" src="https://apis.google.com/js/plusone.js">
  {lang: "en-GB"}
</script>
<?php } ?>
<script type="text/javascript"><!-- reCAPTCHA -->
var RecaptchaOptions = {
	theme : 'white'
};
</script>
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
</head>

<body>

<div id="container">
<header>
	<h1><a href="<?php echo YOURLS_SITE; ?>"><?php echo ISQ::$general['name']; ?></a></h1>
	<ul class="menu">
		<li><a href="<?php echo YOURLS_SITE; ?>"><?php echo ISQ::$general['name']; ?></a></li>
		<li><a href="<?php echo ISQ::$links['url_1']; ?>"><?php echo ISQ::$links['name_1']; ?></a></li>
		<li><a href="<?php echo ISQ::$links['url_2']; ?>"><?php echo ISQ::$links['name_2']; ?></a></li>
		<li><a href="<?php echo ISQ::$links['url_3']; ?>"><?php echo ISQ::$links['name_3']; ?></a></li>
		<li><a href="<?php echo ISQ::$links['url_4']; ?>"><?php echo ISQ::$links['name_4']; ?></a></li>
		<li><a href="<?php echo ISQ::$links['url_5']; ?>"><?php echo ISQ::$links['name_5']; ?></a></li>
		<li><a href="<?php echo ISQ::$links['url_6']; ?>"><?php echo ISQ::$links['name_6']; ?></a></li>
		<li><a href="<?php echo ISQ::$links['url_7']; ?>"><?php echo ISQ::$links['name_7']; ?></a></li>
		<li><a href="<?php echo ISQ::$links['url_8']; ?>"><?php echo ISQ::$links['name_8']; ?></a></li>
		<li><a href="<?php echo ISQ::$links['url_9']; ?>"><?php echo ISQ::$links['name_9']; ?></a></li>
		<li><a href="<?php echo ISQ::$links['url_10']; ?>"><?php echo ISQ::$links['name_10']; ?></a></li>
	</ul>
</header>

<div class="paragraph">
