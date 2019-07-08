<?php

namespace App\Serializer;

use App\Entity\User;


/**
 * Class UserSerializer
 * @package App\Serializer
 */
class UserSerializer
{
    /**
     * @var User
     */
    protected $user;

    /**
     * UserSerializer constructor.
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * @return mixed
     */
    public function serialize()
    {
        $user['id'] = $this->user->getId();
        $user['name'] = $this->user->getName();
        $user['created_at'] = $this->user->getCreatedAt()->getTimestamp();

        return $user;
    }
}
