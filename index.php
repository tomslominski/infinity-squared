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
	<?php

	// Part to be executed if FORM has been submitted
	if ( isset($_REQUEST['url']) ) {

		if ( isset($_POST['antispam']) && $_POST['antispam'] != user ) {
			exit('<p class="error">' . yourls__( 'Antispam check failed!', 'isq_translation') . '</p>'); 
		}

		$url     = yourls_sanitize_url( $_REQUEST['url'] );
		$keyword = isset( $_REQUEST['keyword'] ) ? yourls_sanitize_keyword( $_REQUEST['keyword'] ): '' ;
		$title   = isset( $_REQUEST['title'] ) ? yourls_sanitize_title( $_REQUEST['title'] ) : '' ;
		$return  = yourls_add_new_link( $url, $keyword, $title );
		
		$shorturl = isset( $return['shorturl'] ) ? $return['shorturl'] : '';
		$message  = isset( $return['message'] ) ? $return['message'] : '';
		$title    = isset( $return['title'] ) ? $return['title'] : '';

		if (!empty(ISQ::$social['facebook'])) { $ISQfacebook = '<a href="http://facebook.com/sharer.php?u=$shorturl" class="share-button" target="_blank"><img src="public/img/facebook.png" alt="Facebook" width="55px" height="62px" /></a>'; }
		if (!empty(ISQ::$social['twitter'])) { $ISQtwitter = '<a href="http://twitter.com/share" class="twitter-share-button" data-url="$shorturl" data-text="$title" data-count="vertical">Tweet</a><script src="http://platform.twitter.com/widgets.js"></script>'; }
		if (!empty(ISQ::$social['plus'])) { $ISQplus = '<div class="g-plus" data-action="share" data-annotation="vertical-bubble" data-height="62" data-href="$shorturl"></div>'; }
		if (!empty(ISQ::$social['linkedin'])) { $ISQlinkedin = '<script src="http://platform.linkedin.com/in.js"></script><script type="IN/Share" data-url="$shorturl" data-counter="top"></script>'; }
		if (!empty(ISQ::$general['qr'])) { $ISQqr = '<h2>' . yourls__( 'QR code', 'isq-translation' ) . '</h2><p>' . yourls__( 'Share your link with external devices', 'isq-translation' ) . '</p><img class="qr" src="https://chart.googleapis.com/chart?cht=qr&chs=250x250&chl=$shorturl&chld=L|0" alt="QR" width="250px" height="250px" />'; }
		if (!empty(ISQ::$general['clipboard'])) { $ISQlongcopy = '<button id="long-copy" data-clipboard-target="long-copy">' . yourls__( 'Copy to Clipboard', 'isq-translation' ) . '</button>'; }
		if (!empty(ISQ::$general['clipboard'])) { $ISQshortcopy = '<button id="short-copy" data-clipboard-target="short-copy">' . yourls__( 'Copy to Clipboard', 'isq-translation' ) . '</button>'; }
		if (!empty(ISQ::$general['clipboard'])) { $ISQstatscopy = '<button id="stats-copy" data-clipboard-target="stats-copy">' . yourls__( 'Copy to Clipboard', 'isq-translation' ) . '</button>'; }

		$output_original = yourls__( 'Original URL:', 'isq_translation');
		$output_short = yourls__( 'Short URL:', 'isq_translation');
		/* translators: This is short for statistics */
		$output_stats = yourls__( 'Stats:', 'isq_translation');
		$output_copy = yourls__( 'Click on a link and press Ctrl+C to quickly copy it.', 'isq_translation');
		$output_share_h2 = yourls__( 'Share', 'isq_translation');
		$output_share_p = yourls__( 'Share your short URL', 'isq_translation');

		echo <<<RESULT
		$error
		<div class="output">
		<p><label for="longurl">$output_original</label> <input type="text" name="longurl" onclick="this.select();" onload="this.select();" value="$url" id="long-copy"> $ISQlongcopy </p>
		<p><label for="shorturl">$output_short</label> <input type="text" name="shorturl" onclick="this.select();" onload="this.select();" value="$shorturl" id="short-copy"> $ISQshortcopy </p>
		<p><label for="stats">$output_stats</label> <input type="text" name="stats" onclick="this.select();" onload="this.select();" value="$shorturl+" id="stats-copy"> $ISQstatscopy </p>
		<p class="desktop-only">$output_copy</p>
		</div>

		$ISQqr

		<h2>$output_share_h2</h2>
		<p>$output_share_p</p>
		$ISQfacebook
		$ISQtwitter
		$ISQplus
		$ISQlinkedin
RESULT;

	// Part to be executed when no form has been submitted
	} else {
	
		$site = YOURLS_SITE;

		$site_enter = yourls__( 'Enter a new URL to shorten', 'isq_translation');
		$site_hover = yourls__( 'Hover over the labels to see more information', 'isq_translation');
		$site_long = yourls__( 'Long URL (required):', 'isq_translation');
		$site_long_hover = yourls__( 'Paste the long URL here', 'isq_translation');
		$site_keyword = yourls__( 'Custom keyword:', 'isq_translation');
		$site_keyword_hover = yourls__( 'A keyword replaces the default short string', 'isq_translation');
		$site_title = yourls__( 'Optional title:', 'isq_translation');
		$site_title_hover = yourls__( 'Optional title used when sharing a link from YOURLS', 'isq_translation');
		$site_submit = yourls__( 'Shorten', 'isq_translation');
		$antispam_title = yourls__( 'Antispam check', 'isq_translation');
		$antispam_value = yourls__( 'Are you a bot or a human?', 'isq_translation');
		$antispam_bot = yourls__( 'Bot', 'isq_translation');
		$antispam_human = yourls__( 'Human', 'isq_translation');

		echo <<<HTML
		<h2>$site_enter</h2>
		<h3>$site_hover</h3>
		<form method="post" action="">
		<p><label for="url" title="$site_long_hover">$site_long</label> <input type="text" id="url" class="right" name="url" /></p>
		<p><label for="keyword" title="$site_keyword_hover">$site_keyword</label> <input type="text" id="keyword" class="right" name="keyword" /></p>
		<p><label for="title" title="$site_title_hover">$site_title</label> <input type="text" id="title" class="right" name="title" /></p>
		<p><label for="antispam" title="$antispam_title">$antispam_value</label> <span class="right"><span class="radio"><input type="radio" name="antispam" checked value="bot" />$antispam_bot</span> <span class="radio"><input type="radio" name="antispam" value="user" />$antispam_human</span></span></p> 
		<p><input type="submit" value="$site_submit" /></p>
		</form>
HTML;

	}
	?>
	</div>

<div class="paragraph desktop-only">
<h2><?php yourls_e( 'The bookmarklets', 'isq_translation') ?></h2>
<p><?php yourls_e( 'To use the bookmarklets drag them to your bookmark bar or simply right click on them and select the appropriate option.', 'isq_translation') ?></p>

<a href="javascript:(function()%7Bvar%20d=document,w=window,enc=encodeURIComponent,e=w.getSelection,k=d.getSelection,x=d.selection,s=(e?e():(k)?k():(x?x.createRange().text:0)),s2=((s.toString()=='')?s:enc(s)),f='<?php echo $page; ?>',l=d.location,p='?url='+enc(l.href)+'&title='+enc(d.title)+'&text='+s2,u=f+p;try%7Bthrow('ozhismygod');%7Dcatch(z)%7Ba=function()%7Bif(!w.open(u))l.href=u;%7D;if(/Firefox/.test(navigator.userAgent))setTimeout(a,0);else%20a();%7Dvoid(0);%7D)()" title="<?php yourls_e( 'Shortens the URL of the current site and opens a new tab with the details of the shortened URL.', 'isq_translation') ?>" class="bookmarklet"><?php yourls_e( 'Simple Shorten', 'isq_translation') ?></a>
<a href="javascript:(function()%7Bvar%20d=document,s=d.createElement('script');window.yourls_callback=function(r)%7Bif(r.short_url)%7Bprompt(r.message,r.short_url);%7Delse%7Balert('An%20error%20occured:%20'+r.message);%7D%7D;s.src='<?php echo $page; ?>?url='+encodeURIComponent(d.location.href)+'&jsonp=yourls';void(d.body.appendChild(s));%7D)();" title="<?php yourls_e( 'Opens a pop up with the short URL for the current page.', 'isq_translation') ?>" class="bookmarklet"><?php yourls_e( 'Instant Shorten', 'isq_translation') ?></a>
<a href="javascript:(function()%7Bvar%20d=document,w=window,enc=encodeURIComponent,e=w.getSelection,k=d.getSelection,x=d.selection,s=(e?e():(k)?k():(x?x.createRange().text:0)),s2=((s.toString()=='')?s:enc(s)),f='<?php echo $page; ?>',l=d.location,k=prompt(%22Custom%20URL%22),k2=(k?'&keyword='+k:%22%22),p='?url='+enc(l.href)+'&title='+enc(d.title)+'&text='+s2+k2,u=f+p;if(k!=null)%7Btry%7Bthrow('ozhismygod');%7Dcatch(z)%7Ba=function()%7Bif(!w.open(u))l.href=u;%7D;if(/Firefox/.test(navigator.userAgent))setTimeout(a,0);else%20a();%7Dvoid(0)%7D%7D)()" title="<?php yourls_e( 'Opens a popup which asks for the keyword for the current URL and opens a new tab with the details of the shortened URL.', 'isq_translation') ?>" class="bookmarklet" ><?php yourls_e( 'Custom Shorten', 'isq_translation') ?></a>
<a href="javascript:(function()%7Bvar%20d=document,k=prompt('Custom%20URL'),s=d.createElement('script');if(k!=null){window.yourls_callback=function(r)%7Bif(r.short_url)%7Bprompt(r.message,r.short_url);%7Delse%7Balert('An%20error%20occured:%20'+r.message);%7D%7D;s.src='<?php echo $page; ?>?url='+encodeURIComponent(d.location.href)+'&keyword='+k+'&jsonp=yourls';void(d.body.appendChild(s));%7D%7D)();" title="<?php yourls_e( 'This prompts you for the keyword for the current page and then opens a pop up with the short URL of the current page.', 'isq_translation') ?>" class="bookmarklet"><?php yourls_e( 'Instant Custom Shorten', 'isq_translation') ?></a>
</div>

<div class="footer">
<p><?php yourls_e( 'Powered by <a href="http://yourls.org/">YOURLS</a>. Design by <a href="http://tomslominski.net/">Tom Slominski</a>. Also used: <a href="http://formalize.me/">Formalize</a> and <a href="http://qtip2.com/">qTip²</a>. Find this theme on <a href="https://github.com/tomslominski/infinity-squared">GitHub</a>.', 'isq_translation') ?></p>
</div>
</div>
</body>
</html>