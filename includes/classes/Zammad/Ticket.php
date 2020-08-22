<?php

namespace ZammadWp\Zammad;

use ZammadAPIClient\Resource\TicketArticle;
use ZammadAPIClient\Resource\TicketPriority;
use ZammadAPIClient\Resource\TicketState;
use ZammadAPIClient\ResourceType;
use ZammadWp\Zammad;

class Ticket extends Zammad
{


    // Ticket functions

    /**
     * @param $string
     * @param null $page
     * @param null $objects_per_page
     *
     * @return \ZammadAPIClient\Resource\Ticket[]|null
     */
    public function searchTickets($string, $page = null, $objects_per_page = null)
    {
        $search       =
        $this->search =
            $this->client()->resource(ResourceType::TICKET)->search($string, $page, $objects_per_page);

        if ($this->search) {
            return $this->search;
        }

        if (! is_array($search)) {
            return $search->getError();
        }

        return null;
    }

    /**
     * @param null $page
     * @param null $objects_per_page
     *
     * @return \ZammadAPIClient\Resource\Ticket[]|null
     */
    public function allTickets($page = null, $objects_per_page = null)
    {
        $tickets = $this->tickets = $this->client()->resource(ResourceType::TICKET)->all($page, $objects_per_page);

        if ($this->tickets) {
            return $this->tickets;
        }

        if ($tickets->hasError()) {
            return $tickets->getError();
        }

        return null;
    }

    /**
     * @param $array
     *
     * @return \ZammadAPIClient\Resource\Ticket|object
     */
    public function createTicket($array)
    {
        $ticket = $this->client()->resource(ResourceType::TICKET);
        foreach ($array as $key => $value) {
            $ticket->setValue($key, $value);
        }

        $ticket->save();

        if ($ticket->hasError()) {
            return $ticket->getError();
        }

        return $ticket;
    }

    /**
     * @param $id
     *
     * @return \ZammadAPIClient\Resource\Ticket|false
     */
    public function findTicket($id)
    {
        $ticket = $this->ticket = $this->client()->resource(ResourceType::TICKET)->get($id);

        if ($this->ticket) {
            return $this->ticket;
        }

        if ($ticket->hasError()) {
            return $ticket->getError();
        }

        return false;
    }

    /**
     * @param $id
     * @param $array
     *
     * @return \ZammadAPIClient\Resource\Ticket|false
     */
    public function updateTicket($id, $array)
    {
        $ticket = $this->client()->resource(ResourceType::TICKET)->get($id);
        foreach ($array as $key => $value) {
            $ticket->setValue($key, $value);
        }

        $ticket->save();

        if ($ticket) {
            return $ticket;
        }

        if ($ticket->hasError()) {
            return $ticket->getError();
        }
    }

    public function deleteTicket($id)
    {
        $ticket = $this->client()->resource(ResourceType::TICKET)->get($id);
        $ticket->delete();
    }

    // ticket articles

    /**
     * @param $array
     *
     * @return TicketArticle|object
     */
    public function createTicketArticle($array)
    {
        $ticketArticle = $this->client()->resource(ResourceType::TICKET_ARTICLE);
        foreach ($array as $key => $value) {
            $ticketArticle->setValue($key, $value);
        }

        $ticketArticle->save();

        if ($ticketArticle->hasError()) {
            return $ticketArticle->getError();
        }

        return $ticketArticle;
    }

    /**
     * @param $id
     *
     * @return TicketArticle[]|null
     */
    public function findTicketArticle($id)
    {
        $ticket_article = $this->ticket_article = $this->client()->resource(ResourceType::TICKET_ARTICLE)->get($id);

        if ($this->ticket_article) {
            return $this->ticket_article;
        }

        if ($ticket_article->hasError()) {
            return $ticket_article->getError();
        }

        return null;
    }

    /**
     * @param $id
     * @param $array
     *
     * @return TicketArticle
     */
    public function updateTicketArticle($id, $array)
    {
        $ticketArticle = $this->client()->resource(ResourceType::TICKET_ARTICLE)->get($id);
        foreach ($array as $key => $value) {
            $ticketArticle->setValue($key, $value);
        }

        $ticketArticle->save();

        if ($ticketArticle) {
            return $ticketArticle;
        }

        if ($ticketArticle->hasError()) {
            return $ticketArticle->getError();
        }
    }

    public function deleteTicketArticle($id)
    {
        $ticketArticle = $this->client()->resource(ResourceType::TICKET_ARTICLE)->get($id);
        $ticketArticle->delete();
    }

    /**
     * @param null $page
     * @param null $objects_per_page
     *
     * @return TicketState[]|null
     */
    public function allTicketStates($page = null, $objects_per_page = null)
    {
        $states = $this->states = $this->client()->resource(ResourceType::TICKET_STATE)->all($page, $objects_per_page);

        if ($this->states) {
            return $this->states;
        }

        if ($states->hasError()) {
            return $states->getError();
        }

        return null;
    }

    /**
     * @param $array
     *
     * @return TicketState|object;
     */
    public function createTicketState($array)
    {
        $ticketState = $this->client()->resource(ResourceType::TICKET_STATE);
        foreach ($array as $key => $value) {
            $ticketState->setValue($key, $value);
        }

        $ticketState->save();

        if ($ticketState->hasError()) {
            return $ticketState->getError();
        }

        return $ticketState;
    }

    /**
     * @param $id
     *
     * @return TicketState[]|null
     */
    public function findTicketState($id)
    {
        $state = $this->state = $this->client()->resource(ResourceType::TICKET_STATE)->get($id);

        if ($this->state) {
            return $this->state;
        }

        if ($state->hasError()) {
            return $state->getError();
        }

        return null;
    }

    /**
     * @param $id
     * @param $array
     *
     * @return TicketState|false
     */
    public function updateTicketState($id, $array)
    {
        $ticketState = $this->client()->resource(ResourceType::TICKET_STATE)->get($id);
        foreach ($array as $key => $value) {
            $ticketState->setValue($key, $value);
        }

        $ticketState->save();

        if ($ticketState) {
            return $ticketState;
        }

        if ($ticketState->hasError()) {
            return $ticketState->getError();
        }

        return false;
    }

    public function deleteTicketState($id)
    {
        $ticketState = $this->client()->resource(ResourceType::TICKET_STATE)->get($id);
        $ticketState->delete();
    }

    /**
     * @param null $page
     * @param null $objects_per_page
     *
     * @return TicketPriority[]|null
     */
    public function allTicketPriorities($page = null, $objects_per_page = null)
    {
        $priorities       =
        $this->priorities =
            $this->client()->resource(ResourceType::TICKET_PRIORITY)->all($page, $objects_per_page);

        if ($this->priorities) {
            return $this->priorities;
        }

        if ($priorities->hasError()) {
            return $priorities->getError();
        }

        return null;
    }

    /**
     * @param $array
     *
     * @return TicketPriority|object;
     */
    public function createTicketPriority($array)
    {
        $ticketPriority = $this->client()->resource(ResourceType::TICKET_PRIORITY);
        foreach ($array as $key => $value) {
            $ticketPriority->setValue($key, $value);
        }

        $ticketPriority->save();

        if ($ticketPriority->hasError()) {
            return $ticketPriority->getError();
        }

        return $ticketPriority;
    }

    /**
     * @param $id
     *
     * @return TicketPriority[]|null
     */
    public function findTicketPriority($id)
    {
        $priority = $this->priority = $this->client()->resource(ResourceType::TICKET_PRIORITY)->get($id);

        if ($this->priority) {
            return $this->priority;
        }

        if ($priority->hasError()) {
            return $priority->getError();
        }

        return null;
    }

    /**
     * @param $id
     * @param $array
     *
     * @return TicketPriority|false
     */
    public function updateTicketPriority($id, $array)
    {
        $ticketPriority = $this->client()->resource(ResourceType::TICKET_PRIORITY)->get($id);
        foreach ($array as $key => $value) {
            $ticketPriority->setValue($key, $value);
        }

        $ticketPriority->save();

        if ($ticketPriority) {
            return $ticketPriority;
        }

        if ($ticketPriority->hasError()) {
            return $ticketPriority->getError();
        }

        return false;
    }

    public function deleteTicketPriority($id)
    {
        $ticketPriority = $this->client()->resource(ResourceType::TICKET_PRIORITY)->get($id);
        $ticketPriority->delete();
    }
}
