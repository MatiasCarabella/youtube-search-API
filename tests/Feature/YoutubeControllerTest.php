<?php

namespace Tests\Feature;

use Tests\TestCase;

class YoutubeControllerTest extends TestCase
{
    // Check for Http Status 200 - OK
    public function testHttpStatus200(){
        $exampleKeyword = "test";
        $this->json('get', '/api/youtubeSearch/'.$exampleKeyword)
         ->assertStatus(200);
    }
    
    // Validating that the Response JSON has the correct structure
    public function testReturnsDataInValidFormat(){
        $exampleKeyword = "test";
        $this->json('get', '/api/youtubeSearch/'.$exampleKeyword)
         ->assertJsonStructure(
             [
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
