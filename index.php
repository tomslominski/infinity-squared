<?php 
// Start YOURLS engine
require_once( dirname(__FILE__).'/includes/load-yourls.php' );

// Define infinity-squared settings
define( 'INFINITYSQUARED_NAME', 'kwl.me' );
?>
<html>
<head>
<title>kwl.me</title>
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
   // Match all A links with a title tag and use it as the content (default).
   $('label[title]').qtip();
});
</script>
</head>

<body>

<div id="container">
<h1><a href="<?php echo YOURLS_SITE; ?>"><?php echo INFINITYSQUARED_NAME; ?></a></h1>
<ul class="menu">
	<li><a href="http://tomslominski.net/">Blog</a></li>
	<li><a href="http://local.tomslominski.net/">Local</a></li>
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
		<h2>Your URL has been shortened</h2>
		<p>$message</p>
		<div class="output">
		<p>Original URL: <a href="$url">$url</a></p>
		<p>Short URL: <a href="$shorturl">$shorturl</a></p>
		<p>Stats: <a href="$shorturl+">$shorturl+</a></p>
		</div>

		<h2>Share</h2>
		<p>Share your short URL</p>
		<a href="http://twitter.com/share" class="twitter-share-button" data-url="$shorturl" data-text="$keyword" data-count="vertical">Tweet</a><script type="text/javascript" src="http://platform.twitter.com/widgets.js"></script>
		<a href="#" class="share-button" onClick="facebook=window.open('http://facebook.com/sharer.php?u=$shorturl&t=$title','facebook','toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=no,width=626,height=436,left=0,top=0'); return false;"><img src="public/img/facebook.png" alt="Share on Facebook" /></a>
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
