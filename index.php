<?php 
// Start YOURLS engine
require_once( dirname(__FILE__).'/includes/load-yourls.php' );
?>
<html>
<head>
<title>kwl.me</title>
<link rel="stylesheet" href="<?php echo YOURLS_SITE; ?>/css/share.css?v=<?php echo YOURLS_VERSION; ?>" type="text/css" media="screen" />
<link rel="stylesheet" href="<?php echo YOURLS_SITE; ?>/public.css" type="text/css" media="screen" />
<link href='http://fonts.googleapis.com/css?family=Ubuntu:regular,italic,bold,bolditalic' rel='stylesheet' type='text/css'>
<script src="<?php echo YOURLS_SITE; ?>/js/jquery-1.3.2.min.js" type="text/javascript"></script>
<script src="<?php echo YOURLS_SITE; ?>/js/ZeroClipboard.js?v=<?php echo YOURLS_VERSION; ?>" type="text/javascript"></script>
<script type="text/javascript">ZeroClipboard.setMoviePath( '<?php echo YOURLS_SITE; ?>/js/ZeroClipboard.swf' );</script>
<script src="<?php echo YOURLS_SITE; ?>/js/share.js?v=<?php echo YOURLS_VERSION; ?>" type="text/javascript"></script>
<!-- Formalize -->
<link rel="stylesheet" href="css/formalize.css" />
<script src="js/jquery.formalize.min.js"></script>
</head>

<body>

<div id="container">
<h1><a href="<?php echo YOURLS_SITE; ?>">kwl.me</a></h1>
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

// Mr. Tech- URL Sanitizing
function valid_url($str) {
	return ( ! preg_match('/^(http|https|ftp):\/\/([A-Z0-9][A-Z0-9_-]*(?:\.[A-Z0-9][A-Z0-9_-]*)+):?(\d+)?\/?/i', $str)) 
? FALSE : TRUE;
}

$url = yourls_sanitize_url($_REQUEST['url']);

if (!valid_url($url)) {
	die( 'Invalid url, go back and try again' );
}

// Mr. Tech- Bans
$url = $_REQUEST['url'];

foreach ($yourls_banned_URL as $banned_url) {
	if (stristr($url, $banned_url)) {
		die( 'Banned site' );
	}
}

		$return  = yourls_add_new_link( $url, $keyword, $title );
		
		$shorturl = isset( $return['shorturl'] ) ? $return['shorturl'] : '';
		$message  = isset( $return['message'] ) ? $return['message'] : '';
		$title    = isset( $return['title'] ) ? $return['title'] : '';
		
		echo <<<RESULT
		<h2>URL has been shortened</h2>
		<p>Original URL: <code><a href="$url">$url</a></code></p>
		<p>Short URL: <code><a href="$shorturl">$shorturl</a></code></p>
		<p><strong>$message</strong></p>
RESULT;
		
		// Include the Copy box and the Quick Share box
		yourls_share_box( $url, $shorturl, $title );

	// Part to be executed when no form has been submitted
	} else {
	
		$site = YOURLS_SITE;

		echo <<<HTML
		<h2>Enter a new URL to shorten</h2>
		<form method="post" action="">
		<p><label>URL (required):</label> <input type="text" name="url" value="Paste the long URL here" onfocus="if(this.value == this.defaultValue) this.value = ''" class="right" /></p>
		<p><label>Custom keyword:</label> <input type="text" name="keyword" value="A keyword replaces the default short string" onfocus="if(this.value == this.defaultValue) this.value = ''" class="right" /></p>
		<p><label>Optional title:</label> <input type="text" name="title" value="Optional title used when sharing a link from YOURLS" onfocus="if(this.value == this.defaultValue) this.value = ''" class="right" /></p>
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
<p>Powered by <a href="http://yourls.org/" title="YOURLS">YOURLS</a>. Design by <a href="http://tomslominski.net/">Tom Slominski</a>.</p>
</div>
</div>
</body>
</html>
