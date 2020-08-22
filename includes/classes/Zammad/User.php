<?php

namespace ZammadWp\Zammad;

use ZammadAPIClient\ResourceType;
use ZammadWp\Zammad;

class User extends Zammad
{

    /**
     * @param $string
     * @param null $page
     * @param null $objects_per_page
     *
     * @return \ZammadAPIClient\Resource\User[]|null
     */
    public function searchUsers($string, $page = null, $objects_per_page = null)
    {
        $search       =
        $this->search =
            $this->client()->resource(ResourceType::USER)->search($string, $page, $objects_per_page);

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
     * @return \ZammadAPIClient\Resource\User[]|null
     */
    public function allUsers($page = null, $objects_per_page = null)
    {
        $users = $this->users = $this->client()->resource(ResourceType::USER)->all($page, $objects_per_page);

        if ($this->users) {
            return $this->users;
        }

        if ($users->hasError()) {
            return $users->getError();
        }

        return null;
    }

    /**
     * @param $array
     *
     * @return \ZammadAPIClient\Resource\User|object
     */
    public function createUser($array)
    {
        $user = $this->client()->resource(ResourceType::USER);
        foreach ($array as $key => $value) {
            $user->setValue($key, $value);
        }

        $user->save();

        if ($user->hasError()) {
            return $user->getError();
        }

        return $user;
    }

    /**
     * @param $id
     *
     * @return \ZammadAPIClient\Resource\User
     */
    public function findUser($id)
    {
        $user = $this->user = $this->client()->resource(ResourceType::USER)->get($id);

        if ($this->user) {
            return $this->user;
        }

        if ($user->hasError()) {
            return $user->getError();
        }
    }

    /**
     * @param $id
     * @param $array
     *
     * @return \ZammadAPIClient\Resource\User|object|false
     */
    public function updateUser($id, $array)
    {
        $user = $this->client()->resource(ResourceType::USER)->get($id);
        foreach ($array as $key => $value) {
            $user->setValue($key, $value);
        }

        $user->save();

        if ($user) {
            return $user;
        }

        if ($user->hasError()) {
            return $user->getError();
        }

        return false;
    }

    public function deleteUser($id)
    {
        $user = $this->client()->resource(ResourceType::USER)->get($id);
        $user->delete();
    }
}
