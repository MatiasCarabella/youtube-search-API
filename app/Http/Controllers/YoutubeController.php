<?php

namespace App\Http\Controllers;

use App\Services\YoutubeService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
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
     * @return JsonResponse|Response
     */
    public function searchByKeyword(Request $request, YoutubeService $youtubeService)
    {
        try {
            // Validate request parameters
            $validated = $request->validate([
                'search' => 'required|string',
                'results_per_page' => 'nullable|integer|min:' . self::RESULTS_MIN . '|max:' . self::RESULTS_MAX,
                'page_token' => 'nullable|string',
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'The given data was invalid.',
                'errors' => $e->errors()
            ], 422);
        }

        // Get API key from header or fall back to config
        $apiKey = $request->header('api_key') ?? config('services.youtube.api_key');

        // Validate and set results per page
        $resultsPerPage = $this->validateResultsPerPage($validated['results_per_page'] ?? null);

        $queryParams = [
            'part' => 'snippet',
            'type' => 'video',
            'q' => $validated['search'],
            'key' => $apiKey,
            'maxResults' => $resultsPerPage,
            'pageToken' => $validated['page_token'] ?? null,
        ];

        $response = $youtubeService->searchByKeyword($queryParams);
        
        // If the response is already a Response or JsonResponse instance, return it as is
        if ($response instanceof Response || $response instanceof JsonResponse) {
            return $response;
        }

        return response()->json($response);
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