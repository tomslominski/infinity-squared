<?php
var_dump($_REQUEST);
include('header.php');

		// antispam
		// if ( isset($_POST['antispam']) && $_POST['antispam'] != user ) {
		// 	exit('<p class="error">' . yourls__( 'Antispam check failed!', 'isq_translation') . '</p>'); 
		// }

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

?>
		$error
		<div class="output">
		<p><label for="longurl"><? yourls__( 'Original URL:', 'isq_translation'); ?></label> <input type="text" name="longurl" onclick="this.select();" onload="this.select();" value="<?php echo $url; ?>" id="long-copy"> $ISQlongcopy </p>
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

<?php
	include('footer.php');
?>