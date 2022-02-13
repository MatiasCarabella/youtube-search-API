<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Throwable;

class YoutubeController extends Controller
{
    const RESULTS_MIN = 1;
    const RESULTS_MAX = 10;
    const RESULTS_DEFAULT = 10;
    const API_KEY_DEFAULT = 'AIzaSyAwAlrrnyT9eDQ0mOKslcPcM068EeDxjpY';

    public function searchByKeyword($keyword)
    {

        /**
         * Checking for Google API Key parameter (Optional),
         * If present, we use it.
         * For Demo purposes, if not present we use my personal API Key.
         */
        $apiKey = isset($_GET['api_key']) ? $_GET['api_key'] : self::API_KEY_DEFAULT;

        /**
         * Checking for Results per Page parameter (Optional),
         * - Must be numeric
         * - Must be between 0 and 10
         * If not present or invalid, it defaults to 10.
         */
        $resultsPerPage = (isset($_GET['results_per_page']) && is_numeric($_GET['results_per_page']) &&
                            $_GET['results_per_page'] > self::RESULTS_MIN && $_GET['results_per_page'] <= self::RESULTS_MAX) ?
                            $_GET['results_per_page'] : self::RESULTS_DEFAULT;

        /**
         * Checking for Page Token parameter (Optional),
         * This allows the user to navigate the result pages.
         */
        $pageToken = isset($_GET['page_token']) ? $_GET['page_token'] : null;
        
        $queryParams = [
            'part' => 'snippet',
            'type' => 'video',
            'q' => $keyword,
            'key' => $apiKey,
            'maxResults' => $resultsPerPage,
            'pageToken' => $pageToken,
        ];

        try {
            $response = Http::get("https://www.googleapis.com/youwtube/v3/search", $queryParams);
            /**
             * Check whether or not the Response has items,
             * - If yes return it with the corresponding format
             * - If not return the API's error message
             */
            return array_key_exists('items', json_decode($response->body(), true)) ? 
                    $this->formatResponse($response) :
                    new Response($response->body(), $response->status(), ['Content-Type' => 'application/json']);

        } catch (Exception $e) {
            abort($e->getCode(), $e->getMessage());
        } catch (Throwable) {
            abort(500);
        }
    }

    private function formatResponse($response)
    {
        // CONVIRTIENDO EL JSON A UN ARRAY TRABAJABLE
        $responseArray = json_decode($response->body(), true);
        // CREO EL ARRAY QUE VA A GUARDAR TODOS LOS VIDEOS
        $formattedVideoArray = Array();
        foreach($responseArray["items"] as $video)
        {
            // RECORRO CADA VIDEO DE LA LISTA Y ME GUARDO LA INFO RELEVANTE
            $formattedVideo = [
                "published_at" => $video['snippet']['publishedAt'],
                "id" => $video['id']['videoId'],
                "title" => $video['snippet']['title'],
                "description" => $video['snippet']['description'],
                "thumbnail" => $video['snippet']['thumbnails']['default']['url'],
                "extra" => [
                        "direct_link" => "https://www.youtube.com/watch?v=".$video['id']['videoId'],
                        "channel_title" => $video['snippet']['channelTitle']
                ]
            ];
            // CARGO C/ ARRAY DE VIDEO EN EL ARRAY DE VIDEOS GENERAL
            array_push($formattedVideoArray, $formattedVideo);
        }
        // ARMO EL ARRAY GENERAL Y LE AGREGO EL ARRAY DE VIDEOS
        $formattedResponse = [
            "total_results" => $responseArray['pageInfo']['totalResults'],
            "results_per_page" => $responseArray['pageInfo']['resultsPerPage'],
            "videos" => $formattedVideoArray
        ];
        // SI NO ESTAMOS EN LA PRIMERA PÁGINA MUESTRO EL TOKEN DE LA PÁG ANTERIOR
        if(array_key_exists('prevPageToken', $responseArray)){
            $formattedResponse = array("prev_page_token" => $responseArray['prevPageToken']) + $formattedResponse;
        }
        // SI HAY MÁS PÁGINAS CON ITEMS MUESTRO EL TOKEN DE LA PÁG SIGUIENTE
        if(array_key_exists('nextPageToken', $responseArray)){
            $formattedResponse = array("next_page_token" => $responseArray['nextPageToken']) + $formattedResponse;
        }
        // FINISH
        return $formattedResponse;
    }
}