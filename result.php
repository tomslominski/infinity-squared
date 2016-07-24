<?php
include('header.php');

function display_error( $message, $action = null ) {
	echo '<div class="content error">';
		echo '<p class="message">' . $message . '</p>';

		echo '<p class="action">';
			if( !empty( $action ) ) {
				echo $action;
			} else {
				echo '<a href="' . YOURLS_SITE . '" class="button">' . yourls__( '&larr; Go home and try again', 'isq_translation' ) . '</a>';
			}
		echo '</p>';
	echo '</div>';

	include('footer.php');
	die();
}

if ( empty( $_REQUEST['url'] ) ) {
	display_error( yourls__( 'You haven\'t entered a URL to shorten.', 'isq_translation' ) );
};

// Check if the keyword is reserved
if ( !empty( $_REQUEST['keyword'] ) && yourls_keyword_is_reserved( $_REQUEST['keyword'] ) ) {
	display_error( sprintf( yourls__( 'The keyword %1$s is reserved.'), '<span class="key">' . $_REQUEST['keyword'] . '</span>' ) );
}

// Check if the keyword is taken
if ( !empty( $_REQUEST['keyword'] ) && yourls_keyword_is_taken( $_REQUEST['keyword'] ) ) {
	display_error( sprintf( yourls__( 'The keyword %1$s is taken.'), '<span class="key">' . $_REQUEST['keyword'] . '</span>' ) );
}

// Check what CAPTCHA method was used
$antispam_method = $_REQUEST['antispam_method'];

if ( $antispam_method == 'user_login' ) {

	// User is logged into YOURLS

} else if ( $antispam_method == 'recaptcha' ) {

	// Google reCAPTCHA is enabled
	$recaptcha_data = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret=' . ISQ::$recaptcha['secret'] . '&response=' . $_REQUEST['g-recaptcha-response']);
	$recaptcha_json = json_decode($recaptcha_data, TRUE);

	// What happens when the reCAPTCHA was completed incorrectly
	if ( $recaptcha_json['success'] != 'true' ) {
		display_error( yourls__( 'Are you a bot? Google certainly thinks you are.', 'isq_translation' ) );
	}

} else if ( $antispam_method == 'basic' ) {

	// Basic antispam protection fallback
	// What happens when it was not completed correctly
	if ( $_REQUEST['basic_antispam'] != "" ) {
		display_error( yourls__( 'Are you a bot? The verification was not completed successfully.', 'isq_translation' ) );
	}

} else {

	// No antispam protection was detected
	display_error( yourls__( 'Are you a bot? No antispam protection was completed successfully.', 'isq_translation' ) );

}

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

// Check for all other errors
if( empty( $shorturl ) ) {
	display_error( yourls__( 'The URL could not be shortened.', 'isq_translation' ) );
}

// URL encoded links used in the social sharing buttons
$encoded_shorturl = urlencode($shorturl);
$encoded_title = urlencode($title);

// Add dependencies
$dependencies[] = 'clipboard.js';

?>

<div class="content result">
	<!-- Error reporting -->
	<?php isset( $error ) ? $error : ''; ?>

	<!-- Default output -->
	<h2><?php yourls_e( 'Your short URL', 'isq_translation'); ?></h2>

	<div class="output">
		<div class="form-item full-width">
			<label for="longurl" class="primary"><?php yourls_e( 'Original URL', 'isq_translation'); ?></label>
			<div class="input-with-copy">
				<input type="text" name="longurl" id="longurl" onclick="this.select();" onload="this.select();" value="<?php echo $url; ?>">
				<button data-clipboard-target="#longurl" class="copy-button button" title="<?php yourls_e( 'Copy to clipboard', 'isq_translation' ); ?>"><img src="public/images/clippy.svg"></button>
				<div class="copy-message success" id="copy-success"><?php yourls_e( 'Copied to clipboard', 'isq_translation' ); ?></div>
				<div class="copy-message error" id="copy-error">
					<span class="os macos"><?php yourls_e( 'Press ⌘+C to copy', 'isq_translation' ); ?></span>
					<span class="os pc"><?php yourls_e( 'Press Ctrl+C to copy', 'isq_translation' ); ?></span>
					<span class="os mobile"><?php yourls_e( 'Tap copy', 'isq_translation' ); ?></span>
					<span class="os other"><?php yourls_e( 'Failed to copy', 'isq_translation' ); ?></span>
				</div>
			</div>
		</div>

		<div class="halves">
			<div class="form-item half-width left">
				<label for="shorturl" class="primary"><?php yourls_e( 'Short URL', 'isq_translation'); ?></label>
				<div class="input-with-copy">
					<input type="text" name="shorturl" id="shorturl" onclick="this.select();" value="<?php echo $shorturl; ?>">
					<button data-clipboard-target="#shorturl" class="copy-button button" title="<?php yourls_e( 'Copy to clipboard', 'isq_translation' ); ?>"><img src="public/images/clippy.svg"></button>
					<div class="copy-message success" id="copy-success"><?php yourls_e( 'Copied to clipboard', 'isq_translation' ); ?></div>
					<div class="copy-message error" id="copy-error">
						<span class="os macos"><?php yourls_e( 'Press ⌘+C to copy', 'isq_translation' ); ?></span>
						<span class="os pc"><?php yourls_e( 'Press Ctrl+C to copy', 'isq_translation' ); ?></span>
						<span class="os mobile"><?php yourls_e( 'Tap copy', 'isq_translation' ); ?></span>
						<span class="os other"><?php yourls_e( 'Failed to copy', 'isq_translation' ); ?></span>
					</div>
				</div>
			</div>
			
			<div class="form-item half-width right">
				<label for="stats" class="primary"><?php /* translators: This is short for statistics */ yourls_e( 'Stats', 'isq_translation'); ?></label>
				<div class="input-with-copy">
					<input type="text" name="stats" id="stats" onclick="this.select();" value="<?php echo $shorturl . '+'; ?>" id="stats-copy">
					<button data-clipboard-target="#stats" class="copy-button button" title="<?php yourls_e( 'Copy to clipboard', 'isq_translation' ); ?>"><img src="public/images/clippy.svg"></button>
					<div class="copy-message success" id="copy-success"><?php yourls_e( 'Copied to clipboard', 'isq_translation' ); ?></div>
					<div class="copy-message error" id="copy-error">
						<span class="os macos"><?php yourls_e( 'Press ⌘+C to copy', 'isq_translation' ); ?></span>
						<span class="os pc"><?php yourls_e( 'Press Ctrl+C to copy', 'isq_translation' ); ?></span>
						<span class="os mobile"><?php yourls_e( 'Tap copy', 'isq_translation' ); ?></span>
						<span class="os other"><?php yourls_e( 'Failed to copy', 'isq_translation' ); ?></span>
					</div>
				</div>
			</div>
		</div>
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

			if ( ISQ::$social['googleplus'] ) { echo '<span onclick="window.open(\'https://plus.google.com/share?url=' . $encoded_shorturl . '\',\'_blank\',\'width=550,height=380\')" class="button social-button googleplus" title="Share on Google+">' . file_get_contents('public/images/googleplus.svg') . '</span>'; }
			
			if ( ISQ::$social['vk'] ) { echo '<span onclick="window.open(\'https://vk.com/share.php?url=' . $encoded_shorturl . '\',\'_blank\',\'width=550,height=380\')" class="button social-button vk" title="Share on VK">' . file_get_contents('public/images/vk.svg') . '</span>'; }
		?>		
	</div>

	<?php if ( ISQ::$general['qr'] ) : ?>
		<!-- QR code -->
		<h2><?php yourls_e( 'QR code', 'isq_translation' ); ?></h2>
		<p><?php yourls_e( 'Share your link with external devices', 'isq_translation' ); ?></p>
	
	<?php
		// PHP QR Code is LGPL licensed
		include('public/phpqrcode/qrlib.php');

		echo QRcode::svg( $shorturl, 'url-qr-code', false, QR_ECLEVEL_L, '600' );
	endif; ?>

</div>

<?php include('footer.php'); ?>
