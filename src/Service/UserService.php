<?php

namespace App\Service;

use App\Exception\ResourceValidationException;
use App\Exception\ResourceNotFoundException;
use App\Exception\CanNotPerformThisActionException;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Repository\UserRepository;
use App\Entity\User;

/**
 * @package App\Service
 */
class UserService
{
    /**
     * @var UserRepository userRepository
     */
    private $userRepository;

    /**
     *
     * @var ValidatorInterface
     */
    private $validator;


    /**
     * UserService constructor.
     * @param UserRepository $userRepository
     * @param ValidatorInterface $validator
     */
    public function __construct(UserRepository $userRepository, ValidatorInterface $validator)
    {
        $this->userRepository = $userRepository;
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

        return $this->userRepository->findBy([], null, $limit, $offset);
    }

    /**
     * @param $data
     * @return User
     * @throws ResourceValidationException
     */
    public function create($data)
    {
        $user = new User();
        $user->setName((string)$data['name']);

        $errors = $this->validator->validate($user);

        if (count($errors) > 0) {
            $exp = new ResourceValidationException('Validation Exception.');
            $exp->setFields($errors);

            throw $exp;
        }

        $this->userRepository->persistAndFlush($user);

        return $user;
    }

    /**
     * @param $id
     * @param $currentUser
     * @throws CanNotPerformThisActionException
     * @throws ResourceNotFoundException
     */
    public function delete($id, $currentUser)
    {
        $user = $this->find($id);
        $this->validateDeleteUser($currentUser, $user);
        $this->userRepository->remove($user);
    }

    /**
     * @param $id
     * @return object|null
     * @throws ResourceNotFoundException
     */
    public function find($id)
    {
        $user = $this->userRepository->find($id);

        if (!$user) {
            throw new ResourceNotFoundException('Not found');
        }

        return $user;
    }

    /**
     * @param $currentUser
     * @param $user
     * @throws CanNotPerformThisActionException
     */
    private function validateDeleteUser($currentUser, $user)
    {
        if ($user->getId() == $currentUser->getId()) {
            throw new CanNotPerformThisActionException('Sorry');
        }
    }
}
