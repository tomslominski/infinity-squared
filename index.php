<?php
		include('header.php');

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
		$antispam_title = yourls__( 'This is an antispam check. Sorry, we have to do this to prevent the database being overrun by bots!', 'isq_translation');
		$antispam_value = yourls__( 'reCAPTCHA', 'isq_translation');
		$antispam_bot = yourls__( 'Bot', 'isq_translation');
		$antispam_human = yourls__( 'Human', 'isq_translation');
?>

		<h2><? yourls__( 'Enter a new URL to shorten', 'isq_translation'); ?></h2>
		<h3>$site_hover</h3>
		<form method="post" action="result.php">
		<p><label for="url" title="$site_long_hover">$site_long</label> <input type="text" id="url" class="right" name="url" /></p>
		<p><label for="keyword" title="$site_keyword_hover">$site_keyword</label> <input type="text" id="keyword" class="right" name="keyword" /></p>
		<p><label for="title" title="$site_title_hover">$site_title</label> <input type="text" id="title" class="right" name="title" /></p>
		<p><label for="antispam" title="$antispam_title">$antispam_value</label> <span class="right">$captcha</span></p> 
		<p><input type="submit" value="$site_submit" /></p>
		</form>

<?php include('footer.php'); ?>