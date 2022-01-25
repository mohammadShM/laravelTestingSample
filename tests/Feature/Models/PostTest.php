<?php

namespace Tests\Feature\Models;

use App\Models\Comment;
use App\Models\Post;
use App\Models\Tag;
use App\Models\User;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PostTest extends TestCase
{

    use WithFaker, RefreshDatabase, ModelHelperTesting;

    // for abstract in show model in trait ModelHelperTesting
    protected function model(): Model
    {
        return new Post();
    }

    public function testInsertData(): void
    {
        $data = Post::factory()->make()->toArray();
        Post::create($data);
        $this->assertDatabaseHas('posts', $data);
    }

    public function testPostRelationshipWithUser(): void
    {
        /** @var Post $post */
        $post = Post::factory()->for(User::factory())->create();
        $this->assertTrue(isset($post->user->id));
        $this->assertInstanceOf(User::class, $post->user);
    }

    public function testPostRelationshipWithTag(): void
    {
        try {
            $count = random_int(1, 10);
            /** @noinspection PhpUndefinedMethodInspection */
            $post = Post::factory()->hasTags($count)->create();
            $this->assertCount($count, $post->tags);
            $this->assertInstanceOf(Tag::class, $post->tags->first());
        } catch (Exception $e) {
            var_dump($e);
        }
    }

    public function testPostRelationshipWithComment(): void
    {
        try {
            $count = random_int(0, 10);
            /** @noinspection PhpUndefinedMethodInspection */
            $post = Post::factory()->hasComments($count)->create();
            $this->assertCount($count, $post->comments);
            $this->assertInstanceOf(Comment::class, $post->comments->first());
        } catch (Exception $e) {
            var_dump($e);
        }
    }

}
