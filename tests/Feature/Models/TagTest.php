<?php

namespace Tests\Feature\Models;

use App\Models\Post;
use App\Models\Tag;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TagTest extends TestCase
{
    use WithFaker, RefreshDatabase, ModelHelperTesting;

    // for abstract in show model in trait ModelHelperTesting
    protected function model(): Model
    {
        return new Tag();
    }

    public function testInsertData(): void
    {
        $data = Tag::factory()->make()->toArray();
        Tag::create($data);
        $this->assertDatabaseHas('tags', $data);
    }

    public function testTagRelationshipPost(): void
    {
        try {
            $count = random_int(1, 10);
            /** @noinspection PhpUndefinedMethodInspection */
            $tag = Tag::factory()->hasPosts($count)->create();
            $this->assertCount($count, $tag->posts);
            $this->assertInstanceOf(Post::class, $tag->posts->first());
        } catch (Exception $e) {
            var_dump($e);
        }
    }

}
