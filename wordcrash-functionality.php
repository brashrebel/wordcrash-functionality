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
add_filter( 'gform_get_input_value_3_1', 'wc_host_id_to_email', 10 );
function wc_host_id_to_email( $value ) {
	$email = get_userdata( $value );
	$email = $email->user_email;
	return $email;
}

/**
 * Add pets and capacity user meta fields
 */
add_action( 'show_user_profile', 'wc_add_new_user_fields'  );
add_action( 'edit_user_profile', 'wc_add_new_user_fields'  );

function wc_add_new_user_fields( $user ) {
	?>
	<h3>Host details</h3>

	<table class="form-table">
		<tr>
			<th><label for="country">Country</label></th>
			<td>
				<input type="text" name="country" id="country" value="<?php echo esc_attr( get_the_author_meta( 'country', $user->ID ) ); ?>" class="regular-text" /><br />
			</td>
		</tr>
		<tr>
			<th><label for="state">State</label></th>
			<td>
				<input type="text" name="state" id="state" value="<?php echo esc_attr( get_the_author_meta( 'state', $user->ID ) ); ?>" class="regular-text" /><br />
			</td>
		</tr>
		<tr>
			<th><label for="city">City</label></th>
			<td>
				<input type="text" name="city" id="city" value="<?php echo esc_attr( get_the_author_meta( 'city', $user->ID ) ); ?>" class="regular-text" /><br />
			</td>
		</tr>
		<tr>
			<th><label for="capacity">Capacity</label></th>
			<td>
				<input type="text" name="capacity" id="capacity" value="<?php echo esc_attr( get_the_author_meta( 'capacity', $user->ID ) ); ?>" class="regular-text" /><br />

				<span class="description">Roughly how many visitors could you accommodate?</span>
			</td>
		</tr>
		<tr>
			<th><label for="pets">Pets</label></th>

			<td>
				<input type="text" name="pets" id="pets" value="<?php echo esc_attr( get_the_author_meta( 'pets', $user->ID ) ); ?>" class="regular-text" />
				<br/>
				<span class="description">Please list which pets you have.</span>
			</td>
		</tr>

	</table>
<?php
}

/**
 * Save new user meta fields pets and capacity
 */
add_action( 'personal_options_update', 'wc_save_new_user_fields' );
add_action( 'edit_user_profile_update', 'wc_save_new_user_fields' );

function wc_save_new_user_fields( $user_id ) {

	if ( !current_user_can( 'edit_user', $user_id ) )
		return false;
	update_user_meta( $user_id, 'country', $_POST['country'] );
	update_user_meta( $user_id, 'state', $_POST['state'] );
	update_user_meta( $user_id, 'city', $_POST['city'] );
	update_user_meta( $user_id, 'pets', $_POST['pets'] );
	update_user_meta( $user_id, 'capacity', $_POST['capacity'] );
}