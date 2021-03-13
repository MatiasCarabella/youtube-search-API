<?php

namespace Tests\Feature;

use Tests\TestCase;

class YoutubeControllerTest extends TestCase
{

    // VALIDO QUE EL CONTACTO CON LA API EN CUESTIÓN DEVUELVA STATUS 200
    public function testHttpStatus200(){
        $exampleKeyword = "test";
        $this->json('get', '/api/youtubeSearch/'.$exampleKeyword)
         ->assertStatus(200);
    }
    
    // VALIDO QUE EL JSON OBTENIDO TENGA EL FORMATO ESPERADO
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
