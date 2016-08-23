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
    
    $states = wc_get_states_array();
    
    if ( array_key_exists( strtoupper( $_POST['state'] ), $states ) ) {
            
        $_POST['state'] = $states[ strtoupper( $_POST['state'] ) ];

    }
    
    $_POST['country'] = preg_replace( '/(?:The\s)?United\sStates(?:\sof\sAmerica)?/i', 'USA', $_POST['country'] );
    if ( strtolower( $_POST['country'] ) == 'us' || $_POST['country'] == 'usa' ) $_POST['country'] = 'USA'; // To grab those outliers
    
	update_user_meta( $user_id, 'country', $_POST['country'] );
	update_user_meta( $user_id, 'state', $_POST['state'] );
	update_user_meta( $user_id, 'city', $_POST['city'] );
	update_user_meta( $user_id, 'pets', $_POST['pets'] );
	update_user_meta( $user_id, 'capacity', $_POST['capacity'] );
    
}

// Ensure uniformity for USA and States
add_action( 'gform_pre_submission_2', 'wc_sanitize_countries_states' );
function wc_sanitize_countries_states( $form ) {
    
    $states = wc_get_states_array();
    
    foreach ( $form['fields'] as &$field ) {
        
        // Skip to the next one if it isn't what we want
        if ( $field->label !== 'State/Province' && $field->label !== 'Country' ) {
            continue;
        }
        
        if ( $field->label == 'State/Province' ) {
            
            if ( array_key_exists( strtoupper( $_POST[ 'input_' . $field->id ] ) ) ) {
            
                $_POST[ 'input_' . $field->id ] = $states[ strtoupper( $_POST[ 'input_' . $field->id ] ) ];
                
            }
            
        }
        else if ( $field->label == 'Country' ) {
            
            $_POST[ 'input_' . $field->id ] = preg_replace( '/(?:The\s)?United\sStates(?:\sof\sAmerica)?/i', 'USA', $_POST[ 'input_' . $field->id ] );
            if ( strtolower( $_POST[ 'input_' . $field->id ] ) == 'us' || $_POST[ 'input_' . $field->id ] == 'usa' ) $_POST[ 'input_' . $field->id ] = 'USA'; // To grab those outliers
            
        }
        
    }
    
}

// No sense in having this twice
function wc_get_states_array() {
    
    $states = array(
        'AL' => 'Alabama',
        'AK' => 'Alaska',
        'AZ' => 'Arizona',
        'AR' => 'Arkansas',
        'CA' => 'California',
        'CO' => 'Colorado',
        'CT' => 'Connecticut',
        'DE' => 'Delaware',
        'DC' => 'District of Columbia',
        'FL' => 'Florida',
        'GA' => 'Georgia',
        'HI' => 'Hawaii',
        'ID' => 'Idaho',
        'IL' => 'Illinois',
        'IN' => 'Indiana',
        'IA' => 'Iowa',
        'KS' => 'Kansas',
        'KY' => 'Kentucky',
        'LA' => 'Louisiana',
        'ME' => 'Maine',
        'MD' => 'Maryland',
        'MA' => 'Massachusetts',
        'MI' => 'Michigan',
        'MN' => 'Minnesota',
        'MS' => 'Mississippi',
        'MO' => 'Missouri',
        'MT' => 'Montana',
        'NE' => 'Nebraska',
        'NV' => 'Nevada',
        'NH' => 'New Hampshire',
        'NJ' => 'New Jersey',
        'NM' => 'New Mexico',
        'NY' => 'New York',
        'NC' => 'North Carolina',
        'ND' => 'North Dakota',
        'OH' => 'Ohio',
        'OK' => 'Oklahoma',
        'OR' => 'Oregon',
        'PA' => 'Pennsylvania',
        'RI' => 'Rhode Island',
        'SC' => 'South Carolina',
        'SD' => 'South Dakota',
        'TN' => 'Tennessee',
        'TX' => 'Texas',
        'UT' => 'Utah',
        'VT' => 'Vermont',
        'VA' => 'Virginia',
        'WA' => 'Washington',
        'WV' => 'West Virginia',
        'WI' => 'Wisconsin',
        'WY' => 'Wyoming',
    );
    
    return $states;
    
}