<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"
   "http://www.w3.org/TR/html4/strict.dtd">
<?php 
// Start YOURLS engine
require_once( dirname(__FILE__).'/includes/load-yourls.php' );

// Ask for Infinity Squared settings
require_once( dirname(__FILE__).'/public/config.php' );
class ISQ { public static $general = array(), $links = array(), $social = array(); }

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
<?php if (!empty(ISQ::$general['mobile'])) { echo "<script type='text/javascript'>if (screen.width <= 720) { document.location = 'mobile.php'; }</script>"; } ?> <!-- Redirect to mobile if screen narrower than 720px -->
<title><?php echo $ISQtitle; ?></title> <!-- Site title defined in theme settings -->
<meta http-equiv="Content-Type" content="text/html;charset=utf-8" >
<script src="<?php echo YOURLS_SITE; ?>/js/jquery-1.9.1.min.js" type="text/javascript"></script> <!-- jQuery -->
<link rel="stylesheet" href="<?php echo YOURLS_SITE; ?>/public/formalize.css" /> <!-- Formalize CSS -->
<script src="<?php echo YOURLS_SITE; ?>/public/js/jquery.formalize.min.js" type="text/javascript"></script><!-- Formalize JS -->
<link href="http://fonts.googleapis.com/css?family=Ubuntu:regular,italic,bold,bolditalic" rel="stylesheet" type="text/css"><!-- Ubuntu from Google Web Fonts -->
<link rel="stylesheet" href="<?php echo YOURLS_SITE; ?>/public/jquery.qtip.min.css" /><!-- qTip CSS -->
<script src="<?php echo YOURLS_SITE; ?>/public/js/jquery.qtip.min.js" type="text/javascript"></script><!-- qTip JS -->
<link rel="stylesheet" href="<?php echo YOURLS_SITE; ?>/public/normal.css" type="text/css" /><!-- Theme CSS -->
<script type="text/javascript" src="https://apis.google.com/js/plusone.js">
  {lang: "en-GB"}
</script>
<script>
$(document).ready(function()
{
	// Match all labels with a title tag and use it as the content
	$('label[title]').qtip();
	// Match all bookmarklet links and use their title as content
	$('a.bookmarklet[title]').qtip();
});
</script>
</head>

<body>

<div id="container">
<div class="header">
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
</div>
	<div class="paragraph">
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

		if (!empty(ISQ::$social['facebook'])) { $ISQfacebook = "<a href='http://facebook.com/sharer.php?u=$shorturl' class='share-button' target='_blank'><img src='public/img/facebook.png' alt='Share on Facebook' /></a>"; }
		if (!empty(ISQ::$social['twitter'])) { $ISQtwitter = "<a href='http://twitter.com/share' class='twitter-share-button' data-url='$shorturl' data-text='$title' data-count='vertical'>Tweet</a><script type='text/javascript' src='http://platform.twitter.com/widgets.js'></script>"; }
		if (!empty(ISQ::$social['plus'])) { $ISQplus = "<div class='g-plus' data-action='share' data-annotation='vertical-bubble' data-height='62' data-href='$shorturl'></div>"; }
		if (!empty(ISQ::$social['linkedin'])) { $ISQlinkedin = "<script src='http://platform.linkedin.com/in.js' type='text/javascript'></script><script type='IN/Share' data-url='$shorturl' data-counter='top'></script>"; }
		if (!empty(ISQ::$general['qr'])) { $ISQqr = "<h2>QR code</h2>\n<p>Share your code with external devices</p>\n<img class='qr' src='https://chart.googleapis.com/chart?cht=qr&chs=150x150&chl=$shorturl&chld=L|0' alt='QR code' />"; }

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
	</div>

<div class="paragraph">
<h2>The bookmarklets</h2>
<p>To use the bookmarklets drag them to your bookmark bar or simply right click on them and select the appropriate option. </p>
<a href="javascript:(function()%7Bvar%20d=document,w=window,enc=encodeURIComponent,e=w.getSelection,k=d.getSelection,x=d.selection,s=(e?e():(k)?k():(x?x.createRange().text:0)),s2=((s.toString()=='')?s:enc(s)),f='<?php echo $page; ?>',l=d.location,p='?url='+enc(l.href)+'&title='+enc(d.title)+'&text='+s2,u=f+p;try%7Bthrow('ozhismygod');%7Dcatch(z)%7Ba=function()%7Bif(!w.open(u))l.href=u;%7D;if(/Firefox/.test(navigator.userAgent))setTimeout(a,0);else%20a();%7Dvoid(0);%7D)()" class="bookmarklet" title="Shortens the URL of the current site and opens a new tab with the details of the shortened URL.">Simple Shorten</a>
<a href="javascript:(function()%7Bvar%20d=document,s=d.createElement('script');window.yourls_callback=function(r)%7Bif(r.short_url)%7Bprompt(r.message,r.short_url);%7Delse%7Balert('An%20error%20occured:%20'+r.message);%7D%7D;s.src='<?php echo $page; ?>?url='+encodeURIComponent(d.location.href)+'&jsonp=yourls';void(d.body.appendChild(s));%7D)();" class="bookmarklet">Instant Shorten</a>
<a href="javascript:(function()%7Bvar%20d=document,w=window,enc=encodeURIComponent,e=w.getSelection,k=d.getSelection,x=d.selection,s=(e?e():(k)?k():(x?x.createRange().text:0)),s2=((s.toString()=='')?s:enc(s)),f='<?php echo $page; ?>',l=d.location,k=prompt(%22Custom%20URL%22),k2=(k?'&keyword='+k:%22%22),p='?url='+enc(l.href)+'&title='+enc(d.title)+'&text='+s2+k2,u=f+p;if(k!=null)%7Btry%7Bthrow('ozhismygod');%7Dcatch(z)%7Ba=function()%7Bif(!w.open(u))l.href=u;%7D;if(/Firefox/.test(navigator.userAgent))setTimeout(a,0);else%20a();%7Dvoid(0)%7D%7D)()" class="bookmarklet" title="Opens a popup which asks for the URL you want to shorten and opens a new tab with the details of the shortened URL.">Custom Shorten</a>
<a href="javascript:(function()%7Bvar%20d=document,k=prompt('Custom%20URL'),s=d.createElement('script');if(k!=null){window.yourls_callback=function(r)%7Bif(r.short_url)%7Bprompt(r.message,r.short_url);%7Delse%7Balert('An%20error%20occured:%20'+r.message);%7D%7D;s.src='<?php echo $page; ?>?url='+encodeURIComponent(d.location.href)+'&keyword='+k+'&jsonp=yourls';void(d.body.appendChild(s));%7D%7D)();" class="bookmarklet">Instant Custom Shorten</a>
</div>

<div class="footer">
<p>Powered by <a href="http://yourls.org/" title="YOURLS">YOURLS</a>. Design by <a href="http://tomslominski.net/">Tom Slominski</a>. Also used: <a href="http://formalize.me/">Formalize</a> and <a href="http://craigsworks.com/projects/qtip2/">qTip²</a>. Find this theme on <a href="https://github.com/tomslominski/infinity-squared">GitHub</a>.</p>
</div>
</div>
</body>
</html>
