<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;

class YoutubeController extends Controller
{
    // FUNCIÓN QUE CONSUME LA API DE YOUTUBE
    function searchByKeyword($keyword)
    {
        // ARMANDO EL ARRAY CON LOS PARÁMETROS DE LA QUERY
        $queryParams = [
            'part' => 'snippet',
            'key' => 'AIzaSyAhMwlCgkGksJCKanlM9nnt5Fl2TugtIdA',
            'type' => 'video',
            'q' => $keyword
        ];
        /* CHEQUEANDO SI SE RECIBIÓ EL PARÁMETRO OPCIONAL results_per_page
            Y VALIDANDO QUE SEA UN NÚMERO ENTRE 0 Y 10, ACORDE AL REQUERIMIENTO DEL PROYECTO */
        if(isset($_GET['results_per_page']) && 
            is_numeric($_GET['results_per_page']) &&
            $_GET['results_per_page'] > 0 &&
            $_GET['results_per_page'] <= 10)
        {
            // CASO POSITIVO AGREGARLO AL ARRAY DE PARAMETROS
            $queryParams = $queryParams + array("maxResults" => $_GET['results_per_page']);
        }
        else
        {
            // CASO CONTRARIO DEFAULTEO A 10
            $queryParams = $queryParams + array("maxResults" => 10);
        }
        /* CHEQUEANDO EL OTRO PARÁMETRO OPCIONAL, page_token 
        QUE PERMITE IR RECORRIENDO LAS PÁGINAS DE RESULTADOS */
        if(isset($_GET['page_token']))
        {
            $queryParams = $queryParams + array("pageToken" => $_GET['page_token']);
        }
        // CONSUMO LA API
        $response = Http::get("https://www.googleapis.com/youtube/v3/search",$queryParams);
        // ME ASEGURO DE HABER OBTENIDO RESULTADOS ANTES DE AVANZAR
        if(array_key_exists('items', json_decode($response->body(), true))){
            /* LE PASO EL JSON OBTENIDO A LA FUNCIÓN QUE SE ENCARGA DE DARLE EL FORMATO ACORDE
            A LOS LINEAMIENTOS DEL PROYECTO Y HAGO EL RETURN */
            return $this->formatResponse($response);
        }
        else
        {
            // SI NO OBTUVE RESULTADOS MUESTRO EL MENSAJE DE ERROR TAL CUAL LO DEVUELVE LA API
            return $response;
        }
    }

    public function formatResponse($response)
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