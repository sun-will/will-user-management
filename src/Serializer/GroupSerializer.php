<?php

namespace App\Serializer;

use App\Entity\Group;


/**
 * Class GroupSerializer
 * @package App\Serializer
 */
class GroupSerializer
{
    /**
     * @var Group
     */
    protected $group;

    /**
     * groupSerializer constructor.
     * @param Group $group
     */
    public function __construct(Group $group)
    {
        $this->group = $group;
    }

    /**
     * @return mixed
     */
    public function serialize()
    {
        $group['id'] = $this->group->getId();
        $group['name'] = $this->group->getName();
        $group['created_at'] = $this->group->getCreatedAt()->getTimestamp();

        return $group;
    }
}
