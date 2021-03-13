<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use function PHPUnit\Framework\assertEquals;

class YoutubeControllerTest extends TestCase
{

    public function testHttpStatus200(){
        $exampleKeyword = "test";
        $this->json('get', '/api/youtubeSearch/'.$exampleKeyword)
         ->assertStatus(200);
    }
    
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
