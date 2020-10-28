<?php
/**
 * Integrate Zammad-WP as Action into
 * Ibericode's awesome zammad-wp Plugin
 * @see https://github.com/ibericode/zammad-wp
 */


use ZammadWp\Zammad;

/**
 * Add new action to HTML Forms Plugin
 */
add_filter('hf_available_form_actions', function( $actions ) {

	$actions['zammad'] = __('Create Zammad Ticket', 'zammad-wp');

	return $actions;

}, 20);

/**
 * Render Settings form
 */
add_action('hf_output_form_action_zammad_settings', function($settings, $index) {

	$settings = array_merge( zammad_hf_default_settings(), $settings );

	/*
	if (!defined('ZAMMAD_URL') || !defined('ZAMMAD_HTTP_TOKEN')  || !defined('ZAMMAD_AUTH_TOKEN')) {
		echo __('We need API access. You need to define Zammad Authentication in you wp-config.php as per the install instructions.');
		return;
	}
	*/

	$groups = Zammad::all('group');
	$ticket_priority = Zammad::all('ticket_priority');
	$ticket_state = Zammad::all('ticket_state');


	?>
	<span class="hf-action-summary"><?php printf( 'From %s. To %s.', $settings['title'], $settings['article']['content_type'] ); ?></span>
	<input type="hidden" name="form[settings][actions][<?php echo $index; ?>][type]" value="zammad" />

	<table class="form-table">
		<tr>
			<th><label><?php echo __( 'Zammad Group', 'zammad-wp' ); ?> <span class="hf-required">*</span></label></th>
			<td>
				<select name="form[settings][actions][<?php echo $index; ?>][group_id]" id="group_id" required>
					<?php foreach ($groups as $group) {
						$selected = $settings['group_id'] == $group->getValue('id') ? 'selected' : '';
						echo '<option value="' . $group->getValue('id') . '" ' . $selected . '>' . $group->getValue('name') . '</option>';
					} ?>
				</select>
			</td>
		</tr>
		<tr>
			<th><label><?php echo __( 'Ticket Priority', 'zammad-wp' ); ?> <span class="hf-required">*</span></label></th>
			<td>
				<select name="form[settings][actions][<?php echo $index; ?>][priority_id]" id="priority_id" required>
					<?php foreach ($ticket_priority as $priority) {
						$selected = $settings['priority_id'] == $priority->getValue('id') ? 'selected' : '';
						echo '<option value="' . $priority->getValue('id') . '" ' . $selected . '>' . $priority->getValue('name') . '</option>';
					} ?>
				</select>
			</td>
		</tr>
		<tr>
			<th><label><?php echo __( 'Ticket State', 'zammad-wp' ); ?> <span class="hf-required">*</span></label></th>
			<td>
				<select name="form[settings][actions][<?php echo $index; ?>][state_id]" id="state_id" required>
					<?php foreach ($ticket_state as $state) {
						$selected = $settings['state_id'] == $state->getValue('id') ? 'selected' : '';
						echo '<option value="' . $state->getValue('id') . '" ' . $selected . '>' . $state->getValue('name') . '</option>';
					} ?>
				</select>
			</td>
		</tr>
		<tr>
			<th><label><?php echo __( 'Ticket Subject', 'zammad-wp' ); ?></label></th>
			<td>
				<input name="form[settings][actions][<?php echo $index; ?>][subject]" value="<?php echo esc_attr( $settings['subject'] ); ?>" type="text" class="regular-text" placeholder="<?php echo esc_attr( $settings['subject'] ); ?>" />
				<p class="help"><?php _e( 'Define a [SUBJECT] field and the input will overwrite this.', 'zammad-wp' ); ?></p>
			</td>
		</tr>
		<tr>
			<th><label><?php echo __( 'Message', 'zammad-wp' ); ?> <span class="hf-required">*</span></label></th>
			<td>
				<textarea name="form[settings][actions][<?php echo $index; ?>][message]" rows="8" class="widefat" placeholder="[MESSAGE]"><?php echo esc_textarea( $settings['message'] ); ?></textarea>
				<p class="help">
					<?php _e( 'This allows you to customize the ticket body. ', 'zammad-wp' ); ?>
					<?php _e( 'You can use the following variables (in all fields): ', 'zammad-wp' ); ?>
					<br /><span class="hf-field-names"></span>
				</p>
			</td>
		</tr>
		<tr>
			<th><label><?php echo __( 'Tags', 'zammad-wp' ); ?></label></th>
			<td>
				<input name="form[settings][actions][<?php echo $index; ?>][tags]" value="<?php echo esc_attr( $settings['tags'] ); ?>" type="text" class="regular-text" />
				<p class="help"><?php _e( 'Comma separated list of tags.', 'zammad-wp' ); ?></p>
			</td>
		</tr>
	</table>
	<?php
}, 20, 2);

/**
 * Process Action Function
 */
add_action( 'hf_process_form_action_zammad', 'zammad_hf_process_form_action', 10, 3);
if( !function_exists('zammad_hf_process_form_action') ) {
	function zammad_hf_process_form_action(
			array $settings,
			HTML_Forms\Submission $submission,
			HTML_Forms\Form $form
	) {

		if ( empty( $settings['group_id'] ) ) {
			return false;
		}

		// Get action settings
		$settings = array_merge( zammad_hf_default_settings(), $settings );

		// Get form submission values, uppercase all array keys as HF does
		$data = array_change_key_case($submission->data, CASE_UPPER);

		// Find email field
		$email_address = '';

		// Presort essential fields
		foreach ( $data as $field => $value ) {
			if ( is_email( $value ) ) {
				$email_address = $value;
			} elseif ( hf_is_file( $value ) ) {
				$files[] = $value;
			}
		}

		// Bail if no email address found
		if ( empty( $email_address ) ) {
			return false;
		}

		// Handle Name Fields
		$firstname = hf_array_get( $data, 'FIRSTNAME', '' );
		$lastname  = hf_array_get( $data, 'LASTNAME', '' );

		if ( empty( $fistname ) && empty( $lastname ) ) {
			$name = hf_array_get( $data, 'NAME', '' );
			list( $firstname, $lastname ) = explode( ' ', $name, 2 );
		}

		// Search for existing user in Zammad
		// @see: https://docs.zammad.org/en/latest/api/user.html
		$users = Zammad::search( 'user', $email_address );

		// @see: https://docs.zammad.org/en/latest/api/user.html#create
		$user_meta = apply_filters( 'zammad_wp:hf_action:user_fields', (array) [
				'firstname' => $firstname,
				'lastname'  => $lastname,
		], $submission );

		if ( $users ) {
			// Get first user result & update data
			$user = $users{0}->setValues( $user_meta );
			// Get user id
			$user_id = $users{0}->getValue( 'id' );
			// Save additional data
			$user->save();
		} else {
			// No user found, create a new one
			$user_id = Zammad::user()->createUser( array_merge( [
					'login' => $email_address,
					'email' => $email_address,
			], $user_meta ) )->getValue( 'id' );
		}

		// Prepare ticket values
		// @see: https://docs.zammad.org/en/latest/api/ticket.html#create
		$subject = apply_filters( 'zammad_wp:hf_action:subject',
				hf_replace_data_variables( $settings['subject'], $data, 'strip_tags' ), $submission );
		$message = apply_filters( 'zammad_wp:hf_action:message',
				hf_replace_data_variables( $settings['message'], $data, 'esc_html' ), $submission );
		$tags    = apply_filters( 'zammad_wp:hf_action:tags',
				hf_replace_data_variables( $settings['tags'], $data, 'strip_tags' ), $submission );

		// Prepare Ticket
		$ticket = apply_filters( 'zammad_wp:hf_action:ticket_fields', [
				'title'       => $subject,
				'customer_id' => $user_id,
				// 'customer_id' => 'guess:' . $email_address,
				'group_id'    => (string) $settings['group_id'],
				'priority_id' => (string) $settings['priority_id'],
				'state_id'    => (string) $settings['state_id'],
				'tags'        => $tags
		], $submission );

		// Prepare Article
		$ticket['article'] = apply_filters( 'zammad_wp:hf_action:article_fields', [
				'to'			=> $firstname . ' ' . $lastname . ' <' . $email_address . '>',
				'subject'		=> $subject,
				//'body'			=> $data['MESSAGE'] ?: $message,
				'body'			=> $message,
				'content_type'	=> 'text/html',
				'type'			=> 'web',
				'internal'		=> false,
		], $submission );

		// Create the ticket
		$ticket = Zammad::ticket()->createTicket( $ticket );

		// Ticket creation success/error
		if ( is_int( $ticket ) ) {
			return $ticket;
		} else {
			return false;
		}
	}
}

function zammad_hf_default_settings() {
	return array(
		// Ticket defaults
		'title'			=> '[SUBJECT]',
		'group_id'		=> null,
		'state_id'		=> null,
		'priority_id'	=> null,
		'customer_id'	=> null,

		// Article defaults
		'subject'		=> '[SUBJECT]',
		'message'		=> '[MESSAGE]',

		// Meta defaults
		'tags'			=> '',
	);
}
