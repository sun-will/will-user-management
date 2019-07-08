<?php

namespace App\Repository;

use App\Entity\Membership;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use App\Repository\Traits\RepositoryTrait;

/**
 * @method Membership|null find($id, $lockMode = null, $lockVersion = null)
 * @method Membership|null findOneBy(array $criteria, array $orderBy = null)
 * @method Membership[]    findAll()
 * @method Membership[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MembershipRepository extends ServiceEntityRepository
{
    use RepositoryTrait;

    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Membership::class);
    }
}
