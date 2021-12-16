<?php

namespace App\Traits;

use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Resources\Json\JsonResource;

trait RespondsJson
{
    /**
     * Return a json response with the given data.
     *
     * @param \Illuminate\Contracts\Support\Arrayable|mixed $data
     */
    protected function jsonResponse(string $message, $data = null, int $httpCode = Response::HTTP_OK)
    {
        if (is_object($data) && $data instanceof Arrayable) {
            $data = $data->toArray();
        }

        if ($data instanceof JsonResource) {
            return $data->additional([
                'success'   => true,
                'message'   => $message,
            ]);
        }

        return response()->json([
            'data'      => $data,
            'success'   => true,
            'message'   => $message,
        ], $httpCode);

        return response()->json([
            'success'   => true,
            'message'   => $message,
            'data'      => $data,
        ], $httpCode);
    }

    /**
     * Create a fully customized json error response.
     *
     * @param array|mixed $data
     */
    protected function jsonErrorResponse(
        string $message = null,
        $data = null,
        int $httpCode = Response::HTTP_BAD_REQUEST
    ): JsonResponse {
        $response = [
            'success' => false,
            'message' => $message,
            'data'    => $data,
        ];

        return response()->json($response, $httpCode);
    }
}
