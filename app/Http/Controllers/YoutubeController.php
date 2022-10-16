<?php

namespace App\Http\Controllers;

use App\Services\YoutubeService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;

class YoutubeController extends Controller
{
    const RESULTS_DEFAULT = 10;
    const RESULTS_MIN = 1;
    const RESULTS_MAX = 10;

    /**
     * @param Request $request
     * @param YoutubeService $youtubeService
     * @return array|JsonResponse|Response
     */
    public function searchByKeyword(Request $request, YoutubeService $youtubeService)
    {
        // HEADER VALIDATION:
        /**
         * Checking for Google API Key parameter (Optional),
         * If present, we use it.
         * If not present it defaults to the one set in the ENV file.
         */
        $apiKey = $request->header('api_key') !== NULL ? $request->header('api_key') : env('API_KEY_DEFAULT');

        // BODY VALIDATION:
        $body = json_decode($request->getContent());
        if ($body === null || !isset($body->search)) {
            return $youtubeService->errorResponse(Response::HTTP_BAD_REQUEST, "Field 'search' is mandatory.");
        }

        /**
         * Checking for Results per Page parameter (Optional),
         * - Must be numeric
         * - Must be between 0 and 10
         * If not present or invalid, it defaults to 10.
         */
        $resultsPerPage = (isset($body->results_per_page) && is_numeric($body->results_per_page) &&
                        $body->results_per_page >= self::RESULTS_MIN && $body->results_per_page <= self::RESULTS_MAX) ?
                            $body->results_per_page :
                            self::RESULTS_DEFAULT;

        /**
         * Checking for Page Token parameter (Optional),
         * This allows the user to navigate the result pages.
         */
        $pageToken = $body->page_token ?? null;

        $queryParams = [
            'part' => 'snippet',
            'type' => 'video',
            'q' => $body->search,
            'key' => $apiKey,
            'maxResults' => $resultsPerPage,
            'pageToken' => $pageToken,
        ];

        return $youtubeService->searchByKeyword($queryParams);
    }

}
