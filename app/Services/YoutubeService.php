<?php

namespace App\Services;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;
use Throwable;

class YoutubeService extends BaseService
{
    /**
     * @param array $queryParams
     * @return array|JsonResponse|Response
     */
    public function searchByKeyword(array $queryParams)
    {
        try {
            $response = Http::withoutVerifying()->get("https://www.googleapis.com/youtube/v3/search", $queryParams);

            /**
            * Check whether the Request was successful
            * - If yes return it with the corresponding format
            * - If not return the API error message as is
            */
            return $response->status() === Response::HTTP_OK ?
                        $this->formatResponse($response) :
                        new Response($response->body(), $response->status(), ['Content-Type' => 'application/json']);
        } catch (Exception $e) {
            return $this->errorResponse($e->getCode(), $e->getMessage());
        } catch (Throwable) {
            return $this->errorResponse(Response::HTTP_INTERNAL_SERVER_ERROR, "An error has occurred, please try again later.");
        }
    }

    /**
     * @param $response
     * @return array
     */
    private function formatResponse($response): array
    {
        // Convert the raw Response's body into an array
        $body = json_decode($response->body(), true);
        // Create the variable that will store all the video's info
        $videos = [];
        // Go through every element of the List, storing the relevant data in the $videos Array
        foreach ($body["items"] as $item) {
            $video = [
                "published_at" => $item['snippet']['publishedAt'],
                "id" => $item['id']['videoId'],
                "title" => $item['snippet']['title'],
                "description" => $item['snippet']['description'],
                "thumbnail" => $item['snippet']['thumbnails']['default']['url'],
                "extra" => [
                        "direct_link" => "https://www.youtube.com/watch?v=" . $item['id']['videoId'],
                        "channel_title" => $item['snippet']['channelTitle'],
                ],
            ];
            $videos[] = $video;
        }

        $formattedResponse = [];

        // If the current page is not the first one, the 'prevPageToken' element is displayed
        $formattedResponse += array_key_exists('prevPageToken', $body) ? ["prev_page_token" => $body['prevPageToken']] : [];

        // If there's more pages, the 'nextPageToken' element is displayed
        $formattedResponse += array_key_exists('nextPageToken', $body) ? ["next_page_token" => $body['nextPageToken']] : [];

        return $formattedResponse + [
                "total_results" => $body['pageInfo']['totalResults'],
                "results_per_page" => $body['pageInfo']['resultsPerPage'],
                "videos" => $videos,
            ];
    }
}
