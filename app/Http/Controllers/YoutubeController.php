<?php

namespace App\Http\Controllers;

use App\Services\YoutubeService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;

class YoutubeController extends Controller
{
    private const RESULTS_DEFAULT = 10;
    private const RESULTS_MIN = 1;
    private const RESULTS_MAX = 10;

    /**
     * Search YouTube videos by keyword
     *
     * @param Request $request
     * @param YoutubeService $youtubeService
     * @return array|JsonResponse|Response
     */
    public function searchByKeyword(Request $request, YoutubeService $youtubeService)
    {
        // Get API key from header or fall back to config
        $apiKey = $request->header('api_key') ?? config('services.youtube.api_key');

        // BODY VALIDATION:
        $body = json_decode($request->getContent());
        if ($body === null || !isset($body->search)) {
            return $youtubeService->errorResponse(Response::HTTP_BAD_REQUEST, "Field 'search' is mandatory.");
        }

        // Validate and set results per page
        $resultsPerPage = $this->validateResultsPerPage($body->results_per_page ?? null);

        $queryParams = [
            'part' => 'snippet',
            'type' => 'video',
            'q' => $body->search,
            'key' => $apiKey,
            'maxResults' => $resultsPerPage,
            'pageToken' => $body->page_token ?? null,
        ];

        return $youtubeService->searchByKeyword($queryParams);
    }

    /**
     * Validate and return the results per page value
     *
     * @param mixed $value
     * @return int
     */
    private function validateResultsPerPage($value): int
    {
        if (!is_numeric($value)) {
            return self::RESULTS_DEFAULT;
        }

        $value = (int) $value;

        if ($value < self::RESULTS_MIN) {
            return self::RESULTS_MIN;
        }

        if ($value > self::RESULTS_MAX) {
            return self::RESULTS_MAX;
        }

        return $value;
    }
}