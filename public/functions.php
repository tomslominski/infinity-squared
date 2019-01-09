<?php

/**
 * Return which method is being used for preventing spam,
 * based on the site config.
 *
 * @return string login/recaptcha_v3/recaptcha/basic
 */
function is_get_antispam_method() {
	if( 1 == yourls_is_valid_user() ) {
		return 'login';
	} elseif( isset( ISQ::$recaptcha_v3['sitekey'] ) && ISQ::$recaptcha_v3['sitekey'] && isset( ISQ::$recaptcha_v3['secret'] ) && ISQ::$recaptcha_v3['secret'] ) {
		return 'recaptcha_v3';
	} elseif( isset( ISQ::$recaptcha['sitekey'] ) && ISQ::$recaptcha['sitekey'] && isset( ISQ::$recaptcha['secret'] ) && ISQ::$recaptcha['secret'] ) {
		return 'recaptcha';
	} else {
		return 'basic';
	}
}


/**
 * Terminate the request without shortening the URL and
 * display an error.
 *
 * @param string $message Error message.
 * @param string $action Action message, defaults to "Go home".
 */
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


/**
 * Get remote file, either using CURL or file_get_contents
 * depending on server configuration.
 *
 * @param string $url Remote file URL.
 * @return string Remote file contents.
 */
function get_remote_file( $url ) {
	if( function_exists( 'curl_init' ) ) {
		$curl = curl_init();
		curl_setopt( $curl, CURLOPT_URL, $url );
		curl_setopt( $curl, CURLOPT_RETURNTRANSFER, true );
		$output = curl_exec( $curl );
		curl_close( $curl );
		return $output;
	} elseif( ini_get( 'allow_url_fopen' ) ) {
		return file_get_contents( $url );
	} else {
		display_error( yourls__( 'Your server doesn\'t support reCAPTCHA. Ask your host to install cURL or turn on allow_url_fopen.', 'isq_translation' ) );
	}
}
