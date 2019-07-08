<?php

namespace App\Serializer;

use App\Entity\Membership;


/**
 * Class MembershipSerializer
 * @package App\Serializer
 */
class MembershipSerializer
{
    /**
     * @var Membership
     */
    protected $membership;

    /**
     * membershipSerializer constructor.
     * @param membership $membership
     */
    public function __construct(Membership $membership)
    {
        $this->membership = $membership;
    }

    /**
     * @return mixed
     */
    public function serialize()
    {
        $membership['id'] = $this->membership->getId();
        $membership['user_id'] = $this->membership->getUser()->getId();
        $membership['group_id'] = $this->membership->getGroup()->getId();
        $membership['created_at'] = $this->membership->getCreatedAt()->getTimestamp();

        return $membership;
    }
}
