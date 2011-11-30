<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
   "http://www.w3.org/TR/html4/strict.dtd">
<?php 
// Start YOURLS engine
require_once( dirname(__FILE__).'/includes/load-yourls.php' );

// Ask for Infinity Squared settings
require_once( dirname(__FILE__).'/public/config.php' );
class ISQ { public static $general = array(), $links = array(), $social = array(); }

// Settings definitions
if (!empty(ISQ::$social['facebook'])) { $ISQfacebook = "<a href='#' class='share-button' onClick='facebook=window.open('http://facebook.com/sharer.php?u=$shorturl&t=$title','facebook','toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=no,width=626,height=436,left=0,top=0'); return false;'><img src='public/img/facebook.png' alt='Share on Facebook' /></a>"; }
if (!empty(ISQ::$social['twitter'])) { $ISQtwitter = "<a href='http://twitter.com/share' class='twitter-share-button' data-url='$shorturl' data-text='$keyword' data-count='vertical'>Tweet</a><script type='text/javascript' src='http://platform.twitter.com/widgets.js'></script>"; }
if (!empty(ISQ::$social['plus'])) { $ISQplus = "<a href='https://m.google.com/app/plus/x/?v=compose&content=$shorturl' target='_blank'><img src='public/img/plus.png' alt='Share on Google+' /></a>"; }
if (!empty(ISQ::$social['linkedin'])) { $ISQlinkedin = "<script src='http://platform.linkedin.com/in.js' type='text/javascript'></script><script type='IN/Share' data-url='$shorturl' data-counter='top'></script>"; }
if (!empty(ISQ::$general['qr'])) { $ISQqr = "<h2>QR code</h2>\n<p>Share your code with external devices</p>\n<img class='qr' src='https://chart.googleapis.com/chart?cht=qr&chs=150x150&chl=$shorturl&chld=L|0' alt='QR code' />"; }
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
if ( $message = $url. "added to database") {
		$error = "<h2>URL shortened successfully</h2>\n<p>View the details of your short URL below.</p>";
	} elseif ( $message = $url. "already exists in database") {
		$error = "<h2 class='error'>An error has occured :(</h2>\n<p class='error'>This URL already exists in this database. This website does not allow a single URL to have multiple short links.</p>";
	} elseif ( $message = "Short URL" .$url. "already exists in database or is reserved") {
		$error = "<h2 class='error'>An error has occured :(</h2>\n<p class='error'>This short URL already exists in this database or is reserved. This website does not allow a single URL to have multiple short links. It could've also been reserved by the admin.</p>";
	} elseif ( $message = "Missing URL input") {
		$error = "<h2 class='error'>An error has occured :(</h2>\n<p class='error'>You did not enter the URL you want to shorten or the server lost it. Please try again.</p>";
	} elseif ( $message = "URL is a short URL") {
		$error = "<h2 class='error'>An error has occured :(</h2>\n<p class='error'>You cannot shorten a short URL!</p>";
	}
?>

<html>
<head>
<title><?php echo $ISQtitle; ?></title>
<meta http-equiv="Content-Type" content="text/html;charset=utf-8" >
<script src="<?php echo YOURLS_SITE; ?>/js/jquery-1.4.3.min.js" type="text/javascript"></script>
<link rel="stylesheet" href="<?php echo YOURLS_SITE; ?>/public/formalize.css" />
<script src="<?php echo YOURLS_SITE; ?>/public/js/jquery.formalize.min.js" type="text/javascript"></script>
<link href='http://fonts.googleapis.com/css?family=Ubuntu:regular,italic,bold,bolditalic' rel='stylesheet' type='text/css'>
<script src="<?php echo YOURLS_SITE; ?>/public/js/jquery.qtip.min.js" type="text/javascript"></script>
<link rel="stylesheet" href="<?php echo YOURLS_SITE; ?>/public/jquery.qtip.min.css" />
<link rel="stylesheet" href="<?php echo YOURLS_SITE; ?>/public/public.css" type="text/css" media="screen" />
<script>
$(document).ready(function()
{
	// Match all labels with a title tag and use it as the content
	$('label[title]').qtip();
});
</script>
</head>

<body>

<div id="container">
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

	<?php

	// Part to be executed if FORM has been submitted
	if ( isset($_REQUEST['url']) ) {

		$url     = yourls_sanitize_url( $_REQUEST['url'] );
		$keyword = isset( $_REQUEST['keyword'] ) ? yourls_sanitize_keyword( $_REQUEST['keyword'] ): '' ;
		$title   = isset( $_REQUEST['title'] ) ? yourls_sanitize_title( $_REQUEST['title'] ) : '' ;
		$return  = yourls_add_new_link( $url, $keyword, $title );
		
		$shorturl = isset( $return['shorturl'] ) ? $return['shorturl'] : '';
		$message  = isset( $return['message'] ) ? $return['message'] : '';
		$title    = isset( $return['title'] ) ? $return['title'] : '';
		
		echo <<<RESULT
		$error
		<div class="output">
		<p>Original URL: <a href="$url">$url</a></p>
		<p>Short URL: <a href="$shorturl">$shorturl</a></p>
		<p>Stats: <a href="$shorturl+">$shorturl+</a></p>
		</div>

		$ISQqr

		<h2>Share</h2>
		<p>Share your short URL</p>
		$ISQfacebook
		$ISQtwitter
		$ISQplus
		$ISQlinkedin
RESULT;

	// Part to be executed when no form has been submitted
	} else {
	
		$site = YOURLS_SITE;

		echo <<<HTML
		<h2>Enter a new URL to shorten</h2>
		<h3>Hover over the labels to see more information</h3>
		<form method="post" action="">
		<p><label for="url" title="Paste the long URL here">Long URL (required):</label> <input type="text" id="url" class="right" name="url" /></p>
		<p><label for="keyword" title="A keyword replaces the default short string">Custom keyword:</label> <input type="text" id="keyword" class="right" name="keyword" /></p>
		<p><label for="title" title="Optional title used when sharing a link from YOURLS">Optional title:</label> <input type="text" id="title" class="right" name="title" /></p>
		<p><input type="submit" value="Shorten" /></p>
		</form>
HTML;

	}

	?>

<h2>The bookmarklets</h2>
<p>To use the bookmarklets drag them to your bookmark bar or simply right click on them and select the appropriate option.</p>
<p><a href="javascript:(function()%7Bvar%20d=document,s=d.createElement('script');window.yourls_callback=function(r)%7Bif(r.short_url)%7Bprompt(r.message,r.short_url);%7Delse%7Balert('An%20error%20occured:%20'+r.message);%7D%7D;s.src='http://kwl.me/admin/index.php?u='+encodeURIComponent(d.location.href)+'&jsonp=yourls';void(d.body.appendChild(s));%7D)();" class="bookmarklet">Instant Shorten</a></p>
<p><a href="javascript:(function()%7Bvar%20d=document,k=prompt('Custom%20URL'),s=d.createElement('script');if(k!=null){window.yourls_callback=function(r)%7Bif(r.short_url)%7Bprompt(r.message,r.short_url);%7Delse%7Balert('An%20error%20occured:%20'+r.message);%7D%7D;s.src='http://kwl.me/admin/index.php?u='+encodeURIComponent(d.location.href)+'&k='+k+'&jsonp=yourls';void(d.body.appendChild(s));%7D%7D)();" class="bookmarklet">Custom Keyword Shorten</a></p>

<div class="footer">
<p>Powered by <a href="http://yourls.org/" title="YOURLS">YOURLS</a>. Design by <a href="http://tomslominski.net/">Tom Slominski</a>. Also used: <a href="http://formalize.me/">Formalize</a> and <a href="http://craigsworks.com/projects/qtip2/">qTip²</a>. Find this theme on <a href="https://github.com/tomslominski/infinity-squared">GitHub</a>.</p>
</div>
</div>
</body>
</html>
