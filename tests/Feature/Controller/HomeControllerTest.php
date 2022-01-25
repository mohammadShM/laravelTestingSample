<?php

namespace Tests\Feature\Controller;

use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class HomeControllerTest extends TestCase
{

    use WithFaker, RefreshDatabase;

    public function testIndexMethod(): void
    {
        Post::factory()->count(100)->create();
        $response = $this->get(route('home'));
        $response->assertStatus(200);
        $response->assertViewIs('home');
        $response->assertViewHas('posts', Post::query()->latest()->paginate(15));
    }

}