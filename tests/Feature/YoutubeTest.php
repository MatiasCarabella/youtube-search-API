<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\Http;

class YoutubeTest extends TestCase
{
    const EXAMPLE_KEYWORD = 'paradise';

    // Check for Http Status 200 - OK
    public function testHttpStatus200() {

        $body = [
            'search' => self::EXAMPLE_KEYWORD
        ];

        $searchSuccess = json_decode(file_get_contents('./tests/Responses/YouTube/SearchSuccess.json'), true);
        Http::fake($searchSuccess);

        $this->json('GET', '/api/youtube-search/', $body)
         ->assertStatus(200);
    }

    // Validating that the Response JSON has the correct structure
    public function testReturnsDataWithValidFormat() {

        $body = [
            'search' => self::EXAMPLE_KEYWORD,
            'results_per_page' => 5,
            "page_token" => "CAUQAA",
        ];

        $searchSuccess = json_decode(file_get_contents('./tests/Responses/YouTube/SearchSuccess.json'), true);
        Http::fake($searchSuccess);

        $this->json('GET', '/api/youtube-search/', $body)
         ->assertJsonStructure(
             [
                 'total_results',
                 'results_per_page',
                 'videos' => [
                        '*' => [
                        'published_at',
                        'id',
                        'title',
                        'description',
                        'thumbnail',
                        'extra' => [
                            'direct_link',
                            'channel_title'
                        ]
                     ]
                 ]
             ]
         );
    }
}
