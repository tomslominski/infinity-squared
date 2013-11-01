<?php include('header.php'); ?>

	<h2><?php yourls_e( 'Enter a new URL to shorten', 'isq_translation'); ?></h2>
	<h3><?php yourls_e( 'Hover over the labels to see more information', 'isq_translation'); ?></h3>
	<form method="post" action="result.php">
		<p><label for="url" title="<?php yourls_e( 'Paste the long URL here', 'isq_translation'); ?>"><?php yourls_e( 'Long URL (required):', 'isq_translation'); ?></label> <input type="text" id="url" class="right" name="url" /></p>
		<p><label for="keyword" title="<?php yourls_e( 'A keyword replaces the default short string', 'isq_translation'); ?>"><?php yourls_e( 'Custom keyword:', 'isq_translation'); ?></label> <input type="text" id="keyword" class="right" name="keyword" /></p>
		<p><label for="title" title="<?php yourls_e( 'Optional title used when sharing a link from YOURLS', 'isq_translation'); ?>"><?php yourls_e( 'Optional title:', 'isq_translation'); ?></label> <input type="text" id="title" class="right" name="title" /></p>
		<p><label for="antispam" title="<?php yourls_e( 'This is an antispam check. Sorry, we have to do this to prevent the database being overrun by bots!', 'isq_translation'); ?>"><?php yourls_e( 'reCAPTCHA', 'isq_translation'); ?></label> <span class="right"></span></p> 
		<p><input type="submit" value="<?php yourls_e( 'Shorten', 'isq_translation'); ?>" /></p>
	</form>

<?php include('footer.php'); ?>
