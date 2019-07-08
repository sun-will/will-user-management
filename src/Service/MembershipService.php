<?php

namespace App\Service;

use App\Exception\ResourceValidationException;
use App\Exception\ResourceNotFoundException;
use App\Exception\ResourceAlreadyCreatedException;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use App\Repository\MembershipRepository;
use App\Service\GroupService;
use App\Service\UserService;
use App\Entity\Membership;

/**
 * @package App\Service
 */
class MembershipService
{
    /**
     * @var GroupService groupService
     */
    private $groupService;

    /**
     * @var UserService userService
     */
    private $userService;

    /**
     * @var MembershipRepository membershipRepository
     */
    private $membershipRepository;

    /**
     * MembershipService constructor.
     * @param \App\Service\GroupService $groupService
     * @param \App\Service\UserService $userService
     * @param MembershipRepository $membershipRepository
     */
    public function __construct(
        GroupService $groupService,
        UserService $userService,
        MembershipRepository $membershipRepository
    ) {
        $this->groupService = $groupService;
        $this->userService = $userService;
        $this->membershipRepository = $membershipRepository;
    }

    /**
     * @param $filters
     * @param int $page
     * @param int $limit
     * @return Membership[]
     */
    public function findAll($filters, $page = 1, $limit = 10)
    {
        $filters = $this->validateFilters($filters);
        $offset = (($page * $limit) - $limit);

        return $this->membershipRepository->findBy($filters, null, $limit, $offset);
    }

    /**
     * @param $groupId
     * @param $userId
     * @return Membership
     * @throws ResourceAlreadyCreatedException
     * @throws ResourceNotFoundException
     */
    public function create($groupId, $userId)
    {
        $user = $this->userService->find($userId);
        $group = $this->groupService->find($groupId);

        try {
            $membership = New Membership;
            $membership->setUser($user);
            $membership->setGroup($group);
            $this->membershipRepository->persistAndFlush($membership);
        } catch (UniqueConstraintViolationException $exception) {
            throw new ResourceAlreadyCreatedException('Already created.');
        }

        return $membership;
    }

    /**
     * @param $id
     * @return Membership
     * @throws ResourceNotFoundException
     */
    public function find($id)
    {
        $membership = $this->membershipRepository->find($id);

        if (!$membership) {
            throw new ResourceNotFoundException('Not found.');
        }

        return $membership;
    }

    /**
     * @param $id
     * @throws ResourceNotFoundException
     */
    public function delete($id)
    {
        $membership = $this->find($id);

        $this->membershipRepository->remove($membership);
    }

    /**
     * @param $filters
     * @return mixed
     */
    private function validateFilters($filters)
    {
        $allowedFilters = ['user', 'group'];
        foreach ($filters as $key => $value) {
            if (is_null($value) || $value == '' || !in_array($key, $allowedFilters)) {
                unset($filters[$key]);
            }
        }

        return $filters;
    }
}
