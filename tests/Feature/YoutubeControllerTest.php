<?php

namespace Tests\Feature;

use Tests\TestCase;

class YoutubeControllerTest extends TestCase
{
    const EXAMPLE_KEYWORD = 'paradise';

    // Check for Http Status 200 - OK
    public function testHttpStatus200() {
        $this->json('GET', '/api/youtubeSearch/' . self::EXAMPLE_KEYWORD)
         ->assertStatus(200);
    }
    
    // Validating that the Response JSON has the correct structure
    public function testReturnsDataInValidFormat() {
        $this->json('GET', '/api/youtubeSearch/' . self::EXAMPLE_KEYWORD)
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
