<?php

namespace App\Controller;

use App\Exception\CanNotPerformThisActionException;
use App\Exception\ResourceNotFoundException;
use App\Exception\ResourceValidationException;
use App\Exception\ResourceAlreadyCreatedException;
use Symfony\Component\HttpFoundation\JsonResponse;


/**
 * Class BaseController
 * @package App\Controller
 */
abstract class BaseController
{

    /**
     * @param \Exception $exception
     * @return JsonResponse
     */
    public function errorResponse(\Exception $exception)
    {
        if ($exception instanceof ResourceValidationException) {
            $response = new JsonResponse($this->buildResponse($exception), JsonResponse::HTTP_BAD_REQUEST);
        } elseif ($exception instanceof ResourceNotFoundException) {
            $response = new JsonResponse($this->buildResponse($exception), JsonResponse::HTTP_NOT_FOUND);
        } elseif ($exception instanceof CanNotPerformThisActionException) {
            $response = new JsonResponse($this->buildResponse($exception), JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
        } elseif ($exception instanceof ResourceAlreadyCreatedException) {
            $response = new JsonResponse($this->buildResponse($exception), JsonResponse::HTTP_ALREADY_REPORTED);
        } else {
            $response = new JsonResponse($this->buildResponse($exception), JsonResponse::HTTP_BAD_REQUEST);
        }

        return $response;
    }

    /**
     * @param $exception
     * @return mixed $data
     */
    private function buildResponse($exception)
    {
        $data = array(
            'message' => $exception->getMessage(),
        );

        if (method_exists($exception, 'getFields') && !empty($exception->getFields())) {
            foreach ($exception->getFields() as $field) {
                if (method_exists($field, 'getPropertyPath') && method_exists($field, 'getMessage')) {
                    $data['fields'][] = [
                        'name' => $field->getPropertyPath(),
                        'message' => $field->getMessage(),
                    ];
                } else {
                    $data['fields'][] = [
                        'name' => $field['name'],
                        'message' => $field['message'],
                        'extra' => $field['extra'],
                    ];
                }
            }
        }

        return $data;
    }

    /**
     * Return a JSON response
     * 
     * @param array $data
     * @param int $code
     * 
     * @return Symfony\Component\HttpFoundation\JsonResponse
     */
    public function sendResponse($data, $code = JsonResponse::HTTP_OK){
        return new JsonResponse($data, $code);
    }

}
