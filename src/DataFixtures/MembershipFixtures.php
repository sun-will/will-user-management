<?php

namespace App\DataFixtures;

use App\Entity\Membership;
use App\DataFixtures\UserFixtures;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class MembershipFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $membership = new Membership();

        $user = $this->getReference(UserFixtures::ADMIN_USER_REFERENCE);
        $group = $this->getReference(GroupFixtures::GROUP_REFERENCE);
        
        $membership->setUser($user);
        $membership->setGroup($group);

        $manager->persist($membership);
        $manager->flush();
    }

    public function getDependencies()
    {
        return array(
            UserFixtures::class,
            GroupFixtures::class,
        );
    }
}
