<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\Http;
use Illuminate\Testing\Fluent\AssertableJson;

class YoutubeTest extends TestCase
{
    private const EXAMPLE_KEYWORD = 'paradise';
    private const YOUTUBE_API_URL = 'https://www.googleapis.com/youtube/v3/search*';
    private const MOCK_RESPONSE_PATH = './tests/Responses/YouTube/SearchSuccess.json';

    protected function setUp(): void
    {
        parent::setUp();
        
        // Ensure the mock response file exists
        if (!file_exists(self::MOCK_RESPONSE_PATH)) {
            throw new \RuntimeException('Mock response file not found: ' . self::MOCK_RESPONSE_PATH);
        }
    }

    private function getMockResponse(): array
    {
        return json_decode(file_get_contents(self::MOCK_RESPONSE_PATH), true);
    }

    public function testHttpStatus200()
    {
        // Arrange
        $body = ['search' => self::EXAMPLE_KEYWORD];
        
        // Mock the YouTube API response
        Http::fake([
            self::YOUTUBE_API_URL => Http::response($this->getMockResponse(), 200)
        ]);

        // Act & Assert
        $this->json('GET', '/api/youtube-search', $body)
            ->assertStatus(200);
    }

    public function testReturnsDataWithValidFormat()
    {
        // Arrange
        $body = [
            'search' => self::EXAMPLE_KEYWORD,
            'results_per_page' => 5,
            'page_token' => 'CAUQAA',
        ];

        // Mock the YouTube API response
        Http::fake([
            self::YOUTUBE_API_URL => Http::response($this->getMockResponse(), 200)
        ]);

        // Act & Assert
        $this->json('GET', '/api/youtube-search', $body)
            ->assertJson(fn (AssertableJson $json) =>
                $json->has('total_results')
                    ->has('results_per_page')
                    ->has('next_page_token')  // Added this line
                    ->has('videos', fn ($json) =>
                        $json->each(fn ($video) =>
                            $video->has('published_at')
                                ->has('id')
                                ->has('title')
                                ->has('description')
                                ->has('thumbnail')
                                ->has('extra.direct_link')
                                ->has('extra.channel_title')
                        )
                    )
            );
    }
}