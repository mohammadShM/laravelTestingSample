<?php

namespace Tests\Feature\Models;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserTest extends TestCase
{

    use WithFaker, RefreshDatabase, ModelHelperTesting;

    // for abstract in show model in trait ModelHelperTesting
    protected function model(): Model
    {
        return new User();
    }

    public function testInsertData(): void
    {
        $data = User::factory()->make()->toArray();
        $data['password'] = '123456';
        User::query()->create($data);
        $this->assertDatabaseHas('users', $data);
    }

    public function testUserRelationshipWithPost(): void
    {
        try {
            $count = random_int(1, 10);
            /** @noinspection PhpUndefinedMethodInspection */
            $user = User::factory()->hasPosts($count)->create();
            $this->assertCount($count, $user->posts);
            $this->assertInstanceOf(Post::class, $user->posts->first());
        } catch (Exception $e) {
            var_dump($e);
        }
    }

    public function testUserRelationshipWithComment(): void
    {
        try {
            $count = random_int(1, 10);
            /** @noinspection PhpUndefinedMethodInspection */
            $user = User::factory()->hasComments($count)->create();
            $this->assertCount($count, $user->comments);
            $this->assertInstanceOf(Comment::class, $user->comments->first());
        } catch (Exception $e) {
            var_dump($e);
        }
    }

}
