<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use App\Service\MembershipService;
use App\Controller\BaseController;
use App\Serializer\MembershipSerializer;

/**
 * @Route("/api")
 * @return JsonResponse
 */
class MembershipController extends BaseController
{
    /**
     * @var membershipService
     */
    private $membershipService;

    /**
     * MembershipController constructor.
     * @param membershipService $membershipService
     */
    public function __construct(MembershipService $membershipService)
    {
        $this->membershipService = $membershipService;
    }

    /**
     * @Route("/membership", methods={"GET"})
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request)
    {
        $page = $request->query->getInt('page', 1);
        $perPage = $request->query->getInt('per_page', 10);
        $groupId = $request->query->getInt('group_id', null);
        $userId = $request->query->getInt('user_id', null);

        $filters = ['user' => $userId, 'group' => $groupId];

        try {
            $memberships = $this->membershipService->findAll($filters, $page, $perPage);
        } catch (\Exception $exception) {
            return $this->errorResponse($exception);
        }

        $serializedMemberships = [];

        if (count($memberships) == 0) {
            return $this->sendResponse([], JsonResponse::HTTP_NO_CONTENT);
        }

        foreach ($memberships as $membership) {
            $membershipSerializer = new MembershipSerializer($membership);
            $serializedMemberships[] = $membershipSerializer->serialize();
        }

        return $this->sendResponse($serializedMemberships, JsonResponse::HTTP_OK);
    }


    /**
     * @Route("/membership", methods={"POST"})
     * @param Request $request
     * @return JsonResponse
     */
    public function create(Request $request)
    {
        $postData = (array)json_decode($request->getContent(), true);
        $request->request->replace($postData);

        $data = $this->membershipData($request);

        try {
            $membership = $this->membershipService->create($data['groupId'], $data['userId']);
        } catch (\Exception $exception) {
            return $this->errorResponse($exception);
        }


        $membershipSerializer = new MembershipSerializer($membership);

        return $this->sendResponse($membershipSerializer->serialize(), JsonResponse::HTTP_CREATED);
    }


    /**
     * @Route("/membership/{id}", methods={"DELETE"})
     * @param $id
     * @return JsonResponse
     */
    public function delete($id)
    {
        try {
            $this->membershipService->delete($id);
        } catch (\Exception $exception) {
            return $this->errorResponse($exception);
        }

        return $this->sendResponse([], Response::HTTP_OK); 
    }

    /**
     * @param $request
     * @return array
     */
    private function membershipData($request)
    {
        return [
            'groupId' => $request->request->get('group_id'),
            'userId' => $request->request->get('user_id'),
        ];
    }
}
