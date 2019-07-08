<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Entity\Traits\TimestampTrait;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MembershipRepository")
 * @ORM\Table(uniqueConstraints={
 *      @ORM\UniqueConstraint(name="uniq_index_on_user_id_and_group_id", columns={"user_id", "group_id"})
 * })
 */
class Membership
{
    use TimestampTrait;
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="Memberships")
     */
    private $user;


    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Group", inversedBy="Memberships")
     */
    private $group;

    /**
     * Membership constructor.
     * @throws \Exception
     */
    public function __construct()
    {
        $this->updateTimestamps();
    }

    /**
     * @return int|null
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return User|null
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @param User $user
     * @return Membership
     */
    public function setUser(User $user): self
    {
        $this->user = $user;

        return $this;
    }


    /**
     * @return Group
     */
    public function getGroup(): Group
    {
        return $this->group;
    }

    /**
     * @param Group|null $group
     * @return Membership
     */
    public function setGroup(?Group $group): self
    {
        $this->group = $group;

        return $this;
    }
}
