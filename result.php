<?php
include('header.php');

// Stop here if bookmarklet with a JSON callback function ("instant" bookmarklets) -- code from YOURLS
if( isset( $_GET['jsonp'] ) && $_GET['jsonp'] == 'yourls' ) {
	$short = $return['shorturl'] ? $return['shorturl'] : '';
	$message = "Short URL (Ctrl+C to copy)";
	header('Content-type: application/json');
	echo yourls_apply_filter( 'bookmarklet_jsonp', "yourls_callback({'short_url':'$short','message':'$message'});" );

	die();
}

$resp = recaptcha_check_answer (ISQ::$recaptcha['private'],
								$_SERVER["REMOTE_ADDR"],
								$_POST["recaptcha_challenge_field"],
								$_POST["recaptcha_response_field"]);

if (!$resp->is_valid) {
	// What happens when the CAPTCHA was entered incorrectly
	die ( '<p class="error" title="' . $resp->error . '">' . yourls__( 'The reCAPTCHA wasn\'t entered correctly. Go back and try it again.', 'isq_translation' ) . '</p></div></div>' );
}

$url     = yourls_sanitize_url( $_REQUEST['url'] );
$keyword = isset( $_REQUEST['keyword'] ) ? yourls_sanitize_keyword( $_REQUEST['keyword'] ): '' ;
$title   = isset( $_REQUEST['title'] ) ? yourls_sanitize_title( $_REQUEST['title'] ) : '' ;
$return  = yourls_add_new_link( $url, $keyword, $title );
		
$shorturl = isset( $return['shorturl'] ) ? $return['shorturl'] : '';
$message  = isset( $return['message'] ) ? $return['message'] : '';
$title    = isset( $return['title'] ) ? $return['title'] : '';

?>

<!-- Error reporting -->
<?php echo $error; ?>

<!-- Default output -->
<div class="output">
	<p><label for="longurl"><? yourls_e( 'Original URL:', 'isq_translation'); ?></label> <input type="text" name="longurl" onclick="this.select();" onload="this.select();" value="<?php echo $url; ?>" id="long-copy"> <?php if (!empty(ISQ::$general['clipboard'])) { echo '<button id="long-copy" data-clipboard-target="long-copy" class="desktop-only">' . yourls__( 'Copy to Clipboard', 'isq-translation' ) . '</button>'; } ?> </p>
	<p><label for="shorturl"><?php yourls_e( 'Short URL:', 'isq_translation'); ?></label> <input type="text" name="shorturl" onclick="this.select();" onload="this.select();" value="<?php echo $shorturl; ?>" id="short-copy"> <?php if (!empty(ISQ::$general['clipboard'])) { echo '<button id="short-copy" data-clipboard-target="short-copy" class="desktop-only">' . yourls__( 'Copy to Clipboard', 'isq-translation' ) . '</button>'; } ?> </p>
	<p><label for="stats"><?php /* translators: This is short for statistics */ yourls_e( 'Stats:', 'isq_translation'); ?></label> <input type="text" name="stats" onclick="this.select();" onload="this.select();" value="<?php echo $shorturl . '+'; ?>" id="stats-copy"> <?php if (!empty(ISQ::$general['clipboard'])) { echo '<button id="stats-copy" data-clipboard-target="stats-copy" class="desktop-only">' . yourls__( 'Copy to Clipboard', 'isq-translation' ) . '</button>'; } ?> </p>
	<p class="desktop-only"><?php yourls_e( 'Click on a link and press Ctrl+C to quickly copy it.', 'isq_translation'); ?></p>
</div>

<!-- QR code -->
<?php if (!empty(ISQ::$general['qr'])) { echo '<h2>' . yourls__( 'QR code', 'isq-translation' ) . '</h2><p>' . yourls__( 'Share your link with external devices', 'isq-translation' ) . '</p><img class="qr" src="https://chart.googleapis.com/chart?cht=qr&chs=250x250&chl=$shorturl&chld=L|0" alt="QR" width="250px" height="250px" />'; } ?>

<!-- Social sharers -->
<h2><?php yourls_e( 'Share', 'isq_translation'); ?></h2>
<p><?php yourls_e( 'Share your short URL', 'isq_translation'); ?></p>
<?php if (!empty(ISQ::$social['facebook'])) { echo '<div class="social-sharer"><a href="http://facebook.com/sharer.php?u=' . $shorturl . '" class="share-button" target="_blank"><img src="public/img/facebook.png" alt="Facebook" width="55px" height="62px" /></a></div>'; } ?>
<?php if (!empty(ISQ::$social['twitter'])) { echo '<div class="social-sharer"><a href="http://twitter.com/share" class="twitter-share-button" data-url="' . $shorturl . '" data-text="Check out this link!" data-count="vertical">Tweet</a><script src="http://platform.twitter.com/widgets.js"></script></div>'; } ?>
<?php if (!empty(ISQ::$social['plus'])) { echo '<div class="g-plus social-sharer" data-action="share" data-annotation="vertical-bubble" data-height="62" data-href="' . $shorturl . '"></div>'; } ?>
<?php if (!empty(ISQ::$social['linkedin'])) { echo '<div class="social-sharer"><script src="http://platform.linkedin.com/in.js"></script><script type="IN/Share" data-url="' . $shorturl . '" data-counter="top"></script></div>'; } ?>

<?php include('footer.php'); ?>
