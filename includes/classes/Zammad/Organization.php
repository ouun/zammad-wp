<?php

namespace ZammadWp\Zammad;

use ZammadAPIClient\ResourceType;
use ZammadWp\Zammad;

class Organization extends Zammad
{
    /**
     * @param $string
     * @param null $page
     * @param null $objects_per_page
     *
     * @return \ZammadAPIClient\Resource\Organization[]/null
     */
    public function searchOrganizations($string, $page = null, $objects_per_page = null)
    {
        $search =
        $this->search =
            $this->client()->resource(ResourceType::ORGANIZATION)->search($string, $page, $objects_per_page);

        if ($this->search) {
            return $this->search;
        }

        if (!is_array($search)) {
            return $search->getError();
        }

        return null;
    }

    /**
     * @param null $page
     * @param null $objects_per_page
     *
     * @return \ZammadAPIClient\Resource\Organization[]/null
     */
    public function allOrganizations($page = null, $objects_per_page = null)
    {
        $organizations =
        $this->organizations =
            $this->client()->resource(ResourceType::ORGANIZATION)->all($page, $objects_per_page);

        if ($this->organizations) {
            return $this->organizations;
        }

        if ($organizations->hasError()) {
            return $organizations->getError();
        }

        return null;
    }

    /**
     * @param $array
     *
     * @return \ZammadAPIClient\Resource\Organization|object
     */
    public function createOrganization($array)
    {
        $organization = $this->client()->resource(ResourceType::ORGANIZATION);
        foreach ($array as $key => $value) {
            $organization->setValue($key, $value);
        }

        $organization->save();

        if ($organization->hasError()) {
            return $organization->getError();
        }

        return $organization;
    }

    /**
     * @param $id
     *
     * @return \ZammadAPIClient\Resource\Organization|null
     */
    public function findOrganization($id)
    {
        $organization = $this->organization =  $this->client()->resource(ResourceType::ORGANIZATION)->get($id);

        if ($this->organization) {
            return $this->organization;
        }

        if ($organization->hasError()) {
            return $organization->getError();
        }

        return null;
    }

    /**
     * @param $id
     * @param $array
     *
     * @return \ZammadAPIClient\Resource\Organization|false
     */
    public function updateOrganization($id, $array)
    {
        $organization = $this->client()->resource(ResourceType::ORGANIZATION)->get($id);
        foreach ($array as $key => $value) {
            $organization->setValue($key, $value);
        }

        $organization->save();

        if ($organization) {
            return $organization;
        }

        if ($organization->hasError()) {
            return $organization->getError();
        }

        return false;
    }

    public function deleteOrganization($id)
    {
        $organization = $this->client()->resource(ResourceType::ORGANIZATION)->get($id);
        $organization->delete();
    }
}
