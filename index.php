<?php include('header.php');

$url     = isset( $_REQUEST['url'] ) ? yourls_sanitize_url( $_REQUEST['url'] ) : '' ;
$keyword = isset( $_REQUEST['keyword'] ) ? yourls_sanitize_keyword( $_REQUEST['keyword'] ) : '' ;
$title   = isset( $_REQUEST['title'] ) ? yourls_sanitize_title( $_REQUEST['title'] ) : '' ;

?>

<h2><?php yourls_e( 'Enter a new URL to shorten', 'isq_translation'); ?></h2>
<h3><?php yourls_e( 'Hover over the labels to see more information', 'isq_translation'); ?></h3>
<form method="post" action="result.php">
	<p><label for="url" title="<?php yourls_e( 'Paste the long URL here', 'isq_translation'); ?>"><?php yourls_e( 'Long URL (required):', 'isq_translation'); ?></label> <input type="text" id="url" class="right" name="url" value="<?php echo($url); ?>" /></p>
	<p><label for="keyword" title="<?php yourls_e( 'A keyword replaces the default short string', 'isq_translation'); ?>"><?php yourls_e( 'Custom keyword:', 'isq_translation'); ?></label> <input type="text" id="keyword" class="right" name="keyword" value="<?php echo($keyword); ?>" /></p>
	<p><label for="title" title="<?php yourls_e( 'Optional title used when sharing a link from YOURLS using social sharers', 'isq_translation'); ?>"><?php yourls_e( 'Optional title:', 'isq_translation'); ?></label> <input type="text" id="title" class="right" name="title" value="<?php echo($title); ?>" /></p>
	<div class="recaptcha-container"><label title="<?php yourls_e( 'Verification used to ensure the user is not a bot', 'isq_translation'); ?>"><?php yourls_e( 'reCAPTCHA:', 'isq_translation'); ?></label> <div class="g-recaptcha right" data-sitekey="<?php echo ISQ::$recaptcha['sitekey']; ?>"></div></div>

	<p><input type="submit" value="<?php yourls_e( 'Shorten', 'isq_translation'); ?>" /></p>
</form>

<?php include('footer.php'); ?>
