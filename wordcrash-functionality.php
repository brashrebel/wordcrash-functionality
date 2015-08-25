<?php
/*
Plugin Name: WordCrash Functionality
Plugin URI: http://github.com/brashrebel/wordcrash-functionality
Description: Adds the necessary functionality to the WordCrash website.
Version: 0.1
Author: WP Ann Arbor
Author URI: http://wpannarbor.com
License: GPL2
*/

/**
 * Convert the hidden field's value from ID to email address
 * First number in filter name is form ID, second is field ID
 */
add_filter( 'gform_get_input_value_2_4', 'wc_host_id_to_email', 10 );

function wc_host_id_to_email( $value ) {
	$email = get_userdata( $value );
	$email = $email->user_email;
	return $email;
}