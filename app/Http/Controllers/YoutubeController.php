<?php

namespace App\Http\Controllers;

use App\Services\YoutubeService;
use Illuminate\Http\Request;

class YoutubeController extends Controller
{
    const RESULTS_DEFAULT = 10;
    const RESULTS_MIN = 1;
    const RESULTS_MAX = 10;

    public function searchByKeyword(Request $request, YoutubeService $youtubeService)
    {
        // HEADER VALIDATION:
        /**
         * Checking for Google API Key parameter (Optional),
         * If present, we use it.
         * For Demo purposes, if not present we use my personal API Key.
         */
        $apiKey = $request->header('api_key') !== NULL ? $request->header('api_key') : env('API_KEY_DEFAULT');

        // BODY VALIDATION:
        $body = json_decode($request->getContent());
        /**
         * Checking for Results per Page parameter (Optional),
         * - Must be numeric
         * - Must be between 0 and 10
         * If not present or invalid, it defaults to 10.
         */
        $resultsPerPage = (isset($body->results_per_page) &&
                        is_numeric($body->results_per_page) &&
                        $body->results_per_page >= self::RESULTS_MIN &&
                        $body->results_per_page <= self::RESULTS_MAX) ?
                            $body->results_per_page : self::RESULTS_DEFAULT;

        /**
         * Checking for Page Token parameter (Optional),
         * This allows the user to navigate the result pages.
         */
        $pageToken = isset($body->page_token) ? $body->page_token : null;

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