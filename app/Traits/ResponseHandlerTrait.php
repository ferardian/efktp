<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;

trait ResponseHandlerTrait
{
	/**
	 * Send a success response.
	 *
	 * @param mixed $data
	 * @param string $message
	 * @param int $code
	 * @return \Illuminate\Http\JsonResponse
	 */
	protected function success($data = null, $message = 'Success', $code = 200): JsonResponse
	{
		return response()->json([
			'metaData' => [
				'code' => $code,
				'success' => true,
				'message' => $message,
			],
			'data' => $data
		], $code);
	}

	/**
	 * Send an error response.
	 *
	 * @param string $message
	 * @param int $code
	 * @param mixed $errors
	 * @return \Illuminate\Http\JsonResponse
	 */
	protected function error($errors = null, $message = 'Error', $code = 400): JsonResponse
	{
		return response()->json([
			'metaData' => [
				'code' => $code,
				'success' => false,
				'message' => $message,
			],
			'errors' => substr($errors, 0, 100)
		], $code);
	}
}
