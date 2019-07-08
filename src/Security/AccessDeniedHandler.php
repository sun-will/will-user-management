<?php

namespace App\Security;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Http\Authorization\AccessDeniedHandlerInterface;

/**
 * Class AccessDeniedHandler
 * @package App\Security
 */
class AccessDeniedHandler implements AccessDeniedHandlerInterface
{
    /**
     * @param Request $request
     * @param AccessDeniedException $accessDeniedException
     * @return JsonResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, AccessDeniedException $accessDeniedException)
    {
        return new JsonResponse(['error' => 'Sorry, You don not have access to this page'], 403);
    }
}