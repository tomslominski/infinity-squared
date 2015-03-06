<?php include('header.php');

$url     = isset( $_REQUEST['url'] ) ? yourls_sanitize_url( $_REQUEST['url'] ) : '' ;
$keyword = isset( $_REQUEST['keyword'] ) ? yourls_sanitize_keyword( $_REQUEST['keyword'] ) : '' ;
$title   = isset( $_REQUEST['title'] ) ? yourls_sanitize_title( $_REQUEST['title'] ) : '' ;

?>

<div class="content">
	<h2><?php yourls_e( 'Enter a new URL to shorten', 'isq_translation'); ?></h2>
	<h3><?php yourls_e( 'Hover over the labels to see more information', 'isq_translation'); ?></h3>
	<form method="post" action="result.php">
		<div class="form-item full-width">
			<label for="url" class="primary"><?php yourls_e( 'Long URL (required):', 'isq_translation'); ?></label>
			<label for="url" class="secondary"><?php yourls_e( 'Paste the long URL here', 'isq_translation'); ?></label>
			<input type="url" id="url" name="url" value="<?php echo($url); ?>" />
		</div>

		<div class="form-item half-width">
			<label for="keyword" class="primary"><?php yourls_e( 'Custom keyword:', 'isq_translation'); ?></label>
			<label for="keyword" class="secondary"><?php yourls_e( 'A keyword replaces the default short string', 'isq_translation'); ?></label>
			<input type="text" id="keyword" name="keyword" value="<?php echo($keyword); ?>" />
		</div>

		<div class="form-item half-width">
			<label for="title" class="primary"><?php yourls_e( 'Optional title:', 'isq_translation'); ?></label>
			<label for="title" class="secondary"><?php yourls_e( 'Optional title used when sharing a link from YOURLS using social sharers', 'isq_translation'); ?></label>
			<input type="text" id="title" value="<?php echo($title); ?>" />
		</div>

		<div class="form-item recaptcha-container">
			<label class="primary" title=""><?php yourls_e( 'reCAPTCHA:', 'isq_translation'); ?></label>
			<label class="secondary"><?php yourls_e( 'Verification used to ensure the user is not a bot', 'isq_translation'); ?></label>
			<div class="g-recaptcha" data-sitekey="<?php echo ISQ::$recaptcha['sitekey']; ?>"></div>
		</div>

		<p><input type="submit" value="<?php yourls_e( 'Shorten', 'isq_translation'); ?>" /></p>
	</form>
</div>

<?php include('footer.php'); ?>
