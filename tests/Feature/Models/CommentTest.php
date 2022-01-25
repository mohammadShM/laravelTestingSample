<?php

namespace Tests\Feature\Models;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CommentTest extends TestCase
{
    use WithFaker, RefreshDatabase, ModelHelperTesting;

    // for abstract in show model in trait ModelHelperTesting
    protected function model(): Model
    {
        return new Comment();
    }

    public function testCommentRelationshipWithPost(): void
    {
        /** @noinspection PhpUndefinedMethodInspection */
        $comments = Comment::factory()->hasCommentable(Post::factory())->create();
        $this->assertTrue(isset($comments->commentable->id));
        $this->assertInstanceOf(Post::class, $comments->commentable);
    }

    public function testCommentRelationshipWithUser(): void
    {
        /** @var Comment $comments */
        $comments = Comment::factory()->for(User::factory())->create();
        $this->assertTrue(isset($comments->user->id));
        $this->assertInstanceOf(User::class, $comments->user);
    }

}
