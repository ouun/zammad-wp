<?php

namespace ZammadWp\Zammad;

use ZammadAPIClient\ResourceType;
use ZammadWp\Zammad;

class Group extends Zammad
{

    /**
     * @param null $page
     * @param null $objects_per_page
     *
     * @return \ZammadAPIClient\Resource\Group[]|null
     */
    public function allGroups($page = null, $objects_per_page = null)
    {
        $groups = $this->groups = $this->client()->resource(ResourceType::GROUP)->all($page, $objects_per_page);

        if ($this->groups) {
            return $this->groups;
        }

        if ($groups->hasError()) {
            return $groups->getError();
        }

        return null;
    }

    /**
     * @param $array
     *
     * @return \ZammadAPIClient\Resource\Group|object
     */
    public function createGroup($array)
    {
        $group = $this->client()->resource(ResourceType::GROUP);
        foreach ($array as $key => $value) {
            $group->setValue($key, $value);
        }

        $group->save();

        if ($group->hasError()) {
            return $group->getError();
        }

        return $group;
    }

    /**
     * @param $id
     *
     * @return \ZammadAPIClient\Resource\Group|null
     */
    public function findGroup($id)
    {
        $group = $this->group = $this->client()->resource(ResourceType::GROUP)->get($id);

        if ($this->group) {
            return $this->group;
        }

        if ($group->hasError()) {
            return $group->getError();
        }

        return null;
    }

    /**
     * @param $id
     * @param $array
     *
     * @return \ZammadAPIClient\Resource\Group|false
     */
    public function updateGroup($id, $array)
    {
        $group = $this->client()->resource(ResourceType::GROUP)->get($id);
        foreach ($array as $key => $value) {
            $group->setValue($key, $value);
        }

        $group->save();

        if ($group) {
            return $group;
        }

        if ($group->hasError()) {
            return $group->getError();
        }

        return false;
    }

    public function deleteGroup($id)
    {
        $group = $this->client()->resource(ResourceType::GROUP)->get($id);
        $group->delete();
    }
}
