<?php

namespace ZammadWp;

use ZammadAPIClient\Client;
use ZammadWp\Zammad\Group;
use ZammadWp\Zammad\Organization;
use ZammadWp\Zammad\Ticket;
use ZammadWp\Zammad\User;

class Zammad
{

    public $url;
    private $username;
    private $password;
    private $http_token;
    private $auth_token;

    public $onBehalf;
    public $debug;
    public $timeout;

    private $options;

    /**
     * Zammad constructor.
     *
     * @param array $options
     *
     * @return Client Client object
     */
    public function __construct(array $options = [])
    {
        $this->options    = $options;

        $this->url        = defined('ZAMMAD_URL') ? ZAMMAD_URL :
            ( isset($options['url']) ? $options['url'] : null );
        $this->username   = defined('ZAMMAD_USERNAME') ? ZAMMAD_USERNAME :
            ( isset($options['username']) ? $options['username'] : null );
        $this->password   = defined('ZAMMAD_PASSWORD') ? ZAMMAD_PASSWORD :
            ( isset($options['password']) ? $options['password'] : null );
        $this->http_token = defined('ZAMMAD_HTTP_TOKEN') ? ZAMMAD_HTTP_TOKEN :
            ( isset($options['http_token']) ? $options['http_token'] : null );
        $this->auth_token = defined('ZAMMAD_AUTH_TOKEN') ? ZAMMAD_AUTH_TOKEN :
            ( isset($options['oauth2_token']) ? $options['oauth2_token'] : null );

        $this->onBehalf   = isset($options['on_behalf_user']) ? $options['on_behalf_user'] :
            ( defined('ZAMMAD_ON_BEHALF_USER') ? ZAMMAD_ON_BEHALF_USER : null );
        $this->debug      = isset($options['debug']) ? $options['debug'] :
            ( defined('ZAMMAD_DEBUG') ? ZAMMAD_DEBUG : false );
        $this->timeout    = isset($options['timeout']) ? $options['timeout'] :
            ( defined('ZAMMAD_TIMEOUT') ? ZAMMAD_TIMEOUT : '15' );

        return $this->client();
    }

    /**
     * Client-Wrapper for Zammad connection
     *
     * @return Client Client Object
     */
    protected function client()
    {
        $client = new Client(
            array(
                'url'          => $this->url,
                'username'     => $this->username,
                'password'     => $this->password,
                'http_token'   => $this->http_token,
                'oauth2_token' => $this->auth_token,
                'timeout'      => $this->timeout,
                'debug'        => $this->debug
            )
        );

        $client->unsetOnBehalfOfUser();

        if ($this->onBehalf) {
            $client->setOnBehalfOfUser($this->onBehalf);
        }

        return $client;
    }

    public function ticket()
    {
        return new Ticket($this->options);
    }

    public function user()
    {
        return new User($this->options);
    }

    public function group()
    {
        return new Group($this->options);
    }

    public function organization()
    {
        return new Organization($this->options);
    }

    public function search($type, $string, $page = null, $objects_per_page = null)
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

    public function all($type, $page = null, $objects_per_page = null)
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

    public function create($type, $array)
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

    public function find($type, $id)
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

    public function update($type, $id, $array)
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

    public function delete($type, $id)
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
                return self::ticket()->deleteTicketArticle($id);
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
