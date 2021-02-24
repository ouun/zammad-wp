<?php

/**
 * Integrate Zammad-WP as Action into
 * HTML Forms Plugin by Ibericode
 * @see https://github.com/ibericode/zammad-wp
 */

use HTML_Forms\Form;
use HTML_Forms\Submission;
use ZammadWp\Zammad;

/**
 * Add new action to HTML Forms Plugin
 */
add_filter('hf_available_form_actions', function ($actions) {
    // Add Zammad Action
    $actions['zammad'] = __('Create Zammad Ticket', 'zammad-wp');
    return $actions;
}, 20);

/**
 * Render Settings form
 */
add_action('hf_output_form_action_zammad_settings', function ($settings, $index) {

    $settings = array_merge(zammad_hf_default_settings(), $settings);

    $zammad = new ZammadWp\Zammad([
        // todo: Allow overwriting options with form settings
    ]);

    $groups = $zammad->group()->allGroups();
    $ticket_priority = $zammad->ticket()->allTicketPriorities();
    $ticket_state = $zammad->ticket()->allTicketStates();

    ?>
    <span class="hf-action-summary"><?php printf('<b>%s</b>: Create ticket with state <b>%s</b> for Group <b>%s</b> with priority <b>%s</b>.', $zammad->url, $settings['state'], $settings['group'], $settings['priority']); ?></span>
    <input type="hidden" name="form[settings][actions][<?php echo $index; ?>][type]" value="zammad" />

    <table class="form-table">
        <tr>
            <th><label for="group"><?php echo __('Zammad Group', 'zammad-wp'); ?> <span class="hf-required">*</span></label></th>
            <td>
                <select name="form[settings][actions][<?php echo $index; ?>][group]" id="group" required>
                    <?php foreach ($groups as $group) {
                        $selected = $settings['group'] == $group->getValue('name') ? 'selected' : '';
                        echo '<option value="' . $group->getValue('name') . '" ' . $selected . '>' . $group->getValue('name') . '</option>';
                    } ?>
                </select>
            </td>
        </tr>
        <tr>
            <th><label for="priority"><?php echo __('Ticket Priority', 'zammad-wp'); ?> <span class="hf-required">*</span></label></th>
            <td>
                <select name="form[settings][actions][<?php echo $index; ?>][priority]" id="priority" required>
                    <?php foreach ($ticket_priority as $priority) {
                        $selected = $settings['priority'] == $priority->getValue('name') ? 'selected' : '';
                        echo '<option value="' . $priority->getValue('name') . '" ' . $selected . '>' . $priority->getValue('name') . '</option>';
                    } ?>
                </select>
            </td>
        </tr>
        <tr>
            <th><label for="state"><?php echo __('Ticket State', 'zammad-wp'); ?> <span class="hf-required">*</span></label></th>
            <td>
                <select name="form[settings][actions][<?php echo $index; ?>][state]" id="state" required>
                    <?php foreach ($ticket_state as $state) {
                        $selected = $settings['state'] == $state->getValue('name') ? 'selected' : '';
                        echo '<option value="' . $state->getValue('name') . '" ' . $selected . '>' . $state->getValue('name') . '</option>';
                    } ?>
                </select>
            </td>
        </tr>
        <tr>
            <th><label for="subject"><?php echo __('Ticket Subject', 'zammad-wp'); ?></label></th>
            <td>
                <input id="subject" name="form[settings][actions][<?php echo $index; ?>][subject]" value="<?php echo esc_attr($settings['subject']); ?>" type="text" class="regular-text" placeholder="<?php echo esc_attr($settings['subject']); ?>" />
                <p class="help"><?php _e('Define a [SUBJECT] field and the input will overwrite this.', 'zammad-wp'); ?></p>
            </td>
        </tr>
        <tr>
            <th><label for="message"><?php echo __('Message', 'zammad-wp'); ?> <span class="hf-required">*</span></label></th>
            <td>
                <textarea id="message" name="form[settings][actions][<?php echo $index; ?>][message]" rows="8" class="widefat" placeholder="[MESSAGE]"><?php echo esc_textarea($settings['message']); ?></textarea>
                <p class="help">
                    <?php _e('This allows you to customize the ticket body. ', 'zammad-wp'); ?>
                    <?php _e('You can use the following variables (in all fields): ', 'zammad-wp'); ?>
                    <br /><span class="hf-field-names"></span>
                </p>
            </td>
        </tr>
        <tr>
            <th><label for="tags"><?php echo __('Tags', 'zammad-wp'); ?></label></th>
            <td>
                <input id="tags" name="form[settings][actions][<?php echo $index; ?>][tags]" value="<?php echo esc_attr($settings['tags']); ?>" type="text" class="regular-text" />
                <p class="help"><?php _e('Comma separated list of tags.', 'zammad-wp'); ?></p>
            </td>
        </tr>
    </table>
    <?php
}, 20, 2);

add_action('hf_process_form_action_zammad', 'zammad_hf_process_form_action', 10, 3);
if (!function_exists('zammad_hf_process_form_action')) {
    /**
     * Process Action Function
     *
     * @param array $settings
     * @param Submission $submission
     * @param Form $form
     *
     * @return false|int
     */
    function zammad_hf_process_form_action($settings, $submission, $form)
    {

        if (empty($settings['group'])) {
            return false;
        }

        // Connect
        $zammad = new ZammadWp\Zammad([
            // todo: Allow overwriting options
        ]);

        // Get action settings
        $settings = array_merge(zammad_hf_default_settings(), $settings);

        // Get form submission values, uppercase all array keys as HF does
        $data = array_change_key_case($submission->data, CASE_UPPER);

        // Find email field
        $email_address = '';

        // Presort essential fields
        foreach ($data as $field => $value) {
            if (is_email($value)) {
                $email_address = $value;
            } elseif (hf_is_file($value)) {
                $files[] = $value;
            }
        }

        // Bail if no email address found
        if (empty($email_address)) {
            return false;
        }

        // Handle Name Fields
        $firstname = hf_array_get($data, 'FIRSTNAME', '');
        $lastname  = hf_array_get($data, 'LASTNAME', '');

        if (empty($fistname) && empty($lastname)) {
            $name = hf_array_get($data, 'NAME', '');
            list( $firstname, $lastname ) = explode(' ', $name, 2);
        }

        // Search for existing user in Zammad
        // @see: https://docs.zammad.org/en/latest/api/user.html
        $users = $zammad->user()->searchUsers($email_address, 1, 1);

        // @see: https://docs.zammad.org/en/latest/api/user.html#create
        $user_meta = apply_filters('zammad_wp:hf_action:user_fields', (array) [
                'firstname' => $firstname,
                'lastname'  => $lastname,
                'preferences' => [
                        'locale' => get_locale()
                ]
        ], $submission);

        if ($users) {
			// Get first user result & update data
			$user = $users[0]->setValues($user_meta);
			// Get user id
			$user_id = $users[0]->getValue('id');
            // Save additional data
            $user->save();
        } else {
            // No user found, create a new one
            $user_id = $zammad->user()->createUser(array_merge([
                    'login' => $email_address,
                    'email' => $email_address,
            ], $user_meta))->getValue('id');
        }

        // Set on behalf
        $customer = new ZammadWp\Zammad([
            'on_behalf_user' => $user_id
        ]);

        // Prepare ticket values
        // @see: https://docs.zammad.org/en/latest/api/ticket.html#create
        $subject = apply_filters(
            'zammad_wp:hf_action:subject',
            hf_replace_data_variables($settings['subject'], $data, 'strip_tags'),
            $submission
        );
        $message = apply_filters(
            'zammad_wp:hf_action:message',
            hf_replace_data_variables($settings['message'], $data, 'esc_html'),
            $submission
        );
        $tags    = apply_filters(
            'zammad_wp:hf_action:tags',
            hf_replace_data_variables($settings['tags'], $data, 'strip_tags'),
            $submission
        );

        // Prepare Ticket
        $ticket = apply_filters('zammad_wp:hf_action:ticket_fields', [
                'title'         => $subject,
                'customer_id'   => $user_id,
                'group'         => (string) $settings['group'],
                'priority'      => (string) $settings['priority'],
                'state'         => (string) $settings['state'],
                'tags'          => $tags
        ], $submission);

        // Prepare Article
        // @see: https://community.zammad.org/t/custom-form-to-create-new-ticket-via-api/2068/2
        $ticket['article'] = apply_filters('zammad_wp:hf_action:article_fields', [
                'sender'        => 'Customer',
                'subject'       => $subject,
                'body'          => $message,
                'content_type'  => 'text/html',
                'type'          => 'web',
                'internal'      => false,
        ], $submission);

        // Create the ticket
        $ticket = $customer->ticket()->createTicket($ticket);

        // Ticket creation success/error
        if (is_int($ticket)) {
            return $ticket;
        } else {
            return false;
        }
    }
}

function zammad_hf_default_settings()
{
    return array(
        // Ticket defaults
        'title'         => '[SUBJECT]',
        'group'         => null,
        'state'         => null,
        'priority'      => null,

        // Article defaults
        'subject'       => '[SUBJECT]',
        'message'       => '[MESSAGE]',

        // Meta defaults
        'tags'          => '',
    );
}
