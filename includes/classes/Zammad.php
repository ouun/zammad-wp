<?php

namespace ZammadWp;

use ZammadAPIClient\Client;
use ZammadWp\Zammad\Group;
use ZammadWp\Zammad\Organization;
use ZammadWp\Zammad\Ticket;
use ZammadWp\Zammad\User;

class Zammad
{
    private $username;
    private $password;
    private $url;

    public function __construct()
    {
        $this->username   = defined('ZAMMAD_USERNAME') ? ZAMMAD_USERNAME : null;
        $this->password   = defined('ZAMMAD_PASSWORD') ? ZAMMAD_PASSWORD : null;
        $this->http_token = defined('ZAMMAD_HTTP_TOKEN') ? ZAMMAD_HTTP_TOKEN : null;
        $this->auth_token = defined('ZAMMAD_AUTH_TOKEN') ? ZAMMAD_AUTH_TOKEN : null;
        $this->url        = defined('ZAMMAD_URL') ? ZAMMAD_URL : null;
        $this->onBehalf   = defined('ZAMMAD_ON_BEHALF_USER') ? ZAMMAD_ON_BEHALF_USER : false;
        $this->debug      = defined('ZAMMAD_DEBUG') ? ZAMMAD_DEBUG : false;
        $this->timeout    = defined('ZAMMAD_TIMEOUT') ? ZAMMAD_TIMEOUT : 15;
    }

    protected function client()
    {
        $client = new Client([
            'url'           => $this->url,
            'username'      => $this->username,
            'password'      => $this->password,
            'http_token'    => $this->http_token,
            'oauth2_token'  => $this->auth_token
        ]) ? : null;

        if (!empty($this->onBehalf)) {
            $client->setOnBehalfOfUser($this->onBehalf) ? : null;
        }

        if ($this->debug === 'true') {
            $client->debug = true;
        }

        if (!empty($this->timeout)) {
            $client->timeout = (int) $this->timeout;
        }
        return $client;
    }

    public static function ticket()
    {
        return new Ticket();
    }

    public static function user()
    {
        return new User();
    }

    public static function group()
    {
        return new Group();
    }

    public static function organization()
    {
        return new Organization();
    }

    public static function search($type, $string, $page = null, $objects_per_page = null)
    {
        switch ($type) {
            case 'ticket':
                return self::ticket()->searchTickets($string, $page, $objects_per_page);
                break;
            case 'user':
                return self::user()->searchUsers($string, $page, $objects_per_page);
                break;
            case 'organization':
                return self::organization()->searchOrganizations($string, $page, $objects_per_page);
                break;
            default:
                return 'Unsupported resource type: ' . '"' . $type . '"';
        }
    }

    public static function all($type, $page = null, $objects_per_page = null)
    {
        switch ($type) {
            case 'user':
                return self::user()->allUsers($page, $objects_per_page);
                break;
            case 'group':
                return self::group()->allGroups($page, $objects_per_page);
                break;
            case 'organization':
                return self::organization()->allOrganizations($page, $objects_per_page);
                break;
            case 'ticket':
                return self::ticket()->allTickets($page, $objects_per_page);
                break;
            case 'ticket_priority':
                return self::ticket()->allTicketPriorities($page, $objects_per_page);
                break;
            case 'ticket_state':
                return self::ticket()->allTicketStates($page, $objects_per_page);
                break;
            default:
                return 'Unsupported resource type: ' . '"' . $type . '"';
        }
    }

    public static function create($type, $array)
    {
        switch ($type) {
            case 'ticket':
                self::ticket()->createTicket($array);
                break;
            case 'organization':
                self::organization()->createOrganization($array);
                break;
            case 'ticket_priority':
                self::ticket()->createTicketPriority($array);
                break;
            case 'ticket_state':
                self::ticket()->createTicketState($array);
                break;
            case 'ticket_article':
                self::ticket()->createTicketArticle($array);
                break;
            case 'user':
                self::user()->createUser($array);
                break;
            case 'group':
                self::group()->createGroup($array);
                break;
            default:
                return 'Unsupported resource type: ' . '"' . $type . '"';
        }
    }

    public static function find($type, $id)
    {
        switch ($type) {
            case 'ticket':
                return self::ticket()->findTicket($id);
                break;
            case 'organization':
                return self::organization()->findOrganization($id);
                break;
            case 'ticket_priority':
                return self::ticket()->findTicketPriority($id);
                break;
            case 'ticket_state':
                return self::ticket()->findTicketState($id);
                break;
            case 'ticket_article':
                return self::ticket()->findTicketArticle($id);
                break;
            case 'user':
                return self::user()->findUser($id);
                break;
            case 'group':
                return self::group()->findGroup($id);
                break;
            default:
                return 'Unsupported resource type: ' . '"' . $type . '"';
        }
    }

    public static function update($type, $id, $array)
    {
        switch ($type) {
            case 'ticket':
                return self::ticket()->updateTicket($id, $array);
                break;
            case 'organization':
                return self::organization()->updateOrganization($id, $array);
                break;
            case 'ticket_priority':
                return self::ticket()->updateTicketPriority($id, $array);
                break;
            case 'ticket_state':
                return self::ticket()->updateTicketState($id, $array);
                break;
            case 'ticket_article':
                return self::ticket()->updateTicketArticle($id, $array);
                break;
            case 'user':
                return self::user()->updateUser($id, $array);
                break;
            case 'group':
                return self::group()->updateGroup($id, $array);
                break;
            default:
                return 'Unsupported resource type: ' . '"' . $type . '"';
        }
    }

    public static function delete($type, $id)
    {
        switch ($type) {
            case 'ticket':
                return self::ticket()->deleteTicket($id);
                break;
            case 'organization':
                return self::organization()->deleteOrganization($id);
                break;
            case 'ticket_priority':
                return self::ticket()->deleteTicketPriority($id);
                break;
            case 'ticket_state':
                return self::ticket()->deleteTicketState($id);
                break;
            case 'ticket_article':
                return self::ticket()->deletTicketArticle($id);
                break;
            case 'user':
                return self::user()->deleteUser($id);
                break;
            case 'group':
                return self::group()->deleteGroup($id);
                break;
            default:
                return 'Unsupported resource type: ' . '"' . $type . '"';
        }
    }
}
