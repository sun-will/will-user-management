<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\GroupService;
use App\Controller\BaseController;
use App\Serializer\GroupSerializer;

/**
 * @Route("/api")
 * @return JsonResponse
 */
class GroupController extends BaseController
{

    /**
     * @var GroupService
     */
    private $groupService;

    /**
     * GroupController constructor.
     * @param GroupService $groupService
     */
    public function __construct(GroupService $groupService)
    {
        $this->groupService = $groupService;
    }

    /**
     * @Route("/group", methods={"GET"})
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request)
    {
        $page = $request->query->getInt('page', 1);
        $perPage = $request->query->getInt('per_page', 10);
        $groups = $this->groupService->findAll($page, $perPage);
        $serializedGroups = [];

        if (count($groups) == 0) {
            return JsonResponse::create([], JsonResponse::HTTP_NO_CONTENT);
        }

        foreach ($groups as $group) {
            $groupSerializer = new GroupSerializer($group);
            $serializedGroups[] = $groupSerializer->serialize();
        }

        return $this->sendResponse($serializedGroups);
    }

    /**
     * @Route("/group", methods={"POST"})
     * @param Request $request
     * @return JsonResponse|Response
     */
    public function create(Request $request)
    {
        $postData = (array)json_decode($request->getContent(), true);
        $request->request->replace($postData);

        $data = $this->createGroupData($request);

        try {
            $group = $this->groupService->create($data);
        } catch (\Exception $exception) {
            return $this->errorResponse($exception);
        }

        $groupSerializer = new GroupSerializer($group);
        return $this->sendResponse($groupSerializer->serialize(), JsonResponse::HTTP_CREATED);
    }

    /**
     * @Route("/group/{id}", methods={"DELETE"})
     * @param $id
     * @return JsonResponse|Response
     */
    public function delete($id)
    {
        try {
            $this->groupService->delete($id);
        } catch (\Exception $exception) {
            return $this->errorResponse($exception);
        }
        return $this->sendResponse([], JsonResponse::HTTP_OK); 
    }

    /**
     * @param $request
     * @return array
     */
    private function createGroupData($request)
    {
        return [
            'name' => $request->request->get('name'),
        ];
    }
}
