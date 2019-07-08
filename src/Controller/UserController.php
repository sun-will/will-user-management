<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\UserService;
use App\Controller\BaseController;
use Symfony\Component\Security\Core\Security;
use App\Serializer\UserSerializer;

/**
 * @Route("/api")
 * @return JsonResponse
 */
class UserController extends BaseController
{

    /**
     * @var UserService
     */
    private $userService;

    /**
     * UsersController constructor.
     * @param UserService $userService
     */
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * @Route("/user", methods={"GET"})
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request)
    {
        $page = $request->query->getInt('page', 1);
        $perPage = $request->query->getInt('per_page', 10);
        $users = $this->userService->findAll($page, $perPage);
        $serializedUsers = [];


        if (count($users) == 0) {
            return JsonResponse::create([], JsonResponse::HTTP_NO_CONTENT);
        }

        foreach ($users as $user) {
            $userSerializer = new UserSerializer($user);
            $serializedUsers[] = $userSerializer->serialize();
        }
        return $this->sendResponse($serializedUsers, JsonResponse::HTTP_OK); 
    }

    /**
     * @Route("/user", methods={"POST"})
     * @param Request $request
     * @return JsonResponse|Response
     */
    public function create(Request $request)
    {
        $postData = (array)json_decode($request->getContent(), true);
        $request->request->replace($postData);

        $data = $this->createUserData($request);

        try {
            $user = $this->userService->create($data);
        } catch (\Exception $exception) {
            return $this->errorResponse($exception);
        }

        $userSerializer = new UserSerializer($user);
        return $this->sendResponse($userSerializer->serialize(), JsonResponse::HTTP_CREATED);   
    }

    /**
     * @Route("/user/{id}", methods={"DELETE"})
     * @param $id
     * @param Security $security
     * @return Response|JsonResponse
     */
    public function delete($id, Security $security)
    {
        $user = $security->getUser();
        try {
            $this->userService->delete($id, $user);
        } catch (\Exception $exception) {
            return $this->errorResponse($exception);
        }

        return $this->sendResponse([], Response::HTTP_OK);
    }

    /**
     * @param $request
     * @return array
     */
    private function createUserData($request)
    {
        return [
            'name' => $request->request->get('name'),
        ];
    }
}
