<?php

namespace App\Service;

use App\Exception\ResourceValidationException;
use App\Exception\CanNotPerformThisActionException;
use App\Exception\ResourceNotFoundException;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Repository\GroupRepository;
use App\Service\UserService;
use App\Entity\Group;

/**
 * @package App\Service
 */
class GroupService
{
    /**
     * @var GroupRepository groupRepository
     */
    private $groupRepository;

    /**
     * @var UserService userService
     */
    private $userService;

    /**
     *
     * @var ValidatorInterface
     */
    private $validator;


    /**
     * GroupService constructor.
     * @param GroupRepository $groupRepository
     * @param userService $userService
     * @param ValidatorInterface $validator
     */
    public function __construct(
        GroupRepository $groupRepository,
        UserService $userService,
        ValidatorInterface $validator
    ) {
        $this->groupRepository = $groupRepository;
        $this->userService = $userService;
        $this->validator = $validator;
    }

    /**
     * @param int $page
     * @param int $limit
     * @return mixed
     */
    public function findAll($page = 1, $limit = 10)
    {
        $offset = (($page * $limit) - $limit);

        return $this->groupRepository->findBy([], null, $limit, $offset);
    }

    /**
     * @param $data
     * @return Group
     * @throws ResourceValidationException
     */
    public function create($data)
    {
        $group = new Group();
        $group->setName($data['name']);

        $errors = $this->validator->validate($group);

        if (count($errors) > 0) {
            $exp = new ResourceValidationException('Validation Exception.');
            $exp->setFields($errors);

            throw $exp;
        }

        $this->groupRepository->persistAndFlush($group);

        return $group;
    }

    /**
     * @param $id
     * @throws ResourceNotFoundException
     * @throws CanNotPerformThisActionException
     */
    public function delete($id)
    {
        $group = $this->find($id);

        if (count($group->getMemberships()) > 0) {
            throw new CanNotPerformThisActionException('Delete this group not allowed.');
        }

        $this->groupRepository->remove($group);
    }

    /**
     * @param $id
     * @return Group|null
     * @throws ResourceNotFoundException
     */
    public function find($id)
    {
        $group = $this->groupRepository->find($id);

        if (!$group) {
            throw new ResourceNotFoundException('Group is not found');
        }

        return $group;
    }
}
