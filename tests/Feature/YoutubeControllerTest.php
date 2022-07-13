<?php

namespace Tests\Feature;

use Tests\TestCase;

class YoutubeControllerTest extends TestCase
{
    const EXAMPLE_KEYWORD = 'paradise';

    // Check for Http Status 200 - OK
    public function testHttpStatus200() {

        $body = [
            'search' => self::EXAMPLE_KEYWORD
        ];

        $this->json('GET', '/api/youtube-search/', $body)
         ->assertStatus(200);
    }

    // Validating that the Response JSON has the correct structure
    public function testReturnsDataInValidFormat() {

        $body = [
            'search' => self::EXAMPLE_KEYWORD,
            'results_per_page' => 5,
            "page_token" => "CAUQAA",
        ];

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
