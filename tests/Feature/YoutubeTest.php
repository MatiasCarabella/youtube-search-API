<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\Http;
use Illuminate\Testing\Fluent\AssertableJson;

class YoutubeTest extends TestCase
{
    private const EXAMPLE_KEYWORD = 'paradise';
    private const YOUTUBE_API_URL = 'https://www.googleapis.com/youtube/v3/search*';
    private const MOCK_RESPONSE_PATH = 'tests/Responses/YouTube/SearchSuccess.json';
    private array $mockResponse;

    protected function setUp(): void
    {
        parent::setUp();
        $mockPath = base_path(self::MOCK_RESPONSE_PATH);
        if (!file_exists($mockPath)) {
            throw new \RuntimeException('Mock response file not found: ' . $mockPath);
        }
        $this->mockResponse = json_decode(file_get_contents($mockPath), true);
    }

    private function getMockResponse(): array
    {
        return $this->mockResponse;
    }

    public function testHttpStatus200()
    {
        $this->json('GET', '/ping')
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
                    ->has('next_page_token')
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

    public function testReturnsErrorForMissingSearchParameter()
    {
        // No 'search' parameter
        $body = [];
        $this->json('GET', '/api/youtube-search', $body)
            ->assertStatus(422)
            ->assertJson(fn (AssertableJson $json) =>
                $json->has('message')
                     ->has('errors')
            );
    }

    public function testHandlesYoutubeApiError()
    {
        $body = ['search' => self::EXAMPLE_KEYWORD];
        Http::fake([
            self::YOUTUBE_API_URL => Http::response(['error' => 'API error'], 500)
        ]);
        $this->json('GET', '/api/youtube-search', $body)
            ->assertStatus(500)
            ->assertJson(fn (AssertableJson $json) =>
                $json->has('error')
            );
    }
}