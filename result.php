<?php
$dependencies = array();

include('header.php');

if ( ISQ::$general['clipboard'] ) {
	$dependencies[] = 'ZeroClipboard';
};

function display_error($message) {
	echo '<div class="content"><p class="error">' . $message . '</p></div>';
	include('footer.php');
	die();
}

if ( empty( $_REQUEST['url'] ) ) {
	display_error( yourls__( 'You haven\'t entered a URL to shorten. Please go back and try again.', 'isq_translation' ) );
};

if ( !empty(ISQ::$recaptcha['sitekey']) && !empty(ISQ::$recaptcha['secret']) ) {
	$recaptcha_data = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret=' . ISQ::$recaptcha['secret'] . '&response=' . $_REQUEST['g-recaptcha-response']);
	$recaptcha_json = json_decode($recaptcha_data, TRUE);

	// What happens when the CAPTCHA was completed incorrectly
	if ( $recaptcha_json['success'] != 'true' ) {
		display_error( yourls__( 'Are you a bot? Google certainly thinks you are. Please go back and try again.', 'isq_translation' ) );
	}
} else {
	if ( $_REQUEST['basic_antispam'] != "" ) {
		display_error( yourls__( 'Are you a bot? The verification was not completed successfully. Please go back and try again.', 'isq_translation' ) );
	}
};

// Get parameters -- they will all be sanitized in yourls_add_new_link()
$url     = $_REQUEST['url'];
$keyword = isset( $_REQUEST['keyword'] ) ? $_REQUEST['keyword'] : '' ;
$title   = isset( $_REQUEST['title'] ) ?  $_REQUEST['title'] : '' ;
$text    = isset( $_REQUEST['text'] ) ?  $_REQUEST['text'] : '' ;

// Create short URL, receive array $return with various information
$return  = yourls_add_new_link( $url, $keyword, $title );

$shorturl = isset( $return['shorturl'] ) ? $return['shorturl'] : '';
$message  = isset( $return['message'] ) ? $return['message'] : '';
$title    = isset( $return['title'] ) ? $return['title'] : '';
$status   = isset( $return['status'] ) ? $return['status'] : '';

// URL encoded links used in the social sharing buttons
$encoded_shorturl = urlencode($shorturl);
$encoded_title = urlencode($title);

// QR code shenanigans
if ( ISQ::$general['qr'] ) { 

	include('public/phpqrcode/qrlib.php');

	$qrContainerId = 'url-qr-code'; 
	$saveToFile = false; 
	$imageWidth = 600; // in pixels

	$qrCode = QRcode::svg($url, $qrContainerId, $saveToFile, QR_ECLEVEL_L, $imageWidth);

};

?>

<div class="content">
	<!-- Error reporting -->
	<?php isset( $error ) ? $error : ''; ?>

	<!-- Default output -->
	<h2><?php yourls_e( 'Your short URL', 'isq_translation'); ?></h2>

	<div class="output">
		<div class="form-item full-width">
			<label for="longurl" class="primary"><?php yourls_e( 'Original URL', 'isq_translation'); ?></label>
			<input type="text" name="longurl" id="longurl" onclick="this.select();" onload="this.select();" value="<?php echo $url; ?>">
			<?php if ( ISQ::$general['clipboard'] ) { echo '<button data-clipboard-target="longurl" class="desktop-only copy-button button">' . yourls__( 'Copy to clipboard', 'isq_translation' ) . '</button>'; } ?>
		</div>

		<div class="halves">

		<div class="form-item half-width left">
			<label for="shorturl" class="primary"><?php yourls_e( 'Short URL', 'isq_translation'); ?></label>
			<input type="text" name="shorturl" id="shorturl" onclick="this.select();" value="<?php echo $shorturl; ?>">
			<?php if ( ISQ::$general['clipboard'] ) { echo '<button data-clipboard-target="shorturl" class="desktop-only copy-button button">' . yourls__( 'Copy to clipboard', 'isq_translation' ) . '</button>'; } ?>
		</div>
		
		<div class="form-item half-width right">
			<label for="stats" class="primary"><?php /* translators: This is short for statistics */ yourls_e( 'Stats', 'isq_translation'); ?></label>
			<input type="text" name="stats" id="stats" onclick="this.select();" value="<?php echo $shorturl . '+'; ?>" id="stats-copy">
			<?php if ( ISQ::$general['clipboard'] ) { echo '<button data-clipboard-target="stats" class="desktop-only copy-button button">' . yourls__( 'Copy to clipboard', 'isq_translation' ) . '</button>'; } ?>
		</div>
		
		</div>

		<p class="desktop-only"><?php yourls_e( 'Click on a link and press Ctrl+C to quickly copy it.', 'isq_translation'); ?></p>
	</div>

	<!-- Social sharers -->
	<h2><?php yourls_e( 'Share', 'isq_translation'); ?></h2>
	<p><?php yourls_e( 'Share your short URL', 'isq_translation'); ?></p>
	<div class="social-sharing">
		<?php
			if ( ISQ::$social['twitter'] ) { echo '<span onclick="window.open(\'https://twitter.com/intent/tweet?url=' . $encoded_shorturl . '&text=' . $encoded_title . '\',\'_blank\',\'width=550,height=380\')" class="button social-button twitter" title="Share on Twitter">' . file_get_contents('public/images/twitter.svg') . '</span>'; }

			if ( ISQ::$social['appdotnet'] ) { echo '<span onclick="window.open(\'https://account.app.net/intent/post/?text=' . $encoded_title . '&url=' . $encoded_shorturl . '\',\'_blank\',\'width=550,height=380\')" class="button social-button appdotnet" title="Share on App.net">' . file_get_contents('public/images/appdotnet.svg') . '</span>'; }

			if ( ISQ::$social['facebook'] ) { echo '<span onclick="window.open(\'https://www.facebook.com/sharer/sharer.php?u=' . $shorturl . '\',\'_blank\',\'width=550,height=380\')" class="button social-button facebook" title="Share on Facebook">' . file_get_contents('public/images/facebook.svg') . '</span>'; }

			if ( ISQ::$social['tumblr'] ) { echo '<span onclick="window.open(\'http://www.tumblr.com/share/link?url=' . $encoded_shorturl . '&name=' . $encoded_title . '\',\'_blank\',\'width=550,height=380\')" class="button social-button tumblr" title="Share on Tumblr">' . file_get_contents('public/images/tumblr.svg') . '</span>'; }

			if ( ISQ::$social['linkedin'] ) { echo '<span onclick="window.open(\'https://www.linkedin.com/shareArticle?mini=true&url=' . $encoded_shorturl . '&title=' . $encoded_title . '\',\'_blank\',\'width=550,height=380\')" class="button social-button linkedin" title="Share on LinkedIn">' . file_get_contents('public/images/linkedin.svg') . '</span>'; }	

			if ( ISQ::$social['googleplus'] ) { echo '<span onclick="window.open(\'https://plus.google.com/share?url=' . $encoded_shorturl . '\',\'_blank\',\'width=550,height=380\')" class="button social-button googleplus" title="Share on LinkedIn">' . file_get_contents('public/images/googleplus.svg') . '</span>'; }
		?>		
	</div>

	<!-- QR code -->
	<?php if ( ISQ::$general['qr'] ) { echo '<h2>' . yourls__( 'QR code', 'isq_translation' ) . '</h2><p>' . yourls__( 'Share your link with external devices', 'isq_translation' ) . '</p>' . $qrCode; } ?>
</div>

<?php include('footer.php'); ?>
