<?php

namespace App\Helpers\Traits;

use App\Helpers\Helper;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\JsonResponse;

trait ResponseTrait
{
    protected function success(string $message = '', $data = null, int $statusCode = 200, ?array $meta = null): JsonResponse
    {
        $response = [
            'code' => $statusCode,
            'message' => $message,
        ];

        if (!empty($meta)) {
            $newData = ['meta' => $meta];

            if ($data !== null) {
                $newData['data'] = $data;
            }

            $response['data'] = $newData;
        }
        else {
            if ($data !== null) {
                $response['data'] = $data;
            }
        }

        return response()->json($response, 200, [], JSON_INVALID_UTF8_IGNORE);
    }

    protected function successWithPaginate(string $message, LengthAwarePaginator $data, int $statusCode = 200,
                                           ?array $additionalData = [], bool $isMergeAdditional = false): JsonResponse
    {
        $newData['data'] = $data->items();
        $newData['meta'] = [
            'pagination' => [
                'total' => $data->total(),
                'page_size' => $data->perPage(),
                'current' => $data->currentPage(),
                'total_pages' => $data->lastPage()
            ]
        ];

        if (!empty($additionalData)) {
            if ($isMergeAdditional && isset($additionalData['id'])) {
                $isExistData = collect($data->items())->where('id', $additionalData['id'])->first();
                if (empty($isExistData)) {
                    $newData['data'][] = $additionalData;
                }
            }
            else {
                foreach ($additionalData as $key => $value) {
                    $newData[$key] = $value;
                }
            }
        }

        return response()->json(
            [
                'code' => $statusCode,
                'message' => $message,
                'data' => $newData,
            ], 200, [], JSON_INVALID_UTF8_IGNORE
        );
    }

    protected function successWithPaginateNull(string $message, int $statusCode = 200): JsonResponse
    {
        $newData['data'] = [];
        $newData['meta'] = [
            'pagination' => [
                'total' => 0,
                'page_size' => Helper::getPerPage(),
                'current' => Helper::getCurrentPage(),
                'total_pages' => 1,
            ]
        ];

        return response()->json(
            [
                'code' => $statusCode,
                'message' => $message,
                'data' => $newData,
            ], 200, [], JSON_INVALID_UTF8_IGNORE
        );
    }

    protected function error(string $message = '', $errors = [], int $statusCode = 400): JsonResponse
    {
        return response()->json(
            [
                'code' => $statusCode,
                'message' => $message,
                'errors' => $errors
            ], 200, [], JSON_INVALID_UTF8_IGNORE
        );
    }

    protected function errorNotFound(string $message = ''): JsonResponse
    {
        return response()->json(
            [
                'code' => 404,
                'message' => empty($message) ? __('general.not_found') : $message,
            ], 200, [], JSON_INVALID_UTF8_IGNORE
        );
    }

    protected function validationErrors(string $message = '', $errors = []): JsonResponse
    {
        return response()->json([
            'errors' => $errors,
            'message' => $message,
        ], 422, [], JSON_INVALID_UTF8_IGNORE);
    }

    protected function errorAuthorization(string $message = '', $errors = [], int $statusCode = 403): JsonResponse
    {
        if (empty($message)) {
            $message = __('authentication.unauthorized_access_error');
        }

        return response()->json(
            [
                'code' => $statusCode,
                'message' => $message,
                'errors' => $errors
            ]
        );
    }
}
