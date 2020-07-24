<?php

/**
 * Core plugin functionality.
 *
 * @package ZammadWp
 */

namespace ZammadWp;

use ZammadWp\Zammad\Ticket;
use ZammadWp\Zammad\User;

add_action('admin_init', function () {

    $ticket_text = 'API test ticket';

    //
    // Delete test users and tickets
    //
    $tickets = Zammad::ticket()->allTickets();
    if ($tickets) {
        foreach ($tickets as $ticket) {
            echo 'delete: ' . $ticket->getID();
            $ticket->delete();
        }
    }

    $users = Zammad::user()->searchUsers('example.com');
    foreach ($users as $user) {
        echo 'delete: ' . $user->getID();
        $user->delete();
    }

    //
    // Create a user
    //
    $email_address = 'api_test4@example.com';

    $user = Zammad::user()->createUser([
        'login' => $email_address,
        'email' => $email_address,
    ]);

    echo '<pre>';
    echo var_dump($user);
    echo '</pre>';

    $user_id = $user->getID(); // same as getValue('id')
    echo '<h1>created: ' . $user_id . '</h1>';

    //
    // Fetch user
    //
    $user = Zammad::user()->findUser($user_id);
    echo '<pre>';
    echo var_dump($user);
    echo '</pre>';

    //
    // Search user
    //
    $users = Zammad::user()->searchUsers($email_address);
    echo 'Found ' . count($users) . ' user(s) with email address ' . $email_address . "\n";

    $ticket_data = [
        'group_id'    => 1,
        'priority_id' => 1,
        'state_id'    => 1,
        'title'       => $ticket_text,
        'customer_id' => $user_id,
        'article'     => [
            'subject' => $ticket_text,
            'body'    => $ticket_text,
        ],
    ];
    $ticket = Zammad::ticket()->createTicket($ticket_data);

    if ($ticket) {
        $ticket_id = $ticket->getID(); // same as getValue('id')
    }

    //
    // Fetch ticket
    //
    $ticket = Zammad::ticket()->findTicket($ticket_id);
    echo '<pre>';
    echo var_dump($ticket->getValues());
    echo '</pre>';

    //
    // Fetch ticket articles
    //
    $ticket_articles = $ticket->getTicketArticles();
    foreach ($ticket_articles as $ticket_article) {
        echo '<pre>';
        echo var_dump($ticket_article);
        echo '</pre>';
    }

    //
    // Search ticket
    //
    $tickets = Zammad::ticket()->searchTickets($ticket_text);
    echo 'Found ' . count($tickets) . ' ticket(s) with text ' . $ticket_text . "\n";

    //
    // Delete created ticket
    //
    $ticket->delete();

    //
    // Delete created user
    //
    $user->delete();

    wp_die('done');
});
